<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <?php include 'defaultheader.php' ?>
    <?php include 'params.php' ?>
    <?php include 'handlelogin.php' ?>
    <title>Site Setup</title>
    <script type="text/javascript">
      function updateUsersLists() {
        $.getJSON(
          'ajax/getuseroptions.php',
          function(data){
            $.each(
              data,
              function(key,value){
                $("currentusers").append("<tr><td>" + value + "</td><td>" + key + "</td></tr>");
                $("userslistteams").append("<li>" + value + " : " + key + "</li>");
              });
          });
      }
      $(function(){
        updateUsersLists();
      });
    </script>
    <style media="screen">
      th{
        text-align: center;
      }
      .team, #addteam{

      }
    </style>
  </head>
  <body>
  <ul class="nav nav-tabs">
    <li><a href="#users">Users</a></li>
    <li><a href="#teams">Teams</a></li>
    <li><a href="#voting">Voting</a></li>
    <li><a href="#setup">Setup</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane fade" id="users">
      <table id="currentusers">
        <tr>
          <th colspan="2">Users</th>
        </tr>
        <tr>
          <th>Name</th>
          <th>Linux Username</th>
        </tr>
      </table>
    </div>
    <div class="tab-pane fade" id="teams">
      <ul id="userslistteams"></ul>

    </div>
    <div class="tab-pane fade" id="voting">

    </div>
    <div class="tab-pane fade" id="setup">
      <h1>Setup</h1>
      <h2>WARNING, CHANGING THESE SETTINGS WILL DELETE ALL CURRENT USER DATA</h2>
      Number of projects: <input type="number" min="1" name="num_projects" value="1"><br>
      <button type="button" name="resetbutton" id="resetbutton">Reset Data</button>
    </div>
  </div>
  </body>
</html>
