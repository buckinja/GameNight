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