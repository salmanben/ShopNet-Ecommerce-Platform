<?php
session_start();
$id = $_SESSION['id'];
$sql = "select image from seller where id ='$id'";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);
$image = $row == null ? '' : $row['image'];
$header = '<header>

<img class="user" src="../../../upload/'. $image . '" alt="">
</header>';

?>