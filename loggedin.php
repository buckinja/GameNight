<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	header('Content-Type: text/html');
	session_start();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device=width, initial-scale=1">
		<link rel="icon" href="../../favicon.ico">

		<title>Game Night</title>
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/gamenight.css" rel="stylesheet">
		<link rel="stylesheet" href="//maxcdn.boostrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<link href='https://fonts.googleapis.com/css?family=Fira+Sans:400,700' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Orbitron:500' rel='stylesheet' type='text/css'>
	</head>

	<body>
		<?php include("navigation.php"); ?>

		<div class="container-fluid">
      		<div class="content">
				<div class="mainText">
					<?php
						//if no username is stored, redirect to login page
						if (session_status() == PHP_SESSION_ACTIVE) {
							if (!isset($_SESSION['uname'])) { 
								echo "<h2>Please sign in!</h2>";
								echo '<script type="text/javascript">
					     			var redirect = function () {
					         			window.location.replace("gamenight.php");
					     			};
					     			redirect();</script>';
							} else {
								echo "<h2>Welcome, " . $_SESSION['uname'] . "!</h2>";
							}
						}
					?>
					<br>
					<p>TEXT HERE.</p>
					<br>
				</div>
			</div>
		</div>

		<div class="row">

			<!-- left side of screen -->
			<div class="col-md-6 col-xs-6">
				<div class="inside inside-full-height">
      			  	<div class="content">
      			  		<div class="mainText">
      				  		<h2>Your Assigned Section Title Here</h2>
							<p>This is where you will code and work!!</p>
							<p>Tables, login, text, and forms in this section, please </p>
							<p>Example Buttons to use below: </p>
							<p><button class="buttonLogin" type="button">Login</button></p>
							<p><button class="buttonOther" type="button">OtherButton</button></p>
						</div>
      			  	</div>
     			</div>
			</div>

			<!-- right side of screen -->
			<div class="col-md-6 col-xs-6">
				<div class="inside inside-full-height">
      				  <div class="content">
      				  	<div class="mainText">
      				  		<h2>TITLE!</h2>
							<p>This is where you will code and work!!</p>
							<p>Tables, login, text, and forms in this section, please </p>
							<p>Example Buttons to use below: </p>
							<p><button class="buttonLogin" type="button">LOGIN</button></p>
							<p><button class="buttonOther" type="button">BUTTON</button></p>
						</div>
					  </div>
				</div>
			</div>
		</div> <!-- end row -->

		<!-- Bootstraps javascript -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="ie10-viewport-bug-workaround.js"></script>
	</body>
</html>
