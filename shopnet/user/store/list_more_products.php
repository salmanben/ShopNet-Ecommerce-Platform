<?php
include "../../connect_DB.php";
if(!isset($_GET['category_id']) || !isset($_GET['offset']) || !isset($_GET['seller_id']) ){
    exit;
}
$seller_id = $_GET['seller_id'];
$category_id = mysqli_real_escape_string($connect, $_GET['category_id']);
$offset= $_GET['offset'];
if($category_id == "0")
$sql = "SELECT p.* , c.name FROM product p join category c on p.category_id = c.id  where count > 0 and p.status = 1 and seller_id = '$seller_id' and p.id < $offset order by p.id desc limit 40";
else
$sql = "SELECT p.* , c.name FROM product p join category c on p.category_id = c.id  where category_id = $category_id and  count > 0 and p.status = 1 and seller_id = '$seller_id' and p.id < $offset order by p.id desc limit 40";
$result = mysqli_query($connect,$sql);
$row = mysqli_fetch_All($result,MYSQLI_ASSOC);
$row = json_encode($row);
echo $row;
?>