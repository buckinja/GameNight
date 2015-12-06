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
		<!-- <meta name="viewport" content="width=device=width, initial-scale=1"> -->
		<link rel="icon" href="../../favicon.ico">

		<title>Game Night</title>
		<link href="css/bootstrap.css" rel="stylesheet">
		<link href="css/gamenight.css" rel="stylesheet">
		<link rel="stylesheet" href="//maxcdn.boostrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<link href='https://fonts.googleapis.com/css?family=Fira+Sans:400,700' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Orbitron:500' rel='stylesheet' type='text/css'>
		<script src="actions.js"></script>

		<!-- scripts that require php -->
		<script>
			//creates html on right side of the screen with all players and a button to remove any of them immediately from the roster. 
			//Onclick calls removePlayer(player)
			viewRoster = function () {
				var playerRoster = <?php echo json_encode($_SESSION['players']) ?>;
				var newPlayer;
				var obj;
				var key;
				var player;

				console.debug(playerRoster);

				document.getElementById("workarea").innerHTML = "";

				var area = document.getElementById("workarea");
				var areaForm = document.createElement('div');
				areaForm.innerHTML = '<div class="mainText"><h2>THIS EVENT\'S ROSTER</h2><div class="form-group" id="playerRosterDiv"></div></div><div id="message4"></div>';
				area.appendChild(areaForm);


		        for(key in playerRoster) {
		            player = playerRoster[key];
		            console.debug("player: " + player);
			    	newPlayer = document.createElement('div');
			    	newPlayer.innerHTML = '<p style="width:100%"><div class="rosterSpace"><span class="rosterPlayer">' + player + '</span></div><button class="buttonRoster" type="button" onclick="removePlayer(\'' + player + '\')">REMOVE PLAYER</button></p>';
		    		areaForm.appendChild(newPlayer);
		    	}
			}

			//adds radio buttons and hidden input in order to collect user input about players' scores
			recordPlayerScore = function () {
				var playerRoster = <?php echo json_encode($_SESSION['players']) ?>;
				var score;
				var obj;
				var key;
				var player;

		        for(key in playerRoster) {
		            player = playerRoster[key];
			    	score = document.createElement('div');
			    	score.innerHTML = '<div class="form-group"><div class="rosterSpace"><span class="rosterPlayer">' + player + '</span></div><form><input type="hidden" name="nameofplayer" value="' + player + '"><input type="radio" name="score" value="win">Win<input type="radio" name="score" value="loss">Loss<input type="radio" name="score" value="tie">Tie</form></div>';
		    		addPlayerDiv.appendChild(score);
		    	}
			}

			//players are stored in roster in session storage, so this uses AJAX to send player name to removeplayer.php for removal from roster
			function removePlayer(playerName) {
				var params = "uname=" + encodeURIComponent(playerName);

				var xhr = new XMLHttpRequest();
				if (!xhr) {
			        throw 'Unable to create HttpRequest.'; 
			    }

			    xhr.open("POST", "removeplayer.php", true);
			    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr.send(params);
				 
				xhr.onreadystatechange = function ()
				{
				    if (xhr.readyState==4 && xhr.status==200)  {
				      	document.getElementById("message4").innerHTML = '<p>' + xhr.responseText + '</p>';
				      	window.location.reload();
				    }
				}
			}
		</script>

	</head>

	<body>
		<?php include("navigation.php"); ?>

		<div class="row">

			<!-- left side of screen -->
			<div class="col-md-3 col-xs-3">
				<div class="inside inside-full-height">
      			  	<div class="divContent">
      			  		<p><button class="buttonAction" type="button" onclick="hostGameNight()">ADD A PLAYER TO THE ROSTER</button></p>
      			  		<p><button class="buttonAction" type="button" onclick="viewRoster()">VIEW CURRENT ROSTER</button></p>
      			  		<p><button class="buttonAction" type="button" onclick="recordRound()">RECORD GAME OUTCOME</button></p>
      			  		<p><button class="buttonAction" type="button" onclick="getRecommendation()">GET RECOMMENDATIONS</button></p>
      			  		<p><button class="buttonAction" type="button" onclick="seeRank()">SEE HOW YOU RANK</button></p>
      			  		<p><button class="buttonAction" type="button" onclick="insertGame()">ADD A NEW GAME</button></p>
      			  		<p><button class="buttonAction" type="button" onclick="rateGame()">RATE A GAME/CHANGE RATING</button></p>
      			  	</div>
     			</div>
			</div>

			<!-- right side of screen -->
			<div class="col-md-9 col-xs-9">
				<div class="inside inside-full-height">
      				<div class="divContent">
      					<div class="mainText" id="workarea">
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
							<h5>Happy gaming.</h5>
							<br>
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
