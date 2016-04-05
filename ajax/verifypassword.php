<?php
  session_start();

  include '../sqlserverparams.php';
  include '../params.php';

  if(isset($_POST["password"]) && isset($_POST["username"])){
    $putitivePassword = $_POST["password"];
    $username = $_POST["username"];

    include '/../sqlserverparams.php';
    $conn = new mysqli($serverAddress, $serverUser, $serverPassword);

    if($conn->connect_error){
      die("Database Connection Failed");
    }

    $result = $conn->query("SELECT password_hash, name FROM $dbname.jk_users WHERE linux_user = '$username';");

    if($result->num_rows == 0){
      die("Error, no entry was found for $username");
    } else {
      $row = $result->fetch_assoc();
      if($row["password_hash"] == $putitivePassword){
        $_SESSION[$userParam] = $username;
        $_SESSION[$nameParam] = $row["name"];
        echo "success";
      } else {
        die("Incorrect Password.");
      }
    }
  } else {
    die("Missing parameters");
  }
?>
