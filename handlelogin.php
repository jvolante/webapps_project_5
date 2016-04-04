<?php
  // Include this in the main page to handle when the user logs in.
  if(isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    include 'sqlserverparams.php';
    $conn = new mysqli($serverAddress, $serverUser, $serverPassword);

    if($conn->connect_error){
      die("Database Connection Failed");
    }

    $result = $conn->query("SELECT password_hash, name FROM jk_users WHERE linux_user = '$username';");

    if($result->num_rows == 0){
      die("Error, no entry was found for $username");
    } else {
      $row = $result->fetch_assoc();
      if($row["password_hash"] == $password){
        $_SESSION[$userParam] = $username;
        $_SESSION[$nameParam] = $row["name"];
      } else {
        die("Incorrect Password.");
      }
    }
  }
?>
