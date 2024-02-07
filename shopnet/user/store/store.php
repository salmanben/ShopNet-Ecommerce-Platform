<?php
include "../../connect_DB.php";
include '../layout/layout.php';
if (!isset($_GET['id']))
{
  http_response_code(404);
  exit;
}
$seller_id = mysqli_real_escape_string($connect ,$_GET['id']);
$sql = "SELECT * FROM seller  where  id = '$seller_id'";
$result_seller = mysqli_query($connect, $sql);
if (mysqli_num_rows($result_seller) == 0)
{
  http_response_code(404);
  exit;
}
$seller = mysqli_fetch_assoc($result_seller);

if (isset($_GET['category_id']) and $_GET['category_id'] != '0')
    $sql = "SELECT p.*, c.name FROM product p join category c on p.category_id = c.id  where c.id = '{$_GET['category_id']}' and   count > 0 and p.status = 1 and seller_id = '$seller_id' order by p.id desc limit 40  ";
else
    $sql = "SELECT p.*, c.name FROM product p join category c on p.category_id = c.id  where count > 0 and p.status = 1 and seller_id = '$seller_id' order by p.id desc limit 40  ";

$result_products = mysqli_query($connect,$sql);

$sql = "SELECT * FROM category where  status = 1";
$result_categories = mysqli_query($connect, $sql);
$follow = "Follow";
if (isset($_SESSION['id']) and $_SESSION['role'] == 'buyer')
{
  $sql = "SELECT * FROM seller_followers where buyer_id = '{$_SESSION['id']}' and seller_id = '$seller_id'";
  $result = mysqli_query($connect, $sql);
  if (mysqli_num_rows($result) > 0)
     $follow = "Followed";
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="store.css">
    <link rel="stylesheet" href="../layout/layout.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Store</title>
    <link rel="icon" href="../../icon.png">
    <script defer src="store.js"></script>
    <script defer src="../layout/layout.js"></script>
  </head>
  <body>
    <?php  echo $header ?>

    <div class="wrapper">
      <div class="categories">
        <h4>
          <i class="fa-solid fa-bars"></i>
          <span>Categories</span>
        </h4>
        <ul>
          <li class="<?php
              if (empty($_GET['category_id']) or $_GET['category_id'] == '0')
                echo  "active " 
           ?>">
             <a href="?id=<?php echo $seller_id ?>&category_id=0">
              <i class="fa-solid fa-shop"></i>
              <span>All</span>
             </a>
          </li>
          <?php
             
              while($row_cat = mysqli_fetch_assoc($result_categories))
              {
                $is_active =( isset($_GET['category_id']) and $_GET['category_id'] == $row_cat["id"]) ? 'active' : '';
                echo ' <li class="'.$is_active.'">
                        <a href="?id='.$seller_id.'&category_id='.$row_cat["id"].'">
                          <i class="'.$row_cat["icon"].'"></i>
                          <span>'.$row_cat["name"].'</span>
                        </a>  
                      </li>';
              }
          ?>
        </ul>
      </div>
      <div class="container">
        <div class="content">
          <div class="banner"><img src="../../upload/<?php echo $seller['banner'] ?>" alt=""></div>
        </div>
        <div class="follow-contact">
            <button class="follow-btn"><?php echo $follow ?></button>
            <a href="../messaging/messaging.php?id=<?php echo $seller['id'] ?>" class="contact">Contact</a>
        </div>
        <div class="background"></div>
      </div>
    </div>
    <section> 
        <?php
          while($row = mysqli_fetch_assoc($result_products)){
                    echo '	
														<div class = "card" id = "'.$row['id'].'">
															<div>
                                  <a href = "../product_details/product_details.php?id='.$row['id'].'">
                                    <img src="../../upload/'.$row['image'].'" alt=""> 
                                  </a>
																	<h3>'.$row['title'].'</h3>
                                  <p class="category">'.$row['name'].'</p>
																	<p class="price">Price:
																		<span>$'.$row['price'].'</span>
																	</p>
																</div>
																<div class="shop">
																	<a onclick="addToCart(event)" href="" class="add-cart" id ="'.$row['id'].'">
                                    <i class="card-icon fas fa-shopping-bag"></i><span>Add to cart</span>
																	</a>
																</div>
                                <a href = "../product_details/product_details.php?id='.$row['id'].'" class="preview">
                                   <i class="fas fa-angle-double-right"></i>
                                </a>
															</div>
                   ';
          }
        ?> 
        <div class="view-more" onclick = "viewMore(event)">
            <button>View More</button>
        </div>
        <div class="no-data-found">
          <p>No products found.</p>
        </div>
    </section>
    <?php  echo $footer; ?>
  </body>
</html>