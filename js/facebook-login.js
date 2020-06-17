(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.CommunityBuilder = {
    attach: function (context, settings) {

      let appId = drupalSettings.community_builder.app_id,
          version = drupalSettings.community_builder.version;
      // Facebook app
      logInWithFacebook = function() {
        FB.login(function(response) {
          if (response.authResponse) {
            const accessToken = response.authResponse.accessToken;
            // Get user details like name email and friends.
            getUserDetails(accessToken);
            // Now you can redirect the user or do an AJAX request to
            // a PHP script that grabs the signed request from the cookie.
          } else {
            alert('User cancelled login or did not fully authorize.');
          }
        });
        return false;
      };

      getUserDetails = function(accessToken) {
        // FB user API.
        FB.api(
          '/me',
          'GET',
          {
            "fields" : "name,email,friends",
            "access_token" : accessToken
          },
          function(response) {
            console.log('UserData', {response});
            // Make ajax request to drupal for user authentication.
            let xhr = new XMLHttpRequest();
            xhr.open("POST", '/facebook/user/login', true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
              if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                let res = JSON.parse(this.response);
                if (res.status === 200) {
                  // Redirect user after successful.
                  window.location.href = res.redirect;
                } else {
                  console.log(res);
                }
              }
            };
            xhr.send(`email=${response.email}&name=${response.name}&uid=${response.id}&friends=${JSON.stringify(response.friends.data)}`);
          }
        );
      };

      window.fbAsyncInit = function() {
        FB.init({
          appId: appId, // app id is from config settings.
          cookie: true, // This is important, it's not enabled by default
          version: version // version is from config settings.
        });
      };

      (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    }
  };
})(jQuery, Drupal, drupalSettings);
