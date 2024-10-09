<?
include_once "../lib/rlatjd_fun.php";

if(isset($_POST['memid'])){
  $mem_id = $_POST['memid'];

  $echostr = '';

  $sql_res_point = "select mem_point from Gn_Member where mem_id='{$mem_id}'";
  $res_auto_po = mysql_query($sql_res_point);
  $row_auto_po = mysql_fetch_array($res_auto_po);
  $rest_point = $row_auto_po['mem_point'];
  $sql_auto_con = "select * from auto_update_contents where mem_id='{$mem_id}'";
  $orderQuery = " ORDER BY id DESC limit 10 ";
  if(isset($_POST['more']) && $_POST['more'] == true){
    $orderQuery = " ORDER BY id DESC limit 20 ";
  }
  $query = $sql_auto_con.$orderQuery;
  $res_con = mysql_query($query);
  $i = 0;
  while($row_con=mysql_fetch_array($res_con)){
    $i++;
    switch ($row_con['web_type']){
        case 'peopleid':
            $web_type_con = "인물";
            break;
        case 'newsid':
            $web_type_con = "뉴스";
            break;
        case 'mapid':
            $web_type_con = "지도";
            break;
        case 'gmarketid':
            $web_type_con = "지마켓";
            break;
        case 'blogid':
            $web_type_con = "블로그";
            break;
        case 'youtubeid':
            $web_type_con = "유투브";
            break;
        default:
            $web_type_con = " ";
            break;
    }
    if($row_con['state_flag'] == 1){
        $state = "진행";
        $checked = "checked";
    }
    else{
        $state = "중지";
        $checked = "";
    }

    $echostr .= '
    <tr>
        <td class="iam_table">'.$i.'</td>
        <td class="iam_table">'.$web_type_con.'</td>
        <td class="iam_table">'.$row_con['keyword'].'</td>
        <td class="iam_table">'.$row_con['reg_date'].'</td>
        <td class="iam_table">'.$row_con['settle_cnt'].'</td>
        <td class="iam_table">'.$rest_point.'</td>
        <td class="iam_table">'.$state.'</td>
        <td class="iam_table">
            <a href="javascript:edit_auto_data('.$row_con['id'].')"><img src="/iam/img/menu/icon_edit.png" width="22"></a>
            <label class="switch">
            <input type="checkbox" name="status" id="stauts_'.$row_con[id].'" value="'.$row_con[id].'" '.$checked.'>
            <span class="slider round" name="status_round" id="stauts_round_'.$row_con[id].'"></span>
            </label>
            <a href="javascript:remove_auto_data('.$row_con['id'].')"><img src="/iam/img/menu/icon_card_del.png" width="22"></a>
        </td>
    </tr>';
  }
  //<a href="javascript:stop_auto_data('.$row_con['id'].')"><img src="img/stop_icon.png" width="20"></a>
  echo $echostr;
}
?>