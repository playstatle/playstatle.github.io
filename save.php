
<html>
<head>
	<title>Register Result</title>
</head>
</html>
<?php
date_default_timezone_set("Asia/Bangkok");
$date = date("Y-m-d");
$time = date("H:i:s");
// $serverName="ip_serverDB";
// $userName="USER_DB";
// $userPassword="PASSWORD_DB";
// $dbName="DB_NAME";
$name = $_REQUEST['name'];
$lastname = $_REQUEST['lastname'];
$id = $_REQUEST['userid'];
$user_type = $_REQUEST['user_tpye'];
$pass = $_REQUEST['pass'];
$email = $_REQUEST['email'];

if($user_type === ''){
	echo '<h1 align="center"><font color="red">Please select user type first.</font></h1>';
	echo '<h1 align="center"><font color="red"> Please try to register again.</font></h1>';
	exit();
}

// echo("id = $id");exit();
if(isset($name) && isset($email) && isset($pass) && !empty($id)){
	$connect=mysqli_connect("172.16.2.78", "atroot","atrtms@U1","application") or die("Error, can't connect database");
	mysqli_set_charset($connect,"utf8");
	$sql = "select name, lastname, userId from line_user_register where userId='{$id}' group by userId"; 
	$result = mysqli_query($connect,$sql) or die ("error".mysqli_error()); 
	$count_row = mysqli_num_rows($result);
	if($count_row < 1 && !empty($user_type)){ 
		
		$query = "INSERT INTO line_user_register(name,lastname,email,user_type,status,userId,pass,modified_date) VALUE ('$name','$lastname','$email','$user_type','INACTIVE','$id','$pass',NOW())"; 
		
		$resource = mysqli_query($connect,$query) or die ("error".mysqli_error());
		echo "<br/><br/>";
		echo '<h1 align="center"><font color="red">Register Successful</font></h1>';
		echo '<h1 align="center"><font color="red"> Please Login.</font></h1>';
		// echo '<h1 align="center"><font color="red"> กดที่เครื่องหมาย X มุมขวาบนเพื่อปิดหน้าต่างนี้</font></h1>'; 
	}else{ 
			$row=mysqli_fetch_assoc($result);
			echo '<h1 align="center"><font color="red">You already have a registration by '.$row["name"].' '.$row["lastname"].'.</font></h1>';
	} 
}else{
		echo '<h1 align="center"><font color="red"> Please try again.</font></h1>';
}
?>