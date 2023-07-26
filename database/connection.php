<?php

namespace Connection;

use \PDO;
use \PDOException;

date_default_timezone_set("Asia/Bangkok");
ini_set('display_errors', 1);
ini_set('track_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Database
{
    // Local
    public static function local($sql = null, $stmtSelect = null, $stmtInsert = null)
    {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $dbname = "test";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($sql) {
                $conn->exec($sql);
            }

            if ($stmtSelect) {
                $stmt = $conn->prepare("$stmtSelect");
                $stmt->execute();
                // $stmt->setFetchMode(PDO::FETCH_NUM);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $result = $stmt->fetchAll();

                return $result;
            }

            if ($stmtInsert) {
                $stmt = $conn->prepare($stmtInsert["queryString"]);

                foreach ($stmtInsert["data"] as $res) {

                    $stmt->execute($res);
                }
            }

            echo "Operation successfully";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $conn = null;
    }

    // On production: utlrms1
    public static function utlrms1($sql = null, $stmtSelect = null, $stmtInsertandUpdate = null)
    {
        $servername = "utlrms1";
        $username = "atroot";
        $password = "atrtms@U1";
        $dbName = "application";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($sql) {
                $conn->exec($sql);
            }

            if ($stmtSelect) {
                $stmt = $conn->prepare("$stmtSelect");
                $stmt->execute();
                // $stmt->setFetchMode(PDO::FETCH_NUM);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $result = $stmt->fetchAll();

                return $result;
            }

            if ($stmtInsertandUpdate) {
                $stmt = $conn->prepare($stmtInsertandUpdate["queryString"]);

                foreach ($stmtInsertandUpdate["data"] as $res) {

                    $stmt->execute($res);
                }
            }

            // echo "Operation successfully";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $conn = null;
    }

    // On production: th1srcim1: virtual_run
    public static function th1srcim1($sql = null, $stmtSelect = null, $stmtInsertandUpdate = null)
    {
        $servername = "th1srcim1.nseb.co.th";
        $username = "oeeadmin";
        $password = "oeecell@min1";
        $dbName = "virtual_run";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if ($sql) {
                $conn->exec($sql);
            }

            if ($stmtSelect) {
                $stmt = $conn->prepare("$stmtSelect");
                $stmt->execute();
                // $stmt->setFetchMode(PDO::FETCH_NUM);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $result = $stmt->fetchAll();

                return $result;
            }

            if ($stmtInsertandUpdate) {
                $stmt = $conn->prepare($stmtInsertandUpdate["queryString"]);

                foreach ($stmtInsertandUpdate["data"] as $res) {

                    $stmt->execute($res);
                }
            }

            // echo "Operation successfully";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        $conn = null;
    }

    public static function oracle()
    {
        // $conn = oci_connect('BIDPENGUSR1', "vb'fu[uU1P", 'DWPRD1/XE');
        // if (!$conn) {
        //     $e = oci_error();
        //     trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        // }

        // $stid = oci_parse($conn, 'SELECT DISTINCT CUST_CODE, CUST_REQ_NO AS SWR_NO, PO_NO FROM UA_ELR');
        // oci_execute($stid);

        // while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
        //     foreach ($row as $item) {
        //         print_r($item);
        //     }
        // }

        // $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = DWPRD1)(PORT = 1521)))(CONNECT_DATA=(SID=IOT)))";
        // $conn = ocilogon("BIDPENGUSR1", "vb'fu[uU1P", $db);

        // if ($conn = OCILogon("system", "your database password", $db)) {
        //     echo "Successfully connected to Oracle.\n";
        //     OCILogoff($conn);
        // } else {
        //     $err = OCIError();
        //     echo "Connection failed." . $err[text];
        // }
    }
}
