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
            insert_db($sms_idx_arr[$j], $ids_arr[$i], $self_con);
          }
        } else {
          insert_db($sms_idx, $ids_arr[$i], $self_con);
        }
      }
    } else {
      if (strpos($sms_idx, ",") !== false) {
        $sms_idx_arr = explode(",", $sms_idx);
        for ($j = 0; $j < count($sms_idx_arr); $j++) {
          insert_db($sms_idx_arr[$j], $ids, $self_con);
        }
      } else {
        insert_db($sms_idx, $ids, $self_con);
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
            insert_db_reqlink($req_idx_arr[$j], $ids_arr[$i], $self_con);
          }
        } else {
          insert_db_reqlink($req_idx, $ids_arr[$i], $self_con);
        }
      }
    } else {
      if (strpos($req_idx, ",") !== false) {
        $req_idx_arr = explode(",", $req_idx);
        for ($j = 0; $j < count($req_idx_arr); $j++) {
          insert_db_reqlink($req_idx_arr[$j], $ids, $self_con);
        }
      } else {
        insert_db_reqlink($req_idx, $ids, $self_con);
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
            insert_db_landlink($land_idx_arr[$j], $ids_arr[$i], $self_con);
          }
        } else {
          insert_db_landlink($land_idx, $ids_arr[$i], $self_con);
        }
      }
    } else {
      if (strpos($land_idx, ",") !== false) {
        $land_idx_arr = explode(",", $land_idx);
        for ($j = 0; $j < count($land_idx_arr); $j++) {
          insert_db_landlink($land_idx_arr[$j], $ids, $self_con);
        }
      } else {
        insert_db_landlink($land_idx, $ids, $self_con);
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

function insert_db($idx, $mem_id, $self_con)
{
  $date = time();
  $num = rand(10, 90);
  $event_name_eng = $date . $num;
  $sql_mem_chk = "select * from Gn_Member where mem_id='{$mem_id}'";
  $res_mem_chk = mysqli_query($self_con, $sql_mem_chk);
  $row_mem_chk = mysqli_num_rows($res_mem_chk);
  if ($row_mem_chk) {
    $sql = "INSERT INTO Gn_event_sms_info(event_idx, event_name_eng, mobile, reservation_title, reservation_desc, m_id, regdate)
                      (SELECT event_idx, '{$event_name_eng}', mobile, reservation_title, reservation_desc, '{$mem_id}', now() FROM Gn_event_sms_info where sms_idx={$idx})";
    $result = mysqli_query($self_con, $sql);
    $sms_idx = mysqli_insert_id($self_con);
    $sql_step_info = "INSERT INTO Gn_event_sms_step_info(sms_idx, step, send_day, send_time, title, content, image, image1, image2, regdate, send_deny) 
                                (SELECT {$sms_idx}, step, send_day, send_time, title, content, image, image1, image2, now(), send_deny FROM Gn_event_sms_step_info WHERE sms_idx={$idx})";
    mysqli_query($self_con, $sql_step_info);
  } else {
    return;
  }
}

function insert_db_reqlink($idx, $mem_id, $self_con)
{
  $date = time();
  $num = rand(10, 90);
  $pcode = $event_name_eng = $date . $num;

  $sql_mem_chk = "select * from Gn_Member where mem_id='{$mem_id}'";
  $res_mem_chk = mysqli_query($self_con, $sql_mem_chk);
  $row_mem_chk = mysqli_num_rows($res_mem_chk);
  if ($row_mem_chk) {
    $mem_data = mysqli_fetch_array($res_mem_chk);
    $phone_num = $mem_data['mem_phone'];

    $sql = "INSERT INTO Gn_event(event_name_kor, event_name_eng, event_title, event_desc, event_info, event_sms_desc, pcode, event_type, mobile, regdate, ip_addr, m_id, short_url, read_cnt, cnt, object, callback_no, event_req_link, daily_req_link,sms_idx1,sms_idx2,sms_idx3)
                      (SELECT event_name_kor, '{$event_name_eng}', event_title, event_desc, event_info, event_sms_desc, '{$pcode}', event_type, '{$phone_num}', now(), ip_addr, '{$mem_id}', '', 0, cnt, object, callback_no, event_req_link, daily_req_link,sms_idx1,sms_idx2,sms_idx3 FROM Gn_event where event_idx={$idx})";
    $result = mysqli_query($self_con, $sql);

    $event_idx = mysqli_insert_id($self_con);
    $transUrl = "http://" . $GLOBALS['host'] . "/event/event.html?pcode=" . $pcode . "&sp=" . $event_name_eng;
    $transUrl = get_short_url($transUrl);
    $insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
    mysqli_query($self_con, $insert_short_url) or die(mysqli_error($self_con));
  } else {
    return;
  }
}

function insert_db_landlink($idx, $mem_id, $self_con)
{
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
      $sql = "select * from Gn_event where event_name_eng='{$row_land_data['pcode']}'";
      $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
      $event_data = $row = mysqli_fetch_array($result);
      $sp = $event_data['pcode'];
    }
    $sql = "INSERT INTO Gn_landing(title, description, content, movie_url, footer_content, file, alarm_sms_yn, reply_yn, request_yn, pcode, read_cnt, regdate, short_url, status_yn, lecture_yn, cnt, m_id, event_idx)
                      (SELECT title, description, content, movie_url, footer_content, file, alarm_sms_yn, reply_yn, request_yn, pcode, 0, now(), '', status_yn, lecture_yn, cnt, '{$mem_id}', event_idx FROM Gn_landing where landing_idx={$idx})";
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
