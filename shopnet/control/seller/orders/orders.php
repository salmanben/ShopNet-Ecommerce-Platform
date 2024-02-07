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
$sql = " SELECT o.* from orders o
join order_products op on o.id = op.order_id
join product p on p.id = op.product_id
where p.seller_id = '$id' GROUP BY o.id  order by o.date desc; 
";
$result = mysqli_query($connect, $sql);
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
  <title>Orders</title>
  <link rel="icon" href="../../../icon.png">
  <script defer src="../scripts/table_script.js"></script>
</head>

<body>
  <div class="wrapper">
    <?php echo $aside; ?>
    <div class="container">
      <?php echo $header; ?>
      <div class="content">
        <h2>Orders</h2>
        <div class="create-search">
          <div class="search">
            <input type="search" placeholder="Search by invoice id..." id="">
            <i class="fa-solid fa-magnifying-glass"></i>
          </div>
        </div>
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
  </div>
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
  </script>

</body>

</html>