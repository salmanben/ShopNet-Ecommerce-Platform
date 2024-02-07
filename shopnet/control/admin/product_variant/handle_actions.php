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
        if ($_POST["name"] == '') {
            echo json_encode(['status' => 'error', 'message' => "You must fill all the fields."]);
            exit();
        }
    
        $name = mysqli_real_escape_string($connect, trim($_POST["name"]));
        $status = mysqli_real_escape_string($connect, trim($_POST["status"]));
        
        if (strlen($name) > 30) {
            echo json_encode(['status' => 'error', 'message' => "The name must be less than 30 characters."]);
            exit();
        }
    
        $name = ucfirst($name);
        $sql = "UPDATE product_variants
            SET name = '$name', 
            status = '$status', 
            product_id = '{$_POST['product_id']}'
            WHERE id = '{$_POST['product_variant_id']}'";
    
        $result = mysqli_query($connect, $sql);
    
        if ($result) {
            echo json_encode(['status' => 'success', 'message' => "Product variant updated successfully."]);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
        }
    }
     else if ($_GET['action'] == 'delete') {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data);
        $id = $data->id;
        $sql = "DELETE FROM product_variants where id='$id'";
        $result = mysqli_query($connect, $sql);
        if ($result) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
        }
    }

}

else if (isset($_GET['action']) && $_GET['action'] == 'switch_status')
{
    $sql = "UPDATE product_variants set status = {$_GET['status']} where id = '{$_GET['id']}'" ;
    $result = mysqli_query($connect, $sql);
    if (!$result)
       echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
    else
       echo json_encode(['status' => 'success']);

        


}
