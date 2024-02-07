<?php
include "../../../connect_DB.php";
session_start();
if (!isset($_SESSION['id'])) {
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    exit;
}
$id = $_SESSION['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action'])) {
    if ($_GET['action'] == 'update') {
        if (
            $_POST["name"] == '' ||
            $_POST["price"] == '' ||
            $_POST["is_default"] == ''
        ) {
            echo json_encode(['status' => 'error', 'message' => "You must fill all the fields."]);
            exit();
        }
    
        $name = mysqli_real_escape_string($connect, trim($_POST["name"]));
        $status = mysqli_real_escape_string($connect, trim($_POST["status"]));
        $price = mysqli_real_escape_string($connect, trim($_POST["price"]));
        $is_default = mysqli_real_escape_string($connect, trim($_POST["is_default"]));
        if (strlen($name) > 30) {
            echo json_encode(['status' => 'error', 'message' => "The name must be less than 30 characters."]);
            exit();
        }
    
        if ($is_default == 1 && $price!= 0) {
            echo json_encode(['status' => 'error', 'message' => "The default variant item must have a price equal to 0."]);
            exit();
        }

        if($price != 0 and !filter_var($price, FILTER_VALIDATE_FLOAT))
        {
            echo json_encode(['status' => 'error', 'message' => "Invalid price."]);
            exit();
        }
    
        $name = ucfirst($name);
        $sql = "UPDATE product_variant_items
            SET name = '$name', 
            status = '$status', 
            is_default = '$is_default', 
            price = '$price', 
            variant_id = '{$_POST['variant_id']}'
            WHERE id = '{$_POST['id']}'";
    
        $result = mysqli_query($connect, $sql);
    
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => "Product variant item updated successfully."]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
        }
    } elseif ($_GET['action'] == 'delete') {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data);
        $id = $data->id;
        $sql = "DELETE FROM product_variant_items where id='$id'";
        $result = mysqli_query($connect, $sql);
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => "Product variant item deleted successfully."]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
        }
    }
    
}
else if (isset($_GET['action']) && $_GET['action'] == 'switch_status')
{
    $sql = "UPDATE product_variant_items set status = {$_GET['status']} where id = '{$_GET['id']}'" ;
    $result = mysqli_query($connect, $sql);
    if (!$result)
       echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
    else
       echo json_encode(['status' => 'success']);
}
