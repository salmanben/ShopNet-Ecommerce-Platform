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
            $_POST["name"] == '' ||
            $_POST["icon"] == ''
        )  {
            $response['status'] = 'error';
            $response['message'] = 'You must fill all the fields.';
            echo json_encode($response);
            exit();
        }
        $name = mysqli_real_escape_string($connect, trim($_POST["name"]));
        $icon = mysqli_real_escape_string($connect, trim($_POST["icon"]));
        $status = mysqli_real_escape_string($connect, $_POST["status"]);
    
    
        $sql = "SELECT name  FROM category WHERE name = '$name'";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Category name already exists.';
            echo json_encode($response);
            exit();
        }
        $sql = "SELECT icon  FROM category WHERE icon = '$icon'";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Category icon  already exists.';
            echo json_encode($response);
            exit();
        }
    
        $sql = "INSERT INTO category (name, icon, status)
               VALUES ('$name', '$icon', '$status')";
        $result = mysqli_query($connect, $sql);
    
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Category created successfully.';
            echo json_encode($response);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Something went wrong, please try again.';
            echo json_encode($response);
        }
    } else if ($_GET['action'] == 'update') {
        if (
            $_POST["name"] == '' ||
            $_POST["icon"] == ''
        )  {
            $response['status'] = 'error';
            $response['message'] = 'You must fill all the fields.';
            echo json_encode($response);
            exit();
        }
        $name = mysqli_real_escape_string($connect, trim($_POST["name"]));
        $icon = mysqli_real_escape_string($connect, trim($_POST["icon"]));
        $status = mysqli_real_escape_string($connect, $_POST["status"]);
        $id = mysqli_real_escape_string($connect, $_POST["id"]);

        $sql = "SELECT name  FROM category WHERE name = '$name' and id != $id";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Category name  already exists.';
            echo json_encode($response);
            exit();
        }
        $sql = "SELECT icon  FROM category WHERE icon = '$icon' and id != $id";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Category icon  already exists.';
            echo json_encode($response);
            exit();
        }
        $sql = "UPDATE category 
                SET name = '$name', 
                icon = '$icon', 
                status = '$status' 
                WHERE id = '$id'";
    
        $result = mysqli_query($connect, $sql);
    
        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Category updated successfully.';
            echo json_encode($response);
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Something went wrong, please try again.';
            echo json_encode($response);
        }
    } elseif ($_GET['action'] == 'delete') {
        $json_data = file_get_contents("php://input");
        $data = json_decode($json_data);
        $category_id = $data->id;
        $sql =  "SELECT * from product where category_id='$category_id'";
        $result = mysqli_query($connect, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo json_encode(['status' => 'error', 'message' => "You can't delete a category that has products."]);
            exit;
        }
        $sql = "DELETE FROM category WHERE id='$category_id'";
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
}
else if (isset($_GET['id']) && isset($_GET['status'])) {

    $sql = "UPDATE category set status = {$_GET['status']} where id = '{$_GET['id']}'";
    $result = mysqli_query($connect, $sql);
    if (!$result)
       echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
    else
       echo json_encode(['status' => 'success']);
 }

?>