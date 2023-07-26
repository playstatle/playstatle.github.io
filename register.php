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
	<script>
            
//init LIFF
function initializeApp(data) {
		// alert("hey");
		// var data = liff.getProfile();
		// console.info(data);
		liff.getProfile().then(function (profile){
			console.info(profile,'to');
			if(profile.userId !== '' || profile.userId !== undefined){
				$('#userid').val(profile.userId);
			}
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
	console.info("test");

	liff.init({

		liffId: "1645851682-ra4Y7avE"

	}).then(function (data){
		initializeApp();
	});

	$('#pass').change(function(){

		var not_match_count = 0;
		var not_match = 'You must set password with'
		var pass = $(this).val();

		var lowercase = /[a-z]/g;
		if(lowercase.test(pass) === false){
			not_match_count++;
			not_match += ' lowercase letter';
		}

		var uppercase = /[A-Z]/g;
		if(uppercase.test(pass) === false){
			if(not_match_count > 0){
				not_match += ',';
			}
			not_match_count++;
			not_match += ' uppercase letter';
		}

		var numbercase = /[0-9]/g;
		if(numbercase.test(pass) === false){
			if(not_match_count > 0){
				not_match += ',';
			}
			not_match_count++;
			not_match += ' number';
		}
		
		if(pass.length < 8){
			if(not_match_count > 0){
				not_match += ',';
			}
			not_match_count++;
			not_match += ' minimum 8 characters';
		}

		var special_characters = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;
		if(special_characters.test(pass) === false){
			if(not_match_count > 0){
				not_match += ',';
			}
			not_match_count++;
			not_match += ' special characters';
		}

		if(not_match_count > 0){
			alert(not_match);
			$(this).val('');
		}

	});

	

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
    
    $servername = "utlrms1";
    $username = "atroot";
    $password = "atrtms@U1";
    $dbname = "application";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT id, code, name FROM line_user_type ORDER BY name");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        
//        foreach($result as $res){
//           echo $res["id"];
//        }
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    
    $conn = null;

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
                    <option value="" selected disabled hidden>Please select</option>
                    
                    <?php foreach($result as $res): ?>
                        <option value="<?php echo $res["code"] ?>"><?php echo $res["name"] ?></option>
                    <?php endforeach ?>

                </select><br/> 

                <label>Password</label>
                <input class="form-control" type="password" id="pass" name="pass" autocomplete="off" /><br />
                <label>Confirm Password</label>
                <input class="form-control" type="password" id="repass" name="repass" onchange="chkPass();" autocomplete="off" /><br />

                <button class="btn btn-primary" id="btnRegist" >Register</button>
            </div>
        </div>
    </form>
</body>
</html>