<?php
include "../../connect_DB.php";
include '../layout/layout.php';
if (!isset($_SESSION['id'])) {
  header("location:../../auth/login/login.php");
  exit;
}
if (isset($_SESSION['role']) and $_SESSION['role'] != 'buyer') {
  http_response_code('404');
  exit;
}
$id = $_SESSION['id'];
$sql = " SELECT * from orders where buyer_id = '$id' order by date desc";
$result = mysqli_query($connect, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="orders.css">
  <link rel="stylesheet" href="../layout/layout.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Orders</title>
  <link rel="icon" href="../../icon.png">
  <script defer src="../layout/layout.js"></script>
</head>

<body>
  <?php echo $header; ?>
  <div class="container">
    <div class="no-orders">
      <p>You don't have any order.</p>
    </div>
    <div class="main">
      <div class="table">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Invoice ID</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Date</th>
              <th>Show</th>
            </tr>
          </thead>
          <tbody class="tbody">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
              $checked = $row["status"] ==  1 ? "checked" : "";
              echo '
                <tr class="item" id="' . $row["invoice_id"] . '">
                 <td>' . $row["id"] . '</td>
                 <td>' . $row["invoice_id"] . '</td>
                 <td>$' . $row["amount"] . '</td>
                 <td> <span class="status-order">' . $row["status"] . '</span></td>
                 <td>' . $row["date"] . '</td>
                 <td><a class="show" href="show.php?order_id=' . $row["id"] . '"><i class="fa-solid fa-eye"></i></a></td>
                </tr>
                ';
            }
            ?>
          </tbody>

        </table>
      </div>
    </div>
  </div>
  <?php echo $footer; ?>
  <script>
    var statusOrder = document.querySelectorAll(".status-order");
    statusOrder.forEach(function(element) {
      var status = element.innerText.trim().toLowerCase();
      switch (status) {
        case 'pending':
          element.style.backgroundColor = "#ffc107";
          break;
        case 'completed':
          element.style.backgroundColor = "#28a745";
          break;
        case 'dropped off':
          element.style.backgroundColor = "#17a2b8";
          break;
        default:
          element.style.backgroundColor = "gray";
          break;
      }
    });
    var tbody = document.querySelector(".tbody")
    var noOrders = document.querySelector(".no-orders")
    if (tbody.innerText == '') {
      tbody.parentNode.remove()
      noOrders.style.display = "block"
    }
  </script>
</body>

</html>