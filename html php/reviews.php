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

try {
  $conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);
  if (!$conn) {
    throw new Exception("Failed to connect to database: " . mysqli_connect_error());
  }
} catch (Exception $e) {
  die("Error: " . $e->getMessage());
}

$user_id = $_SESSION["user_id"];
if (isset($_POST["submit_rating"])) {
  $rating = $_POST["rating"];
  $comment = $_POST["comment"];
  $check_query = "SELECT * FROM ratings WHERE user_id = '$user_id'";
  $check_result = mysqli_query($conn, $check_query);
  if (mysqli_num_rows($check_result) > 0) {
    $error_message = "You already submitted a rating.";
  } else {
    $sql = "INSERT INTO ratings (user_id, comment, rating) VALUES ('$user_id', '$comment', '$rating')";
    if (mysqli_query($conn, $sql)) {
      $success_message = "Rating submitted successfully.";
    } else {
      $error_message = "Error submitting rating: " . mysqli_error($conn);
    }
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
      <a href="home_with_user_info.php" id="navbarlogo"> EXPENSE TRUCKER </a>
      <div class="navbartoggle" id="mobilemenu"></div>
      <ul class="navbarmenu">
        <li class="navbaritm"> 
          <a href="" class="navbarlink" id="homepage">Features</a>
        </li>
        <li class="navbaritm"> 
          <a href="aboutus.html" class="navbarlink" id="servicespage">About Us</a>
        </li>
        <li class="navbaritm"> 
          <a href="transfers.php" class="navbarlink" id="servicespage">transfers</a>
        </li>
        <li class="navbaritm"> 
        </li>
        <li class="navbaritm"> 
          <h4 class="navbarlink" id="servicespage"><?php echo $_SESSION["username"]; ?></h4>   
        </li>
        <li class="navbaritm"> 
          <form method="post">
            <button type="submit" name="logout"  class="button" id="servicespage">logout</button>
          </form>
        </li>
      </ul>
    </div>
  </div> 
  <main class="main">
    <div class="image">
      <img src="../img/budget.png" alt="">
    </div>
    <div class="rating-form">
      <form method="post">
        <?php if (isset($error_message)) { ?>
          <p class="error-message"><?php echo $error_message; ?></p>
        <?php } else if (isset($success_message)) { ?>
          <p class="success-message"><?php echo $success_message; ?></p>
        <?php } ?>
        <label for="rating">Rate our app:</label>
        <select name="rating" id="rating" class="input">
          <option value="1">1 star</option>
          <option value="2">2 stars</option>
          <option value="3">3 stars</option>
          <option value="4">4 stars</option>
          <option value="5">5 stars</option>
        </select>
        <label for="comment">Comment:</label>
        <textarea name="comment" id="comment" class="input"></textarea>
        <button type="submit" name="submit_rating" class="btn">Submit</button>
      </form>
    </div>
    
  </main>
</body>
</html>