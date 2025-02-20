<?
include_once "../lib/rlatjd_fun.php";
$GLOBALS['host'] = $HTTP_HOST;
if (isset($_POST['send_ids'])) {
  $ids = $_POST['send_ids'];
  if ($_POST['type'] == "step") {
    $sms_idx = $_POST['sms_idx'];

    if (strpos($ids, ",") !== false) {
      $ids_arr = explode(",", $ids);
      for ($i = 0; $i < count($ids_arr); $i++) {
        if (strpos($sms_idx, ",") !== false) {
          $sms_idx_arr = explode(",", $sms_idx);
          for ($j = 0; $j < count($sms_idx_arr); $j++) {
            insert_db($sms_idx_arr[$j], $ids_arr[$i]);
          }
        } else {
          insert_db($sms_idx, $ids_arr[$i]);
        }
      }
    } else {
      if (strpos($sms_idx, ",") !== false) {
        $sms_idx_arr = explode(",", $sms_idx);
        for ($j = 0; $j < count($sms_idx_arr); $j++) {
          insert_db($sms_idx_arr[$j], $ids);
        }
      } else {
        insert_db($sms_idx, $ids);
      }
    }

    $sql_send_ids = "update Gn_Member set step_send_ids='{$ids}' where mem_id='{$_SESSION['one_member_id']}'";
    mysqli_query($self_con, $sql_send_ids);
    echo 1;
  } else if ($_POST['type'] == "reqlink") {
    $req_idx = $_POST['req_idx'];
    if (strpos($ids, ",") !== false) {
      $ids_arr = explode(",", $ids);
      for ($i = 0; $i < count($ids_arr); $i++) {
        if (strpos($req_idx, ",") !== false) {
          $req_idx_arr = explode(",", $req_idx);
          for ($j = 0; $j < count($req_idx_arr); $j++) {
            insert_db_reqlink($req_idx_arr[$j], $ids_arr[$i]);
          }
        } else {
          insert_db_reqlink($req_idx, $ids_arr[$i]);
        }
      }
    } else {
      if (strpos($req_idx, ",") !== false) {
        $req_idx_arr = explode(",", $req_idx);
        for ($j = 0; $j < count($req_idx_arr); $j++) {
          insert_db_reqlink($req_idx_arr[$j], $ids);
        }
      } else {
        insert_db_reqlink($req_idx, $ids);
      }
    }
  } else if ($_POST['type'] == "landlink") {
    $land_idx = $_POST['land_idx'];
    if (strpos($ids, ",") !== false) {
      $ids_arr = explode(",", $ids);
      for ($i = 0; $i < count($ids_arr); $i++) {
        if (strpos($land_idx, ",") !== false) {
          $land_idx_arr = explode(",", $land_idx);
          for ($j = 0; $j < count($land_idx_arr); $j++) {
            insert_db_landlink($land_idx_arr[$j], $ids_arr[$i]);
          }
        } else {
          insert_db_landlink($land_idx, $ids_arr[$i]);
        }
      }
    } else {
      if (strpos($land_idx, ",") !== false) {
        $land_idx_arr = explode(",", $land_idx);
        for ($j = 0; $j < count($land_idx_arr); $j++) {
          insert_db_landlink($land_idx_arr[$j], $ids);
        }
      } else {
        insert_db_landlink($land_idx, $ids);
      }
    }
  }
  echo 1;
} else if (isset($_POST['delete_img'])) {
  $img_val = $_POST['img'];
  $sms_detail_idx = $_POST['sms_detail_idx'];
  if ($img_val == "image1") {
    $img = "image";
  }
  if ($img_val == "image2") {
    $img = "image1";
  }
  if ($img_val == "image3") {
    $img = "image2";
  }
  $sql_update = "update Gn_event_sms_step_info set " . $img . "='' where sms_detail_idx={$sms_detail_idx}";
  mysqli_query($self_con, $sql_update);
  echo 1;
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
                                object = '{$event_row['object']}', 
                                sms_idx1 = '{$sms_idx1}',
                                sms_idx2 = '{$sms_idx2}',
                                sms_idx3 = '{$sms_idx3}'";
    if($event_row['cnt'] <> "")
      $sql.= ",cnt = '{$event_row['cnt']}'";
    if($event_row['event_req_link'] <> "")
      $sql.= ",event_req_link = '{$event_row['event_req_link']}'";
    if($event_row['daily_req_link'] <> "")
      $sql.= ",daily_req_link = '{$event_row['daily_req_link']}'";
    if($event_row['callback_no'] <> "")
      $sql.= ",callback_no = '{$event_row['callback_no']}'";
    mysqli_query($self_con, $sql);

    $event_idx = mysqli_insert_id($self_con);
    $transUrl = "http://" . $GLOBALS['host'] . "/event/event.html?pcode=" . $pcode . "&sp=" . $event_name_eng;
    $transUrl = get_short_url($transUrl);
    $insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
    mysqli_query($self_con, $insert_short_url) or die(mysqli_error($self_con));
  }
  return $pcode;
}

