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

  if ($uname === null || $pc === null) {
    echo "<p>Error: Entry must contain a name and password.</p>";
  } else if ( hash('sha256', $slt . $pc) !== $pcode) {
      //if hashed pw didn't match, error message
      echo "<p>Invalid username or password.</p>";
  } else {
    if (session_status() == PHP_SESSION_ACTIVE) {
      //if new session, set username, id, players array (roster)
      if (!isset($_SESSION['uname']) || $_SESSION['uname'] === $uname) { 
        $_SESSION['uname'] = $uname;
        $_SESSION['id'] = $id;
        $_SESSION['players'] = array();
        $_SESSION['players'][] = $uname;
        echo 'Welcome!';
      } else {
        echo "Another user is currently logged in.";
      }
    }
  }
  $stmt->close();
  $mysqli->close();
?>