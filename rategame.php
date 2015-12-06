<?php
  ini_set('display_errors', 'On');
  session_start();
  include 'pw.php';
//new mysqli obj for queries
  $mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  }

//get data from post
  $gameTitle = $_POST["gameTitle"];
  $rating = $_POST['rating'];

//get user id from session storage
  if (!(isset($_SESSION['id']))) {
    echo "error with user id in session";
  } else {
    $playerID = $_SESSION['id'];
  }

  $gameID = 0;
  $GID = 0;
  $RATE = 0;

//get game's id from database
  if (!($stmt = $mysqli->prepare('SELECT id FROM game WHERE name = (?)'))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
  } 
  if (!($stmt->bind_param("s", $gameTitle))) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  } 

  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!($stmt->bind_result($gameID))) {
      echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $stmt->fetch();
//if there was no game of that name, error message
  if ($gameID == 0 || $gameID == NULL) {
      echo "<p>That game is not in the system.</p><p>Click on \"ADD A NEW GAME\" to add it!</p><br>";
  } else {

      $stmt->close();
      //find user's rating of this game with user id and game id
      if (!($stmt = $mysqli->prepare('SELECT gid, rating FROM game_rating WHERE pid = (?) AND gid = (?)'))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
      } 
      if (!($stmt->bind_param("ii", $playerID, $gameID))) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      } 

      if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      if (!($stmt->bind_result($GID, $RATE))) {
          echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      $stmt->fetch();
      //if game was already rated, update the row with new rating
      if ($GID != 0 && $GID != NULL) {
          echo "<p>You previously rated this game " . $RATE. " stars out of 5.</p><br>";

          $stmt->close(); 

          if (!($stmt = $mysqli->prepare('UPDATE game_rating SET rating = (?) WHERE gid = (?) AND pid = (?)'))) {
             echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }

          if (!($stmt->bind_param('dii', $rating, $gameID, $playerID))) {
              echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          //message if success or fail
          if (!$stmt->execute()) {
            echo "<p>Rating update failed.</p>";
          } else {
            echo '<p>Rating updated successfully.</p>';
          }
      } else {

          $stmt->close(); 
          //if game not previously rated, create new row in game_rating which associates a player with a game
          if (!($stmt = $mysqli->prepare('INSERT INTO game_rating (gid, pid, rating) VALUES (?, ?, ?)'))) {
             echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }

          if (!($stmt->bind_param('iid', $gameID, $playerID, $rating))) {
              echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }
          //message on success or fail
          if (!$stmt->execute()) {
            echo "<p>Rating failed.</p>";
          } else {
            echo '<p>Rating added successfully.</p>';
          }
      }
  }
  $stmt->close();
  $mysqli->close();
?>