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
  <link rel="stylesheet" href="signup.css">
  <script defer src="signup.js"></script>
  <link rel="icon" href="../../icon.png">
  <title>Sign Up</title>
</head>

<body>
  <div class="form">
    <p class="error"></p>
    <i class="fa-sharp fa-solid fa-right-long"></i>
    <h1>
      <a href="../../user/home/index.php">
        <img src="../../icon.png" alt="logo"> Shop <span>Net</span>
      </a>
    </h1>
    <form action="" onsubmit="submit_form(event)" enctype="multipart/form-data">
      <div class="container1">
        <div class="choice">
          <button type="button" onclick="switchToBuyer()">Buyer</button>
          <button type="button" onclick="switchToSeller()">Seller</button>
          <div class="background"></div>
        </div>
        <div>
          <i class="fa-solid fa-user"></i>
          <input type="text" name="username" placeholder="Username" id="">
        </div>
        <div>
          <i class="fa-solid fa-envelope"></i>
          <input type="email" name="email" placeholder="Email" id="">
        </div>
        <div>
          <i class="fa-solid fa-lock"></i>
          <i onclick="showPssword()" class="fa-solid fa-eye"></i>
          <input type="password" name="password" placeholder="Password" id="">
        </div>
        <div>
          <i class="fa-solid fa-lock"></i>
          <i onclick="showPssword()" class="fa-solid fa-eye"></i>
          <input type="password" name="Re-password" placeholder="Re-type Password" id="">
        </div>
        <div class="file">
          <i class="fa-solid fa-image"></i>
          <input type="file" name="file" id="">
        </div>
      </div>
      <div class="container2">
        <div>
          <label for="PayPal">PayPal</label>
          <input type="email" name="P_email" id="PayPal" placeholder="PayPal email">
        </div>
        <div>
          <label for="banner">Banner</label>
          <input type="file" name="banner" id="banner">
        </div>
      </div>
      <button class="signup">Sign Up</button>
    </form>
    <p class="login">You already have an account? <a href="../login/login.php">Log In</a>
    </p>
  </div>
</body>

</html>