<?php
include "../../../connect_DB.php";
if ($_GET["check"] == 'email') {

    if (
        $_POST["email"] == ''
    ) {
        echo "You must fill  the field.";
        exit();
    }

    $email = mysqli_real_escape_string($connect, trim($_POST["email"]));

    $sql = "select email from admin where email = '$email'";
    $result = mysqli_query($connect, $sql);
    if (Mysqli_num_rows($result) == 0) {
        echo "Email not exist.";
        exit();
    }
    echo 1;
}
if ($_GET["check"] == 'password') {

    $password = mysqli_real_escape_string($connect, trim($_POST["password"]));
    $re_password = mysqli_real_escape_string($connect,  trim($_POST["Re-password"]));
    $email = $_GET["email"];
    if (
        $password == ''||
        $re_password == ''
    ) {
        echo "You must fill all the fields.";
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
    $password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "select id from  admin where email = '$email'";
    $result = mysqli_query($connect, $sql);
    $row = mysqli_fetch_assoc($result);
    $id = $row['id'];
    $sql = "UPDATE admin  SET password  = '$password' where id = '$id'";
    $result = mysqli_query($connect, $sql);
    echo 1;
}
