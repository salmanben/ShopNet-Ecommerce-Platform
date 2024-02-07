<?php
include "../../connect_DB.php";
session_start();
if(!isset($_SESSION['id']) or $_SESSION['role'] != 'buyer')
exit;
$id = $_SESSION['id'];
if(isset($_GET['id'])){
    $Pid  = $_GET['id'];
    $sql = "DELETE FROM cart where product_id = '$Pid' AND buyer_id = '$id'";
    $result = mysqli_query($connect,$sql);
    if($result)
        echo 1;

}

?>