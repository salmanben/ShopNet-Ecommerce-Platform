<?php
include "../../../connect_DB.php";
session_start();
if (isset($_GET['id'])) {
  $id = $_SESSION['id'];
  $idP = $_GET['id'];
  $sql = "SELECT send_id,message FROM Messaging  where seller_id = '$id'
  AND buyer_id = '$idP' ORDER BY date ";
  $result = mysqli_query($connect, $sql);
  $msg = "";
  while ($row = mysqli_fetch_assoc($result)) {
    if ($row['send_id'] == $id)
      $class = "to";
    else
      $class = "from";
    $msg .= '<p class = "' . $class . '">' . $row["message"] . '</p>';
  }
  echo $msg;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $data = file_get_contents("php://input");
  $data = json_decode($data, true);
  $id = $_SESSION['id'];
  $idP = $data['id'];
  $msg = mysqli_real_escape_string($connect, $data['msg']);
  $sql = "INSERT INTO Messaging (buyer_id, seller_id, send_id, message)
          VALUES ('$idP', '$id', '$id', '$msg')";

  $result = mysqli_query($connect, $sql);
  if ($result)
    echo 1;
}
