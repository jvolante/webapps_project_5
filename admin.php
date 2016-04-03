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
          <th>Name</th>
          <th>Linux Username</th>
        </tr>
      </table>
    </div>
    <div class="tab-pane fade" id="teams">
      <ul id="userslistteams"></ul>
      <div class="teams">
        <ul class="team">
          <li id="addteam">
            <svg enable-background="new 0 0 500 500" id="Layer_1" version="1.1" viewBox="0 0 500 500" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
              <circle cx="249.9" cy="250.4" r="204.7" stroke="#000000" stroke-miterlimit="10"/><circle cx="249.9" cy="247.4" fill="#FFFFFF" r="181.8" stroke="#000000" stroke-miterlimit="10"/>
              <g>
                <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="22" x1="250" x2="250" y1="123" y2="372"/>
                <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="22" x1="374" x2="126" y1="247" y2="247"/>
              </g>
            </svg>
          </li>
        </ul>
      </div>
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
