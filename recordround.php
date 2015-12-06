<?php
  ini_set('display_errors', 'On');
  session_start();

  include 'pw.php';
//new obj for queries
  $mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  } 

  $gameName = $_POST['gameTitle'];
  $player = $_POST['player'];
  $score = $_POST['score'];
  $date = $_POST['date'];

  if ($player == "''") {
    $player = "";
    $score = "";
  }

  $gameID = 0;
  $playerID = 0;
  //if round id is set, grab it, otherwise set it to 0 (middle condition should never be reached)
  if (isset($_SESSION['rid'])) {
    $roundID = $_SESSION['rid'];
  } else if ($player != "" && $player != NULL) {
    echo "Session roundID not set yet";
    $roundID = 0;
  } else {
    $roundID = 0;
  }

  if ($player == "" || $player == NULL) {
      //we need to create a new row in round if it doesn't exist
      $roundID = 0;

      //get id from round table if it's there
      if (!($stmt = $mysqli->prepare('SELECT id FROM round WHERE date_played = (?)'))) {
          echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
      } 
      if (!($stmt->bind_param("s", $date))) {
          echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      } 

      if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      if (!($stmt->bind_result($roundID))) {
          echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      $_SESSION['rid'] = "";
      $_SESSION['rid'] = $roundID;

      //if no matching round has been found, create one
      if($roundID == 0 || $roundID == NULL) {

            //get gid from game table
            $stmt->close();
            if (!($stmt = $mysqli->prepare('SELECT id FROM game WHERE name = (?)'))) {
                echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
            } 
            if (!($stmt->bind_param("s", $gameName))) {
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            } 

            if (!$stmt->execute()) {
              echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            if (!($stmt->bind_result($gameID))) {
                echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            $stmt->fetch();
            //ensure game is fetched properly
            if ($gameID == "" || $gameID == NULL) {
                echo "<p>That game is not in the system.</p><p>Click on \"ADD A NEW GAME\" to add it!</p><br>";
            } else {
                //if game is valid, create new row in round 
                $stmt->close();

                if (!($stmt = $mysqli->prepare('INSERT INTO round (date_played, gid) VALUES (?, ?)'))) {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }

                if (!($stmt->bind_param('si', $date, $gameID))) {
                    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                }

                if (!$stmt->execute()) {
                  echo "<p>Round addition failed.</p>";
                } else {

                  //if round creation was successful, get round id by referencing the date
                  $stmt->close(); 
                  $roundID = 0;

                  if (!($stmt = $mysqli->prepare('SELECT id FROM round WHERE date_played = (?)'))) {
                      echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
                  } 
                  if (!($stmt->bind_param("s", $date))) {
                      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                  } 

                  if (!$stmt->execute()) {
                    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                  }

                  if (!($stmt->bind_result($roundID))) {
                      echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
                  }

                  $stmt->fetch();

                  if ($roundID == 0 || $roundID == NULL) {
                      echo "Fetching round ID failed";
                  } else {
                      $_SESSION['rid'] = "";
                      $_SESSION['rid'] = $roundID;
                  }



                  //increment times_played count for game (one time per round)
                  $stmt->close();

                  if (!($stmt = $mysqli->prepare('UPDATE game SET times_played = times_played + 1 WHERE id = (?)'))) {
                      echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                  }

                  if (!($stmt->bind_param('i', $gameID))) {
                      echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                  }

                  if (!$stmt->execute()) {
                      echo "<p>Game times_played increment failed.</p>";
                  } 

                }
            }
        }

  } else {
    //round was previously created, so insert a round-player row

    //get game id
    if (!($stmt = $mysqli->prepare('SELECT id FROM game WHERE name = (?)'))) {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
    } 
    if (!($stmt->bind_param("s", $gameName))) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
    } 

    if (!$stmt->execute()) {
      echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    if (!($stmt->bind_result($gameID))) {
        echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
    }

    $stmt->fetch();

    //ensure game id was fetched properly
    if ($gameID == 0 || $gameID == NULL) {
        echo "<p>That game is not in the system.</p><p>Click on \"ADD A NEW GAME\" to add it!</p><br>";
    } else {

        //if game id is good, fetch player id
        $stmt->close();

        if (!($stmt = $mysqli->prepare('SELECT id FROM player WHERE uname = (?)'))) {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
        } 
        if (!($stmt->bind_param("s", $player))) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        } 

        if (!$stmt->execute()) {
          echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        if (!($stmt->bind_result($playerID))) {
            echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $stmt->fetch();


        //if playerID and roundID hold values, create round-player row
        if ($playerID != 0 && $playerID != NULL && $roundID != 0 && $roundID != NULL) {

            $stmt->close();

            if (!($stmt = $mysqli->prepare('INSERT INTO round_player (win_status, rid, pid) VALUES (?, ?, ?)'))) {
                echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }

            if (!($stmt->bind_param('sii', $score, $roundID, $playerID))) {
                echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            if (!$stmt->execute()) {
              echo "<p>Round addition failed.</p>";
            } 

            if ($score == "tie" || $score == "win") {
    
                //increment win for anyone who won or tied for win
                $stmt->close();

                if (!($stmt = $mysqli->prepare('UPDATE player SET wins = wins + 1 WHERE id = (?)'))) {
                    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
                }

                if (!($stmt->bind_param('i', $playerID))) {
                    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
                }

                if (!$stmt->execute()) {
                    echo "<p>Win increment failed.</p>";
                } 
            } 
        } 
        echo "<p>Your completed game was recorded successfully.</p>";
    }
  }

  $stmt->close();
  $mysqli->close();
?>