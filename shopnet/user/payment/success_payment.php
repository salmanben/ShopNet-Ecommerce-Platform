<?php
include "../../connect_DB.php";
session_start();
if (!isset($_SESSION['id']) or $_SESSION['role'] != 'buyer')
    exit;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['coupon']))
        $coupon = '';
    else
        $coupon = $_SESSION['coupon'];
    $data = file_get_contents("php://input");
    $data = json_decode($data, true);
    $sql = "INSERT INTO `orders` (`invoice_id`, `buyer_id`, `amount`, `subtotal`, `coupon`, `shipping_method`, `address_info`)
    VALUES ('{$data["invoiceId"]}', '{$_SESSION["id"]}', {$_SESSION["amount"]}, {$_SESSION["subtotal"]}, '$coupon', '{$_SESSION["shipping_method"]}', '{$_SESSION["user_address"]}')
";

    mysqli_query($connect, $sql);

    $order_id = mysqli_insert_id($connect);
    $cart = json_decode($_SESSION['cart'], true);
    for ($i = 0; $i < count($cart); $i++) {
        $product_id = $cart[$i]['id'];
        $variants = json_encode($cart[$i]["variants"]);
        $qty = $cart[$i]['qty'];
        $unit_price = $cart[$i]['unit_price'];


        $sql = "INSERT INTO `order_products` (`product_id`, `order_id`, `variants`, `qty`, `unit_price`)
                VALUES ($product_id, $order_id, '$variants', $qty, $unit_price)";
        mysqli_query($connect, $sql);
        
        $sql = "UPDATE product SET count = count - $qty WHERE id = $product_id";
        mysqli_query($connect, $sql);
        
        $sql = "DELETE FROM cart where buyer_id='{$_SESSION['id']}' and product_id = $product_id";
        mysqli_query($connect, $sql);
    }
    if ($coupon != null)
    {
        $code =  json_decode($_SESSION['coupon'], true)['code'];
        $sql = "UPDATE coupon SET quantity = quantity - 1 WHERE code = '$code'";
        mysqli_query($connect, $sql);
    }
    
    unset($_SESSION['coupon']);
    unset($_SESSION["user_address"]);
    unset($_SESSION["amount"]);
    unset($_SESSION["shipping_method"]);
    unset($_SESSION['cart']);
    echo json_encode(['status' => 'success']);
}
