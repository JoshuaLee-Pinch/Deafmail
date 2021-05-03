<?php
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.html');
	exit;
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT gmailpsd, gmail FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $username);
$stmt->fetch();
$stmt->close();


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
        		$fromemail = $overview[0]->from;
            	$subjectemail = $overview[0]->subject;
                $recipient = $overview[0]->to ;
                $seen =0;
                $dateofmail =$overview[0]->date;
                $body = imap_fetchbody($inbox,$email_number,1);

            	$output.= 'body0:  '.imap_fetchbody($inbox,$email_number,0).'</br>';;
            	$output.= 'body1:  '.imap_fetchbody($inbox,$email_number,1).'</br>';;
            	$output.= 'body1.1:  '.imap_fetchbody($inbox,$email_number,1.1).'</br>';;
            	$output.= 'body1.2:  '.imap_fetchbody($inbox,$email_number,1.2).'</br>';;
            	$output.= 'body2:  '.imap_fetchbody($inbox,$email_number,2).'</br>';;
            	$output.= 'body2.0:  '.imap_fetchbody($inbox,$email_number,2.0).'</br>';;
            	$output.= 'body2.1:  '.imap_fetchbody($inbox,$email_number,2.1).'</br>';;

            	$output.= 'body2.2:  '.imap_fetchbody($inbox,$email_number,2.2).'</br>';;
            	$output.= 'body2.3:  '.imap_fetchbody($inbox,$email_number,2.3).'</br>';;
	  			if ($stmt = $con->prepare(' INSERT INTO inboxmail (fromemail, subjectemail, recipient, seenmail, dateofmail, body) VALUES (?,?,?,?,?,?)')) {
					
					$stmt->bind_param('ssssss',$fromemail, $subjectemail,$recipient,$seen,$dateofmail,$body);
					$stmt->execute();
					echo 'You have successfully registered, you can now login!';
				} else {
					// Something is wrong with the sql statement, check to make sure accounts table exists with all 3 fields.
					echo 'Could not prepare statement!';
				}
				echo $output;
    }
    $stmt->close();
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
$con->close();
?>