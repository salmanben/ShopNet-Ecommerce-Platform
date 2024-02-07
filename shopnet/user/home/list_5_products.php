<?php
include "../../connect_DB.php";
if (!isset($_GET['category_id'])) {
    exit;
}
$category_id = mysqli_real_escape_string($connect, $_GET['category_id']);
if ($category_id == "0")
    $sql = "SELECT p.*, count(op.product_id) as op_count , c.name FROM product p join category c on p.category_id = c.id 
    join  order_products op on p.id = op.product_id  where p.count > 0 and p.status = 1
    group by op.product_id
    order by op_count desc limit 5";
else
    $sql = "SELECT p.*, count(op.product_id) as op_count , c.name FROM product p join category c on p.category_id = c.id 
    join  order_products op on p.id = op.product_id where p.category_id=$category_id and p.count > 0 and p.status = 1
    group by op.product_id
    order by op_count desc limit 5";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_All($result, MYSQLI_ASSOC);
$row = json_encode($row);
echo $row;
