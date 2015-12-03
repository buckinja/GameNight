<?php
  ini_set('display_errors', 'On');
  session_start();
  include 'pw.php';

  $mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  }

  $gameTitle = $_POST["gameTitle"];

  $rating = $_POST['rating'];

  if (!(isset($_SESSION['id']))) {
    echo "error with user id in session";
  } else {
    $playerID = $_SESSION['id'];
  }

  $gameID = 0;
  $GID = 0;
  $RATE = 0;


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

  if ($gameID == 0 || $gameID == NULL) {
      echo "<p>That game is not in the system.</p><p>Click on \"ADD A NEW GAME\" to add it!</p><br>";
  } else {

      $stmt->close();

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

      if ($GID != 0 && $GID != NULL) {
          echo "<p>You have already rated that game.</p><p>You gave it " . $RATE. " stars out of 5.</p><br>";
      } else {

          $stmt->close(); 

          if (!($stmt = $mysqli->prepare('INSERT INTO game_rating (gid, pid, rating) VALUES (?, ?, ?)'))) {
             echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
          }

          if (!($stmt->bind_param('iid', $gameID, $playerID, $rating))) {
              echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
          }

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