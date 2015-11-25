//function checks if account information entered is valid sends AJAX call to server to create account if so
checkUserName = function () {
	//gets var data from html elements, and if address elements not entered, stores an empty string in each.
	var uname = document.getElementById("uname").value;
	var pc = document.getElementById("pc").value;
	var fname = document.getElementById("fname").value;
	var lname = document.getElementById("lname").value;

	//encoding url-style string with variable data to send as POST in AJAX
	var params = "uname=" + encodeURIComponent(uname) + "&pc=" + encodeURIComponent(pc) + "&fname=" + encodeURIComponent(fname) + "&lname=" + encodeURIComponent(lname);

	//if no username or password entered, error message
	if(uname === "" || pc === "" || lname === "" || fname === "") {
		document.getElementById("message1").innerHTML = "<p>Please complete all fields.</p>";
	} 

	//passed all error checking at the browser level, so start AJAX.  If server sends error messages, they will show in the same html element as above.
	else {
		var xhr = new XMLHttpRequest();
		if (!xhr) {
	        throw 'Unable to create HttpRequest.'; // you're using a horrible browser
	    }

	    xhr.open("POST", "signup.php", true);
	    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(params);
		 
		xhr.onreadystatechange = function ()
		{
		    if (xhr.readyState==4 && xhr.status==200)  
		    {
		        document.getElementById("message1").innerHTML = xhr.responseText;
		    }
		}
	}
}

//verifies username and password entered and sends AJAX call to server to request sign in.
signIn = function () {
	var uname = document.getElementById("uname2").value;
	var pc = document.getElementById("pc2").value;
	var params = "uname=" + encodeURIComponent(uname) + "&pc=" + encodeURIComponent(pc);

	if(uname === "" || pc === "") {
		document.getElementById("message2").innerHTML = "<p>Enter both a username and password please.</p>";
	} else {
		var xhr = new XMLHttpRequest();
		if (!xhr) {
	        throw 'Unable to create HttpRequest.'; // you're using a horrible browser
	    }

	    xhr.open("POST", "loggingin.php", true);
	    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.send(params);
		 
		xhr.onreadystatechange = function ()
		{
		    if (xhr.readyState==4 && xhr.status==200)  {
		    // {
		    //     document.getElementById("message2").innerHTML = xhr.responseText;
		    // }

			    if (xhr.responseText === 'Welcome!')
			    {		    	
			    	window.location.replace("loggedin.php");
			    } else if (xhr.responseText === 'Another user is currently logged in.') {
			    	document.getElementById("message2").innerHTML = "<p>Another user is currently logged in.</p>";
			    	var newbutton = document.createElement('div');
			    	newbutton.innerHTML = '<p><a href="logout.php"><button class="buttonOther" id="centered" type="submit">LOG OUT</button></a></p>';
			    	var logbuttondiv = document.getElementById("log"); 
					logbuttondiv.appendChild(newbutton);
			    } else {
			    	document.getElementById("message2").innerHTML = xhr.responseText;
			    }
			}
		}
	}
}