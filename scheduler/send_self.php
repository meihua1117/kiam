#!/opt/php/bin/php
<?php
/*$mysql_host = 'localhost';
$mysql_user = 'kiam';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';
$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysql_error());
mysql_select_db($mysql_db) or die(mysql_error());
mysql_query("set names utf8");*/
include_once "/home/kiam/lib/db_config.php";
require_once('/home/kiam/fcm/vendor/autoload.php');

$url = 'https://fcm.googleapis.com/v1/projects/onepagebookmms5/messages:send';
putenv('GOOGLE_APPLICATION_CREDENTIALS=/home/kiam/fcm/onepagebookmms5.json');
$scope = 'https://www.googleapis.com/auth/firebase.messaging';
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes($scope);
$auth_key = $client->fetchAccessTokenWithAssertion();
$headers = array(
    'Authorization: Bearer ' . $auth_key['access_token'],
    'Content-Type: application/json'
);

//회사폰
$sql = "select * from gn_conf";
$result = mysqli_query($self_con, $sql);
$row = mysqli_fetch_array($result);

$mem_id = $row['phone_id']; //'onlymain';
$send_num = $row['phone_num']; //'01083904260';

if ($mem_id != "" && $send_num != "") {
    //실패된 셀폰바로발송을 찾아 발송한다.
    $sql = "SELECT idx,type FROM Gn_MMS WHERE reg_date > DATE_FORMAT(DATE_ADD(now(),INTERVAL -30 MINUTE),'%Y-%m-%d %H:%i:%s') 
                                            AND send_num=recv_num 
                                            AND up_date IS NULL 
                                            AND reservation IS NULL 
                                            AND type='5';";
    runSQL($self_con, $sql, $mem_id, $send_num, $url, $headers);

    //실패된 셀폰예약발송을 찾아 발송한다.
    $sql = "SELECT idx,type FROM Gn_MMS WHERE reservation IS NOT NULL 
                                            AND reservation > DATE_FORMAT(DATE_ADD(now(),INTERVAL -30 MINUTE),'%Y-%m-%d %H:%i:%s') 
                                            AND reservation < now()
                                            AND send_num=recv_num 
                                            AND up_date IS NULL 
                                            AND type='5';";
    runSQL($self_con, $sql, $mem_id, $send_num, $url, $headers);
}

mysqli_close($self_con);


function runSQL($self_con, $sql_all, $mem_id, $send_num, $url, $headers)
{
    $resul_all = mysqli_query($self_con, $sql_all);
    while ($row_all = mysqli_fetch_array($resul_all)) {

        $sidx = $row_all['idx'];
        $send_type = $row_all['type'];

        $query = "UPDATE Gn_MMS SET  mem_id='$mem_id', send_num='$send_num' WHERE idx='$sidx'";
        $result = mysqli_query($self_con, $query);

        $query = "select * from Gn_MMS_Number where mem_Id='$mem_id' and sendnum='$send_num'";
        $result = mysqli_query($self_con, $query);
        $info = mysqli_fetch_array($result);
        $pkey = $info['pkey'];
        if ($pkey != "") {
            sendPush($self_con, $url, $headers, $pkey, $sidx, $send_type, $send_num);
        }
    }
}
function sendPush($self_con, $url, $headers, $pkey, $sidx, $send_type, $send_num)
{
    $title = '{"MMS Push"}';
    $message = '{"Send":"Start","idx":"' . $sidx . '","send_type":"' . $send_type . '"}';
    $fields = array(
        'data' => array(
            "body" => $message,
            "title" => $title
        )
    );

    $fields['token'] = $pkey;
    $fields['android'] = array("priority" => "high");
    $fields = json_encode(array('message' => $fields));
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

    /*$ title = '{"MMS Push"}';
    $message = '{"Send":"Start","idx":"' . $sidx . '","send_type":"' . $send_type . '"}';
    $fields = array(
        'data' => array(
            "body" => $message,
            "title" => $title
        )
    );
    if (is_array($pkey)) {
        $fields['registration_ids'] = $pkey;
    } else {
        $fields['to'] = $pkey;
    }

    $fields['priority'] = "high";
    $fields['token'] = $pkey;
    $fields = http_build_query($fields);
    $url = "https://nm.kiam.kr/fcm/send_fcm.php";
    //$fields = json_encode ($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    //curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
*/
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    //print_R($result);
    $json = json_decode($result);
    $msg = "";
    $msg = $json->results[0]->error;
    curl_close($ch);

    $query = "insert into Gn_MMS_PUSH set send_num='{$send_num}',idx='{$sidx}',token='{$pkey}',error='{$msg}',regdate=now()";

    mysqli_query($self_con, $query) or die(mysqli_error($self_con));
}

?>