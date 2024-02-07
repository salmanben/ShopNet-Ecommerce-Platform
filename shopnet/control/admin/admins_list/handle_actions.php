<?php
include "../../../connect_DB.php";
if (isset($_GET['id']) && isset($_GET['status'])) {
   if ($_GET['id'] == 1)
   {
      echo json_encode(['status' => 'error', 'message' => "Unauthorized Action !"]);
      exit;
   }
   $sql = "UPDATE admin set status = {$_GET['status']} where id = '{$_GET['id']}'";
   $result = mysqli_query($connect, $sql);
   if (!$result)
      echo json_encode(['status' => 'error', 'message' => "Something went wrong, please try again."]);
   else
      echo json_encode(['status' => 'success']);
}
