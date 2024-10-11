<?
include_once "../lib/rlatjd_fun.php";

if(isset($_POST['ID'])){
  $start_date = $_POST['start'];
  $end_date = $_POST['end'];
  // $type = $_POST['type'];
  $item_type = $_POST['item_type'];
  $ID = $_POST['ID'];

  $str_start = ' 00:00:00';
  $str_end = ' 23:59:59';

  $echostr = '';
  $searchstr = '';
  if(($start_date != '') && ($end_date != '')){
    $searchstr .= " AND pay_date >= '$start_date$str_start' and pay_date <= '$end_date$str_end'";
  }
  // if($type != ''){
  //   $searchstr .= " AND type='{$type}'";
  // }
  // if($ID != ''){
  //   $searchstr .= " AND buyer_id like '%{$ID}%'";
  // }

  $sql = "select * from Gn_Item_Pay_Result where list_show=1 and item_name='AI카드' and buyer_id='{$ID}'";
  $orderQuery = " ORDER BY pay_date DESC limit 10 ";
  if(isset($_POST['more']) && $_POST['more'] == 'see_more'){
    $orderQuery = " ORDER BY pay_date DESC limit 20 ";
  }
  $query = $sql.$searchstr.$orderQuery;
  // echo $query;exit;
  $result = mysqli_query($self_con,$query);
  while($row = mysqli_fetch_array($result)){
    if(($row['type'] == "buy") || ($row['type'] == "service")){
        $type = "충전";
    }
    else if($row['type'] == "use"){
        $type = "결제";
    }
    else if($row['type'] == "minus"){
        $type = "차감";
    }
    else if($row['type'] == "servicebuy"){
        $type = "판매";
    }
    else if($row['type'] == "cardsend" || $row['type'] == "contentssend"){
        $type = "전송";
    }
    else if($row['type'] == "contentsrecv"){
        $type = "수신";
    }
    else{
        $type = "쉐어";
    }

    if($row['type'] == "cardsend" || $row['type'] == "contentssend"){
      $str = '<td class="iam_table">'.$row["item_name"].'</td>';
    }
    else{
      $str = '<td class="iam_table">'.$row["pay_method"].'</td>';
    }

    $val1 = explode("?", $row['site']);
    $card_url = trim(substr(trim($val1[1]), 0, 10));

    $img_sql = "select main_img1 from Gn_Iam_Name_Card where card_short_url='{$card_url}'";
    $res_img = mysqli_query($self_con,$img_sql);
    $row_img = mysqli_fetch_array($res_img);
    $main_img = $row_img['main_img1'];

    $echostr .= '<tr>
                <td class="iam_table">'.$row["pay_date"].'</td>
                <td class="iam_table">'.$type.'</td>
                '.$str.'
                <td class="iam_table">'.$row["seller_id"].'</td>
                <td class="iam_table"><img src="'.$main_img.'" style="width:50px;height:50px;"><br><a href="'.$row['site'].'" target="_blank">미리보기</a></td>
                <td class="iam_table">'.$row['current_point'].'P</td>
                <td class="iam_table"><a href="javascript:del_ai_list('.$row['no'].')">삭제</a></td>
                </tr>';
  }
  echo $echostr;
}
?>