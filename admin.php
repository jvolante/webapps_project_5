<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <?php include 'defaultheader.php' ?>
    <?php include 'handlelogin.php' ?>
    <?php include 'params.php' ?>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index-style.css"/>
    <?php
    // Make sure user is admin
    if(!isset($_SESSION[$userParam]) || $_SESSION[$userParam] != 'admin'){
      die("You must be admin to view this page!");
    }
    // Code to handle data reset
    if(isset($_POST["numprojects"])){
      include 'sqlserverparams.php';
      $conn = new mysqli($serverAddress, $serverUser, $serverPassword);

      if($conn->connect_error){
        die("Database Connection Failed");
      }

      $conn->query("DELETE FROM pca.jk_writins;");
      $conn->query("DELETE FROM pca.jk_votes;");
      $conn->query("DELETE FROM pca.jk_team;");
      $conn->query("DELETE FROM pca.jk_projects;");
      $conn->query("DELETE FROM pca.jk_users WHERE name <> 'admin';");

      for ($i=1; $i <= intval($_POST["numprojects"]); $i++) {
        $conn->query("INSERT INTO jk_projects VALUES ('Project $i', FALSE);");
      }
    }
    ?>
    <title>Site Setup</title>
    <script type="text/javascript">
      $.fn.exists = function () {
        return this.length !== 0;
      }

      function updateUsersLists() {
        $("#currentusers").append("");
        $("#userslistteams").append("");
        $.getJSON(
          'ajax/getusers.php',
          function(data){
            $.each(
              data,
              function(key,value){
                $("#currentusers").append("<tr><td>" + value + "</td><td>" + key + "</td></tr>");
                $("#userslistteams").append("<li>" + value + " : " + key + "</li>");
              });
          });
      }

      function popluateTeamBoxes(projectname) {
        $(".teams").html("");
        $.get(
          'ajax/getteamsforproject.php',
          {'projectname':projectname},
          function (data) {
            if(data == "none"){
              currentteam = 1;
              $.getJSON(
                'ajax/getusers.php',
                function(data){
                  $.each(data, function(linuxuser, name){
                    $(".teams").append('<li><ul class="team" id="team' + currentteam + '"><li>' + linuxuser + '</li></ul></li>');
                  });
                }
              );
            } else {
              data = $.parseJSON(data);
              $.each(
                data,
                function(team_id, members){
                  teamlist = "";
                  $.each(
                    members,
                    function(index, name){
                      teamlist += "<li>" + name + "</li>";
                    }
                  );
                  $(".teams").append('<li><ul class="team" id="team' + team_id + '">' + teamlist + '</ul></li>');
                }
              );
            }

            // Make the drag and drop crap
            $(".team").each(function(index){
              sortable = new Sortable(this, {
                group: 'teams',
                sort: 'true'
              });
            });
          }
        );
      }

      startingproject = 0;

      $(function(){
        $('#tabs a:last').tab('show');
        updateUsersLists();
        $.get(
          'ajax/getprojectnames.php',
          function(data){
            data = $.parseJSON(data);
            $.each(
              data,
              function(key, value){
                if(value){
                  projectbutton = '<div class="votebutton btn" id="' + key.replace(/\s+/g, '') + 'button">Open</div>';
                } else {
                  projectbutton = '<div class="openproject votebutton btn" id="' + key.replace(/\s+/g, '') + 'button">Close</div>';
                }
                $("#selectproject").append('<option value="' + key + '">' + key + "</option>");
                if(startingproject == 0){
                  startingproject = key;
                }
                $("#projectvotinglist").append("<li>" + key + " " + projectbutton + "</li>");
              }
            );

            $(".votebutton").click(function(event){
              obj = $(this);
              projectname = obj.parent().html().match(/([\w \d]+?) </)[1];
              obj.html("Working...");

              // The object will have the openproject class if the project is already open
              opening = !obj.hasClass("openproject");

              $.post(
                'ajax/opencloseproject.php',
                {'project':projectname, 'open':opening},
                function(data){
                  if(data == "success"){
                    $("#projectvotingmessage").html("");
                    if(opening){
                      obj.addClass("openproject");
                      obj.html("Close");
                    } else {
                      obj.removeClass("openproject");
                      obj.html("Open");
                    }
                  } else {
                    if(opening){
                      obj.html("Open");
                    } else {
                      obj.html("Close");
                    }
                    $("#projectvotingmessage").html("Failed to open project!");
                  }
                }
              );
            });
          }
        );
        $(".resetform").submit(function(){
          // Make sure the value is a number before it is pushed
          if(/^\d+$/.test($("#num_projects").val())){
            return true;
          } else {
            $("#resetformmessage").html("Number of Projects must be a number!");
            return false;
          }
        });

        popluateTeamBoxes(startingproject);

        $("#addusersbutton").click(function(){
          if($("#addusersbutton").html() == "Add Users"){
            $("#addusersmessage").html("");
            re = /(.{1,20})\s*:\s*([\w\d]{1,8})/gm;
            s = $("#newusers").val();

            userlist = {};
            while(m = re.exec(s)){
              userlist[m[2]] = m[1];
            }

            $("#addusersbutton").html("Sending...");
            $.post(
              'ajax/addusers.php',
              userlist,
              function(data){
                if(data == "success"){
                  $("#newusers").val("");
                  updateUsersLists();
                } else {
                  $("#addusersmessage").html("Failed to add new users!");
                }
                $("#addusersbutton").html("Add Users");
              }
            );
          }
        });

        $("#setteamsbutton").click(function(){
          data = {};
          $(".team").each(function(index){
            team_id = parseInt($(this).id().match(/team(\d+)/)[1]);
            members = [];
            $(this).children('li').each(function(i){
              members.push($(this).html());
            });
            data[team_id] = members;
          });

          $("#setteamsbutton").html('Working...');

          $.post(
            'ajax/newteamsforporject.php',
            {'teams':JSON.stringify(data)},
            function(data){
              $("#setteamsbutton").html('Set Teams');
              if(data == 'success'){
                $("#addteamsmessage").html("");
              } else {
                $("#addteamsmessage").html("Failed to update teams");
              }
            }
          )
        });
      });
    </script>
    <style media="screen">
      th{
        text-align: center;
      }

      .btn {
        -webkit-border-radius: 10;
        -moz-border-radius: 10;
        border-radius: 10px;
        color: #ffffff;
        font-size: 20px;
        background: #202e4a;
        padding: 10px 20px 10px 20px;
        text-decoration: none;
      }

      .btn:hover {
        background: #2e426b;
        text-decoration: none;
      }
    </style>
  </head>
  <body>
  <ul class="nav nav-tabs" id="tabs">
    <li class="active"><a data-toggle="tab" data-target="#users">Users</a></li>
    <li><a data-toggle="tab" data-target="#teams">Teams</a></li>
    <li><a data-toggle="tab" data-target="#voting">Voting</a></li>
    <li><a data-toggle="tab" data-target="#setup">Setup</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane fade" id="users">
      <table id="currentusers">
        <tr>
          <th>Name</th>
          <th>Linux Username</th>
        </tr>
      </table>
      <div class="addusers">
        <h2>Add Users</h2>
        Add users using format <i>name : linuxuser</i><br>
        <textarea rows="8" cols="40" id="newusers"></textarea>
        <div id="addusersmessage">

        </div>
        <div class="btn" id="addusersbutton">Add Users</div>
      </div>
    </div>
    <div class="tab-pane fade" id="teams">
      <div id="addteamsmessage">

      </div>
      <select id="selectproject" name="">
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
  				while($row = mysqli_fetch_assoc($result)){
  					echo "<li><a href=\"#p" . $curProj . "\" data-toggle=\"tab\">" . $row['name'] . "</a></li>";
  					$curProj++;
  				}
  			}
        ?>
      </select>
      <ul class="teams">
      </ul>
      <div class="btn" id="setteamsbutton">Set Teams</div>
    </div>
    <div class="tab-pane fade" id="voting">
      <div id="projectvotingmessage">

      </div>
      <ul id="projectvotinglist"></ul>
    </div>
    <div class="tab-pane fade" id="setup">
      <h1>Setup</h1>
      <h2>WARNING, CHANGING THESE SETTINGS WILL DELETE ALL CURRENT USER DATA</h2>
      <form class="resetform" action="admin.php" method="post">
        <p id="resetformmessage">

        </p>
        Number of projects: <input type="number" min="1" name="num_projects" value="7" id="num_projects"><br>
        <input type="submit" name="name" value="Reset Data">
      </form>
    </div>
  </div>
  </body>
</html>
