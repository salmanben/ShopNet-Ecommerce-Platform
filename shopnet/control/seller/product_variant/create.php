<?php
include "../../../connect_DB.php";
include('../layout/aside.php');
include('../layout/header.php');
if (!isset($_SESSION['id'])) {
    header("location:../../../auth/login/login.php");
    exit;
}
if ($_SESSION['role'] !== 'seller') {
    http_response_code(404);
    exit;
}
if (!isset($_GET['product_id']))
{
    http_response_code(404);
    exit; 
}
$sql = "SELECT title  FROM product WHERE id = '{$_GET['product_id']}' and seller_id = '$id'";
$result = mysqli_query($connect, $sql);
if (mysqli_num_rows($result) == 0) {
    http_response_code(404);
    exit;
}
$product_title = mysqli_fetch_assoc($result)['title'];


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
    <title>Product Variants | Create</title>
    <link rel="icon" href="../../../icon.png">
    <script defer src="../scripts/handle_actions.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php echo $aside ?>
        <div class="container">
            <?php echo $header ?>
            <div class="content">
                <h2>Product Variants</h2>
                <a class="back-link" href="product_variants.php?product_id=<?php echo $_GET['product_id'] ?>"><i class="fa-solid fa-left-long"></i>Variants</a>
                <h4>Product: <span><?php echo  $product_title; ?></span></h4>
                <div class="card">
                    <div class="card-header">
                        <h4> Create Product Variant</h4>
                    </div>
                    <div class="card-body">
                        <p class="message">
                        </p>
                        <form onsubmit="createItem(event)" action="handle_actions.php" method="post" enctype="multipart/form-data">
                            <div>
                                <input type="hidden" name="product_id" value="<?php echo $_GET['product_id']; ?>">
                            </div>
                            <div>
                                <label for="name">Name:</label>
                                <input class="title" type="text" name="name" placeholder="Name" id="name">
                            </div>
                            <div>
                                <label for="status">Status:</label>
                                <select name="status" class="status" id="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="submit-btn">Create</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>