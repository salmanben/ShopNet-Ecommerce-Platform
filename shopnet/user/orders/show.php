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
if (!isset($_GET['order_id'])) {
   http_response_code(404);
   exit;
}
$sql = "SELECT * from orders where id = '{$_GET['order_id']}' and buyer_id='$id'";
$result = mysqli_query($connect, $sql);
$row_order = mysqli_fetch_assoc($result);
if ($row_order == null) {
   http_response_code(404);
   exit;
}
$sql = "SELECT op.*, image, price from order_products op join product p
on op.product_id = p.id
where order_id = '{$_GET['order_id']}'";
$result_order_products = mysqli_query($connect, $sql);
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
      <div class="details">
         <h4><span>Invoice Id:</span> <span><?php echo $row_order['invoice_id'] ?></span> </h4>
         <div class="info">
            <div class="shipping-info">
               <h5>Shipping Info:</h5>
               <ul>
                  <?php
                  $shipping_info  = json_decode($row_order['address_info'], true);

                  foreach ($shipping_info as $key => $value) {
                     echo '
                            <li><span>' . str_replace('_', " ", ucfirst($key)) . ': </span><span>' . $value . '</span></li>
                            ';
                  }
                  ?>
               </ul>
            </div>
            <div class="shipping-method">
               <?php
               $shipping_method  = json_decode($row_order['shipping_method'], true);
               ?>
               <h5><span>Shipping Method:</span><span><?php echo "{$shipping_method['name']}(\${$shipping_method['cost']})" ?> </span></h5>
               <h5><span>Date:</span><span><?php echo $row_order['date'] ?> </span></h5>
            </div>

         </div>
         <div class="table">
            <table>
               <thead>
                  <th>Id</th>
                  <th>Product</th>
                  <th>Variants</th>
                  <th>Unit Price</th>
                  <th>Quantity</th>
                  <th>Total</th>
               </thead>
               <tbody>
                  <?php
                  $subtotal = 0;
                  while ($row_order_products = mysqli_fetch_assoc($result_order_products)) {
                     echo '
                         <tr>
                            <td>' . $row_order_products["id"] . '</td>
                            <td><a href="../product_details/product_details.php?id=' . $row_order_products['product_id'] . '"><img src = "../../upload/' . $row_order_products["image"] . '"></a></td>
                            <td class="variants">
                            ';
                     $variants = json_decode($row_order_products['variants'], true);
                     $unit_price = $row_order_products["price"];
                     foreach ($variants as $key => $value) {
                        $unit_price += $value["price"];
                        echo '<p><span>' . $key . ':</span><span>' . $value["name"] . '($' . $value["price"] . ')</span></p>';
                     }
                     $subtotal += $unit_price * $row_order_products["qty"];
                     echo '
                            </td>
                            <td class="unit-price">$' . $unit_price . '</td>
                            <td>' . $row_order_products["qty"] . '</td>
                            <td class="total">$' . $unit_price * $row_order_products["qty"] . '</td>
                         </tr>
                         ';
                  }
                  ?>
               </tbody>
            </table>
         </div>
         <div class="invoice">
            <div>
               <h5><span>SubTotal: </span><span>$<?php echo $subtotal ?></span></h5>
               <h5><span>Total: </span><span>$<?php echo $row_order['amount'] ?></span></h5>
            </div>
            <div>
               <h5><span>Discount: </span><span>$</span> <?php echo  $subtotal + $shipping_method['cost'] - $row_order['amount'] ?></h5>
               <h5><span>Fee Shipping: </span>$<span><?php echo $shipping_method['cost'] ?></span></h5>
            </div>
         </div>

      </div>
   </div>
   <?php echo $footer; ?>
</body>

</html>