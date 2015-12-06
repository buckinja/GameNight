//Inserts html in the right side of the screen with options to enter a new game in the database 
//User manually types a game name and uses a select drop-down to choose the genre and category
//On button click in new html elements, submitNewGame() is called.
insertGame = function () {
	document.getElementById("workarea").innerHTML = "";

	var area = document.getElementById("workarea");
	var areaForm = document.createElement('div');
	areaForm.innerHTML = '<div class="mainText"><h2>ADD A NEW GAME</h2><div class="form-group"><label>Game title:</label><input id="gameTitle" type="text" class="form-control"></div><div class="form-group"><label>Category:</label><select id="genre" class="form-control"><option>Other</option><option>Cards</option><option>Children</option><option>Cooperative</option><option>Euro-game</option><option>Logic</option><option>Mass market game</option><option>Miniature/tabletop</option><option>Party game</option><option>RPG</option><option>Strategy</option><option>Word game</option></select></div><div class="form-group"><label>Theme:</label><select id="theme" class="form-control"><option>None</option><option>Adventure</option><option>Economics</option><option>Fantasy</option><option>Farming/Settling</option><option>Gambling</option><option>Historical</option><option>Mystery</option><option>Race</option><option>Sci-fi</option><option>Steampunk</option><option>Trains</option><option>Trivia</option><option>War</option></select></div><br><div id="message3"></div><p><button onclick="submitNewGame()" class="buttonOther" id="centered">ADD GAME</button></p></div>';
	area.appendChild(areaForm);
}


//Pulls user input and uses AJAX to send new game info to a php via post
submitNewGame = function() {
	var gameName = document.getElementById("gameTitle").value;
	var genre = document.getElementById("genre").value;
	var theme = document.getElementById("theme").value;
	var params = "gameName=" + encodeURIComponent(gameName) + "&genre=" + encodeURIComponent(genre) + "&theme=" + encodeURIComponent(theme);

	//if no game name entered, error message
	if(gameName === "") {
		document.getElementById("message3").innerHTML = "<p>Enter a game title.</p>";
	} else {
		//otherwise, AJAX to send info to newgame.php
		var xhr = new XMLHttpRequest();
		if (!xhr) {
	        throw 'Unable to create HttpRequest.'; // you're using a horrible browser
	    }

	    xhr.open("POST", "newgame.php", true);
	    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(params);
		 
		xhr.onreadystatechange = function () {
		    if (xhr.readyState==4 && xhr.status==200)  {

		    	//confirmation of insertion in the database is returned
		        document.getElementById("message3").innerHTML = xhr.responseText;
			}
		}
	}
}

//Inserts html button in the right side of the screen with options to add a player to the night's game roster
//On button click in new html elements, addNewPlayer() is called.
hostGameNight = function () {
	document.getElementById("workarea").innerHTML = "";

	var area = document.getElementById("workarea");
	var areaForm = document.createElement('div');
	areaForm.innerHTML = '<div class="mainText"><h2>HOST A GAME NIGHT</h2><div class="form-group"><p>Have each player enter their login credentials here to add them to the roster:</p><div id="addPlayerDiv"><button class="buttonOther" type="button" onclick="addNewPlayer()">ADD PLAYER</button></div><div id="message0"></div></div></div>';
	area.appendChild(areaForm);
}

//Inserts html in the right side of the screen with options to add a player to the night's game roster
//User (or rather, user's friends) manually enter name and password in input text boxes
//On button click in new html elements, addNewPlayer() is called.
addNewPlayer = function () {
	document.getElementById("addPlayerDiv").innerHTML = "";

	var newdiv = document.getElementById("addPlayerDiv");
	var playerForm = document.createElement("div");
	playerForm.innerHTML = '<div class="form-group"><label>User name:</label><input id="username" type="text" class="form-control"></div><div class="form-group"><label>Password:</label><input id="passc" type="password" class="form-control"></div><br><p><button onclick="checkPlayerUserName()" class="buttonOther" id="centered">SIGN UP</button></p>';
	newdiv.appendChild(playerForm);
}

//checks if account information entered is valid sends AJAX call to server to create account if so
checkPlayerUserName = function () {
	//gets var data from html elements, and if address elements not entered, stores an empty string in each.
	var uname = document.getElementById("username").value;
	var pc = document.getElementById("passc").value;

	//encoding url-style string with variable data to send as POST in AJAX
	var params = "uname=" + encodeURIComponent(uname) + "&pc=" + encodeURIComponent(pc);

	//if no username or password entered, error message
	if(uname === "" || pc === "") {
		document.getElementById("message0").innerHTML = "<p>Please complete all fields.</p>";
	} 

	//passed all error checking at the browser level, so start AJAX.  If server sends error messages, they will show in the same html element as above.
	else {
		var xhr = new XMLHttpRequest();
		if (!xhr) {
	        throw 'Unable to create HttpRequest.'; // you're using a horrible browser
	    }

	    xhr.open("POST", "addplayer.php", true);
	    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(params);
		 
		xhr.onreadystatechange = function () {
		    if (xhr.readyState==4 && xhr.status==200) {
		        document.getElementById("message0").innerHTML = '<p>' + xhr.responseText + '</p>';
		    }

		    if (xhr.responseText == "Player added successfully.") {
		    	window.location.reload();
		    }
		}
	}
}

