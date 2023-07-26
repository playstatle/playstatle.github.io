<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content=”text/html;charset=utf-8" />
	<title>Login</title>
	<script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>

	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
	crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script>
//init LIFF
function initializeApp(data) {

	// alert(data.context.userId);
	let urlParams = new URLSearchParams(window.location.search);
	
	$('#name').val(urlParams.toString());
	$('#userid').val(data.context.userId);

	$('#UserInfo').val(profile.displayName);
}
//ready
$(function (){
	console.info("test");

	liff.init(function (data){
		initializeApp(data);
	});


	$('#ButtonGetProfile').click(function () {
	liff.getProfile().then(
		profile=> {
			$('#UserInfo').val(profile.displayName);
			alert('done');
		}
		);
	});


	// $('#ButtonSendMsg').click(function () {
	// liff.sendMessages([
	// {
	// 	type: 'text',
	// 	text: $(‘#userid’).val() + $(‘#QueryString’).val() + $(‘#msg’).val()
	// 	text: $('คุณเคยลงทะเบียนแล้ว').val()
	// }
	// ])
	// .then(() => {
	// 	alert('done');
	// })
	// });

});

</script>
</head>

<body>
	<form action="login_check.php" method="post">
		<div class="row">
			<div class="col-md-6" style="margin:5px">
				<input class="form-control" type="hidden" id="userid" name="userid" /> <br />
				<label>Email</label>
				<input class="form-control" type="email" id="email" name="email" autocomplete="off" /><br />
				<label>Password</label>
				<input class="form-control" type="password" id="pass" name="pass" autocomplete="off" /><br />
				
				
				<button class="btn btn-primary" >Login</button>
			</div>
		</div>
	</form>
</body>
</html>