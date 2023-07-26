window.onload = function () {
  const defaultLiffId = "1655446689-lO2NWoMy";

  let myLiffId = defaultLiffId;

  liff
    .init({
      liffId: myLiffId,
    })
    .then(() => {
      // start to use LIFF's api
      // console.log("Init Successful");

      initializeApp();
    })
    .catch((err) => {
      console.log(err);
    });
};

function initializeApp() {
  if (liff.isLoggedIn()) {
    if (liff.isInClient()) {
      liff.getProfile().then(function (profile) {
        // console.log(profile);

        getUserProfile(profile.userId);
      });
      // console.log("Logged in on Mobile");
    } else {
      liff.getProfile().then(function (profile) {
        // console.log(profile);
        
        getUserProfile(profile.userId);
      });
      // console.log("Logged in on Desktop");
    }
  } else {
    console.log("Your are not login");
    liff.login();
  }
}