//Inserts html in the right side of the screen with options to record the outcome of a game on completion
//User manually types a game name and uses radio buttons to record win/loss/tie for each player in the roster
//On button click in new html elements, enterGameData() is called.
recordRound = function () {
	document.getElementById("workarea").innerHTML = "";

	var area = document.getElementById("workarea");
	var areaForm = document.createElement('div');
	areaForm.innerHTML = '<div class="mainText"><h2>RECORD GAME OUTCOME</h2><div class="form-group"><input type="text" id="gameTitle"></div><div id="addPlayerDiv"></div><button class="buttonOther" type="button" onclick="enterGameData()">GAME OVER</button><div id="message5"></div></div></div>';
	area.appendChild(areaForm);
	//radio buttons are added here
	recordPlayerScore();
}


// sends information to the database via AJAX to create a row in table 'round' 
// After AJAX is complete, it iterates through the players and scores and calls
// insertNewRoundPlayer() on each to record each player's info in a 'round-player'
// row that associates and player with a particular round.
function enterGameData () {
	var gameTitle = document.getElementById("gameTitle").value;
	var roster = document.getElementsByName("nameofplayer"); //array of players in roster
	var allScores = document.getElementsByName("score");
	var leftoff = 0;
	var i, j;
	var player;
	var score;
	var params;

    // date code from: http://stackoverflow.com/questions/1531093/how-to-get-current-date-in-javascript
    // after this code, var today holds a date/time stamp that uniquely id's this round -- this is primarily for
    // developing the website further after the end of CS340, but it is useful in one query already.
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; 
	var yyyy = today.getFullYear();
	var h = today.getHours();
	var m = today.getMinutes();
	var s = today.getSeconds();

	if(dd < 10) {
	    dd = '0' + dd;
	} 

	if(mm < 10) {
	    mm = '0' + mm;
	} 

	if(m < 10) {
	    m = '0' + m;
	} 

	if(s < 10) {
	    s = '0' + s;
	} 

	//including hours and minutes so that each round can be uniquely identified by its date variable
	today = mm + '/' + dd + '/' + yyyy + " " + h + ":" + m + ":" + s;

	//if no game title entered, error message
	if(gameTitle === "") {
		document.getElementById("message5").innerHTML = "<p>Please complete all fields.</p>";
	} else {
		//AJAX to send info to create round
		var	params = "score=''&player=''&gameTitle=" + encodeURIComponent(gameTitle) + "&date=" + encodeURIComponent(today);

		var xhr = new XMLHttpRequest();
		if (!xhr) {
	        throw 'Unable to create HttpRequest.'; 
	    }

	    xhr.open("POST", "recordround.php", true);
	    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(params);
		 
		xhr.onreadystatechange = function () {
		    if (xhr.readyState==4 && xhr.status==200) {

		        //round is finished being created, so the round_players are ready to be inserted
		        for (i = 0; i < roster.length; i++) {
		        	//scores are collected in sets of 3, so for each player, look at 3 scores to find the selected one
					for (j = leftoff; j < leftoff + 3; j++) {
					    if (allScores[j].checked == true) {
					        score = allScores[j].value;
					    }
					}

					leftoff = j;
					player = roster[i].value;

					//call function to insert new round_player row for each 
					insertNewRoundPlayer(gameTitle, score, player, today);
					
				}
		    }
		}

	}
}


