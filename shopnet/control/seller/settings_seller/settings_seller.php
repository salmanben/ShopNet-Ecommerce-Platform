<?php
include "../../../connect_DB.php";
include('../layout/aside.php');
include('../layout/header.php');
if (!isset($_SESSION['id'])) {
  header("location:../../../auth/login/login.php");
  exit;
}
if ($_SESSION['role'] !== 'seller') {
  http_response_code(404);
  exit;
}
$sql = "select * from seller where id ='$id'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);



?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/settings_seller.css">
  <link rel="stylesheet" href="../styles/layout.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Settings</title>
  <link rel="icon" href="../../../icon.png">
  <script defer src="../scripts/settings_seller.js"></script>
</head>

<body>
  <div class="wrapper">
    <?php echo $aside ?>
    <div class="container">
      <?php echo $header ?>
      <div class="content">
        <h2>Settings</h2>
        <div class="form">
          <div class="user-img">
            <img class="" src="../../../upload/<?php echo $row['image'] ?>" alt="image">
          </div>
          <p class="message"></p>
          <form action="" onsubmit="submitForm(event)" enctype="multipart/form-data">
            <div class="container-one">
              <div>
                <i class="fa-solid fa-user"></i>
                <input class="username" value="<?php echo $row['username'] ?>" type="text" name="username" placeholder="Username" id="">
              </div>
              <div>
                <i class="fa-solid fa-envelope"></i>
                <input class="email" value="<?php echo $row['email'] ?>" type="email" name="email" placeholder="Email" id="">
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
                <input class="img-file" type="file" name="file" id="">
              </div>

            </div>
            <div class="container-two">
              <div>
                <i class="fa-solid fa-envelope"></i>
                <input class="P_email" value="<?php echo $row['paypal_email'] ?>" type="email" name="P_email" id="PayPal" placeholder="PayPal email">
              </div>
              <div class="file">
                <i class="fa-solid fa-image"></i>
                <input class="banner-file" type="file" name="banner" id="">
              </div>
             <div>
                <img class="banner" src="../../../upload/<?php echo $row['banner'] ?>" alt="banner">
             </div>
              <button class="signup-btn">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>