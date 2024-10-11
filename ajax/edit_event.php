<?
include_once "../lib/rlatjd_fun.php";
extract($_POST);
if(isset($_POST['edit_ev'])){
  $ID = $_POST['ID'];
  $event_id = $_POST['id'];

  $sql = "select * from Gn_event where event_idx={$event_id}";
  $res = mysqli_query($self_con,$sql);
  while ($row = mysqli_fetch_array($res)){
    $sql_mem_data = "select site from Gn_Member where mem_id='{$row['m_id']}'";
    $res_mem_data = mysqli_query($self_con,$sql_mem_data);
    $row_mem_data = mysqli_fetch_array($res_mem_data);
    if($row_mem_data['site'] == "kiam"){
      $domain = "http://kiam.kr";
    }
    else{
      $domain = "http://".$row_mem_data['site'].".kiam.kr";
    }
    $arr_replay['domain'] = $domain;
    $arr_replay['id'] = $event_id;
    $arr_replay['m_id'] = $row['m_id'];
    $arr_replay['event_title'] = $row['event_title'];
    $arr_replay['event_desc'] = $row['event_desc'];
    $arr_replay['card_short_url'] = $row['event_info'];
    $arr_replay['btn_title'] = $row['event_type'];
    $arr_replay['btn_link'] = $row['event_sms_desc'];
    $arr_replay['short_url'] = $row['short_url'];
    $arr_replay['event_link'] = $row['event_req_link'];
    $arr_replay['read_cnt'] = $row['read_cnt'];
    $arr_replay['regdate'] = $row['regdate'];
    $arr_replay['autojoin_img'] = $row['object'];
    // $reply_content[0] = $arr_replay;
  }
  $sql_step = "select a.idx, a.send_num, a.allow_state, a.reserv_sms_id, c.reservation_title from gn_automem_sms_reserv a inner join Gn_event_sms_info c on a.reserv_sms_id=c.sms_idx where a.auto_event_id={$event_id} order by a.idx desc limit 1";
  $res_step = mysqli_query($self_con,$sql_step);
  while ($row_step = mysqli_fetch_array($res_step)){
    $sql_step_info = "select MAX(step) as step from Gn_event_sms_step_info where sms_idx={$row_step['reserv_sms_id']}";
    $res_step_info = mysqli_query($self_con,$sql_step_info);
    $row_step_info = mysqli_fetch_array($res_step_info);
    $arr_replay['send_num'] = $row_step['send_num'];
    $arr_replay['step_allow_state'] = $row_step['allow_state'];
    $arr_replay['reserv_sms_id'] = $row_step['reserv_sms_id'];
    $arr_replay['step'] = $row_step_info['step'];
    $arr_replay['step_title'] = $row_step['reservation_title'];
    $arr_replay['step_idx'] = $row_step['idx'];
    // $reply_content[0] = $arr_replay;
  }
  echo json_encode($arr_replay);
  
}
else if(isset($_POST['save'])){
  // $id = $_POST['id'];
  // $title = $_POST['title'];
  // $desc = $_POST['desc'];
  // $card_short_url = $_POST['card_short_url'];
  // $btn_title = $_POST['btn_title'];
  // $btn_link = $_POST['btn_link'];
  // $short_url = $_POST['short_url'];
  // $read_cnt = $_POST['read_cnt'];
  // // $object = $_POST['object'];
  // $event_link = $_POST['event_link'];

  if($_FILES['autojoin_img']['name']) {
    $info = explode(".",$_FILES['autojoin_img']['name']);
    $ext = $info[count($info)-1];
    $filename = time().".".$ext;
    //메인에 옮길때 수정패치 할것 test->www
    $autojoin_img_path = "http://www.kiam.kr".gcUpload($_FILES['autojoin_img']['name'], $_FILES['autojoin_img']['tmp_name'], $_FILES['autojoin_img']['size'], "ad", $filename);
  }

  if($autojoin_img_path){
    $addQuery = " object='$autojoin_img_path', ";
    $addQuery1 = " img='$autojoin_img_path', ";
  }

  $sql_update = "update Gn_event set event_title='{$event_title}', event_desc='{$event_desc}', event_info='{$card_short_url}', event_type='{$btn_title}', event_sms_desc='{$btn_link}', short_url='{$short_url}', read_cnt={$read_cnt},".$addQuery." regdate=now(), event_req_link='{$event_req_link}' where event_idx={$event_idx}";

  // echo $sql_update; exit;
  mysqli_query($self_con,$sql_update);
  if($location_iam){
    echo "<script>alert('수정되었습니다.');location.href='/';</script>";
  }
  else{
    echo "<script>alert('수정되었습니다.');location.href='/iama/service_Iam_admin.php?mode=auto'</script>";
  }
}
else if(isset($_POST['del'])){
  $id = $_POST['id'];

  $id_arr = array();
  if(strpos($id, ",") !== false){
    $id_arr = explode(",", $id);
  }
  else{
    $id_arr[0] = $id;
  }

  for($i = 0; $i < count($id_arr); $i++){
    $sql_del = "delete from Gn_event where event_idx={$id_arr[$i]}";
    mysqli_query($self_con,$sql_del);
  
    $sql_chk = "select count(idx) as cnt from gn_automem_sms_reserv where auto_event_id={$id_arr[$i]}";
    $res_cnt = mysqli_query($self_con,$sql_chk);
    $cnt = mysqli_fetch_array($res_cnt);
    if($cnt['cnt'] != 0){
      $sql_del = "delete from gn_automem_sms_reserv where auto_event_id={$id_arr[$i]}";
      mysqli_query($self_con,$sql_del);
    }
  }
  echo 1;
}
else if(isset($_POST['search'])){
  $ID = $_POST['ID'];
  $start_date = $_POST['start'];
  $end_date = $_POST['end'];

  $str_start = ' 00:00:00';
  $str_end = ' 23:59:59';

  $echostr = '';
  $searchstr = '';
  if(($start_date != '') && ($end_date != '')){
    $searchstr .= " AND regdate >= '$start_date$str_start' and regdate <= '$end_date$str_end'";
  }

  $sql = "select * from Gn_event where event_name_kor='단체회원자동가입및아이엠카드생성' and m_id='{$ID}'".$searchstr . " order by regdate desc";
  // echo $sql; exit;
  $res = mysqli_query($self_con,$sql);
  $i = 0;
  while($row = mysqli_fetch_array($res)){
    $pop_url = '/event/automember.php?pcode='.$row['pcode'].'&eventidx='.$row['event_idx'];
    $id_sql = "select count(event_id) as cnt from Gn_Member where event_id={$row['event_idx']} and mem_type='A'";
    $res_id = mysqli_query($self_con,$id_sql);
    $row_id = mysqli_fetch_array($res_id);
    if($row_id['cnt'] != null){
        $cnt_join = $row_id['cnt'];
    }
    else{
        $cnt_join = 0;
    }
    $i++;
    $sql_service = "select auto_join_event_idx from Gn_Iam_Service where mem_id='{$row['m_id']}'";
    $res_service = mysqli_query($self_con,$sql_service);
    $row_service = mysqli_fetch_array($res_service);
    if($row["event_idx"] == $row_service['auto_join_event_idx']){
        $checked_auto = "checked";
    }else{
        $checked_auto = "";
    }
    $class_name = ($_SESSION['iam_member_id'] == "iam1")?"auto_switch_copy":"auto_switch";
    if($row['object'] != "")
    { 
        $img_part= '<img class="zoom" src="'. $row['object'].'" style="width:90%;">';
    }
    $body .= '                    </td>';
    $echostr .= '<tr>
                  <td class="iam_table">'.$i.'</td>
                  <td class="iam_table">'.$row['event_title'].'</td>
                  <td class="iam_table"><a onclick="newpop(`'.$pop_url.'`)">미리보기</a>/<a onclick="copyHtml(`'.$row['short_url'].'`)">링크복사</a></td>
                  <td class="iam_table">'.$img_part.'</td>
                  <td class="iam_table">'.$row['read_cnt'].'/'.$cnt_join.'</td>
                  <td class="iam_table">'.$row['regdate'].'</td>
                  <td class="iam_table"><a onclick="edit_ev('.$row['event_idx'].')">수정</a>/<a onclick="delete_ev('.$row['event_idx'].')">삭제</a></td>
                  <td class="iam_table">
                      <label class="'.$class_name.'">
                          <input type="checkbox" name="auto_status" id="auto_stauts_'.$row['event_idx'].'" value="'. $row['event_idx'].'" '.$checked_auto.'>
                          <span class="slider round" name="auto_status_round" id="auto_stauts_round_'.$row['event_idx'].'"></span>
                      </label>
                      <input type="hidden" name="auto_service_id" id="auto_service_id" value="'. $row['m_id'].'">
                  </td>
              </tr>';
  }
  echo $echostr;
}
else if(isset($_POST['update_state'])){
  $id = $_POST['id'];
  $status = $_POST['status'];
  $sql_update = "update gn_automem_sms_reserv set allow_state={$status} where idx={$id}";
  mysqli_query($self_con,$sql_update);
  echo 1;
}
else if(isset($_POST['update_join_state'])){
  $status = $_POST['status'];
  $mem_id = $_POST['mem_id'];
  if($status){
    $id = $_POST['id'];
  }
  else{
    $id = 0;
  }
  $sql_update = "update Gn_Iam_Service set auto_join_event_idx={$id} where mem_id='{$mem_id}'";
  mysqli_query($self_con,$sql_update);
  echo 1;
}
else if(isset($_POST['duplicate_msg'])){
  $status = $_POST['status'];
  $mem_id = $_POST['mem_id'];
  if($status){
    $id = $_POST['id'];

    $sql_update = "update Gn_Iam_Service set auto_join_event_idx={$id} where mem_id='{$mem_id}'";
    mysqli_query($self_con,$sql_update);
    
    $sql_service_ids = "select mem_id, main_domain from Gn_Iam_Service where mem_id!='iam1' order by idx asc";
    $res_service_ids = mysqli_query($self_con,$sql_service_ids);
    while($row_servicee_ids = mysqli_fetch_array($res_service_ids)){
      $sql_mem_data = "select mem_phone from Gn_Member where mem_id='{$row_servicee_ids['mem_id']}'";
      $res_mem_data = mysqli_query($self_con,$sql_mem_data);
      $row_mem_data = mysqli_fetch_array($res_mem_data);
      if($row_mem_data['mem_phone'] != ""){
        $rand_num = rand(100, 999);
        $cur_time1 = date("YmdHis");
        $event_name_eng = "회원IAM 신청".$cur_time1.$rand_num;
        $pcode = "aimem".$cur_time1.$rand_num;
  
        $sql_dup_event = "INSERT INTO Gn_event(event_name_kor, event_name_eng, event_title, event_desc, event_info, event_sms_desc, pcode, event_type, mobile, regdate, ip_addr, m_id, short_url, read_cnt, cnt, object, callback_no, event_req_link, daily_req_link) 
        (SELECT event_name_kor, '{$event_name_eng}', event_title, event_desc, '', event_sms_desc, '{$pcode}', event_type, '{$row_mem_data['mem_phone']}', now(), ip_addr, '{$row_servicee_ids['mem_id']}', '', 0, cnt, object, callback_no, event_req_link, daily_req_link FROM Gn_event WHERE event_idx='{$id}')";
        mysqli_query($self_con,$sql_dup_event) or die(mysqli_error($self_con));
        $event_idx = mysqli_insert_id($self_con);
  
        $transUrl = $row_servicee_ids['main_domain']."/event/automember.php?pcode=".$pcode."&eventidx=".$event_idx;
        $transUrl = get_short_url($transUrl);
        $insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
        mysqli_query($self_con,$insert_short_url) or die(mysqli_error($self_con));
      }
    }
  }
  echo 1;
}
else if(isset($_POST['edit_step'])){
  if($type == "updat"){
    $sql_update = "update gn_automem_sms_reserv set reserv_sms_id={$sms_idx}, send_num='{$mobile}', regdate=now() where auto_event_id={$event_idx}";
    mysqli_query($self_con,$sql_update);
  }
  else{
    $sql_insert = "insert into gn_automem_sms_reserv set auto_event_id={$event_idx}, reserv_sms_id={$sms_idx}, send_num='{$mobile}', regdate=now()";
    mysqli_query($self_con,$sql_insert);
  }

  echo 1;
}
?>