<?php
include "../../connect_DB.php";
session_start();
if (!isset($_SESSION['id']) or $_SESSION['role'] != 'buyer')
    exit;
$id = $_SESSION['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['coupon']))
        unset($_SESSION['coupon']);
    $data = file_get_contents("php://input");
    $data = json_decode($data, true);
    $coupon_code = $data['code'];
    $sql = "SELECT * FROM coupon where code = '$coupon_code'";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid coupon code.']);
        exit;
    }
    $row = mysqli_fetch_assoc($result);
    if ($row['status'] == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid coupon code.']);
        exit;
    } else if ($row['quantity'] == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Coupon expired.']);
        exit;
    }
    $sql = "SELECT * FROM `orders` WHERE `buyer_id` = '$id' AND `coupon` != '';
    ";
    $result = mysqli_query($connect, $sql);
    if (mysqli_num_rows($result) > 0) {
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $used = 0;
        for ($i = 0; $i < count($rows); $i++) {
            $coupon = json_decode($rows[$i]['coupon'], true);
            
            if ($coupon['code'] == $coupon_code)
                $used++;
        }

        if ($used >= $row['max_use']) {
            echo json_encode(['status' => 'error', 'message' => "You can use this coupon only {$row['max_use']} times."]);
            exit;
        }
    }

    $subtotal = $_SESSION['subtotal'];
    if ($row['type'] == 'amount') {
        $discount = $row['cost'];
    } else {
        $discount = ($subtotal * $row['cost']) / 100;
    }
    $discount = round($discount, 2);
    $_SESSION['coupon'] = json_encode($row);
    echo json_encode(['status' => 'success', 'discount' => $discount, 'total' => $subtotal - $discount]);
    exit;
}
