<?php
include "../../connect_DB.php";
if (!isset($_GET['searched'])) {
   exit;
}
$searched = mysqli_real_escape_string($connect, $_GET['searched']);
$category_id = mysqli_real_escape_string($connect, $_GET['category_id']);
if ($category_id == 0)
{
   if (isset($_GET['offset']))
      $sql = "SELECT p.*, c.name FROM product p JOIN category c ON p.category_id = c.id WHERE count > 0 AND p.status = 1 AND (title LIKE '%$searched%' or short_description LIKE '%$searched%' or long_description LIKE '%$searched%') and p.id < {$_GET['offset']} ORDER BY p.id DESC LIMIT 40";
   else
      $sql = "SELECT p.*, c.name FROM product p JOIN category c ON p.category_id = c.id WHERE count > 0 AND p.status = 1 AND (title LIKE '%$searched%' or short_description LIKE '%$searched%' or long_description LIKE '%$searched%') ORDER BY p.id DESC LIMIT 40";
}
else
{
   if (isset($_GET['offset']))
      $sql = "SELECT p.*, c.name FROM product p JOIN category c ON p.category_id = c.id WHERE p.category_id = $category_id AND count > 0 AND p.status = 1  AND (title LIKE '%$searched%' or short_description LIKE '%$searched%' or long_description LIKE '%$searched%') and p.id < {$_GET['offset']} ORDER BY p.id DESC LIMIT 40";
   else
      $sql = "SELECT p.*, c.name FROM product p JOIN category c ON p.category_id = c.id WHERE p.category_id = $category_id AND count > 0 AND p.status = 1  AND (title LIKE '%$searched%' or short_description LIKE '%$searched%' or long_description LIKE '%$searched%') ORDER BY p.id DESC LIMIT 40";
}

$result = mysqli_query($connect, $sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
$rows = json_encode($rows);
echo $rows;
