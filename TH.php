<?php
/*Source :  https://medium.com/@nattaponsirikamonnet/%E0%B8%AA%E0%B8%A3%E0%B9%89%E0%B8%B2%E0%B8%87-bot-%E0%B8%94%E0%B9%89%E0%B8%A7%E0%B8%A2-line-messaging-api-d7de644ac892
*/
    $accessToken = "7X+iG5fXeq8QJwm9cbG8w4WzH0/O+ooshKi3bwZTVvSA83hXiQydfQdQDKSUX0+6iHAHwEE30dv4q/ftCttyUQH+Sfb67J4j8nkodQeeZPGYaksbOV81ALlehjnWvmb6v7vbCcLS80Qceiv7Op0W/wdB04t89/1O/w1cDnyilFU="; //copy Channel access token ตอนที่ตั้งค่ามาใส่
    
    $content = file_get_contents('php://input');
    $arrayJson = json_decode($content, true);
    
    $arrayHeader = array();
    $arrayHeader[] = "Content-Type: application/json";
    $arrayHeader[] = "Authorization: Bearer {$accessToken}";
	
	echo "I am Panida Teacher Bot";
	echo '<pre>';
	print_r(PDO::getAvailableDrivers());
	echo '</pre>';
 
     //รับข้อความจากผู้ใช้
    $message = $arrayJson['events'][0]['message']['text'];
 
	//connect sqlite3 database : demo1.sqlite
	$dir = 'sqlite:demo1.sqlite';
	//echo "<br>";
	$dbh  = new PDO($dir) or die("cannot open the database");
	//$query =  'SELECT * FROM TH_Meaning where word = '.$message.''; 
	//echo $query =  'SELECT * FROM TH_Meaning where word = "ข้น"';
	echo $query =  "SELECT * FROM TH_Meaning where word = '{$message}'";
	echo "<br>";
	
	foreach ($dbh->query($query) as $row)
	{
		echo $row[0]."<br>".$row[1]."<br>".$row[2]."<br>".$row[3]."<br>".$row[4]."<br>";
		echo "$ID = ".$ID = $row[0];
		echo "$word = ".$word = $row[1];
		echo "$meaning = ".$meaning = "หมายถึง : ".$row[2];
		echo "$messageType = ".$messageTypr = $row[3];
		echo "$url = ".$url = $row[4];
	
	}
	$dbh = null; //This is how you close a PDO connection
	
	$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = $meaning;
    replyMsg($arrayHeader,$arrayPostData);

	#ตัวอย่าง Message Type "Text"
    if($message == "สวัสดี"){
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "สวัสดีจ้าาา";
        replyMsg($arrayHeader,$arrayPostData);
    }
	#ตัวอย่าง Message Type "Text"
    else if($message == "หวัดดี"){
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "สวัสดีจ้าาา";
        replyMsg($arrayHeader,$arrayPostData);
    }
    #ตัวอย่าง Message Type "Sticker"
    else if($message == "ฝันดี"){
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "sticker";
        $arrayPostData['messages'][0]['packageId'] = "2";
        $arrayPostData['messages'][0]['stickerId'] = "46";
        replyMsg($arrayHeader,$arrayPostData);
    }
    #ตัวอย่าง Message Type "Image"
    else if($message == "รูปน้องแมว"){
        $image_url = "https://i.pinimg.com/originals/cc/22/d1/cc22d10d9096e70fe3dbe3be2630182b.jpg";
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "image";
        $arrayPostData['messages'][0]['originalContentUrl'] = $image_url;
        $arrayPostData['messages'][0]['previewImageUrl'] = $image_url;
        replyMsg($arrayHeader,$arrayPostData);
    }
    else if($message == "รูปน้องแพน"){
        $image_url = "https://8d4e-210-4-134-252.ap.ngrok.io/develop/bot_th_pictures/pan.jpg";
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "image";
        $arrayPostData['messages'][0]['originalContentUrl'] = $image_url;
        $arrayPostData['messages'][0]['previewImageUrl'] = $image_url;
        replyMsg($arrayHeader,$arrayPostData);
    }
	else if($message == "รูปเขาพระสุเมรุ"){
        $image_url = "https://8d4e-210-4-134-252.ap.ngrok.io/develop/bot_th_pictures/S__57737268.jpg";
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "image";
        $arrayPostData['messages'][0]['originalContentUrl'] = $image_url;
        $arrayPostData['messages'][0]['previewImageUrl'] = $image_url;
        replyMsg($arrayHeader,$arrayPostData);
    }
    #ตัวอย่าง Message Type "Location"
    else if($message == "พิกัดสยามพารากอน"){
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "location";
        $arrayPostData['messages'][0]['title'] = "สยามพารากอน";
        $arrayPostData['messages'][0]['address'] =   "13.7465354,100.532752";
        $arrayPostData['messages'][0]['latitude'] = "13.7465354";
        $arrayPostData['messages'][0]['longitude'] = "100.532752";
        replyMsg($arrayHeader,$arrayPostData);
    }
    #ตัวอย่าง Message Type "Text + Sticker ใน 1 ครั้ง"
    else if($message == "ลาก่อน"){
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "อย่าทิ้งกันไป";
        $arrayPostData['messages'][1]['type'] = "sticker";
        $arrayPostData['messages'][1]['packageId'] = "1";
        $arrayPostData['messages'][1]['stickerId'] = "131";
        replyMsg($arrayHeader,$arrayPostData);
    }
	
	#เติ้ล edit======================================================================= 
	#ตัวอย่าง Message Type "Text + Sticker ใน 1 ครั้ง"
    else if($message == "hi"){
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "หวัดดี";
        $arrayPostData['messages'][1]['type'] = "sticker";
        $arrayPostData['messages'][1]['packageId'] = "11537";
        $arrayPostData['messages'][1]['stickerId'] = "52002768";
        replyMsg($arrayHeader,$arrayPostData);
    }

	else 
	    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "ยังไม่มีในความหมายในฐานข้อมูลค่ะ";
		$arrayPostData['messages'][1]['type'] = "sticker";
        $arrayPostData['messages'][1]['packageId'] = "11538";
        $arrayPostData['messages'][1]['stickerId'] = "51626509";
        replyMsg($arrayHeader,$arrayPostData);
		
	
function replyMsg($arrayHeader,$arrayPostData){
        $strUrl = "https://api.line.me/v2/bot/message/reply";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$strUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);    
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arrayPostData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close ($ch);
    }
   exit;
?>