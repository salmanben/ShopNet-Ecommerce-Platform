<?php
include "../../../connect_DB.php";
session_start();
if (!isset($_SESSION['id'])) {
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    exit;
}
if (isset($_GET['status']) and isset($_GET['id'])) {
    $sql = "UPDATE orders set status = '{$_GET['status']}' where id = '{$_GET['id']}'";
    $result = mysqli_query($connect, $sql);
    if (!$result)
        echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
    else
        echo json_encode(['status' => 'success']);
}