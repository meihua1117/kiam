<?php
include_once "../lib/rlatjd_fun.php";

function IntervalDays($CheckIn,$CheckOut){
    $CheckInX = explode("-", $CheckIn);
    $CheckOutX =  explode("-", $CheckOut);
    $date1 =  mktime(0, 0, 0, $CheckInX[1],$CheckInX[2],$CheckInX[0]);
    $date2 =  mktime(0, 0, 0, $CheckOutX[1],$CheckOutX[2],$CheckOutX[0]);
    $interval =($date2 - $date1)/(3600*24);

    // returns numberofdays
    return  $interval ;

}

function check_join_tables($tables, $join_table) {
    if($join_table == '') {
        return false;
    }
    foreach($tables as $table) {
        if($table['table'] == $join_table) {
            return false;
        }
    }
    return true;
}

function sendPush($url, $headers, $pkey, $sidx, $send_type, $send_num)
{

    $title='{"MMS Push"}';
    $message='{"Send":"Start","idx":"'.$sidx.'","send_type":"'.$send_type.'"}';
    $fields = array ( 
        'data' => array (
                    "body" => $message,
                    "title" => $title)
    );
    

    if(is_array($pkey)) {
        $fields['registration_ids'] = $pkey;
    } else {
        $fields['to'] = $pkey;
    }
    $fields['token'] = $pkey;

    $fields['priority'] = "high";

    //$fields = json_encode ($fields);
    $fields = http_build_query($fields);
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, "https://nm.kiam.kr/fcm/send_fcm.php" );
    curl_setopt ( $ch, CURLOPT_POST, true );
    //curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0); 
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0);

    $result = curl_exec ( $ch );
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    } 
    //print_R($result);
    $json = json_decode($result);
    $msg = "";
    $msg = $json->results[0]->error;
    curl_close ( $ch );
    
    $query = "insert into Gn_MMS_PUSH set send_num='".$send_num."',
                                          idx='".$sidx."',
                                          token='".$pkey."',
                error='$msg'";

    mysqli_query($self_con,$query) or die(mysqli_error($self_con));        

}


set_time_limit(0);
$debug_mode = false;
$ret = array('status'=>'1', 'msg'=>'');

$url = 'https://fcm.googleapis.com/fcm/send';
$headers = array (
    'Authorization: key=' . GOOGLE_SERVER_KEY,
    'Content-Type: application/json'
);



$step_idx = $_REQUEST['step_idx'];
$totalCount = $_REQUEST['totalCount'];
$self_memo = $_REQUEST['self_memo'];
if($step_idx != '') {
    $is_step_reservation = true;
    $step_sql = "select * from Gn_event_sms_info where sms_idx = '{$step_idx}'";
    $step_res = mysqli_query($self_con,$step_sql);
    $time = 60 - date("i");
    $interval = IntervalDays(date("Y-m-d", time()), $reservation_date);
}else {
    $title = $_REQUEST['title'];;
    $text = $_REQUEST['text'];
    $rday = $_REQUEST['rday'];
    $htime = $_REQUEST['htime'];
    $mtime = $_REQUEST['mtime'];
    $send_img = $_REQUEST['send_img'];
    $send_img1 = $_REQUEST['send_img1'];
    $send_img2 = $_REQUEST['send_img2'];

    $save_mms_s = $_REQUEST['save_mms_s'];
}

$memberList = array();

$searchParam = $_REQUEST['send_list'];
$searchParam = str_replace('\\', '', $searchParam);
$searchParam = json_decode($searchParam, 1);
$ret = array('status'=>'1', 'count'=>'0');
$joined_tables = array();
$search = array();
$from_table = "";

$keyList = array();
$wheres = array();

