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
$userSql = "SELECT * FROM user_info WHERE user_id = $user_id";
$userResult = mysqli_query($conn, $userSql);
$user = mysqli_fetch_assoc($userResult);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $sourceCategory = $_POST["source_category"];
  $destinationCategory = $_POST["destination_category"];
  $transferAmount = $_POST["transfer_amount"];
  $transferComment = $_POST["transfer_comment"];
  $transferDate = date("Y-m-d");

  $balanceSql = "SELECT category_cost FROM categories_info WHERE category_id = $sourceCategory";
  $balanceResult = mysqli_query($conn, $balanceSql);
  $currentBalance = mysqli_fetch_assoc($balanceResult)["category_cost"];

  if ($transferAmount > $currentBalance) {
    echo "Error: The transfer amount cannot exceed the current balance in the source category.";
  } else {
    $updateSql1 = "UPDATE categories_info SET category_cost = category_cost - $transferAmount WHERE category_id = $sourceCategory AND category_cost >= $transferAmount";
    $updateSql2 = "UPDATE categories_info SET category_cost = category_cost + $transferAmount WHERE category_id = $destinationCategory";
    $updateSql3 = "INSERT INTO transfers (source_category_id, destination_category_id, amount, comment, t_date) VALUES (?, ?, ?, ?, ?)";
    
    try {
      $stmt = $conn->prepare($updateSql3);
      $stmt->bind_param("iidss", $sourceCategory, $destinationCategory, $transferAmount, $transferComment, $transferDate);

      if ($conn->query($updateSql1) === TRUE && $conn->query($updateSql2) === TRUE && $stmt->execute() === TRUE) {
        header("Location: categories.php");
        exit();
      } else {
        echo "Error: " . $conn->error;
      }
      $stmt->close();
      mysqli_free_result($balanceResult);
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Transfer</title>
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
  <main class="main ">
  <div class="container">
    <h1>Transfer</h1>
    <form method="post">
      <div>
      <label for="source_category">Source Category:</label>
<select id="source_category" name="source_category" required class="input">
  <?php
    $categoriesSql = "SELECT * FROM categories_info WHERE user_id = $user_id";
    $categoriesResult = mysqli_query($conn, $categoriesSql);

    while ($row = mysqli_fetch_assoc($categoriesResult)) {
      if ($row["category_id"] != $destinationCategory) {
        echo "<option value='" . $row["category_id"]. "'>" . $row["category_name"] . "</option>";
      }
    }
    mysqli_free_result($categoriesResult);
  ?>
</select>
</div>
<div>
<label for="destination_category">Destination Category:</label>
<select id="destination_category" name="destination_category" required class="input">
  <?php
    $categoriesSql = "SELECT * FROM categories_info WHERE user_id = $user_id";
    $categoriesResult = mysqli_query($conn, $categoriesSql);

    while ($row = mysqli_fetch_assoc($categoriesResult)) {
      if ($row["category_id"] != $sourceCategory) {
        echo "<option value='" . $row["category_id"]. "'>" . $row["category_name"] . "</option>";
      }
    }
    mysqli_free_result($categoriesResult);
  ?>
</select>
</div>
<div>
<label for="transfer_amount">Transfer Amount:</label>
<input type="number" id="transfer_amount" name="transfer_amount" min="0.01" step="0.01" required class="input">
</div>
<div>
<label for="transfer_comment">Comment:</label>
<input type="text" id="transfer_comment" name="transfer_comment" class="input">
</div>
<button type="submit" class="button">Transfer</button>
</form>
</div>
</main>
</body>
</html>