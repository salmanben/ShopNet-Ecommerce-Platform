<?php
include "../../../connect_DB.php";
session_start();
if (!isset($_SESSION['id'])) {
    exit;
}
if ($_SESSION['role'] !== 'seller') {
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']))
{
    if ($_GET['action'] == 'create') {
        if (
            $_POST["title"] == '' ||
            $_POST["long_description"] == '' ||
            $_POST["short_description"] == '' ||
            $_POST["price"] == '' ||
            $_POST["count"] == '' ||
            $_POST["category_id"] == ''
        ) {
            echo json_encode(['status' => 'error', 'message' => "You must fill all the fields."]);
            exit();
        }
    
        if ($_FILES["file"]['error'] == 4) {
            echo json_encode(['status' => 'error', 'message' => "You must choose an image for your product."]);
            exit();
        }
    
        $title = mysqli_real_escape_string($connect, trim($_POST["title"]));
        $title = ucfirst($title);
        $long_description = mysqli_real_escape_string($connect, trim($_POST["long_description"]));
        $long_description = ucfirst($long_description);
        $short_description = mysqli_real_escape_string($connect, trim($_POST["short_description"]));
        $short_description = ucfirst($short_description);
        $price = mysqli_real_escape_string($connect, trim($_POST["price"]));
        $count = mysqli_real_escape_string($connect, trim($_POST["count"]));
        $category_id = mysqli_real_escape_string($connect, trim($_POST["category_id"]));
        $status = mysqli_real_escape_string($connect, trim($_POST["status"]));

        if (strlen($title) > 30) {
            echo json_encode(['status' => 'error', 'message' => "The title must be less than 30 characters."]);
            exit();
        }
        if (strlen($long_description) > 1000) {
            echo json_encode(['status' => 'error', 'message' => "The long  description is too long."]);
            exit();
        }
        if (strlen($short_description) > 150) {
            echo json_encode(['status' => 'error', 'message' => "The short  description is too long."]);
            exit();
        }
        if(!filter_var($price, FILTER_VALIDATE_FLOAT))
        {
            echo json_encode(['status' => 'error', 'message' => "Invalid price."]);
            exit();
        }
        if(!filter_var($count, FILTER_VALIDATE_INT))
        {
            echo json_encode(['status' => 'error', 'message' => "Invalid count."]);
            exit();
        }

    
        $arr_ext = ["png", "jfif", "jpeg", "webp", "jpg"];
        $arr = explode(".", $_FILES["file"]["name"]);
        $ext = strtolower(end($arr));
        if (!in_array($ext, $arr_ext)) {
            echo json_encode(['status' => 'error', 'message' => "Invalid image, the image must be one of these types: png, jpeg, jfif, webp, jpg."]);
            exit();
        }
        if ($_FILES["file"]["size"] > 2000000) {
            echo json_encode(['status' => 'error', 'message' => "Invalid image, the size of the image shouldn't exceed 2MB."]);
            exit();
        }
    
        $image = uniqid() . '.' . $ext;
    
        move_uploaded_file(
            $_FILES["file"]["tmp_name"],
            "../../../upload/" . $image
        );
        $sql = "INSERT INTO product (title, long_description, short_description, price, count, category_id, image, seller_id, status)
               VALUES ('$title', '$long_description', '$short_description', '$price', '$count', '{$_POST['category_id']}', '$image', '{$_SESSION['id']}', $status)";
        $result = mysqli_query($connect, $sql);
    
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => "Product created successfully."]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
        }
    }
    
    else if ($_GET['action'] == 'update') {
        if (
            $_POST["title"] == '' ||
            $_POST["long_description"] == '' ||
            $_POST["short_description"] == '' ||
            $_POST["price"] == '' ||
            $_POST["count"] == '' ||
            $_POST["category_id"] == ''
        ) {
            echo json_encode(['status' => 'error', 'message' => "You must fill all the fields."]);
            exit();
        }


        $title = mysqli_real_escape_string($connect, trim($_POST["title"]));
        $title = ucfirst($title);
        $long_description = mysqli_real_escape_string($connect, trim($_POST["long_description"]));
        $long_description = ucfirst($long_description);
        $short_description = mysqli_real_escape_string($connect, trim($_POST["short_description"]));
        $short_description = ucfirst($short_description);
        $price = mysqli_real_escape_string($connect, trim($_POST["price"]));
        $count = mysqli_real_escape_string($connect, trim($_POST["count"]));
        $category_id = mysqli_real_escape_string($connect, trim($_POST["category_id"]));
        $status = mysqli_real_escape_string($connect, trim($_POST["status"]));

        if (strlen($title) > 30) {
            echo json_encode(['status' => 'error', 'message' => "The title must be less than 30 characters."]);
            exit();
        }
        if (strlen($long_description) > 1000) {
            echo json_encode(['status' => 'error', 'message' => "The long  description is too long."]);
            exit();
        }
        if (strlen($short_description) > 150) {
            echo json_encode(['status' => 'error', 'message' => "The short  description is too long."]);
            exit();
        }
        if(!filter_var($price, FILTER_VALIDATE_FLOAT))
        {
            echo json_encode(['status' => 'error', 'message' => "Invalid price."]);
            exit();
        }
        if(!filter_var($count, FILTER_VALIDATE_INT))
        {
            echo json_encode(['status' => 'error', 'message' => "Invalid count."]);
            exit();
        }


        $sql = "SELECT image from product where id='{$_POST["product_id"]}'";
        $result = mysqli_query($connect, $sql);
        $image =  mysqli_fetch_assoc($result)['image'];
        if ($_FILES["file"]['error'] == 0) {
            $arr_ext = ["png", "jfif", "jpeg", "webp", "jpg"];
            $arr = explode(".", $_FILES["file"]["name"]);
            $ext = strtolower(end($arr));
            if (!in_array($ext, $arr_ext)) {
                echo json_encode(['status' => 'error', 'message' => "Invalid image, the image must be one of these types: png, jpeg, jfif, webp, jpg."]);
                exit();
            }
            if ($_FILES["file"]["size"] > 2000000) {
                echo json_encode(['status' => 'error', 'message' => "Invalid image, the size of the image shouldn't exceed 2MB."]);
                exit();
            }
            if (file_exists("../../../upload/" . $image)) {
                unlink("../../../upload/" . $image);
            }
            $image = uniqid() . '.' . $ext;

            move_uploaded_file(
                $_FILES["file"]["tmp_name"],
                "../../../upload/" . $image
            );
        }



        $sql = "UPDATE product 
            SET title = '$title', 
            long_description = '$long_description', 
            short_description = '$short_description', 
            price = '$price', 
            count = '$count', 
            category_id = '{$_POST['category_id']}', 
            image = '$image' ,
            status = '$status'
            WHERE id = '{$_POST['product_id']}'";

        $result = mysqli_query($connect, $sql);

        if ($result) {
            echo json_encode(['status' => 'success', 'message' => "Product updated successfully."]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
        }
    }    
    else if ($_GET['action'] == 'delete') {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data);
        $product_id = $data->id;
        $sql =  "SELECT * from product where id='$product_id' and seller_id='{$_SESSION['id']}'";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            $sql =  "SELECT * from order_products where product_id='$product_id'";
            $result = mysqli_query($connect, $sql);
            if (mysqli_num_rows($result) > 0) {
                echo json_encode(['status' => 'error', 'message' => "You can't delete a product that has orders."]);
            } else {
                $sql =  "SELECT image from product where id='$product_id'";
                $result = mysqli_query($connect, $sql);
                $image = mysqli_fetch_assoc($result)['image'];
                if (file_exists('../../../upload/' . $image)) {
                    unlink('../../../upload/' . $image);
                }
                $sql = "DELETE FROM product where id='$product_id'";
                $result = mysqli_query($connect, $sql);
                if ($result) {
                    echo json_encode(['status' => 'success', 'message' => "Product deleted successfully."]);
                } else {
                    echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
                }
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
        }
    }
    
}
else if (isset($_GET['action']) && $_GET['action'] == 'switch_status')
{
    $sql = "UPDATE product set status = {$_GET['status']} where id = '{$_GET['id']}'" ;
    $result = mysqli_query($connect, $sql);
    if (!$result)
       echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
    else
       echo json_encode(['status' => 'success']);
}


?>