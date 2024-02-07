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
if (!isset($_GET['order_id'])) {
   http_response_code(404);
   exit;
}
$id = $_SESSION['id'];
$sql = "SELECT * from orders where id = '{$_GET['order_id']}' ";
$result = mysqli_query($connect, $sql);
$row_order = mysqli_fetch_assoc($result);
if ($row_order == null) {
   http_response_code(404);
   exit;
}
$disabled = strtolower($row_order['status']) == 'completed' ? "disabled" : "";
$sql = "SELECT op.*, image, price from order_products op join product p
on op.product_id = p.id
where p.seller_id = '$id' and  order_id = '{$_GET['order_id']}'";
$result_order_products = mysqli_query($connect, $sql);
$rows_order_products = mysqli_fetch_all($result_order_products, MYSQLI_ASSOC);
if (count($rows_order_products) == 0) {

   http_response_code(404);
   exit;
}
$status = $rows_order_products[0]['status'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="../styles/show_order.css">
   <link rel="stylesheet" href="../styles/layout.css">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <title>Orders</title>
   <link rel="icon" href="../../../icon.png">
   <script defer src="../scripts/handle_actions.js"></script>
</head>

<body>
   <div class="wrapper">
      <?php echo $aside; ?>
      <div class="container">
         <?php echo $header; ?>
         <div class="content">
            <h2>Order Details</h2>
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
               <div class="order-products">
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
                        for ($i = 0; $i < count($rows_order_products); $i++) {
                           echo '
                         <tr>
                            <td>' . $rows_order_products[$i]["id"] . '</td>
                            <td><a href="../product/edit.php?product_id=' . $rows_order_products[$i]["product_id"] . '"><img src = "../../../upload/' . $rows_order_products[$i]["image"] . '"></a></td>
                            <td class="variants">
                            ';
                           $variants = json_decode($rows_order_products[$i]['variants'], true);
                           $unit_price = $rows_order_products[$i]["price"];
                           foreach ($variants as $key => $value) {
                              $unit_price += $value["price"];
                              echo '<p><span>' . $key . ':</span><span>' . $value["name"] . '($' . $value["price"] . ')</span></p>';
                           }
                           $subtotal += $unit_price * $rows_order_products[$i]["qty"];
                           echo '
                            </td>
                            <td class="unit-price">$' . $unit_price . '</td>
                            <td>' . $rows_order_products[$i]["qty"] . '</td>
                            <td class="total">$' . $unit_price * $rows_order_products[$i]["qty"] . '</td>
                         </tr>
                         ';
                        }

                        ?>

                     </tbody>
                  </table>
               </div>
               <div class="invoice">
                  <div>
                     <h5><span>Total: </span><span>$<?php echo $subtotal ?></span></h5>
                  </div>
               </div>
               <div class="order-status">
                  <select <?php echo $disabled ?> data-order-id="<?php echo $row_order['id'] ?>" onchange="switch_order_status(event)" name="status" id="">
                     <?php
                     $selected_pending = (strtolower($status) == 'pending') ? 'selected' : '';
                     $selected_dropped_off = (strtolower($status) == 'dropped off') ? 'selected' : '';
                     ?>
                     <option value="Pending" <?php echo $selected_pending; ?>>Pending</option>
                     <option value="Dropped Off" <?php echo $selected_dropped_off; ?>>Dropped Off</option>
                  </select>
               </div>

            </div>
         </div>
      </div>
   </div>

</body>

</html>