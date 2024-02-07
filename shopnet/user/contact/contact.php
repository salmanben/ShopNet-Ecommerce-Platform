<?php
include '../layout/layout.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="contact.css">
  <link rel="stylesheet" href="../layout/layout.css">
  <link rel="icon" href="../../icon.png">
  <script defer src="contact.js"></script>
  <script defer src="../layout/layout.js"></script>
  <title>Contact</title>
</head>

<body>
  <?php echo $header ?>
  <div class="container">
    <div class="form">
      <h2>
        <a href="../home/index.php">
          <img src="../../icon.png" alt="logo"> Shop<span>Net</span>
        </a>
      </h2>
      <p class="error message"></p>
      <form>
        <div>
          <label for="name">Name:</label>
          <input type="text" value="" name="name" placeholder="Name" id="name">
        </div>
        <div>
          <label for="email">Email:</label>
          <input type="email" name="email" placeholder="Email" id="email">
        </div>
        <div>
          <label for="message">Message:</label>
          <textarea name="message" placeholder="Message" id="message" cols="30" rows="10"></textarea>
        </div>
        <button type="submit">Submit</button>
      </form>
    </div>
  </div>
  <?php echo $footer ?>
</body>

</html>