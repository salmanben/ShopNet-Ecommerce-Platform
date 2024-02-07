<?php
include "../../../connect_DB.php";
include('../layout/aside.php');
include('../layout/header.php');
if (!isset($_SESSION['id'])) {
    header("location:../login/login.php");
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    http_response_code(404);
    exit;
}

$sql = "SELECT * from coupon";
$result = mysqli_query($connect, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/layout.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Coupon</title>
    <link rel="icon" href="../../../icon.png">
    <script defer src="../scripts/table_script.js"></script>
    <script defer src="../scripts/handle_actions.js"></script>
</head>

<body>
    <div class="wrapper">
        <?php echo $aside ?>
        <div class="container">
            <?php echo $header ?>
            <div class="content">
                <h2>Coupon</h2>
                <div class="create-item">
                    <a href="create.php">Create coupon</a>
                    <div class="search">
                        <input type="search" placeholder="Search by id..." id="">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Code</th>
                                <th>Quantity</th>
                                <th>Max Use</th>
                                <th>Type</th>
                                <th>Cost</th>
                                <th>Status</th>
                                <th>Update</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            <?php

                            while ($row = mysqli_fetch_assoc($result)) {
                                $checked = $row["status"] ==  1 ? "checked" : "";
                                echo '
                                <tr class="item" id ="' . $row["id"] . '">
                                <td>' . $row["id"] . '</td>
                                 <td>' . $row["code"] . '</td>
                                 <td>' . $row["quantity"] . '</td>
                                 <td>' . $row["max_use"] . '</td>
                                 <td>' . $row["type"] . '</td>
                                 <td>' . $row["cost"] . '</td>
                                 <td><input onclick="switchStatus(event, \'' . $row["id"] . '\')" ' . $checked . ' type="checkbox" name="" id="" class="status"></td>                                 
                                 <td><a class="update" href="edit.php?coupon_id=' . $row["id"] . '"><i class="fa-solid fa-pen-to-square"></i></a></td>
                                 <td><a href="" onclick="deleteItem(event)" class="delete"><i class="fa-solid fa-trash"></i></a></td>
                                </tr>';
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>