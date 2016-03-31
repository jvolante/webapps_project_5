<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <?php include 'defaultheader.php'; ?>
    <title>People's Choice Awards</title>
  </head>
  <body>
    <head>
      <link rel="stylesheet" href="poop.css" media="screen" title="no title" charset="utf-8">
      <script type="text/javascript" src="poop.js">

      </script>
    </head>
    <!-- /// JUMBOTRON \\\ -->
    <div class="jumbotron cd-intro">
      <div class="container cd-intro-content mask">
        <div class="hr animated fadeIn">
          <img src="1st trophy.png" alt="Maple Leaf logo">
          <hr>
        </div>

        <h1 data-content="People's Choice Awards"><span>People's Choice Awards</span></h1>

        <div class="action-wrapper">
          <p>
            <a href="#projects" class="cd-btn main-action" role="button">View Projects</a>
            <a href="404.html" class="cd-btn" id="signin" target="_blank" role="button"><?php
              // Generate sign in button.
              if(isset($_SESSION[$userParam])){
                $name = $_SESSION[$userParam];
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
    <section id="projects">
      Projects Area
      <?php
        // TODO: Generate projects table from the database
      ?>
    </section>
  </body>
</html>
