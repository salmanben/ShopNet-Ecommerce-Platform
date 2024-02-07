<?php
include "../../../connect_DB.php";
session_start();
if (!isset($_SESSION['id'])) {
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action']))
{
    $response = array();
    if ($_GET['action'] == 'create') {
        if (
            $_POST["code"] == '' ||
            $_POST["max_use"] == '' ||
            $_POST["quantity"] == '' ||
            $_POST["type"] == '' ||
            $_POST["cost"] == ''
        )  {
            $response['status'] = 'error';
            $response['message'] = 'You must fill all the fields.';
            echo json_encode($response);
            exit();
        }
        $code = mysqli_real_escape_string($connect, trim($_POST["code"]));
        $quantity = mysqli_real_escape_string($connect, trim($_POST["quantity"]));
        $max_use = mysqli_real_escape_string($connect, trim($_POST["max_use"]));
        $type = mysqli_real_escape_string($connect, $_POST["type"]);
        $cost = mysqli_real_escape_string($connect, trim($_POST["cost"]));
        $status = mysqli_real_escape_string($connect, $_POST["status"]);
    
        if (!filter_var($quantity, FILTER_VALIDATE_INT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid quantity.';
            echo json_encode($response);
            exit();
        }
        if (!filter_var($max_use, FILTER_VALIDATE_INT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid max use.';
            echo json_encode($response);
            exit();
        }
        if (!filter_var($cost, FILTER_VALIDATE_FLOAT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid cost.';
            echo json_encode($response);
            exit();
        }
    
        $sql = "SELECT code FROM coupon WHERE code = '$code'";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Coupon code already exists.';
            echo json_encode($response);
            exit();
        }
    
        $sql = "INSERT INTO coupon (code, quantity,max_use,  type, cost, status)
               VALUES ('$code', '$quantity','$max_use', '$type', '$cost', '$status')";
        $result = mysqli_query($connect, $sql);
    
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Coupon created successfully.';
            echo json_encode($response);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Something went wrong, please try again.';
            echo json_encode($response);
        }
    } else if ($_GET['action'] == 'update') {
        if (
            $_POST["code"] == '' ||
            $_POST["max_use"] == '' ||
            $_POST["quantity"] == '' ||
            $_POST["type"] == '' ||
            $_POST["cost"] == ''
        )  {
            $response['status'] = 'error';
            $response['message'] = 'You must fill all the fields.';
            echo json_encode($response);
            exit();
        }
        $code = mysqli_real_escape_string($connect, trim($_POST["code"]));
        $quantity = mysqli_real_escape_string($connect, trim($_POST["quantity"]));
        $max_use = mysqli_real_escape_string($connect, trim($_POST["max_use"]));
        $type = mysqli_real_escape_string($connect, $_POST["type"]);
        $cost = mysqli_real_escape_string($connect, trim($_POST["cost"]));
        $status = mysqli_real_escape_string($connect, $_POST["status"]);
        $id = mysqli_real_escape_string($connect, $_POST["id"]);
    
        if (!filter_var($quantity, FILTER_VALIDATE_INT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid quantity.';
            echo json_encode($response);
            exit();
        }
        if (!filter_var($max_use, FILTER_VALIDATE_INT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid max use.';
            echo json_encode($response);
            exit();
        }
        if (!filter_var($cost, FILTER_VALIDATE_FLOAT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid cost.';
            echo json_encode($response);
            exit();
        }
        $sql = "select code from coupon where code = '$code' and id != $id";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Coupon code already exists.';
            echo json_encode($response);
            exit();
        }
    
        $sql = "UPDATE coupon 
                SET code = '$code', 
                quantity = '$quantity', 
                max_use = '$max_use', 
                type = '$type', 
                cost = '$cost', 
                status = '$status' 
                WHERE id = '$id'";
    
        $result = mysqli_query($connect, $sql);
    
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Coupon updated successfully.';
            echo json_encode($response);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Something went wrong, please try again.';
            echo json_encode($response);
        }
    }
    else  if ($_GET['action'] == 'delete')
    {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data);
        $coupon_id = $data->id;
        $sql = "DELETE FROM coupon where id='$coupon_id'";
        $result = mysqli_query($connect, $sql);
        if ($result)
        {
            $response['status'] = 'success';
            echo json_encode($response);
        }
        else{
            $response['status'] = 'error';
            $response['message'] = 'Something went wrong, please try again.';
            echo json_encode($response);
        }
        

    }
}
else if (isset($_GET['id']) && isset($_GET['status'])) {

    $sql = "UPDATE coupon set status = {$_GET['status']} where id = '{$_GET['id']}'";
    $result = mysqli_query($connect, $sql);
    if (!$result)
       echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
    else
       echo json_encode(['status' => 'success']);
 }

?>