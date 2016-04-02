<?php
  if(isset($_POST["password"]) && isset($_POST["username"])){
    $putitivePassword = $_POST["password"];
    $username = $_POST["username"];

    $conn = new mysqli("james", "CS4220", "");

    $result = $conn->query("SELECT password_hash FROM users WHERE linux_user = $username;");

    if($result->num_rows == 0){
      die("Error, no entry was found for $username");
    } else {
      $row = $result->fetch_assoc();
      if($row["password_hash"] == $putitivePassword){
        echo "success";
      } else {
        die("Incorrect Password.");
      }
    }
  }
?>
