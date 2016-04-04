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
	  }

	  function populateNames() {
		$.post("ajax/getusers.php",
        function(data){
          data = $.parseJSON(data);
		  isFirst = true

          $.each(data, function(key, value){
			if(isFirst){
				$("#crossbarLinkArea").append('<a href="http://judah.cedarville.edu/~' + key + '/cs4220.html" class="crossbar">' + value + '</a>');
				isFirst = false;
			}
			else {
				$("#crossbarLinkArea").append('\n&bull;\n');
				$("#crossbarLinkArea").append('<a href="http://judah.cedarville.edu/~' + key + '/cs4220.html" class="crossbar">' + value + '</a>');
			}
          });
        });
	  }

    function verifyPassword() {
      shaobject = new jsSHA("SHA-512", "TEXT");
      shaobject.update($("#password").val());
      passwordhash = shaobject.getHash("HEX");

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

    </script>
  </head>
  <body>
    <form action="index.php" method="post" id="loginform" onsubmit="return verifyPassword()">
      <!-- Sign in form for flyout, This won't show in the main page -->
      <p id="message"></p>
      Name: <select name="user" id="user" style="color:black"></select><br>
      Password: <input type="password" style="color:black" name = "password" id="password"/><br>
      <input type="submit" style="color:black" value="Log In"/>
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
            <a href="404.html" class="cd-btn" id="signin" target="_blank" role="button"><?php
              // Generate sign in button.
              if(isset($_SESSION[$userParam])){
                $name = $_SESSION[$nameParam];
                echo 'Welcome $name! | (Not you?)';
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
			<li class="active"><a  href="#1b" data-toggle="tab">Overview</a></li>
		<?php
<<<<<<< HEAD
			include 'sqlserverparams.php';
=======
      include 'sqlserverparams.php'

			$dbname = "pca";
>>>>>>> 7355d2b8781add9e9f6dd51d646ffb13bd3e4932

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
			$curProj = 1;
			if ($numProjects == 0){
				echo "No data returned";
			}else{
				// our query returned at least one result. loop over results and do stuff.
				while($row = mysqli_fetch_assoc($result)){
					echo "<li><a href=\"#p" . $curProj . "\" data-toggle=\"tab\">" . $row['name'] . "</a></li>";
					$curProj++;
				}
			}
		?>
		</ul>
			<div class="tab-content clearfix">
				<div class="tab-pane active" id="1b">
					<h3>This is the default tab</h3>

				</div>
				<div class="tab-pane" id="p1">
					<h3>This is project 1</h3>
				</div>
				<div class="tab-pane" id="p2">
					<h3>This is project 2</h3>
				</div>
			</div>
		</div>
    </section>
  </body>
</html>
