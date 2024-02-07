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
if (isset($_GET['amount'])) {
    $amount = $_GET['amount'];
    if ($amount == '')
    {
        echo json_encode(['status' => 'error', 'message' => "You must insert the amount."]);
        exit;
    }
    if(!filter_var($amount, FILTER_VALIDATE_FLOAT))
    {
        echo json_encode(['status' => 'error', 'message' => "Invalid amount."]);
        exit; 
    }
    $sql = " SELECT * from withdraw_method
    order by id desc limit 1";
    $result = mysqli_query($connect, $sql);
    $row_withdraw_method = mysqli_fetch_assoc($result);
   
    $sql = "SELECT COALESCE(SUM(amount + (amount * charges) / 100), 0)  as sum from withdraw_request where seller_id = '$id'
    and status = 'paid'";
    $result = mysqli_query($connect, $sql);
    $total_withdraw = mysqli_fetch_assoc($result)['sum'];

    $sql = "SELECT COALESCE(SUM(unit_price * qty), 0) AS sum 
    FROM orders o JOIN order_products op
    on o.id = op.order_id
    JOIN product p ON op.product_id = p.id 
    WHERE seller_id = '$id' AND LOWER(o.status) = 'completed'";
    $result = mysqli_query($connect, $sql);
    $total_earnings = mysqli_fetch_assoc($result)['sum'];

    $current_balance = $total_earnings - $total_withdraw;
    
    $sql = "SELECT *  from withdraw_request where seller_id = '$id'
    and status = 'pending'";
    $result= mysqli_query($connect, $sql);
    if(mysqli_num_rows($result) > 0)
    {
        echo json_encode(['status' => 'error', 'message' => "Please wait until the admin process the previous request."]);
        exit;
    }
    if($amount < $row_withdraw_method['min_amount'])
    {
        echo json_encode(['status' => 'error', 'message' => "The minimum amount you can request is \${$row_withdraw_method['min_amount']}."]);
        exit;
    }
    $max_possible_request_amount = $current_balance / (1 + $row_withdraw_method['charges']/100);
    $max_possible_request_amount = round($max_possible_request_amount, 2);
    if($amount > $max_possible_request_amount)
    {
        echo json_encode(['status' => 'error', 'message' => "You don't have enough balance. The maximum amount you can request is \${$max_possible_request_amount}."]);
        exit;
    }
    if($amount > $row_withdraw_method['max_amount'])
    {
        echo json_encode(['status' => 'error', 'message' => "The maximum amount you can request is \${$row_withdraw_method['max_amount']}."]);
        exit;
    }
    $amount = round($amount, 2);
    $sql = "INSERT INTO withdraw_request(amount, charges, seller_id)values($amount, {$row_withdraw_method['charges']}, '$id')";
    $result = mysqli_query($connect, $sql);
    $request_id = mysqli_insert_id($connect);
    $sql_date = "SELECT date FROM withdraw_request WHERE id = $request_id";
    $result_date = mysqli_query($connect, $sql_date);
    $row_date = mysqli_fetch_assoc($result_date);
    $date = $row_date['date'];
    if (!$result){
       echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
    }else
    {
        $charges = ($amount * $row_withdraw_method['charges']) / 100;
        $charges = round($charges, 2);
        echo json_encode(['status' => 'success', 'message' => "Request Sent Successfully.",
                        'id' => $request_id,'status'=>'Pending', 'amount'=>$amount, 'charges'=>$charges, 
                        'total'=>round(($amount +  $charges), 2) , 'date'=>$date]);
    }
        
}
