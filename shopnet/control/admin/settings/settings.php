<?php
include "../../../connect_DB.php";
include('../layout/aside.php');
include('../layout/header.php');
if (!isset($_SESSION['id'])) {
  header("location:../login/login.php");
  exit;
}

if ($_SESSION['role'] !== 'admin') {
  http_response_code(404);
  exit;
}
$sql = "select * from admin where id  = '$id'";
$result = mysqli_query($connect, $sql);
$row_admin = mysqli_fetch_assoc($result);

$sql = "select * from paypal order by id desc limit 1";
$result = mysqli_query($connect, $sql);
$row_paypal = mysqli_fetch_assoc($result);

$sql = "select * from email_settings order by id desc limit 1";
$result = mysqli_query($connect, $sql);
$row_email = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/settings.css">
  <link rel="stylesheet" href="../styles/layout.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Settings</title>
  <link rel="icon" href="../../../icon.png">
  <script defer src="../scripts/settings.js"></script>
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
            <img src="../../../upload/<?php echo $row_admin['image'] ?>" alt="admin">
          </div>
          <div class="choice">
            <button onclick="Edit()">
              <i class="fa-solid fa-pen-to-square"></i>Edit </button>
            <button onclick="Add()">
              <i class="fa-solid fa-user-plus"></i>Add Admin </button>
            <div class="background"></div>
          </div>
          <p class="message"></p>
          <form action="" method="POST" enctype="multipart/form-data" onsubmit="submitFormAdmin(event)">
            <div class='name'>
              <i class="fa-solid fa-user"></i>
              <input class="first_name" type="text" value="<?php echo $row_admin['first_name'] ?>" name="first_name" placeholder=" First Name" id="">
              <input class="last_name" value="<?php echo $row_admin['last_name'] ?>" type="text" name="last_name" placeholder=" Last Name" id="">
            </div>
            <div>
              <i class="fa-solid fa-envelope"></i>
              <input class="email" value="<?php echo $row_admin['email'] ?>" type="email" name="email" placeholder="Email" id="">
            </div>
            <div>
              <i class="fa-solid fa-lock"></i>
              <i class="fa-solid fa-eye" onclick="showPssword()"></i>
              <input class="password" type="password" name="password" placeholder="Password" id="">
            </div>
            <div>
              <i class="fa-solid fa-lock"></i>
              <i class="fa-solid fa-eye" onclick="showPssword()"></i>
              <input class="re-password" type="password" name="Re-password" placeholder="Re-type Password" id="">
            </div>
            <div class="file">
              <i class="fa-solid fa-image"></i>
              <input type="file" name="file" id="">
            </div>
            <button class="submit" type="submit">Save</button>
          </form>
        </div>
        <div class="client-id card">
          <div class="card-header">
            <h4>Update Client Id</h4>
          </div>
          <div class="card-body">
            <p class="message-client-id"></p>
            <form action="" onsubmit="submitFormClientId(event)">
              <div>
                <input type="hidden" name="id" value="<?php echo empty($row_paypal) ? '' : $row_paypal['id'] ?>">
                <input type="text"  name="client_id" value="<?php echo empty($row_paypal) ? '' : $row_paypal['client_id'] ?>" placeholder="Client Id" id="">
                <button>Save</button>
              </div>
            </form>
          </div>
        </div>
        <div class="email-settings card">
          <div class="card-header">
            <h4>Update Email Settings</h4>
          </div>
          <div class="card-body">
            <p class="message-email-settings"></p>
            <form action="" onsubmit="submitFormEmailSettings(event)">
            <input type="hidden" name="id" value="<?php echo empty($row_email) ? '' : $row_email['id'] ?>" >
              <div>
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo empty($row_email) ? '' : $row_email['username'] ?>" placeholder="Username" id="username">
              </div>
              <div>
                <label for="password">Password:</label>
                <input type="text" name="password" value="<?php echo empty($row_email) ? '' : $row_email['password'] ?>" placeholder="Password" id="password">
              </div>
              <div>
                <label for="from">From:</label>
                <input type="text" name="from" value="<?php echo empty($row_email) ? '' : $row_email['from_name'] ?>" placeholder="From" id="from">
              </div>
              <button>Save</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>