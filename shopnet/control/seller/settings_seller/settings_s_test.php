<?php
include "../../../connect_DB.php";
session_start();
if (!isset($_SESSION["id"])) {
    exit;
}
if ($_SESSION['role'] !== 'seller') {
    exit;
}
$id = $_SESSION["id"];
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $sql = "SELECT username,image,email,email_PayPal
     FROM seller where id = '$id'";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);
    $row = json_encode($row);

    echo $row;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        $_POST["username"] == '' or
        $_POST["email"] == '' or
        $_POST["password"] == '' or
        $_POST["Re-password"] == '' or
        $_POST["P_email"] == ''
    ) {
        echo "You must fill all the fields.";
        exit();
    }


    $username = mysqli_real_escape_string($connect, trim($_POST["username"]));
    $email = mysqli_real_escape_string($connect, trim($_POST["email"]));
    $password = mysqli_real_escape_string($connect, trim($_POST["password"]));
    $re_password = mysqli_real_escape_string($connect, trim($_POST["Re-password"]));
    $P_email = mysqli_real_escape_string($connect, trim($_POST["P_email"]));

    if (
        !preg_match("/[a-zA-Z]/", $username) ||
        !preg_match('/^[a-zA-Z0-9#_-]{3,30}$/', $username)
    ) {
        echo "Invalid username.";
        exit();
    }
    if (!preg_match('/^[a-zA-Z0-9#_-]{5,30}$/', $password)) {
        echo "Invalid password.";
        exit();
    }
    if ($re_password != $password) {
        echo "The two password must be the same.";
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email.";
        exit();
    }
    $sql = "select id, email from seller where email = '$email'";
    $result = mysqli_query($connect, $sql);
    if (Mysqli_num_rows($result) > 0 && mysqli_fetch_assoc($result)['id'] != $id) {
        echo "Email already exists.";
        exit();
    }
    if (!filter_var($P_email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid PayPal email.s";
        exit();
    }
    $sql = "select image, banner from seller where id = '$id'";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);
    $old_image = $row['image'];
    $old_banner = $row['banner'];
    $arr_ext = ["png", "jfif", "jpeg", "webp", "jpg"];
    if ($_FILES["file"]['error'] != 4) {
        $arr = explode(".", $_FILES["file"]["name"]);
        $ext = end($arr);
        if (!in_array($ext, $arr_ext)) {
            echo "Invalid image, the image must be one of these types: png, jpeg, jfif, webp, jpg.";
            exit();
        }
        if ($_FILES["file"]["size"] > 2000000) {
            echo 'Invalid image, the size of image doesn\'t have to pass 2MB ';
            exit();
        }
        $image = uniqid("Image") . '.' . $ext;
    } else
        $image = $old_image;
    if ($_FILES["banner"]['error'] != 4) {
        $arr = explode(".", $_FILES["banner"]["name"]);
        $ext = end($arr);
        if (!in_array($ext, $arr_ext)) {
            echo "Invalid banner, the banner must be one of these types: png, jpeg, jfif, webp, jpg.";
            exit();
        }
        if ($_FILES["file"]["size"] > 2000000) {
            echo 'Invalid banner, the size of banner doesn\'t have to pass 2MB ';
            exit();
        }
        $banner = uniqid("Banner") . '.' . $ext;
    } else
        $banner = $old_banner;
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE seller SET 
            username = '$username',
            email = '$email',
            password = '$password',
            image = '$image',
            banner = '$banner',
            paypal_email = '$P_email'
        WHERE id = '$id'";

    $result = mysqli_query($connect, $sql);

    if ($result) {
        if ($_FILES["file"]['error'] != 4) {
            if (file_exists("../../../upload/" . $old_image))
                unlink("../../../upload/" . $old_image);
            move_uploaded_file(
                $_FILES["file"]["tmp_name"],
                "../../../upload/" . $image
            );
        }
        if ($_FILES["banner"]['error'] != 4) {
            if (file_exists("../../../upload/" . $old_banner))
                unlink("../../../upload/" . $old_banner);
            move_uploaded_file(
                $_FILES["banner"]["tmp_name"],
                "../../../upload/" . $banner
            );
        }
        echo 1;
    } else {
        echo "Something went wrong, please try again.";
    }
}
