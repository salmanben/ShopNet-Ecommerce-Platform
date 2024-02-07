<?php
include "../../../connect_DB.php";
session_start();
if (!isset($_SESSION['id'])) {
  exit;
}

if ($_SESSION['role'] !== 'admin') {
  exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $inputJson = file_get_contents('php://input');
  $input = json_decode($inputJson, true);
  $start_date = $input['startDate'];
  $end_date = $input['endDate'];


  $sql = "SELECT COUNT(id) AS count FROM buyer WHERE date >= '$start_date' AND date <= '$end_date'";
  $result = mysqli_query($connect, $sql);
  $total_buyers = mysqli_fetch_assoc($result)['count'];

  $sql = "SELECT COUNT(id) AS count FROM seller WHERE date >= '$start_date' AND date <= '$end_date'";
  $result = mysqli_query($connect, $sql);
  $total_sellers = mysqli_fetch_assoc($result)['count'];

  $sql = "SELECT COUNT(id) AS count FROM `admin` WHERE date >= '$start_date' AND date <= '$end_date'";
  $result = mysqli_query($connect, $sql);
  $total_admins = mysqli_fetch_assoc($result)['count'];

  $sql = "SELECT COUNT(id) AS count FROM orders WHERE date >= '$start_date' AND date <= '$end_date'";
  $result = mysqli_query($connect, $sql);
  $total_orders = mysqli_fetch_assoc($result)['count'];

  $sql = "SELECT COUNT(id) AS count FROM orders WHERE lower(`status`)='completed' AND date >= '$start_date' AND date <= '$end_date'";
  $result = mysqli_query($connect, $sql);
  $completed_orders = mysqli_fetch_assoc($result)['count'];

  $sql = "SELECT COUNT(id) AS count FROM orders WHERE lower(`status`)='pending' AND date >= '$start_date' AND date <= '$end_date'";
  $result = mysqli_query($connect, $sql);
  $pending_orders = mysqli_fetch_assoc($result)['count'];

  $sql = "SELECT COUNT(id) AS count FROM product WHERE date >= '$start_date' AND date <= '$end_date'";
  $result = mysqli_query($connect, $sql);
  $total_products = mysqli_fetch_assoc($result)['count'];

  $sql = "SELECT COALESCE(SUM(subtotal), 0) AS sum FROM orders WHERE status = 'completed' AND date >= '$start_date' AND date <= '$end_date'";
  $result = mysqli_query($connect, $sql);
  $total_earnings = mysqli_fetch_assoc($result)['sum'];
  $total_earnings = round($total_earnings, 2);

  $response = array(
    'total_buyers' => $total_buyers,
    'total_sellers' => $total_sellers,
    'total_admins' => $total_admins,
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
    $sql = "SELECT COUNT(id) AS count FROM orders WHERE MONTH(date) = $i AND YEAR(date) = $current_year";
    $result = mysqli_query($connect, $sql);
    $total_orders_month = mysqli_fetch_assoc($result)['count'];
    $response['orders'][] = $total_orders_month;

    $sql = "SELECT COALESCE(SUM(subtotal), 0) AS sum FROM orders WHERE MONTH(date) = $i AND status = 'completed'  AND YEAR(date) = $current_year";
    $result = mysqli_query($connect, $sql);
    $total_earnings_month = mysqli_fetch_assoc($result)['sum'];
    $response['earnings'][] = $total_earnings_month;
  }

  $sql = "SELECT COUNT(id) AS count FROM orders where lower(`status`)='completed' and YEAR(date) = $current_year";
  $result = mysqli_query($connect, $sql);
  $completed_orders = mysqli_fetch_assoc($result)['count'];

  $sql = "SELECT COUNT(id) AS count FROM orders where lower(`status`)='pending' and YEAR(date) = $current_year";
  $result = mysqli_query($connect, $sql);
  $pending_orders = mysqli_fetch_assoc($result)['count'];
  
  $sql = "SELECT COUNT(id) AS count FROM orders where lower(`status`)='dropped off' and YEAR(date) = $current_year";
  $result = mysqli_query($connect, $sql);
  $dropped_off_orders = mysqli_fetch_assoc($result)['count'];
  $response['orders_status'] = [$completed_orders, $pending_orders, $dropped_off_orders] ;
  echo json_encode($response);
}
