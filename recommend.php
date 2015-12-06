<?php
  ini_set('display_errors', 'On');
  session_start();
  include 'pw.php';

  //new mysqli obj for queries
  $mysqli = new mysqli('oniddb.cws.oregonstate.edu', 'buckinja-db', $pw, 'buckinja-db');
  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  }

  //holds type of query from select
  $option = $_POST["selectoption"];
  //if user entered manual data, it's here
  $data = $_POST["data"];

  //get user's id from session storage
  if (!(isset($_SESSION['id']))) {
    echo "error with user id in session";
  } else {
    $playerID = $_SESSION['id'];
  }

  $gameName = "";
  $rating = 0.0;
  $genre = "";
  $theme = "";
  $number = 0;

  //this query gets the average (rounded to 2 decimal points) rating of every game in the database 
  //and lists them (and related data) from greatest average rating to least.  Includes games without
  //any ratings.
  if($option == "rating") {
      if (!($stmt = $mysqli->prepare('SELECT name, genre, theme, ROUND(AVG(rating), 2) as r
          FROM game
          LEFT JOIN game_rating ON game.id = game_rating.gid
          GROUP BY game_rating.gid
          ORDER BY r DESC'))) 
      {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
      } 

      if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      if (!($stmt->bind_result($gameName, $genre, $theme, $rating))) {
          echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      //data is stored in associative array, eventually JSON encoded before sending
      $rows = array();
      while($stmt->fetch()) {
          $rows[] = array('game' => $gameName, 'genre' => $genre, 'theme' => $theme, 'rating' => $rating);
      }

      //if no results, send back error array
      if (empty($rows)) {
          $rows = array('error' => "no data");
      }
  } else if ($option == "genre") {
      //this query option selects all games within a user-specified genre and lists them 
      //by average rating (descending), and includes games without ratings. Only sends back
      //the top 10.
      if (!($stmt = $mysqli->prepare('SELECT name, genre, theme, ROUND(AVG(rating), 2) as r
          FROM game
          LEFT JOIN game_rating ON game.id = game_rating.gid
          WHERE genre=(?)
          GROUP BY game_rating.gid
          ORDER BY r DESC
          LIMIT 10'))) 
      {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
      } 

      if (!($stmt->bind_param('s', $data))) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      if (!($stmt->bind_result($gameName, $genre, $theme, $rating))) {
          echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      //data is stored in associative array, eventually JSON encoded before sending
      $rows = array();
      while($stmt->fetch()) {
          $rows[] = array('game' => $gameName, 'genre' => $genre, 'theme' => $theme, 'rating' => $rating);
      }

      //if no results, send back error array
      if (empty($rows)) {
          $rows = array('error' => "no data");
      }
  } else if ($option == "theme") {
      //this query option selects all games within a user-specified theme and lists them 
      //by average rating (descending), and includes games without ratings.  Only sends back
      //the top 10.
      if (!($stmt = $mysqli->prepare('SELECT name, genre, theme, ROUND(AVG(rating), 2) as r
          FROM game
          LEFT JOIN game_rating ON game.id = game_rating.gid
          WHERE theme=(?)
          GROUP BY game_rating.gid
          ORDER BY r DESC
          LIMIT 10'))) 
      {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
      } 

      if (!($stmt->bind_param('s', $data))) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      if (!($stmt->bind_result($gameName, $genre, $theme, $rating))) {
          echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      //data is stored in associative array, eventually JSON encoded before sending
      $rows = array();
      while($stmt->fetch()) {
          $rows[] = array('game' => $gameName, 'genre' => $genre, 'theme' => $theme, 'rating' => $rating);
      }

      //if no results, send back error array
      if (empty($rows)) {
          $rows = array('error' => "no data");
      }

  } else if ($option == "popularity") {
      //this query option selects the top 10 games based on number of times played (or fewer 
      //if less than 10 games in the database have ever been entered in a round)
      if (!($stmt = $mysqli->prepare('SELECT name, genre, theme, times_played 
          FROM game
          WHERE times_played>0
          ORDER BY times_played DESC
          LIMIT 10'))) 
      {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
      } 

      if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      if (!($stmt->bind_result($gameName, $genre, $theme, $number))) {
          echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      //data is stored in associative array, eventually JSON encoded before sending
      $rows = array();
      while($stmt->fetch()) {
          $rows[] = array('game' => $gameName, 'genre' => $genre, 'theme' => $theme, 'rating' => $number);
      }

      //if no results, send back error array
      if (empty($rows)) {
          $rows = array('error' => "no data");
      }
  } else if ($option == "yourrating") {
      //this query option selects the 10 games the user has rated highest, or fewer if they haven't rated
      //10 games. If they rated 0, they get an error message.
      if (!($stmt = $mysqli->prepare('SELECT name, genre, theme, rating
          FROM game
          INNER JOIN game_rating ON game.id = game_rating.gid
          WHERE game_rating.pid=(?)
          ORDER BY rating DESC
          LIMIT 10'))) 
      {
        echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
      } 

      if (!($stmt->bind_param('i', $playerID))) {
        echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      if (!($stmt->bind_result($gameName, $genre, $theme, $rating))) {
          echo "Binding result failed: (" . $stmt->errno . ") " . $stmt->error;
      }

      //data is stored in associative array, eventually JSON encoded before sending
      $rows = array();
      while($stmt->fetch()) {
          $rows[] = array('game' => $gameName, 'genre' => $genre, 'theme' => $theme, 'rating' => $rating);
      }

      //if no results, send back error array
      if (empty($rows)) {
          $rows = array('error' => "no data");
      }
  } 

  //JSON encode before sending back

  echo json_encode($rows);

  $stmt->close();
  $mysqli->close();
?>