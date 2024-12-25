#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";

$date = date("Y-m-d");
$date_now = date("Y-m-d H:i:s");
$fp = fopen("set_old_request_step.log","w+");

$sql_old_req = "SELECT * FROM Gn_event_oldrequest WHERE end_date > '{$date_now}'";
fwrite($fp,$sql_old_req."\r\n");
$res_old_req = mysqli_query($self_con, $sql_old_req);
while($row_old_req = mysqli_fetch_assoc($res_old_req)){
    $start_date = $row_old_req['start_date'];
    $sms_idx = $row_old_req['sms_idx'];
    $sql_sms_info = "SELECT m_id,reservation_title FROM Gn_event_sms_info WHERE sms_idx='{$sms_idx}'";
    $res_sms_info = mysqli_query($self_con, $sql_sms_info) or die(mysqli_error($self_con));
    $row_sms_info = mysqli_fetch_assoc($res_sms_info);
    $sql_step_info = "SELECT * FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}'";
    fwrite($fp,$sql_step_info."\r\n");
    $res_step_info = mysqli_query($self_con, $sql_step_info) or die(mysqli_error($self_con));
    while ($row_step_info = mysqli_fetch_array($res_step_info)) {
        $send_day = $row_step_info['send_day'];
        $jpg = $jpg1 = $jpg2 = '';
        if ($row_step_info['image'])
            $jpg = "https://nm.kiam.kr/adjunct/mms/thum/" . $row_step_info['image'];
        if ($row_step_info['image1'])
            $jpg1 = "https://nm.kiam.kr/adjunct/mms/thum/" . $row_step_info['image1'];
        if ($row_step_info['image2'])
            $jpg2 = "https://nm.kiam.kr/adjunct/mms/thum/" . $row_step_info['image2'];
        if ($send_day > 0) {
            $send_time = $row_step_info['send_time'];
            if ($send_time == "") $send_time = "09:30";
            if ($send_time == "00:00") $send_time = "09:30";
            $reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));
            fwrite($fp,$reservation."\r\n");
        }
    }
    $mem_id = $row_sms_info['row_sms_info'];
    $reservation_title = $row_sms_info['reservation_title'];

    $start_index = $row_old_req['addr_start_index'] * 1;
    $end_index =  $row_old_req['addr_end_index'] * 1;
    $cnt = $end_index - $start_index + 1;
    $group_idx = $row_old_req['address_idx'];
    $num_arr = array();
    $sql = "SELECT recv_num FROM Gn_MMS_Receive WHERE grp_id = '{$group_idx}' limit {$start_index}, {$cnt}";
    $gresult = mysqli_query($self_con, $sql);
    while ($mms_receive = mysqli_fetch_array($gresult)) {
        array_push($num_arr, $mms_receive['recv_num']);
    }
    $recv_num = implode(",", $num_arr);
    
}
mysqli_close($self_con);
?>