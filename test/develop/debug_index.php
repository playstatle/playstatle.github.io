<?php
//phpinfo();exit();
ini_set("display_errors", 1);
    
//$msg = "First line of text\nSecond line of text";
//
//// use wordwrap() if lines are longer than 70 characters
//$msg = wordwrap($msg,70);
//
//// send email
//$send_mail = mail("tapanutak@utacgroup.com,tapanut.akk@gmail.com","My subject",$msg);
//
//if($send_mail){
//    echo("send succesfully.");
//} else {
//    echo("send fail.");
//}
//exit();

function dd($arr){
    echo("<pre>");
    print_r($arr);
    exit();
}
require "vendor/autoload.php";
require_once('line-bot/line-bot-sdk-php-master/src/LINEBot.php');
date_default_timezone_set("Asia/Bangkok");

// require_once '../vendor/autoload.php';
// $accessToken = "FBhFsHIOv/q6QqOgkgGesE4rM3fdLzdQymHbmcxRSM78LwCIASJZ6dlwzgm394QqXhsVpoUtqCtJCNYXij/gOjdFdkMQAzfVNMLrg5xuwuocDMRZXrUM1PSFjt2mj9SSP5w1R9x/9G4RItHZxgXP+gdB04t89/1O/w1cDnyilFU="; 
//copy Channel access token ตอนที่ตั้งค่ามาใส่
$accessToken = "kdYcIotJg92FjXWiwPobFpk8eidj/BtsFafxvE5UUiYRnGRqKxOXqHV+uIKbyvPNq23W5u5kHiuhYeduf67X8ucPeRDP/fRPaQ1Tx9/I59agwghMq2lt/IJODS2vJ6vtWwdUcsJtRigBjC4hFMBr4QdB04t89/1O/w1cDnyilFU=";

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
//$log_name = "log/UTAC_Anywhere_log_" . date('Y-m-d') . ".txt";
// $log_name = "UTAC_Anywhere_log_2019-10-22.txt";
//รับข้อความจากผู้ใช้
$id = $arrayJson['events'][0]['source']['userId'];
// $Name = $arrayJson['events'][0]['source']['userId'];

$message = str_replace('#','%23',strtoupper($arrayJson['events'][0]['message']['text']));//'JQ40001A';
//$message = strtoupper($arrayJson['events'][0]['message']['text']);
$conn = mysqli_connect("utlrms1", "atroot", "atrtms@U1", "application") or die("Error, can't connect database");
//$conn = mysqli_connect("172.16.2.78", "atroot", "atrtms@U1", "application") or die("Error, can't connect database");
//$db = new PDO("sqlsrv:Server=172.19.32.50\CIMSQL1;Database=Project_tracking_online", "sa", ":b,@,bo1");

/////////////////////////
// $sql_check_account = "SELECT * FROM line_user_register
// WHERE line_user_register.userId = 'Ub7bdf5fc0521052f639d6cd31fc926da' AND line_user_register.`status` = 'ACTIVE'";
// $query_check_account = mysqli_query($conn,$sql_check_account) or mysqli_error("Error");
// $num_row = mysqli_num_rows($query_check_account);
// $query_result = mysqli_fetch_assoc($query_check_account);
// print_r($query_result['user_type']);
// exit();
//////////////////////

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($accessToken);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => 'b95c6d9e6b5fc3269094c5838a1ad451']);

