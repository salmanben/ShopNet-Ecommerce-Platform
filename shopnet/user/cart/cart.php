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
$sql = "select p.* from product p join cart c on c.product_id = p.id where c.buyer_id = '$id' order by c.date desc";
$result = mysqli_query($connect, $sql);
if (mysqli_num_rows($result) == 0)
{
  header("location:../home/index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Dosis&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="cart.css">
  <link rel="stylesheet" href="../layout/layout.css">
  <script src="https://www.paypal.com/sdk/js?client-id=ATAWw7MAe4zs3x9vDxGr5BsJlzq0h4ypwWGEHKf3P_oQT5esVhUr1AY9O3nUO2y96CdTVJWm7MVGhy3P&currency=USD&disable-funding=credit,card"></script>
  <script defer src="cart.js"></script>
  <script defer src="../layout/layout.js"></script>
  <link rel="icon" href="../../icon.png">
  <title>Cart</title>
</head>

<body>
  <?php  echo $header; ?>
  <div class="container">
   <div class="main">
    <h2>Shopping Cart</h2>
    <div class="content">
      <div class="products">
        <div class="select-all">
          <input type="checkbox" name="" id="">
          <h3>Select All</h3>
        </div>
        <ul>
          <?php
          while ($row = mysqli_fetch_assoc($result)) {
            $variants = [];
            $sql = "SELECT id, name from product_variants where product_id = {$row['id']} and status = 1";
                    $result_pv = mysqli_query($connect, $sql);
                    if (mysqli_num_rows($result) > 0){
                      while($row_pv = mysqli_fetch_assoc($result_pv))
                      {
                        $sql = "SELECT * from product_variant_items where variant_id = {$row_pv['id']} and status = 1";
                        $result_pvi = mysqli_query($connect, $sql);
                        $product_variant_items = mysqli_fetch_all($result_pvi, MYSQLI_ASSOC);
                        if (count($product_variant_items) > 0)
                        {
                          $variants[$row_pv['name']] = $product_variant_items;
                        }
                      }
                    }
            echo '           
               <li id = ' . $row["id"] . '>
                <div>
                  <input  type="checkbox" name="" id="">
                  <a href="../product_details/product_details.php?id=' . $row["id"] . '">
                    <img src="../../upload/' . $row["image"] . '" alt="">
                  </a>
                  <h3>' . $row["title"] . '</h3>
                </div>
                <div class="variants">';?>
                <?php
                foreach($variants as $key=>$value)
                {
                  
                  echo '
                  <div>
                  <label>'.$key.':</label>
                   <select name="" class="'.$key.'" id="">
                      ';?>
                    <?php
                        for ($i = 0; $i < count($value); $i++)
                        {
                          $selected = $value[$i]['is_default'] == 1 ? 'selected' : '';
                         echo  '<option data-price='.$value[$i]['price'].' '.$selected .' value="'.$value[$i]['id'].'">'.$value[$i]['name'].'</option>';
                        } 
                        echo '
                           </select>
                         </div>';
                  
                    }
                echo '
                  </div>
                  <div>
                  <div class="count">
                    <span class="plus">+</span>
                    <span data-count = "' . $row["count"] . '"  class="num">1</span>
                    <span class="moins">-</span>
                  </div>
                  <p>$<span class="price">' . $row["price"] . '</span></p>
                  <p onclick = "rmProduct(' . $row["id"] . ')"><i class="fa-solid fa-x"></i></p>
                  </div>
                
              </li>';
          }
          ?>

        </ul>
      </div>
      <div class="payer">
        <h3>Total: $<span class="total">0.00</span></h3>
        <button class="payerBtn">Payer</button>
      </div>
    </div>
   </div>
  </div>
  <?php  echo $footer; ?>
</body>
</html>