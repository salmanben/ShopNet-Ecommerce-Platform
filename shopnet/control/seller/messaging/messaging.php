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
$id = $_SESSION['id'];
$sql = "SELECT m.*, username
FROM messaging m
JOIN buyer b ON m.buyer_id = b.id
JOIN (
    SELECT buyer_id, MAX(date) AS max_date
    FROM messaging
    WHERE seller_id = '$id'
    GROUP BY buyer_id
) AS recent_messages ON m.buyer_id = recent_messages.buyer_id AND m.date = recent_messages.max_date
WHERE m.seller_id = '$id' ORDER BY m.date desc;
";
$result = mysqli_query($connect, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="../styles/messaging.css">
  <link rel="stylesheet" href="../styles/layout.css">
  <script defer src="../scripts/messaging.js"></script>
  <link rel="icon" href="../../../icon.png">
  <title>Messaging</title>
</head>

<body>
  <div class="wrapper">
    <?php echo $aside ?>
    <div class="container">
      <?php echo $header ?>
      <div class="content">
        <h2>Messaging</h2>
        <div class="messaging-container">
          <div class="partners-container">
            <div class="header">
              <i class="fa-brands fa-rocketchat"></i>
              <span>Messaging</span>
            </div>
            <input type="search" class="search-partner" name="" placeholder="Search..." id="">
            <ul class="list-partners">
              <?php
              while ($row = mysqli_fetch_assoc($result)) {
                echo '
                <li class="buyer" id="' . $row['buyer_id'] . '" onclick="accessChat(event)">
                  <img src="buyer.png">
                  <h4>' . $row['username'] . '</h4>
                  <span class="date">' . date('D M d Y', strtotime($row['date'])) . '</span>
                  <p>' . $row['message'] . '</p>
                </li>';
              }
              ?>
            </ul>
          </div>
          <div class="chat">
            <div class="empty">
              <i class="fa-solid fa-paper-plane"></i>
              <p>Stay connected with your <span>clients</span>
                through our integrated messaging system, making communication effortless and efficient
              </p>
            </div>
            <div class="header">
              <i class="fa-solid fa-left-long"></i>
              <img src="">
              <h4></h4>
            </div>
            <div class="messages">
            </div>
            <div class="send">
              <input type="text" placeholder="write your message">
              <button class="sendBtn"><i class="fa-solid fa-paper-plane"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>