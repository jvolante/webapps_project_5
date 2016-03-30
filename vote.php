<!DOCTYPE html>
<html>
  <?php

    $projectParam = 'project';
    $requiredParams = array($projectParam);

    // Check if the GET params exist
    foreach ($requiredParams as $param) {
      if (!isset($param, $_GET)) {
        die('$param was not specified');
      }
    }

    $currentProject = $_GET[$projectParam];
  ?>
  <head>
    <?php include 'defaultheader.php'; ?>
    <meta charset="utf-8">
    <title>
      <?php echo 'Vote on $currentProject!'; ?>
    </title>
  </head>
  <body>
    <?php
      if(!isset($userParam, $_GET)){
        die('You need to log in to vote');
      }
    ?>
    <!-- TODO: interface for voting -->
  </body>
</html>
