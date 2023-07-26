<?php

ini_set("display_errors", 1);
require "vendor/autoload.php";
require_once('line-bot/line-bot-sdk-php-master/src/LINEBot.php');
date_default_timezone_set("Asia/Bangkok");

// require_once '../vendor/autoload.php';
// $accessToken = "FBhFsHIOv/q6QqOgkgGesE4rM3fdLzdQymHbmcxRSM78LwCIASJZ6dlwzgm394QqXhsVpoUtqCtJCNYXij/gOjdFdkMQAzfVNMLrg5xuwuocDMRZXrUM1PSFjt2mj9SSP5w1R9x/9G4RItHZxgXP+gdB04t89/1O/w1cDnyilFU="; 
//copy Channel access token ตอนที่ตั้งค่ามาใส่
$accessToken = "iLbB6nbkgebIvHjep64Qfnt6+RslJfl7S0SY2tp4aeq17q/vYJKhaV+DIRyjWyK4ZbL/gXy/hmCooiSVyZcI9IoxhmN84y5EUj6CmVvTOoMbhUwjNu9o7bM+S2saftQZ352yR/qWbROC3UBOliyCowdB04t89/1O/w1cDnyilFU=";

$content = file_get_contents('php://input');
$arrayJson = json_decode($content, true);
$result = array();
$arrayHeader = array();
$content = '';
$lot = '';
$mc_id = '';
$bom = '';
$time_start = '';
$package = '';
$en_start = '';
$i = 0;
$arrayPostData = array();
$arrayHeader[] = "Content-Type: application/json";
$arrayHeader[] = "Authorization: Bearer {$accessToken}";
$log_msg = array();
echo "Hi2<br>";


$log_name = "UTAC_Anywhere_log_" . date('Y-m-d') . ".txt";
// $log_name = "UTAC_Anywhere_log_2019-10-22.txt";
//รับข้อความจากผู้ใช้
$id = $arrayJson['events'][0]['source']['userId'];
// $Name = $arrayJson['events'][0]['source']['userId'];


$message = $arrayJson['events'][0]['message']['text'];
$conn = mysqli_connect("172.16.2.78", "atroot", "atrtms@U1", "application") or die("Error, can't connect database");
$db = new PDO("sqlsrv:Server=172.19.32.50\CIMSQL1;Database=Project_tracking_online", "sa", ":b,@,bo1");

$lineWebLink = 'http://th1srwebprd1/MESCENTER1WEBAPI/ActiveLData/Swr/GetDataCustSwr?busItem={"SWR_NO":["' . $message . '"]}';

/////////////////////////
// $sql_check_account = "SELECT * FROM line_user_register
// WHERE line_user_register.userId = 'Ub7bdf5fc0521052f639d6cd31fc926da' AND line_user_register.`status` = 'ACTIVE'";
// $query_check_account = mysqli_query($conn,$sql_check_account) or mysqli_error("Error");
// $num_row = mysqli_num_rows($query_check_account);
// $query_result = mysqli_fetch_assoc($query_check_account);
// print_r($query_result['user_type']);
// exit();
//////////////////////

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient('iLbB6nbkgebIvHjep64Qfnt6+RslJfl7S0SY2tp4aeq17q/vYJKhaV+DIRyjWyK4ZbL/gXy/hmCooiSVyZcI9IoxhmN84y5EUj6CmVvTOoMbhUwjNu9o7bM+S2saftQZ352yR/qWbROC3UBOliyCowdB04t89/1O/w1cDnyilFU=');
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => 'b95c6d9e6b5fc3269094c5838a1ad451']);


