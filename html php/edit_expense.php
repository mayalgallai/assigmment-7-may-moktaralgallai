<?php
session_start();
// هذه صفحة تقوم ب تعديل  داخل جدول المصاريف
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

if (!isset($_GET["expense_id"])) {
  header("Location: expenses.php");
  exit();
}

$expense_id = $_GET["expense_id"];
$user_id = $_SESSION["user_id"];

require_once "database.php";

$conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
  $ex_name = $_POST["ex_name"];
  $ex_date = $_POST["ex_date"];
  $amount = $_POST["amount"];
  $comments = $_POST["comments"];
  $payment_method = $_POST["payment_method"];
  
  $stmt = $conn->prepare("UPDATE expenses_info SET ex_name = ?, ex_date = ?, amount = ?, comments = ?, payment_method = ? WHERE expense_id = ?");
$stmt->bind_param("ssdssi", $ex_name, $ex_date, $amount, $comments, $payment_method, $expense_id);
$stmt->execute();
  if ($stmt->execute() === TRUE) {
    // Expense updated successfully
    header("Location: expenses.php?category_id=$category_id");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }
  $stmt->close();
}

$sql = "SELECT ex_name, ex_date, amount, comments, payment_method FROM expenses_info WHERE expense_id = $expense_id";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
  $ex_name = $row["ex_name"];
  $ex_date = $row["ex_date"];
  $amount = $row["amount"];
  $comments = $row["comments"];
  $payment_method = $row["payment_method"];
} else {
  // Expense not found
  header("Location: expenses.php");
  exit();
}
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

  </header>

  <div class="container">
    <a href="expenses.php?category_id=<?php echo $category_id; ?>" class="btn-back">
      <i class="fa fa-chevron-left"></i> Back to Expenses
    </a>

    <main class="main">
      <h2>Edit Expense</h2>
      <form method="post">
        <div class="form-group">
          <label for="ex_name">Name</label>
          <input  class="input" type="text" id="ex_name" name="ex_name" value="<?php echo $ex_name; ?>" required>
        </div>
        <div class="form-group">
          <label for="ex_date">Date</label>
          <input  class="input" type="date" id="ex_date" name="ex_date" value="<?php echo $ex_date; ?>" required>
        </div>
        <div class="form-group">
          <label for="amount">Amount</label>
          <input  class="input" type="number" id="amount" name="amount" value="<?php echo $amount; ?>" required>
        </div>
        <div class="form-group">
          <label for="comments">Comments</label>
          <textarea  class="input" id="comments" name="comments"><?php echo $comments; ?></textarea>
        </div>
        <div class="form-group">
          <label for="payment_method">Payment Method</label>
          
          <select name="payment_method" class="input">
          <option value="check">Check</option>
          <option value="card">Card</option>
          <option value="cash">Cash</option>
        </select>
          </select>
        </div>
        <button class="btn" type="submit" name="submit">Save Changes</button>
      </form>
    </main>
  </div>
</body>
</html>

<?php
mysqli_close($conn);
?>