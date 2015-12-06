<?php
  ini_set('display_errors', 'On');
  session_start();

  $uname = $_POST['uname'];
//remove player with that username from array
  foreach($_SESSION['players'] as $k => $v) {
      if($v == $uname) {
        echo $uname . " was removed from the roster.";
        unset($_SESSION['players'][$k]);
      }
  }
?>