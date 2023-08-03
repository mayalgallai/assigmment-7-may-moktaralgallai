<?php
session_start();
// تعرض المصاريف مع امكانيه الحذف وتعديل داخل هذه المصاريف
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

if (!isset($_GET["category_id"])) {
  header("Location: categories.php");
  exit();
}

$category_id = $_GET["category_id"];
$user_id = $_SESSION["user_id"];

require_once "database.php";

$conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
  $expense_id = $_POST["expense_id"];
  $stmt = $conn->prepare("DELETE FROM expenses_info WHERE expense_id = ? AND category_id = ?");
  $stmt->bind_param("ii", $expense_id, $category_id);
  if ($stmt->execute() === TRUE) {
   
    $sql = "SELECT SUM(amount) as total_cost FROM expenses_info WHERE category_id = $category_id";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
      $total_cost = $row["total_cost"];
    }
    header("Location: expenses.php?category_id=$category_id");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }
  $stmt->close();
}

$sql = "SELECT c.category_name, SUM(e.amount) AS total_cost
        FROM expenses_info e
        JOIN categories_info c ON e.category_id = c.category_id
        WHERE e.category_id = ?
        GROUP BY c.category_name";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

$category_name = "";
$total_cost = 0;

if ($row = mysqli_fetch_assoc($result)) {
  $category_name = $row["category_name"];
  $total_cost = $row["total_cost"];
}

$sql = "SELECT expense_id, ex_name, ex_date, amount, comments, payment_method
        FROM expenses_info
        WHERE category_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="../css/home.css">
       
    </head>
<body>
<div class="navbar">
  <div class="navbarcon">
      <a href="home_with_user_info.php" id="navbarlogo"> EXPENSE TRUCKER </a>
      <div class="navbartoggle" id="mobilemenu"></div>
      <ul class="navbarmenu">
        <li class="navbaritm"> 
          <a href="" class="navbarlink" id="homepage">Features</a>
        </li>
        <li class="navbaritm"> 
          <a href="#" class="navbarlink" id="aboutpage">Our Reviews</a>
        </li>
        <li class="navbaritm"> 
          <a href="aboutus.html" class="navbarlink" id="servicespage">About Us</a>
        </li>
        <li class="navbaritm"> 
        </li>
        <li class="navbaritm"> 
          <h4 class="navbarlink" id="servicespage"><?php echo $_SESSION["username"]; ?></h4>   
        </li>
        <li class="navbaritm"> 
          <form method="post">
            <button type="submit" name="logout"  class="button" id="servicespage">  logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div> 

  <div class="container">
    

    <main class="main">
    <form method="post">
      <a href="categories.php" >
      Back to Categories
    </a>
      <h2>Expenses for <?php echo $category_name; ?></h2>
      <p>Total Cost: <?php echo $total_cost; ?></p>
      <table>
        <thead>
          <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Comments</th>
            <th>Payment Method</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
  <tr>
    <td><?php echo $row["ex_name"]; ?></td>
    <td><?php echo $row["ex_date"]; ?></td>
    <td><?php echo $row["amount"]; ?></td>
    <td><?php echo $row["comments"]; ?></td>
    <td><?php echo $row["payment_method"]; ?></td>
    <td>
  <form method="post" >
    <input type="hidden" name="expense_id" value="<?php echo $row["expense_id"]; ?>">
    <button type="submit" name="delete" class="btn-delete">delete</button>
  </form>
</td>
<td>
  <a href="edit_expense.php?category_id=<?php echo $category_id; ?>&expense_id=<?php echo $row["expense_id"]; ?>" class="btn-add"></i>edit</a>
</td>
  </tr>
<?php } ?>
        </tbody>
      </table>
    </main>
  </div>
</body>
</html>