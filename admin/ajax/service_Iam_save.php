<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$idx = $_POST["idx"];
if($_POST['mode'] == "creat") {
    $head_logo = gcUploadRename($_FILES['head_logo']["name"],$_FILES["head_logo"][tmp_name],$_FILES["head_logo"][size], "data/site");
    $footer_logo = gcUploadRename($_FILES['footer_logo']["name"],$_FILES["footer_logo"][tmp_name],$_FILES["footer_logo"][size], "data/site");
    if(count(explode(".",$sub_domain)) == 1)
        $sub_domain = "http://".$sub_domain.".kiam.kr";
    if(count(explode(".",$main_domain)) == 1)
        $main_domain = "http://".$main_domain.".kiam.kr";
    $domain = $sub_domain;
    $parse = parse_url($sub_domain);
    $site = explode(".", $parse['host']);
    $query = "update Gn_Member set site_iam = '$site[0]' where mem_id = '$mem_id'";
    mysqli_query($self_con,$query);
    $name_card_sql = "select * from Gn_Iam_Name_Card where group_id is NULL and mem_id = '$mem_id' order by req_data limit 0,1";
    $res = mysqli_query($self_con,$name_card_sql);
    $row = mysqli_fetch_array($res);
    if(!$row) {
        $site_name = $member_iam['site_iam'];
        $card_name = $member_iam['mem_name'];
        $card_phone = $member_iam['mem_phone'];
        $card_email = $member_iam['mem_email'];
        $card_addr1 = $member_iam['mem_add1'];
        $card_addr2 = $member_iam['mem_add2'];
        if (!strpos($card_phone, "-")) {
            if (strlen($card_phone) > 10) {
                $card_phone1 = substr($card_phone, 0, 3);
                $card_phone2 = substr($card_phone, 3, 4);
                $card_phone3 = substr($card_phone, 7, 4);
                $card_phone = $card_phone1 . "-" . $card_phone2 . "-" . $card_phone3;
            } else {
                $card_phone1 = substr($card_phone, 0, 3);
                $card_phone2 = substr($card_phone, 3, 3);
                $card_phone3 = substr($card_phone, 6, 4);
                $card_phone = $card_phone1 . "-" . $card_phone2 . "-" . $card_phone3;
            }
        }
        $card_addr = $card_addr1 . " " . $card_addr2;
        $short_url = generateRandomString();
        $img_url = "/iam/img/common/logo-2.png";
        $name_card_sql = "select main_img1,main_img2,main_img3 from Gn_Iam_Name_Card where group_id is NULL and mem_id='iam1' order by req_data limit 0,1";
        $card_result = mysqli_query($self_con,$name_card_sql);
        $card_row = mysqli_fetch_array($card_result);
        $main_img1 = $card_row['main_img1'];
        $main_img2 = $card_row['main_img2'];
        $main_img3 = $card_row['main_img3'];
        $name_card_sql = "insert into Gn_Iam_Name_Card (mem_id, card_short_url, card_name, card_phone, card_email, card_addr, profile_logo, req_data,up_data,main_img1,main_img2,main_img3) 
                                values ('$mem_id', '$short_url', '$card_name', '$card_phone', '$card_email', '$card_addr', '$img_url', now(), now(),'$main_img1','$main_img2','$main_img3')";
        mysqli_query($self_con,$name_card_sql) or die(mysqli_error($self_con));
        $profile_idx = mysqli_insert_id($self_con);
    }else{
        $profile_idx = $row['idx'];
        $name_card_sql = "select main_img1 from Gn_Iam_Name_Card where idx='$profile_idx'";
        $card_result = mysqli_query($self_con,$name_card_sql);
        $card_row = mysqli_fetch_array($card_result);
        if(!$card_row[0]) {
            $name_card_sql = "select main_img1,main_img2,main_img3 from Gn_Iam_Name_Card where group_id is NULL and mem_id='iam1' order by req_data limit 0,1";
            $card_result = mysqli_query($self_con,$name_card_sql);
            $card_row = mysqli_fetch_array($card_result);
            $main_img1 = $card_row['main_img1'];
            $main_img2 = $card_row['main_img2'];
            $main_img3 = $card_row['main_img3'];
            $name_card_sql = "update Gn_Iam_Name_Card set main_img1 = '$main_img1',main_img2 = '$main_img2',main_img3 = '$main_img3',up_data = now() where idx='$profile_idx'";
            mysqli_query($self_con,$name_card_sql) or die(mysqli_error($self_con));
        }
    }
    $cont_sql = "select count(*) from Gn_Iam_Contents where card_idx ='$profile_idx'";
    $cont_res = mysqli_query($self_con,$cont_sql);
    $cont_row = mysqli_fetch_array($cont_res);
    if($cont_row[0] == 0){
        $name_card_sql = "select card_short_url from Gn_Iam_Name_Card where idx='$profile_idx'";
        $card_result = mysqli_query($self_con,$name_card_sql);
        $card_row = mysqli_fetch_array($card_result);
        $card_short_url = $card_row['card_short_url'];

        $name_card_sql = "select idx from Gn_Iam_Name_Card where group_id is NULL and mem_id='iam1' order by req_data limit 0,1";
        $card_result = mysqli_query($self_con,$name_card_sql);
        $card_row = mysqli_fetch_array($card_result);
        $cont_sql = "select * from Gn_Iam_Contents where card_idx='{$card_row['idx']}'";
        $cont_res = mysqli_query($self_con,$cont_sql);
        $cont_row = mysqli_fetch_assoc($cont_res);
        $sql = "insert into Gn_Iam_Contents set ";
        foreach ($cont_row as $key => $v) {
            if ($key == "mem_id") {
                $v = "'".$mem_id."'";
            }else if ($key == "card_short_url" || $key == "westory_card_url") {
                $v = "'".$card_short_url."'";
            }else if ($key == "card_idx") {
                $v = $profile_idx;
            }else if($key =="req_data" || $key =="up_data"){
                $v = "'".date("Y-m-d H:i:s")."'";
            }else
                $v = "'".str_replace("'","",$v)."'";
            if ($key != "idx" && $key != "public_display" && $v != "")
                $sql .= $key . "=" . $v . ",";
        }
        $sql = substr($sql,0,strlen($sql)-1);
        mysqli_query($self_con,$sql);
        $contents_idx = mysqli_insert_id($self_con);
        $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$contents_idx,card_idx=$profile_idx,main_card=$profile_idx";
        mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));

        $sql2 = "update Gn_Iam_Name_Card set up_data = now() where idx={$profile_idx}";
        mysqli_query($self_con,$sql2);
    }
    if($service_type != 2)
        $service_price = "";
    if(!isset($_POST[status]))
        $status = 'Y';
    if(!isset($_POST[contract_start_date]))
        $contract_start_date = date("Y-m-d");
    if(!isset($_POST[contract_end_date]))
        $contract_end_date = date("Y-m-d",strtotime("+120 month"));
    $query="insert into Gn_Iam_Service set `main_domain`='$main_domain',
                                  `sub_domain` ='$sub_domain',
                                  mem_cnt ='$mem_cnt',
                                  iamcard_cnt ='$iamcard_cnt',
                                  send_content ='$send_content',
                                  my_card_cnt ='$my_card_cnt',
								  `communications_vendors` ='$communications_vendors',
                                  `privacy` ='$privacy',
                                  `email` ='$email',
                                  `company_name` ='$company_name',
                                  brand_name='$brand_name',
                                  business_number='$business_number',
                                  contract_start_date='$contract_start_date',
                                  contract_end_date='$contract_end_date',
                                  mem_id='$mem_id',
                                  mem_name='$mem_name',
                                  `owner_name`      ='$owner_name',
                                  `owner_cell`   ='$owner_cell',
                                  manager_name= '$manager_name',
                                  manager_tel= '$manager_tel',
                                  `fax` ='$fax',
                                  `status` ='$status',
                                  address ='$address',
                                  web_theme='$web_theme',
                                  home_link='$home_link',
                                  head_logo='$head_logo',
                                  profile_idx='$profile_idx',
                                  footer_logo='$footer_logo',
                                  footer_link='$footer_link',
                                  share_price='$share_price',
                                  month_price='$month_price',
				  kakao='$kakao',
				  kakao_api_key='$kakao_api_key',
                                  pay_link='$pay_link',
                                  service_type = '$service_type',
                                  service_price = '$service_price',
                                  service_price_exp = '$service_price1',
                                  `regdate`=NOW() ";
    mysqli_query($self_con,$query);
    $service_idx = mysqli_insert_id($self_con);

    $sql_mem_data = "select mem_phone from Gn_Member where mem_id='{$mem_id}'";
    $res_mem_data = mysqli_query($self_con,$sql_mem_data);
    $row_mem_data = mysqli_fetch_array($res_mem_data);

    $rand_num = rand(100, 999);
    $cur_time1 = date("YmdHis");

    //콜백메시지 디폴트 복제
    $sql_event_idx = "select duplicate_event_idx from gn_mms_callback where duplicate_event_idx!=0";
    $res_event_idx = mysqli_query($self_con,$sql_event_idx);
    $row_event_idx = mysqli_fetch_array($res_event_idx);
    if($row_event_idx['duplicate_event_idx']){
        $sql_dup_mms = "INSERT INTO gn_mms_callback(title, content, img, iam_link, type, regdate, service_state, allow_state, mem_sel_cnt, mb_id, duplicate_event_idx) 
        (SELECT title, content, img, iam_link, type, now(), service_state, allow_state, 0, NULL, 0 FROM gn_mms_callback WHERE duplicate_event_idx!=0)";
        mysqli_query($self_con,$sql_dup_mms) or die(mysqli_error($self_con));
        $mms_idx = mysqli_insert_id($self_con);
        $event_name_eng = "callback msg reg".$cur_time1.$rand_num;
        $pcode = "callbackmsg".$cur_time1.$rand_num;
    
        $sql_dup_event = "INSERT INTO Gn_event(event_name_kor, event_name_eng, event_title, event_desc, event_info, event_sms_desc, pcode, event_type, mobile, regdate, ip_addr, m_id, short_url, read_cnt, cnt, object, callback_no, event_req_link, daily_req_link) 
        (SELECT event_name_kor, '{$event_name_eng}', event_title, event_desc, event_info, event_sms_desc, '{$pcode}', event_type, '{$row_mem_data[mem_phone]}', now(), ip_addr, '{$mem_id}', '', 0, cnt, object, {$mms_idx}, event_req_link, daily_req_link FROM Gn_event WHERE event_idx='{$row_event_idx[duplicate_event_idx]}')";
        mysqli_query($self_con,$sql_dup_event) or die(mysqli_error($self_con));
        $event_idx = mysqli_insert_id($self_con);
    
        $transUrl = $main_domain."/event/callbackmsg.php?pcode=".$pcode."&eventidx=".$event_idx;
        $transUrl = get_short_url($transUrl);
        $insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
        mysqli_query($self_con,$insert_short_url) or die(mysqli_error($self_con));
    }

    //오토회원가입메시지 디폴트 복제
    $sql_auto_event = "select auto_join_event_idx from Gn_Iam_Service where mem_id='iam1'";
    $res_auto_event = mysqli_query($self_con,$sql_auto_event);
    $row_auto_event = mysqli_fetch_array($res_auto_event);
    if($row_auto_event['auto_join_event_idx']){
        $event_name_eng = "회원IAM 신청".$cur_time1.$rand_num;
        $pcode = "aimem".$cur_time1.$rand_num;
    
        $sql_dup_event = "INSERT INTO Gn_event(event_name_kor, event_name_eng, event_title, event_desc, event_info, event_sms_desc, pcode, event_type, mobile, regdate, ip_addr, m_id, short_url, read_cnt, cnt, object, callback_no, event_req_link, daily_req_link) 
        (SELECT event_name_kor, '{$event_name_eng}', event_title, event_desc, event_info, event_sms_desc, '{$pcode}', event_type, '{$row_mem_data[mem_phone]}', now(), ip_addr, '{$mem_id}', '', 0, cnt, object, callback_no, event_req_link, daily_req_link FROM Gn_event WHERE event_idx='{$row_auto_event[auto_join_event_idx]}')";
        mysqli_query($self_con,$sql_dup_event) or die(mysqli_error($self_con));
        $event_idx = mysqli_insert_id($self_con);
        
        $query = "update Gn_Iam_Service set auto_join_event_idx='$event_idx' where idx='$service_idx'";
        mysqli_query($self_con,$query);
    
        $transUrl = $main_domain."/event/automember.php?pcode=".$pcode."&eventidx=".$event_idx;
        $transUrl = get_short_url($transUrl);
        $insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
        mysqli_query($self_con,$insert_short_url) or die(mysqli_error($self_con));
    }
    
    //데일리메시지 디폴트 복제
    $sql_daily_event = "select daily_msg_event_idx from Gn_Iam_Service where mem_id='iam1'";
    $res_daily_event = mysqli_query($self_con,$sql_daily_event);
    $row_daily_event = mysqli_fetch_array($res_daily_event);
    if($row_daily_event['daily_msg_event_idx']){
        $event_name_eng = "데일리문자 신청".$cur_time1.$rand_num;
        $pcode = "dailymsg".$cur_time1.$rand_num;
    
        $sql_dup_event = "INSERT INTO Gn_event(event_name_kor, event_name_eng, event_title, event_desc, event_info, event_sms_desc, pcode, event_type, mobile, regdate, ip_addr, m_id, short_url, read_cnt, cnt, object, callback_no, event_req_link, daily_req_link) 
        (SELECT event_name_kor, '{$event_name_eng}', event_title, event_desc, event_info, event_sms_desc, '{$pcode}', event_type, '{$row_mem_data[mem_phone]}', now(), ip_addr, '{$mem_id}', '', 0, cnt, object, callback_no, event_req_link, daily_req_link FROM Gn_event WHERE event_idx='{$row_daily_event[daily_msg_event_idx]}')";
        mysqli_query($self_con,$sql_dup_event) or die(mysqli_error($self_con));
        $event_idx = mysqli_insert_id($self_con);
    
        $transUrl = $main_domain."/event/dailymsg.php?pcode=".$pcode."&eventidx=".$event_idx;
        $transUrl = get_short_url($transUrl);
        $insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
        mysqli_query($self_con,$insert_short_url) or die(mysqli_error($self_con));
    }
} else if ($_POST['mode'] == "updat") {
    $query = "select * from Gn_Iam_Service where idx='$idx'";
    $res = mysqli_query($self_con,$query);
    $data = mysqli_fetch_array($res);    
    if($data[dup_up_state]){
        $sql_chk_mem = "select mem_id from Gn_Member where mem_id='{$mem_id}'";
        $res_chk_mem = mysqli_query($self_con,$sql_chk_mem);
        $row_chk_mem = mysqli_fetch_array($res_chk_mem);
        if($row_chk_mem['mem_id']){
            echo "<script>alert('이미 사용중인 아이디입니다.');location='/admin/service_Iam_list.php';</script>";
            exit;
        } else {
            $site1 = explode("//", $main_domain);
            $site2 = explode(".", trim($site1[1]));
            $site = trim($site2[0]);

            $site_iam1 = explode("//", $sub_domain);
            $site_iam2 = explode(".", trim($site_iam1[1]));
            $site_iam = trim($site_iam2[0]);

            $sql_mem_insert = "insert into Gn_Member set mem_id='{$mem_id}',
                                                        mem_leb=22,
                                                        web_pwd=password('{$mem_id}'),
                                                        mem_pass=md5('{$mem_id}'),
                                                        mem_name='{$mem_name}',
                                                        mem_nick='{$mem_name}',
                                                        mem_phone='{$owner_cell}',
                                                        mem_sex='',
                                                        zy='{$company_name}',
                                                        first_regist=now() ,
                                                        mem_check=now(),
                                                        mem_add1='{$address}',
                                                        mem_email='{$email}',
                                                        recommend_id = 'onlyone',
                                                        site = '{$site}',
                                                        site_iam = '{$site_iam}',
                                                        mem_birth = '',
                                                        service_type=2";
            mysqli_query($self_con,$sql_mem_insert);
        }
    }
    $head_logo = gcUploadRename($_FILES['head_logo']["name"],$_FILES["head_logo"][tmp_name],$_FILES["head_logo"][size], "data/site");
    $footer_logo = gcUploadRename($_FILES['footer_logo']["name"],$_FILES["footer_logo"][tmp_name],$_FILES["footer_logo"][size], "data/site");
    $domain = $sub_domain;
    if($service_type < 2)
        $service_price = "";
    if($service_type == 3)
        $service_price = $service_exp_date;
    if($service_type == 2 && !$service_price){
        echo "<script>alert('단체회원 금액을 입력해주세요.');history.back(-1);</script>";
        exit;
    }else if($service_type == 3 && !$service_price){
        echo "<script>alert('체험회원 일자을 입력해주세요.');history.back(-1);</script>";
        exit;
    }

    if($profile_idx == "" ||$profile_idx == 0) {
        $short_url = generateRandomString();
        $name_card_sql="insert into Gn_Iam_Name_Card (mem_id, card_short_url, req_data,up_data, domain)".
            "values ('$mem_id', '$short_url',now(), now(),'$domain')";
        mysqli_query($self_con,$name_card_sql) or die(mysqli_error($self_con));
        $profile_idx = mysqli_insert_id($self_con);        
    }
    $addQuery = "";
    if($head_logo)
        $addQuery .= "head_logo='$head_logo',";
    if($footer_logo)
        $addQuery .= "footer_logo='$footer_logo',";
    
    $query="update  Gn_Iam_Service set `main_domain` ='$main_domain', 
                                  `sub_domain` ='$sub_domain', 
                                  mem_cnt ='$mem_cnt',
                                  iamcard_cnt ='$iamcard_cnt',
                                  send_content ='$send_content',
                                  my_card_cnt ='$my_card_cnt',
								  `communications_vendors` ='$communications_vendors',
                                  `privacy` ='$privacy',
                                  `email` ='$email',
                                  `company_name` ='$company_name', 
                                  brand_name='$brand_name',
                                  business_number='$business_number',
                                  contract_start_date='$contract_start_date',
                                  contract_end_date='$contract_end_date',
                                  mem_id='$mem_id',
                                  mem_name='$mem_name',
                                  `owner_name`      ='$owner_name',
                                  `owner_cell`   ='$owner_cell', 
                                  manager_name= '$manager_name',
                                  manager_tel= '$manager_tel',
                                  `fax` ='$fax',
                                  `status`          ='$status', 
                                  address ='$address',
                                  web_theme='$web_theme',
                                  home_link='$home_link',
                                  profile_idx='$profile_idx',
                                  footer_link='$footer_link',
                                  share_price='$share_price',
                                  month_price='$month_price',
								  kakao='$kakao',
								  kakao_api_key='$kakao_api_key',
                                  pay_link='$pay_link',
                                  service_type = '$service_type',
                                  service_price = '$service_price',
                                  service_price_exp = '$service_price1',
                                  ai_card_point = '$ai_point',
                                  auto_member_point = '$automem_point',
                                  card_send_point = '$card_point',
                                  contents_send_point = '$contents_point',
                                  autodata_point = '$autodata_point',
                                  ai_point_start = '$ai_start_date',
                                  ai_point_end = '$ai_end_date',
                                  automem_point_start = '$automem_start_date',
                                  automem_point_end = '$automem_end_date',
                                  card_point_start = '$card_start_date',
                                  card_point_end = '$card_end_date',
                                  contents_point_start = '$contents_start_date',
                                  contents_point_end = '$contents_end_date',
                                  autodata_point_start = '$autodata_start_date',
                                  autodata_point_end = '$autodata_end_date',
                                  self_share_card = '$self_share_card',
                                  callback_set_point = '$callback_set_point',
                                  callback_point_start = '$callback_start_date',
                                  callback_point_end = '$callback_end_date',
                                  daily_set_point = '$daily_set_point',
                                  daily_point_start = '$daily_start_date',
                                  daily_point_end = '$daily_end_date',
                                  $addQuery
                                  `status` ='$status',
                                  dup_up_state=0
                         WHERE idx='$idx'";    
    mysqli_query($self_con,$query);
    $card_cnt_off = $old_card_cnt - $iamcard_cnt;
    $share_cnt_off = $old_send_content - $send_content;
    $my_cnt_off = $old_my_card_cnt - $my_card_cnt;
    $sql = "select site_iam from Gn_Member where mem_id='$mem_id'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    $site = $row[0];
    $sql = "select mem_id,iam_card_cnt, iam_share_cnt from Gn_Member where site_iam='$site' order by mem_code";
    $result = mysqli_query($self_con,$sql);
    while($row = mysqli_fetch_array($result)) {
        if($row['mem_id'] == $mem_id)
            $iam_card_cnt = $row[iam_card_cnt] - $my_cnt_off;
        else
            $iam_card_cnt = $row[iam_card_cnt] - $card_cnt_off;
        $iam_share_cnt = $row[iam_share_cnt] - $share_cnt_off;
        $query = "update Gn_Member set iam_card_cnt = '$iam_card_cnt',iam_share_cnt = '$iam_share_cnt' where mem_id = '{$row['mem_id']}'";
        mysqli_query($self_con,$query);
    }
} else if($_POST['mode'] == "del") {
    $query="delete from Gn_Iam_Service WHERE idx='$idx'";
    mysqli_query($self_con,$query);	
} else if($_POST['mode'] == "check_service"){
    if(count(explode(".",$sub_domain)) == 1)
        $sub_domain = "http://".$sub_domain.".kiam.kr";
    $query="select count(idx) from Gn_Iam_Service where `main_domain` like '%$sub_domain%' or `sub_domain` like '%$sub_domain%'";  
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    echo json_encode(array("result"=>$row[0]));
    exit;
}
if($from_page == "iama")
    echo "<script>alert('저장되었습니다.');location='/iama/service_Iam_admin.php';</script>";
else
    echo "<script>alert('저장되었습니다.');location='/admin/service_Iam_list.php';</script>";
exit;
?>