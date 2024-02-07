<?php
include "../../connect_DB.php";
session_start();
if (!isset($_SESSION['id']) or $_SESSION['role'] != 'buyer' or !isset($_GET['id'])) {
  exit;
}

$seller_id = $_GET['id'];
$sql = "SELECT username, image from seller where id = '$seller_id'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);
echo json_encode($row);