<?php
session_start();
include '../params.php'
include '../sqlserverparams';

$conn = new mysqli($serverAddress, $serverUser, $serverPassword);

if($conn->connect_error){
  die("Database Connection Failed");
}

$user = $_SESSION[$userParam];
$project = $_POST["project"];
$one = $_POST["1stplace"];
$two = $_POST["2ndplace"];
$three = $_POST["3rdplace"];

$result = $conn->query("INSERT INTO $dbname.jk_votes VALUES ('$user', '$project', '$one', '$two', '$three')");
?>
