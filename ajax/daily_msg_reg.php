<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
date_default_timezone_set('Asia/Seoul');
$cur_time = date("Y-m-d H:i:s");
$date_today=date("Y-m-d");
$cur_time1 = date("YmdHis");
extract($_POST);
$member_id = $_POST['member_id'];//신청회원 아이디
$event_idx = $_POST['event_idx'];

$mem_point = 0;
$cur_time_com_mms = time() - (86400 * 3);
$cur_time_mms_com = date("Y-m-d H:i:s", $cur_time_com_mms);
$s = 0;

$sql_point = "select key_content from Gn_Search_Key where key_id='dailymsg_set_point'";
$res_point = mysqli_query($self_con,$sql_point);
$row_point = mysqli_fetch_array($res_point);
$daily_set_point = $row_point['key_content'];

$sql_mem = "select * from Gn_Member where mem_id='{$member_id}'";
$res_mem = mysqli_query($self_con,$sql_mem);
if(mysqli_num_rows($res_mem) == 0){
    echo 3;
    exit;
}
else{
    $row_mem = mysqli_fetch_array($res_mem);
    $mem_name = $row_mem['mem_name'];
    $mem_phone = $row_mem['mem_phone'];
    $method = $member_id . "/" . $mem_name;
}

$sql_send_num = "select sendnum from Gn_MMS_Number where mem_id='{$member_id}' order by sort_no asc, user_cnt desc , idx desc limit 1";
$res_send_num = mysqli_query($self_con,$sql_send_num);
$row_send_num = mysqli_fetch_array($res_send_num);
if($row_send_num[0] == ""){
    $send_num = $mem_phone;
}
else{
    $send_num = $row_send_num[0];
}

$sql_data = "select * from Gn_event where event_idx={$event_idx}";
$res_data = mysqli_query($self_con,$sql_data);
$row_data = mysqli_fetch_array($res_data);
$mem_id1 = $row_data['m_id'];//분양사 아이디

$up_img = $row_data['object'];
$img_name_arr = array();
if($up_img != ""){
    if(strpos($up_img, ",") !== false){
        $img_name_arr = explode(",", $up_img);
    }
    else{
        $img_name_arr[0] = $up_img;
    }
}

$sql="select * from Gn_MMS_Group where  mem_id='".$member_id."' and grp='아이엠'";
$sresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$krow = mysqli_fetch_array($sresult);    

$sql="select count(*) cnt from Gn_MMS_Receive_Iam where  mem_id='".$member_id."' and grp_id='$krow[idx]'";
$sresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$skrow = mysqli_fetch_array($sresult);

$send_day = "";
$day = ceil($skrow[cnt] / (int)$row_data['callback_no']);
for($i = 1; $i <= $day;$i++) {
    if($i == $day) $comma = "";
    else $comma = ",";
    $send_day .= date("Y-m-d", strtotime("+$i day")) . $comma;
}

$sql_memdata = "select * from Gn_Member where mem_id='{$mem_id1}'";
$res_memdata = mysqli_query($self_con,$sql_memdata);
$row_memdata = mysqli_fetch_array($res_memdata);
$point = $row_memdata['mem_point'];
$cash = $row_memdata['mem_cash'];

if($point < $daily_set_point){
    echo 2; exit;
}

$k = 0;
$kk = 0;

if($row_data['daily_req_link'] == ''){
    $sql_req_mem_card = "select card_short_url from Gn_Iam_Name_Card where mem_id='{$member_id}' order by idx asc limit 1";
    $res_req_mem_card = mysqli_query($self_con,$sql_req_mem_card);
    $row_req_mem_card = mysqli_fetch_array($res_req_mem_card);

    if($row_mem['site_iam'] == "obmms"){
        $site_iam = "www";
    }
    else{
        $site_iam = $row_mem['site_iam'];
    }
    $txt = $row_data['event_req_link'] . "    https://".$site_iam.".kiam.kr/?" . $row_req_mem_card[0].$row_mem['mem_code'];
}
else{
    $txt = $row_data['event_req_link'];
}

