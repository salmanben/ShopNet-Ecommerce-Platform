<?php
include "../../../connect_DB.php";
session_start();
if (!isset($_SESSION['id'])) {
    exit;
}
if ($_SESSION['role'] !== 'seller') {
    exit;
}
$id = $_SESSION['id'];
if (isset($_GET['status']) and isset($_GET['id'])) {
    $sql = "SELECT `status` from orders where id = '{$_GET['id']}'";
    $result = mysqli_query($connect, $sql);
    $status = mysqli_fetch_assoc($result)['status'];
    if ($status == 'Completed') {
        echo json_encode(['status' => 'error', 'message' => "Unauthorized action!"]);
        exit;
    }
    $sql = "UPDATE order_products set status = '{$_GET['status']}' where order_id = '{$_GET['id']}'
    and product_id in (SELECT id from product where seller_id = '$id')
    ";
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
        exit;
    }

    if ($_GET['status'] == 'Pending') {
        $sql = "UPDATE orders set status = 'Pending' where id = '{$_GET['id']}'";
        $result = mysqli_query($connect, $sql);
        if (!$result) {
            echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
            exit;
        } else {
            echo json_encode(['status' => 'success']);
            exit;
        }
    } else {
        $sql = "SELECT id from order_products where order_id = '{$_GET['id']}' and lower(status) = 'Pending'";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) == 0) {
            $sql = "UPDATE orders set status = 'Dropped Off' where id = '{$_GET['id']}'";
            $result = mysqli_query($connect, $sql);
            if (!$result) {
                echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
                exit;
            } else {
                echo json_encode(['status' => 'success']);
                exit;
            }
        }
    }
}
