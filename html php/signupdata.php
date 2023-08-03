<?php//--may moktar algallai 6/11/2023 هذه صفحة تعرض بيانات المسجله 
?>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="../css/home.css">
       
    </head>
    <body>
        <div class="navbar">
            <div class="navbarcon">
                      <a href="home.html" id="navbarlogo"> EXPENSE TRUCKER </a>
                      <div class="navbartoggle" id="mobilemenu">
  </div>
  <ul class="navbarmenu">
     
            <li class="navbaritm"> 
                      <a href="" class="navbarlink" id="homepage">features </a>
            </li>
            <li class="navbaritm"> 
                      <a href="#" class="navbarlink" id="aboutpage"> our reviews</a>
            </li>
            <li class="navbaritm"> 
                      <a href="aboutus.html" class="navbarlink" id="servicespage"> about us</a>
            </li>   
  </ul>
  </div>
</div> 
<main  class="main">
    <div class="image">
        <img src="../img/budget.png" alt="">
    </div>
    <?php
$name = $_GET['name'];
$email = $_GET['email'];
$username=$_GET['username'];

echo "full name...$name<br>";
echo "user name...$username<br>";
echo" email...$email<br> ";
?>
</main>
</body>
</html>