$total_count = $skrow[cnt];
$iam = 1;
$title = $row_data['event_info'];
$send_num = $mem_phone;
$group_idx = $krow['idx'];
$address_name = "아이엠";
$daily_cnt = $row_data['callback_no'];
$htime = $row_data['event_sms_desc'];
$mtime = $row_data['event_type'];
$upimage_str = $img_name_arr[0];
$upimage_str1 = "";
$upimage_str2 = "";
$send_date = $send_day;
$mem_id = $member_id;
$event_idx = $event_idx;

$reg = time();
$uni_id=$reg.sprintf("%02d",$k);
$date = explode(",", $send_date);
$end_date = max($date);
$start_date = min($date);

$deny = "";

$query = "
insert into Gn_daily set mem_id='{$mem_id}', 
                            iam='$iam',
                            send_num='$send_num',
                            group_idx='$group_idx',
                            total_count='$total_count',
                            title='$title',
                            content='$txt',
                            daily_cnt='$daily_cnt',
                            start_date='$start_date',
                            end_date='$end_date',
                            jpg='$upimage_str',
                            jpg1='$upimage_str1',
                            jpg2='$upimage_str2',
                            status='Y',
                            reg_date=NOW(),
                            htime='$htime',
                            mtime='$mtime',
                            send_deny='$deny',
                            event_idx='$event_idx';
                            
";
mysqli_query($self_con,$query);    
//echo $query."<BR>";
$gd_id = mysqli_insert_id($self_con);

$sql="select * from Gn_MMS_Receive where grp_id = '$group_idx' ";
$sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
while($srow=mysqli_fetch_array($sresult)) {
    if($kk == $daily_cnt) { 
        $k++;
        $kk = 0;
    }                
    if($kk > 0)
        $recv_num_set[$k] .= ",".$srow['recv_num'];
    else
        $recv_num_set[$k] = $srow['recv_num'];
    $kk++;
}

for($i=0;$i <count($date);$i++) {
    $reservation = $date[$i]." ".$htime.":".$mtime.":00";
    sendmms(6, $mem_id, $send_num, $recv_num_set[$i], $reservation, $title, $txt, $upimage_str, $upimage_str1, $upimage_str2, 'Y', "", "", "", $gd_id, $deny);
}
            
for($i=0;$i <count($date);$i++) {
    $query = "insert into Gn_daily_date set gd_id='$gd_id',
                                            send_date='$date[$i]',
                                            recv_num='$recv_num_set[$i]'";
    mysqli_query($self_con,$query);    
    //echo $query."<BR>";;
}

// $ch_daily = curl_init();

// $fields['mode'] = "daily_save";
// $fields['total_count'] = $skrow[cnt];
// $fields['iam'] = 1;
// $fields['title'] = $row_data['event_info'];
// $fields['txt'] = $row_data['event_req_link'];
// $fields['send_num'] = $send_num;
// $fields['group_idx'] = $krow['idx'];
// $fields['address_name'] = "아이엠";
// $fields['daily_cnt'] = $row_data['callback_no'];
// $fields['htime'] = $row_data['event_sms_desc'];
// $fields['mtime'] = $row_data['event_type'];
// $fields['upimage_str'] = $img_name_arr[0];
// $fields['upimage_str1'] = "";
// $fields['upimage_str2'] = "";
// $fields['send_date'] = $send_day;
// $fields['mem_id'] = $member_id;
// $fields['event_idx'] = $event_idx;

// $headers = array('Accept: application/json, text/plain, */*', 'Cache_Control: no-cache', 'content-type:mutipart/form-data;');

// curl_setopt($ch_daily, CURLOPT_URL, "http://kiam.kr/mypage.proc.php");
// curl_setopt($ch_daily, CURLOPT_POST, true);
// //curl_setopt($ch_daily, CURLOPT_HTTPHEADER, $headers );
// curl_setopt($ch_daily, CURLOPT_RETURNTRANSFER, true);
// curl_setopt($ch_daily, CURLOPT_POSTFIELDS, http_build_query($fields));

