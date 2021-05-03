<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.html');
	exit;
}
if($_SERVER['REQUEST_METHOD']=='POST')
           {	
           		//gets email and password
               	$DATABASE_HOST = 'localhost';
				$DATABASE_USER = 'root';
				$DATABASE_PASS = '';
				$DATABASE_NAME = 'phplogin';
				$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
				if (mysqli_connect_errno()) {
					exit('Failed to connect to MySQL: ' . mysqli_connect_error());
				}
				// We don't have the password or email info stored in sessions so instead we can get the results from the database.
				$stmt = $con->prepare('SELECT gmail, gmailpsd FROM accounts WHERE id = ?');
				// In this case we can use the account ID to get the account info.
				$stmt->bind_param('i', $_SESSION['id']);
				$stmt->execute();
				$stmt->bind_result($gmail, $gmailpsd);
				$stmt->fetch();
				$stmt->close();
				// Load Composer's autoloader
				require 'vendor/autoload.php';
				// Instantiation and passing `true` enables exceptions
				$mail = new PHPMailer(true);
				try {
				    //Server settings
				    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
				    $mail->isSMTP();                                            // Send using SMTP
				    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
				    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
				    $mail->Username   = $gmail;                     // SMTP username
				    $mail->Password   = $gmailpsd;                               // SMTP password
				    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
				    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

				    //Recipients
				    $mail->setFrom($gmail, 'Mailer');
				    $mail->addAddress($_POST['recipient']);

				    // Content
				    $mail->isHTML(true);                                  // Set email format to HTML
				    $mail->Subject = $_POST['header'];
				    $mail->Body    = $_POST['body'];
				    $mail->AltBody = strip_tags($_POST['body']);

				    $mail->send();
				    echo 'Message has been sent';
				} catch (Exception $e) {
				    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
				} 
				header('Location: home.php');
           } 
?>