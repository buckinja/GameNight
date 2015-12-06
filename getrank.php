<?php
  ini_set('display_errors', 'On');
  session_start();
  include 'pw.php';

  //new mysqli obj for queries
  $mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  }

//grab user id from session storage
  if (!(isset($_SESSION['id']))) {
    echo "error with user id in session";
  } else {
    $playerID = $_SESSION['id'];
  }

  $fname = "";
  $lname = "";
  $wins = 0;
  $id = 0;

//query gets all players in the system and lists them by number of wins
  if (!($stmt = $mysqli->prepare('SELECT fname, lname, wins, id FROM player ORDER BY wins DESC'))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
  } 

  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  if (!($stmt->bind_result($fname, $lname, $wins, $id))) {
      echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

//results stored in array, eventually JSON encoded
  $rows = array();
  while($stmt->fetch()) {
      $rows[] = array('fname' => $fname, 'lname' => $lname, 'wins' => $wins, 'id' => $id);
  }
  //user id is also needed, so it's passed back in same array obj
  $rows['user'] = $playerID;

  echo json_encode($rows);

  $stmt->close();
  $mysqli->close();
?>