foreach($searchParam as $param) {
    $from = isset($param['from']) ? $param['from'] : '1800-01-01';
    $to = isset($param['to']) ? $param['to'] :'2500-01-01';
    $value = isset($param['value']) ? $param['value'] : "";
    $from_login_count = isset($param['from_count']) ? $param['from_count'] : "0";
    $from_login_count = $from_login_count == '' ? '0' : $from_login_count;
    $to_login_count = isset($param['to_count']) ? $param['to_count'] : "10000000000000";
    $to_login_count = $to_login_count == '' ? '0' : $to_login_count;

    $key = $param['key'];
    $details = $param['details'];
    if($key == "keyword") {
        $values = explode(",", $value);
        $likes = array();
        foreach($values as $keyword) {
            $likes[] = "Gn_Iam_Name_Card.card_keyword like '%{$keyword}%'";
        }
        $like = implode(" AND ", $likes);
    }else {
        $like = "";
    }

    $join_tables = array(
        'phone'=>array('Gn_Member', '', ''),
        'member'=>array('Gn_Member', 'Gn_MMS_Number', 'Gn_MMS_Number.mem_id =Gn_Member.mem_id'),
        'product'=>array('tjd_pay_result', 'Gn_Member', 'Gn_Member.mem_id =tjd_pay_result.buyer_id'),
        'level'=>array('Gn_Member', '', ''),
        'selling_level'=>array('Gn_Member', '', ''),
        'iam_level'=>array('Gn_Member', 'Gn_Iam_Service', 'Gn_Iam_Service.mem_id = Gn_Member.mem_id'),
        'selling'=>array('Gn_Member', '', ''),
        'iam_sell'=>array('Gn_Iam_Service', 'Gn_Member', 'Gn_Member.mem_id = Gn_Iam_Service.mem_id'),
        'region'=>array('Gn_Member', '', ''),
        'age'=>array('Gn_Member', '', ''),
        'keyword'=>array('Gn_Member', 'Gn_Iam_Name_Card', 'Gn_Iam_Name_Card.mem_id = Gn_Member.mem_id'),
        'recommend'=>array('(SELECT *, COUNT(*) as cnt FROM Gn_Member GROUP BY recommend_id) AS Gn_Member', '', ''),
        'login_count'=>array('Gn_Member', '', ''));
    $search_conditions = array(
        'phone'=>array(
            'all'=>"1=1",
            'number'=>"FIND_IN_SET(REPLACE(Gn_Member.mem_phone, '-', ''), '{$value}')"
        ),
        'member'=>array(
            'all'=>"1=1",
            'owner'=>"REPLACE(Gn_Member.mem_phone,'-','') = REPLACE(Gn_MMS_Number.sendnum, '-','') and Gn_MMS_Number.sendnum is not null and Gn_MMS_Number.sendnum != ''",
            'add'=>"REPLACE(Gn_Member.mem_phone,'-','') != REPLACE(Gn_MMS_Number.sendnum,'-','') and Gn_MMS_Number.sendnum is not null and Gn_MMS_Number.sendnum != ''"
        ),
        'product'=>array(
            'all'=>"1=1",
            'standard'=>"tjd_pay_result.member_type = 'standard'",
            'professional'=>"tjd_pay_result.member_type = 'professional'",
            'enterprise'=>"tjd_pay_result.member_type = 'enterprise'",
            'year-professional'=>"tjd_pay_result.member_type = 'year-professional'",
            'dber'=>"tjd_pay_result.member_type = 'dber'"
        ),
        'level'=>array(
            'all'=>"1=1",
            'normal'=>"Gn_Member.mem_leb = 22",
            'work'=>"Gn_Member.mem_leb = 50",
            'speaker'=>"Gn_Member.mem_leb = 21",
            'painter'=>"Gn_Member.mem_leb = 60"
        ),
        'selling_level'=>array(
            'all'=>"1=1",
            'normal'=>"Gn_Member.service_type = 0",
            'reseller'=>"Gn_Member.service_type = 1",
            'seller'=>"Gn_Member.service_type = 2",
            'seller_domain'=>"Gn_Member.site like '%{$value}%'"
        ),
        'iam_level'=>array(
            'all'=>"1=1",
            'free'=>"Gn_Member.mem_iam_type='0'",
            'pay'=>"Gn_Member.mem_iam_type = '1'",
            'reseller'=>"Gn_Iam_Service.manager_name = '리셀러'",
            'seller_start'=>"Gn_Iam_Service.manager_name = '분양자' and Gn_Iam_Service.status = 'Y'",
            'seller_stop'=>"Gn_Iam_Service.manager_name = '분양자' and Gn_Iam_Service.status <> 'Y'",
            // 'seller_domain'=>"Gn_Iam_Service.sub_domain = '{$value}'",
            'seller_domain'=>"Gn_Member.site_iam = '{$value}'",
            'person'=>"Gn_Member.mem_iam_type = '2'",
            'work'=>"Gn_Member.mem_iam_type = '3'",
            'company'=>"Gn_Member.mem_iam_type = '4'",
            'stage'=>"Gn_Member.mem_iam_type = '5'"
        ),
        'selling'=>array(
            'all'=>"1=1",
            'normal'=>"Gn_Member.service_type = 0",
            'reseller'=>"Gn_Member.service_type = 1",
            'seller'=>"Gn_Member.service_type = 2"
        ),
        /*'iam_sell'=>array(
            'all'=>"1=1",
            'start'=>"a.status = 'Y'",
            'stop'=>"a.status <> 'Y'",
            'special'=>"b.sub_domain = '{$value}'"
        ),*/
        'region'=>array(
            'all'=>"1=1",
            'korea'=>"Gn_Member.mem_add1 like '%대한민국%'",
            'province'=>"Gn_Member.mem_add1 like '%{$value}%'",
            'city'=>"Gn_Member.mem_add1 like '%{$value}%'",
            'town'=>"Gn_Member.mem_add1 like '%{$value}%'"
        ),
        'age'=>array(
            'all'=>"1=1",
            'special'=>"Gn_Member.mem_birth >= '{$from}' and Gn_Member.mem_birth < '{$to}'"
        ),
        'keyword'=>array(
            'all'=>"1=1",
            'special'=>"{$like}"
        ),
        'recommend'=>array(
            'id'=>"Gn_Member.recommend_id = '{$value}'",
            'all'=>"1=1",
            'count'=>"Gn_Member.cnt >= {$from} and Gn_Member.cnt < {$to}"
        ),
        'login_count'=>array(
            'period'=>"Gn_Member.login_date >= '{$from}' and Gn_Member.login_date <= '{$to}' AND Gn_Member.visited >= {$from_login_count} and Gn_Member.visited <= {$to_login_count}",
            'count'=>"Gn_Member.visited >= {$from_login_count} and Gn_Member.visited <= {$to_login_count} AND Gn_Member.login_date >= '{$from}' and Gn_Member.login_date <= '{$to}'"
    ));
    if($key == '') {
        $ret['status'] = '0';
        echo json_encode($ret);
        return;
    }
    if($details == '') {
        $ret['status'] = '0';
        echo json_encode($ret);
        return;
    }

    /***** =============== select count ============= *******/
    /*if($key == "recommend" && $details[0] == "count" && $from == 0) {
        $query = "SELECT recommend_id FROM Gn_Member GROUP BY recommend_id";
        $res  = mysqli_query($self_con,$query);
        $recommends =mysqli_fetch_
        while($row = mysqli_fetch_array($res)) {
            $recommends[] = $row['recommend_id'];
        }
        $in = implode("','", $recommends);
        $search[$key] = "Gn_Member.mem_id not in ('{$in}')";
        $from_table = 'Gn_Member';
    }else if($key == "recommend" && $details[0] == "id"){
        $from_table = 'Gn_Member';
        $search[$key] = "Gn_Member.recommend_id = '{$value}'";
    }else*/ {
        $conditions = $search_conditions[$key];
        $join_table = $join_tables[$key];
        if(!in_array($key, $keyList))
        {
            $wheres = array();
            array_push($keyList, $key);
        }
        foreach($details as $detail) {
            if(array_key_exists($detail, $conditions)) {
                $wheres[] = $conditions[$detail];
            }
        }

        if(count($wheres) == 1 )
            $where = $wheres[0];
        else{
            $combine = ") OR (";
            if($key == "region")
                $combine = ") AND (";
            $where = "(".implode($combine, $wheres).")";
        }
        //$where = count($wheres) == 1 ? $wheres[0] : "(".explode(") OR (", $wheres).")";
        $search[$key] = $where;
        $from_table = ($from_table != 'Gn_Member' & $from_table != '') ? $from_table : $join_table[0];
        if(check_join_tables($joined_tables, $join_table[1])) {
            $join = array();
            $join['table'] = $join_table[1];
            $join['on'] = $join_table[2];
            if($key == "product" && $join_table[1] != '') {
                $join['dir'] = 'INNER';
            }else {
                $join['dir'] = 'LEFT';
            }
            $joined_tables[] = $join;
        }
    }
}

