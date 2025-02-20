<?
include_once "../lib/rlatjd_fun.php";
function add_event_request($event_code, $event_idx, $pcode)
{
    global $self_con;
    $m_id = $_SESSION['iam_member_id'];
    $mem_sql = "select mem_name,mem_phone,mem_email,mem_sex,mem_add1,mem_birth,zy from Gn_Member where mem_id='$m_id'";
    $mem_res = mysqli_query($self_con, $mem_sql);
    $mem_row = mysqli_fetch_array($mem_res);
    $name = $mem_row['mem_name'];
    $recv_num = $mobile = str_replace("-", "", $mem_row['mem_phone']);
    $email = $mem_row['mem_email'];
    $sex = $mem_row['mem_sex'];
    $addr = $mem_row['mem_add1'];
    $birthday = $mem_row['mem_birth'];
    $job = $mem_row['zy'];
    $sql = "insert into Gn_event_request set event_idx='{$event_idx}',
                                            event_code='{$event_code}',
                                            m_id='{$m_id}',
                                            name='{$name}',
                                            mobile='{$mobile}',
                                            email='{$email}',
                                            sex='{$sex}',
                                            addr='{$addr}',
                                            birthday='{$birthday}',
                                            job='{$job}',
                                            pcode='{$pcode}',
                                            sp='{$pcode}',
                                            regdate=now(),
                                            step_end_time=now(),
                                            target='3'";
    mysqli_query($self_con, $sql);
    $request_idx = mysqli_insert_id($self_con);

    $event_sql = "select sms_idx1, sms_idx2, sms_idx3, mobile from Gn_event where event_idx='{$event_idx}'";
    $event_res = mysqli_query($self_con, $event_sql);
    $event_row = mysqli_fetch_array($event_res);
    $send_num = $event_row['mobile'];

    $sms_sql = "select * from Gn_event_sms_info where sms_idx='{$event_row['sms_idx1']}' or sms_idx='{$event_row['sms_idx2']}' or sms_idx='{$event_row['sms_idx3']}'";
    $sms_res = mysqli_query($self_con, $sms_sql) or die(mysqli_error($self_con));
    while ($sms_row = mysqli_fetch_array($sms_res)) {
        $mem_id = $sms_row['m_id'];
        $sms_idx = $sms_row['sms_idx'];
        //알람등록
        $sql = "select * from Gn_event_sms_step_info where sms_idx='{$sms_idx}'";
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $step_end_time = date("Y-m-d H:i:s");
        while ($row = mysqli_fetch_array($result)) {
            // 시간 확인
            $send_day = $row['send_day'];
            $send_time = $row['send_time'];

            if ($send_time == "") $send_time = "09:30";
            if ($send_time == "00:00") $send_time = "09:30";
            if ($send_day == "0") {
                $reservation = "";
                $jpg = $jpg1 = $jpg2 = '';
                if ($row['image'])
                    $jpg = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image'];
                if ($row['image1'])
                    $jpg1 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image1'];
                if ($row['image2'])
                    $jpg2 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image2'];

                sendmms(3, $mem_id, $send_num, $recv_num, $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, 'Y', $row['sms_idx'], $row['sms_detail_idx'], $request_idx, "", $row['send_deny']);

                $query = "insert into Gn_MMS_Agree set mem_id='$mem_id',
                                                    send_num='$send_num',
                                                    recv_num='$recv_num',
                                                    content='" . addslashes($row['content']) . "',
                                                    title='" . addslashes($row['title']) . "',
                                                    jpg='$jpg',
                                                    jpg1='',
                                                    jpg2='',
                                                    up_date=NOW(),
                                                    reg_date=NOW(),
                                                    reservation='$reservation',
                                                    sms_idx='{$row['sms_idx']}',
                                                    sms_detail_idx='{$row['sms_detail_idx']}',
                                                    request_idx='$request_idx'";
                mysqli_query($self_con, $query) or die(mysqli_error($self_con));
            } else {
                $reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));
                if ($step_end_time < $reservation)
                    $step_end_time = $reservation;
            }
        }
        $sql = "update Gn_event_request set step_end_time='{$step_end_time}' where request_idx = {$request_idx}";
        mysqli_query($self_con, $sql);
    }
    return $request_idx;
}
function han($s)
{
    return reset(json_decode('{"s":"' . $s . '"}'));
}
function to_han($str)
{
    return preg_replace('/(\\\u[a-f0-9]+)+/e', 'han("$0")', $str);
}
if ($_POST['method'] == "create_format") {
    $obj = $_POST['data'];
    $obj = str_replace('\n', '---n---', $obj);
    $obj = json_decode($obj);
    if (!$obj) {
        $obj = $_POST['data'];
        $obj = str_replace('\n', '---n---', $obj);
        $obj = json_decode(stripcslashes($obj));
    }
    $title = addslashes($obj->title);
    $desc = addslashes($desc);
    $desc = str_replace('---n---', '\n', $obj->desc);
    $sign = $obj->sign;
    $req_yn = $obj->req_yn;
    $event_code = $obj->event_code;
    $pcode = $obj->pcode;
    $event_idx = $obj->event_idx;
    $sql = "insert into gn_report_form set title='$title',descript='$desc',sign_visible=$sign,user_id='{$_SESSION['iam_member_id']}',reg_date=now(),request_yn = '$req_yn'";
    if($event_idx)
        $sql .= "pcode='$event_idx'";
    mysqli_query($self_con, $sql);
    $repo_id = mysqli_insert_id($self_con);
    $link = "https://" . $HTTP_HOST . "/iam/report.php?repo=$repo_id";
    $link = get_short_url($link);
    $sql = "update gn_report_form set short_url='$link' where id=$repo_id";
    mysqli_query($self_con, $sql);
    $items = $obj->item;
    foreach ($items as $item) {
        $repo_type = $item->type;
        $repo_title = addslashes($item->title);
        $repo_req = $item->req;
        $repo_req = addslashes($repo_req);
        $repo_keys = $item->key;
        $repo_order = $item->order;
        $sql = "insert into gn_report_form1 set form_id=$repo_id,item_type=$repo_type,item_order=$repo_order,item_title='$repo_title',item_req='$repo_req'";
        mysqli_query($self_con, $sql);
        $item_id = mysqli_insert_id($self_con);
        $i = 0;
        foreach ($repo_keys as $repo_key) {
            $tag_id = $repo_id . "a" . $item_id . "b" . $i;
            if ($repo_type != 2) {
                $repo_key = addslashes($repo_key);
                $sql = "insert into gn_report_form2 set form_id=$repo_id,item_id=$item_id,tag_name='$repo_key',tag_id='$tag_id'";
            } else {
                $cont = str_replace('---n---', '\n', $repo_key->desc);
                $cont = str_replace("'", "\'", $cont);
                $link = $repo_key->link;
                $img = addslashes($repo_key->img);
                $sql = "insert into gn_report_form2 set form_id=$repo_id,item_id=$item_id,tag_name='$cont',tag_id='$tag_id',tag_link='$link',tag_img='$img'";
            }
            mysqli_query($self_con, $sql);
            $i++;
        }
    }
    echo json_encode(array("id" => $repo_id));
} else if ($_POST['method'] == "edit_format") {
    $index = $_POST['index'];
    $sql = "select short_url,request_yn,pcode from gn_report_form where id = $index";
    $res = mysqli_query($self_con, $sql);
    $row = mysqli_fetch_array($res);
    $event_idx = $row['pcode'];
    if ($row['short_url'] == "") {
        $link = "https://" . $HTTP_HOST . "/iam/report.php?repo=$index";
        $link = get_short_url($link);
        $sql = "update gn_report_form set short_url='$link' where id=$index";
        mysqli_query($self_con, $sql);
    }
    $sql = "select count(idx) from gn_report_table where repo_id = $index";
    $res = mysqli_query($self_con, $sql);
    $cnt_row = mysqli_fetch_array($res);
    $count = $cnt_row[0];
    if ($count == 0) {
        $obj = $_POST['data'];
        $obj = str_replace('\n', '---n---', $obj);
        $obj = json_decode($obj);
        if (!$obj) {
            $obj = $_POST['data'];
            $obj = str_replace('\n', '---n---', $obj);
            $obj = json_decode(stripcslashes($obj));
        }
        $title = addslashes($obj->title);
        $desc = addslashes($desc);
        $desc = str_replace('---n---', '\n', $obj->desc);
        $sign = $obj->sign;
        $req_yn = $obj->req_yn;
        $event_code = $obj->event_code;
        $pcode = $obj->pcode;
        $event_idx = $obj->event_idx;
        $sql = "update gn_report_form set title='$title',descript='$desc',sign_visible=$sign,reg_date=now(),request_yn = '$req_yn',pcode='$event_idx' where id = $index";
        mysqli_query($self_con, $sql);
        $sql = "delete from gn_report_form1 where form_id = $index";
        mysqli_query($self_con, $sql);
        $sql = "delete from gn_report_form2 where form_id = $index";
        mysqli_query($self_con, $sql);
        $items = $obj->item;
        foreach ($items as $item) {
            $repo_type = $item->type;
            $repo_title = addslashes($item->title);
            //$repo_title = str_replace("'", "\'", $repo_title);
            //if ($repo_type == 1 || $repo_type == 2)
            $repo_req = $item->req;
            //else
            //    $repo_req = "";
            //$repo_title = str_replace("'", "\'", $repo_title);
            //$repo_req = str_replace("'", "\'", $repo_req);
            $repo_req = addslashes($repo_req);
            $repo_keys = $item->key;
            $repo_order = $item->order;
            $sql = "insert into gn_report_form1 set form_id=$index,item_type=$repo_type,item_order=$repo_order,item_title='$repo_title',item_req='$repo_req'";
            mysqli_query($self_con, $sql);
            $item_id = mysqli_insert_id($self_con);
            $i = 0;
            foreach ($repo_keys as $repo_key) {
                $tag_id = $index . "a" . $item_id . "b" . $i;
                if ($repo_type != 2) {
                    $repo_key = addslashes($repo_key);
                    $sql = "insert into gn_report_form2 set form_id=$index,item_id=$item_id,tag_name='$repo_key',tag_id='$tag_id'";
                } else {
                    $cont = str_replace('---n---', '\n', $repo_key->desc);
                    $cont = str_replace("'", "\'", $cont);
                    $link = addslashes($repo_key->link);
                    $img = addslashes($repo_key->img);
                    $sql = "insert into gn_report_form2 set form_id=$index,item_id=$item_id,tag_name='$cont',tag_id='$tag_id',tag_link='$link',tag_img='$img'";
                }
                mysqli_query($self_con, $sql);
                $i++;
            }
        }
    }
    echo json_encode(array("id" => $index));
} else if ($_POST['method'] == "del") {
    $index = $_POST['index'];
    $index_array = explode(",", $index);
    foreach ($index_array as $index) {
        $sql = "select pcode from gn_report_form where id=$index and request_yn='Y'";
        $res = mysqli_query($self_con, $sql);
        $row = mysqli_fetch_array($res);
        $sql = "delete from gn_report_form where id=$index";
        mysqli_query($self_con, $sql);
        $sql = "delete from gn_report_form1 where form_id=$index";
        mysqli_query($self_con, $sql);
        $sql = "delete from gn_report_form2 where form_id=$index";
        mysqli_query($self_con, $sql);
        //$sql = "drop table gn_report_table{$index}";
        $sql = "delete from gn_report_table where repo_id =$index";
        mysqli_query($self_con, $sql);
    }
    echo json_encode(array("result" => "success"));
} else if ($_POST['method'] == "delete_report") {
    $index = $_POST['index'];
    $ids = $_POST['idx'];
    $idx_array = explode(",", $ids);
    foreach ($idx_array as $idx) {
        $sql = "delete from gn_report_table where idx=$idx";
        mysqli_query($self_con, $sql);
    }
    echo json_encode(array("result" => "success"));
} else if ($_POST['method'] == "change_status") {
    $index = $_POST['index'];
    $status = $_POST['status'];
    $sql = "update gn_report_form set status = {$status} where id=$index";
    mysqli_query($self_con, $sql);
    echo json_encode(array("result" => "success"));
} else if ($_POST['method'] == "clone") {
    $index = $_POST['index'];
    $index_array = explode(",", $index);
    foreach ($index_array as $index) {
        $sql_form = "SELECT * FROM gn_report_form WHERE id=$index";
        $res_form = mysqli_query($self_con, $sql_form);
        $row_form = mysqli_fetch_array($res_form);
        $title = $row_form['title'];
        $title = addslashes($title);
        $desc = $row_form['descript'];
        $desc = addslashes($desc);
        $sign_visible = $row_form['sign_visible'];
        $req_yn = $row_form['request_yn'];
        $event_idx = $row_form['pcode'];
        $detail = $row_form['detail'];
        $sql_form = "INSERT INTO gn_report_form set title='{$title}', 
                                                    descript='{$desc}', 
                                                    status = 1, 
                                                    user_id ='{$_SESSION['iam_member_id']}',
                                                    sign_visible = $sign_visible, 
                                                    request_yn = '{$req_yn}',
                                                    pcode='{$event_idx}',
                                                    detail='{$detail}',
                                                    reg_date = now()";
        mysqli_query($self_con, $sql_form) or die(mysqli_error($self_con));
        $form_id = mysqli_insert_id($self_con);
        $link = "https://" . $HTTP_HOST . "/iam/report.php?repo=$form_id";
        $link = get_short_url($link);
        $sql = "update gn_report_form set short_url='$link' where id=$form_id";
        mysqli_query($self_con, $sql);
        $sql_form1 = "select * from gn_report_form1 where form_id=$index order by item_order";
        $res_form1 = mysqli_query($self_con, $sql_form1);
        while ($row_form1 = mysqli_fetch_array($res_form1)) {
            $item_type = $row_form1['item_type'];
            $item_order = $row_form1['item_order'];
            $item_title = $row_form1['item_title'];
            $item_title = addslashes($item_title);
            $item_req = $row_form1['item_req'];
            $item_req = addslashes($item_req);
            $sql = "INSERT INTO gn_report_form1 set form_id=$form_id,
                                                    item_type=$item_type,
                                                    item_order=$item_order,
                                                    item_title='$item_title',
                                                    item_req= '$item_req'";
            mysqli_query($self_con, $sql);
            $item_id = mysqli_insert_id($self_con);
            $sql_form2 = "select * from gn_report_form2 where form_id=$index and item_id={$row_form1['id']} order by id";
            $res_form2 = mysqli_query($self_con, $sql_form2);
            $i = 0;
            while ($row_form2 = mysqli_fetch_array($res_form2)) {
                $tag_id = $form_id . "a" . $item_id . "b" . $i;
                $tag_name = $row_form2['tag_name'];
                $tag_name = addslashes($tag_name);
                $tag_link = $row_form2['tag_link'];
                $tag_link = addslashes($tag_link);
                $tag_img = $row_form2['tag_img'];
                $tag_img = addslashes($tag_img);
                $sql = "INSERT INTO gn_report_form2 set form_id=$form_id,item_id=$item_id,tag_name='{$tag_name}',tag_id='$tag_id',tag_link='$tag_link',tag_img='$tag_img'";
                mysqli_query($self_con, $sql);
                $i++;
            }
        }
    }
    echo json_encode(array("result" => "success"));
} else if ($_POST['method'] == "reg_report") {
    $sign = $_POST['sign'];
    $obj = $_POST['data'];
    $obj = str_replace('\n', '---n---', $obj);
    $obj = json_decode(stripslashes($obj));
    $index = $obj->index;
    $memid = $obj->id;
    $name = $obj->name;
    $phone = $obj->phone;
    $items = $obj->item;
    $sql = "insert into gn_report_table set repo_id=$index,";
    $sql .= "userid='" . $memid . "',";
    $sql .= "name='" . $name . "',";
    $sql .= "phone='" . $phone . "',";
    $sql .= "sign='" . $sign . "',";
    $sql .= "reg_date=now(),";
    $cont = array();
    foreach ($items as $item) {
        $key = $item->key;
        $val = str_replace('---n---', '\n', $item->value);
        $arr = array($key => $val);
        array_push($cont, $arr);
    }
    $sql .= "cont='" . to_han(json_encode($cont)) . "'";
    mysqli_query($self_con, $sql);

    $sql = "select request_yn,pcode from gn_report_form where id=$index";
    $res = mysqli_query($self_con, $sql);
    $row = mysqli_fetch_array($res);
    if ($row['request_yn']) {
        $erq_sql = "select * from Gn_event where event_idx = {$row['pcode']}";
        $erq_res = mysqli_query($self_con, $erq_sql);
        $erq_row = mysqli_fetch_array($erq_res);
        add_event_request($erq_row['event_name_eng'], $erq_row['event_idx'], $erq_row['pcode']);
    }

    echo json_encode(array("result" => "success"));
} else if ($_POST['method'] == "change_report") {
    $sign = $_POST['sign'];
    $obj = $_POST['data'];
    $obj = str_replace('\n', '---n---', $obj);
    $obj = json_decode(stripslashes($obj));
    $memid = $obj->id;
    if ($_SESSION['iam_member_id'] == $memid) {
        $index = $obj->form_index;
        $idx = $obj->index;
        $name = $obj->name;
        $phone = $obj->phone;
        $items = $obj->item;
        $sql = "update gn_report_table set ";
        $cont = array();
        foreach ($items as $item) {
            $key = $item->key;
            $val = str_replace('---n---', '\n', $item->value);
            $arr = array($key => $val);
            array_push($cont, $arr);
        }
        $sql .= "cont='" . json_encode($cont) . "',";
        $sql .= "name='" . $name . "',";
        $sql .= "phone='" . $phone . "',";
        $sql .= "sign='" . $sign . "'";
        $sql .= " where idx=$idx and userid='$memid'";
        mysqli_query($self_con, $sql);
        echo json_encode(array("result" => "success"));
    } else {
        echo json_encode(array("result" => "failed"));
    }
} else if ($_POST['method'] == "auto_login") {
    //회원가입
    $userid = $_POST['id'];
    $name = $_POST['name'];
    $mobile = $_POST['phone'];
    $passwd = substr($mobile, -4);
    $sql = "select mem_code form Gn_Member where mem_id='$userid'";
    $res = mysqli_query($self_con, $sql);
    $row = mysqli_fetch_array($res);
    if (!$row) {
        $repo_id = $_POST['repo'];
        $sql = "select user_id from gn_report_form where id=$repo_id";
        $res = mysqli_query($self_con, $sql);
        $row = mysqli_fetch_array($res);
        $sql = "select site,site_iam,mem_id,mem_phone from Gn_Member where mem_id='{$row['user_id']}'";
        $res = mysqli_query($self_con, $sql);
        $row = mysqli_fetch_array($res);
        //password()
        $query = "insert into Gn_Member set mem_id='$userid',
                        mem_leb='22',
                        web_pwd=md5('$passwd'),
                        mem_pass=md5('$passwd'),
                        mem_name='$name',
                        mem_nick='$name',
                        mem_type='A',
                        recommend_id = '{$row['mem_id']}',
                        site='{$row['site']}',
                        site_iam='{$row['site_iam']}',
                        mem_phone='$mobile',
                        zy='',
                        first_regist=now() ,
                        mem_check=now(),
                        mem_add1='',
                        mem_email='',
                        mem_sex='',
                        join_ip='{$_SERVER['REMOTE_ADDR']}'
                        ";
        mysqli_query($self_con, $query);
        $mem_code = mysqli_insert_id($self_con);

        $sql = "select count(idx) from Gn_Iam_Info where mem_id = '$userid'";
        $sql_count = mysqli_query($self_con, $sql);
        $comment_row = mysqli_fetch_array($sql_count);

        if ((int)$comment_row[0] == 0 && $HTTP_HOST == "kiam.kr") {
            $sql2 = "insert into Gn_Iam_Info (mem_id, reg_data) values ('$userid', now())";
            $result2 = mysqli_query($self_con, $sql2) or die(mysqli_error($self_con));
        }

        $check_sql = "select count(idx) from Gn_Iam_Name_Card where group_id = 0 and mem_id = '$userid'";
        $check_result = mysqli_query($self_con, $check_sql);
        $check_comment_row = mysqli_fetch_array($check_result);
        if (!$check_comment_row[0]) { //네임카드가 하나도 없으면 자동으로 한개 생성한다
            $site_name = $row['site_iam'];
            $card_name = $name;
            $card_title = $name . " 소개";
            $card_phone = $mobile;
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
            $short_url = generateRandomString();
            $img_url = "/iam/img/common/logo-2.png";
            $name_card_sql = "insert into Gn_Iam_Name_Card (mem_id, card_short_url, card_title,card_name, card_phone, profile_logo, req_data,up_data) 
                                values ('$userid', '$short_url', '$card_title','$card_name', '$card_phone', '$img_url', now(), now())";
            mysqli_query($self_con, $name_card_sql) or die(mysqli_error($self_con));
        }
        $repo_msg = "성공적으로 제출되었습니다. 확인아이디는 " . $userid . ", 비밀번호는 폰 뒤 4자리이거나, 설정하셨던 비번입니다.  https://" . $row['site_iam'] . ".kiam.kr/";
        $msg = sendmms(5, $row['mem_id'], str_replace("-", "", $row['mem_phone']), str_replace("-", "", $mobile), "", "리포트 제출하기", $repo_msg, "", "", "", "Y");
    } else {
        $mem_code = $row['mem_code'];
    }
    echo json_encode(array("result" => "success", "id" => $userid, "pass" => $passwd, "code" => $mem_code, "mms_result" => $msg));
} else if ($_POST['method'] == "send_report") {
    $send_ids = $_POST['send_ids'];
    $repo_ids = $_POST['repo_idx'];
    $mem_arr = explode(",", $send_ids);
    $repo_arr = explode(",", $repo_ids);
    foreach ($mem_arr as $mem_id) {
        if ($mem_id != "") {
            $sql = "select site_iam,service_type from Gn_Member where mem_id='$mem_id'";
            $res = mysqli_query($self_con, $sql);
            $row = mysqli_fetch_array($res);
            if ($row['service_type'] >= 2) {
                foreach ($repo_arr as $repo) {
                    $sql = "select title,descript,sign_visible,pcode,request_yn,detail from gn_report_form where id={$repo}";
                    $repo_res = mysqli_query($self_con, $sql);
                    $repo_row = mysqli_fetch_assoc($repo_res);
                    $event_idx = 0;
                    if ($repo_row['request_yn']) {
                        /*$erq_sql = "select * from Gn_event where event_idx = {$repo_row['pcode']}";
                        $erq_res = mysqli_query($self_con,$erq_sql);
                        $erq_row = mysqli_fetch_assoc($erq_res);*/
                        $event_idx = insert_db_reqlink($repo_row['pcode'], $mem_id);
                    }
                    $sql = "insert into gn_report_form set title = '{$repo_row['title']}',
                                                        descript = '{$repo_row['descript']}',
                                                        status = 1,
                                                        user_id = '{$mem_id}',
                                                        reg_date = now(),
                                                        sign_visible = '{$repo_row['sign_visible']}',
                                                        detail = '{$repo_row['detail']}',
                                                        pcode= '{$event_idx}',
                                                        request_yn = '{$repo_row['request_yn']}'";
                    mysqli_query($self_con, $sql);
                    $form_id = mysqli_insert_id($self_con);

                    $link = "https://" . $row['site_iam'] . ".kiam.kr/iam/report.php?repo=$form_id";
                    $link = get_short_url($link);
                    $sql = "update gn_report_form set short_url='$link' where id=$form_id";
                    mysqli_query($self_con, $sql);

                    $sql = "select id,item_type from gn_report_form1 where form_id=$repo order by item_order";
                    $res = mysqli_query($self_con, $sql);
                    while ($row = mysqli_fetch_array($res)) {
                        $sql = "insert into gn_report_form1 (form_id,item_type,item_order,item_title,item_req) (select $form_id,item_type,item_order,item_title,item_req from gn_report_form1 where id={$row['id']})";
                        mysqli_query($self_con, $sql);
                        $item_id = mysqli_insert_id($self_con);
                        $sql1 = "select id,tag_name from gn_report_form2 where form_id=$repo and item_id = {$row['id']} order by id";
                        $res1 = mysqli_query($self_con, $sql1);
                        $index = 0;
                        while ($row1 = mysqli_fetch_array($res1)) {
                            $tag_id = $form_id . "a" . $item_id . "b" . $index;
                            $sql = "insert into gn_report_form2 (form_id,item_id,tag_name,tag_id,tag_link,tag_img) (select $form_id,$item_id,tag_name,'$tag_id',tag_link,tag_img from gn_report_form2 where id={$row1['id']})";
                            mysqli_query($self_con, $sql);
                            $index++;
                        }
                    }
                }
            }
        }
    }
    echo json_encode(array("result" => "success"));
} else if ($_POST['method'] == "uploadImage") {
    $up_dir = make_folder_month(1);
    if ($up_dir != '') {
        $uploaddir = '..' . $up_dir;
    } else {
        $uploaddir = '../upload/';
        $up_dir = "/upload/";
    }
    $file_arr = explode(".", $_FILES['uploadFile']['name']);
    $date_file_name = "repo_".date('YmdHis') .".". $file_arr[count($file_arr) - 1];
    $uploadfile = $uploaddir . basename($date_file_name);
    if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadfile)) {
        $img_url = $up_dir . basename($date_file_name);
        $handle = new Image($uploadfile, 800);
        $handle->resize();
        uploadFTP($uploadfile);
        # temp파일에 있는 걸, 내가 지정한 uploadfile에 이동시켜라
        //echo "https://www.kiam.kr".$img_url;
        echo $img_url;
    }
} else if ($_POST['method'] == "save_report_detail") {
    $index = $_POST['id'];
    $detail = $_POST['cont'];
    $sql = "update gn_report_form set detail='$detail' where id = $index";
    mysqli_query($self_con, $sql);
    echo json_encode(array("result" => "success"));
} else if ($_POST['method'] == "get_matching_ids") {
    $mem_name = $_POST['name'];
    $mem_phone = str_replace("-", "", trim($_POST['phone']));
    $sql = "select mem_id,mem_name,mem_phone from Gn_Member use index(mem_id) where mem_name = '{$mem_name}' and REPLACE(mem_phone,'-','')='{$mem_phone}' order by first_regist desc";
    $res = mysqli_query($self_con, $sql);
    $data = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    echo json_encode($data);
} else if ($_POST['method'] == "get_statistic") {
    $repo_id = $_POST['index'];
    $report_sql = "select * from gn_report_form where id = {$repo_id}";
    $report_res = mysqli_query($self_con, $report_sql);
    $report_row = mysqli_fetch_assoc($report_res);

    $sql = "select count(idx) from gn_report_table where repo_id={$repo_id}";
    $res = mysqli_query($self_con, $sql);
    $row = mysqli_fetch_array($res);
    $count = $row[0];
    if ($count == null)
        $count = 0;
    echo json_encode(
        array(
            "result" => 'success',
            "channel" => $report_row['channel'],
            "send_count" => $report_row['send_count'],
            "display_count" => $report_row['display_count'],
            "ads_price" => $report_row['ads_price'],
            "total_payment" => $report_row['total_payment'],
            "manage_price" => $report_row['manage_price'],
            "click_count" => $report_row['visit'],
            "reply_count" => $count,
            "click_count_manual" => $report_row['click_count_manual'],
            "price_per_reply" => $report_row['price_per_reply'],
            "sale_discount" => $report_row['sale_discount'],
            "reply_count_manual" => $report_row['reply_count_manual']
        )
    );
} else if ($_POST['method'] == "save_statistic") {
    $repo_id = $_POST['index'];
    $channel = $_POST['channel'];
    $send_count = $_POST['send_count'] == "" ? 0 : $_POST['send_count'];
    $display_count = $_POST['display_count'];
    $ads_price = $_POST['ads_price'];
    //$total_payment = $_POST['total_payment'];
    $manage_price = $_POST['manage_price'];
    $price_per_reply = $_POST['price_per_reply'];
    $sale_discount = $_POST['sale_discount'];
    $click_count_manual = $_POST['click_count_manual'];
    $reply_count_manual = $_POST['reply_count_manual'];
    $report_sql = "update gn_report_form set send_count = {$send_count},
                                            channel = '{$channel}',
                                            display_count = {$display_count},
                                            ads_price = {$ads_price},
                                            price_per_reply = {$price_per_reply},
                                            sale_discount = {$sale_discount},
                                            click_count_manual = {$click_count_manual},
                                            reply_count_manual = {$reply_count_manual},
                                            manage_price = {$manage_price} where id = {$repo_id}";
    mysqli_query($self_con, $report_sql);
    echo json_encode(array("result" => 'success', "msg" => $report_sql));
}

