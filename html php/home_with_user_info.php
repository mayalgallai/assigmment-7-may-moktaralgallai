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
      <a href="home.php" id="navbarlogo"> EXPENSE TRUCKER </a>
      <div class="navbartoggle" id="mobilemenu"></div>
      <ul class="navbarmenu">
        <li class="navbaritm">
          <input type="serch"  class="serch" placeholder="search" name="serch">
        </li>
           
        <li class="navbaritm"> 
          <a href="aboutus.html" class="navbarlink" id="servicespage"> about us</a>
        </li>
        <li class="navbaritm"> 
          <a class="navbarlink" id="addcategory" href="categories.php?username=<?php echo $username; ?>">add category</a>
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
      <h1>WELCOME <?php echo $username; ?></h1>

      <a href=""><img src="../img/facebook.png" alt="" height="20px"></a>
      <a href=""><img src="../img/instagram.png" alt="" height="20px"></a>
      <a href=""><img src="../img/linkedin.png" alt="" height="20px"></a>
      <a href=""><img src="../img/telegram.png" alt=""height="20px"></a>
      <a href=""><img src="../img/whatsapp.png" alt=""height="20px"></a>
      <a href=""><img src="../img/youtube.png" alt="" height="20px"></a>
    </div>
  </main>
</body>
</html>