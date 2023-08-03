<?php
 require_once "database.php";

 $conn = mysqli_connect($servername, $dbusername ,$dbpassword , $dbname );


// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  
  $username = test_input($_POST["username"]);
  $email = test_input($_POST["email"]);
  $password = test_input($_POST["password"]);
  $phone = test_input($_POST["tel"]);

  // Validate input fields
  $passwordErr = "";
  if (empty($password) || strlen($password) < 8 || strlen($password) > 14) {
    $passwordErr = "Your password must be between 8 and 14 characters long.";
  } elseif (!preg_match("/[0-9]/", $password)) {
    $passwordErr = "Your password must contain at least one number.";
  } elseif (!preg_match("/[A-Z]/", $password)) {
    $passwordErr = "Your password must contain at least one uppercase letter.";
  } elseif (!preg_match("/[a-z]/", $password)) {
    $passwordErr = "Your password must contain at least one lowercase letter.";
  } elseif (!preg_match("/[^a-zA-Z0-9]/", $password)) {
    $passwordErr = "Your password must contain at least one special character.";
  }

  if (!empty($passwordErr)) {
    
    echo $passwordErr;
  } elseif (empty($password) || empty($username) || empty($email)|| empty($phone)) {
   
    echo "Please fill in all required fields.";
  } elseif (!isset($_POST["chek"])) {
 
    echo "Please accept our terms of service.";
  } else {
   

    // Prepare and execute the statement
    $stmt = mysqli_prepare($conn, "INSERT INTO user_info (user_name, email, user_password, phone_number) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $username, $email, $password, $phone);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
   
      header("Location: signup_success.php");
      exit();
    } else {
  
      echo "Error: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
  }
}

mysqli_close($conn);


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
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
        <img src="../img/sign-up.png" alt="" >
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <h1>signup</h1>
        <p>welcome to expense community </p>
        <p>signup with <a href="#google">google</a></p></Br>
        <p>__________________or___________________</p>
        
            <label for="username">Username<span>*</span></label>
            <input class="input" type="text" name="username" id="username" required>
      
      
           <label for="email">Email Address<span>*</span></label>
            <input class="input" type="email" name="email" id="email" required>
      
      
            <label for="password">Password<span>*</span></label>
            <input class="input" type="password" name="password" id="password" required>
      
      
            <label for="tel">Phone Number<span>*</span></label>
            <input class="input" type="tel" name="tel" id="tel" required>
      
        
            <div class="terms">
                <input type="checkbox" name="chek" id="terms" required>
                <label for="terms">I agree to the <a href="#">Terms and Conditions</a></label>
            </div>
            <div class="btn"><button class="btn1" type="submit" name="submit" value="log in">sign up</button></div>
            <p>already have an account <a href="login.php"> log in</a></p>
        </form>
    </main>
 
</body>
</html>