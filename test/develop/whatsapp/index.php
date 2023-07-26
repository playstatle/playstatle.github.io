<?php

require "vendor/autoload.php";
require_once "vendor/twilio/sdk/src/Twilio/TwiML/MessagingResponse.php";
date_default_timezone_set("Asia/Bangkok");

$found = false;
$response = new Twilio\Twiml\MessagingResponse;
$id = str_replace("whatsapp:+","",$_REQUEST['From']);
$content = file_get_contents('php://input');
$arrayJson = json_decode($content, true);
$result = array();
$log_msg = array();
$arrayPostData = array();
$message = $_REQUEST['Body'];

$con=mysqli_connect("127.0.0.1", "root","","test") or die("Error, can't connect database");
mysqli_set_charset($con,"utf8"); 
$sql = "Select * From whatsapp_login where userId='+{$id}' AND whatsapp_login.status = 'ACTIVE'";
$result = mysqli_query($con,$sql) or die ("error".mysqli_error());
$num_row = mysqli_num_rows($result);
$query_result = mysqli_fetch_assoc($result);
if($num_row >= 0){
    if($query_result['active_flag'] == 0){
        if (strtolower($message) == 'login') {
            $response->message("https://de4d2d43.ngrok.io/whatsapp/login.php?user_id=$id");
        }
        else if (strtolower($message) == 'register') {
            $response->message("https://de4d2d43.ngrok.io/whatsapp/register.php?user_id=$id");
        }else
        {
            $response->message("You don't have permission. Please register and login.");
        }
        print $response;
    }else if($query_result['active_flag'] == 1){
        switch($query_result['user_type']){
            case 'EMS':
                $data = '{"PO_NO":"' . str_replace(' ','%20',$message) . '"}';
                $lineWebLink = "http://th1srwebprd1/MESCENTER1WEBAPI/ActiveLData/Swr/GetDataCustSwr?busItem=$data";
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
                $lineWebLink = "http://utlwebdev1/MESCENTER1WEBAPI/ActiveLData/Swr/GetDataCustSwr?busItem=$data";
                break;
            default:
                $data = '{"SWR_NO":["' . str_replace(' ','%20',$message) . '"]}';
                $lineWebLink = "http://th1srwebprd1/MESCENTER1WEBAPI/ActiveLData/Swr/GetDataCustSwr?busItem=$data";
                break;
        }
        
        $web = file_get_contents($lineWebLink);
        
        $result = json_decode($web, true);
        if(!$result){
            
            $arrayPostData = 'System error, please try later or contact at apinyako@utacgroup.com if any support needed.';
            
            replyMsg($arrayPostData);
            exit();
        }
        
        $count_result = count($result['ListData']);
    
    if ($count_result > 0) {

        $found = false;
        $result_key = 0;
        $result_get = '';
        
        $not_duplicate_result = unique_multidim_array($result['ListData'],'UTL_LOT');
        
        foreach ($not_duplicate_result as $key => $value) {

            if ($query_result['user_type'] === 'UTAC' || ($query_result['user_type'] === 'IFX' && ($value['CUST_CODE'] === 'IFX' || $value['CUST_CODE'] === 'IN6')) || $query_result['user_type'] === $value['CUST_CODE']) {

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
                            $WIP_LOT_STATUS = "🚚 ON WIP : " . $value['SHIP_DATE']."\n";
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
                            $SHIP_DATE = "📋 SOD : " . $value['SHIP_DATE']."\n";
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
                    
                    if((($key + 1) % 8 === 0)){
    
                        $result_key++;
                        $result_get = '';
    
                    }
    
                } elseif($query_result['user_type'] !== 'UTAC' && $query_result['user_type'] !== $value['CUST_CODE']) {
    
                    $result_get = "You don't have permission to see the information.";
    
                } 
            }
    
            $found = true;
            replyMsg($result_get);
    
        } else {
    
            switch($message){
                case 'SWR /BR no.':
                    $text = "Please enter SWR or BR no.";
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
                        switch($query_result['user_type']){
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
                        $text = "Hello Sender, Sorry, we can't find your Request#. If you confirm that your Request# is correct, it can be that your Request# has not been started in UTAC WIP system. Please give us 3 working days for your request to be started in UTAC WIP. You may contact $contact to confirm your Request# status.";
                    } else {
                        $text = "Please contact administrator";
                    }
                    break;
            }

            $arrayPostData = $text;
            
            $found = true;
            replyMsg($arrayPostData);
    
        }
    }
}

function replyMsg($result_get) {
    $response = new Twilio\Twiml\MessagingResponse;
    $msg = $result_get;
    $msg = str_replace('\n',"\n",$msg);
    $msg = str_replace('\\',"",$msg);
    $msg = str_replace('"',"",$msg);
    $response->message($msg);  
    print $response;
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