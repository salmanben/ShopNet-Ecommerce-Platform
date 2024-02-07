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
$aside = '
    <aside>
        <h1>
            <img src="../../../icon.png" alt="logo"> Shop <span>Net</span>
        </h1>
        <nav>
            <ul>
                <li class="' . set_active(["dashboard/dashboard.php"]) . '">
                    <a href="' . set_url("dashboard/dashboard.php") . '">
                        <i class="fas fa-chart-bar"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="' . set_active(["product/product.php", "product/create.php", "product/edit.php", "product_variant/product_variants.php", "product_variant/create.php", "product_variant/edit.php", "product_variant_item/product_variant_items.php", "product_variant_item/create.php", "product_variant_item/edit.php"]) . '">
                    <a href="' . set_url("product/product.php") . '">
                        <i class="fa-solid fa-shop"></i>
                        <span>Product</span>
                    </a>
                </li>
                <li class="' . set_active(["category/category.php", "category/create.php", "category/edit.php"]) . '">
                    <a href="' . set_url("category/category.php") . '">
                       <i class="fa-solid fa-list"></i>
                       <span>Category</span>
                    </a>
                </li>
                <li class="' . set_active(["orders/orders.php", "orders/show.php"]) . '">
                    <a href="' . set_url("orders/orders.php") . '">
                        <i class="fas fa-shopping-cart orders-icon"></i>
                        <span>Orders</span>
                    </a>
                </li>
                <li class="' . set_active(["coupon/coupon.php", "coupon/create.php", "coupon/edit.php"]) . '">
                    <a href="' . set_url("coupon/coupon.php") . '">
                        <i class="fa-solid fa-tag"></i>
                        <span>Coupon</span>
                    </a>
                </li>
                <li class="' . set_active(["shipping/shipping.php", "shipping/create.php", "shipping/edit.php"]) . '">
                    <a href="' . set_url("shipping/shipping.php") . '">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>Shipping</span>
                    </a>
                </li>
                <li class="' . set_active(["admins_list/admins_list.php"]) . '">
                    <a href="' . set_url("admins_list/admins_list.php") . '">
                        <i class="fas fa-user-shield admin-icon"></i>
                        <span>Admins</span>
                    </a>
                </li>
                <li class="' . set_active(["sellers_list/sellers_list.php"]) . '">
                    <a href="' . set_url("sellers_list/sellers_list.php") . '">
                        <i class="fa-solid fa-users"></i>
                        <span>Sellers</span>
                    </a>
                </li>
                <li class="' . set_active(["buyers_list/buyers_list.php"]) . '">
                    <a href="' . set_url("buyers_list/buyers_list.php") . '">
                        <i class="fa-solid fa-users"></i>
                        <span>Buyers</span>
                    </a>
                </li>
                <li class="' . set_active(["settings/settings.php"]) . '">
                    <a href="' . set_url("settings/settings.php") . '">
                        <i class="fa-sharp fa-solid fa-gear"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="' . set_active(["withdraw/withdraw.php"]) . '">
                    <a href="' . set_url("withdraw/withdraw.php") . '">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span>Withdraw</span>
                    </a>
                </li>
                <li class="' . set_active(["contact/contact.php"]) . '">
                    <a href="' . set_url("contact/contact.php") . '">
                       <i class="fa-solid fa-envelope"></i>
                       <span>Contact</span>
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
