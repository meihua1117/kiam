<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
date_default_timezone_set('Asia/Seoul');
$cur_time = date("Y-m-d H:i:s");
$date_today = date("Y-m-d");
$cur_time1 = date("YmdHis");
extract($_POST);

$member_id = $_POST['member_id']; //신청회원 아이디
$event_idx = $_POST['event_idx'];
$callback_no = $_POST['callback_no'];
$callback_times = "a".$_POST['callback_times'];

$mem_point = 0;
$cur_time_com_mms = time() - (86400 * 3);
$cur_time_mms_com = date("Y-m-d H:i:s", $cur_time_com_mms);
$s = 0;

$sql_point = "select key_content from Gn_Search_Key where key_id='callback_set_point'";
$res_point = mysqli_query($self_con, $sql_point);
$row_point = mysqli_fetch_array($res_point);
$callback_set_point = $row_point['key_content'];

$sql_mem = "select * from Gn_Member where mem_id='{$member_id}'";
$res_mem = mysqli_query($self_con, $sql_mem);
if (mysqli_num_rows($res_mem) == 0) {
    echo json_encode(array("result"=>3));
    exit;
} else {
    $row_mem = mysqli_fetch_array($res_mem);
    $mem_name = $row_mem['mem_name'];
    $method = $member_id . "/" . $mem_name;
}

$sql_data = "select * from Gn_event where event_idx={$event_idx}";
$res_data = mysqli_query($self_con, $sql_data);
$row_data = mysqli_fetch_array($res_data);
$mem_id = $row_data['m_id']; //분양사 아이디

$sql_memdata = "select * from Gn_Member where mem_id='{$mem_id}'";
$res_memdata = mysqli_query($self_con, $sql_memdata);
$row_memdata = mysqli_fetch_array($res_memdata);
$point = $row_memdata['mem_point'];
$cash = $row_memdata['mem_cash'];

if ($point < $callback_set_point) {
    echo json_encode(array("result"=>2));
    exit;
}

$sql_update_callback = "update Gn_Member set mem_callback={$callback_no}, mem_callback_phone_state=1, mem_callback_mun_state=1, phone_callback={$callback_no}, mun_callback={$callback_no}, phone_callback_state=3, mun_callback_state=3,callback_times='{$callback_times}' where mem_id='{$member_id}'";
mysqli_query($self_con,$sql_update_callback) or die(mysqli_error($self_con));

$sql_callback_msg = "update gn_mms_callback set regdate=now() where idx={$callback_no}";
mysqli_query($self_con, $sql_callback_msg) or die(mysqli_error($self_con));

$sql_chk = "select mb_id from gn_mms_callback where service_state=2 and type=3 and mb_id='{$member_id}'";
$res_chk = mysqli_query($self_con,$sql_chk);
$row_chk = mysqli_fetch_array($res_chk);
if ($row_chk['mb_id'] != "") {
    $sql_callback = "update gn_mms_callback set type=0, regdate=now() where service_state=2 and type=3 and mb_id='{$member_id}'";
    mysqli_query($self_con, $sql_callback) or die(mysqli_error($self_con));
}

$sql_service = "select * from Gn_Iam_Service where mem_id='{$mem_id}'"; //분양사이면.
$res_service = mysqli_query($self_con,$sql_service);
if (mysqli_num_rows($res_service)) {
    $row = mysqli_fetch_array($res_service);
    if ($row['callback_point_end'] < $date_today) {
        $min_point = $callback_set_point * 1;
    } else {
        if ($row['callback_set_point'] == '') {
            $min_point = $callback_set_point * 1;
        } else if ($row['callback_set_point'] == 0) {
            $min_point = 0;
        } else {
            $min_point = $row['callback_set_point'] * 1;
        }
    }
} else {
    $min_point = $callback_set_point * 1;
}

$sql_insert = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$row_memdata['mem_phone']}', item_name='콜백메시지', item_price={$min_point}, pay_percent=90, current_point={$point}-{$min_point}, current_cash='{$cash}', pay_status='Y', VACT_InputName='{$row_memdata['mem_name']}', type='use', pay_method='{$method}', pay_date=now(), point_val=1";
mysqli_query($self_con, $sql_insert);

$sql_point_update = "update Gn_Member set mem_point=mem_point-{$min_point} where mem_id='{$mem_id}'";
mysqli_query($self_con, $sql_point_update) or die(mysqli_error($self_con));

$sql_point = "select mem_id, mem_point, mem_phone from Gn_Member where mem_id='{$mem_id}'";
$res_point = mysqli_query($self_con, $sql_point);
$row_point = mysqli_fetch_array($res_point);

$mem_phone = str_replace('-', '', $row_point['mem_phone']);
$point = (int)$row_point['mem_point'];
if ($point <= 10000 && $point > 5000) $mem_point = 10000;
if ($point <= 5000 && $point > 3000) $mem_point = 5000;
if ($point <= 3000) $mem_point = 3000;

$s++;
$uni_id = time() . sprintf("%02d", $s);
if ($mem_point != 0) {
    $sql_mms_send = "select reg_date, recv_num from Gn_MMS where title='포인트 충전 안내' and content='" . $mem_id . ", 고객님의 잔여 포인트가 " . $mem_point . " 포인트 이하입니다. 포인트가 부족할 경우 현재 이용중이신 기능이 중지되오니 충전해주시길 바랍니다. 감사합니다.' and mem_id='{$mem_id}' order by idx desc limit 1";
    $res_mms_send = mysqli_query($self_con, $sql_mms_send);
    if (mysqli_num_rows($res_mms_send)) {
        while ($row_mms_send = mysqli_fetch_array($res_mms_send)) {
            $reg_date_msg = $row_mms_send['reg_date'];
            if ($reg_date_msg < $cur_time_mms_com) {
                send_mms($mem_id, $mem_phone, $mem_point);
            }
        }
    } else {
        send_mms($mem_id, $mem_phone, $mem_point);
    }
}
echo json_encode(array("result"=>1));

function send_mms($mem_id, $mem_phone, $mem_point)
{
    $title = "포인트 충전 안내";
    $txt = $mem_id . ", 고객님의 잔여 포인트가 " . $mem_point . " 포인트 이하입니다. 포인트가 부족할 경우 현재 이용중이신 기능이 중지되오니 충전해주시길 바랍니다. 감사합니다.";
    sendmms(5, $mem_id, $mem_phone, $mem_phone, "", $title, $txt, "", "", "", "Y");
}
