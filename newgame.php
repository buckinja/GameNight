<?php
  ini_set('display_errors', 'On');

  include 'pw.php';

  $mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  } 

  $gameName = $_POST['gameName'];
  $genre = $_POST['genre'];
  $theme = $_POST['theme'];

  $genre1 = "";

  if (!($stmt = $mysqli->prepare('SELECT genre, theme FROM game WHERE game.name = (?)'))) {
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

  if ($genre1 !== "") {
      echo "<p>That game is already in the system.</p><p>Look in category " . $genre1 . " or in theme " . $theme1. ".</p><br>";
  } else {

      $mysqli2 = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
      if ($mysqli2->connect_errno) {
          echo "Failed to connect to MySQL: " . $mysqli2->connect_error;
      } 

      if (!($stmt2 = $mysqli2->prepare('INSERT INTO game (name, genre, theme) VALUES (?, ?, ?)'))) {
         echo "Prepare failed: (" . $mysqli2->errno . ") " . $mysqli->error;
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