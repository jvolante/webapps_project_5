<?php
include 'sqlserverparams.php';

  $conn = new mysqli($serverAddress, $serverUser, $serverPassword);

  if($conn->connect_error){
    die("Database Connection Failed");
  }

  $result = $conn->query("SELECT name, isopen FROM jk_projects ORDER BY name;");

  $projectNames = array();
  while($row = mysqli_fetch_assoc($result)){
    $projectNames[$row["name"]] = $row["isopen"];
  }
  echo json_encode($projectNames);
?>
