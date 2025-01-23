#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";

$curDate =  new DateTime();
$sql_req = "SELECT * FROM Gn_event_request WHERE step_end_time > now() AND target <> '' AND target < 100 ";
$res_req = mysqli_query($self_con, $sql_req);
while ($row_req = mysqli_fetch_assoc($res_req)) {
    $mem_id = $row_req['m_id'];
    $start_date = $row_req['regdate'];
    $recv_num = str_replace("-", "", $row_req['mobile']);
    $request_idx = $row_req['request_idx'];

    $sql_event = "SELECT * FROM Gn_event WHERE event_idx='{$row_req['event_idx']}'";
    $res_event = mysqli_query($self_con, $sql_event) or die(mysqli_error($self_con));
    $row_event = mysqli_fetch_array($res_event);
    $step_idx1 = $row_event['sms_idx1'];
    $step_idx2 = $row_event['sms_idx2'];
    $step_idx3 = $row_event['sms_idx3'];
    $send_num = $row_event['mobile'];

    $sql_sms_info = "SELECT sms_idx FROM Gn_event_sms_info WHERE sms_idx='{$step_idx1}' or sms_idx='{$step_idx2}' or sms_idx='{$step_idx3}'";
    $res_sms_info = mysqli_query($self_con, $sql_sms_info) or die(mysqli_error($self_con));
    while ($row_sms_info = mysqli_fetch_assoc($res_sms_info)) {
        $sms_idx = $row_sms_info['sms_idx'];
        //알람등록
        $sql_step_info = "SELECT * FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}'";
        $res_step_info = mysqli_query($self_con, $sql_step_info) or die(mysqli_error($self_con));
        while ($row_step_info = mysqli_fetch_array($res_step_info)) {
            $send_day = $row_step_info['send_day'];
            if ($send_day > 0) {
                $send_time = $row_step_info['send_time'];
                if ($send_time == "") $send_time = "09:30";
                if ($send_time == "00:00") $send_time = "09:30";
                $send_time_array = explode(":", $send_time);
                $reserveDate = new DateTime($start_date);
                $reserveDate->setTime($send_time_array[0], $send_time_array[1]);
                $reserveDate->modify('+' . $send_day . ' day');
                if ($reserveDate->format('Y-m-d') == $curDate->format('Y-m-d')) {
                    $jpg = $jpg1 = $jpg2 = '';
                    if ($row_step_info['image'])
                        $jpg = "https://nm.kiam.kr/adjunct/mms/thum/" . $row_step_info['image'];
                    if ($row_step_info['image1'])
                        $jpg1 = "https://nm.kiam.kr/adjunct/mms/thum/" . $row_step_info['image1'];
                    if ($row_step_info['image2'])
                        $jpg2 = "https://nm.kiam.kr/adjunct/mms/thum/" . $row_step_info['image2'];
                    $result = sendmms($row_req['target'], $mem_id, $send_num, $recv_num, $reserveDate->format('Y-m-d H:i:s'), $row_step_info['title'], $row_step_info['content'], $jpg, $jpg1, $jpg2, "Y", $sms_idx, $row_step_info['sms_detail_idx'], $request_idx, "", $row_step_info['send_deny']);

                    $sql_mms_agree = "insert into Gn_MMS_Agree set mem_id='{$mem_id}',
															send_num='{$send_num}',
															recv_num='{$recv_num}',
															content='{$row_step_info['content']}',
															title='{$row_step_info['title']}',
															jpg='{$jpg}',
															jpg1='',
															jpg2='',
															reg_date=NOW(),
															reservation='{$reserveDate->format('Y-m-d H:i:s')}',
															sms_idx='{$sms_idx}',
															sms_detail_idx='{$row_step_info['sms_detail_idx']}',
															request_idx='{$request_idx}',
															up_date=NOW()";
                    mysqli_query($self_con, $sql_mms_agree) or die(mysqli_error($self_con));
                    echo "event request " . $request_idx . " : " . $result . PHP_EOL;
                }
            }
        }
    }
}
function sendmms($type, $userid, $sendnum, $recvnum, $rserv_time, $title, $content, $img, $img1, $img2, $send_agreement_yn, $sms_idx = "", $sms_detail_idx = "", $request_idx = "", $gd_id = "", $send_deny_msg = "", $or_id = "")
{
    if ($userid == "" || $sendnum == "" || $recvnum == "") {
        return "fail";
    }
    global $self_con;
    $query = "SELECT * FROM Gn_MMS_Number WHERE mem_id='{$userid}' AND sendnum='{$sendnum}'";
    $result = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($result);
    if ($row['pkey'] == "")
        return "fail:no key";

    //초기화 진행
    $send_go_num = array();
    $send_go_num[0] = $sendnum;
    $ch_mms = curl_init();

    //발송폰번호배열
    $fields['user_id'] = $userid;
    $fields['send_go_num'] = $send_go_num;
    //수신폰번호배열
    $fields['send_num'] = $recvnum;
    //예약발송시간
    if ($rserv_time != "")
        $fields['reserv_time'] = $rserv_time;
    //타이틀
    $fields['send_title'] = $title;
    //텍스트
    $fields['send_txt'] = $content;
    //첨부이미지	
    $fields['send_img'] = $img;
    $fields['send_img1'] = $img1;
    $fields['send_img2'] = $img2;
    //스텝문자 idx, 이벤트요청 idx
    if ($sms_idx != "")
        $fields['sms_idx'] = $sms_idx;
    if ($sms_detail_idx != "")
        $fields['sms_detail_idx'] = $sms_detail_idx;
    if ($request_idx != "")
        $fields['request_idx'] = $request_idx;
    if ($gd_id != "")
        $fields['gd_id'] = $gd_id;
    if ($or_id != "")
        $fields['or_id'] = $or_id;
    //수신거부
    $fields['send_deny_msg'] = $send_deny_msg;
    //동의상태
    $fields['send_agreement_yn'] = $send_agreement_yn;
    //제한값들
    $send_go_user_cnt = array();
    $send_go_user_cnt[0] = $row['user_cnt'];
    $fields['send_go_user_cnt'] = $send_go_user_cnt;
    $send_go_max_cnt = array();
    $send_go_max_cnt[0] = $row['daily_limit_cnt_user'];
    $fields['send_go_max_cnt'] = $send_go_max_cnt;
    $send_go_memo2 = array();
    $send_go_memo2[0] = $row['memo2'];
    $fields['send_go_memo2'] = $send_go_memo2;
    $send_go_cnt1 = array();
    $send_go_cnt1[0] = $row['cnt1'];
    $fields['send_go_cnt1'] = $send_go_cnt1;
    $send_go_cnt2 = array();
    $send_go_cnt2[0] = $row['cnt2'];
    $fields['send_go_cnt2'] = $send_go_cnt2;
    //문자발송타입
    $fields['send_type'] = $type;
    //기타
    $fields['key'] = "sendkey2022";
    $fields['send_chk'] = "";
    $fields['send_save_mms'] = "";
    $fields['send_agree_msg'] = "N";
    $fields['send_deny_wushi_0'] = "ok";
    $fields['send_deny_wushi_1'] = "ok";
    $fields['send_deny_wushi_2'] = "";
    $fields['send_deny_wushi_3'] = "";
    $fields['send_ssh_check'] = "";
    $fields['send_ssh_check2'] = "";
    $fields['send_ssh_check3'] = "";
    $fields['send_onebook_status'] = "N";
    $fields['send_go_remain_cnt'] = "";
    $fields['send_cnt'] = 1;
    $fields['free_use'] = "N";
    //딜레이
    $fields['send_delay'] = 5;
    $fields['send_delay2'] = 15;
    $fields['send_close'] = 24;

    $headers = array('Accept: application/json, text/plain, */*', 'Cache_Control: no-cache', 'content-type:multipart/form-data;');
    //$headers = array('Accept: application/json, text/plain, */*', 'Cache_Control: no-cache', 'content-type:application/json;');
    //curl_setopt($ch_mms, CURLOPT_URL, "https://kiam.kr/ajax/heineken.php");
    curl_setopt($ch_mms, CURLOPT_URL, "https://nm.kiam.kr/ajax/heineken.php");
    curl_setopt($ch_mms, CURLOPT_POST, true);
    curl_setopt($ch_mms, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch_mms, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch_mms, CURLOPT_POSTFIELDS, $fields);
    //curl_setopt($ch_mms, CURLOPT_VERBOSE, true);

    $mms_result = curl_exec($ch_mms);
    if ($mms_result === FALSE) {
        //die(curl_error($ch_mms));
        $ret = "fail=>" . curl_error($ch_mms);
    } else {
        $result = json_decode($mms_result);
        $ret = "success=>" . $result->msg;
    }
    curl_close($ch_mms);
    return $ret;
}
mysqli_close($self_con);
?>