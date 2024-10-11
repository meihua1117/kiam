<?
include_once "../lib/rlatjd_fun.php";

if(isset($_POST['ID'])){
  $ID = $_POST['ID'];
  if(isset($_POST['del_all'])){
    $method = $_POST['method'];
    if($method == "send"){
      $sql = "delete from Gn_Item_Pay_Result where point_val=3 and buyer_id='{$ID}' and type='noticesend'";
      $result = mysqli_query($self_con,$sql);
      echo $result;
    }
    else if($method == "recv"){
      $sql = "delete from Gn_Item_Pay_Result where point_val=3 and buyer_id='{$ID}' and type='noticerecv'";
      $result = mysqli_query($self_con,$sql);
      echo $result;
    }
  }
  else{
    $list_type = $_POST['type'];
    $search_key = $_POST['search_key'];
  
    $echostr = '';
    $search_str = '';
    $str_search = "";
  
    if($list_type == "noticerecv"){
      $search_str = " and type='noticerecv' and buyer_id='{$ID}'";
    }
    else if($list_type == "noticesend"){
      $search_str = " and type='noticesend' and buyer_id='{$ID}'";
    }

    if($search_key != ""){
      $str_search = " and (message like '%".$search_key."%' or pay_method like '%".$search_key."%')";
    }
    $sql = "select * from Gn_Item_Pay_Result where point_val=3 ";
    $orderQuery = " ORDER BY pay_date DESC limit 10 ";
    if(isset($_POST['more']) && $_POST['more'] == 'more'){
      $orderQuery = " ORDER BY pay_date DESC limit 20 ";
    }
    $query = $sql.$search_str.$str_search.$orderQuery;
    // echo $query;exit;
    $result = mysqli_query($self_con,$query);
    while($row = mysqli_fetch_array($result)){  
      $sql_mem_data = "select * from Gn_Member where mem_id='{$row["pay_method"]}'";
      $res_mem = mysqli_query($self_con,$sql_mem_data);
      $row_mem = mysqli_fetch_array($res_mem);

      $message = str_replace("\n", "<br>", $row["message"]);
      $message = str_replace('"', ' ', $message);

      $echostr .= '<tr>
                <td class="iam_table">'.$row["pay_date"].'</td>
                <td class="iam_table"><a href="javascript:show_more_con_detail(`'.$message.'`, `notice`)">'.cut_str($row['message'], 10).'</a></td>
                <td class="iam_table"><a href="'.$row["site"].'" target="_blank">'.$row["site"].'</td>
                <td class="iam_table">'.$row["pay_method"].'/'.$row_mem["mem_name"].'</td>
                <td class="iam_table"><a href="javascript:remove_recv_notice('.$row['no'].')">삭제</a></td>
                </tr>';
    }
    echo $echostr;
  }
}
?>