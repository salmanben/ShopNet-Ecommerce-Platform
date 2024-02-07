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
$sql = "SELECT f.seller_id,s.username,s.banner From seller_followers f
Join seller s ON f.seller_id = s.id
where buyer_id = '$id' order by f.date desc";
$result = mysqli_query($connect, $sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="followed.css">
  <link rel="stylesheet" href="../layout/layout.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="icon" href="../../icon.png">
  <script defer src="followed.js"></script>
  <script defer src="../layout/layout.js"></script>
  <title>Followed</title>
</head>

<body>
  <?php echo $header; ?>
  <div class="main">
    <div class="search-followed">
      <i class="fa-solid fa-magnifying-glass"></i>
      <input type="search" placeholder="Write the seller's username." name="" id="">
    </div>
    <div class="list-followed">
      <?php
      while ($row = mysqli_fetch_assoc($result)) {
        echo '
        <div class="box">
          <div class="banner">
            <img src="../../upload/' . $row["banner"] . '" alt="">
          </div>
          <div class="text">
            <p class="username">' . $row["username"] . '</p>
            <a class="visit-store" href="../store/store.php?id=' . $row["seller_id"] . '">Visit Store</a>
            <a class="remove" id="' . $row["seller_id"] . '" onclick="remove(this.id)" href="">Remove</a>
          </div>
        </div>';
      }
      ?>
    </div>
    <div class="message">
          <p></p>
    </div>
  </div>
  <?php echo $footer; ?>
</body>



</html>