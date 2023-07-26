<?php
/*Source :  https://medium.com/@nattaponsirikamonnet/%E0%B8%AA%E0%B8%A3%E0%B9%89%E0%B8%B2%E0%B8%87-bot-%E0%B8%94%E0%B9%89%E0%B8%A7%E0%B8%A2-line-messaging-api-d7de644ac892
*/
    $accessToken = "Lw95GsIYZZ3kBurt7VvyTwp3TpIniGeXdr+mUVNK6k9LDH2Jj0G/D4UEeCjv1AnJBBuHVUJYdVfNpKUsdaER0x4GHUwH1tIdmdq6kunpzi0lKdVHOcudQatMhU6elgqUoP9B6MYg79RzvFFCKcBYwgdB04t89/1O/w1cDnyilFU="; //copy Channel access token ตอนที่ตั้งค่ามาใส่
    
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
    $message = trim($arrayJson['events'][0]['message']['text']);
    //$message = "@aaa"; /*debug1*/
    $checktxt = substr($message,0,1); /*debug1*/
    echo "<br>".$checktxt."<br>"; /*debug1*/


	
    if($checktxt == "@")
    {
        echo "<br>first txt char is @ <br>";
        echo $query_insert_new = "INSERT INTO TH_Meaning ('word','meaning','messageType','url') VALUES ('a','a','a','a')";

        try {
            $db = new PDO("sqlite:demo1.sqlite");
            //$sql = "INSERT INTO students_tb (sname, score) VALUES (:sname, :score)";
            $sql = "INSERT INTO TH_Meaning (word,meaning,messageType,url) VALUES ('a','a','a','a')";
            
            //$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            //$arrayPostData['messages'][0]['type'] = "text";
            //$arrayPostData['messages'][0]['text'] = $sql;
            //replyMsg($arrayHeader,$arrayPostData);
            
            $stat = $db->prepare($sql);

            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = $stat;
            replyMsg($arrayHeader,$arrayPostData);
 
            $success = $stat->execute();

            // does the value exist?
            if ($success) {
                echo "success";
                    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                    $arrayPostData['messages'][0]['type'] = "text";
                    $arrayPostData['messages'][0]['text'] = "success";
                    replyMsg($arrayHeader,$arrayPostData);
            } else {
                echo "not success";
                    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                    $arrayPostData['messages'][0]['type'] = "text";
                    $arrayPostData['messages'][0]['text'] = "Not success";
                    replyMsg($arrayHeader,$arrayPostData);
            }

            $db = null;

        } catch (PDOException $e) {
            // for development
            print "We had an error: " . $e->getMessage() . "<br>";
            die();
        }

        //$arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        //$arrayPostData['messages'][0]['type'] = "text";
        //$arrayPostData['messages'][0]['text'] = $query_insert_new;
        //replyMsg($arrayHeader,$arrayPostData);
    }
    elseif($checktxt !== "@") 
    {

        //connect sqlite3 database : demo1.sqlite
	    $dir = 'sqlite:demo1.sqlite';
	    $dbh  = new PDO($dir) or die("cannot open the database");

        echo "<br>first txt char is not @ <br>";
	    echo $query =  "SELECT * FROM TH_Meaning where word = '{$message}'";      

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
        if($message == "test"){
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "test";
            replyMsg($arrayHeader,$arrayPostData);
            echo "<br>"."test"."<br>";
        }
        else if($message == "สวัสดี"){
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
        else{ 
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "ยังไม่มีในความหมายในฐานข้อมูลค่ะ";
            $arrayPostData['messages'][1]['type'] = "sticker";
            $arrayPostData['messages'][1]['packageId'] = "11538";
            $arrayPostData['messages'][1]['stickerId'] = "51626509";
            replyMsg($arrayHeader,$arrayPostData);
        }	
    }

    
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