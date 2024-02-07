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
$sql = "SELECT withdraw_request.*, paypal_email  from withdraw_request join seller
on seller.id = withdraw_request.seller_id
order by withdraw_request.date desc";
$result = mysqli_query($connect, $sql);

$sql = " SELECT * from withdraw_method
order by id desc limit 1";
$result_withdraw_method = mysqli_query($connect, $sql);
$row_withdraw_method = mysqli_fetch_assoc($result_withdraw_method);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/style.css">
  <link rel="stylesheet" href="../styles/layout.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        <div class="create-search">
          <div class="create-method">
            <div class="card">
              <div class="card-header">
                <h4> Withdraw Settings</h4>
              </div>
              <div class="card-body">
                <p class="message">
                </p>
                <form action="" class="withdraw-method-form" onsubmit="updateItem(event)">
                  <input type="hidden" name="id" value="<?php echo  $row_withdraw_method != null ? $row_withdraw_method['id'] : "" ?>">
                  <div>
                    <label for="method_name">Method Name:</label>
                    <input type="text" id="method_name" name="method_name" readonly value="PayPal" id="method_name" placeholder="Method Name">
                  </div>
                  <div>
                    <label for="charges">Charges(%):</label>
                    <input type="text" id="charges" name="charges" value="<?php echo  $row_withdraw_method != null ? $row_withdraw_method['charges'] : "" ?>" id="charges" placeholder="Charges(%)">
                  </div>
                  <div>
                    <label for="min_amount">Min Amount($):</label>
                    <input type="text" id="min_amount" name="min_amount" value="<?php echo  $row_withdraw_method != null ? $row_withdraw_method['min_amount'] : "" ?>" id="min_amount" placeholder="Min Amount">
                  </div>
                  <div>
                    <label for="max_amount">Max Amount($):</label>
                    <input type="text" id="max_amount" value="<?php echo  $row_withdraw_method != null ? $row_withdraw_method['max_amount'] : "" ?>" name="max_amount" id="max_amount" placeholder="Max Amount">
                  </div>
                  <button type="submit">Update</button>
                </form>
              </div>
            </div>
          </div>
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
                <th>PayPal</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody class="tbody">
              <?php
              while ($row = mysqli_fetch_assoc($result)) {
                $charges = ($row['charges'] * $row['amount']) / 100;
                $charges = round($charges, 2);
                $total = round($charges + $row['amount'], 2);
                $selected_pending = $row['status'] == 'Pending' ? "selected" : "";
                $selected_paid = $row['status'] == 'Paid' ? "selected" : "";
                $selected_decline = $row['status'] == 'Decline' ? "selected" : "";
                echo '
                <tr class="item" id="' . $row["id"] . '">
                 <td>' . $row["id"] . '</td>
                 <td>$' . $total . '</td>
                 <td>$' . $row["amount"] . '</td>
                 <td>$' . $charges . '</td>
                 <td>' . $row["paypal_email"] . '</td>
                 <td> 
                 <select name="status" data-id="' . $row['id'] . '" onchange = "changeValueOfStatus(event)" id="">
                   <option ' . $selected_pending . ' value="Pending">Pending</option>
                   <option ' . $selected_decline . '  value="Decline">Decline</option>
                   <option ' . $selected_paid . '  value="Paid">Paid</option>
                 </select>
                 </td>
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

</body>

</html>