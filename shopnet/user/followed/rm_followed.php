<?php
include "../../connect_DB.php";
session_start();
if(!isset($_SESSION['id']) or !isset($_GET['id']))
  exit;
$buyer_id = $_SESSION['id'];
$seller_id = $_GET['id'];
$sql = "DELETE from seller_followers where seller_id = '$seller_id' and buyer_id = '$buyer_id'";
$result = mysqli_query($connect, $sql);
if ($result)
{   
    echo json_encode(['status'=>'success']);
    exit;
}
else
{
    echo json_encode(['status'=>'error', 'message'=>'Something went wrong, please try again.']);
    exit;
}

?>