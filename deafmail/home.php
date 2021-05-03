<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.html');
	exit;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<script src="main.js" defer></script>
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Website Title</h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="exit.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
				<a href="inbox.php"><i class="fas fa-list"></i>Inbox</a>
				<a href="compose.php"><i class="fas fa-envelope-open"></i>Compose</a>
			</div>
		</nav>
		<main>
		<div class="login">
			<h2>Home Page</h2>
			<p>Welcome back, <?=$_SESSION['name']?>!</p>
			<form action="url" method="post" name="submit">
				<label for="button1">
						<i class="fas fa-microphone"></i>
				</label>
				<input type="text" id="button1" value="Start listening">
				<label for="input">
					<i class="fas fa-user"></i>
				</label>
				<input type="text" name="input" placeholder="input" id="input" required>
				<input type="submit" value="goto">
				
			</form>
		</div>
	</main>
	</body>
	
</html>
