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
if (!isset($_SESSION['subtotal']) || !isset($_SESSION['user_address']) 
|| !isset($_SESSION['shipping_method']) || !isset($_SESSION['cart']))
{
    header("location:../cart/cart.php");
    exit;
}

$subtotal = $_SESSION['subtotal'];
$shippingFee = json_decode($_SESSION['shipping_method'], true)['cost'];
if (isset($_SESSION['coupon'])) {
    $coupon = json_decode($_SESSION['coupon'], true);
    if ($coupon['type'] == 'amount') {
        $discount = $coupon['cost'];
    } else {
        $discount = round((($subtotal * $coupon['cost']) / 100), 2);
    }
} else
    $discount = 0;
$total = round($subtotal + $shippingFee - $discount, 2);
$_SESSION['amount'] = $total;

$sql = "select * from paypal order by id desc limit 1";
$result = mysqli_query($connect, $sql);
$row_paypal = mysqli_fetch_assoc($result);
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
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $row_paypal['client_id'] ?>&currency=USD&disable-funding=credit,card"></script>
    <link rel="stylesheet" href="../layout/layout.css">
    <script defer src="../layout/layout.js"></script>
    <script defer src="finish_payment.js"></script>
    <link rel="icon" href="../../icon.png">
    <title>Payment</title>
</head>

<body>
    <?php echo $header; ?>
    <div class="main">
        <div class="invoice">
            <div>
                <h4>Subtotal:</h4>
                <h5>$<span class="subtotal-value"><?php echo $subtotal ?></span></h5>
            </div>
            <div>
                <h4>Discount:</h4>
                <h5>$<span class="discount-value"><?php echo $discount ?></span></h5>
            </div>
            <div>
                <h4>Shipping fee:</h4>
                <h5>$<span class="shipping-fee"><?php echo $shippingFee ?></span></h5>
            </div>
            <div class="total">
                <h3>Total:</h3>
                <h4>$<span class="total-value"><?php echo $total ?></span></h4>
            </div>
            <div id="paypal-button-container"></div>
        </div>
    </div>
    <?php echo $footer; ?>
    <style>
        .main {
            margin: 50px 10px;
            min-height: calc(100vh - 292.46px);
        }

        .invoice {
            margin: auto;
            max-width: 350px;
            height: fit-content;
            padding: 10px;
            border: solid 1px #ddd;
            background-color: white;
            box-shadow: 0 0 3px #ddd;
            border-radius: 5px;
        }

        .invoice h4 {
            font-weight: 500;
        }


        .invoice div {
            margin-bottom: 15px;
            display: flex;
        }

        .invoice h5 {
            margin-left: 10px;
            font-size: 16px;
            font-weight: 500;
        }

        .invoice .total h3 {
            margin-right: 10px;
            font-weight: 500;
            font-size: 20px;
        }

        .invoice .total h4 {
            font-weight: 500;
            font-size: 18px;
        }
        #paypal-button-container{
            position: relative;
            top:25px
        }
    </style>
</body>

</html>