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
$id = $_SESSION['id'];
$product_id = $_GET['product_id'];
$sql = "SELECT * from product where id='$product_id' and seller_id='$id'";
$result = mysqli_query($connect, $sql);
if (mysqli_num_rows($result)  == 0)
{
    http_response_code(404);
    exit;
}
$row = mysqli_fetch_assoc($result);
$sql = "SELECT id, name from category where status = 1";
$result_category = mysqli_query($connect, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/layout.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Product | Update</title>
    <link rel="icon" href="../../../icon.png">
    <script defer src="../scripts/handle_actions.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php echo $aside ?>
        <div class="container">
            <?php echo $header ?>
            <div class="content">
                <h2>Product</h2>
                <div class="card">
                    <div class="card-header">
                        <h4> Update Product</h4>
                    </div>
                    <div class="card-body">
                        <p class="message">
                        </p>
                        <img class="product_img" src="../../../upload/<?php echo $row['image'] ?>" alt="">
                        <form onsubmit="updateItem(event)" action="handle_actions.php" method="post" enctype="multipart/form-data">
                            <div>
                                <input type="hidden" name="product_id" value="<?php echo $row['id'] ?>">
                            </div>
                            <div>
                                <label for="title">Title:</label>
                                <input class="title" value="<?php echo $row['title'] ?>" type="text" name="title" placeholder="Title" id="title">
                            </div>
                            <div>
                                <label for="short_description">Short Description:</label>
                                <input type="text" name="short_description" value="<?php echo $row['short_description'] ?>" placeholder="Short Description" id="short_description">
                            </div>
                            <div>
                                <label for="long_description">Long Description:</label>
                                <textarea name="long_description" placeholder="Long Description" id="long_description" cols="30" rows="10"><?php echo $row['long_description'] ?></textarea>
                            </div>
                            <div>
                                <label for="price">Price ($):</label>
                                <input class="price" value="<?php echo $row['price'] ?>" type="text" name="price" placeholder="Price ($)" id="price">
                            </div>
                            <div>
                                <label for="count">Count:</label>
                                <input class="count" value="<?php echo $row['count'] ?>" min='1' type="number" name="count" placeholder="Count" id="count">
                            </div>
                            <div>
                                <label for="image">Image:</label>
                                <input onchange="change_img(event)" class="image" type="file" name="file" id="image">
                            </div>
                            <div>
                                <label for="category">Category:</label>
                                <select name="category_id" class="category">
                                    <option value="">Select</option>
                                    <?php
                                    $category_id = $row['category_id'];
                                    while ($category_row = mysqli_fetch_assoc($result_category)) {
                                        $selected = $category_row["id"] == $category_id ? 'selected' : '';
                                        echo '<option ' . $selected . ' value="' . $category_row["id"] . '">' . $category_row["name"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div>
                                <label for="status">Status:</label>
                                <select name="status" class="type" id="status">
                                    <option <?php if ($row['status'] == 1) echo "selected" ?> value="1">Active</option>
                                    <option <?php if ($row['status'] == 0) echo "selected" ?> value="0">Inactive</option>
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