$sql_check_account = "SELECT * FROM line_user_register
WHERE line_user_register.userId = '{$id}' AND line_user_register.`status` = 'ACTIVE'";
$query_check_account = mysqli_query($conn, $sql_check_account) or mysqli_error("Error");
$num_row = mysqli_num_rows($query_check_account);
$query_result = mysqli_fetch_assoc($query_check_account);
if ($num_row >= 1) {
    if ($query_result['user_type'] == 'TIU') {
        $found = false;
        if (strlen($message) == 11 && is_numeric($message) && 
            (substr($message, 2, 2) != 01  && substr($message, 2, 2) != 02  && substr($message, 2, 2) != 03
             && substr($message, 2, 2) != 04  && substr($message, 2, 2) != 05  &&substr($message, 2, 2) != 06
             && substr($message, 2, 2) != 07  && substr($message, 2, 2) != 08  && substr($message, 2, 2) != 09
             && substr($message, 2, 2) != 10  && substr($message, 2, 2) != 11  && substr($message, 2, 2) != 12 )) {
            $web = file_get_contents('http://th1srwebprd1/MESCENTER1WEBAPI/ActiveLData/Swr/GetDataCustSwr?busItem=[' . $message . ']');

            $result = json_decode($web, true);
            if (count($result['ListData']) > 0) {

                foreach ($result['ListData'] as $key => $value) {
                    $result_get[$key] = "UTL Lot : " . $value['UTL_LOT'] .
                            "\nSWR no. : " . $value['CUST_REQ_NO'] .
                            "\n🚚 Status : " . $value['SWR_STATUS'] .
                            "\n📦 Qty : " . $value['QTY_IN'] .
                            "\n⏳ ON WIP : " . $value['ON_WIP_OPTN_CODE'] .
                            "\n📝 Expected Complete Date : " . $value['PLAN_COMPLETE_DATE'] .
                            // "no".
                            "\n📑 Invoice/HAWB/Agent : " . $value['INVOICE_NO'];

                    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                    $arrayPostData['messages'][$key]['type'] = "text";
                    $arrayPostData['messages'][$key]['text'] = $result_get[$key];
                }
                $found = true;
                replyMsg($arrayHeader, $arrayPostData);
                $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
            } else {

                $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                $arrayPostData['messages'][0]['type'] = "text";
                $arrayPostData['messages'][0]['text'] = "Hello Sender, Sorry, we can’t find your SWR#. If you confirm that your SWR# is correct, it can be that your SWR# has not been started in UTAC WIP system. Normally, it takes 3 working days for SWR to be started in UTAC WIP. Please contact Apinya apinyako@utacgroup.com ; Pisit pisitte@utacgroup.com to confirm your SWR# status.";
                $found = true;
                replyMsg($arrayHeader, $arrayPostData);
                $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
            }
        } if (strlen($message) == 11 && is_numeric($message) && 
            (substr($message, 2, 2) == 01 || substr($message, 2, 2) == 02 || substr($message, 2, 2) == 03
            || substr($message, 2, 2) == 04 || substr($message, 2, 2) == 05 || substr($message, 2, 2) == 06
            || substr($message, 2, 2) == 07 || substr($message, 2, 2) == 08 || substr($message, 2, 2) == 09
            || substr($message, 2, 2) == 10 || substr($message, 2, 2) == 11 || substr($message, 2, 2) == 12 )) {

            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "You don't have permission to see the information.";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);

        }if ($message == "SWR /BR no.") {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please enter SWR or BR no.";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }if ($message == "Q & A") {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please contact administrator";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }if ($message == "UTL Updates") {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "http://utlnet/";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }if ($message == "Project 4C") {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please enter Project no./Project code name";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }if ($message == 'LF Inventory/Delivery') {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please enter LF UTL stock no.";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        } else if ($found == false) {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please contact administrator";
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }
    }if ($query_result['user_type'] == 'MXM') {

        $found = false;
        if (strlen($message) == 11 && is_numeric($message) && 
            (substr($message, 2, 2) == 01 || substr($message, 2, 2) == 02 || substr($message, 2, 2) == 03
            || substr($message, 2, 2) == 04 || substr($message, 2, 2) == 05 || substr($message, 2, 2) == 06
            || substr($message, 2, 2) == 07 || substr($message, 2, 2) == 08 || substr($message, 2, 2) == 09
            || substr($message, 2, 2) == 10 || substr($message, 2, 2) == 11 || substr($message, 2, 2) == 12 )) {
            $web = file_get_contents('http://th1srwebprd1/MESCENTER1WEBAPI/ActiveLData/Swr/GetDataCustSwr?busItem=[' . $message . ']');
            $result = json_decode($web, true);
            if (count($result['ListData']) > 0) {
                foreach ($result['ListData'] as $key => $value) {

                    $status = '';

                    if ($value['SWR_STATUS'] == 'under review') {
                        $status = 'Not Release';
                    }if ($value['SWR_STATUS'] == 'cancel') {
                        $status = 'Cancel';
                    }if ($value['SWR_STATUS'] == 'started' || $value['SWR_STATUS'] == 'on-hold' || $value['SWR_STATUS'] == 'shipped') {
                        $status = $value['ON_WIP_OPTN_CODE'];
                    }
                    if ($value['UTL_LOT'] != '') {
                        $PT = "\n📑 PT : " . $value['UTL_LOT'];
                    }if ($value['START_DATE'] != '') {
                        $START_DATE = "\n📋 Start : " . $value['START_DATE'];
                    } else {
                        $START_DATE = "";
                    }if ($value['PLAN_COMPLETE_DATE'] != '') {
                        $PLAN_COMPLETE = "\n📋 Complete : " . $value['PLAN_COMPLETE_DATE'];
                    } else {
                        $PLAN_COMPLETE = "";
                    }if ($value['SHIP_DATE'] != '') {
                        if ($status == 'Not Release' || $value['SWR_STATUS'] == 'shipped') {

                            $SHIP_DATE = "\n🚚 Expected Ship Date : " . $value['SHIP_DATE'];
                        } else {
                            $SHIP_DATE = "\n🚚 Ship : " . $value['SHIP_DATE'];
                        }
                    } else {
                        $SHIP_DATE = "";
                    }if ($value['INVOICE_NO'] != '') {
                        $INVOICE_NO = "\n📋 Invoice : " . $value['INVOICE_NO'];
                    } else {
                        $INVOICE_NO = "";
                    }

                    $result_get[$key] = "BR : " . $value['CUST_REQ_NO'] .
                            "\nELR : " . $value['ELR_NO'] .
                            $PT .
                            "\n💻 Cust assy lot : " . $value['CUST_ASSY_LOT'] .
                            "\n📦 Status : " . $status . $START_DATE .
                            $PLAN_COMPLETE . $SHIP_DATE . $INVOICE_NO;


                    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                    $arrayPostData['messages'][$key]['type'] = "text";
                    $arrayPostData['messages'][$key]['text'] = $result_get[$key];
                }
                $found = true;
                replyMsg($arrayHeader, $arrayPostData);
                $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
            }
        } else if (strlen($message) == 11 && is_numeric($message) && 
            (substr($message, 2, 2) != 01 || substr($message, 2, 2) != 02 || substr($message, 2, 2) != 03
            || substr($message, 2, 2) != 04 || substr($message, 2, 2) != 05 || substr($message, 2, 2) != 06
            || substr($message, 2, 2) != 07 || substr($message, 2, 2) != 08 || substr($message, 2, 2) != 09
            || substr($message, 2, 2) != 10 || substr($message, 2, 2) != 11 || substr($message, 2, 2) != 12 )) {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "You don't have permission to see the information.";

            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }if ($message == "SWR /BR no.") {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please enter SWR or BR no.";

            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        } else if ($found == false) {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please contact administrator";
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }

        // ftp_file_put_contents($log_name, print_r($log_msg,true));
    }if ($query_result['user_type'] == 'UTAC') {
        $found = false;
        if (strlen($message) == 11 && is_numeric($message) && 
            (substr($message, 2, 2) != 01 && substr($message, 2, 2) != 02 && substr($message, 2, 2) != 03
            && substr($message, 2, 2) != 04 && substr($message, 2, 2) != 05 && substr($message, 2, 2) != 06
            && substr($message, 2, 2) != 07 && substr($message, 2, 2) != 08 && substr($message, 2, 2) != 09
            && substr($message, 2, 2) != 10 && substr($message, 2, 2) != 11 && substr($message, 2, 2) != 12 )) {
            $web = file_get_contents('http://th1srwebprd1/MESCENTER1WEBAPI/ActiveLData/Swr/GetDataCustSwr?busItem=[' . $message . ']');

            $result = json_decode($web, true);
            if (count($result['ListData']) > 0) {

                foreach ($result['ListData'] as $key => $value) {
                    $result_get[$key] = "UTL Lot : " . $value['UTL_LOT'] .
                            "\nSWR no. : " . $value['CUST_REQ_NO'] .
                            "\n🚚 Status : " . $value['SWR_STATUS'] .
                            "\n📦 Qty : " . $value['QTY_IN'] .
                            "\n⏳ ON WIP : " . $value['ON_WIP_OPTN_CODE'] .
                            "\n📝 Expected Complete Date : " . $value['PLAN_COMPLETE_DATE'] .
                            "\n📑 Invoice/HAWB/Agent : " . $value['INVOICE_NO'];

                    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                    $arrayPostData['messages'][$key]['type'] = "text";
                    $arrayPostData['messages'][$key]['text'] = $result_get[$key];
                }
                $found = true;
                replyMsg($arrayHeader, $arrayPostData);
                $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
            } else {

                $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                $arrayPostData['messages'][0]['type'] = "text";
                $arrayPostData['messages'][0]['text'] = "Hello Sender, Sorry, we can’t find your SWR#. If you confirm that your SWR# is correct, it can be that your SWR# has not been started in UTAC WIP system. Normally, it takes 3 working days for SWR to be started in UTAC WIP. Please contact Apinya apinyako@utacgroup.com ; Pisit pisitte@utacgroup.com to confirm your SWR# status.";
                $found = true;
                replyMsg($arrayHeader, $arrayPostData);
                $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
            }
        } else if (strlen($message) == 11 && is_numeric($message) && 
            (substr($message, 2, 2) == 01 || substr($message, 2, 2) == 02 || substr($message, 2, 2) == 03
            || substr($message, 2, 2) == 04 || substr($message, 2, 2) == 05 || substr($message, 2, 2) == 06
            || substr($message, 2, 2) == 07 || substr($message, 2, 2) == 08 || substr($message, 2, 2) == 09
            || substr($message, 2, 2) == 10 || substr($message, 2, 2) == 11 || substr($message, 2, 2) == 12 )) {
            $web = file_get_contents('http://th1srwebprd1/MESCENTER1WEBAPI/ActiveLData/Swr/GetDataCustSwr?busItem=[' . $message . ']');
            $result = json_decode($web, true);
            if (count($result['ListData']) > 0) {
                foreach ($result['ListData'] as $key => $value) {

                    $status = '';

                    if ($value['SWR_STATUS'] == 'under review') {
                        $status = 'Not Release';
                    }if ($value['SWR_STATUS'] == 'cancel') {
                        $status = 'Cancel';
                    }if ($value['SWR_STATUS'] == 'started' || $value['SWR_STATUS'] == 'on-hold' || $value['SWR_STATUS'] == 'shipped') {
                        $status = $value['ON_WIP_OPTN_CODE'];
                    }
                    if ($value['UTL_LOT'] != '') {
                        $PT = "\n📑 PT : " . $value['UTL_LOT'];
                    }if ($value['START_DATE'] != '') {
                        $START_DATE = "\n📋 Start : " . $value['START_DATE'];
                    } else {
                        $START_DATE = "";
                    }if ($value['PLAN_COMPLETE_DATE'] != '') {
                        $PLAN_COMPLETE = "\n📋 Complete : " . $value['PLAN_COMPLETE_DATE'];
                    } else {
                        $PLAN_COMPLETE = "";
                    }if ($value['SHIP_DATE'] != '') {
                        if ($status == 'Not Release' || $value['SWR_STATUS'] == 'shipped') {

                            $SHIP_DATE = "\n🚚 Expected Ship Date : " . $value['SHIP_DATE'];
                        } else {
                            $SHIP_DATE = "\n🚚 Ship : " . $value['SHIP_DATE'];
                        }
                    } else {
                        $SHIP_DATE = "";
                    }if ($value['INVOICE_NO'] != '') {
                        $INVOICE_NO = "\n📋 Invoice : " . $value['INVOICE_NO'];
                    } else {
                        $INVOICE_NO = "";
                    }

                    $result_get[$key] = "BR : " . $value['CUST_REQ_NO'] .
                            "\nELR : " . $value['ELR_NO'] .
                            $PT .
                            "\n💻 Cust assy lot : " . $value['CUST_ASSY_LOT'] .
                            "\n📦 Status : " . $status . $START_DATE .
                            $PLAN_COMPLETE . $SHIP_DATE . $INVOICE_NO;


                    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                    $arrayPostData['messages'][$key]['type'] = "text";
                    $arrayPostData['messages'][$key]['text'] = $result_get[$key];
                }
                $found = true;
                replyMsg($arrayHeader, $arrayPostData);
                $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
            }
        }else if (strlen($message) == 13 && (substr($message, 0, 1) == 'T'|| substr($message, 0, 1) == 't')) {
            //Test T381946SD0721
            $message = strtoupper($arrayJson['events'][0]['message']['text']);
            $web = file_get_contents('http://th1srwebprd1/MESCENTER1WEBAPI/ActiveLData/Swr/GetDataCustSwr?busItem={"SWR_NO":["' . $message . '"]}');

            $result = json_decode($web, true);
            if (count($result['ListData']) > 0) {

                foreach ($result['ListData'] as $key => $value) {

                ////pack and test///
                // if($value[''] != ''){
                //     $PT = "\n📑 PT : ". $value['UTL_LOT'];
                // }

                    $result_get[$key] = "Trial# : " . $message .
                        // "\n📋 Lot ID : ".$value['CUST_ASSY_LOT'].
                    "\n📦 UTL Lot : " . $value['UTL_LOT'] .
                    "\n⏳ Status : " . $value['WIP_LOT_STATUS'] .
                    "\n📝 Assy Start : " . $value['START_DATE'] .
                    "\n📝 Assy Out : " . $value['PLAN_COMPLETE_DATE'] .
                    "\n🚚 SOD : " . $value['SHIP_DATE'] .
                    "\n📑 Invoice : " . $value['INVOICE_NO'];

                    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                    $arrayPostData['messages'][$key]['type'] = "text";
                    $arrayPostData['messages'][$key]['text'] = $result_get[$key];
                }
                $found = true;
                replyMsg($arrayHeader, $arrayPostData);
                $log_msg= CreateLogContent($log_name,$id,$message,$arrayPostData);
            } else {

                $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                $arrayPostData['messages'][0]['type'] = "text";
                $arrayPostData['messages'][0]['text'] = "Hello Sender, Sorry, we can’t find your Trial#. If you confirm that your Trial# is correct, it can be that your Trial# has not been started in UTAC WIP system. Normally, it takes 3 working days for Trial# to be started in UTAC WIP. Please contact Saranya saranyaji@utacgroup.com ; Sarayut sarayutch@utacgroup.com to confirm your Trial# status.";
                $found = true;
                replyMsg($arrayHeader, $arrayPostData);
                $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
            }
        }if ($message == "SWR /BR no.") {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please enter SWR or BR no.";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }if ($message == "Q & A") {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please contact administrator";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }if ($message == "UTL Updates") {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "http://utlnet/";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }if ($message == "Project 4C") {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please enter Project no./Project code name";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }if ($message == 'LF Inventory/Delivery') {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please enter LF UTL stock no.";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        } else if ($found == false) {
            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Please contact administrator";
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }
    }
}if ($query_result['user_type'] == 'STM') {

    if (strlen($message) == 13 && (substr($message, 0, 1) == 'T'|| substr($message, 0, 1) == 't')) {
        $message = strtoupper($arrayJson['events'][0]['message']['text']);
        $web = file_get_contents('http://th1srwebprd1/MESCENTER1WEBAPI/ActiveLData/Swr/GetDataCustSwr?busItem=["' . $message . '"]');

        $result = json_decode($web, true);
        if (count($result['ListData']) > 0) {

            foreach ($result['ListData'] as $key => $value) {

                ////pack and test///
                // if($value[''] != ''){
                //     $PT = "\n📑 PT : ". $value['UTL_LOT'];
                // }

                $result_get[$key] = "Trial# : " . $message .
                        // "\n📋 Lot ID : ".$value['CUST_ASSY_LOT'].
                        "\n📦 UTL Lot : " . $value['UTL_LOT'] .
                        "\n⏳ Status : " . $value['WIP_LOT_STATUS'] .
                        "\n📝 Assy Start : " . $value['START_DATE'] .
                        "\n📝 Assy Out : " . $value['PLAN_COMPLETE_DATE'] .
                        "\n🚚 SOD : " . $value['SHIP_DATE'] .
                        "\n📑 Invoice : " . $value['INVOICE_NO'];

                $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                $arrayPostData['messages'][$key]['type'] = "text";
                $arrayPostData['messages'][$key]['text'] = $result_get[$key];
            }
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg= CreateLogContent($log_name,$id,$message,$arrayPostData);
        } else {

            $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
            $arrayPostData['messages'][0]['type'] = "text";
            $arrayPostData['messages'][0]['text'] = "Hello Sender, Sorry, we can’t find your Trial#. If you confirm that your Trial# is correct, it can be that your Trial# has not been started in UTAC WIP system. Normally, it takes 3 working days for Trial# to be started in UTAC WIP. Please contact Saranya saranyaji@utacgroup.com ; Sarayut sarayutch@utacgroup.com to confirm your Trial# status.";
            $found = true;
            replyMsg($arrayHeader, $arrayPostData);
            $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
        }
    }if ($message == 'Trial no.') {
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "Please enter Trial No.";
        $found = true;
        replyMsg($arrayHeader, $arrayPostData);
        $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
    }if ($message == 'Help') {
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = "Please contact administrator";
        $found = true;
        replyMsg($arrayHeader, $arrayPostData);
        $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
    }
} else {
    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = "You don't have permission to see the information.";

    replyMsg($arrayHeader, $arrayPostData);
    $log_msg = CreateLogContent($log_name, $id, $message, $arrayPostData);
}if ($message == "Q & A") {
    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = "Please contact administrator";
    replyMsg($arrayHeader, $arrayPostData);
}if ($message == "UTL Updates") {
    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = "http://utlnet/";
    replyMsg($arrayHeader, $arrayPostData);
}if ($message == "Project 4C") {
    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = "Please enter Project no./Project code name";
    replyMsg($arrayHeader, $arrayPostData);
}if ($message == 'LF Inventory/Delivery') {
    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = "Please enter LF UTL stock no.";
    replyMsg($arrayHeader, $arrayPostData);
}if ($message == 'FR1414') {

    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = "407,000 EA";
    replyMsg($arrayHeader, $arrayPostData);
}
if ($message == 'FR1287') {
    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = "509,000 EA";
    replyMsg($arrayHeader, $arrayPostData);
}
if (substr($message, 0, 2) == '4C' || substr($message, 0, 2) == '4c') {
    $codename = str_replace(' ', '', substr($message, 2));
    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = "https://fb02265d.ngrok.io/4c_V1.php?codename={$codename}";
    replyMsg($arrayHeader, $arrayPostData);
}
if ($message == "ดี") {
    // $arrayPostData['to'] = $id;
    $response = $bot->getProfile($id);
    if ($response->isSucceeded()) {

        $profile = $response->getJSONDecodedBody();
    }

    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = $Name;
    $arrayPostData['messages'][1]['type'] = "sticker";
    $arrayPostData['messages'][1]['packageId'] = "1";
    $arrayPostData['messages'][1]['stickerId'] = "131";

    replyMsg($arrayHeader, $arrayPostData);
}

