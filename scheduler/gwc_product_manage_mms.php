#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";
require_once('/home/kiam/fcm/vendor/autoload.php');
$headers = array('Accept: application/json, text/plain, */*', 'Cache_Control: no-cache', 'content-type:mutipart/form-data;');
date_default_timezone_set('Asia/Seoul');

$server = "http://www.goodhow.com:";


$push_url = 'https://fcm.googleapis.com/v1/projects/onepagebookmms5/messages:send';
putenv('GOOGLE_APPLICATION_CREDENTIALS=/home/kiam/fcm/onepagebookmms5.json');
$scope = 'https://www.googleapis.com/auth/firebase.messaging';
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes($scope);
$auth_key = $client->fetchAccessTokenWithAssertion();
$push_headers = array(
    'Authorization: Bearer ' . $auth_key['access_token'],
    'Content-Type: application/json'
);
echo "data::" . date("Y-m-d H:i:s") . "\n";
$s = 0;
$sql_mem_auto = "select mem_id from Gn_Gwc_Order where pay_status='Y' and page_type=0 group by mem_id";
$res_mem_auto = mysqli_query($self_con, $sql_mem_auto);
while ($row_mem_auto = mysqli_fetch_array($res_mem_auto)) {
    $date_this_month = date("Y-m") . "-01 00:00:00";
    $date_today = date("Y-m-d");
    $prev_month_ts = strtotime($date_today . ' -1 month');
    $date_pre_month = date("Y-m", $prev_month_ts) . "-01 00:00:00";

    //$sql_this_month_pay = "select SUM(TotPrice) as money from tjd_pay_result where cash_prod_pay=0 and gwc_cont_pay=1 and buyer_id='{$row_mem_auto['mem_id']}' and end_status='Y' and date>'{$date_this_month}'";//당월 구매금액
    $sql_this_month_pay = "select SUM(TotPrice) as money from tjd_pay_result_gwc where cash_prod_pay=0 and buyer_id='{$row_mem_auto['mem_id']}' and end_status='Y' and date>'{$date_this_month}'"; //당월 구매금액
    $res_this_month_pay = mysqli_query($self_con, $sql_this_month_pay);
    $row_this_month_pay = mysqli_fetch_array($res_this_month_pay);

    $this_month_pay = $row_this_month_pay[0] ? $row_this_month_pay[0] : 0;
    $cnt_all_prod_this = floor($this_month_pay * 1 / 20000); //당월 가져오기 가능 전체건수

    $sql_get_prod_this = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='{$row_mem_auto['mem_id']}' and ori_store_prod_idx!=0 and req_data>'{$date_this_month}'";
    $res_get_prod_this = mysqli_query($self_con, $sql_get_prod_this);
    $row_get_prod_this = mysqli_fetch_array($res_get_prod_this); //당월 가져오기한 총 건수

    //$sql_pre_month_pay = "select SUM(TotPrice) as money from tjd_pay_result where cash_prod_pay=0 and gwc_cont_pay=1 and buyer_id='{$row_mem_auto[mem_id]}' and end_status='Y' and date>'{$date_pre_month}' and date<'{$date_this_month}'";//전달 구매금액
    $sql_pre_month_pay = "select SUM(TotPrice) as money from tjd_pay_result_gwc where cash_prod_pay=0 and buyer_id='{$row_mem_auto['mem_id']}' and end_status='Y' and date>'{$date_pre_month}' and date<'{$date_this_month}'"; //전달 구매금액
    $res_pre_month_pay = mysqli_query($self_con, $sql_pre_month_pay);
    $row_pre_month_pay = mysqli_fetch_array($res_pre_month_pay);

    $pre_month_pay = $row_pre_month_pay[0] ? $row_pre_month_pay[0] : 0;
    $cnt_all_prod_pre = floor($pre_month_pay * 1 / 20000); //전달 가져오기 가능 전체건수

    $sql_get_prod_pre = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='{$row_mem_auto['mem_id']}' and ori_store_prod_idx!=0 and req_data>'{$date_pre_month}' and req_data<'{$date_this_month}'";
    $res_get_prod_pre = mysqli_query($self_con, $sql_get_prod_pre);
    $row_get_prod_pre = mysqli_fetch_array($res_get_prod_pre); //전달 가져오기한 총 건수

    $sql_get_prod = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='{$row_mem_auto['mem_id']}' and ori_store_prod_idx!=0";
    $res_get_prod = mysqli_query($self_con, $sql_get_prod);
    $row_get_prod = mysqli_fetch_array($res_get_prod); //현재까지 가져오기한 총 건수

    $pre_rest_cnt = $cnt_all_prod_pre - $row_get_prod_pre[0] * 1; //전달 잔여건수
    $this_rest_cnt = $cnt_all_prod_this - $row_get_prod_this[0] * 1; //당월 잔여건수

    $sql_point = "select mem_id, mem_phone from Gn_Member where mem_id='{$row_mem_auto['mem_id']}'";
    $res_point = mysqli_query($self_con, $sql_point);
    $row_point = mysqli_fetch_array($res_point);

    $mem_id = $row_point['mem_id'];
    $mem_phone = str_replace('-', '', $row_point['mem_phone']);

    $txt = "회원님의 전월 구매금액은 " . $pre_month_pay . "원 / 등록건수 " . $row_get_prod_pre[0] . "건이었습니다. 금월 현재까지 구매금액은 현재 " . $this_month_pay . "원입니다.";

    if ($this_rest_cnt) {
        $txt .= "현재 잔여 " . $this_rest_cnt . "건은 익월말에 소진되므로 상품 가져오기하세요.";
    }

    if ($cnt_all_prod_this <= $row_get_prod_pre[0]) {
        $txt .= "현재 " . $cnt_all_prod_this . "건 가져온 상품이 계속 판매상태로 유지됩니다. 판매건수 추가하려면 추가 구매해야 합니다.";
    }

    $txt .= "굿마켓의 수익쉐어 미션에 동참하기 위해 꼭 굿마켓에서 구매해주시기바랍니다.";

    $uni_id = time() . sprintf("%02d", $s);

    send_mms($mem_id, $mem_phone, $uni_id, $mem_point, $push_url, $push_headers, $txt, $self_con);
}

