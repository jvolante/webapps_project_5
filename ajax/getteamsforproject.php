<?php
if(isset($_GET["projectname"])){
  include 'sqlserverparams.php';

  $projectName = $_GET["projectname"];

  $conn = new mysqli($serverAddress, $serverUser, $serverPassword);

  if($conn->connect_error){
    die("Database Connection Failed");
  }

  $result = $conn->query("SELECT team_id, user FROM jk_teams WHERE project = $projectName ORDER BY team_id;");

  $teams = array();
  while($row = mysqli_fetch_assoc($result)){
    if(!isset($teams[$row["team_id"]])){
      $teams[$row["team_id"]] = array();
    }
    array_push($teams[$row["team_id"]], $row["user"]);
  }
  echo json_encode($teams);
}
?>
