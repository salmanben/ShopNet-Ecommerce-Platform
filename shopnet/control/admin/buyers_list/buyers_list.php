<?php
include "../../../connect_DB.php";
include('../layout/aside.php');
include('../layout/header.php');
if(!isset($_SESSION['id'])){
  header("location:../login/login.php");
  exit;
}

if ($_SESSION['role'] !== 'admin') {
  http_response_code(404);
  exit;
}
$sql = " SELECT * from buyer";
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
  <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Buyers</title>
  <link rel="icon" href="../../../icon.png">
  <script defer src="../scripts/handle_actions.js"></script>
  <script defer src="../scripts/table_script.js"></script>
</head>

<body>
  <div class="wrapper">
    <?php echo $aside; ?>
    <div class="container">
      <?php echo $header; ?>
      <div class="content">
        <h2>Buyers</h2>
        <div class="search">
          <input type="search" placeholder="Search by id..." id="">
          <i class="fa-solid fa-magnifying-glass"></i>
        </div>
        <div class="table">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody class="tbody">
              <?php
              while ($row = mysqli_fetch_assoc($result)) {
                $checked = $row["status"] ==  1 ? "checked" : "";
                echo '
                <tr class="item" id="' . $row["id"] . '">
                 <td>' . $row["id"] . '</td>
                 <td>' . $row["username"] . '</td>
                 <td>' . $row["email"] . '</td>
                 <td><input onclick="switchStatus(event, \'' . $row["id"] . '\')" ' . $checked . ' type="checkbox" name="" id="" class="status"></td>
                </tr>
                ';
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