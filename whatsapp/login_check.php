
<html>
<head>
	<title>Login Successful</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content=”text/html;charset=utf-8" />
	<title>Login</title>
	<script src="https://d.line-scdn.net/liff/1.0/sdk.js"></script>

	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
	crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script type="text/javascript">
		function closewindow(){
			liff.closewindow();
		}

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


		});

	</script>
</head>
</html>

<?php 

$flag = "";
$email = $_POST['email'];
$id = $_POST['userid'];
$pass = $_POST['pass'];
// $email = 'lalitadu@utacgroup.com';
// $id = 'Ub7bdf5fc0521052f639d6cd31fc926da';
// $pass = '12345';

$connect=mysqli_connect("127.0.0.1", "root","","test") or die("Error, can't connect database");
mysqli_set_charset($connect,"utf8"); 
$sql = "SELECT * from whatsapp_login where userId='{$id}' 
AND whatsapp_login.email = '{$email}' AND whatsapp_login.pass = '{$pass}' 
AND whatsapp_login.`status` = 'ACTIVE'";
$sql1 = "UPDATE whatsapp_login SET active_flag= 1 where userId='{$id}' 
AND whatsapp_login.email = '{$email}' AND whatsapp_login.pass = '{$pass}' 
AND whatsapp_login.`status` = 'ACTIVE'";
$result = mysqli_query($connect,$sql) or die ("error".mysqli_error()); 

$count_row = mysqli_num_rows($result);

if($count_row > 0){
	while($row = mysqli_fetch_assoc($result)){
		if($row['user_type'] == 'UTAC'){
			$result1 = mysqli_query($connect,$sql1) or die ("error".mysqli_error($result));
				echo '<h1 align="center"><font color="red">Login Successful</font></h1>';
		}if($row['user_type'] == 'STM'){
			$result1 = mysqli_query($connect,$sql1) or die ("error".mysqli_error($result));
				echo '<h1 align="center"><font color="red">Login Successful</font></h1>';
			}
	}
}else if($count_row == 0){
	echo '<h3 align="center"><font color="red">You need an approval before access.</font></h3>';
}

?>