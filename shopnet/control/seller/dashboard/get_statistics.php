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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $inputJson = file_get_contents('php://input');
  $input = json_decode($inputJson, true);
  $start_date = $input['startDate'];
  $end_date = $input['endDate'];


  $sql = "SELECT o.* FROM orders o
  join order_products op on op.order_id = o.id
  join product p on op.product_id = p.id 
  where seller_id = '$id'
  AND o.date BETWEEN  '$start_date' AND '$end_date'
  group by o.id
  ";
  $result = mysqli_query($connect, $sql);
  $total_orders = count(mysqli_fetch_all($result));

  $sql = "SELECT o.* FROM orders o
  JOIN order_products op ON op.order_id = o.id
  JOIN product p ON op.product_id = p.id 
  WHERE seller_id = '$id' AND LOWER(o.status) = 'completed'
  AND o.date >= '$start_date' AND o.date <= '$end_date'
  group by o.id";
  $result = mysqli_query($connect, $sql);
  $completed_orders = count(mysqli_fetch_all($result));

  $sql = "SELECT o.* FROM orders o
  JOIN order_products op ON op.order_id = o.id
  JOIN product p ON op.product_id = p.id 
  WHERE seller_id = '$id' AND LOWER(o.status) = 'pending'
  AND o.date >= '$start_date' AND o.date <= '$end_date'
  group by o.id";
  $result = mysqli_query($connect, $sql);
  $pending_orders = count(mysqli_fetch_all($result));

  $sql = "SELECT COUNT(id) AS count FROM product WHERE seller_id= '$id' and  date >= '$start_date' AND date <= '$end_date'";
  $result = mysqli_query($connect, $sql);
  $total_products = mysqli_fetch_assoc($result)['count'];

  $sql = "SELECT COALESCE(SUM(unit_price * qty), 0) AS sum 
  FROM orders o JOIN order_products op
  on o.id = op.order_id
  JOIN product p ON op.product_id = p.id 
  WHERE seller_id = '$id'
  and  o.date >= '$start_date' AND o.date <= '$end_date' AND LOWER(o.status) = 'completed'";
  $result = mysqli_query($connect, $sql);
  $total_earnings = mysqli_fetch_assoc($result)['sum'];
  $total_earnings = round($total_earnings, 2);
  $response = array(
    'total_orders' => $total_orders,
    'completed_orders' => $completed_orders,
    'pending_orders' => $pending_orders,
    'total_products' => $total_products,
    'total_earnings' => $total_earnings
  );

  echo json_encode($response);
} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $response = array();
  $current_year = date("Y");

  for ($i = 1; $i <= date('m'); $i++) {
    $sql = "SELECT o.* FROM orders o
    join order_products op on op.order_id = o.id
    join product p on op.product_id = p.id 
    where seller_id = '$id'
    AND  MONTH(o.date) = $i AND YEAR(o.date) = $current_year
    group by o.id";

    $result = mysqli_query($connect, $sql);
    $total_orders_month = count(mysqli_fetch_all($result));
    $response['orders'][] = $total_orders_month;

    $sql = "SELECT COALESCE(SUM(unit_price * qty), 0) AS sum 
    FROM orders o JOIN order_products op
    on o.id = op.order_id
    JOIN product p ON op.product_id = p.id 
    WHERE seller_id = '$id' AND  MONTH(o.date) = $i AND YEAR(o.date) = $current_year 
    AND LOWER(o.status) = 'completed'";
    $result = mysqli_query($connect, $sql);
    $total_earnings_month = mysqli_fetch_assoc($result)['sum'];
    $response['earnings'][] = $total_earnings_month;
  }

  $sql = "SELECT o.* FROM orders o
  JOIN order_products op ON op.order_id = o.id
  JOIN product p ON op.product_id = p.id 
  WHERE seller_id = '$id' AND LOWER(o.status) = 'completed' AND YEAR(o.date) = $current_year
  group by o.id";
  $result = mysqli_query($connect, $sql);
  $completed_orders = count(mysqli_fetch_all($result));
  
  $sql = "SELECT o.* FROM orders o
  join order_products op on op.order_id = o.id
  join product p on op.product_id = p.id 
  where seller_id = '$id' and LOWER(o.status) = 'pending' AND YEAR(o.date) = $current_year
  group by o.id
  ";
  $result = mysqli_query($connect, $sql);
  $pending_orders = count(mysqli_fetch_all($result));

  $sql = "SELECT o.* FROM orders o
  JOIN order_products op ON op.order_id = o.id
  JOIN product p ON op.product_id = p.id 
  WHERE seller_id = '$id' AND LOWER(o.status) = 'dropped off' AND YEAR(o.date) = $current_year
  group by o.id";
  $result = mysqli_query($connect, $sql);
  $dropped_off_orders = count(mysqli_fetch_all($result));

  $response['orders_status'] = [$completed_orders, $pending_orders, $dropped_off_orders];
  echo json_encode($response);
}