$query = "";
if($from_table != '') {
    $query = "SELECT Gn_Member.mem_id as mem_id, Gn_Member.mem_phone as mem_phone FROM {$from_table} ";
}

if(count($joined_tables) > 0) {
    $join_query = "";
    foreach($joined_tables as $join) {
        if($join['table'] == 'Gn_Member') {
            $join_query = " {$join['dir']} JOIN {$join['table']} ON {$join['on']} ".$join_query;
        }else {
            $join_query .= " {$join['dir']} JOIN {$join['table']} ON {$join['on']}";
        }
    }
    $query.= $join_query;
}

if(count($search) > 0) {
    $where = implode(") AND (", $search);
    $where = "(".$where.")";
    $query .= " WHERE {$where}";
}

// file_put_contents("query.txt", "condition:".print_r($searchParam, 1), FILE_APPEND);
// file_put_contents("query.txt", $query."\n", FILE_APPEND);
$res = mysqli_query($self_con, $query);
while($row = mysqli_fetch_array($res)) {
    $memberList[] = $row;
}
if($rday != '') {
    $reservation = $rday." ".$htime.":".$mtime.":00"; // 예약시간
    $now_date = date("Y-m-d H:i:s");
    if($now_date > $reservation) {
        $ret['status'] = '2';
        $ret['msg'] = "지금보다 예약하기 이전시간입니다.";
        echo json_encode($ret);
        return;
    }
}

