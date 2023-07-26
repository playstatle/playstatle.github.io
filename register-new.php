<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content=”text/html;charset=utf-8" />
	<title>Registration</title>
	<!-- <script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script> -->
	<script src="https://static.line-scdn.net/liff/edge/2.1/liff.js"></script>
	<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
	 <!--  <script src="liff-starter.js"></script> -->
	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
	crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	
	<style>
		/* .top-buffer { 
			left: 50%;
			margin-right: -50%;
			margin-top: 150px;
		}
		label {
			display: inline-block;
			width: 140px;
			text-align: right;
		}
		#message {
			display:none;
			background: #f1f1f1;
			color: #000;
			position: relative;
			padding: 20px;
			margin-top: 10px;
		}

		#message p {
			padding: 10px 35px;
			font-size: 18px;
		}

		.valid {
			color: green;
		}

		.valid:before {
			position: relative;
			left: -35px;
			content: "?";
		}

		.invalid {
			color: red;
		}

		.invalid:before {
			position: relative;
			left: -35px;
			content: "?";
		} */
	</style>
	
	<script>

		var myInput;
		var letter;
		var capital;
		var number;
		var length;
		var special;
		//init LIFF
		function initializeApp(data) {
				// alert("hey");
				// var data = liff.getProfile();
				// console.info(data);
				liff.getProfile().then(function (profile){
					console.info(profile,'to');
					$('#userid').val(profile.userId);
				})

				// UserId(data);
			
			// $('#name').val(data.displayName);
			// $('#userid').val(data.userId);

			// $('#UserInfo').val(profile.displayName);
		}

		function UserId(userData){
			console.info(userData);
		}

		function chkPass(){
			var pass = $('#pass').val();
			var repass = $('#repass').val();

			console.info(pass,repass);

			if(pass !== '' && repass !== ''){
				if(pass != repass){
					alert("Your Password doesn't match");
					$('#repass').val('');
					$('#pass').val('');
					letter.classList.remove("valid");
					letter.classList.add("invalid");
					length.classList.remove("valid");
					length.classList.add("invalid");
					number.classList.remove("valid");
					number.classList.add("invalid");
					capital.classList.remove("valid");
					capital.classList.add("invalid");
					special.classList.remove("valid");
					special.classList.add("invalid");
				}
			}

		}

		function ValidateEmail(){
			var email = $('#email').val();
			console.info(email);
			var mail = email.split("@");
			var mail_chk = mail[1].split(".");
			console.info(mail_chk);
			if(mail_chk[0] == 'hotmail' || mail_chk[0] == 'gmail' || mail_chk[0] == 'yahoo' || mail_chk[0] == 'outlook' || mail_chk[0]=='live' ){
				alert("Can't use personal email");
				$("#email").val(" ");
			}
			// if(mail[1] != 'utacgroup.com'){
			// 	alert("Please register with company email");
			// 	$("#email").val(" ");
			// }
		}

		function cust_code_enable(val){
				// var selected = $('#user_type').val();
			// alert(selected);
			if(val.value !== ''){
				if(val.value =='customer'){
				
					$('#cust_code').removeAttr('disabled');

				}if(val.value !='customer'){
					$('#cust_code').attr('disabled', 'disabled');
				}
			}
		}

		//ready
		$(function (){

			liff.init({

				liffId: "1645851682-ra4Y7avE"

			}).then(function (data){
				initializeApp();
			}
				
			);

			myInput = document.getElementById("pass");
			letter = document.getElementById("letter");
			capital = document.getElementById("capital");
			number = document.getElementById("number");
			length = document.getElementById("length");
			special = document.getElementById("special");

			document.getElementById("message").style.display = "none";

			myInput.onfocus = function () {
				document.getElementById("message").style.display = "block";
			}

			myInput.onblur = function () {
				document.getElementById("message").style.display = "none";
			}

			myInput.onkeyup = function () {
				// Validate lowercase letters
				var lowerCaseLetters = /[a-z]/g;
				if (myInput.value.match(lowerCaseLetters)) {
					letter.classList.remove("invalid");
					letter.classList.add("valid");
				} else {
					letter.classList.remove("valid");
					letter.classList.add("invalid");
				}

				// Validate capital letters
				var upperCaseLetters = /[A-Z]/g;
				if (myInput.value.match(upperCaseLetters)) {
					capital.classList.remove("invalid");
					capital.classList.add("valid");
				} else {
					capital.classList.remove("valid");
					capital.classList.add("invalid");
				}

				// Validate numbers
				var numbers = /[0-9]/g;
				if (myInput.value.match(numbers)) {
					number.classList.remove("invalid");
					number.classList.add("valid");
				} else {
					number.classList.remove("valid");
					number.classList.add("invalid");
				}

				// Validate length

				if (myInput.value.length >= 8) {
					length.classList.remove("invalid");
					length.classList.add("valid");
				} else {
					length.classList.remove("valid");
					length.classList.add("invalid");
				}


				var specials = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
				if (specials.test(myInput.value)) {
					special.classList.remove("invalid");
					special.classList.add("valid");
				} else {
					special.classList.remove("valid");
					special.classList.add("invalid");
				}
			}
			
			var msg = "<?php echo $message; ?>";
			
			if (msg !== '') {
				alert(msg);
			}

		});

		// $(function (){
		// 	console.info("no");
			


		// 	// liff.getProfile();
		// 	// liff.getProfile(function (data){
		// 	// 	console.info("get");
		// 	// });
		// 	////////////////
		// 	// liff.getProfile().then(profile =>{
		// 	// 		initializeApp(profile);

		// 	// }).catch(function (error) {
		// 	// 		window.alert("Error getting profile: " + error);
		// 	// });

		//  //    $('#btnRegist').addEventListener('click', function () {
		//  //        liff.closeWindow();
		//  //    });   
		//  //    console.info("get"); 
			
		// })


		// liff.init({ 

		// 	liffId: "1645851682-ra4Y7avE"

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
				<input class="form-control" type="email" id="email" name="email" onchange="ValidateEmail();" /><br />
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
			

				<label>Password</label>
				<input class="form-control" type="password" id="pass" name="pass" autocomplete="off" /><br />
				<label>Confirm Password</label>
				<input class="form-control" type="password" id="repass" name="repass" onchange="chkPass();" autocomplete="off" /><br />
				
				<button class="btn btn-primary" id="btnRegist" >Register</button>

				<div id="message" >
					<h3>Password must contain the following:</h3>
					<p id="letter" class="invalid">A <b>lowercase</b> letter</p>
					<p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
					<p id="number" class="invalid">A <b>number</b></p>
					<p id="length" class="invalid">Minimum <b>8 characters</b></p>
					<p id="special" class="invalid">Special <b>characters</b></p>
				</div>

			</div>
		</div>
	</form>
</body>

</html>