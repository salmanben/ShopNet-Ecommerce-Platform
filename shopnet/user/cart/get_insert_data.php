<?php
include "../connect_DB.php";
session_start();
if (!isset($_SESSION['id'])) {
  exit;
}
$id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['get'])) {
    
  $data = file_get_contents('php://input');
  $data = json_decode($data);
  $arr = array();
  for ($i = 0; $i < count($data); $i++) {
    $id = $data[$i]->id;
    $sql = "SELECT email FROM seller s JOIN product p
    ON s.id = p.seller_id  WHERE p.id = '$id'";
    $result = mysqli_query($connect, $sql);
    $email = mysqli_fetch_assoc($result)['email'];

    array_push($arr, [
      'amount' => Array(
            'value'=>''
      ),
      'payee' => Array(
        'email_address' => $email
      )
    ]);
  }

  $arr = json_encode($arr);
  echo $arr;
}
else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['insert'])) {
  $first_name = mysqli_real_escape_string($connect, $_POST['first_name']);
  $last_name = mysqli_real_escape_string($connect, $_POST['last_name']);
  $email = mysqli_real_escape_string($connect, $_POST['email']);
  $phone = mysqli_real_escape_string($connect, $_POST['phone']);
  $zip = mysqli_real_escape_string($connect, $_POST['zip']);
  $city = mysqli_real_escape_string($connect, $_POST['city']);
  $country = mysqli_real_escape_string($connect, $_POST['country']);
  $address = mysqli_real_escape_string($connect, $_POST['address']);
  $Arrdata = $_POST['Arrdata'];
  $Arrdata = json_decode($Arrdata, true);
  
  do {
      $command_id = uniqid();
      $sql = "SELECT id FROM command WHERE id = '$command_id'";
      $result = mysqli_query($connect, $sql);
  } while (mysqli_num_rows($result) > 0);
  $buyer_id = $id;
  $date = date('Y-m-d', time());
  $state = 'In Progress';
  for ($i = 0; $i < count($Arrdata); $i++) {
    $count = $Arrdata[$i]['count'];
    $id = $Arrdata[$i]['id'];
    $size = $Arrdata[$i]['size'];
    $sql = "INSERT INTO `command` (`id`, `product_id`, `buyer_id`, `count`, `state`, `size`, `first_name`, `last_name`, `address`, `zip`, `country`, `city`, `phone`, `email`, `date`)
            VALUES ('$command_id', '$id', '$buyer_id', '$count', '$state', '$size', '$first_name', '$last_name', '$address', '$zip', '$country', '$city', '$phone', '$email', '$date')";
    $result = mysqli_query($connect, $sql);
    $sql = "UPDATE product SET count = count - $count WHERE id = '$id'";
    $result = mysqli_query($connect, $sql);
  }
  echo 1;
}

?>
