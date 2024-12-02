<?
include_once "../lib/rlatjd_fun.php";

if(isset($_POST['edit_ev'])){
  $ID = $_POST['ID'];
  $event_id = $_POST['id'];

  $sql = "select * from Gn_event where event_idx={$event_id}";
  $res = mysqli_query($self_con,$sql);
  while ($row = mysqli_fetch_array($res)){
    $arr_replay['id'] = $event_id;
    $arr_replay['m_id'] = $row['m_id'];
    $arr_replay['send_cnt'] = $row['callback_no'];
    $arr_replay['htime'] = $row['event_sms_desc'];
    $arr_replay['img'] = $row['object'];
    $arr_replay['mtime'] = $row['event_type'];
    $arr_replay['short_url'] = $row['short_url'];
    $arr_replay['read_cnt'] = $row['read_cnt'];
    $arr_replay['regdate'] = $row['regdate'];
    $arr_replay['event_desc_daily_intro'] = $row['event_desc'];
    $arr_replay['event_title_daily_intro'] = $row['event_title'];
    $arr_replay['msg_title_daily'] = $row['event_info'];
    $arr_replay['msg_desc_daily'] = $row['event_req_link'];
    $arr_replay['daily_req_link'] = $row['daily_req_link'];
  }
  echo json_encode($arr_replay);
  
}
else if(isset($_POST['save'])){
  $id = $_POST['daily_event_idx'];
  $title = $_POST['daily_event_title_intro'];
  $desc = $_POST['daily_event_desc_intro'];
  $msg_title = $_POST['daily_event_title'];
  $msg_desc = $_POST['daily_event_desc'];
  $short_url = $_POST['daily_short_url'];
  $read_cnt = $_POST['daily_read_cnt'];
  $regdate = $_POST['daily_regdate1'];
  $send_cnt = $_POST['daily_send_cnt'];
  $daily_req_link = $_POST['daily_req_link'];
  $htime = $_POST['htime'];
  $mtime = $_POST['mtime'];

  if($_FILES['daily_img']['name']) {
    $img_name=date("dmYHis").str_replace(" ","",basename($_FILES["daily_img"]["name"]));	
    $img_name=str_replace("'","",$img_name);	
    $img_name=str_replace('"',"",$img_name);	
    $img_name=basename($img_name);	
    if(move_uploaded_file($_FILES["daily_img"]['tmp_name'], "../adjunct/mms/thum/".$img_name)){
      $size = getimagesize("../adjunct/mms/thum/".$img_name);
      if($size[0] > 640) {
          $ysize = $size[1] * (640 / $size[0]);
          thumbnail("../adjunct/mms/thum/".$img_name,"../adjunct/mms/thum1/".$img_name,"",640,$ysize,"");
          $show_img = "adjunct/mms/thum1/".$img_name;
      } else if($size[1] > 480) {
          $xsize = $size[0] * (480/ $size[1]);
          thumbnail("../adjunct/mms/thum/".$img_name,"../adjunct/mms/thum1/".$img_name,"",$xsize,480,"");
          $show_img = "adjunct/mms/thum1/".$img_name;
      } else {
          $show_img = "adjunct/mms/thum/".$img_name;
      }
    }
  }

  if($show_img){
    $addQuery = " object='$show_img', ";
  }
  $sql_update = "update Gn_event set event_title='{$title}', event_desc='{$desc}', event_sms_desc='{$htime}', event_info='{$msg_title}', event_type='{$mtime}', short_url='{$short_url}', read_cnt={$read_cnt},".$addQuery." regdate='{$regdate}', callback_no='{$send_cnt}', event_req_link='{$msg_desc}', daily_req_link='{$daily_req_link}' where event_idx={$id}";

  mysqli_query($self_con,$sql_update);
  if(isset($_POST['admin'])){
    echo "<script>alert('수정되었습니다.');location='/admin/daily_msg_list_service.php';</script>";
  }else{
    echo "<script>alert('수정되었습니다.');location='/iama/service_Iam_admin.php?mode=daily';</script>";
  }
}else if(isset($_POST['del'])){
  $id = $_POST['id'];
  $sql_daily = "select gd_id from Gn_daily where event_idx={$id} and event_idx!=0";
  $res_daily = mysqli_query($self_con,$sql_daily);
  while($row_daily = mysqli_fetch_array($res_daily)){
    $gd_id = $row_daily['gd_id'];
    $sql = "delete from Gn_daily where gd_id={$gd_id}";
    $result=mysqli_query($self_con,$sql);      
    
    $query = "delete from Gn_daily_date where gd_id='$gd_id'";
    mysqli_query($self_con,$query);    
        
    $query = "delete from Gn_MMS where gd_id='$gd_id' ";
    mysqli_query($self_con,$query); 

    $query = "delete from gn_mail where gd_id='$gd_id' ";
    mysqli_query($self_con,$query);  
  }

  $sql_del = "delete from Gn_event where event_idx={$id}";
  mysqli_query($self_con,$sql_del);
  echo 1;
}
else if(isset($_POST['show_req_mem'])){
  $event_idx = $_POST['event_idx'];

  $echo_str = "";
  $sql_req_mem = "select b.* from Gn_event a inner join Gn_daily b on a.event_idx=b.event_idx where a.event_idx={$event_idx} Group by b.mem_id";
  $res_req_mem = mysqli_query($self_con,$sql_req_mem);
  $i = 0;
  while($row_req_mem = mysqli_fetch_array($res_req_mem)){
    $i++;
    $sql_name = "select mem_name from Gn_Member where mem_id='{$row_req_mem['mem_id']}'";
    $res_name = mysqli_query($self_con,$sql_name);
    $row_name = mysqli_fetch_array($res_name);

    $echo_str .= "<tr><td class='iam_table'>".$i."</td>";
    $echo_str .= "<td class='iam_table'>".$row_name['mem_name']."</td>";
    $echo_str .= "<td class='iam_table'>".$row_req_mem['mem_id']."</td>";
    $echo_str .= "<td class='iam_table'><a href='javascript:del_req_data(`".$event_idx."`, `".$row_req_mem['mem_id']."`)'>삭제</a></td></tr>";
  }

  echo $echo_str;
}
else if(isset($_POST['show_req_mem_admin'])){
  $event_idx = $_POST['event_idx'];

  $echo_str = "";
  $sql_req_mem = "select b.* from Gn_event a inner join Gn_daily b on a.event_idx=b.event_idx where a.event_idx={$event_idx}";
  $res_req_mem = mysqli_query($self_con,$sql_req_mem);
  $i = 0;
  while($row_req_mem = mysqli_fetch_array($res_req_mem)){
    $i++;
    $sql_name = "select mem_name from Gn_Member where mem_id='{$row_req_mem['mem_id']}'";
    $res_name = mysqli_query($self_con,$sql_name);
    $row_name = mysqli_fetch_array($res_name);

    $echo_str .= "<tr><td class='iam_table'>".$i."</td>";
    $echo_str .= "<td class='iam_table'>".$row_name['mem_name']."</td>";
    $echo_str .= "<td class='iam_table'>".$row_req_mem['mem_id']."</td>";
    $echo_str .= "<td class='iam_table'><a href='javascript:show_detail_daily(`".$row_req_mem['gd_id']."`, `".$event_idx."`, `".$row_req_mem['mem_id']."`)'>상세보기</a>/<a href='javascript:del_req_data(`".$event_idx."`, `".$row_req_mem['mem_id']."`)'>삭제</a></td></tr>";
  }

  echo $echo_str;
}
else if(isset($_POST['show_req_mem_detail'])){
  $event_idx = $_POST['event_idx'];
  $gd_id = $_POST['gd_id'];
  $mem_id = $_POST['mem_id'];

  $echo_str = "";
  $sql_req_mem = "select * from Gn_daily where event_idx={$event_idx} and gd_id={$gd_id} and mem_id='{$mem_id}'";
  $res_req_mem = mysqli_query($self_con,$sql_req_mem);
  $i = 0;
  while($row_req_mem = mysqli_fetch_array($res_req_mem)){
    $i++;
    $ksql="select * from Gn_MMS_Group where idx='{$row_req_mem['group_idx']}'";
    $kresult=mysqli_query($self_con,$ksql) or die(mysqli_error($self_con));
    $krow = mysqli_fetch_array($kresult);

    $sql="select count(*) cnt from Gn_daily_date where gd_id='{$row_req_mem['gd_id']}'";
    $sresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $srow = mysqli_fetch_array($sresult);

    $echo_str .= "<tr><td class='iam_table'>".$row_req_mem['send_num']."</td>";
    $echo_str .= "<td class='iam_table'>".$row_req_mem['total_count']."</td>";
    $echo_str .= "<td class='iam_table'>".$srow['cnt']."</td>";
    $echo_str .= "<td class='iam_table'>".$row_req_mem['daily_cnt']."</td>";
    $echo_str .= "<td class='iam_table'>".$row_req_mem['start_date']."</td>";
    $echo_str .= "<td class='iam_table'>".$row_req_mem['end_date']."</td>";
    $echo_str .= "<td class='iam_table'>".$row_req_mem['reg_date']."</td></tr>";
  }

  echo $echo_str;
}
else if(isset($_POST['del_req_mem'])){
  $event_idx = $_POST['event_idx'];
  $mem_id = $_POST['mem_id'];

  $sql_data = "select * from Gn_daily where mem_id='{$mem_id}' and event_idx={$event_idx} and event_idx!=0";
  $res_data = mysqli_query($self_con,$sql_data);
  while($row_data = mysqli_fetch_array($res_data)){
    $gd_id = $row_data['gd_id'];
    $query = "delete from Gn_daily_date where gd_id='{$gd_id}'";
    mysqli_query($self_con,$query);
    $query = "delete from Gn_MMS where gd_id='{$gd_id}' ";
    mysqli_query($self_con,$query);
    $sql_del = "delete from Gn_daily where gd_id='{$gd_id}'";
    mysqli_query($self_con,$sql_del);
    $query = "delete from gn_mail where gd_id='{$gd_id}' ";
    mysqli_query($self_con,$query);  
  }
  echo 1;
}
else if(isset($_POST['mode'])){
  $event_idx = $_POST['id'];
  $status = $_POST['status'];
  if($status){
    $sql_update = "update Gn_Iam_Service set daily_msg_event_idx={$event_idx} where mem_id='iam1'";
    mysqli_query($self_con,$sql_update);

    $sql_service_ids = "select mem_id, main_domain from Gn_Iam_Service where mem_id!='iam1' order by idx asc";
    // echo $sql_service_ids; exit;
    $res_service_ids = mysqli_query($self_con,$sql_service_ids);
    while($row_servicee_ids = mysqli_fetch_array($res_service_ids)){
      $sql_mem_data = "select mem_phone from Gn_Member where mem_id='{$row_servicee_ids['mem_id']}'";
      $res_mem_data = mysqli_query($self_con,$sql_mem_data);
      $row_mem_data = mysqli_fetch_array($res_mem_data);
      if($row_mem_data['mem_phone'] != ""){
          $rand_num = rand(100, 999);
          $cur_time1 = date("YmdHis");
          $event_name_eng = "데일리문자 신청".$cur_time1.$rand_num;
          $pcode = "dailymsg".$cur_time1.$rand_num;

          $sql_dup_event = "INSERT INTO Gn_event(event_name_kor, event_name_eng, event_title, event_desc, event_info, event_sms_desc, pcode, event_type, mobile, regdate, ip_addr, m_id, short_url, read_cnt, cnt, object, callback_no, event_req_link, daily_req_link) 
          (SELECT event_name_kor, '{$event_name_eng}', event_title, event_desc, event_info, event_sms_desc, '{$pcode}', event_type, '{$row_mem_data['mem_phone']}', now(), ip_addr, '{$row_servicee_ids['mem_id']}', '', 0, cnt, object, callback_no, event_req_link, daily_req_link FROM Gn_event WHERE event_idx='{$event_idx}')";
          mysqli_query($self_con,$sql_dup_event) or die(mysqli_error($self_con));
          $event_idx = mysqli_insert_id($self_con);
      
          $transUrl = $row_servicee_ids['main_domain']."/event/dailymsg.php?pcode=".$pcode."&eventidx=".$event_idx;
          $transUrl = get_short_url($transUrl);
          $insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
          mysqli_query($self_con,$insert_short_url) or die(mysqli_error($self_con));
      }
    }
  }
  else{
    $sql_format = "update Gn_Iam_Service set daily_msg_event_idx=0 where daily_msg_event_idx!=0 and mem_id='iam1'";
    mysqli_query($self_con,$sql_format);
  }
  echo 1;
}
?>