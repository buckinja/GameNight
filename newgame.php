<?php
  ini_set('display_errors', 'On');

  include 'pw.php';
  //new obj for queries
  $mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  } 

  //get post data
  $gameName = $_POST['gameName'];
  $genre = $_POST['genre'];
  $theme = $_POST['theme'];

  $genre1 = "";

  //get the game's genre and theme -- if it's in the database, tell the user what genre and theme it's under
  if (!($stmt = $mysqli->prepare('SELECT genre, theme FROM game WHERE name = (?)'))) {
      echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
  } 
  if (!($stmt->bind_param("s", $gameName))) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  } 

  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!($stmt->bind_result($genre1, $theme1))) {
      echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $stmt->fetch();

  if ($genre1 != "" && $genre1 != NULL) {
    //message if game is in database
      echo "<p>That game is already in the system.</p><p>Look in category " . $genre1 . " or in theme " . $theme1. ".</p><br>";
  } else {
      //another object to run simultaneously
      $mysqli2 = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
      if ($mysqli2->connect_errno) {
          echo "Failed to connect to MySQL: " . $mysqli2->connect_error;
      } 
      //create new game row since it doesn't exist
      if (!($stmt2 = $mysqli2->prepare('INSERT INTO game (name, genre, theme) VALUES (?, ?, ?)'))) {
         echo "Prepare failed: (" . $mysqli2->errno . ") " . $mysqli2->error;
      }

      if (!($stmt2->bind_param('sss', $gameName, $genre, $theme))) {
          echo "Binding parameters failed: (" . $stmt2->errno . ") " . $stmt2->error;
      }

      if (!$stmt2->execute()) {
        echo "<p>Game addition failed.</p>";
      } else {
        echo '<p>Game added successfully.</p>';
      }
      $stmt2->close();
      $mysqli2->close();
  }
  $stmt->close();
  $mysqli->close();
?>