<?php
include "../../connect_DB.php";
include '../layout/layout.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "select * from product where id ='$id'";
  $result = mysqli_query($connect, $sql);
  $row = mysqli_fetch_assoc($result);
  if ($row == null) {
    http_response_code(404);
    exit;
  }
} else {
  http_response_code(404);
  exit;
}
$variants = [];
$sql = "SELECT id, name from product_variants where product_id = {$row['id']} and status = 1";
$result_pv = mysqli_query($connect, $sql);
if (mysqli_num_rows($result) > 0) {
  while ($row_pv = mysqli_fetch_assoc($result_pv)) {
    $sql = "SELECT * from product_variant_items where variant_id = {$row_pv['id']} and status = 1";
    $result_pvi = mysqli_query($connect, $sql);
    $product_variant_items = mysqli_fetch_all($result_pvi, MYSQLI_ASSOC);
    if (count($product_variant_items) > 0) {
      $variants[$row_pv['name']] = $product_variant_items;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="product_details.css">
  <link rel="stylesheet" href="../layout/layout.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Product Details</title>
  <link rel="icon" href="../../icon.png">
  <script src="https://www.paypal.com/sdk/js?client-id=ATAWw7MAe4zs3x9vDxGr5BsJlzq0h4ypwWGEHKf3P_oQT5esVhUr1AY9O3nUO2y96CdTVJWm7MVGhy3P&disable-funding=credit,card&currency=USD"></script>
  <script defer src="product_details.js"></script>
  <script defer src="../layout/layout.js"></script>
</head>

<body>
  <?php echo $header ?>
  <div class="main">
    <div class="card">
      <div class="product">
        <div class="container">
          <img src="../../upload/<?php echo $row['image'] ?>" alt="">
          <div class="background"></div>
        </div>
      </div>
      <div class="content">
        <h2><?php echo $row['title'] ?></h2>
        <p class="price"><?php echo $row['price'] ?>$</p>
        <p class="short_description">
          <?php echo $row['short_description'] ?>
        </p>

        <div class="variants">
          <?php
          foreach ($variants as $key => $value) {

            echo '
                  <div>
                    <h4>' . $key . ':</h4>
                  <p>
         '; ?>
          <?php
            for ($i = 0; $i < count($value); $i++) {

              echo  '<span>' . $value[$i]['name'] . '</span>';
            }
            echo '
                  </p>
                  </div>';
          }
          ?>
        </div>
        <div class="actions">
          <button onclick="addToCart(event)" id="<?php echo $row['id'] ?>" class='add-cart'><i class="fa-solid fa-cart-shopping"></i> Add To Cart</button>
          <a href="../store/store.php?id=<?php echo $row['seller_id'] ?>" class="visit-store">Visit Store<i class="fa-solid fa-right-long"></i></a>
          <a href="../messaging/messaging.php?id=<?php echo $row['seller_id'] ?>" class="contact-seller"><i class="fa-brands fa-rocketchat"></i></a>
        </div>
      </div>
    </div>
    <div class="long-description">
      <h2>Description: </h2>
      <p class="">
        <?php echo $row['long_description'] ?>
      </p>
    </div>
  </div>
  <?php echo $footer ?>
</body>

</html>