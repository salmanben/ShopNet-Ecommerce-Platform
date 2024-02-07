<?php
session_start();
if (isset($_SESSION['id'])) {
  if ($_SESSION['role'] == 'seller')
    header("location:../../control/seller/dashboard/dashboard.php");
  else  if ($_SESSION['role'] == 'buyer')
    header("location:../../user/home/index.php");
  else
    header("location:../../control/admin/dashboard/dashboard.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="Login.css">
  <script defer src="Login.js"></script>
  <link rel="icon" href="../../icon.png">
  <title>Log In</title>
</head>

<body>
  <div class="form">
    <h1>
      <a href="../../user/home/index.php">
        <img src="../../icon.png" alt="logo"> Shop<span>Net</span>
      </a>
    </h1>
    <div class="choice">
      <button>Buyer</button>
      <button>Seller</button>
      <div class="background"></div>
    </div>
    <p class="error"></p>
    <form>
      <div>
        <i class="fa-solid fa-envelope"></i>
        <input type="email" name="email" placeholder="Email" id="">
      </div>
      <div>
        <i class="fa-solid fa-lock"></i>
        <i class="fa-solid fa-eye" onclick="showPssword()"></i>
        <input type="password" name="password" placeholder="Password" id="">
      </div>
      <button>Log In</button>
      <p class="forgot">forgot password?</p>
      <p class="signup">You don't have an account? <a href="../signup/signup.php">Sign Up</a></p>
    </form>
  </div>
</body>

</html>