<?php
include "../../../connect_DB.php";
include('../layout/aside.php');
include('../layout/header.php');
if (!isset($_SESSION['id'])) {
  header("location:../login/login.php");
  exit;
}

if ($_SESSION['role'] !== 'admin') {
  http_response_code(404);
  exit;
}

$sql = "SELECT COUNT(id) AS count FROM buyer";
$result = mysqli_query($connect, $sql);
$total_buyers = mysqli_fetch_assoc($result)['count'];

$sql = "SELECT COUNT(id) AS count FROM seller";
$result = mysqli_query($connect, $sql);
$total_sellers = mysqli_fetch_assoc($result)['count'];

$sql = "SELECT COUNT(id) AS count FROM `admin`";
$result = mysqli_query($connect, $sql);
$total_admins = mysqli_fetch_assoc($result)['count'];

$sql = "SELECT COUNT(id) AS count FROM orders";
$result = mysqli_query($connect, $sql);
$total_orders = mysqli_fetch_assoc($result)['count'];

$sql = "SELECT COUNT(id) AS count FROM orders where lower(`status`)='completed'";
$result = mysqli_query($connect, $sql);
$completed_orders = mysqli_fetch_assoc($result)['count'];

$sql = "SELECT COUNT(id) AS count FROM orders where lower(`status`)='pending'";
$result = mysqli_query($connect, $sql);
$pending_orders = mysqli_fetch_assoc($result)['count'];

$sql = "SELECT COUNT(id) AS count FROM product";
$result = mysqli_query($connect, $sql);
$total_products = mysqli_fetch_assoc($result)['count'];

$sql = "SELECT COALESCE(SUM(subtotal), 0) AS sum FROM orders where status = 'completed'";
$result = mysqli_query($connect, $sql);
$total_earnings = mysqli_fetch_assoc($result)['sum'];
$total_earnings = round($total_earnings, 2);

$sqlImg = "select image from admin where id = '$id'";
$result = mysqli_query($connect, $sqlImg);
$image = mysqli_fetch_assoc($result)['image'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/dashboard.css">
  <link rel="stylesheet" href="../styles/layout.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Dashboard</title>
  <link rel="icon" href="../../../icon.png">
  <script defer src="../scripts/dashboard.js"></script>
</head>

<body>
  <div class="wrapper">
    <?php echo $aside ?>
    <div class="container">
      <?php echo $header ?>
      <div class="content">
        <h2>Dashboard</h2>
        <div class="boxes">
          <div class="box">
            <div class="icon total-orders">
              <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="text">
              <h4>Total Orders</h4>
              <h4><?php echo $total_orders ?></h4>
            </div>
          </div>
          <div class="box">
            <div class="icon completed-orders">
              <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="text">
              <h4>Completed Orders</h4>
              <h4><?php echo $completed_orders ?></h4>
            </div>
          </div>
          <div class="box">
            <div class="icon pending-orders">
              <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="text">
              <h4>Pending Orders</h4>
              <h4><?php echo $pending_orders ?></h4>
            </div>
          </div>
          <div class="box">
            <div class="icon total-earnings">
              <i class="fa-solid fa-money-bill"></i>
            </div>
            <div class="text">
              <h4>Total Earnings</h4>
              <h4>$<?php echo $total_earnings ?></h4>
            </div>
          </div>
          <div class="box">
            <div class="icon total-products">
              <i class="fa-brands fa-product-hunt"></i>
            </div>
            <div class="text">
              <h4>Total Products</h4>
              <h4><?php echo $total_products ?></h4>
            </div>
          </div>
          <div class="box">
            <div class="icon total-users">
              <i class="fa-solid fa-user"></i>
            </div>
            <div class="text">
              <h4>Total Buyers</h4>
              <h4><?php echo $total_buyers ?></h4>
            </div>
          </div>
          <div class="box">
            <div class="icon total-users">
              <i class="fa-solid fa-user"></i>
            </div>
            <div class="text">
              <h4>Total Sellers</h4>
              <h4><?php echo $total_sellers ?></h4>
            </div>
          </div>
          <div class="box">
            <div class="icon total-users">
              <i class="fa-solid fa-user"></i>
            </div>
            <div class="text">
              <h4>Total Admins</h4>
              <h4><?php echo $total_admins ?></h4>
            </div>
          </div>
        </div>
        <div class="date">
          <div>
            <label for="begin">Start Date</label>
            <input class="start-date" type="date" name="" id="begin">
          </div>
          <div>
            <label for="end">End Date</label>
            <input class="end-date" type="date" name="" id="end">
          </div>
        </div>
        <div class="boxes">
          <div class="box">
            <div class="icon total-orders">
              <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="text">
              <h4>Total Orders</h4>
              <h4 class="total-orders-value">...</h4>
            </div>
          </div>
          <div class="box">
            <div class="icon completed-orders">
              <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="text">
              <h4>Completed Orders</h4>
              <h4 class="completed-orders-value">...</h4>
            </div>
          </div>
          <div class="box">
            <div class="icon pending-orders">
              <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="text">
              <h4>Pending Orders</h4>
              <h4 class="pending-orders-value">...</h4>
            </div>
          </div>
          <div class="box">
            <div class="icon total-earnings">
              <i class="fa-solid fa-money-bill"></i>
            </div>
            <div class="text">
              <h4>Total Earnings</h4>
              <h4 class="total-earnings-value">...</h4>
            </div>
          </div>
          <div class="box">
            <div class="icon total-products">
              <i class="fa-brands fa-product-hunt"></i>
            </div>
            <div class="text">
              <h4>Total Products</h4>
              <h4 class="total-products-value">...</h4>
            </div>
          </div>
          <div class="box">
            <div class="icon total-users">
              <i class="fa-solid fa-user"></i>
            </div>
            <div class="text">
              <h4>Total Buyers</h4>
              <h4 class="total-buyers-value">...</h4>
            </div>
          </div>
          <div class="box">
            <div class="icon total-users">
              <i class="fa-solid fa-user"></i>
            </div>
            <div class="text">
              <h4>Total Sellers</h4>
              <h4 class="total-sellers-value">...</h4>
            </div>
          </div>
          <div class="box">
            <div class="icon total-users">
              <i class="fa-solid fa-user"></i>
            </div>
            <div class="text">
              <h4>Total Admins</h4>
              <h4 class="total-admins-value">...</h4>
            </div>
          </div>
        </div>
        <h3 class="year">Year: <?php echo date("Y") ?></h3>
        <div class="charts">
          <div>
            <canvas id="orders" ></canvas>
          </div>
          <div>
            <canvas id="orders-status" ></canvas>
          </div>
          <div>
            <canvas id="earnings" ></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>