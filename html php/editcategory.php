
<?php

session_start();

if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}
require_once "database.php";

$conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION["user_id"];
$category_name = $_GET["category_name"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $new_name = $_POST["new_name"];
  $new_cost = $_POST["new_cost"];
  
  $stmt = $conn->prepare("UPDATE categories_info SET category_name=?, category_cost=? WHERE user_id=? AND category_name=?");
  $stmt->bind_param("sdss", $new_name, $new_cost, $user_id, $category_name);

  if ($stmt->execute() === TRUE) {
    header("Location: categories.php");
    exit();
  } else {
    echo "Error: " . $stmt->error;
  }

  $stmt->close();
}
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
    <form method="post">
      <h2>Edit Category</h2>
      <h4>Name</h4>
      <input type="text" name="new_name" placeholder="New Name" class="input">
      <h4>Cost</h4>
      <input type="text" name="new_cost" placeholder="New Cost" class="input">
      <div class="btn">
        <button class="btn1" type="submit" name="submit" value="edit category">Save Changes</button>
      </div>
    </form>
  </main>
</body>
</html>