// $mms_result = curl_exec($ch_daily);
// if($mms_result === FALSE){
//     //die(curl_error($ch_daily));
//     $ret = "신청시 오류가 발생하였습니다.";
// }
// curl_close($ch_daily);

$sql_service = "select * from Gn_Iam_Service where mem_id='{$mem_id1}'";//분양사아이디이면.
$res_service = mysqli_query($self_con,$sql_service);
if(mysqli_num_rows($res_service)){
    $row = mysqli_fetch_array($res_service);
    if($row['daily_point_end'] < $date_today){
        $min_point = $daily_set_point * 1;
    }
    else{
        if($row['daily_set_point'] == ''){
            $min_point = $daily_set_point * 1;
        }
        else if($row['daily_set_point'] == 0){
            $min_point = 0;
        }
        else{
            $min_point = $row['daily_set_point'] * 1;
        }
    }
}
else{
    $min_point = $daily_set_point * 1;
}

$sql_insert = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id1}', buyer_tel='{$row_memdata['mem_phone']}', item_name='데일리메시지', item_price={$min_point}, pay_percent=90, current_point={$point}-{$min_point}, current_cash='{$cash}', pay_status='Y', VACT_InputName='{$row_memdata[mem_name]}', type='use', pay_method='{$method}', pay_date=now(), point_val=1";
mysqli_query($self_con,$sql_insert);

$sql_point_update = "update Gn_Member set mem_point=mem_point-{$min_point} where mem_id='{$mem_id1}'";
$result = mysqli_query($self_con,$sql_point_update) or die(mysqli_error($self_con));

$sql_point = "select mem_id, mem_point, mem_phone from Gn_Member where mem_id='{$mem_id1}'";
$res_point = mysqli_query($self_con,$sql_point);
$row_point = mysqli_fetch_array($res_point);

$mem_phone = str_replace('-', '', $row_point['mem_phone']);
$point = (int)$row_point['mem_point'];
if($point <= 10000 && $point > 5000) $mem_point = 10000;
if($point <= 5000 && $point > 3000) $mem_point = 5000;
if($point <= 3000) $mem_point = 3000;

$s++;
$uni_id=time().sprintf("%02d",$s);
if($mem_point != 0){
    $sql_mms_send = "select reg_date, recv_num from Gn_MMS where title='포인트 충전 안내' and content='".$mem_id1.", 고객님의 잔여 포인트가 ".$mem_point." 포인트 이하입니다. 포인트가 부족할 경우 현재 이용중이신 기능이 중지되오니 충전해주시길 바랍니다. 감사합니다.' and mem_id='{$mem_id1}' order by idx desc limit 1";
    $res_mms_send = mysqli_query($self_con,$sql_mms_send);
    if(mysqli_num_rows($res_mms_send)){
        while($row_mms_send = mysqli_fetch_array($res_mms_send)){
            $reg_date_msg = $row_mms_send['reg_date'];
            if($reg_date_msg < $cur_time_mms_com){
                send_mms($mem_id1, $mem_phone, $uni_id, $mem_point);
            }
        }
    }
    else{
        send_mms($mem_id1, $mem_phone, $uni_id, $mem_point);
    }
}
    
echo 1;
exit;
function send_mms($mem_id, $mem_phone, $uni_id, $mem_point){
	$title = "포인트 충전 안내";
	$txt = $mem_id.", 고객님의 잔여 포인트가 ".$mem_point." 포인트 이하입니다. 포인트가 부족할 경우 현재 이용중이신 기능이 중지되오니 충전해주시길 바랍니다. 감사합니다.";

    sendmms(5, $mem_id, $mem_phone, $mem_phone, "", $title, $txt, "", "", "", "Y");

}
?>