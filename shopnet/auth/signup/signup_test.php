<?php
include "../../connect_DB.php";
if (isset($_GET["form"])) {
    if ($_GET["form"] == "seller") {

        if (
            $_POST["username"] == '' ||
            $_POST["email"] == '' ||
            $_POST["password"] == '' ||
            $_POST["Re-password"] == '' ||
            $_POST["P_email"]  == '' 
        ) {
            echo "You must fill all the fields.";
            exit();
        }

        if ($_FILES["file"]['error'] == 4) {
            echo "You must upload an image for your profile.";
            exit();
        }
        if ($_FILES["banner"]['error'] == 4) {
            echo "You must upload a banner.";
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
            echo "The two passwords must be the same.";
            exit();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email.";
            exit();
        }
        $sql = "select email from seller where email = '$email'";
        $result = mysqli_query($connect, $sql);
        if (Mysqli_num_rows($result) > 0) {
            echo "Email already exists.";
            exit();
        }

        if (!filter_var($P_email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid PayPal email.";
            exit();
        }

        $arr_ext = ["png", "jfif", "jpeg", "webp", "jpg"];
        $arr = explode(".", $_FILES["file"]["name"]);
        $ext = end($arr);
        if (!in_array($ext, $arr_ext)) {
            echo "Invalid image, the image extension must be one of these types: png, jpeg, jfif, webp, jpg.";
            exit();
        }
        if ($_FILES["file"]["size"] > 2000000) {
            echo 'Invalid image, the size of image dons\'t have to pass 2MB.';
            exit();
        }

        $arr = explode(".", $_FILES["banner"]["name"]);
        $ext = end($arr);
        if (!in_array($ext, $arr_ext)) {
            echo "Invalid banner, the banner extension must be one of these types: png, jpeg, jfif, webp, jpg.";
            exit();
        }
        if ($_FILES["banner"]["size"] > 3000000) {
            echo 'Invalid banner, the size of banner dons\'t have to pass 3MB.';
            exit();
        }

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        do {
            $unique_id = substr(md5(microtime()), rand(0, 19), 13);
            $sql = "select id from seller where id = '$unique_id'";
            $result = mysqli_query($connect, $sql);
        } while (Mysqli_num_rows($result) > 0);
        session_start();
        $_SESSION["id"] = $unique_id;
        $_SESSION["role"] = 'seller';
        $password = password_hash($password, PASSWORD_DEFAULT);
        $image = uniqid("Image") . '.' . $ext;
        move_uploaded_file(
            $_FILES["file"]["tmp_name"],
            "../../upload/" . $image
        );
        $banner = uniqid("Banner") . '.' . $ext;
        move_uploaded_file(
            $_FILES["banner"]["tmp_name"],
            "../../upload/" . $banner
        );
        $sql = "INSERT INTO seller (id, username, email, password, image, paypal_email, banner)
         VALUES ('$unique_id', '$username', '$email', '$password', '$image', '$P_email', '$banner')";
        $result = mysqli_query($connect, $sql);

        if ($result) {
            echo 1;
        } else {
            echo "Something went wrong, please try again.";
        }
    } else {
        if (
            $_POST["username"] == '' ||
            $_POST["email"] == '' ||
            $_POST["password"] == '' ||
            $_POST["Re-password"] == ''
        ) {
            echo "You must fill all fields.";
            exit();
        }

        $username = mysqli_real_escape_string($connect, trim($_POST["username"]));
        $email = mysqli_real_escape_string($connect, trim($_POST["email"]));
        $password = mysqli_real_escape_string($connect, trim($_POST["password"]));
        $re_password = mysqli_real_escape_string($connect, trim($_POST["Re-password"]));

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
            echo "The two passwords must be the same.";
            exit();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email.";
            exit();
        }

        $sql = "select email from buyer where email = '$email'";
        $result = mysqli_query($connect, $sql);
        if (Mysqli_num_rows($result) > 0) {
            echo "Email already exists.";
            exit();
        }

        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        do {
            $unique_id = substr(md5(microtime()), rand(0, 19), 13);
            $sql = "select id from buyer where id = '$unique_id'";
            $result = mysqli_query($connect, $sql);
        } while (Mysqli_num_rows($result) > 0);
        session_start();
        $_SESSION["id"] = $unique_id;
        $_SESSION["role"] = 'buyer';
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO buyer (id,username,email,password) values('$unique_id','$username','$email','$password')";
        $result = mysqli_query($connect, $sql);
        if ($result) {
            echo 1;
        } else {
            echo "Something went wrong, please try again.";
        }
    }
}
