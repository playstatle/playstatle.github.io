<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content=”text/html;charset=utf-8" />
	<title>Registration</title>
	<script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>
	<script src="https://static.line-scdn.net/liff/edge/2.1/liff.js"></script>

	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
	crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script>
//init LIFF
function initializeApp(data) {

	liff.getProfile().then(function (profile){
		console.info(profile,'to');
		// if(profile.userId !== '' || profile.userId !== undefined){
			$('#userid').val(profile.userId);
		// }
	})

}

function chkPass(){
	var pass = $('#pass').val();
	var repass = $('#repass').val();

	console.info(pass,repass);

	if(pass !== '' && repass !== ''){
		if(pass != repass){
			alert("Your Password doesn't match");
		}
	}
}
//ready
$(function (){
	console.info("test");

	// liff.init(function (data){
	// 	console.info(data);
	// 	initializeApp(data);
	// });

	liff.init({

		liffId: "1645851682-ra4Y7avE"

	}).then(function (data){
		initializeApp();
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
	// 	text: $('¤Ø³à¤ÂÅ§·ÐàºÕÂ¹áÅéÇ').val()
	// }
	// ])
	// .then(() => {
	// 	alert('done');
	// })
	// });

});
// $(function () {
// //init LIFF
// $('#userid').val("test");
// $('#name').val('test');
// console.info("test");
// liff.init(function (data) {

// 	initializeApp(data);
// });
// // ButtonGetProfile
// $('#ButtonGetProfile').click(function () {
// 	liff.getProfile().then(
// 		profile=> {
// 			$('#UserInfo').val(profile.displayName);
// 			alert('done');
// 		}
// 		);
// });
// // ButtonSendMsg #QueryString
// $('#ButtonSendMsg').click(function () {
// 	liff.sendMessages([
// 	{
// 		type: 'text',
// 		text: $(‘#userid’).val() + $(‘#QueryString’).val() + $(‘#msg’).val()
// 		text: $('¤Ø³à¤ÂÅ§·ÐàºÕÂ¹áÅéÇ').val()
// 	}
// 	])
// 	.then(() => {
// 		alert('done');
// 	})
// });
// });
</script>
</head>
<?php 
ini_set("display_errors", 1);
// require "vendor/autoload.php";
// require_once('line-bot/line-bot-sdk-php-master/src/LINEBot.php');

// echo "test";
// $content = file_get_contents('php://input');
// $arrayJson = json_decode($content, true);
// print_r($arrayJson);
// $result = array();
// $arrayHeader = array();
// $accessToken = "YDYCsmgEH7CeBJ8PgtOAddbi01YgqLvpjSG+dVloDYw94BzLj26yUkgJ0OL132jmZbL/gXy/hmCooiSVyZcI9IoxhmN84y5EUj6CmVvTOoMmYDGBAM6yv3HmX96HW6xKpwTbPoF7YhMH/3awaoH/9gdB04t89/1O/w1cDnyilFU=";
// $arrayHeader[] = "Content-Type: application/json";
// $arrayHeader[] = "Authorization: Bearer {$accessToken}";

// $id = $arrayJson['events'][0]['source']['userId'];
// print_r($id);
// exit();
?>
<body>
	<form action="save.php" method="get">
		<div class="row">
			<div class="col-md-6" style="margin:5px">
				<input class="form-control" type="hidden" id="userid" name="userid" /> <br />
				<label>Name</label>
				<input class="form-control" type="text" id="name" name="name"/><br />
				<label>Lastname</label>
				<input class="form-control" type="text" id="lastname" name="lastname"/><br />
				<label>Email</label>
				<input class="form-control" type="email" id="email" name="email"/><br />
				<label>User Type</label>
				<select class="form-control" id="user_tpye" name="user_tpye">
				<option value="" selected="">Please select</option>
					<option value="UTAC">UTAC Employee</option>
					<option value="TIU">TEXAS INSTRUMENTS INC.</option>
					<option value="MXM">MAXIM INTEGRATED PRODUCTS</option>
					<option value="STM">ST MICROELECTRONICS</option>
					<option value="EMS">ELMOS SEMICONDUCTOR</option>
					<option value="AKM">ASAHI KASEI MICROSYSTEM</option>
					<option value="IFX">INFINEON TECHNOLOGY</option>
					<option value="ONS">ON SEMICONDUCTOR</option>
					<option value="MCH">MICROCHIP TECHNOLOGY</option>
					<option value="PII">POWER INTEGRATIONS INC.</option>
					<option value="IN6">INFINEON TECHNOLOGY </option>
					<option value="NXP">NXP SEMICONDUCTORS</option>
					<option value="ISL">ISL-RENESAS</option>
				</select><br/>
				<!-- <label>Username</label>
				<input class="form-control" type="text" id="username" name="username"/><br /> -->
				<label>Password</label>
				<input class="form-control" type="password" id="pass" name="pass" autocomplete="off" /><br />
				<label>Confirm Password</label>
				<input class="form-control" type="password" id="repass" name="repass" onchange="chkPass();" autocomplete="off" /><br />
				
				<button class="btn btn-primary" >Register</button>
			</div>
		</div>
	</form>
</body>
</html>