$ad_msg = "";
$sql_ad = "select 
                        `gaid`, `client`, `start_date`, `send_start_date`, `send_end_date`, `send_count`, `title`, `content`, `img_path`, `sort_order` 
                    from Gn_Ad 
                where status='C' 
                order by sort_order asc, regdate desc";
$res_ad = mysqli_fetch_$sql_ad);
$row_ad = mysqli_fetch_array($res_ad);
$ad_msg = $row_ad['content'];
if($row_ad[img_path] == "") {
    $ad_msg .= $domain_url.$row_ad['img_path'];
}else {
    //$ad_msg .= $domain_url;
    $img = "http://www.kiam.kr/".$row_ad['img_path'];
}

// 발송형태
$sendType = 0; // 1-묶음발송,1-개별발송
// 발송간격
$sendDelay = 1;
$sendDelay2 = 1;
// 발송제한
$sendClose = 24;

$reg = time();
$req = $reg;
// 발송목록
$sendedList = array();
foreach($memberList as $member) {
    $send_num = str_replace("-","",$member['mem_phone']);
    if(in_array($send_num, $sendedList))
        continue;
 
    if($step_idx != ''mysqli_fetch_
        while($lrow = mysqli_fetch_array($step_res)) {
            $mem_id = $member['mem_id'];
            $sms_idx = $lrow['sms_idx'];
            $reservation_title = $lrow['reservation_title'];

            //알람등록
            /*$sql="select * from Gn_event_sms_info where sms_idx='$sms_idx'";
            //echo $sql;
            $resumysqli_fetch_ry($sql) or die(mysqli_error($self_con));
            $row=mysqli_fetch_array($result);*/

            $reg = time();
            $sql="select * from Gn_event_sms_step_info where sms_idx='$sms_idx'";

            $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $k = 0;mysqli_fetch_
            while($row=mysqli_fetch_array($result)) {
                // 시간 확인
                $k++;
                $send_day = $row['send_day'];
                $send_time = $row['send_time'];
                if($send_time == "") $send_time = "09:30";
                if($send_time == "00:00") $send_time = "09:30";
                if($send_day == "0")
                    $reservation = "";
                else
                    $reservation = date("Y-m-d $send_time:00",strtotime("+$send_day days"));

                $jpg = $jpg1 = $jpg2 = '';
                if($row['image'])
                    $jpg = "http://www.kiam.kr/adjunct/mms/thum/".$row['image'];
                if($row['image1'])
                    $jpg1 = "http://www.kiam.kr/adjunct/mms/thum/".$row['image1'];
                if($row['image2'])
                    $jpg2 = "http://www.kiam.kr/adjunct/mms/thum/".$row['image2'];

                // $ret = sendmms(5, $mem_id, $send_num, $send_num, $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, "Y", $row['sms_idx'], $row['sms_detail_idx']);
                // if($ret != "fail")
                //      $sendedList[] = $send_num;
                $queryn = "select * from Gn_MMS_Number where mem_id='$mem_id' and sendnum='$send_num'";
                $resultnmysqli_fetch_ry($queryn);
                $rown = mysqli_fetch_array($resultn);
                if($rown['pkey'] != "")
                {
                    $uni_id=$reg.sprintf("%02d",$k).sprintf("%02d",0);
                    $query = "insert into Gn_MMS set mem_id='$mem_id',
                                                     send_num='$send_num',
                                                     recv_num='$send_num',
                                                     uni_id='$uni_id',
                                                     content='$row[content]',
                                                     title='$row[title]',
                                                     type='5',
                                                     jpg='$jpg',
                                                     jpg1='$jpg1',
                                                     jpg2='$jpg2',
                                                     agreement_yn='Y',
                                                     reg_date=NOW(),
                                                     reservation='$reservation',
                                                     sms_idx='$row[sms_idx]',
                                                     sms_detail_idx='$row[sms_detail_idx]',
                                                     request_idx='',
                                                     self_memo='$self_memo',
                                                     recv_num_cnt=1                                      
                                                     
                    ";
                    mysqli_query($self_con,$query) or die(mysqli_error($self_con));
                    $last_id = mysqli_insert_id($self_con); 
                    
                    if($reservation == "")
                        sendPush($url, $headers, $rown['pkey'], $last_id, 5, $send_num);

                    //발송폰리스트애 추가한다.
                    if(!in_array($send_num, $sendedList))
                        $sendedList[] = $send_num;
 
                    
                    $query = "insert into Gn_MMS_Agree set mem_id='$mem_id',
                                                     send_num='$send_num',
                                                     recv_num='$send_num',
                                                     content='$row[content]',
                                                     title='$row[title]',
                                                     jpg='$jpg',
                                                     reg_date=NOW(),
                                                     reservation='$reservation',
                                                     sms_idx='$row[sms_idx]',
                                                     sms_detail_idx='$row[sms_detail_idx]',
                                                     request_idx=''
                            ";
                    //echo $query."<BR>";
                    mysqli_query($self_con,$query) or die(mysqli_error($self_con));
                }
                    

            }
        }
    }else {
        $mms_info[delay]=$sendDelay;
        $mms_info[delay2]=$sendDelay2;
        $mms_info[close]=$sendClose;
        // $ret = sendmms(5, $member['mem_id'], $send_num, $send_num, $reservation, htmlspecialchars($title), addslashes(htmlspecialchars($text)), $send_img, $send_img1, $send_img2, "N");
        // if($ret != "fail")
        //     $sendedList[] = $send_num;
        $queryn = "select * from Gn_MMS_Number where mem_id='$member[mem_id]' and sendnum='$send_num'";
        $resultnmysqli_fetch_ry($queryn);
        $rown = mysqli_fetch_array($resultn);
        if($rown['pkey'] != "")
        {
            $content = addslashes(htmlspecialchars($text));
            $title = htmlspecialchars($title);
            //file_put_contents("query.txt", $send_img."\n", FILE_APPEND);
            $query = "insert into Gn_MMS set mem_id='$member[mem_id]',
                                                         send_num='$send_num',
                                                         recv_num='$send_num',
                                                         uni_id='$req',
                                                         content='$content',
                                                         title='$title',
                                                         type='5',
                                                         delay='$sendDelay2',
                                                         delay2='$sendDelay2',
                                                         close='$sendClose',
                                                         jpg='$send_img',
                                                         jpg1='$send_img1',
                                                         jpg2='$send_img2',
                                                         agreement_yn='N',
                                                         reg_date=NOW(),
                                                         reservation='$reservation',
                                                         self_memo='$self_memo',
                                                         recv_num_cnt=1";
            mysqli_query($self_con,$query) or die(mysqli_error($self_con));
            $last_id = mysqli_insert_id($self_con); 
   
            if($reservation == "")
                sendPush($url, $headers, $rown['pkey'], $last_id, 5, $send_num);
            
            //발송폰리스트애 추가한다.
            if(!in_array($send_num, $sendedList))
                $sendedList[] = $send_num;
    
            if($save_mms_s == 'ok')
            { //메시지 저장
                if($send_img != '') //메시지 타입
                    $message_info[msg_type]="B";
                else
                    $message_info[msg_type]="A";
                $sql="insert into Gn_MMS_Message set "; //발송
                $message_info[mem_id]=$member['mem_id'];
                $message_info[title]=htmlspecialchars(str_replace("{|name|}", "{|REP|}",$title));
                $message_info[message]=htmlspecialchars(str_replace("{|name|}", "{|REP|}",$text));
                $message_info[img]=$send_img;
                $message_info[img1]=$send_img1;
                $message_info[img2]=$send_img2;
                foreach($message_info as $key=>$v)
                {
                    $sql.=" $key='$v' , ";
                }
                $sql.=" reg_date=now() ";
                if($debug_mode == false) {
                    mysqli_query($self_con,$sql);
                }
            }
        }

    }
}

$ret['status'] = '1';
$ret['msg'] = '요청하신 '.$totalCount.'건의 대상에 대한 셀폰 발송처리가 완료되었습니다.';
echo json_encode($ret);

?>
