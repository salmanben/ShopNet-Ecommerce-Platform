<?php
include "../../../connect_DB.php";
if (
    $_POST["email"] == '' ||
    $_POST["password"] == ''
) {
    echo "You must fill all the fields.";
    exit();
}

$email = mysqli_real_escape_string($connect, trim($_POST["email"]));
$password = mysqli_real_escape_string($connect, trim($_POST["password"]));

$sql = "select email from admin where email = '$email'";
$result = mysqli_query($connect, $sql);
if (Mysqli_num_rows($result) == 0) {
    echo "Email not exists.";
    exit();
}

$sql = "select id,password, status from admin where email = '$email'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);
if (!password_verify($password, $row['password'])) {
    echo 'Incorrect password.';
    exit();
}
if ($row['status'] == 0) {
    echo 'Your account is inactivated!';
    exit();
}
session_start();
$_SESSION["id"] = $row['id'];
$_SESSION["role"] = 'admin';
echo 1;
