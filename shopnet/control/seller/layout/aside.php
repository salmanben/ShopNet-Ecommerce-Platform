<?php

function set_active($path_arr)
{
    foreach ($path_arr as $path) {
        if (str_contains($_SERVER["SCRIPT_NAME"], $path)) {
            return "active";
        }
    }
    return "";
}

function set_url($path)
{
    if (str_contains($_SERVER["SCRIPT_NAME"], $path)) {
        return "";
    } else {
        return "../" . $path;
    }
}

$aside =
    '<aside>
<h1>
  <img src="../../../icon.png" alt="logo"> Shop <span>Net</span>
</h1>
<nav>
  <ul>
    <li class="' .
    set_active(["dashboard/dashboard.php"]) .
    '">
      <a href="' . set_url("dashboard/dashboard.php") . '">
      <i class="fas fa-chart-bar"></i>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="' .
    set_active([
        "product/product.php",
        "product/create.php",
        "product/edit.php",
        "product_variant/product_variants.php",
        "product_variant/create.php",
        "product_variant/edit.php",
        "product_variant_item/product_variant_items.php",
        "product_variant_item/create.php",
        "product_variant_item/edit.php",
    ]) .
    '">
      <a href="' . set_url("product/product.php") . '">
        <i class="fa-solid fa-shop"></i>
        <span>Product</span>
      </a>
    </li>
    <li class="' .
    set_active(["orders/orders.php", "orders/show.php"]) .
    '">
      <a href="' . set_url("orders/orders.php") . '">
        <i class="fas fa-shopping-cart orders-icon"></i>
        <span>Orders</span>
      </a>
    </li>
    <li class="' .
    set_active(["messaging/messaging.php"]) .
    '">
      <a href="' . set_url("messaging/messaging.php") . '">
        <i class="fa-brands fa-rocketchat"></i>
        <span>Messaging</span>
      </a>
    </li>
    <li class="' .
    set_active(["withdraw/withdraw.php"]) .
    '">
    <a href="' . set_url("withdraw/withdraw.php") . '">
      <i class="fa-solid fa-money-bill-transfer"></i>
      <span>Withdraw</span>
    </a>
  </li>
    <li class="' .
    set_active(["settings_seller/settings_seller.php"]) .
    '">
      <a href="' . set_url("settings_seller/settings_seller.php") . '">
        <i class="fa-sharp fa-solid fa-gear"></i>
        <span>Settings</span>
      </a>
    </li>
    <li>
      <a href="../../../logout.php">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Log out</span>
      </a>
    </li>
  </ul>
</nav>
</aside>';
?>
