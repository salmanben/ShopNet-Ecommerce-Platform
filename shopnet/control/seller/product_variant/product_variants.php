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
$result = mysqli_query($connect,$sql);
if (mysqli_num_rows($result) == 0)
{
    http_response_code(404);
    exit;
}
$product_title = mysqli_fetch_assoc($result)['title'];
$sql = "SELECT *  FROM product_variants where product_id = '{$_GET['product_id']}' ORDER BY date desc";
$result = mysqli_query($connect,$sql);
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
    <title>Product Variants</title>
    <link rel="icon" href="../../../icon.png">
    <script defer src="../scripts/handle_actions.js"></script>
    <script defer src="../scripts/table_script.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php echo $aside ?>
        <div class="container">
            <?php echo $header ?>
            <div class="content">
                <h2>Product Variants</h2>
                <div class="create-search">
                <a href="create.php?product_id=<?php echo $_GET['product_id'] ?>" class="create_item">Create product variant</a>
                  <div class="search">
                     <input type="search" placeholder="Search by id..." id="">
                     <i class="fa-solid fa-magnifying-glass"></i>
                  </div>
               </div>
                <h4>Product: <span><?php echo  $product_title; ?></span></h4>
                <div class="table">
                <table>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Variant Items</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            <?php
                             
                            while ($row = mysqli_fetch_assoc($result)) {
                                $checked = $row["status"] ==  1 ? "checked" : "";
                                echo'
                                <tr class="item" id ="' . $row["id"] . '">
                                 <td>' . $row["id"] . '</td>
                                 <td>' . $row["name"] . '</td>
                                 <td><input onclick="switchStatus(event, \'' . $row["id"] . '\')" ' . $checked . ' type="checkbox" name="" id="" class="status"></td>
                                 <td><a class="variant_items" href="../product_variant_item/product_variant_items.php?variant_id=' . $row["id"] . '"><i class="fa-solid fa-gear"></i></a></td>
                                 <td><a class="update" href="edit.php?product_variant_id=' . $row["id"] . '&product_id='.$_GET['product_id'].'"><i class="fa-solid fa-pen-to-square"></i></a></td>
                                 <td><a href="" onclick="deleteItem(event)" data-product-id="'.$_GET['product_id'].'" class="delete"><i class="fa-solid fa-trash"></i></a></td>
                                </tr>';
                            }
                            
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>