//sends info to recordround.php to create a round_player row that associates a player with a particular round
function insertNewRoundPlayer(gameTitle, score, player, date) {
	var params = "gameTitle=" + encodeURIComponent(gameTitle) + "&score=" + encodeURIComponent(score) + "&player=" + encodeURIComponent(player) + "&date=" + encodeURIComponent(date);

	//sending info via AJAX
	var xhr = new XMLHttpRequest();
	if (!xhr) {
        throw 'Unable to create HttpRequest.'; // you're using a horrible browser
    }

    xhr.open("POST", "recordround.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send(params);
	 
	xhr.onreadystatechange = function () {
	    if (xhr.readyState==4 && xhr.status==200) {
	        document.getElementById("message5").innerHTML = '<p>' + xhr.responseText + '</p>';
	        console.log(xhr.responseText);
	    }
	}
}

//Inserts html in the right side of the screen with options to store a player's game rating in the database
//User manually types a game name and uses drop-down to select a star rating (1-5)
//On button click in new html elements, rateThisGame() is called.
rateGame = function() {
	document.getElementById("workarea").innerHTML = "";

	var area = document.getElementById("workarea");
	var areaForm = document.createElement("div");
	areaForm.innerHTML = '<div class="form-group"><label>Game:</label><input id="gameName" type="text" class="form-control"><br><label>Select rating:</label><select id="ratings" class="form-control"><option value="*">*</option><option value="**">**</option><option value="***">***</option><option value="****">****</option><option value="*****">*****</option></select></div><br><p><button onclick="rateThisGame()" class="buttonOther" id="centered">RATE GAME</button></p><div id="message6"></div>';
	area.appendChild(areaForm);
}


//checks value of select and sends a float var and game to rategame.php to add a row in game_rating that
//associates a game with a particular player.  
rateThisGame = function() {
	var rating = document.getElementById("ratings").value;
	var gameTitle = document.getElementById("gameName").value;

	console.log("ratings: " + rating + "  game: " + gameTitle);
	var float_rating;

	switch(rating) {
		case "*": 
			float_rating = 1.0;
			break;
		case "**": 
			float_rating = 2.0;
			break;
		case "***": 
			float_rating = 3.0;
			break;
		case "****": 
			float_rating = 4.0;
			break;
		case "*****": 
			float_rating = 5.0;
			break;
	}

	var params = "rating=" + encodeURIComponent(float_rating) + "&gameTitle=" + encodeURIComponent(gameTitle);

	console.log(params);


	//sending info to rategame.php via AJAX
	var xhr = new XMLHttpRequest();
	if (!xhr) {
        throw 'Unable to create HttpRequest.'; // you're using a horrible browser
    }

    xhr.open("POST", "rategame.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send(params);
	 
	xhr.onreadystatechange = function () {
	    if (xhr.readyState==4 && xhr.status==200) {
	        document.getElementById("message6").innerHTML = '<p>' + xhr.responseText + '</p>';
	        console.log(xhr.responseText);
	        window.location.reload();
	    }
	}
}


//creates table on the right side of the screen with rows created dynamically with database info via AJAX.
//On receipt of data, it parses the JSON obj and displays it in the table.
seeRank = function() {
	var newPlayer;

	document.getElementById("workarea").innerHTML = "";

	var area = document.getElementById("workarea");
	var areaForm = document.createElement("div");
	areaForm.innerHTML = '<div class="mainText"><h2>PLAYERS RANKED BY MOST GAMES WON</h2><div><span class="rankSpan" id="usermessage"></span></div><div class="table-div"><table class="table table-striped"><thead><tr><th>Player</th><th>Wins</th></tr></thead><tbody id="ranksDiv"></tbody></table></div></div>';
	area.appendChild(areaForm);


	//AJAX to request query results... no data sent this time since player's info is stored in session storage
	var xhr = new XMLHttpRequest();
	if (!xhr) {
        throw 'Unable to create HttpRequest.'; // you're using a horrible browser
    }

    xhr.open("POST", "getrank.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send();
	 
	xhr.onreadystatechange = function () {
	    if (xhr.readyState==4 && xhr.status==200) {
	        console.log(xhr.responseText);
	        //AJAX response is in JSON form this time...
	        var response = JSON.parse(xhr.responseText);
	        var fname;
	        var lname;
	        var wins;
	        var userWins;

	        //loop through obj and pull out info, dynamically creating table elements each loop
	        for(key in response) {
	        	console.log(response[key]);
	        	console.log("user: " + response['user']);
	        	if (response[key].fname) {
		        	fname = response[key].fname;
		        	lname = response[key].lname;
		        	wins = response[key].wins;
		        	console.log(fname + " " + lname + " " + wins);

		        	newPlayer = document.createElement('tr');
			    	newPlayer.innerHTML = '<td class="rankSpan">' + fname + ' ' + lname + '</td><td class="rankSpan">' + wins + '</td>';
					ranksDiv.appendChild(newPlayer);
		        } 
		        //when user's data is found in the object, create a message with their win count
		        if (response['user'] === response[key].id) {
	        		userWins = response[key].wins;
	        		console.log("User has WON " + userWins + " times.");
	        		var msg = document.getElementById("usermessage");
	        		msg.innerHTML = "You have won " + userWins + " games total!";
	        	}
			}
	    }
	}
}

//Inserts html in the right side of the screen with options to select and view data from the database
//User uses drop-down to select which kind of query to run, and if appropriate enters genre/theme data into a text input
//On button click in new html elements, recommendGames() is called.
getRecommendation = function() {
	document.getElementById("workarea").innerHTML = "";

	var area = document.getElementById("workarea");
	var areaForm = document.createElement("div");
	areaForm.innerHTML = '<div class="form-group"><label>Recommend a game by:</label><select id="recommend" class="form-control"><option value="rating">RATING (lists all games on record ordered by rating)</option><option value="theme">THEME (ex: Adventure, Gambling, Fantasy, or Trains - enter theme below) </option><option value="genre">GENRE (ex: Cards, Cooperative, Euro-game, or Party game - enter genre below)</option><option value="yourrating">YOUR PERSONAL FAVORITES (lists the 10 games you have rated highest, or all if you have rated fewer than 10 games)</option><option value="popularity">POPULARITY (shows top 10 most-played games in the system)</option></select></div><br><div class="form-group"><label>Enter THEME or GENRE if applicable: </label><br><input type="text" id="extraData"></div><p><button onclick="recommendGames()" class="buttonOther" id="centered">FETCH GAMES</button></p><div id="message8"></div>';
	area.appendChild(areaForm);

}


//This is the most query-heavy function.  It creates html in the right side of the screen for a table based on user's input and 
//when AJAX completes, the table is populated dynamically with the results.
recommendGames = function() {
	var selectMenu = document.getElementById("recommend");
	var data = document.getElementById("extraData").value;
	var selectType = selectMenu.options[selectMenu.selectedIndex].value;
	document.getElementById("workarea").innerHTML = "";	
	var area = document.getElementById("workarea");
	var areaForm = document.createElement("div");
	//table is created
	areaForm.innerHTML = '<div class="mainText"><h2 id="recommendheader"></h2><div class="form-group"><div class="table-div"><table class="table table-striped"><thead><tr><th>Game</th><th>Genre</th><th>Theme</th><th id="otherCategory">Overall rating</th></tr></thead><tbody id="recommendGames"></tbody></table></div><div id="message8"></div></div>';
	area.appendChild(areaForm);
	var rec = document.getElementById("recommendGames");

	//insert header, and change last column head if appropriate
	switch(selectType) {
		case 'theme':
			document.getElementById("recommendheader").innerHTML = "GAME RECOMMENDATIONS: BY THEME";
			break;
		case 'genre':
			document.getElementById("recommendheader").innerHTML = "GAME RECOMMENDATIONS: BY GENRE";
			break;
		case 'rating':
			document.getElementById("recommendheader").innerHTML = "GAME RECOMMENDATIONS: BY RATING";
			break;
		case 'yourrating':
			document.getElementById("recommendheader").innerHTML = "GAME RECOMMENDATIONS: YOUR TOP FAVORITE GAMES";
			document.getElementById("otherCategory").innerHTML = "";
			document.getElementById("otherCategory").innerHTML = "Your rating";
			break;
		case 'popularity':
			document.getElementById("recommendheader").innerHTML = "GAME RECOMMENDATIONS: BY POPULARITY";
			document.getElementById("otherCategory").innerHTML = "";
			document.getElementById("otherCategory").innerHTML = "Times played";
			break;
		default:
			console.log("invalid select option");
	}

	var params = "selectoption=" + encodeURIComponent(selectType) + "&data=" + encodeURIComponent(data);

	console.log(params);


	var xhr = new XMLHttpRequest();
	if (!xhr) {
        throw 'Unable to create HttpRequest.'; // you're using a horrible browser
    }

    xhr.open("POST", "recommend.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.send(params);
	 
	xhr.onreadystatechange = function () {
	    if (xhr.readyState==4 && xhr.status==200) {

	        var response = JSON.parse(xhr.responseText);

	        if (response["error"]) {
	        	//detailed error message for if no data was returned
	        	document.getElementById("message8").innerHTML = '<h5>Your query did not match any games in our system.</p><p>If you selected "theme" or "genre," please type accurately and be aware that the system is case-sensitive.  If you are looking for your personal favorites, be sure that you have entered some ratings in the system!</h5>';
	        } else {
	        	//if there is data, plug it into the table
		        for(key in response) {
		        	console.log(response[key]);
		        	if (response[key].rating) {
			        	game = response[key].game;
			        	genre = response[key].genre;
			        	theme = response[key].theme;
			        	rating = response[key].rating;

			        	if (rating === null) {
			        		rating = "Not yet rated";
			        	}

			        	newGame = document.createElement('tr');
				    	newGame.innerHTML = '<td class="rankSpan">' + game + '</td><td class="rankSpan">' + genre + '</td><td class="rankSpan">' + theme + '</td><td class="rankSpan">' + rating + '</td>';
						
						rec.appendChild(newGame);
					}
				}
			}
	    }
	}
}




