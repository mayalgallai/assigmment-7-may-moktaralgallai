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

$conn = mysqli_connect($servername, $dbusername ,$dbpassword , $dbname );
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["submit"])) {
  $new_username = $_POST["username"];
  $new_email = $_POST["email"];
  
 
  $user_id = $_SESSION["user_id"];
  $update_query = "UPDATE user_info SET user_name='$new_username', email='$new_email' WHERE user_id=$user_id";
  if ($conn->query($update_query) === TRUE) {

    $_SESSION["username"] = $new_username;
    $_SESSION["email"] = $new_email;


    header("Location: home.php");
    exit();
  } else {
    echo "Error updating record: " . $conn->error;
  }
}


$user_id = $_SESSION["user_id"];
$select_query = "SELECT user_name, email FROM user_info WHERE user_id=$user_id";
$result = $conn->query($select_query);
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $username = $row["user_name"];
  $email = $row["email"];
} else {
  echo "Error getting user data: " . $conn->error;
}
if (isset($_POST["logout"])) {
    session_destroy();
    header("Location: login.php");
    exit();
  }
?>
<html>
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../css/home.css">
</head>
<body>
<body>
  <div class="navbar">
  <div class="navbarcon">
      <a href="home_with_user_info.php" id="navbarlogo"> EXPENSE TRUCKER </a>
      <div class="navbartoggle" id="mobilemenu"></div>
      <ul class="navbarmenu">
        <li class="navbaritm">
          <input type="serch"  class="serch" placeholder="search" name="serch">
        </li>
           
        <li class="navbaritm"> 
          <a href="aboutus.html" class="navbarlink" id="servicespage"> about us</a>
        </li>
        <li class="navbaritm"> 
          <a class="navbarlink" id="addcategory" href="addcategory.php?username=<?php echo $username; ?>">add category</a>
        </li>
        <li class="navbaritm"> 
          <a href="setteng.php" class="navbarlink" id="servicespage"> setting </a>
        </li>
        <li class="navbaritm"> 
          <h4 class="navbarlink" id="servicespage">WELCOME <?php echo $username; ?></h4>   
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
      <img src="../img/pngegg.png" alt="">
    </div>
   
    
    <div class="txt">
<form method="post">
 <h4>username</h4>
  <input class="input" type="text" name="username" value="<?php echo $username; ?>">
  
  <h4>email</h4>
  <input  class="input" type="email" name="email" value="<?php echo $email; ?>">
  
  <div class="btn"><button class="btn1" type="submit" name="submit" value="savechnge">save chnge</button></div>

</form>

<?php

$conn->close();
?>
 </div>
  </main>
</body>
</html>