$sql_check_account = "SELECT * FROM line_user_register
WHERE line_user_register.userId = '{$id}' AND line_user_register.`status` = 'ACTIVE'";
$query_check_account = mysqli_query($conn, $sql_check_account) or mysqli_error("Error");
$num_row = mysqli_num_rows($query_check_account);
$query_result = mysqli_fetch_assoc($query_check_account);
if ($num_row >= 1) {
    
    $user_type = $query_result['user_type'];
    if($user_type === 'UTAC' || $user_type === 'UTACG'){
        $message_arr = explode(",", trim($message));
        $user_type = $message_arr[0];
        $message = $message_arr[1];
    }

    switch($user_type){
        case 'EMS':
            $data = '{"PO_NO":"' . str_replace(' ','%20',$message) . '"}';
            break;
        case 'AKM':
        case 'ADI':
        case 'MUR':
        case 'AL9':
        case 'DW2':
        case 'SS0':
        case 'NP3':
        case 'IDT':
        case 'CYP':
        case 'DL1':
        case 'SL6':
            $data = '{"CUST_ASSY_LOT":"' . str_replace(' ','%20',$message) . '"}';
            break;
        default:
            $data = '{"SWR_NO":["' . str_replace(' ','%20',$message) . '"]}';
            break;
    }
    
    $lineWebLink = "http://th1srwebprd1/MESCENTER1WEBAPI/ActiveLData/Swr/GetDataCustSwr?busItem=$data";
//    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
//    $arrayPostData['messages'][0]['type'] = "text";
//    $arrayPostData['messages'][0]['text'] = $lineWebLink;
//    replyMsg($arrayHeader, $arrayPostData);
//    exit();
    
    $web = file_get_contents($lineWebLink);
    
    $result = json_decode($web, true);
    
    if(!$result){
//        CreateLogDB($conn,$id,$query_result['user_type'],$message,"System error, please try later or contact at apinyako@utacgroup.com if any support needed..");
//        CreateLogContent($log_name, $id, $message, "System error, please try later.");
        
        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = 'System error, please try later or contact at apinyako@utacgroup.com if any support needed.';
        
        replyMsg($arrayHeader, $arrayPostData);
        exit();
    }
    
    $count_result = count($result['ListData']);
    
    if ($count_result > 0) {

        $found = true;
        $result_key = 0;
        $result_get = '';
        $customer_type = null;
        
        $not_duplicate_result = unique_multidim_array($result['ListData'],'UTL_LOT');
        
        foreach ($not_duplicate_result as $key => $value) {

            if ($query_result['user_type'] === 'UTAC' || $query_result['user_type'] === 'UTACG' || ($query_result['user_type'] === 'IFX' && ($value['CUST_CODE'] === 'IFX' || $value['CUST_CODE'] === 'IN6')) || $query_result['user_type'] === $value['CUST_CODE']) {

                $customer_type = $value['CUST_CODE'];
                switch($value['CUST_CODE']){
                    case 'TIU':
                        $result_get .= "UTL Lot : " . $value['UTL_LOT'] .
                            "\nSWR no. : " . $value['CUST_REQ_NO'] .
                            "\n📋 Cust assy lot : " . $value['CUST_ASSY_LOT'] .
                            "\n🚚 Status : " . $value['SWR_STATUS'] .
                            "\n📦 Qty : " . $value['QTY_IN'] .
                            "\n⏳ ON WIP : " . $value['ON_WIP_OPTN_CODE'] .
                            "\n📝 Expected Complete Date : " . $value['PLAN_COMPLETE_DATE'] .
                            // "no".
                            "\n📑 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        break;
                    case 'MXM':
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

                        $result_get .= "BR : " . $value['CUST_REQ_NO'] .
                                "\nELR : " . $value['ELR_NO'] .
                                $PT .
                                "\n💻 Cust assy lot : " . $value['CUST_ASSY_LOT'] .
                                "\n📦 Status : " . $status . $START_DATE .
                                $PLAN_COMPLETE . $SHIP_DATE . $INVOICE_NO."\n";
                        break;
                    case 'ONS':
                        $EBR_NO = "";
                        $UTL_LOT = "";
                        $CUST_ASSY_LOT = "";
                        $CURRENT_QTY = "";
                        $WIP_LOT_STATUS = "";
                        $ASSY_OUT_DATE = "";
                        $INVOICE_NO = "";

                        if ($value['CUST_REQ_NO'] != '') {
                            $EBR_NO = "📑 EBR no : " . $value['CUST_REQ_NO']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $UTL_LOT = "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $CUST_ASSY_LOT = "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $CURRENT_QTY = "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['WIP_LOT_STATUS'] != '') {
                            $WIP_LOT_STATUS = "🚚 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }
                        
                        if ($value['ASSY_OUT_DATE'] != '') {
                            $ASSY_OUT_DATE = "📋 Expected Complete Date : " . $value['ASSY_OUT_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $INVOICE_NO = "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }

                        $result_get .= $EBR_NO.$UTL_LOT.$CUST_ASSY_LOT.$CURRENT_QTY.$WIP_LOT_STATUS.$ASSY_OUT_DATE.$INVOICE_NO;
                        break;
                    case 'MCH':
                        $CUST_REQ_NO = "";
                        $UTL_LOT = "";
                        $CUST_ASSY_LOT = "";
                        $SWR_STATUS = "";
                        $ON_WIP_OPTN_CODE = "";
                        $CURRENT_QTY = "";
                        $INVOICE_NO = "";

                        if ($value['CUST_REQ_NO'] != '') {
                            $CUST_REQ_NO = "📑 AI# : " . $value['CUST_REQ_NO']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $UTL_LOT = "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $CUST_ASSY_LOT = "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }

                        if ($value['SWR_STATUS'] != '') {
                            $SWR_STATUS = "📋 Status# : " . $value['SWR_STATUS']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $ON_WIP_OPTN_CODE = "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $CURRENT_QTY = "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $INVOICE_NO = "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }

                        $result_get .= $CUST_REQ_NO.$UTL_LOT.$CUST_ASSY_LOT.$SWR_STATUS.$ON_WIP_OPTN_CODE.$CURRENT_QTY.$INVOICE_NO;
                        break;
                    case 'PII':
                        $CUST_REQ_NO = "";
                        $UTL_LOT = "";
                        $CUST_ASSY_LOT = "";
                        $ELR_NO = "";
                        $SWR_STATUS = "";
                        $CURRENT_QTY = "";
                        $ASSY_OUT_DATE = "";
                        $INVOICE_NO = "";

                        if ($value['CUST_REQ_NO'] != '') {
                            $CUST_REQ_NO = "📑 EBO# : " . $value['CUST_REQ_NO']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $UTL_LOT = "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $CUST_ASSY_LOT = "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }

                        if ($value['ELR_NO'] != '') {
                            $ELR_NO = "📋 ELR# : " . $value['ELR_NO']."\n";
                        }

                        if ($value['SWR_STATUS'] != '') {
                            $SWR_STATUS = "📋 Status# : " . $value['SWR_STATUS']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $CURRENT_QTY = "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['ASSY_OUT_DATE'] != '') {
                            $ASSY_OUT_DATE = "📋 Assy out : " . $value['ASSY_OUT_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $INVOICE_NO = "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }

                        $result_get .= $CUST_REQ_NO.$UTL_LOT.$CUST_ASSY_LOT.$ELR_NO.$SWR_STATUS.$CURRENT_QTY.$ASSY_OUT_DATE.$INVOICE_NO;
                        break;
                    case 'IFX':
                    case 'IN6':
                        $CUST_REQ_NO = "";
                        $UTL_LOT = "";
                        $CUST_ASSY_LOT = "";
                        $SWR_STATUS = "";
                        $CURRENT_QTY = "";
                        $SHIP_DATE = "";
                        $INVOICE_NO = "";
                        $ON_WIP_OPTN_CODE = "";

                        if ($value['CUST_REQ_NO'] != '') {
                            $CUST_REQ_NO = "📑 SPO# : " . $value['CUST_REQ_NO']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $UTL_LOT = "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $CUST_ASSY_LOT = "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }

                        if ($value['SWR_STATUS'] != '') {
                            $SWR_STATUS = "📋 Status# : " . $value['SWR_STATUS']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $ON_WIP_OPTN_CODE = "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $CURRENT_QTY = "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['SHIP_DATE'] != '') {
                            $SHIP_DATE = "📋 SOD1 : " . $value['SHIP_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $INVOICE_NO = "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }

                        $result_get .= $CUST_REQ_NO.$UTL_LOT.$CUST_ASSY_LOT.$SWR_STATUS.$ON_WIP_OPTN_CODE.$CURRENT_QTY.$SHIP_DATE.$INVOICE_NO;
                        break;
                    case 'NXP':
                        $CUST_REQ_NO = "";
                        $UTL_LOT = "";
                        $CUST_ASSY_LOT = "";
                        $ELR_NO = "";
                        $SWR_STATUS = "";
                        $CURRENT_QTY = "";
                        $SHIP_DATE = "";
                        $INVOICE_NO = "";
                        $ON_WIP_OPTN_CODE = "";

                        if ($value['CUST_REQ_NO'] != '') {
                            $CUST_REQ_NO = "📑 STR# : " . $value['CUST_REQ_NO']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $UTL_LOT = "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $CUST_ASSY_LOT = "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }

                        if ($value['ELR_NO'] != '') {
                            $ELR_NO = "📋 ELR# : " . $value['ELR_NO']."\n";
                        }

                        if ($value['SWR_STATUS'] != '') {
                            $SWR_STATUS = "📋 Status# : " . $value['SWR_STATUS']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $ON_WIP_OPTN_CODE = "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $CURRENT_QTY = "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['SHIP_DATE'] != '') {
                            $SHIP_DATE = "📋 SOD : " . $value['SHIP_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $INVOICE_NO = "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }

                        $result_get .= $CUST_REQ_NO.$UTL_LOT.$CUST_ASSY_LOT.$ELR_NO.$SWR_STATUS.$ON_WIP_OPTN_CODE.$CURRENT_QTY.$SHIP_DATE.$INVOICE_NO;
                        break;
                    case 'ISL':
                        $CUST_REQ_NO = "";
                        $UTL_LOT = "";
                        $CUST_ASSY_LOT = "";
                        $SWR_STATUS = "";
                        $CURRENT_QTY = "";
                        $SHIP_DATE = "";
                        $INVOICE_NO = "";

                        if ($value['CUST_REQ_NO'] != '') {
                            $CUST_REQ_NO = "📑 EBR# : " . $value['CUST_REQ_NO']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $UTL_LOT = "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $CUST_ASSY_LOT = "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }

                        if ($value['SWR_STATUS'] != '') {
                            $SWR_STATUS = "📋 Status# : " . $value['SWR_STATUS']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $CURRENT_QTY = "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['SHIP_DATE'] != '') {
                            $SHIP_DATE = "📋 SOD : " . $value['SHIP_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $INVOICE_NO = "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }

                        $result_get .= $CUST_REQ_NO.$UTL_LOT.$CUST_ASSY_LOT.$SWR_STATUS.$CURRENT_QTY.$SHIP_DATE.$INVOICE_NO;
                        break;
                    case 'STM':
                        $result_get .= "Trial# : " . $message .
                            // "\n📋 Lot ID : ".$value['CUST_ASSY_LOT'].
                            "\n📦 UTL Lot : " . $value['UTL_LOT'] .
                            "\n⏳ Status : " . $value['WIP_LOT_STATUS'] .
                            "\n📝 Assy Start : " . $value['START_DATE'] .
                            "\n📝 Assy Out : " . $value['PLAN_COMPLETE_DATE'] .
                            "\n🚚 SOD : " . $value['SHIP_DATE'] .
                            "\n📑 Invoice : " . $value['INVOICE_NO']."\n";
                        break;
                    case 'EMS':

                        $result_get = "";

                        if ($value['PO_NO'] != '') {
                            $result_get .= "📑 PO : " . $value['PO_NO']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $result_get .= "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $result_get .= "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }

                        if ($value['ELR_NO'] != '') {
                            $result_get .= "📋 ELR : " . $value['ELR_NO']."\n";
                        }

                        if ($value['SWR_STATUS'] != '') {
                            $result_get .= "📋 Status# : " . $value['SWR_STATUS']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $result_get .= "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $result_get .= "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['SHIP_DATE'] != '') {
                            $result_get .= "📋 SOD : " . $value['SHIP_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $result_get .= "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }
                        break;
                    case 'AKM':
                    
                        $result_get = "";

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $result_get .= "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $result_get .= "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['SWR_STATUS'] != '') {
                            $result_get .= "📋 Status# : " . $value['SWR_STATUS']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $result_get .= "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $result_get .= "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['SHIP_DATE'] != '') {
                            $result_get .= "📋 SOD : " . $value['SHIP_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $result_get .= "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }
                        break;
                    case 'ADI':
                
                        $result_get = "";

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $result_get .= "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $result_get .= "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['CUST_REQ_NO'] != '') {
                            $result_get .= "📑 SLR : " . $value['CUST_REQ_NO']."\n";
                        }

                        if ($value['ELR_NO'] != '') {
                            $result_get .= "📋 ELR : " . $value['ELR_NO']."\n";
                        }

                        if ($value['SWR_STATUS'] != '') {
                            $result_get .= "📋 Status# : " . $value['SWR_STATUS']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $result_get .= "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $result_get .= "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['SHIP_DATE'] != '') {
                            $result_get .= "📋 SOD : " . $value['SHIP_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $result_get .= "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }
                        break;
                    case 'MUR':
            
                        $result_get = "";

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $result_get .= "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $result_get .= "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['PO_NO'] != '') {
                            $result_get .= "📑 PO : " . $value['PO_NO']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $result_get .= "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['SHIP_DATE'] != '') {
                            $result_get .= "📋 SOD : " . $value['SHIP_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $result_get .= "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }
                        break;
                    case 'AL9':
                    case 'IDT':
                    case 'CYP':
        
                        $result_get = "";

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $result_get .= "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $result_get .= "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $result_get .= "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['SHIP_DATE'] != '') {
                            $result_get .= "📋 SOD : " . $value['SHIP_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $result_get .= "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }
                        break;
                    case 'DW2':
    
                        $result_get = "";

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $result_get .= "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $result_get .= "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $result_get .= "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $result_get .= "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['SHIP_DATE'] != '') {
                            $result_get .= "📋 SOD : " . $value['SHIP_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $result_get .= "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }
                        break;
                    case 'SS0':
                    case 'NP3':

                        $result_get = "";

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $result_get .= "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $result_get .= "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['ELR_NO'] != '') {
                            $result_get .= "📋 ELR : " . $value['ELR_NO']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $result_get .= "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $result_get .= "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['SHIP_DATE'] != '') {
                            $result_get .= "📋 SOD : " . $value['SHIP_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $result_get .= "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }
                        break;
                    case 'DL1':

                        $result_get = "";

                        if ($value['PO_NO'] != '') {
                            $result_get .= "📑 PO : " . $value['PO_NO']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $result_get .= "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $result_get .= "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }

                        if ($value['ELR_NO'] != '') {
                            $result_get .= "📋 ELR : " . $value['ELR_NO']."\n";
                        }

                        if ($value['SWR_STATUS'] != '') {
                            $result_get .= "📋 Status# : " . $value['SWR_STATUS']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $result_get .= "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $result_get .= "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['ASSY_OUT_DATE'] != '') {
                            $result_get .= "📋 Expected Complete Date : " . $value['ASSY_OUT_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $result_get .= "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }
                        break;
                    case 'SL6':

                        $result_get = "";

                        if ($value['PO_NO'] != '') {
                            $result_get .= "📑 PO : " . $value['PO_NO']."\n";
                        }
                        
                        if ($value['UTL_LOT'] != '') {
                            $result_get .= "📑 UTL Lot : " . $value['UTL_LOT']."\n";
                        }

                        if ($value['CUST_ASSY_LOT'] != '') {
                            $result_get .= "📋 Cust assy lot : " . $value['CUST_ASSY_LOT']."\n";
                        }

                        if ($value['SWR_STATUS'] != '') {
                            $result_get .= "📋 Status# : " . $value['SWR_STATUS']."\n";
                        }

                        if ($value['ON_WIP_OPTN_CODE'] != '') {
                            $result_get .= "📋 ON WIP : " . $value['ON_WIP_OPTN_CODE']."\n";
                        }

                        if ($value['CURRENT_QTY'] != '') {
                            $result_get .= "📋 Qty : " . $value['CURRENT_QTY']."\n";
                        }

                        if ($value['ASSY_OUT_DATE'] != '') {
                            $result_get .= "📋 Expected Complete Date : " . $value['ASSY_OUT_DATE']."\n";
                        }

                        if ($value['INVOICE_NO'] != '') {
                            $result_get .= "📋 Invoice/HAWB/Agent : " . $value['INVOICE_NO']."\n";
                        }
                        break;
                    default:
                        break;
                }

                if($key < $count_result - 1){
                    $result_get .= "\n";
                }

                $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                $arrayPostData['messages'][$result_key]['type'] = "text";
                $arrayPostData['messages'][$result_key]['text'] = $result_get;
                
                if((($key + 1) % 8 === 0)){

                    $result_key++;
                    $result_get = '';

                }

            } elseif($query_result['user_type'] !== 'UTAC' && $query_result['user_type'] !== $value['CUST_CODE']) {

                $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
                $arrayPostData['messages'][0]['type'] = "text";
                $arrayPostData['messages'][0]['text'] = "You don't have permission to see the information.";
                
//                CreateLogDB($conn,$id,$query_result['user_type'],$message,"You don't have permission to see the information.");
                replyMsg($arrayHeader, $arrayPostData);
                exit();

            }
                
        }
//        CreateLogDB($conn,$id,$query_result['user_type'],$message,$result_get,$customer_type);
        replyMsg($arrayHeader, $arrayPostData);

    } else {

        switch($message){
            case 'SWR /BR no.':
                $text = "Please enter your request#";
                break;
            case 'Q & A':
            case 'Help':
                $text = "Please contact administrator";
                break;
            case 'UTL Updates':
                $text = "http://utlnet/";
                break;
            case 'Project 4C':
                $text = "Please enter Project no./Project code name";
                break;
            case 'LF Inventory/Delivery':
                $text = "Please enter LF UTL stock no.";
                break;
            case 'Trial no.':
                $text = "Please enter Trial No.";
                break;
            case 'FR1414':
                $text = "407,000 EA";
                break;
            case 'FR1287':
                $text = "509,000 EA";
                break;
            case 'ดี':
                $text = $id;
                break;
            default:
                if(substr($message, 0, 2) == '4C' || substr($message, 0, 2) == '4c') {
                    $codename = str_replace(' ', '', substr($message, 2));
                    $text = "https://2a67a658.ngrok.io/develop/4C_V1.php?codename={$codename}";
                } else if($found == false){
                    switch($user_type){
                        case 'TIU':
                            $contact = 'Apinya apinyako@utacgroup.com ; Monsicha monsichath@utacgroup.com';
                            break;
                        case 'MXM':
                            $contact = 'Nutthaporn nutthapornka@utacgroup.com ; Thanasub thanasubth@utacgroup.com';
                            break;
                        case 'STM':
                            $contact = 'Saranya saranyaji@utacgroup.com ; Sarayut sarayutch@utacgroup.com';
                            break;
                        case 'EMS':
                        case 'SS0':
                            $contact = 'Kanchana kanchanaku@utacgroup.com ; Thanapon thanaponng@utacgroup.com';
                            break;
                        case 'ONS':
                            $contact = 'Piyapatch piyapatchni@utacgroup.com';
                            break;
                        case 'AKM':
                            $contact = 'Thanakan thanakanin@utacgroup.com ; Siraprapa siraprapapa@utacgroup.com';
                            break;
                        case 'DL1':
                        case 'SL6':
                            $contact = 'Surachet surachetpa@utacgroup.com ; Pornthip pornthipta@utacgroup.com';
                            break;
                        case 'MCH':
                            $contact = 'Lerdvaritsara lerdvaritsarawa@utacgroup.com ; Thanasub thanasubth@utacgroup.com';
                            break;
                        case 'PII':
                            $contact = 'Surachet surachetpa@utacgroup.com';
                            break;
                        case 'ADI':
                        case 'LTC':
                        case 'NP3':
                            $contact = 'Tarntip tarntipbe@utacgroup.com ; Nattakan nattakanch@utacgroup.com';
                            break;
                        case 'IFX':
                            $contact = 'Thitipong thitipongyo@utacgroup.com ; Sarayut sarayutch@th.utacgroup.com';
                            break;
                        case 'MUR':
                            $contact = 'Worawid worawidsa@utacgroup.com ; Kotchamas kotchamasma@utacgroup.com ; Siraprapa siraprapapa@utacgroup.com';
                            break;
                        case 'AL9':
                        case 'CYP':
                            $contact = 'Wenceslao wenceslaoci@utacgroup.com';
                            break;
                        case 'NXP':
                            $contact = 'Charunan charunanya@utacgroup.com ; Nattakan nattakanch@utacgroup.com';
                            break;
                        case 'DW2':
                            $contact = 'Kanokwan kanokwanfa@utacgroup.com ; Piyapatch piyapatchni@utacgroup.com';
                            break;
                        case 'IDT':
                        case 'ISL':
                            $contact = 'Ekalak ekalakkl@utacgroup.com';
                            break;
                        default:
                            $contact = 'Apinya apinyako@utacgroup.com ; Pisit pisitte@utacgroup.com';
                            break;
                    }
                    $text = "Hello Sender, Sorry, we can’t find your Request#. If you confirm that your Request# is correct, it can be that your Request# has not been started in UTAC WIP system. Please give us 3 working days for your request to be started in UTAC WIP. You may contact $contact to confirm your Request# status.";
                } else {
                    $text = "Please contact administrator";
                }
                break;
        }

        $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
        $arrayPostData['messages'][0]['type'] = "text";
        $arrayPostData['messages'][0]['text'] = $text;

        if($message === 'ดี'){
            $arrayPostData['messages'][1]['type'] = "sticker";
            $arrayPostData['messages'][1]['packageId'] = "1";
            $arrayPostData['messages'][1]['stickerId'] = "131";
        }
        
        $found = true;
//        CreateLogDB($conn,$id,$query_result['user_type'],$message,$text);
        replyMsg($arrayHeader, $arrayPostData);

    }
    
}

function SWRreturn() {
    $arrayPostData['replyToken'] = $arrayJson['events'][0]['replyToken'];
    $arrayPostData['messages'][0]['type'] = "text";
    $arrayPostData['messages'][0]['text'] = "Please enter SWR or BR no.";
    replyMsg($arrayHeader, $arrayPostData);
}

function CreateLogContent($log_name, $id, $message, $text) {
    $log_msg = '';
    if (!file_exists($log_name)) {
        $log_msg .= "datetime|user_id|input|response\n";
    }
    $log_msg .= date('h:i:sa')."|$id|$message|$text\n";
    ftp_file_put_contents($log_name, $log_msg);
    return true;
}

function CreateLogDB($conn,$user_id,$user_type,$input,$output,$customer_type=null){
    $query = "INSERT INTO line_access_log(user_id,user_type,input,output,customer_type) VALUE ('$user_id','$user_type','$input','$output','$customer_type')";
    return mysqli_query($conn, $query) or mysqli_error($conn);
}

function ftp_file_put_contents($remote_file, $file_string) {
// FTP login details
    $ftp_server = '127.0.0.1';
    $ftp_user_name = 'root';
    $ftp_user_pass = 'root';

// Create temporary file
    $local_file = fopen($remote_file, 'a+');
    fwrite($local_file, $file_string);
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

function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();
   
    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

exit();