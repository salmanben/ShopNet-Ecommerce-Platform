<?php
include "../../connect_DB.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (
        $_POST["name"] == '' ||
        $_POST["email"] == '' ||
        $_POST["message"] == ''
    ) {
        echo "You must fill all the fields.";
        exit();
    }

    $name = trim(mysqli_real_escape_string($connect, $_POST["name"]));
    $email = trim(mysqli_real_escape_string($connect, $_POST["email"]));
    $message = trim(mysqli_real_escape_string($connect, $_POST["message"]));

    if (strlen($name) > 50) {
        echo "Invalid name.";
        exit();
    }
    if (strlen($message) > 1000) {
        echo "The message is too long.";
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email.";
        exit();
    }

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $sql = "INSERT INTO contact (name, email, message) VALUES ('$name', '$email', '$message')";
    $result = mysqli_query($connect, $sql);
    if (!$result) {
        echo 'Something went wrong, please try again.';
    } else {
        echo '1';
    }
}
