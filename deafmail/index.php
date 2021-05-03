<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
$username = 'deafmailtest@gmail.com';
$password = '!Password1';


try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $username;                     // SMTP username
    $mail->Password   = $password;                               // SMTP password
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    //Recipients
    $mail->setFrom('joshualeepinch@gmail.co.uk', 'Mailer');
    $mail->addAddress('metamorphias@gmail.com');

    $body = '<p><strong>Hello</strong>testing phpmailer</p>';

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'test';
    $mail->Body    = $body;
    $mail->AltBody = strip_tags($body);

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
/* connect to gmail */
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';

/* try to connect */
$inbox = imap_open($hostname,$username ,$password) or die('Cannot connect to Gmail: ' . imap_last_error());

/* grab emails */
$emails = imap_search($inbox,'UNSEEN');

/* if emails are returned, cycle through each... */
if($emails) {

    /* begin output var */
    $output = '';

    /* put the newest emails on top */
    rsort($emails);

    /* for every email... */
    foreach($emails as $email_number) {

        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox,$email_number,0);


        $output.= 'Name:  '.$overview[0]->from.'</br>';
            $output.= 'Email:  '.$overview[0]->message_id.'</br>';
                $output.= 'body:  '.imap_fetchbody($inbox,$email_number,2).'</br>';;



    }

    echo $output;
} 

/* close the connection */

imap_close($inbox);
$servername = "localhost";
$sqlusername = "admin";
$sqlpassword = "password1";

// Create connection
$conn = new mysqli($servername, $sqlusername, $sqlpassword);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>