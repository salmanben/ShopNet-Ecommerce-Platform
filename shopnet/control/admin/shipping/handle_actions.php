<?php
include "../../../connect_DB.php";
session_start();
if (!isset($_SESSION['id'])) {
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['action'])) {
    $response = array();
    if ($_GET['action'] == 'create') {

        if (
            ($_POST["name"] == '' ||
                $_POST["type"] == '' ||
                $_POST["cost"] == '')
        ) {
            $response['status'] = 'error';
            $response['message'] = 'You must fill all the fields.';
            echo json_encode($response);
            exit();
        }
        if ($_POST["type"] == 'min_cost' and $_POST["min_cost"] == '') {
            $response['status'] = 'error';
            $response['message'] = 'You must insert the value of the min cost.';
            echo json_encode($response);
            exit();
        }

        $name = mysqli_real_escape_string($connect, trim($_POST["name"]));
        $type = mysqli_real_escape_string($connect, $_POST["type"]);
        $cost = mysqli_real_escape_string($connect, trim($_POST["cost"]));
        $min_cost = mysqli_real_escape_string($connect, trim($_POST["min_cost"]));
        $status = mysqli_real_escape_string($connect, $_POST["status"]);

        if ($cost != 0 and !filter_var($cost, FILTER_VALIDATE_FLOAT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid cost.';
            echo json_encode($response);
            exit();
        }
        if ($_POST['min_cost'] != '' and !filter_var($min_cost, FILTER_VALIDATE_FLOAT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid min cost.';
            echo json_encode($response);
            exit();
        }

        $sql = "SELECT name FROM shipping_method WHERE name = '$name'";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Shipping method already exists.';
            echo json_encode($response);
            exit();
        }
        if (empty($min_cost))
            $sql = "INSERT INTO shipping_method (name, type, cost, status)
            VALUES ('$name', '$type', '$cost', '$status')";
        else
            $sql = "INSERT INTO shipping_method (name, type, cost, min_cost, status)
            VALUES ('$name', '$type', '$cost', '$min_cost', '$status')";

        $result = mysqli_query($connect, $sql);

        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Shipping method created successfully.';
            echo json_encode($response);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Something went wrong, please try again.';
            echo json_encode($response);
        }
    } else if ($_GET['action'] == 'update') {
        if (
            ($_POST["name"] == '' ||
                $_POST["type"] == '' ||
                $_POST["cost"] == '')
        ) {
            $response['status'] = 'error';
            $response['message'] = 'You must fill all the fields.';
            echo json_encode($response);
            exit();
        }
        if ($_POST["type"] == 'min_cost' and $_POST["min_cost"] == '') {
            $response['status'] = 'error';
            $response['message'] = 'You must insert the value of the min cost.';
            echo json_encode($response);
            exit();
        }

        $name = mysqli_real_escape_string($connect, trim($_POST["name"]));
        $type = mysqli_real_escape_string($connect, $_POST["type"]);
        $cost = mysqli_real_escape_string($connect, trim($_POST["cost"]));
        $min_cost = mysqli_real_escape_string($connect, trim($_POST["min_cost"]));
        if ($min_cost == '')
            $min_cost = null;
        $status = mysqli_real_escape_string($connect, $_POST["status"]);

        if ($cost != 0 and !filter_var($cost, FILTER_VALIDATE_FLOAT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid cost.';
            echo json_encode($response);
            exit();
        }
        if ($_POST['min_cost'] != '' and !filter_var($min_cost, FILTER_VALIDATE_FLOAT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid min cost.';
            echo json_encode($response);
            exit();
        }
        $id = $_POST['id'];
        $sql = "select name from shipping_method where name = '$name' and id != $id";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Shipping method already exists.';
            echo json_encode($response);
            exit();
        }
        if (empty($min_cost))
        $sql = "UPDATE shipping_method 
            SET name = '$name', 
            type = '$type', 
            cost = '$cost', 
            min_cost = null, 
            status = '$status' 
            WHERE id = '$id'";
        else
        $sql = "UPDATE shipping_method 
            SET name = '$name', 
            type = '$type', 
            cost = '$cost', 
            min_cost = $min_cost, 
            status = '$status' 
            WHERE id = '$id'";
        $result = mysqli_query($connect, $sql);

        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Shipping method updated successfully.';
            echo json_encode($response);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Something went wrong, please try again.';
            echo json_encode($response);
        }
    } else  if ($_GET['action'] == 'delete') {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data);
        $shipping_method_id = $data->id;
        $sql = "DELETE FROM shipping_method where id='$shipping_method_id'";
        $result = mysqli_query($connect, $sql);
        if ($result) {
            $response['status'] = 'success';
            echo json_encode($response);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Something went wrong, please try again.';
            echo json_encode($response);
        }
    }
} else if (isset($_GET['id']) && isset($_GET['status'])) {

    $sql = "UPDATE shipping_method set status = {$_GET['status']} where id = '{$_GET['id']}'";
    $result = mysqli_query($connect, $sql);
    if (!$result)
        echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
    else
        echo json_encode(['status' => 'success']);
}
