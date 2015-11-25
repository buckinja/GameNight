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

		<script src="signup.js"></script>
	</head>

	<body>
		<?php include("navigation.php"); ?>

		<div class="container-fluid">
      		<div class="content">
				<div class="mainText">
				  	<h2>Hello again!</h2>
					<br>
					<p><a href="newaccount.php"><button id="centered" class="buttonOther" type="button">CREATE AN ACCOUNT</button></a></p>
					<br>
					<h2>Login:</h2>
					<br>
				  		<div class="form-group">
				  			<label>User name:</label>
				  			<input id="uname2" type="text" class="form-control">
				  		</div>
				  		<div class="form-group">
				  			<label>Password:</label>
				  			<input id="pc2" type="password" class="form-control">
				  		</div>
				  		<div id="message2"></div>
				  		<div id="log"></div>
						<br>
						<div>
						<p><button onclick="signIn()" class="buttonOther" id="centered">LOGIN</button></p>
						</div>
				</div>
			</div>
		</div>


		<!-- Bootstraps javascript -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="ie10-viewport-bug-workaround.js"></script>
	</body>
</html>