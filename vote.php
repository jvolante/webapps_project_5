<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <?php

    $projectParam = 'project';
    $requiredParams = array($projectParam);

    // Check if the GET params exist
    foreach ($requiredParams as $param) {
      if (!isset($_GET[$param])) {
        die('$param was not specified');
      }
    }

    $currentProject = $_GET[$projectParam];
  ?>
  <head>
    <?php include 'defaultheader.php'; ?>
    <meta charset="utf-8">
    <title>
      <?php echo 'Vote on ' . $currentProject . '!'; ?>
    </title>
    <script type="text/javascript">
      linux_to_name = <?php
      	include 'sqlserverparams.php';

      	// Create connection
      	$conn = new mysqli($serverAddress, $serverUser, $serverPassword);
      	// Check connection
      	if ($conn->connect_error) {
      		die("Connection failed: " . $conn->connect_error);
      	}

      	$result = $conn->query("SELECT linux_user, name FROM $dbname.jk_users;");
      	if (! $result){
      		// probably a syntax error in your SQL,
      		// but could be some other error
      		throw new Db_Query_Exception("DB Error: " . mysql_error());
      	}
      	$rows = array();
      	while($row = mysqli_fetch_assoc($result)){
      		$rows[$row['linux_user']] = $row['name'];
      	}

      	echo json_encode($rows);
      ?>;

      $(function(){
        $("#castbutton").click(function(){
          $.post(
            'ajax/castvote.php',
            {
              'project':<?php echo $_GET[$projectParam]; ?>,
              '1stplace':$('#1stplace').val(),
              '2ndplace':$('#2ndplace').val(),
              '3rdplace':$('#3rdplace').val(),
              'writeins':JSON.stringify({'user':$("#writins").val(), 'writein':$("#writein").val()})
            }
          )
          window.location = "index.php";
        });
        $.post(
          'ajax/getteamsforproject.php',
          {'project':<?php echo $_GET[$projectParam]; ?>},
          function(data){
            $.each(
              data,
              function(team_id, members){
                memberslist = "";
                $.each(
                  function(index, user){
                    memberslist +=  linux_to_name[user] + ', ';
                  }
                );
                $(".teams").append('<option val="' + team_id + '">' + memberslist + "</option>");
              }
            );
          }
        );
      });
    </script>
  </head>
  <body>
    <?php
      if(!isset($userParam)){
        die('You need to log in to vote');
      }
    ?>
    <div class="1stplace">
      <select class="teams" name="1stplace" id="1stplace">

      </select>
    </div>

    <div class="2ndplace">
      <select class="teams" name="2ndplace" id="2ndplace">

      </select>
    </div>

    <div class="3rdplace">
      <select class="teams" name="3rdplace" id="3rdplace">

      </select>
    </div>

    <div class="writeins">
      <select class="teams" name="writeins" id="writeins">

      </select>
      <textarea name="writein" id="writein" rows="8" cols="40"></textarea>
    </div>
    <button type="button" name="button" id="castbutton">Cast Vote</button>
  </body>
</html>
