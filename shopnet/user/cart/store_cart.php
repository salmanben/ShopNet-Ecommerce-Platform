<?php
include "../../connect_DB.php";
session_start();
if(!isset($_SESSION['id']) or $_SESSION['role'] != 'buyer')
   exit;
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = file_get_contents("php://input");
    $data = json_decode($data, true);
    $_SESSION['subtotal'] = $data['subtotal'];
    $_SESSION['cart'] = json_encode($data['cart']);
    echo 1;
}

