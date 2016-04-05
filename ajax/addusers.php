<?php
include '../sqlserverparams.php';
$conn = new mysqli($serverAddress, $serverUser, $serverPassword);

if($conn->connect_error){
  die("Database Connection Failed");
}

// SHA-512 of "password"
$defaultPassword = "b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86";

foreach ($_POST as $linuxName => $name) {
  $conn->query("INSERT INTO $dbname.jk_users VALUES ('$linuxName', '$name', '$defaultPassword');") or die("SQL error");
}

echo "success";
?>
