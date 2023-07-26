<html>

<head>
	<title>Login Successful</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content=”text/html;charset=utf-8" />
	<title>Login</title>
	<script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>

	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script type="text/javascript">
		function closewindow() {
			liff.closewindow();
		}

		$(function() {
			console.info("test");

			liff.init(function(data) {
				initializeApp(data);
			});


			$('#ButtonGetProfile').click(function() {
				liff.getProfile().then(
					profile => {
						$('#UserInfo').val(profile.displayName);
						alert('done');
					}
				);
			});


		});
	</script>
</head>

</html>

<?php

ini_set("display_errors", 1);
require_once('line-bot/line-bot-sdk-php-master/src/LINEBot.php');

require "vendor/autoload.php";

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('iLbB6nbkgebIvHjep64Qfnt6+RslJfl7S0SY2tp4aeq17q/vYJKhaV+DIRyjWyK4ZbL/gXy/hmCooiSVyZcI9IoxhmN84y5EUj6CmVvTOoMbhUwjNu9o7bM+S2saftQZ352yR/qWbROC3UBOliyCowdB04t89/1O/w1cDnyilFU=');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => 'b95c6d9e6b5fc3269094c5838a1ad451']);

$email = $_POST['email'];
$id = $_POST['userid'];
$pass = $_POST['pass'];

$connect = mysqli_connect("172.16.2.78", "atroot", "atrtms@U1", "application") or die("Error, can't connect database");
mysqli_set_charset($connect, "utf8");
$sql = "SELECT * from line_user_register where line_user_register.email = '{$email}' AND line_user_register.pass = '{$pass}' 
AND line_user_register.`status` = 'ACTIVE'";
$result = mysqli_query($connect, $sql) or die("error" . mysqli_error());


$count_row = mysqli_num_rows($result);

if ($count_row > 0) {
	while ($row = mysqli_fetch_assoc($result)) {
		$flag = false;
		if ($row['user_type'] == 'UTAC') {

			$richId = 'richmenu-aaad9f3a3c3fc42c35082c8fda4088a6';

			$response = $bot->linkRichMenu($id, $richId);
			$flag = true;
			if ($response) {
				echo '<h1 align="center"><font color="red">Login Successful</font></h1>';
			} else {
				echo '<h1 align="center"><font color="red">Please Login Again</font></h1>';
			}
		}
		if ($row['user_type'] == 'STM') {

			$richId = 'richmenu-5deeb2c23934ade657c155b03ee6c1a8';

			$response = $bot->linkRichMenu($id, $richId);
			$flag = true;
			if ($response) {
				echo '<h1 align="center"><font color="red">Login Successful</font></h1>';
			} else {
				echo '<h1 align="center"><font color="red">Please Login Again</font></h1>';
			}
		} elseif ($flag == false) {

			$richId = 'richmenu-2dd94505676666788dc75d7af984a6df';

			$response = $bot->linkRichMenu($id, $richId);

			if ($response) {
				echo '<h1 align="center"><font color="red">Login Successful</font></h1>';
			} else {
				echo '<h1 align="center"><font color="red">Please Login Again</font></h1>';
			}
		}
	}
} else if ($count_row == 0) {
	echo '<h3 align="center"><font color="red">You need an approval before access.</font></h3>';
}

?>