<?php
  ini_set('display_errors', 'On');
  include 'pw.php';
  session_start();

  //get post data
  $uname = $_POST['uname'];
  $pc = $_POST['pc'];
  //new obj for queries
  $mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  } 

  //get pw from table
  if (!($stmt = $mysqli->prepare('SELECT id, pc, slt FROM player WHERE uname = (?)'))) {
      echo "<p>You did not enter a valid username or password.</p>";
  } 
  if (!($stmt->bind_param("s", $uname))) {
      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
  } 

  if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $pcode = 0;
  $slt = "";

  if (!($stmt->bind_result($id, $pcode, $slt))) {
      echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  $stmt->fetch();

  if ($uname === '') {
    $uname = null;
  }

  if ($pc === '') {
    $pc = null;
  }
//error checking and pw hashing....
  if ($uname === null || $pc === null) {
    echo "<p>Error: Entry must contain a name and password.</p>";
  } else if ( hash('sha256', $slt . $pc) !== $pcode) {
      echo "<p>Invalid username or password.</p>";
  } else {
    //if passes, put username in local storage to signal signin is active
    $_SESSION['players'][] = $uname;
    echo "Player added successfully.";
  }

  $stmt->close();
  $mysqli->close();
?>