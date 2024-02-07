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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = array();
    if ($_GET['form'] == 'client_id') {
        if ($_POST['client_id'] == '') {
            $response['status'] = 'error';
            $response['message'] = 'You must fill  the field.';
            echo json_encode($response);
            exit;
        }
        $client_id = mysqli_real_escape_string($connect, trim($_POST["client_id"]));
        $id = $_POST['id'];
        if (!$id) {
            $sql = "INSERT INTO paypal (client_id) value('$client_id')";
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
            $sql = "UPDATE paypal set client_id = '$client_id' where id=$id";
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
        $response['message'] = 'Data saved successfully.';
        echo json_encode($response);
        exit;
    } else if ($_GET['form'] == 'email_settings') {
        if (
            $_POST['username'] == ''
            || $_POST['password'] == ''
            || $_POST['from'] == ''
        ) {
            $response['status'] = 'error';
            $response['message'] = 'You must fill all  the fields.';
            echo json_encode($response);
            exit;
        }
        $username = mysqli_real_escape_string($connect, trim($_POST["username"]));
        $password = mysqli_real_escape_string($connect, trim($_POST["password"]));
        $from = mysqli_real_escape_string($connect, trim($_POST["from"]));
        $id = $_POST['id'];

        if (!$id) {
            $sql = "INSERT INTO email_settings (username, password, `from_name`) 
                    VALUES ('$username', '$password', '$from')";
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
            $sql = "UPDATE email_settings 
                    SET username = '$username', password = '$password', `from_name` = '$from' 
                    WHERE id = {$_POST['id']}";
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
        $response['message'] = 'Data saved successfully.';
        echo json_encode($response);
        exit;
    } else {
        if (
            $_POST["first_name"] == '' ||
            $_POST["last_name"] == '' ||
            $_POST["email"] == '' ||
            $_POST["password"] == '' ||
            $_POST["Re-password"] == ''
        ) {
            $response['status'] = 'error';
            $response['message'] = 'You must fill all the fields.';
            echo json_encode($response);
            exit();
        }

        if ($_FILES["file"]["error"] == 4 && $_GET["form"] == "add") {
            $response['status'] = 'error';
            $response['message'] = 'You must upload a profile image.';
            echo json_encode($response);
            exit();
        }

        $first_name = mysqli_real_escape_string($connect, trim($_POST["first_name"]));
        $last_name = mysqli_real_escape_string($connect, trim($_POST["last_name"]));
        $email = mysqli_real_escape_string($connect, trim($_POST["email"]));
        $password = mysqli_real_escape_string($connect, trim($_POST["password"]));
        $re_password = mysqli_real_escape_string($connect, trim($_POST["Re-password"]));

        if (!preg_match("/^[a-zA-Z\s]{3,30}$/", $first_name)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid first name.';
            echo json_encode($response);
            exit();
        }
        if (!preg_match("/^[a-zA-Z\s]{3,30}$/", $last_name)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid last name.';
            echo json_encode($response);
            exit();
        }
        if (!preg_match('/^[a-zA-Z0-9#_-]{5,30}$/', $password)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid password.';
            echo json_encode($response);
            exit();
        }
        if ($re_password != $password) {
            $response['status'] = 'error';
            $response['message'] = 'The two passwords must be the same.';
            echo json_encode($response);
            exit();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['status'] = 'error';
            $response['message'] = 'Invalid email.';
            echo json_encode($response);
            exit();
        }

        $sql = "SELECT id, email FROM admin WHERE email = '$email'";
        $result = mysqli_query($connect, $sql);

        if ($_GET["form"] == "add") {
            if (mysqli_num_rows($result) > 0) {
                $response['status'] = 'error';
                $response['message'] = 'Email already exists.';
                echo json_encode($response);
                exit();
            }
        } else {
            if (mysqli_num_rows($result) > 0 && mysqli_fetch_assoc($result)['id'] != $id) {
                $response['status'] = 'error';
                $response['message'] = 'Email already exists.';
                echo json_encode($response);
                exit();
            }
        }

        if ($_GET["form"] == "add" || $_FILES["file"]["error"] != 4) {
            $arr_ext = ["png", "jfif", "jpeg", "webp", "jpg"];
            $arr = explode(".", $_FILES["file"]["name"]);
            $ext = end($arr);
            if (!in_array($ext, $arr_ext)) {
                $response['status'] = 'error';
                $response['message'] = 'Invalid image type. The image must be one of these types: png, jpeg, jfif, webp, jpg.';
                echo json_encode($response);
                exit();
            }
            if ($_FILES["file"]["size"] > 2000000) {
                $response['status'] = 'error';
                $response['message'] = 'Invalid image size. The size of the image must not exceed 2MB.';
                echo json_encode($response);
                exit();
            }
            $image = uniqid("Image") . "." . $ext;
            move_uploaded_file(
                $_FILES["file"]["tmp_name"],
                "../../../upload/" . $image
            );
        }

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $password = password_hash($password, PASSWORD_DEFAULT);
        if ($_GET["form"] == "add") {
            do {
                $unique_id = substr(md5(microtime()), rand(0, 19), 13);
                $sql = "SELECT id FROM seller WHERE id = '$unique_id'";
                $result = mysqli_query($connect, $sql);
            } while (mysqli_num_rows($result) > 0);
            $sql = "INSERT INTO admin (id, first_name, last_name, email, password, image) 
                    VALUES ('$unique_id', '$first_name', '$last_name', '$email', '$password', '$image')";
            $result = mysqli_query($connect, $sql);
            if ($result) {
                $response['status'] = 'success';
                $response['message'] = 'Admin added successfully.';
                echo json_encode($response);
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong, please try again.';
                echo json_encode($response);
            }
        } else {
            $sql = "SELECT image FROM admin WHERE id  = '$id'";
            $result = mysqli_query($connect, $sql);
            $old_image = mysqli_fetch_assoc($result)["image"];
            if ($_FILES["file"]["error"] != 4) {
                if (file_exists("../../../upload/" . $old_image)) {
                    unlink("../../../upload/" . $old_image);
                }
            } else {
                $image = $old_image;
            }
            $sql = "UPDATE admin SET first_name = '$first_name', last_name = '$last_name',
                email = '$email', password = '$password', image = '$image' 
                WHERE id = '$id'";

            $result = mysqli_query($connect, $sql);
            if ($result) {
                $response['status'] = 'success';
                $response['message'] = 'Data updated successfully.';
                echo json_encode($response);
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Error updating data.';
                echo json_encode($response);
            }
        }
    }
}
