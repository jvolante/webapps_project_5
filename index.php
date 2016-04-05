<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/drop-theme-arrows-bounce-dark.min.css"/>
    <link rel="stylesheet" href="css/index-style.css"/>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="./js/randomColor/randomColor.js"></script>
	<script src="./js/chartjs/Chart.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/drop.min.js"></script>
    <script src="js/sha512.js"></script>
    <?php include 'defaultheader.php'; ?>
    <?php include 'params.php' ?>
    <?php include 'handlelogin.php' ?>
    <title>People's Choice Awards</title>

    <script type="text/javascript">
      $(function (){

        $("#signin").click(function (){ return false; });

        // Populate the list of usernames.
        $.post("ajax/getusers.php",
        function(data){
          data = $.parseJSON(data);
          $.each(data, function(key, value){
            $("#user").append('<option value="' + key + '">' + value + '</option>');
          });

          //Create the sign in flyout
          drop = new Drop({
            target: document.querySelector('#signin'),
            content: document.querySelector('#loginform'),
            position: 'bottom center',
            openOn: 'click',
            classes: 'drop-target drop-theme-arrows-bounce-dark'
          });
        });
      });

	  window.onload = function(){
      populateNames();
      getProjects();
	  }

	  function populateNames() {
		$.post("ajax/getusers.php",
        function(data){
          data = $.parseJSON(data);
		  isFirst = true

          $.each(data, function(key, value){
			if(value != "admin"){
				if(isFirst){
					$("#crossbarLinkArea").append('<a href="http://judah.cedarville.edu/~' + key + '/cs4220.html" class="crossbar">' + value + '</a>');
					isFirst = false;
				}
				else {
					$("#crossbarLinkArea").append('\n&bull;\n');
					$("#crossbarLinkArea").append('<a href="http://judah.cedarville.edu/~' + key + '/cs4220.html" class="crossbar">' + value + '</a>');
				}
			}
          });
        });
	  }

    function verifyPassword() {
      shaobject = new jsSHA("SHA-512", "TEXT");
      shaobject.update($("#password").val());
      passwordhash = shaobject.getHash("HEX");
	  console.log(passwordhash);

      $.post(
        "ajax/verifypassword.php",
        {"username":$("#user").val(), "password":passwordhash},
        function(data){
          if(data == "success"){
            window.location.reload();
          } else {
            $("#message").html("Incorrect Password");
          }
        }
      );
      return false;
    }
    
    function getProjects(){
	  var projects = [];
	  $.post("ajax/getprojectnames.php",
	  function(data){
		getGraphData(data);
	  });
    }
	
	
	var t=setInterval(liveUpdate,5000);
	
	function liveUpdate(){
		if(isAProjectOpen){
			isAProjectOpen = false;
			getProjects();
		}
	}
	
	var isAProjectOpen = false;
	var projectNames;
	var graphsData = [];
	var teamColors = {};
	function getGraphData(data){
		projectNames = $.parseJSON(data);
		
		$.each(projectNames, function(project, isOpen){
			if(isOpen == 1){
				isAProjectOpen = true;
			}
			$.get(
			  "ajax/getpointsforproject.php",
			  {"projectname":project},
			  function(data){
				var scores = [];
				var jsonObj = JSON.parse(data);
				$.each(jsonObj, function(teamid, votedata){
					if(!$.isEmptyObject(teamid)){
						var myColor;
						if(!(teamid in teamColors)){
							myColor = randomColor({luminosity: 'dark'});
							teamColors[teamid] = myColor;
						}
						else{
							myColor = teamColors[teamid];
						}
						var fV = parseInt(votedata['firstVotes']);
						var sV = parseInt(votedata['secondVotes']);
						var tV = parseInt(votedata['thirdVotes']);
						var score = fV * 3 + sV * 2 + tV;
						var names = votedata['names'];
						var nameStr = "";
						var firstName = true;
						$.each(names, function(index, name){
							if(firstName){
								nameStr = nameStr.concat(name);
								firstName = false;
							}
							else {
								nameStr = nameStr.concat(" & " + name);
							}
						});
						
						scores.push({
							value: score,
							color: myColor,
							highlight: ColorLuminance(myColor, .1),
							label: nameStr
						});
						
					}
				});
				updateGraphs(project, scores);
			});
		});
	}
	
	var graphs = {};
	function updateGraphs(project, curGraphData){
		curGraphData.sort(function(a, b){return b.value-a.value});
		if(curGraphData.length >= 1){
			curGraphData[0]['color'] = "#f7c950";
			curGraphData[0]['highlight'] = ColorLuminance("#f7c950", .1);
		}
		if(curGraphData.length >= 2){
			curGraphData[1]['color'] = "#c5c5c5";
			curGraphData[1]['highlight'] = ColorLuminance("#c5c5c5", .1);
		}
		if(curGraphData.length >= 3){
			curGraphData[2]['color'] = "#e8995f";
			curGraphData[2]['highlight'] = ColorLuminance("#e8995f", .1);
		}
		
		var canvasID = project.replace(/\s/g, '') + "Canvas";
		var ctx = $("#" + canvasID).get(0).getContext("2d");
		
		if(curGraphData.length == 0){
			ctx.font = "30px Arial";
			ctx.fillStyle = "#202E4A";
			ctx.fillText("No Votes on this Project",10,50);
		}
		else{
			if(isAProjectOpen){
				if(typeof graphs[project] != 'undefined'){
					graphs[project].destroy();
				}
				graphs[project] = new Chart(ctx).Pie(curGraphData, {responsive : false, maintainAspectRatio: false, animateRotate: false});
			}
			else{
				graphs[project] = new Chart(ctx).Pie(curGraphData, {responsive : false, maintainAspectRatio: false, animateRotate: true});
			}
			
		}
	}
	
	function ColorLuminance(hex, lum) {

		// validate hex string
		hex = String(hex).replace(/[^0-9a-f]/gi, '');
		if (hex.length < 6) {
			hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
		}
		lum = lum || 0;

		// convert to decimal and change luminosity
		var rgb = "#", c, i;
		for (i = 0; i < 3; i++) {
			c = parseInt(hex.substr(i*2,2), 16);
			c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
			rgb += ("00"+c).substr(c.length);
		}

		return rgb;
	}
    
    
	
    </script>
  </head>
  <body>
    <form action="index.php" method="post" id="loginform" onsubmit="return verifyPassword()">
      <!-- Sign in form for flyout, This won't show in the main page -->
      <p id="message"></p>
      Name: <select name="user" id="user" style="color:black"></select><br>
      Password: <input type="password" style="color:black" name = "password" id="password"/><br>
      <input type="submit" style="color:black" value="Log In"/><br>
    </form>

    <!-- /// JUMBOTRON \\\ -->
    <div class="jumbotron cd-intro">
      <div class="container cd-intro-content mask">
        <div class="hr animated fadeIn">
          <img src="1st trophy.png" alt="Trophy logo">
          <hr>
        </div>

        <h1 data-content="People's Choice Awards"><span>People's Choice Awards</span></h1>

        <div class="action-wrapper">
          <p>
            <a href="#projects" class="cd-btn main-action" role="button">View Projects</a>
            <a href="404.html" class="cd-btn" id="signin" target="_blank" role="button" style="color:white;text-decoration:none;"><?php
              // Generate sign in button.
              if(isset($_SESSION[$userParam])){
                $name = $_SESSION[$nameParam];
                echo 'Welcome ' . $name . '! | (Not you?)';
              } else {
                echo 'Sign in';
              }
            ?></a>
          </p>
        </div>
      </div>
    </div>
    <!-- /// END JUMBOTRON \\\ -->
	<section class="crossbar">
		<section class="crossbarLinks" id="crossbarLinkArea">
		</section>
	</section>
    <section id="projects">
		<div id="tabs" class="container">
		<ul  class="nav nav-tabs">
		<?php
			include 'sqlserverparams.php';

			// Create connection
			$conn = new mysqli($serverAddress, $serverUser, $serverPassword);

			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

			$result = $conn->query("SELECT name FROM pca.jk_projects;");

			if (! $result){
				// probably a syntax error in your SQL,
				// but could be some other error
				throw new Db_Query_Exception("DB Error: " . mysql_error());
			}

			$numProjects = mysqli_num_rows($result);
			if ($numProjects == 0){
				echo "No data returned";
			}else{
				// our query returned at least one result. loop over results and do stuff.
				$firstTab = True;
				while($row = mysqli_fetch_assoc($result)){
					$projectID = $row['name'];
					$projectID = str_replace(' ', '', $projectID);
					if($firstTab){
						echo "<li class=\"active\"><a href=\"#"  . $projectID .  "\" data-toggle=\"tab\">" . $row['name'] . "</a></li>";
						$firstTab = false;
					}
					else {
						echo "<li><a href=\"#"  . $projectID .  "\" data-toggle=\"tab\">" . $row['name'] . "</a></li>";
					}
				}
				$name = $_SESSION[$nameParam];
				if($name == "admin"){
					echo "<li><a href=\"admin.php\">Admin</a></li>";
				}
				
				
				echo "</ul>";
				echo "<div class=\"tab-content clearfix\">";
				$result = $conn->query("SELECT name FROM pca.jk_projects;");
				$firstTab = True;
				while($row = mysqli_fetch_assoc($result)){
					$projectName = $row['name'];
					$projectID = $row['name'];
					$projectID = str_replace(' ', '', $projectID);
					if($firstTab){
						echo "<div class=\"tab-pane active\" id=\"" . $projectID . "\"><h1>" . $projectName . "</h1>";
						$firstTab = False;
					}
					else {
						echo "<div class=\"tab-pane\" id=\"" . $projectID . "\"><h1>" . $projectName . "</h1>";
					}
					echo "<canvas id=\"" . $projectID ."Canvas\" height=\"500px\" width=\"500\">";
					echo "</div>";
				}
				echo "</div>";				
			}
		?>
		</div>
    </section>
  </body>
</html>