function SWRreturn() {
    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = "Please enter SWR or BR no.";
    replyMsg($arrayHeader, $arrayPostData);
}

function CreateLogContent($log_name, $id, $message, $arrayPostData) {
    $log_msg = array(
        "datetime" => date('Y-m-d h:i:sa'),
        // "name" => $query_result['name'],
        // "user_type" => $query_result['user_type'],
        "userID" => $id,
        "msg" => $message,
        "response" => $arrayPostData['messages']
    );
    ftp_file_put_contents($log_name, print_r($log_msg, true));
    return true;
}

function ftp_file_put_contents($remote_file, $file_string) {
// FTP login details
    $ftp_server = '127.0.0.1';
    $ftp_user_name = 'root';
    $ftp_user_pass = 'root';

// Create temporary file
    $local_file = fopen($remote_file, 'a+');
    fwrite($local_file, $file_string . "////////////////////////////////////////////////////////////\r\n");
    rewind($local_file);

// FTP connection
    $ftp_conn = ftp_connect($ftp_server);

// FTP login
    @$login_result = ftp_login($ftp_conn, $ftp_user_name, $ftp_user_pass);

// FTP upload
    if ($login_result)
        $upload_result = ftp_fput($ftp_conn, $remote_file, $local_file, FTP_ASCII);

// Error handling
    if (!$login_result or ! $upload_result) {
        echo('<p>FTP error: The file could not be written to the FTP server.</p>');
    }

// Close FTP connection
    ftp_close($ftp_conn);

// Close file handle
    fclose($local_file);
}

function replyMsg($arrayHeader, $arrayPostData) {
    $strUrl = "https://api.line.me/v2/bot/message/reply";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $strUrl);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayPostData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
}

function send_notify_message($message_data, $accessToken) {

    $headers = array('Method: GET', 'Content-type: multipart/form-data', 'Authorization: Bearer ' . $accessToken);
    $strUrl = "https://api.line.me/v2/bot/message/reply";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $strUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $message_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    // Check Error
    if (curl_error($ch)) {
        $return_array = array('status' => '000: send fail', 'message' => curl_error($ch));
    } else {
        $return_array = json_decode($result, true);
    }
    curl_close($ch);
    return $return_array;
}

exit;
?>