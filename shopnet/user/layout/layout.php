<?php
include "../../connect_DB.php";
session_start();
$cart_count = 0;
if (isset($_SESSION['id']) and $_SESSION['role'] == 'buyer')
{
  $id = $_SESSION['id'];
  $sql = "SELECT COUNT(*) as count from cart where buyer_id = '$id'";
  $result_cart = mysqli_query($connect,$sql);
  $cart_count  = mysqli_fetch_assoc($result_cart)['count'];
}
if (str_contains($_SERVER['SCRIPT_NAME'], "home"))
{
  $url = "";
}
else{
  $url = "../home/index.php";
}
$header = '<header>
<h1>
  <a href="'.$url.'">
    <img src="../../icon.png" alt="logo"> Shop <span>Net</span>
  </a>
</h1>
<div class="search">
  <i class="fa-solid fa-magnifying-glass"></i>
  <input type="text" name="" placeholder="Write the product name, description..." id="">
</div>
<i class="fa-solid fa-bars"></i>
<i class="fa-solid fa-x"></i>
<nav>
  <ul>
    <li class="account">
      <i class="fa-solid fa-user"></i>
      <span>Account</span>
      <div>';

if (!isset($_SESSION['id'])) {
    $header .= '<a href="../../auth/signup/signup.php">
                    <i class="fas fa-user-plus"></i>
                    <span>Sign Up</span>
                  </a>
                  <a href="../../auth/login/login.php">
                    <i class="fas fa-sign-in-alt"></i>
                   <span>Log In</span>
                  </a>';
} else {
    $header .= '<a href="../orders/orders.php">
                   <i class="fas fa-shopping-cart"></i>
                   <span>Orders</span>
                 </a>
                 <a href="../followed/followed.php">
                   <i class="fas fa-users"></i>
                   <span>Followed</span>
                 </a>
                 <a href="../settings_buyer/settings_buyer.php">
                   <i class="fas fa-cog"></i>
                   <span>Settings</span>
                 </a>
                 <a href="../../logout.php">
                  <i class="fas fa-sign-out-alt"></i>
                  <span>Log Out</span>
                 </a>';
}

$header .= '</div>
    </li>
    <li>
      <a class="messaging" href="../messaging/messaging.php">
        <i class="fa-brands fa-rocketchat"></i>
        <span>Messaging</span>
      </a>
    </li>
    <li>
      <a cart-count="' . $cart_count . '" class="cart" href="../cart/cart.php">
        <i class="fa-solid fa-cart-shopping"></i>
        <span>Cart</span>
      </a>
    </li>
  </ul>
</nav>
</header>';

$footer = '<footer>
<div class="footer-content">
  <div class="footer-column">
    <h4>About Us</h4>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean blandit justo vitae ligula tempor, vel interdum turpis bibendum.</p>
  </div>
  <div class="footer-column">
    <h4>Customer Service</h4>
    <ul>
      <li>
        <a href="../contact/contact.php">
          <span>Contact Us</span>
        </a>
      </li>
    </ul>
  </div>
  <div class="footer-column">
    <h4>Stay Connected</h4>
    <p>Follow us on social media for the latest updates and promotions.</p>
    <div class="social-media-icons">
      <a href="#">
        <i class="fab fa-facebook"></i>
      </a>
      <a href="#">
        <i class="fab fa-twitter"></i>
      </a>
      <a href="#">
        <i class="fab fa-instagram"></i>
      </a>
    </div>
  </div>
</div>
<div class="footer-bottom">
  <p>&copy; 2023 ShopNet. All rights reserved.</a>
  </p>
</div>
</footer>';
?>