function send_mms($mem_id, $mem_phone, $uni_id, $mem_point, $url, $headers, $txt, $self_con)
{
    $title = "굿마켓 구매와 상품등록 안내";

    $query = "insert into Gn_MMS set mem_id='{$mem_id}',
                                    send_num='{$mem_phone}',
                                    recv_num='{$mem_phone}',
                                    uni_id='{$uni_id}',
                                    content='{$txt}',
                                    title='{$title}',
                                    delay='5',
                                    delay2='15',
                                    close='24',
                                    type='1',
                                    reg_date=NOW(),
                                    agreement_yn='Y',
                                    recv_num_cnt=1";

    mysqli_query($self_con, $query) or die(mysqli_error($self_con));
    $sidx = mysqli_insert_id($self_con);

    $sql_mem_info = "select mem_name, mem_point, mem_cash from Gn_Member where mem_id='{$mem_id}'";
    $res_mem_info = mysqli_query($self_con, $sql_mem_info);
    $row_mem_info = mysqli_fetch_array($self_con, $res_mem_info);

    $sql_notice_recv_seller = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}',
                                                                buyer_tel='{$mem_phone}',
                                                                site='',
                                                                pay_method='obmms02',
                                                                item_name = '공지사항전송',
                                                                pay_date=NOW(),
                                                                pay_status='Y',
                                                                pay_percent='90',
                                                                order_number = '{$uni_id}',
                                                                VACT_InputName='{$row_mem_info['mem_name']}',
                                                                seller_id='{$title}',
                                                                point_val=3,
                                                                type='noticerecv',
                                                                current_point={$row_mem_info['mem_point']},
                                                                current_cash={$row_mem_info['mem_cash']},
                                                                receive_state=1,
                                                                message='{$txt}',
                                                                alarm_state=0";
    mysqli_query($self_con, $sql_notice_recv_seller);

    $query = "select * from Gn_MMS_Number where mem_Id='{$mem_id}' and sendnum='{$mem_phone}'";
    $result = mysqli_query($self_con, $query);
    $info = mysqli_fetch_array($self_con, $result);
    $pkey = $info['pkey'];
    if ($pkey != "") {
        $send_type = $info['type'];
        sendPush($url, $headers, $pkey, $sidx, $send_type, $mem_phone, $self_con);
    }
}

function sendPush($url, $headers, $pkey, $sidx, $send_type, $send_num, $self_con)
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

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    //print_R($result);
    $json = json_decode($result);
    $msg = "";
    $msg = $json->results[0]->error;
    curl_close($ch);

    $query = "insert into Gn_MMS_PUSH set send_num='{$send_num}',idx='{$sidx}',token='{$pkey}',error='{$msg}'";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));
}
?>