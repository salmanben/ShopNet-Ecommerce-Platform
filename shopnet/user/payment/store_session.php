<?php
include "../../connect_DB.php";
session_start();
if (!isset($_SESSION['id']) or $_SESSION['role'] != 'buyer')
    exit;
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $shipping_method_id = $_POST["shipping_method_id"];
  $sql = "SELECT * FROM shipping_method where id = $shipping_method_id";
  $result = mysqli_query($connect, $sql);
  $row = mysqli_fetch_assoc($result);
  unset($_POST['shipping_method_id']);
  $_SESSION['user_address'] = json_encode($_POST);
  $_SESSION['shipping_method'] = json_encode($row);
  echo json_encode(['status'=>'success']); 
}

?>