function insert_db_landlink($idx, $mem_id)
{
  global $self_con;
  $sql_mem_chk = "select * from Gn_Member where mem_id='{$mem_id}'";
  $res_mem_chk = mysqli_query($self_con, $sql_mem_chk);
  $row_mem_chk = mysqli_num_rows($res_mem_chk);
  if ($row_mem_chk) {
    $sp = "";
    $mem_data = mysqli_fetch_array($res_mem_chk);
    $phone_num = $mem_data['mem_phone'];
    $sql_land_data = "select * from Gn_landing where landing_idx={$idx}";
    $res_land_data = mysqli_query($self_con, $sql_land_data);
    $row_land_data = mysqli_fetch_array($res_land_data);

    if ($row_land_data['pcode']) {
      $sql = "select event_idx from Gn_event where pcode='{$row_land_data['pcode']}'";
      $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
      $event_data = mysqli_fetch_array($result);
      if ($event_data)
        $sp = insert_db_reqlink($event_data['event_idx'], $mem_id);
      //$sp = $event_data['pcode'];
    }
    $sql = "SELECT title, description, content, movie_url, footer_content, file, alarm_sms_yn, reply_yn, request_yn, pcode, status_yn, lecture_yn, cnt, event_idx FROM Gn_landing where landing_idx={$idx}";
    $result = mysqli_query($self_con, $sql);
    $land_row = mysqli_fetch_assoc($result);

    $sql = "INSERT INTO Gn_landing set title = '{$land_row['title']}', 
                                      description = '{$land_row['description']}', 
                                      content = '{$land_row['content']}', 
                                      movie_url = '{$land_row['movie_url']}', 
                                      footer_content = '{$land_row['footer_content']}', 
                                      file = '{$land_row['file']}', 
                                      alarm_sms_yn = '{$land_row['alarm_sms_yn']}', 
                                      reply_yn = '{$land_row['reply_yn']}', 
                                      request_yn = '{$land_row['request_yn']}', 
                                      pcode = '{$sp}', 
                                      read_cnt = 0, 
                                      regdate = now(), 
                                      short_url = '', 
                                      status_yn = '{$land_row['status_yn']}', 
                                      lecture_yn = '{$land_row['lecture_yn']}', 
                                      cnt = '{$land_row['cnt']}', 
                                      m_id = '{$mem_id}'";
    if ($land_row['event_idx'] != "")
      $sql .= ",event_idx = '{$land_row['event_idx']}'";
    $result = mysqli_query($self_con, $sql);

    $land_idx = mysqli_insert_id($self_con);
    $transUrl = "http://" . $GLOBALS['host'] . "/event/event.html?pcode=" . $row_land_data['pcode'] . "&sp=" . $sp . "&landing_idx=" . $land_idx;
    $transUrl = get_short_url($transUrl);
    $insert_short_url = "update Gn_landing set short_url='{$transUrl}' where landing_idx={$land_idx}";
    mysqli_query($self_con, $insert_short_url) or die(mysqli_error($self_con));
  } else {
    return;
  }
}
?>