<?php
//--may moktar algallai 6/11/2023 هذه هيا صفحة تسجيل الدخول 
function checkEmpty($string) {
  if (empty($string)) {
    return true;
  } else {
    return false;
  }
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
      <a href="home.html" id="navbarlogo"> EXPENSE TRUCKER </a>
      <div class="navbartoggle" id="mobilemenu"></div>
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

  <main class="main">
    <div class="image">
      <img src="../img/log-in.png" alt="">
    </div>
    
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="form">
      <h1>login</h1>
      <?php if (isset($error)) { echo $error; } ?>
      <p>welcome to expense community </p>
      <p>log in with <a href="#google">google</a></p>
      <p>__________________or___________________</p>
      <h4>username</h4>
      <input type="text" name="username" placeholder="username" class="input">
      <h4>password</h4>
      <input type="password" name="password" placeholder="password" class="input" >
      <a href="#forgat"><p>forgat password?</p></a>
      <div class="btn"><button class="btn1" type="submit" name="submit" value="log in">login</button></div>
      <p>Don't have account? <a href="signup.php">sign up</a></p>
    </form>
    
    <?php
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
     
      $username = test_input($_POST["username"]);
      $password = test_input($_POST["password"]);

      // Connect to the database
      require_once "database.php";

      $conn = mysqli_connect($servername, $dbusername ,$dbpassword , $dbname );

      // Check connection
      if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
      }

     
      $sql = "SELECT user_id, user_name FROM user_info WHERE user_name='$username' AND user_password='$password'";
      $result = mysqli_query($conn, $sql);

      if ($result) {
        if (mysqli_num_rows($result) == 1) {
    
          $row = mysqli_fetch_assoc($result);
          $user_id = $row['user_id'];

        
          session_start();
          $_SESSION["user_id"] = $user_id;
          $_SESSION["username"] = $username;
          header("Location: home_with_user_info.php");
          exit();
        } else {
          
          $error = "<p class='error'>Invalid login credentials. Please try again.</p>";
        }
      } else {
    
        $error = "<p class='error'>An error occurred while logging in. Please try again.</p>";
      }

    
      mysqli_close($conn);
    }

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    ?>
  </main>
</body>
</html>