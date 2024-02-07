<?php
include "../../connect_DB.php";
session_start();
if(!isset($_SESSION['id'])){
   echo json_encode(['status'=>'error', 'redirect'=>'../../auth/login/login.php']);
   exit;
}
if($_SESSION['role'] != 'buyer'){
    echo json_encode(['status'=>'error', 'message'=>'Please log in as a buyer.']);
    exit;
 }

$id = $_SESSION['id'];
if(isset($_GET['id'])){
    $product_id  = $_GET['id'];
    $sql = "SELECT product_id FROM cart where product_id = '$product_id' AND buyer_id = '$id'";
    $result = mysqli_query($connect,$sql);
    if(mysqli_fetch_assoc($result) == 0){
        $sql = "INSERT INTO cart(product_id,buyer_id) values ('$product_id','$id')";
        $result = mysqli_query($connect,$sql);
       if ($result)
       {
          echo json_encode(['status'=>'success', 'already_added'=>0]);
          exit;
       }
       else
       {
          echo json_encode(['status'=>'error', 'message'=>'Something went wrong, please try again.']);
          exit;
       }
    }
    echo json_encode(['status'=>'success', 'already_added'=>1]);
    
    
}

?>