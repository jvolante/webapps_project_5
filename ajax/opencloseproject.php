<?php
if(isset($_POST['project']) && isset($_POST['open'])){
  $project = $_POST['project'];
  $opening = $_POST['open'];

  $conn = new mysqli($serverAddress, $serverUser, $serverPassword);

  if($conn->connect_error){
    die("Database Connection Failed");
  }

  $conn->query("UPDATE jk_projects SET isopen='$opening' WHERE name='$project'") or die("SQL error");
  echo('success');
} else {
  die('Incorrect parameters')
}
?>
