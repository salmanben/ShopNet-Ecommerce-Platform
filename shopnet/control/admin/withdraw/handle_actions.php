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
    if ($_GET['action'] == 'update') {
        if (
            ($_POST["charges"] == '' ||
                $_POST["min_amount"] == '' ||
                $_POST["max_amount"] == '')
        ) {
            $response['status'] = 'error';
            $response['message'] = 'You must fill all the fields.';
            echo json_encode($response);
            exit();
        }

        $charges = mysqli_real_escape_string($connect, trim($_POST["charges"]));
        $name = mysqli_real_escape_string($connect, trim($_POST["method_name"]));
        $min_amount = mysqli_real_escape_string($connect, $_POST["min_amount"]);
        $max_amount = mysqli_real_escape_string($connect, trim($_POST["max_amount"]));
        if ($charges != 0 and !filter_var($charges, FILTER_VALIDATE_FLOAT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid charges value.';
            echo json_encode($response);
            exit();
        }
        if (!filter_var($min_amount, FILTER_VALIDATE_FLOAT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid min amount.';
            echo json_encode($response);
            exit();
        }
        if (!filter_var($max_amount, FILTER_VALIDATE_FLOAT)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid max amount.';
            echo json_encode($response);
            exit();
        }
        $id = $_POST['id'];
        if (!$id) {
            $sql = "INSERT INTO withdraw_method (method_name, charges, min_amount, max_amount) 
            VALUES ('$name', '$charges', '$min_amount', '$max_amount')";
            $result = mysqli_query($connect, $sql);
            if (!$result) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong, please try again.';
                echo json_encode($response);
                exit;
            } else {
                $id = mysqli_insert_id($connect);
            }
        } else {
            $sql = "UPDATE withdraw_method 
            SET method_name = '$name', 
            charges = '$charges', 
            min_amount = $min_amount, 
            max_amount = $max_amount
            WHERE id = '$id'";
            $result = mysqli_query($connect, $sql);
            if (!$result) {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong, please try again.';
                echo json_encode($response);
                exit;
            }
        }
        $response['status'] = 'success';
        $response['id'] = $id;
        $response['message'] = 'Withdraw method saved successfully.';
        echo json_encode($response);
        exit;
    }
} else if (isset($_GET['id']) && isset($_GET['status'])) {
    $sql = "UPDATE withdraw_request set `status` = '{$_GET['status']}' where id = '{$_GET['id']}'";
    $result = mysqli_query($connect, $sql);
    if (!$result)
        echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
    else
        echo json_encode(['status' => 'success']);
}
