<div id="status"></div>
<div>
    Latest access token: <div><?php echo $latestToken; ?></div>
</div>
<button id="button" onclick="clickHandler()">Get token</button>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({appId: '<?php echo sfConfig::get("app_facebook_app_id"); ?>', status: true, cookie: true,
             xfbml: true});
  };
  (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
      '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
  }());
</script>
<script>

    function clickHandler() {
        FB.login(function(response) {
          
          if (response.session) {
            if (response.perms) {
              // user is logged in and granted some permissions.
              // perms is a comma separated list of granted permissions
              window.location = "<?php echo url_for("oauth/save") ?>?token=" + response.session.access_token;
            } else {
              // user is logged in, but did not grant any permissions
              $("#status").innerHTML = "Error - must click 'Allow' to generate token";
            }
          } else {
            // user is not logged in
            $("#status").innerHTML = "Error - must be logged into Facebook";
          }
        }, {perms:'offline_access'});
    }
</script>