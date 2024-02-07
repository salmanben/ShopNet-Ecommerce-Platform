<?php
include "../../connect_DB.php";
include '../layout/layout.php';
if (isset($_GET['category_id']) and $_GET['category_id'] != '0')
    $sql = "SELECT p.*, c.name FROM product p join category c on p.category_id = c.id  where c.id = '{$_GET['category_id']}' and   count > 0 and p.status = 1 order by p.id desc limit 40  ";
else
    $sql = "SELECT p.*, c.name FROM product p join category c on p.category_id = c.id  where count > 0 and p.status = 1 order by p.id desc limit 40  ";

$result_products = mysqli_query($connect,$sql);

$sql = "SELECT * FROM category where  status = 1";
$result_categories = mysqli_query($connect, $sql);


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../layout/layout.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>ShopNet</title>
    <link rel="icon" href="../../icon.png">
    <script defer src="script.js"></script>
    <script defer src="../layout/layout.js"></script>
  </head>
  <body>
    <?php  echo $header ?>
    <div class="characteristic">
      <img src="img.png" alt="image">
      <p> Discover unbeatable deals and shop with confidence on our platform, where the best offers await you! </p>
      <div class="spans">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
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
             <a href="?category_id=0">
              <i class="fa-solid fa-shop"></i>
              <span>All</span>
             </a>
          </li>
          <?php
             
              while($row_cat = mysqli_fetch_assoc($result_categories))
              {
                $is_active =( isset($_GET['category_id']) and $_GET['category_id'] == $row_cat["id"]) ? 'active' : '';
                echo ' <li class="'.$is_active.'">
                        <a href="?category_id='.$row_cat["id"].'">
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
          <div class="products"></div>
        </div>
        <div class="spans">
          <div>
            <span class="active"></span>
          </div>
          <div>
            <span></span>
          </div>
          <div>
            <span></span>
          </div>
          <div>
            <span></span>
          </div>
          <div>
            <span></span>
          </div>
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