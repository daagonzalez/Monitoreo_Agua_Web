firebase.auth().onAuthStateChanged(function(user) {
  if (user) {
    // User is signed in.
    
    $("#page_loader").show();
    $(".login-cover").hide();
    
  } else {
    // No user is signed in.
    
    $("#page_loader").hide();
    $("#logindiv").show();
    
    var uiConfig = {
        callbacks: {
          signInSuccess: function(currentUser, credential, redirectUrl) {
            // Do something.
            // Return type determines whether we continue the redirect automatically
            // or whether we leave that to developer to handle.
            return false;
          }
        },
        singInFlow: 'popup',
        signInOptions: [
          // Leave the lines as is for the providers you want to offer your users.
          firebase.auth.GoogleAuthProvider.PROVIDER_ID,
          firebase.auth.EmailAuthProvider.PROVIDER_ID
        ],
        // Terms of service url.
        tosUrl: '<your-tos-url>'
      };

      // Initialize the FirebaseUI Widget using Firebase.
      var ui = new firebaseui.auth.AuthUI(firebase.auth());
      // The start method will wait until the DOM is loaded.
      ui.start('#loginData', uiConfig);
    
    
    
  }
});


function cerrar_sesion() {
    firebase.auth().signOut().then(function() {
      // Sign-out successful.
    }).catch(function(error) {
      // An error happened.
    });
}
