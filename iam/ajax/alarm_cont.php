<?
include_once "../../lib/rlatjd_fun.php";
$limit = (intval($_GET['limit']) != 0 ) ? $_GET['limit'] : 10;
$offset = (intval($_GET['offset']) != 0 ) ? $_GET['offset'] : 0;
$contents_count_per_page = $limit;
$w_offset = $offset;
if($w_offset < 0 ){
  $w_offset = 0;
}
$search_key = $_GET['search_key'];
if($_GET['type'] == 'contentsrecv'){
  $sql = "select * from Gn_Item_Pay_Result where point_val=2 and list_show=1 ";
  $sql .= " and type='contentsrecv' and buyer_id='{$_SESSION['iam_member_id']}'";
  if($search_key != "")
    $sql .= " and (message like '%".$search_key."%' or pay_method like '%".$search_key."%')";
  $count_sql = str_replace("*","count(no)",$sql);
  $result = mysqli_query($self_con, $count_sql);
  $row = mysqli_fetch_array($result);
  $cont_count = $row[0];
  $body = "<script>$('#total_count').html('총 ".$cont_count."건');$('#total_count').data('count',".$cont_count.");</script>";
  $sql .= " ORDER BY pay_date DESC ";
  $sql .= " limit $contents_count_per_page , ".$w_offset;
  
  $cont_array = array();
  if(!$global_is_local){
    $redisCache = new RedisCache();
    $cont_list = $redisCache -> get_query_to_array($sql);
    for($i=0 ; $i < count($cont_list); $i++){
        array_push($cont_array,$cont_list[$i]);
    }
  }else{
      $result = mysqli_query($self_con, $sql);
      while( $contents_row = mysqli_fetch_array($result)){
          array_push($cont_array,$contents_row);
      }
  }
  if(count($cont_array) == 0){
    $body .= "<div class=\"content_area\" style=\"text-align:center;margin-left:15px;margin-right:15px;\">".
                "<img src=\"/iam/img/menu/no_alarm.png\" ><br>".
                "<a style=\"text-decoration-line:none;font-weight:bold\">해당 알림이 없습니다.</a>".
              "</div>";
    echo $body;
  }else{
    foreach($cont_array as $row){
      $val1 = explode("=", $row['site']);
      $con_idx = $val1[1];
      $con_img = "select * from Gn_Iam_Contents where idx={$con_idx}";
      $res_con_img = mysqli_query($self_con, $con_img);
      $contents_row = mysqli_fetch_array($res_con_img);
      $main_img = $contents_row['contents_img'];

      $card_sql = "select idx,mem_id,card_name,card_short_url from Gn_Iam_Name_Card use index(idx) where idx = {$contents_row['card_idx']}";
      $card_res = mysqli_query($self_con, $card_sql);
      $westory_card = mysqli_fetch_assoc($card_res);

      $avatar = "";
      $senderID = $row['pay_method'];
      $senderSql = "select profile,mem_name from Gn_Member use index(mem_id) where mem_id='{$senderID}'";
      $senderRes = mysqli_query($self_con, $senderSql);
      $sender = mysqli_fetch_assoc($senderRes);
      $avatar = $sender['profile'];
      if($avatar == ""){
        $sql_card = "select main_img1 from Gn_Iam_Name_Card where mem_id='{$senderID}' order by idx asc limit 1";
        $res_card = mysqli_query($self_con, $sql_card);
        $row_card = mysqli_fetch_assoc($res_card);
        $avatar = $row_card['main_img1'];
      }
      if($row['alarm_state'] == 0){
        $circle = "<div style=\"width:17px;height:17px;position: absolute;top:40px;left:0px;background: #99cc00;border-radius: 50% !important;\"></div>";
      }else{
        $circle="";
      }
      if($avatar == ""){
        $profile_color = $profile_colors[rand(0, count($profile_colors) - 1)];
        $avatar = "<div style=\"position:relative;margin-top:5px;background:".$profile_color.";padding:14px 0px;width:52px;height:52px;border-radius: 50%;overflow:hidden;text-align:center;\">".
                    "<a class=\"profile_font\" style=\"font-size:14px;color:white;width:100%;height:100%;object-fit: cover;text-decoration-line:none;\">".mb_substr($sender['mem_name'],0,3,"utf-8")."</a>".
                  "</div>";
      }else{
        $avatar = "<div class=\"user-img\" style=\"position:relative;margin-top:5px;width:52px;height:52px;\">".
                    "<img src=\"".$avatar."\" >".
                  "</div>";
      }
      $body .= "<div class=\"content_area\" style=\"margin-left:15px;margin-right:15px;\">".
                "<div class=\"content-item\" style=\"box-shadow:none;border-bottom:none\">".
                  "<div class=\"user-item\" style=\"position:relative;display:flex\">".
                    $avatar.
                    $circle.
                    "<div class=\"wrap\" style=\"width: calc(100% - 160px);margin-left:10px\">".
                      "<a class=\"user-name\" style=\"text-decoration-line:none\">".
                          $sender['mem_name'].
                      "</a>".
                      "<a style=\"display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-decoration-line:none\">".
                          $row['message'].
                      "</a>".
                      "<a style=\"text-decoration-line:none;font-size:10px\">".
                          $row['pay_date'].
                      "</a>".
                    "</div>".
                    "<div style=\"width:52px;height:52px;\">".
                      "<img src=\"".$main_img."\" style=\"width:100%\">".
                    "</div>".
                    "<div class=\"dropdown\" style=\"position: absolute; right: 10px; top: 8px;\">".
                        "<button class=\"btn-link dropdown-toggle westory_dropdown\" type=\"button\" data-toggle=\"dropdown\" >".
                            "<img src=\"/iam/img/menu/icon_dot.png\" style=\"height:24px\">".
                        "</button>".
                        "<ul class=\"dropdown-menu comunity\">".
                            "<li><a onclick=\"receive_contents(".$row['no'].", 'refresh');\">읽음</a></li>".
                            "<li><a onclick=\"window.open('/?".strip_tags($contents_row['westory_card_url'])."');\">게시자 보기</a></li>".
                            "<li><a onclick=\"set_friend('".$westory_card['mem_id']. "','".$westory_card['card_name']."','".$westory_card['card_short_url']."','".$westory_card['idx']."')\">게시자와 프렌즈 하기</a></li>".
                            "<li><a onclick=\"set_my_share_contents('".$contents_row['idx']."')\">이 콘텐츠 가져오기</a></li>".
                            "<li><a onclick=\"set_block_contents('".$contents_row['idx']."')\">이 콘텐츠만 차단</a></li>".
                            "<li><a onclick=\"set_block_user('".$westory_card['mem_id']."','".$westory_card['card_short_url']." ')\">게시자 정보 차단</a></li>".
                            "<li><a onclick=\"set_report_contents('".$contents_row['idx']."')\">이 콘텐츠 신고</a></li>".
                            "<li><a onclick=\"show_block_list('".$contents_row['idx']."')\">감추기 리스트 보기</a></li>".
                            "<li><a onclick=\"del_send_list('".$row['no']."', 'contents')\">삭제</a></li>".
                        "</ul>".
                    "</div>".
                  "</div>".
                "</div>".
              "</div>";
    }
    echo $body;
  }
}elseif($_GET['type'] == 'contentssend'){
  $sql = "select * from Gn_Item_Pay_Result where point_val=2 and list_show=1 ";
  $sql .= " and type='contentssend' and buyer_id='{$_SESSION['iam_member_id']}'";
  if($search_key != "")
    $sql .= " and (message like '%".$search_key."%' or pay_method like '%".$search_key."%')";
  $count_sql = str_replace("*","count(no)",$sql);
  $result = mysqli_query($self_con, $count_sql);
  $row = mysqli_fetch_array($result);
  $cont_count = $row[0];
  $body = "<script>$('#total_count').html('총 ".$cont_count."건');$('#total_count').data('count',".$cont_count.");</script>";
  $sql .= " ORDER BY pay_date DESC ";
  $sql .= " limit $contents_count_per_page , ".$w_offset;
  $cont_array = array();
  if(!$global_is_local){
    $redisCache = new RedisCache();
    $cont_list = $redisCache -> get_query_to_array($sql);
    for($i=0 ; $i < count($cont_list); $i++){
        array_push($cont_array,$cont_list[$i]);
    }
  }else{
      $result = mysqli_query($self_con, $sql);
      while( $contents_row = mysqli_fetch_array($result)){
          array_push($cont_array,$contents_row);
      }
  }
  if(count($cont_array) == 0){
    $body .= "<div class=\"content_area\" style=\"text-align:center\">".
                "<img src=\"/iam/img/menu/no_alarm.png\" ><br>".
                "<a style=\"text-decoration-line:none;font-weight:bold\">해당 알림이 없습니다.</a>".
              "</div>";
    echo $body;
  }else{
    foreach($cont_array as $row){
      $val1 = explode("=", $row['site']);
      $con_idx = $val1[1];
      $con_img = "select * from Gn_Iam_Contents where idx={$con_idx}";
      $res_con_img = mysqli_query($self_con, $con_img);
      $contents_row = mysqli_fetch_array($res_con_img);
      $main_img = $contents_row['contents_img'];

      $card_sql = "select idx,mem_id,card_name,card_short_url from Gn_Iam_Name_Card use index(idx) where idx = {$contents_row['card_idx']}";
      $card_res = mysqli_query($self_con, $card_sql);
      $westory_card = mysqli_fetch_assoc($card_res);

      $avatar = "";
      $receiverID = $row['pay_method'];
      $receiverSql = "select profile,mem_name from Gn_Member use index(mem_id) where mem_id='{$receiverID}'";
      $receiverRes = mysqli_query($self_con, $receiverSql);
      $receiver = mysqli_fetch_assoc($receiverRes);
      $avatar = $receiver['profile'];
      if($avatar == ""){
        $sql_card = "select main_img1 from Gn_Iam_Name_Card where mem_id='{$receiverID}' order by idx asc limit 1";
        $res_card = mysqli_query($self_con, $sql_card);
        $row_card = mysqli_fetch_assoc($res_card);
        $avatar = $row_card['main_img1'];
      }
      
      if($avatar == ""){
        $profile_color = $profile_colors[rand(0, count($profile_colors) - 1)];
        $avatar = "<div style=\"position:relative;margin-top:5px;background:".$profile_color.";padding:14px 0px;width:52px;height:52px;border-radius: 50%;overflow:hidden;text-align:center;\">".
                    "<a class=\"profile_font\" style=\"font-size:14px;color:white;width:100%;height:100%;object-fit: cover;text-decoration-line:none;\">".mb_substr($receiver['mem_name'],0,3,"utf-8")."</a>".
                  "</div>";
      }else{
        $avatar = "<div class=\"user-img\" style=\"position:relative;margin-top:5px;width:52px;height:52px;\">".
                    "<img src=\"".$avatar."\" >".
                  "</div>";
      }
      if($row['message'] != ""){
        $type = "알림형";
      }else{
        $type = "수신함";
      }
      $body .= "<div class=\"content_area\" style=\"margin-left:15px;margin-right:15px;\">".
                "<div class=\"content-item\" style=\"box-shadow:none;border-bottom:none\">".
                  "<div class=\"user-item\" style=\"position:relative;display:flex\">".
                    $avatar.
                    "<div class=\"wrap\" style=\"width: calc(100% - 160px);margin-left:10px\">".
                      "<a class=\"user-name\" style=\"text-decoration-line:none\">".
                          $receiver['mem_name'].
                      "</a>".
                      "<a class=\"user-name\" style=\"text-decoration-line:none\">".
                          $type." [".$row["item_price"].'/'.$row["current_point"]."]".
                      "</a>".
                      "<a style=\"display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-decoration-line:none\">".
                          $row['message'].
                      "</a>".
                      "<a style=\"text-decoration-line:none;font-size:10px\">".
                          $row['pay_date'].
                      "</a>".
                    "</div>".
                    "<div style=\"width:52px;height:52px;\">".
                      "<img src=\"".$main_img."\" style=\"width:100%\">".
                    "</div>".
                    "<div class=\"dropdown\" style=\"position: absolute; right: 10px; top: 8px;\">".
                        "<button class=\"btn-link dropdown-toggle westory_dropdown\" type=\"button\" data-toggle=\"dropdown\" >".
                            "<img src=\"/iam/img/menu/icon_dot.png\" style=\"height:24px\">".
                        "</button>".
                        "<ul class=\"dropdown-menu comunity\">".
                            "<li><a onclick=\"del_send_list('".$row['no']."', 'contents')\">삭제</a></li>".
                        "</ul>".
                    "</div>";
      $body .=    "</div>".
                "</div>".
              "</div>";
    }
    echo $body;
  }
}
