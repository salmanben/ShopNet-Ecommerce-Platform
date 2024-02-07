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
if (!isset($_SESSION['subtotal']) || !isset($_SESSION['cart'])) {
    header("location:../cart/cart.php");
    exit;
}
$subtotal = $_SESSION['subtotal'];
$sql = "SELECT * from shipping_method where status = 1";
$result = mysqli_query($connect, $sql);

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
    <link rel="stylesheet" href="payment.css">
    <link rel="stylesheet" href="../layout/layout.css">
    <script defer src="payment.js"></script>
    <script defer src="../layout/layout.js"></script>
    <link rel="icon" href="../../icon.png">
    <title>Payment</title>
</head>

<body>
    <?php echo $header; ?>
    <div class="main">
        <div class="form">
            <form action="">
                <p class="error"></p>
                <input type="hidden" class="shipping-method-id" name="shipping_method_id">
                <div class="name">
                    <div>
                        <label for="first_name">First Name:</label>
                        <input type="text" name="first_name" placeholder="first name" id="first_name">
                    </div>
                    <div>
                        <label for="last_name">Last Name:</label>
                        <input type="text" name="last_name" placeholder="last name" id="last_name">
                    </div>
                </div>
                <div>
                    <label for="address">Address:</label>
                    <textarea name="address" placeholder="address" id="address" cols="30" rows="10"></textarea>
                </div>
                <div>
                    <label for="zip">ZIP:</label>
                    <input type="number" placeholder="zip" name="zip" id="zip">
                </div>
                <div>
                    <label for="country">Country:</label>
                    <input type="text" name="country" placeholder="country" id="country">
                </div>
                <div>
                    <label for="city">City:</label>
                    <input type="text" name="city" placeholder="city" id="city">
                </div>
                <div>
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" placeholder="phone" id="phone">
                </div>
                <div>
                    <label for="email">Email:</label>
                    <input type="email" name="email" placeholder="email" id="email">
                </div>
                <button class='confirm' type="submit">Confirm</button>
            </form>

        </div>
        <div class="invoice">
            <div class="shipping">
                <h4>Shipping:</h4>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['type'] == 'min_cost') {
                        if ($subtotal >= $row['min_cost']) {
                            echo '
                                    <div>
                                        <input type="radio" data-cost="' . $row['cost'] . '" name="shipping_method" value="' . $row['id'] . '" id="' . $row['name'] . '">
                                        <label  for = "' . $row['name'] . '">' . $row['name'] . '($' . $row['cost'] . ')</label>
                                    </div>  
                               ';
                        }
                    } else {
                        echo '
                                 <div>
                                     <input type="radio" data-cost="' . $row['cost'] . '" name="shipping_method" value="' . $row['id'] . '" id="' . $row['name'] . '">
                                     <label  for = "' . $row['name'] . '">' . $row['name'] . '($' . $row['cost'] . ')</label>
                                 </div>  
                            ';
                    }
                }
                ?>
            </div>
            <div>
                <h4>Subtotal:</h4>
                <h5>$<span class="subtotal-value"><?php echo $subtotal ?></span></h5>
            </div>
            <div>
                <h4>Discount:</h4>
                <h5>$<span class="discount-value">0</span></h5>
            </div>
            <div>
                <h4>Shipping fee:</h4>
                <h5>$<span class="shipping-fee">0</span></h5>
            </div>
            <div class="total">
                <h3>Total:</h3>
                <h4>$<span class="total-value"><?php echo $subtotal ?></span></h4>
            </div>
            <div class="coupon">
                <input class="coupon-code" type="text" placeholder="Enter coupon code." name="coupon_code" id="">
                <button class="apply-coupon" onclick="applyCoupon()">Apply</button>
            </div>
        </div>
    </div>
    <?php echo $footer; ?>
</body>

</html>