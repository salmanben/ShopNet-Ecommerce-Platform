<?php
include "../../../connect_DB.php";
include('../layout/aside.php');
include('../layout/header.php');
if (!isset($_SESSION['id'])) {
    header("location:../login/login.php");
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    http_response_code(404);
    exit;
}
if (!isset($_GET['variant_id']) || !isset($_GET['id']))
{
    http_response_code(404);
    exit; 
}
$sql = "SELECT name  FROM product_variants WHERE id = '{$_GET['variant_id']}'";
$result = mysqli_query($connect,$sql);
if (mysqli_num_rows($result) == 0)
{
    http_response_code(404);
    exit;
}
$variant_name = mysqli_fetch_assoc($result)['name'];

$sql = "SELECT * FROM product_variant_items WHERE id = '{$_GET['id']}'";
$result = mysqli_query($connect, $sql);
$product_variant_item = mysqli_fetch_assoc($result);
if ($product_variant_item  == null)
{
    http_response_code(404);
    exit;  
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/layout.css">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Product Variant Items | Update</title>
    <link rel="icon" href="../../../icon.png">
    <script defer src="../scripts/handle_actions.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php echo $aside ?>
        <div class="container">
            <?php echo $header ?>
            <div class="content">
                <h2>Product Variant Items</h2>
                <a class="back-link" href="product_variant_items.php?variant_id=<?php echo $_GET['variant_id'] ?>"><i class="fa-solid fa-left-long"></i>Variant Items</a>
                <h4>Variant: <span><?php echo  $variant_name; ?></span></h4>
                <div class="card">
                    <div class="card-header">
                        <h4> Update Variant Item</h4>
                    </div>
                    <div class="card-body">
                        <p class="message">
                        </p>
                        <form onsubmit="updateItem(event)" action="handle_actions.php" method="post" enctype="multipart/form-data">
                            <div>
                                <input type="hidden" name="variant_id" value="<?php echo $_GET['variant_id']; ?>">
                            </div>
                            <div>
                                <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                            </div>
                            <div>
                                <label for="name">Name:</label>
                                <input class="name" value="<?php echo $product_variant_item['name'] ?>" type="text" name="name" placeholder="Name" id="name">
                            </div>
                            <div>
                                <label for="price">Price ($):</label>
                                <input type="text" value="<?php echo $product_variant_item['price'] ?>" placeholder="Price ($)" name="price" id="price">
                            </div>
                            <div>
                                <label for="is_default">Is Default:</label>
                                <select name="is_default" class="type" id="is_default">
                                    <option value="">Select</option>
                                    <option <?php if ($product_variant_item['is_default'] == 1) echo "selected" ?> value="1">Yes</option>
                                    <option <?php if ($product_variant_item['is_default'] == 0) echo "selected" ?> value="0">No</option>
                                </select>
                            </div>
                            <div>
                                <label for="status">Status:</label>
                                <select name="status" class="type" id="status">
                                    <option <?php if ($product_variant_item['status'] == 1) echo "selected" ?> value="1">Active</option>
                                    <option <?php if ($product_variant_item['status'] == 0) echo "selected" ?> value="0">Inactive</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="submit-btn">Update</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>