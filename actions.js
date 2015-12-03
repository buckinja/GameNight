insertGame = function () {
	document.getElementById("workarea").innerHTML = "";

	var area = document.getElementById("workarea");
	var areaForm = document.createElement('div');
	areaForm.innerHTML = '<div class="mainText"><h2>ADD A NEW GAME</h2><div class="form-group"><label>Game title:</label><input id="gameTitle" type="text" class="form-control"></div><div class="form-group"><label>Category:</label><select id="genre" class="form-control"><option>Other</option><option>Cards</option><option>Children</option><option>Cooperative</option><option>Euro-game</option><option>Logic</option><option>Mass market game</option><option>Miniature/tabletop</option><option>Party game</option><option>RPG</option><option>Strategy</option><option>Word game</option></select></div><div class="form-group"><label>Theme:</label><select id="theme" class="form-control"><option>None</option><option>Adventure</option><option>Economics</option><option>Fantasy</option><option>Farming/Settling</option><option>Gambling</option><option>Historical</option><option>Mystery</option><option>Race</option><option>Sci-fi</option><option>Steampunk</option><option>Trains</option><option>Trivia</option><option>War</option></select></div><br><div id="message3"></div><p><button onclick="submitNewGame()" class="buttonOther" id="centered">ADD GAME</button></p></div>';
	area.appendChild(areaForm);
}

submitNewGame = function() {
	var gameName = document.getElementById("gameTitle").value;
	var genre = document.getElementById("genre").value;
	var theme = document.getElementById("theme").value;
	var params = "gameName=" + encodeURIComponent(gameName) + "&genre=" + encodeURIComponent(genre) + "&theme=" + encodeURIComponent(theme);

	if(gameName === "") {
		document.getElementById("message3").innerHTML = "<p>Enter a game title.</p>";
	} else {
		var xhr = new XMLHttpRequest();
		if (!xhr) {
	        throw 'Unable to create HttpRequest.'; // you're using a horrible browser
	    }

	    xhr.open("POST", "newgame.php", true);
	    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(params);
		 
		xhr.onreadystatechange = function () {
		    if (xhr.readyState==4 && xhr.status==200)  {
		        document.getElementById("message3").innerHTML = xhr.responseText;
			}
		}
	}
}

hostGameNight = function () {
	document.getElementById("workarea").innerHTML = "";

	var area = document.getElementById("workarea");
	var areaForm = document.createElement('div');
	areaForm.innerHTML = '<div class="mainText"><h2>HOST A GAME NIGHT</h2><div class="form-group"><p>Have each player enter their login credentials here to add them to the roster:</p><div id="addPlayerDiv"><button class="buttonOther" type="button" onclick="addNewPlayer()">ADD PLAYER</button></div><div id="message0"></div></div></div>';
	area.appendChild(areaForm);
}

addNewPlayer = function () {
	document.getElementById("addPlayerDiv").innerHTML = "";

	var newdiv = document.getElementById("addPlayerDiv");
	var playerForm = document.createElement("div");
	playerForm.innerHTML = '<div class="form-group"><label>User name:</label><input id="username" type="text" class="form-control"></div><div class="form-group"><label>Password:</label><input id="passc" type="password" class="form-control"></div><br><p><button onclick="checkPlayerUserName()" class="buttonOther" id="centered">SIGN UP</button></p>';
	newdiv.appendChild(playerForm);
}

//function checks if account information entered is valid sends AJAX call to server to create account if so
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

recordRound = function () {
	document.getElementById("workarea").innerHTML = "";

	var area = document.getElementById("workarea");
	var areaForm = document.createElement('div');
	areaForm.innerHTML = '<div class="mainText"><h2>RECORD GAME OUTCOME</h2><div class="form-group"><input type="text" id="gameTitle"></div><div id="addPlayerDiv"></div><button class="buttonOther" type="button" onclick="enterGameData()">GAME OVER</button><div id="message5"></div></div></div>';
	area.appendChild(areaForm);
	recordPlayerScore();
}

function enterGameData () {
	var gameTitle = document.getElementById("gameTitle").value;
	var roster = document.getElementsByName("nameofplayer");
	var allScores = document.getElementsByName("score");
	var leftoff = 0;
	var i, j;
	var player;
	var score;
	var params;

    // date code from: http://stackoverflow.com/questions/1531093/how-to-get-current-date-in-javascript
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
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

	console.log("date: " + today);

	if(gameTitle === "") {
		document.getElementById("message5").innerHTML = "<p>Please complete all fields.</p>";
	} else {

		var	params = "score=''&player=''&gameTitle=" + encodeURIComponent(gameTitle) + "&date=" + encodeURIComponent(today);

		var xhr = new XMLHttpRequest();
		if (!xhr) {
	        throw 'Unable to create HttpRequest.'; // you're using a horrible browser
	    }

	    xhr.open("POST", "recordround.php", true);
	    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(params);
		 
		xhr.onreadystatechange = function () {
		    if (xhr.readyState==4 && xhr.status==200) {
		        document.getElementById("message5").innerHTML = '<p>' + today + '</p><p>' + xhr.responseText + '</p>';
		        console.log("insert new log: " + xhr.responseText);

		        //round is finished being created, so the round_players are ready to be inserted
		        for (i = 0; i < roster.length; i++) {
					for (j = leftoff; j < leftoff + 3; j++) {
					    if (allScores[j].checked == true) {
					        score = allScores[j].value;
					    }
					}

					leftoff = j;
					player = roster[i].value;

					//encoding url-style string with variable data to send as POST in AJAX
					insertNewRoundPlayer(gameTitle, score, player, today);
					
				}
		    }
		}

	}
}



function insertNewRoundPlayer(gameTitle, score, player, date) {
	var params = "gameTitle=" + encodeURIComponent(gameTitle) + "&score=" + encodeURIComponent(score) + "&player=" + encodeURIComponent(player) + "&date=" + encodeURIComponent(date);

	console.debug(params);

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

rateGame = function() {
	document.getElementById("workarea").innerHTML = "";

	var area = document.getElementById("workarea");
	var areaForm = document.createElement("div");
	areaForm.innerHTML = '<div class="form-group"><label>Game:</label><input id="gameName" type="text" class="form-control"><br><label>Select rating:</label><select id="ratings" class="form-control"><option value="*">*</option><option value="**">**</option><option value="***">***</option><option value="****">****</option><option value="*****">*****</option></select></div><br><p><button onclick="rateThisGame()" class="buttonOther" id="centered">RATE GAME</button></p><div id="message6"></div>';
	area.appendChild(areaForm);
}


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
	    }
	}
}


seeRank = function() {
	var newPlayer;

	document.getElementById("workarea").innerHTML = "";

	var area = document.getElementById("workarea");
	var areaForm = document.createElement("div");
	areaForm.innerHTML = '<div class="mainText"><h2>PLAYERS RANKED BY MOST GAMES WON</h2><div><span class="rankSpan" id="usermessage"></span></div><div class="table-div"><table class="table table-striped"><thead><tr><th>Player</th><th>Wins</th></tr></thead><tbody id="ranksDiv"></tbody></table></div></div>';
	area.appendChild(areaForm);


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

	        var response = JSON.parse(xhr.responseText);
	        var fname;
	        var lname;
	        var wins;
	        var userWins;

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


getStats = function() {
	
}


