<?php
include "../../../connect_DB.php";
session_start();
if (!isset($_SESSION['id'])) {
  exit;
}
if ($_SESSION['role'] !== 'seller') {
  exit;
}
$id = $_SESSION['id'];
$sql = "SELECT m.*, username
FROM messaging m
JOIN buyer b ON m.buyer_id = b.id
JOIN (
    SELECT buyer_id, MAX(date) AS max_date
    FROM messaging
    WHERE seller_id = '$id'
    GROUP BY buyer_id
) AS recent_messages ON m.buyer_id = recent_messages.buyer_id AND m.date = recent_messages.max_date
WHERE m.seller_id = '$id' ORDER BY m.date desc;
";
$result = mysqli_query($connect, $sql);
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
echo json_encode($rows);