<?php
  // Include this in the main page to handle when the user logs in.
  if(isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $conn = new mysqli("james", "CS4220", "");

    if($conn->connect_error){
      die("Connection Failed");
    }

    $result = $conn->query("SELECT password_hash FROM users WHERE linux_user = $username;");

    if($result->num_rows == 0){
      die("Error, no entry was found for $username");
    } else {
      $row = $result->fetch_assoc();
      if($row["password_hash"] == $password){
        $_SESSION[$userParam] = $username;
      } else {
        die("Incorrect Password.");
      }
    }
  }
?>
