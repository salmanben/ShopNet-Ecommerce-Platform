<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include "../../connect_DB.php";
$sql = "select * from email_settings order by id desc limit 1";
$result = mysqli_query($connect, $sql);
$row_email = mysqli_fetch_assoc($result);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $data = file_get_contents('php://input');
  $data = json_decode($data);
  $email = $data->email;
  $code = $data->code;
// Load Composer's autoloader
require 'vendor/autoload.php';

// Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP

    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server's hostname or IP address

    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $row_email['username'];           // SMTP username
    $mail->Password   = $row_email['password'];                     // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
    $mail->Port       = 465;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    // Recipients
    $mail->setFrom($row_email['username'], $row_email['from_name'] );               // Set the sender's email address and name
    $mail->addAddress("$email");     

    // Content
    $mail->isHTML(true);                                        // Set email format to HTML
    $mail->Subject = 'Verification Code';
    $mail->Body    = '
    <html>
    <head>
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&family=Roboto&display=swap" rel="stylesheet">
    </head>
    <body style="background-color: #ff006e;padding :20px 20px 100px 20px">
      <h1>
        <a href="Home.php" style="color: white; display: flex; align-items: center; font-family: \'Roboto\', sans-serif; text-decoration: none;">
          Shop<span style="color:#9113a4">Net</span>
        </a>
      </h1>
      <p style="color: #333333; font-size: 16px; line-height: 1.5;font-weight:bold;font-size:18px; margin-top: 50px; font-family: Arial, sans-serif; text-align: center;">
      Your verification code: ' .$code.'
      </p>
    </body>
  </html>
  
    ';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();                                              // Send the email

    echo 'Message has been sent';                               // Display success message if the email was sent successfully
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";  // Display error message if there was an error sending the email
}

}
?>