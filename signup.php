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

  $lname = $_POST['lname'];
  $fname = $_POST['fname'];
  $uname = $_POST['uname'];
  $passc = $_POST['pc'];
  $newSalt = makeRandStr();
  $pc = hash('sha256', $newSalt . $passc);


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

      $mysqli2 = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
      if ($mysqli2->connect_errno) {
          echo "Failed to connect to MySQL: " . $mysqli2->connect_error;
      } 

      if (!($stmt2 = $mysqli2->prepare('INSERT INTO player(fname, lname, uname, pc, slt) VALUES (?, ?, ?, ?, ?)'))) {
         echo "Prepare failed: (" . $mysqli2->errno . ") " . $mysqli->error;
      }

      if (!($stmt2->bind_param('sssss', $fname, $lname, $uname, $pc, $newSalt))) {
          echo "Binding parameters failed: (" . $stmt2->errno . ") " . $stmt2->error;
      }

      if (!$stmt2->execute()) {
        echo "<p>Account creation failed.</p>";
      } else {
        echo '<p>Account created successfully.</p>';
      }
      $stmt2->close();
      $mysqli2->close();
  }
  $stmt->close();
  $mysqli->close();
?>