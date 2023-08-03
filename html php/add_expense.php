<?php
session_start();
//اضافة مصاريف جديده لكل فئة 
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

if (!isset($_GET["category_id"])) {
  header("Location: categories.php");
  exit();
}
if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
  }
$category_id = $_GET["category_id"];
$user_id = $_SESSION["user_id"];

require_once "database.php";

$conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT category_name, category_cost FROM categories_info WHERE category_id = $category_id";
$result = mysqli_query($conn, $sql);

$category_name = "";
$category_amount = 0;

if ($row = mysqli_fetch_assoc($result)) {
  $category_name = $row["category_name"];
  $category_amount = $row["category_cost"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $amount = $_POST["amount"];
  $date = $_POST["date"];
  $comments = $_POST["comments"];
  $payment_method = $_POST["payment_method"];
  $name = $_POST["name"];

  if ($amount > $category_amount) {
    echo "Error: Amount exceeds category amount.";
  } else {
    mysqli_autocommit($conn, false); 
    mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE); 

    try {
  
      $stmt = $conn->prepare("INSERT INTO expenses_info (ex_date, amount, comments, payment_method, category_id, ex_name) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sdssss", $date, $amount, $comments, $payment_method, $category_id, $name);
      $stmt->execute();
      $stmt->close();
    
    
      $new_amount = $category_amount - $amount;
      $update_sql = "UPDATE categories_info SET category_cost = $new_amount WHERE category_id = $category_id";
      mysqli_query($conn, $update_sql);
    
      mysqli_commit($conn); 
    
      header("Location: categories.php");
      exit();
    } catch (Exception $e) {
      mysqli_rollback($conn); 
      echo "Error: " . $e->getMessage();
    }
    
    mysqli_autocommit($conn, true); 

   
  }
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

  
   
    <main class="main">
    <div class="image">
      <img src="../img/budget.png" alt="">
    </div>
      <form method="post">
      <a href="categories.php" >
      Back to Categories
    </a>
        <h5>Add a new expense for <?php echo $category_name; ?></h5>
        <h5>Category amount: <?php echo $category_amount; ?></h5>
        <p>____________________________________</p>
        <h4>Name</h4>
        <input type="text" name="name" placeholder="Name" class="input">
        <h4>Date</h4>
        <input type="date" name="date" placeholder="Date" class="input">
        <h4>Amount</h4>
        <input type="text" name="amount" placeholder="Amount" class="input">
        <h4>Comments</h4>
        <textarea name="comments" placeholder="Comments" class="input"></textarea>
        <h4>Payment Method</h4>
        <select name="payment_method" class="input">
          <option value="check">Check</option>
          <option value="card">Card</option>
          <option value="cash">Cash</option>
        </select>
        <div class="btn">
          <button class="btn1" type="submit" name="submit" value="add expense">Add Expense</button>
        </div>
      </form>
    </main>

</body>
</html>