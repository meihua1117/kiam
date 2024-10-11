<?
include_once "../lib/rlatjd_fun.php";
if (isset($_POST['edit_ev'])) {
  $ID = $_POST['ID'];
  $event_id = $_POST['id'];
  $sql = "select a.*, b.* from Gn_event a inner join gn_mms_callback b on a.callback_no=b.idx where a.event_idx={$event_id}";
  $res = mysqli_query($self_con, $sql);
  while ($row = mysqli_fetch_array($res)) {
    $arr_replay['id'] = $event_id;
    $arr_replay['m_id'] = $row['m_id'];
    $arr_replay['event_info'] = $row['event_info'];
    $arr_replay['event_sms_desc'] = $row['event_sms_desc'];
    $arr_replay['img'] = $row['object'];
    $arr_replay['iam_link'] = $row['event_type'];
    $arr_replay['short_url'] = $row['short_url'];
    $arr_replay['allow_state'] = $row['allow_state'];
    $arr_replay['read_cnt'] = $row['read_cnt'];
    $arr_replay['regdate'] = $row['regdate'];
    $arr_replay['event_desc_call'] = $row['event_desc'];
    $arr_replay['event_title_call'] = $row['event_title'];
  }
  echo json_encode($arr_replay);
} else if (isset($_POST['save'])) {
  $id = $_POST['call_event_idx'];
  $title = $_POST['call_event_title'];
  $desc = $_POST['call_event_desc'];
  $iam_link = $_POST['call_iam_link'];
  $short_url = $_POST['call_short_url'];
  $read_cnt = $_POST['call_read_cnt'];
  $regdate = $_POST['call_regdate1'];
  $event_desc = $_POST['event_desc_call'];
  $event_title = $_POST['event_title_call'];

  if ($_FILES['call_event_img']['name']) {
    $info = explode(".", $_FILES['call_event_img']['name']);
    $ext = $info[count($info) - 1];
    $filename = time() . "." . $ext;
    //메인에 옮길때 수정패치 할것 test->www
    $call_event_img_path = "http://www.kiam.kr" . gcUpload($_FILES['call_event_img']['name'], $_FILES['call_event_img']['tmp_name'], $_FILES['call_event_img']['size'], "ad", $filename);
  }

  if ($call_event_img_path) {
    $addQuery = " object='$call_event_img_path', ";
    $addQuery1 = " img='$call_event_img_path', ";
  }
  $sql_update = "update Gn_event set event_title='{$event_title}',event_info='{$title}', event_desc='{$event_desc}', event_sms_desc='{$desc}', event_type='{$iam_link}', short_url='{$short_url}', read_cnt='{$read_cnt}'," . $addQuery . " regdate='{$regdate}' where event_idx='{$id}'";
  mysqli_query($self_con, $sql_update);

  $sql_call_id = "select callback_no from Gn_event where event_idx={$id}";
  $res_call_id = mysqli_query($self_con, $sql_call_id);
  $row_call_id = mysqli_fetch_array($res_call_id);
  $sql_update_mms = "update gn_mms_callback set title='{$title}', content='{$desc}', " . $addQuery1 . " iam_link='{$iam_link}', regdate=NOW() where idx={$row_call_id[0]}";

  mysqli_query($self_con, $sql_update_mms);
  echo "<script>alert('수정되었습니다.');history.back(-1);</script>";
} else if (isset($_POST['del'])) {
  $id = $_POST['id'];
  $sql_del = "delete from Gn_event where event_idx={$id}";
  mysqli_query($self_con, $sql_del);
  $sql_call_id = "select callback_no from Gn_event where event_idx={$id}";
  $res_call_id = mysqli_query($self_con, $sql_call_id);
  $row_call_id = mysqli_fetch_array($res_call_id);
  $sql_del_call = "delete from gn_mms_callback where idx={$row_call_id[0]}";
  mysqli_query($self_con, $sql_del_call);

  $sql_mem_info = "select * from Gn_Member where (mem_callback_mun_state=1 and mun_callback={$row_call_id[0]}) or (mem_callback_phone_state=1 and phone_callback={$row_call_id[0]})";
  $res_mem_info = mysqli_query($self_con, $sql_mem_info);
  if (mysqli_num_rows($res_mem_info)) {
    while ($row_mem_info = mysqli_fetch_array($res_mem_info)) {
      $sql_sel_first_main = "select * from gn_mms_callback where service_state=0 order by idx asc limit 1";
      $res_main = mysqli_query($self_con, $sql_sel_first_main);
      $row_main = mysqli_fetch_array($res_main);
      $main_idx = $row_main['idx'];

      if ($row_mem_info['phone_callback'] == $cam_id && $row_mem_info['mun_callback'] == $cam_id) {
        $sql_update = "update Gn_Member set phone_callback={$main_idx}, mun_callback={$main_idx} where mem_id='{$row_mem_info['mem_id']}'";
      } else if ($row_mem_info['phone_callback'] == $cam_id) {
        if ($row_mem_info['mun_callback'] == $main_idx) {
          $sql_update = "update Gn_Member set phone_callback={$main_idx}, phone_callback_state=3, mun_callback_state=3 where mem_id='{$userid}'";
        } else {
          $sql_update = "update Gn_Member set phone_callback={$main_idx} where mem_id='{$row_mem_info['mem_id']}'";
        }
      } else if ($row_mem_info['mun_callback'] == $cam_id) {
        if ($row_mem_info['phone_callback'] == $main_idx) {
          $sql_update = "update Gn_Member set mun_callback={$main_idx}, phone_callback_state=3, mun_callback_state=3 where mem_id='{$row_mem_info['mem_id']}'";
        } else {
          $sql_update = "update Gn_Member set mun_callback={$main_idx} where mem_id='{$row_mem_info['mem_id']}'";
        }
      }
      mysqli_query($self_con, $sql_update);
    }
  }
  echo 1;
} else if (isset($_POST['update_state'])) {
  $id = $_POST['id'];
  $status = $_POST['status'];
  $sql = "update gn_mms_callback set allow_state={$status} where idx={$id}";
  mysqli_query($self_con, $sql);
  echo 1;
} /*else if (isset($_POST['update_callback_time'])) {
  $idx = $_POST['callback_idx'];
  $callback_time = $_POST['update_callback_time'];
  $sql = "update gn_mms_callback set callback_time={$callback_time} where idx={$idx}";
  mysqli_query($self_con, $sql);
  echo 1;
} */else if (isset($_POST['get_callback_list'])) {
  $mem_id = $_POST['mem_id'];
  if (!$mem_id) {
    echo '';
    exit;
  }
  $res_str = "";
  $sql = "select a.*, b.* from Gn_event a inner join gn_mms_callback b on a.callback_no=b.idx where b.service_state=1 and a.m_id='{$mem_id}' and a.event_name_kor='콜백메시지관리자설정동의' order by b.regdate desc";
  $res = mysqli_query($self_con, $sql);
  $i = 0;
  while ($row = mysqli_fetch_array($res)) {
    $str_sel_mem = "";
    $str_unsel_mem = "";
    $i++;
    if ($row['allow_state'] == 1) {
      $checked = "checked";
    } else {
      $checked = "";
    }
    if ($row['duplicate_event_idx']) {
      $checked_dup = "checked";
    } else {
      $checked_dup = "";
    }
    $title = str_replace("\n", "<br>", $row['title']);
    $content = str_replace("\n", "<br>", $row['content']);

    $sql_sel_service_mem = "select * from Gn_Member where mem_callback={$row['idx']}";
    $res_sel_service = mysqli_query($self_con, $sql_sel_service_mem);
    $cnt_sel_service = mysqli_num_rows($res_sel_service);

    $sql_sel_mem = "select mem_id, mem_name, phone_callback, mun_callback from Gn_Member use index(callback) where mem_callback={$row['idx']} and ((phone_callback={$row['idx']} and mem_callback_phone_state=1) or (mun_callback={$row['idx']} and mem_callback_mun_state=1))";
    $res_sel_mem = mysqli_query($self_con, $sql_sel_mem);
    $cnt_sel_mem = mysqli_num_rows($res_sel_mem);
    while ($row_sel_mem = mysqli_fetch_array($res_sel_mem)) {
      $str_sel_mem .= $row_sel_mem['mem_id'] . " (" . $row_sel_mem['mem_name'] . ") ";
    }

    $sql_unsel_mem = "select mem_id, mem_name, phone_callback, mun_callback from Gn_Member use index(callback) where (phone_callback!={$row['idx']} or mem_callback_phone_state=0) and (mun_callback!={$row['idx']} or mem_callback_mun_state=0) and mem_callback={$row['idx']}";
    $res_unsel_mem = mysqli_query($self_con, $sql_unsel_mem);
    while ($row_unsel_mem = mysqli_fetch_array($res_unsel_mem)) {
      $str_unsel_mem .= $row_unsel_mem['mem_id'] . " (" . $row_unsel_mem['mem_name'] . ") ";
    }

    $cnt_unsel_mem = $cnt_sel_service * 1 - $cnt_sel_mem * 1;
    $res_str .= '<tr>';
    $res_str .= '<td class="iam_table">' . $i . '</td>';
    $res_str .= '<td class="iam_table"><a href="javascript:show_more(`' . $title . '`)">' . cut_str($row['title'], 5) . '</a></td>';
    $res_str .= '<td class="iam_table"><a href="javascript:show_more(`' . $content . '`)">' . cut_str($row['content'], 10) . '</a></td>';
    $res_str .= '<td class="iam_table"><a href="' . $row['img'] . '" target="_blank"><img class="zoom" src="' . $row['img'] . '" style="width:90%;"></a> </td>';
    $res_str .= '<td class="iam_table"><a style="cursor:pointer" onclick="newpop(`' . $row['short_url'] . '`)">미리보기</a><br><a style="cursor:pointer" onclick="copy(`' . $row['short_url'] . '`)">링크복사</a></td>';
    $res_str .= '<td class="iam_table">' . $row['read_cnt'] . '</td>';
    $res_str .= '<td class="iam_table"><a href="javascript:show_mem_info(`select`, `' . $str_sel_mem . '`);">' . $cnt_sel_mem . '</a>&nbsp;/&nbsp;<a href="javascript:show_mem_info(`unselect`, `' . $str_unsel_mem . '`);">' . $cnt_unsel_mem . '</a></td>';
    $res_str .= '<td class="iam_table">' . $row['regdate'] . '</td>';
    /*$callback_time = "<select name=\"callback_time\" class=\"select\" onchange=\"set_callback_time(" . $row['idx'] . ",$(this))\">" .
      "<option value=\"0\" " . ($row['callback_time'] == 0 ? "selected" : "") . ">1회만 발송</option>" .
      "<option value=\"1\" " . ($row['callback_time'] == 1 ? "selected" : "") . ">매일 1회발송</option>" .
      "<option value=\"2\" " . ($row['callback_time'] == 2 ? "selected" : "") . ">매주 1회발송</option>" .
      "<option value=\"3\" " . ($row['callback_time'] == 3 ? "selected" : "") . ">매월 1회발송</option>" .
      "</select> ";
    $res_str .= '<td class="iam_table">' . $callback_time . '</td>';*/
    $res_str .= '<td class="iam_table">';
    $res_str .=     '<label class="switch" onchange="set_callback_state($(this))">';
    $res_str .=         '<input type="checkbox" name="status" id="stauts_' . $row['idx'] . '" value="' . $row['idx'] . '" ' . $checked . '>';
    $res_str .=         '<span class="slider round" name="status_round" id="stauts_round_' . $row['idx'] . '"></span>';
    $res_str .=     '</label>';
    $res_str .= '</td>';
    $res_str .= '<td class="iam_table"><a style="cursor:pointer" onclick="edit_ev_callback(' . $row['event_idx'] . ')">수정</a><br><a style="cursor:pointer" onclick="delete_ev_callback(' . $row['event_idx'] . ')">삭제</a></td>';
    if ($mem_id == "iam1") {
      $res_str .= '<td class="iam_table">';
      $res_str .=   '<label class="switch_callback_copy">';
      $res_str .=       '<input type="checkbox" name="status" id="stauts_' . $row['idx'] . '" value="' . $row['event_idx'] . '" ' . $checked_dup . '>';
      $res_str .=       '<span class="slider round" name="status_round" id="stauts_round_' . $row['idx'] . '"></span>';
      $res_str .=       '<input type="hidden" name="mms_id" id="mms_id" value="' . $row['callback_no'] . '">';
      $res_str .=   '</label>';
      $res_str .= '</td>';
    }
    $res_str .= '</tr>';
  }
  echo $res_str;
}
