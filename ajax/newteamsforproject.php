<?php
if (isset($_POST["teams"]) && isset($_POST["project"])) {
  $currentProject = $_POST["project"];

  $conn = new mysqli($serverAddress, $serverUser, $serverPassword);

  if($conn->connect_error){
    die("Database Connection Failed");
  }

  $conn->query("DELETE FROM jk_team WHERE project='$currentProject'") or die("SQL error on delete");

  $teams = json_decode($_POST["teams"]) or die("JSON syntax error");

  foreach ($teams as $teamid => $members) {
    foreach ($members as $key => $name) {
      $conn->query("INSERT INTO jk_team VALUES ($teamid, '$currentProject', '$name');") or die("SQL Error on insert");
    }
  }
  echo 'success';
} else {
  die("Incorrect parameters");
}
?>
