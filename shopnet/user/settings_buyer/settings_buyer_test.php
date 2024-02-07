<?php
include "../../connect_DB.php";
session_start();
if(!isset($_SESSION['id'])) {
  exit;
}
if($_SESSION['role'] != 'buyer') {
    exit;
}
$id = $_SESSION['id'];
if($_SERVER['REQUEST_METHOD'] == 'GET'){
     $sql = "select username,email from buyer where id = '$id'";
     $result = mysqli_query($connect,$sql);
     $row = mysqli_fetch_assoc($result);
     $row = json_encode($row);
     echo $row;

     
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (
    $_POST["username"] == '' ||
    $_POST["email"] == '' ||
    $_POST["password"] == '' ||
    $_POST["Re-password"] == ''
) {
    echo "You must fill all the fields.";
    exit();
}


$username = trim(mysqli_real_escape_string($connect, $_POST["username"]));
$email = trim(mysqli_real_escape_string($connect, $_POST["email"]));
$password = trim(mysqli_real_escape_string($connect, $_POST["password"]));
$re_password = trim(mysqli_real_escape_string($connect, $_POST["Re-password"]));

if (
    !preg_match("/[a-zA-Z]/", $username) ||
    !preg_match('/^[a-zA-Z0-9#_-]{5,30}$/', $username)
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

$sql = "select email from buyer where email = '$email' and id != '$id'";
$result = mysqli_query($connect, $sql);
if (Mysqli_num_rows($result) > 0) {
    echo "Email already exists.";
    exit();
}


$email = filter_var($email, FILTER_SANITIZE_EMAIL);
$password = password_hash($password, PASSWORD_DEFAULT);
$sql = "UPDATE buyer SET username = '$username',email = '$email',password = '$password' where id = '$id'";
$result = mysqli_query($connect,$sql);
if(!$result)
   echo 'Something went wrong, please try again.';
else
   echo 1;
}

?>