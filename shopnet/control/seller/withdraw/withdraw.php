<?php
include "../../../connect_DB.php";
include('../layout/aside.php');
include('../layout/header.php');
if (!isset($_SESSION['id'])) {
  header("location:../../../auth/login/login.php");
  exit;
}
if ($_SESSION['role'] !== 'seller') {
  http_response_code(404);
  exit;
}
$id = $_SESSION['id'];

$sql = "SELECT * from withdraw_request 
where seller_id = '$id' order by date desc";
$result = mysqli_query($connect, $sql);

$sql = " SELECT * from withdraw_method
order by id desc limit 1";
$result_withdraw_method = mysqli_query($connect, $sql);
$row_withdraw_method = mysqli_fetch_assoc($result_withdraw_method);

$charges = $row_withdraw_method['charges'];

$sql = "SELECT COALESCE(SUM(amount + (amount * charges) / 100), 0)  as sum from withdraw_request where seller_id = '$id'
and status = 'paid'";
$result_total_withdraw = mysqli_query($connect, $sql);
$total_withdraw = mysqli_fetch_assoc($result_total_withdraw)['sum'];
$total_withdraw = round($total_withdraw, 2);

$sql = "SELECT COALESCE(SUM(unit_price * qty), 0) AS sum 
FROM orders o JOIN order_products op
on o.id = op.order_id
JOIN product p ON op.product_id = p.id 
WHERE seller_id = '$id' AND LOWER(o.status) = 'completed'";
$result_total_earnings = mysqli_query($connect, $sql);
$total_earnings = mysqli_fetch_assoc($result_total_earnings)['sum'];

$current_balance = round(($total_earnings - $total_withdraw), 2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/style.css">
  <link rel="stylesheet" href="../styles/layout.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Withdraw</title>
  <link rel="icon" href="../../../icon.png">
  <script defer src="../scripts/table_script.js"></script>
  <script defer src="../scripts/handle_actions.js"></script>
</head>

<body>
  <div class="wrapper">
    <?php echo $aside; ?>
    <div class="container">
      <?php echo $header; ?>
      <div class="content">
        <h2>Withdraw</h2>
        <div class="boxes">
          <div class="box">
            <div class="icon total-earnings">
              <i class="fa-solid fa-money-bill"></i>
            </div>
            <div class="text">
              <h4>Current Balance</h4>
              <h4>$<?php echo $current_balance ?></h4>
            </div>
          </div>
          <div class="box">
            <div class="icon total-earnings">
              <i class="fa-solid fa-money-bill"></i>
            </div>
            <div class="text">
              <h4>Total Withdraw + Charges</h4>
              <h4>$<?php echo $total_withdraw ?></h4>
            </div>
          </div>
        </div>
        <div class="create-search">
          <?php
             if($current_balance >= $row_withdraw_method['min_amount'] +($row_withdraw_method['min_amount'] * $row_withdraw_method['charges']) / 100)
             {
              echo '<div class="create-request">
              <p class="message">Hello You don\'t have current balance</p>
              <input type="text" name="amount" class="withdraw-amount" placeholder="Enter the amount.">
              <button onclick="withdrawRequest(event)">Send</button>
              </div>';
             }
                
          ?>
          <div class="search">
            <input type="search" placeholder="Search by id..." id="">
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>
        <div class="table">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Total</th>
                <th>Amount</th>
                <th>Charges</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody class="tbody">
              <?php
              while ($row = mysqli_fetch_assoc($result)) {
                $charges = round((($row['charges'] * $row['amount']) / 100), 2);
                $total = round($charges + $row['amount'], 2);
                echo '
                <tr class="item" id="' . $row["id"] . '">
                 <td>' . $row["id"] . '</td>
                 <td>$' . $total . '</td>
                 <td>$' . $row["amount"] . '</td>
                 <td>$' . $charges . '</td>
                 <td> <span class="request-status">' . $row["status"] . '</span></td>
                 <td>' . $row["date"] . '</td>
                </tr>
                ';
              }
              ?>
            </tbody>

          </table>
        </div>
      </div>
    </div>
  </div>
  <script>
    var RequestStatus = document.querySelectorAll(".request-status");
    RequestStatus.forEach(function(element) {
      var status = element.innerText.trim().toLowerCase();
      switch (status) {
        case 'pending':
          element.style.backgroundColor = "#ffc107";
          break;
        case 'paid':
          element.style.backgroundColor = "#28a745";
          break;
        case 'decline':
          element.style.backgroundColor = "red";
          break;
        default:
          element.style.backgroundColor = "gray";
          break;
      }
    });
  </script>

</body>

</html>