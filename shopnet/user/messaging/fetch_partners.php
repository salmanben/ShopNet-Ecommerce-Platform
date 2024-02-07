<?php
include "../../connect_DB.php";
session_start();
if (!isset($_SESSION['id'])) {
  exit;
}
if ($_SESSION['role'] !== 'buyer') {
  exit;
}
$id = $_SESSION['id'];
$sql = "SELECT m.*, username, image
FROM messaging m
JOIN seller s ON m.seller_id = s.id
JOIN (
    SELECT seller_id, MAX(date) AS max_date
    FROM messaging
    WHERE buyer_id = '$id'
    GROUP BY seller_id
) AS recent_messages ON m.seller_id = recent_messages.seller_id AND m.date = recent_messages.max_date
WHERE m.buyer_id = '$id' ORDER BY m.date desc";
$result = mysqli_query($connect, $sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
echo json_encode($rows);