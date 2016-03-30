<style scoped>
#topbar{
  position: fixed;

}
</style>
<div id="topbar">
<script type="text/javascript">
  // Javascript necessary for the top bar to work.
  function showSigninFlyout() {
    // TODO: Do stuff to show the siginin flyout.
  }

  $(function (){
    $("#signin").click(function (){
      showSigninFlyout();
      return false;
    });

    drop = new Drop
      target: document.querySelector('#signin')
      content: 'Hello World'
      position: 'bottom center'
      openOn: 'click'
  });
</script>
<?php
  // Generate sign in button along with other crap.
  if(isset($_SESSION[$userParam])){
    $name = $_SESSION[$userParam];
    echo 'Welcome $name! | (<a href="404.html" id="signin">Not you?</a>)';
  } else {
    echo '<a href="404.html" id="signin">Sign in</a>';
  }
?>
</div>
