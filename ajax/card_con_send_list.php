<?
include_once "../lib/rlatjd_fun.php";

if(isset($_POST['ID'])){
  $ID = $_POST['ID'];
  if(isset($_POST['del_all'])){
    $method = $_POST['method'];
    if($method == "send"){
      $sql = "update Gn_Item_Pay_Result set list_show=0 where buyer_id='{$ID}' and type='contentssend'";
      $result = mysqli_query($self_con,$sql);
      echo $result;
    }
    else if($method == "recv"){
      $card_url = array();
      $i = 0;
      $sql_con_idx = "select no from Gn_Item_Pay_Result where buyer_id='{$ID}' and type='contentsrecv' and list_show=1";
      $res_idx = mysqli_query($self_con,$sql_con_idx);
      while($row_idx = mysqli_fetch_array($res_idx)){
        $card_url[$i] = $row_idx['no'];
        $i++;
      }

      // $sql = "delete from Gn_Item_Pay_Result where pay_method='{$ID}' and type='contentssend'";
      // mysqli_query($self_con,$sql);

      echo json_encode($card_url);
    }
    else{
      $sql = "update Gn_Item_Pay_Result set list_show=0 where buyer_id='{$ID}' and type='cardsend'";
      $result = mysqli_query($self_con,$sql);
      echo $result;
    }
  }
  else{
    $list_type = $_POST['type'];
    $search_key = $_POST['search_key'];

    // $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
    // $startPage = $nowPage?$nowPage:1;
    // $pageCnt = 20;
  
    // $sql = "select * from Gn_Item_Pay_Result where point_val=1 or (point_val=2 and receive_state=1)".$searchStr;
    // $res = mysqli_query($self_con,$sql);
    // $totalCnt = mysqli_num_rows($res);
  
    // $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
    // $number = $totalCnt - ($nowPage - 1) * $pageCnt;
  
    $echostr = '';
    $search_str = '';
    $str_search = "";
  
    if($list_type == "card"){
      $search_str = " and type='cardsend' and buyer_id='{$ID}'";
    }
    else if($list_type == "contentssend"){
      $search_str = " and type='contentssend' and buyer_id='{$ID}'";
    }
    else{
      $search_str = " and type='contentsrecv' and buyer_id='{$ID}'";
    }
    if($search_key != ""){
      $str_search = " and (message like '%".$search_key."%' or pay_method like '%".$search_key."%')";
    }
    $sql = "select * from Gn_Item_Pay_Result where point_val=2 and list_show=1 ";
    $orderQuery = " ORDER BY pay_date DESC limit 10 ";
    if(isset($_POST['more']) && $_POST['more'] == 'more'){
      $orderQuery = " ORDER BY pay_date DESC limit 20 ";
    }
    $query = $sql.$search_str.$str_search.$orderQuery;
    // echo $query;exit;
    $result = mysqli_query($self_con,$query);
    while($row = mysqli_fetch_array($result)){
      if($row['type'] == "cardsend"){
        $val1 = explode("?", $row['site']);
        $card_url = trim(substr(trim($val1[1]), 0, 10));
  
        $img_sql = "select main_img1 from Gn_Iam_Name_Card where card_short_url='{$card_url}'";
        $res_img = mysqli_query($self_con,$img_sql);
        $row_img = mysqli_fetch_array($res_img);
        $main_img = $row_img['main_img1'];

        $curpoint = '<td class="iam_table">'.$row["item_price"].'/'.$row["current_point"].'</td>';
      }
      else if($row['type'] == "contentssend" || $row['type'] == "contentsrecv"){
        $val1 = explode("=", $row['site']);
        $con_idx = $val1[1];
  
        $con_img = "select contents_img from Gn_Iam_Contents where idx={$con_idx}";
        $res_con_img = mysqli_query($self_con,$con_img);
        $row_con_img = mysqli_fetch_array($res_con_img);
        $main_img = $row_con_img['contents_img'];

        $curpoint = "";
      }
  
      $contents = "";
      if($list_type == "contentssend"){
        $state = $row['con_show_cnt'];
        $sql_mem_data = "select * from Gn_Member where mem_id='{$row["pay_method"]}'";
        $res_mem = mysqli_query($self_con,$sql_mem_data);
        $row_mem = mysqli_fetch_array($res_mem);

        $pay_method = '<td class="iam_table">'.$row["pay_method"].'/'.$row_mem['mem_name'].'</td>';
        if($row['message'] != ""){
          $type = "알림형";
          $row['message'] = str_replace("\n", "<br>", $row['message']);
          $detail = '<td class="iam_table"><a href="javascript:show_more_con_detail(`'.$row["message"].'`, `contents`)">'.cut_str($row['message'], 10).'</a></td>';
        }
        else{
          $type = "수신함";
          $detail = '<td class="iam_table"><img src="'.$main_img.'" style="width:50px;height:50px;"><br><a href="'.$row['site'].'" target="_blank">미리보기</a></td>';
        }
        $echostr .= '<tr>
                  <td class="iam_table">'.$row["pay_date"].'</td>
                  <td class="iam_table">'.$type.'</td>
                  '.$detail.'
                  '.$pay_method.'
                  <td class="iam_table">'.$row["item_price"].'/'.$row["current_point"].'</td>
                  <td class="iam_table"><a href="javascript:del_send_list('.$row['no'].', '.$contents.')">삭제</a></td>
                  </tr>';
      }
      else{
        $sql_mem_data = "select * from Gn_Member where mem_id='{$row["pay_method"]}'";
        $res_mem = mysqli_query($self_con,$sql_mem_data);
        $row_mem = mysqli_fetch_array($res_mem);

        if($list_type == "contentsrecv"){
          $state = $row['pay_method'].'/'.$row_mem['mem_name'];
          $pay_method = '';
          $contents = "'contents'";
        }
        else{
          if($row['receive_state'] == 1){
              $state = "Y";
          }
          else{
            $state = "N";
          }
          $pay_method = '<td class="iam_table">'.$row["pay_method"].'/'.$row_mem['mem_name'].'</td>';
        }
        
        $echostr .= '<tr>
                    <td class="iam_table">'.$row["pay_date"].'</td>
                    <td class="iam_table"><img src="'.$main_img.'" style="width:50px;height:50px;"><br><a href="'.$row['site'].'" target="_blank">미리보기</a></td>
                    '.$pay_method.'
                    <td class="iam_table">'.$state.'</td>
                    '.$curpoint.'
                    <td class="iam_table"><a href="javascript:del_send_list('.$row['no'].', '.$contents.')">삭제</a></td>
                    </tr>';
      }
    }
    echo $echostr;
  }
}
?>