function insert_db($idx, $mem_id)
{
    global $self_con;
    $date = time();
    $num = rand(10, 90);
    $event_name_eng = $date . $num;
    $sql_mem_chk = "select count(mem_code) from Gn_Member where mem_id='{$mem_id}'";
    $res_mem_chk = mysqli_query($self_con, $sql_mem_chk);
    $row_mem_chk = mysqli_fetch_array($res_mem_chk);
    if ($row_mem_chk[0]) {
        $sql = "INSERT INTO Gn_event_sms_info (event_idx, event_name_eng, mobile, reservation_title, reservation_desc, m_id, regdate)
                      (SELECT event_idx, '{$event_name_eng}', mobile, reservation_title, reservation_desc, '{$mem_id}', now() FROM Gn_event_sms_info where sms_idx={$idx})";
        mysqli_query($self_con, $sql);
        $sms_idx = mysqli_insert_id($self_con);
        $sql_step_info = "INSERT INTO Gn_event_sms_step_info (sms_idx, step, send_day, send_time, title, content, image, image1, image2, regdate, send_deny) 
                                (SELECT {$sms_idx}, step, send_day, send_time, title, content, image, image1, image2, now(), send_deny FROM Gn_event_sms_step_info WHERE sms_idx={$idx})";
        mysqli_query($self_con, $sql_step_info);
        return $sms_idx;
    } else {
        return 0;
    }
}
function insert_db_reqlink($idx, $mem_id)
{
    global $self_con;
    $date = time();
    $num = rand(10, 90);
    $pcode = $event_name_eng = $date . $num;

    $sql_mem_chk = "select * from Gn_Member where mem_id='{$mem_id}'";
    $res_mem_chk = mysqli_query($self_con, $sql_mem_chk);
    $row_mem_chk = mysqli_num_rows($res_mem_chk);
    $event_idx = 0;
    if ($row_mem_chk) {
        $mem_data = mysqli_fetch_array($res_mem_chk);
        $phone_num = $mem_data['mem_phone'];

        $sql = "SELECT event_name_kor, event_title, event_desc, event_info, event_sms_desc, event_type, ip_addr, cnt, object, callback_no, event_req_link, daily_req_link,sms_idx1,sms_idx2,sms_idx3 FROM Gn_event where event_idx={$idx}";
        $event_res = mysqli_query($self_con, $sql);
        $event_row = mysqli_fetch_assoc($event_res);
        $sms_idx1 = $sms_idx2 = $sms_idx3 = 0;
        if ($event_row['sms_idx1'] != 0)
            $sms_idx1 = insert_db($event_row['sms_idx1'], $mem_id);
        if ($event_row['sms_idx2'] != 0)
            $sms_idx1 = insert_db($event_row['sms_idx2'], $mem_id);
        if ($event_row['sms_idx2'] != 0)
            $sms_idx1 = insert_db($event_row['sms_idx2'], $mem_id);

        $sql = "INSERT INTO Gn_event set event_name_kor = '{$event_row['event_name_kor']}', 
                                event_name_eng = '{$event_name_eng}', 
                                event_title = '{$event_row['event_title']}', 
                                event_desc = '{$event_row['event_desc']}', 
                                event_info = '{$event_row['event_info']}', 
                                event_sms_desc = '{$event_row['event_sms_desc']}', 
                                pcode = '{$pcode}', 
                                event_type = '{$event_row['event_type']}', 
                                mobile = '{$phone_num}', 
                                regdate = now(), 
                                ip_addr = '{$event_row['ip_addr']}', 
                                m_id = '{$mem_id}', 
                                short_url = '', 
                                read_cnt = 0, 
                                cnt = '{$event_row['cnt']}', 
                                object = '{$event_row['object']}', 
                                callback_no = '{$event_row['callback_no']}', 
                                event_req_link = '{$event_row['event_req_link']}', 
                                daily_req_link = '{$event_row['daily_req_link']}',
                                sms_idx1 = '{$sms_idx1}',
                                sms_idx2 = '{$sms_idx2}',
                                sms_idx3 = '{$sms_idx3}'";
        mysqli_query($self_con, $sql);

        $event_idx = mysqli_insert_id($self_con);
        $host = $mem_data['site_iam'] == "kiam" ? "http://kiam.kr" : "http://" . $mem_data['site_iam'] . ".kiam.kr";
        $transUrl = $host . "/event/event.html?pcode=" . $pcode . "&sp=" . $event_name_eng;
        $transUrl = get_short_url($transUrl);
        $insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
        mysqli_query($self_con, $insert_short_url) or die(mysqli_error($self_con));
    }
    return $event_idx;
}
