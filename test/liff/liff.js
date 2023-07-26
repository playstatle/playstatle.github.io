liff.getProfile().then(function (profile) {
	  liff.sendMessages([
		{
		  type: 'image',
		  originalContentUrl: 'https://' + document.domain + '/imgs/' + res + '.jpg',
		  previewImageUrl: 'https://' + document.domain + '/imgs/' + res + '_240.jpg'
		},
		{
		  type: 'text',
		  text: 'From:' + profile.displayName
		}
	  ]).then(function () {
		liff.closeWindow();
	  }).catch(function (error) {
		window.alert('Error sending message: ' + error.message);
	  });
	}).catch(function (error) {
		window.alert("Error getting profile: " + error.message);
	});
}