<?php
session_start();

if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}
if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
  }
require_once "database.php";

$conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $type = $_POST["category_name"];
  $cost = $_POST["category_cost"];
  
  
  $stmt = $conn->prepare("INSERT INTO categories_info (category_name, category_cost, user_id) VALUES (?, ?, ?)");
  $stmt->bind_param("sdi", $type, $cost, $user_id);

 
  if ($stmt->execute() === TRUE) {
   
    header("Location: categories.php");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }

  
  $stmt->close();
}


$sql = "SELECT category_name, category_cost, category_id FROM categories_info WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);


?>
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
          <a href="reviews.php" class="navbarlink" id="aboutpage">Our Reviews</a>
        </li>
        <li class="navbaritm"> 
          <a href="aboutus.html" class="navbarlink" id="servicespage">About Us</a>
        </li>
        <li class="navbaritm"> 
          <a href="transfers.php" class="navbarlink" id="servicespage"> transfers</a>
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
      <h5>Fill in the blanks if you want to add a category</h5>
      <p>____________________________________</p>
      <h4>Type</h4>
      <input type="text" name="category_name" placeholder="Name" class="input">
      <h4>Cost</h4>
      <input type="text" name="category_cost" placeholder="Cost" class="input">
      <div class="btn">
        <button class="btn1" type="submit" name="submit" value="add category">Add Category</button>
      </div>
    </form>
    
    <div class="table-container">
  <h2>Your Categories</h2>
  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>Cost</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <tr>
        <td><?php echo $row["category_name"]; ?></td>
        <td><?php echo $row["category_cost"]; ?></td>
        <td>
          <a href="add_expense.php?id=<?php echo $row["category_id"]; ?>" class="btn-add-expense">
            <i class="fa fa-plus"></i> Add Expense
          </a>
          <a href="expenses.php?category_id=<?php echo $row["category_id"]; ?>" class="btn-view-expenses">
            <i class="fa fa-eye"></i> View Expenses
          </a>
        </td>
      </tr>
    <?php } ?>
    </tbody>
  </table>
</div>
    
  </main>
</body>
</html>