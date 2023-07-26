<?php

include "../database/connection.php";

use Connection\Database as ConnectDB;

switch ($_GET["action"]) {
    case "getUserProfile":
        $stmt = "SELECT * FROM line_user_register WHERE userId = '".$_GET["userId"]."' ";
        $result = ConnectDB::utlrms1(null, $stmt);
        echo json_encode($result[0]);

        break;
    case "getCustomerCode":
        $stmt = "SELECT code FROM line_user_type ";
        $result = ConnectDB::utlrms1(null, $stmt);
        echo json_encode($result);

        break;
}

$postData = json_decode(file_get_contents("php://input"), TRUE);
switch ($postData["action"]) {
    case "search":
        $stmt = "";
        $result = ConnectDB::oracle();
        echo json_encode($result);

        break;
}