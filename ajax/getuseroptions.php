<?php
  // Can be called via an AJAX request to get the site users as key value pairs

  // TODO: Actually implement this
  $users = array("Boblinux"=>"Bob Smith", "Jonlinux"=>"John Doe");
  echo json_encode($users)
?>
