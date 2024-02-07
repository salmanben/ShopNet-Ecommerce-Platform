<?php
include "../../connect_DB.php";
include '../layout/layout.php';
if (!isset($_SESSION['id'])) {
  header("location:../../auth/login/login.php");
  exit;
}
if ($_SESSION['role'] !== 'buyer') {
  http_response_code(404);
  exit;
}


$id = $_SESSION['id'];
$sql = "SELECT username, email from buyer where id = '$id'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="settings_buyer.css">
  <link rel="stylesheet" href="../layout/layout.css">
  <link rel="icon" href="../../icon.png">
  <script defer src="settings_buyer.js"></script>
  <script defer src="../layout/layout.js"></script>
  <title>Settings</title>
</head>

<body>
  <?php echo $header ?>
  <div class="container">
    <div class="form">
      <h2>
        <a href="../home/index.php">
          <img src="../../icon.png" alt="logo"> Shop<span>Net</span>
        </a>
      </h2>
      <p class="error"></p>
      <form>
        <div>
          <i class="fa-solid fa-user"></i>
          <input type="text" value="<?php echo $row['username'] ?>" name="username" placeholder=" New Username" id="">
        </div>
        <div>
          <i class="fa-solid fa-envelope"></i>
          <input type="email" value="<?php echo $row['email'] ?>" name="email" placeholder=" New Email" id="">
        </div>
        <div>
          <i class="fa-solid fa-lock"></i>
          <i class="fa-solid fa-eye" onclick="showPssword()"></i>
          <input type="password" name="password" placeholder=" New Password" id="">
        </div>
        <div>
          <i class="fa-solid fa-lock"></i>
          <i class="fa-solid fa-eye" onclick="showPssword()"></i>
          <input type="password" name="Re-password" placeholder="Re-type Password" id="">
        </div>
        <button type="submit">Save</button>
      </form>
    </div>
  </div>
  <?php echo $footer ?>
</body>

</html>