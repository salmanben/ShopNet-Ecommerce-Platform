<?php
include "../../connect_DB.php";
session_start();
if (!isset($_GET['seller_id']) || !isset($_GET['action']))
{
    exit;
}
if (!isset($_SESSION['id']))
{
    echo json_encode(['status'=>'error', 'redirect'=>'../../auth/login/login.php']);
    exit;
}
if ($_SESSION['role'] != 'buyer')
{
    echo json_encode(['status'=>'error', 'message'=>'You must login as buyer']);
    exit;
}
$buyer_id = $_SESSION['id'];
$seller_id = $_GET['seller_id'];
if ($_GET['action'] == 'add')
    $sql = "INSERT INTO seller_followers (buyer_id, seller_id) values ('$buyer_id', '$seller_id')";
else
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