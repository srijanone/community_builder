getFacebookAccessToken = function() {
  FB.login(function(response) {
    if (response.authResponse) {
      console.log(response);
      const accessToken = response.authResponse.accessToken;
      getFacebookFriends(accessToken);
      // Now you can redirect the user or do an AJAX request to
      // a PHP script that grabs the signed request from the cookie.
    } else {
      alert('User cancelled login or did not fully authorize.');
    }
  });
  return false;
};

getFacebookFriends = function(accessToken) {
  // FB user API.
  FB.api(
    '/me',
    'GET',
    {
      "fields" : "friends,name,email",
      "access_token" : accessToken
    },
    function(response) {
      console.log('Facebook Friends', {response});
      var UserData = response.friends.data;
      var list = '';
      for (var i = 0; i < UserData.length; i++) {
        list += '<li>' + UserData[i].name +  '</li>';
      }
      // document.cookie = "FacebookFriends=" + list;
      document.getElementById('user-name').innerHTML = 'Friends of ' + response.name;
      document.getElementById('facebook-friends').innerHTML = list;
    }
  );
};

window.fbAsyncInit = function() {
  FB.init({
    appId: '1456192121221050',
    cookie: true, // This is important, it's not enabled by default
    version: 'v7.0'
  });
};

(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
