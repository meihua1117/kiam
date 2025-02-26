#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";
require_once('/home/kiam/fcm/vendor/autoload.php');
/*$mysql_host = 'localhost';
$mysql_user = 'kiam';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';
$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysqli_error($self_con));
mysql_select_db($mysql_db) or die(mysqli_error($self_con));
mysqli_query($self_con, "set names utf8");*/

//$debug_mode = true;
$debug_mode = false;
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

/*
send_title: test
send_num: 01088254394
send_txt: test
send_rday: 2019-02-10
send_htime: 19
send_mtime: 30
send_type: 1
send_chk: 
send_img: 
send_img1: 
send_img2: 
send_save_mms: 
send_agree_msg: N
send_deny_wushi_0: ok
send_deny_wushi_1: ok
send_deny_wushi_2: 
send_deny_wushi_3: 
send_deny_msg: 
send_ssh_check: 
send_ssh_check2: 
send_ssh_check3: 
send_delay: 5
send_delay2: 15
send_close: 20
send_onebook_status: N
send_go_num[]: 01032304818
send_go_num[]: 01076514938
send_go_user_cnt[]: 448
send_go_user_cnt[]: 398
send_go_max_cnt[]: 450
send_go_max_cnt[]: 400
send_go_memo2[]: LG
send_go_memo2[]: KT
send_go_cnt1[]: 0
send_go_cnt1[]: 0
send_go_cnt2[]: 2
send_go_cnt2[]: 0
send_go_remain_cnt[]: 2799
send_go_remain_cnt[]: 2800
send_cnt: 1
free_use: N
*/

$date = date("Y-m-d H:i:00"); //"2019-02-11 10:30:00";
//$date = "2019-04-17 09:00:00";
$sql_all = "select * from Gn_MMS where reservation ='{$date}' and result = 1";
$resul_all = mysqli_query($self_con, $sql_all);
while ($row_all = mysqli_fetch_array($resul_all)) {
    $send_num = $row_all['send_num'];
    $one_member_id = $row_all['mem_id'];
    $sidx = $row_all['idx'];
    $send_type = $row_all['type'];
    $recv_num = $row_all['recv_num'];
    $search_str = " and (chanel_type=1 or chanel_type=4 or chanel_type=9)";
    if ($send_type == 2 || $send_type == 3 || $send_type == 4) {
        $search_str = " and (chanel_type=2 or chanel_type=9)";
    }
    if ($send_type == 6 || $send_type == 8 || $send_type == 1) {
        $search_str = " and (chanel_type=1 or chanel_type=4 or chanel_type=9)";
    }

    $sql_deny = "select * from Gn_MMS_Deny where send_num='{$send_num}' and recv_num='{$recv_num}'" . $search_str;
    $res_deny = mysqli_query($self_con, $sql_deny);
    $cnt = mysqli_num_rows($res_deny);
    if ($cnt) {
        $debug_mode = true;
        echo  $date . " " . $send_num . ">>" . $recv_num . "::send_deny_set!!" . "\n";
    }

    $query = "select * from Gn_MMS_Number where sendnum='{$send_num}'";
    $result = mysqli_query($self_con, $query);
    $info = mysqli_fetch_array($result);
    $id = $info['pkey'];
    if ($debug_mode == false) {
        if ($id != "") {
            $title = '{"MMS Push"}';
            $message = '{"Send":"Start","idx":"' . $sidx . '","send_type":"' . $send_type . '"}';
            $fields = array(
                'data' => array(
                    "body" => $message,
                    "title" => $title
                )
            );

            //print_R($message);
            $fields['android'] = array("priority" => "high");
            $fields['token'] = $id;
            $fields = json_encode(array('message' => $fields));
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            //print_R($result);
            curl_close($ch);
            $json = json_decode($result);
            $msg = "";
            $msg = $json->results[0]->error;

            $query = "insert into Gn_MMS_PUSH set send_num='{$send_num}',idx='{$sidx}',token='{$id}',error='{$msg}',regdate=now()";
            if ($debug_mode == false) {
                echo  $date . " phonenum=" . $send_num . " idx=" . $sidx . "\n";
                mysqli_query($self_con, $query) or die(mysqli_error($self_con));
            }
        }
    }
}
mysqli_close($self_con);
?>