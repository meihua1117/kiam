#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";

$date = date("Y-m-d");
$date_log = date("Y-m-d H:i:s");
// echo $date_log; exit;

//$sql_daily = "select * from (select * from Gn_daily order by step_count desc) t where step_sms_idx <> 0 GROUP BY step_sms_idx";
$sql_daily = "WITH Gn_daily_temp AS (SELECT *,ROW_NUMBER() OVER (PARTITION BY step_sms_idx ORDER BY step_count DESC) AS rn FROM Gn_daily WHERE step_sms_idx <> 0 )
                SELECT * FROM Gn_daily_temp WHERE rn = 1;";
$res_daily = mysqli_query($self_con, $sql_daily);
while ($row_daily = mysqli_fetch_array($res_daily)) {
    $sql_day_betw = "select send_day from Gn_event_sms_step_info where sms_idx='{$row_daily['step_sms_idx']}' and send_day='{$row_daily['step_count']}'";
    $res_day_betw = mysqli_query($self_con, $sql_day_betw);
    $row_day_betw = mysqli_fetch_array($res_day_betw);

    if ($row_day_betw['send_day'] == '') {
        continue;
    }

    // $step = $row_daily[step_count] * 1 + 1;
    $sql_cnt = "select min(send_day) as step_count from Gn_event_sms_step_info where sms_idx='{$row_daily['step_sms_idx']}' and send_day > '{$row_daily['step_count']}'";
    $res_cnt = mysqli_query($self_con, $sql_cnt);
    $row_cnt = mysqli_fetch_array($res_cnt);

    $send_day = $row_cnt['step_count'];
    if (!$send_day) {
        continue;
    }
    $htime = $row_daily['htime'];
    $mtime = $row_daily['mtime'];

    $send_day_betw = $send_day * 1 - $row_day_betw['send_day'] * 1;
    $start_date = $row_daily['start_date'];

    $cur_time_com_stamp = time() - (86400 * $send_day_betw);
    $cur_time_com = date("Y-m-d", $cur_time_com_stamp);

    if ($cur_time_com == $start_date) {
        $send_date_arr = array();
        if ($row_daily['max_count']) {
            $day = ceil($row_daily['max_count'] / $row_daily['daily_cnt']);
        } else {
            $day = ceil($row_daily['total_count'] / $row_daily['daily_cnt']);
        }
        for ($i = 0; $i < $day; $i++) {
            $start_day = strtotime($date) + (86400 * $i);
            $today = date("Y-m-d", $start_day);
            if (!$row_daily['weekend_status']) {
                $week = date("l", $start_day);
                if ($week == "Saturday" || $week == "Sunday") {
                    $day++;
                    continue;
                }
            }
            array_push($send_date_arr, $today);
        }
        $send_date = implode(',', $send_date_arr);

        $ch_mms = curl_init();
        $fields['mem_id'] = $row_daily['mem_id'];
        $fields['mode'] = "daily_save";
        $fields['step_daily'] = "Y";
        $fields['total_count'] = $row_daily['total_count'];
        $fields['send_num'] = $row_daily['send_num'];
        $fields['group_idx'] = $row_daily['group_idx'];
        $fields['max_count'] = $row_daily['max_count'];
        $fields['send_date'] = $send_date;
        $fields['set_msg_mode'] = "1";
        $fields['step_sms_idx'] = $row_daily['step_sms_idx'];
        $fields['set_weekend'] = $row_daily['weekend_status'];
        $fields['daily_cnt'] = $row_daily['daily_cnt'];
        $fields['daily_link'] = $row_daily['daily_link'];
        $fields['htime'] = $htime;
        $fields['mtime'] = $mtime;
        // $fields['send_deny_msg'] = $send_deny_msg;
        $fields['step_count'] = $send_day;

        curl_setopt($ch_mms, CURLOPT_URL, "https://nm.kiam.kr/mypage.proc.php");
        curl_setopt($ch_mms, CURLOPT_POST, true);
        curl_setopt($ch_mms, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_mms, CURLOPT_POSTFIELDS, http_build_query($fields));

        $mms_result = curl_exec($ch_mms);
        if ($mms_result === FALSE) {
            $ret = "MMS 발송시 오류가 발생하였습니다.";
        } else {
            $result = json_decode($mms_result);
            $ret = $result->msg;
        }
        curl_close($ch_mms);

        echo $date_log . ">>set next_step_msg>>" . $row_daily['step_sms_idx'] . PHP_EOL;
    }
}
mysqli_close($self_con);
?>