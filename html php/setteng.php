<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit();
}

if (isset($_GET['username'])) {
  $username = $_GET['username'];
} else {
  $username = $_SESSION["username"];
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
          <a href="aboutus.html" class="navbarlink" id="servicespage"> setting </a>
        </li>
        <li class="navbaritm"> 
          <h4 class="navbarlink" id="servicespage"> <?php echo $username; ?></h4>   
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
    
      <a href=" edituser.php"><button type="text" name=" edituser" placeholder=" edituser" class="input"> edit user info</button> </a>
      
     <a href="editcategory"><button type="edit cost in category" name="edit cost in category" placeholder="edit cost in category" class="input">  edit cost in category</button></a> 
    </div>
  </main>
</body>
</html>