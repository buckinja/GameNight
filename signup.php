<?php
  ini_set('display_errors', 'On');

  include 'pw.php';

  //redirects to login page if user is unauthorized 
  if(empty($_POST)) {
    header("Location: gamenight.php", true);
  }

  $mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  } 

  //creates a simple salt
  function makeRandStr($length = 10) {
      $ch = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      $chLength = strlen($ch);
      $random = '';
      for ($i = 0; $i < $length; $i++) {
          $random .= $ch[rand(0, $chLength - 1)];
      }
      return $random;
  }

  //gets post data
  $lname = $_POST['lname'];
  $fname = $_POST['fname'];
  $uname = $_POST['uname'];
  $passc = $_POST['pc'];
  //creates random string for a salt, hashes password with salt
  $newSalt = makeRandStr();
  $pc = hash('sha256', $newSalt . $passc);

  //checks if username is already in database and errors if it is
  if (!($stmt = $mysqli->prepare('SELECT uname FROM player WHERE uname = (?)'))) {
      echo "<p>You did not enter a valid username or password.</p>";
  } 
  if (!($stmt->bind_param("s", $uname))) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  } 

  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if ($stmt->num_rows !== 0) {
      echo "That user name is already taken.";
  } else {

      $stmt->close();
      //if username is free, create a new row in player
      if (!($stmt = $mysqli->prepare('INSERT INTO player(fname, lname, uname, pc, slt) VALUES (?, ?, ?, ?, ?)'))) {
         echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
      }

      if (!($stmt->bind_param('sssss', $fname, $lname, $uname, $pc, $newSalt))) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      if (!$stmt->execute()) {
        echo "<p>Account creation failed.</p>";
      } else {
        echo '<p>Account created successfully.</p>';
      }

  }
  $stmt->close();
  $mysqli->close();
?>