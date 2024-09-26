<?
header("Content-type:text/html;charset=utf-8");
include_once "lib/db_config.php";
/*$query = "select site_iam,mem_id from Gn_Member order by mem_code";
$res = mysql_query($query);
while($row = mysql_fetch_array($res)){
    $site = $row['site_iam'];
    $mem_id = $row[mem_id];
    
    if($site == "kiam"){
        $sub_domain = "www.kiam.kr";
    }else {
        $sub_domain = $site.".kiam.kr";   
    }
    $query = "select iamcard_cnt,my_card_cnt,send_content,mem_id from Gn_Iam_Service where sub_domain like '%$sub_domain%'";
    $res1 = mysql_query($query);
    $data = mysql_fetch_array($res1);
    if($mem_id == $data[mem_id])
        $card_cnt = $data[my_card_cnt];
    else
        $card_cnt = $data[iamcard_cnt];
    $share_cnt = $data[send_content];
    $query = "update Gn_Member set iam_card_cnt='$card_cnt' ,iam_share_cnt='$share_cnt' where mem_id = '$mem_id'";
    mysql_query($query);
    echo "update ".$mem_id." complete!"."<br>";
}*/
/*$query = "select card_idx from Gn_Iam_Contents group by card_idx";
$res = mysql_query($query);
while($row = mysql_fetch_array($res)){
    $card_idx = $row[0];
    $sql = "select count(*) from Gn_Iam_Name_Card where idx ='$card_idx'";
    $r = mysql_query($sql);
    $data = mysql_fetch_array($r);
    if($data[0] == 0){
        $sql = "delete from Gn_Iam_Contents where card_idx ='$card_idx'";
        mysql_query($sql);
        echo $card_idx." remove!\r\n";
    }
}*/
/*$query = "select card_idx from Gn_Iam_Contents group by card_idx";
$result = mysql_query($query);
while($row = mysql_fetch_array($result)){
    $card_idx = $row[0];
    echo $card_idx."<br>";
    $order = 1;
    $sql = "select idx from Gn_Iam_Contents where card_idx ='$card_idx' order by req_data";
    $res = mysql_query($sql);
    while($data = mysql_fetch_array($res)){
        $q = "update Gn_Iam_Contents set contents_order ='$order' where idx = '$data[0]'";
        mysql_query($q);
        echo "&nbsp; ".$data[0]."=>".$order."<br>";
        $order++;
    }
}*/
/*$query = "select idx,contents_share_text from Gn_Iam_Contents where contents_share_text != '' and contents_share_count = 0";
$result = mysql_query($query);
while($row = mysql_fetch_array($result)){
    $card_idx = $row[0];
    echo $card_idx."\r\n";
    $share_ids = explode(",",$row[contents_share_text]);
    $share_count = count($share_ids);
    $q = "update Gn_Iam_Contents set contents_share_count ='$share_count' where idx = '$row[0]'";
    mysql_query($q);
}*/
/*$mem_id = $_GET["mem"];
$month = $_GET["month"];
$query = "select * from tjd_pay_result_month where buyer_id='$mem_id' and pay_yn = 'Y' order by idx desc";
$res = mysql_query($query);
$row = mysql_fetch_array($res);
$order_no = $row[pay_idx].date("Y").sprintf("%02d",$month);
$pay_idx = $row[pay_idx];
$amount = $row[amount];
$reg_date = $row['regdate'];
$pos = strpos($reg_date,"-");
$reg_date = substr($reg_date,0,$pos+1).sprintf("%02d",$month).substr($reg_date,$pos+3);
$reg_date = date("Y-m-d H:i:s",strtotime($reg_date));
$msg = $month."월 미결제";
$query = "insert into tjd_pay_result_month set pay_idx = '$pay_idx',order_number = '$order_no',pay_yn='N',msg = '$msg',regdate = '$reg_date',amount = '$amount',buyer_id = '$mem_id'";
mysql_query($query) or die(mysql_error());
echo "kiam complete:".mysql_insert_id();*/
/*$idx = $_GET["idx"];
$sql = "select * from gn_report_table$idx order by idx ";
$repo_res = mysql_query($sql);

$item_arr = array();
$sql1 = "select * from gn_report_form1 where form_id=$idx and item_type <> 2 order by item_order";
$res1 = mysql_query($sql1);
while($row1 = mysql_fetch_array($res1)){
    $sql2 = "select * from gn_report_form2 where form_id=$idx and item_id = $row1['id'] order by id";
    $res2 = mysql_query($sql2);
    while($row2 = mysql_fetch_array($res2)){
        $row2['item_type'] = $row1['item_type'];
        array_push($item_arr,$row2);
    }
}
while($repo_row = mysql_fetch_array($repo_res)){
    //$result = $repo_row['userid'].'|'.$repo_row['name'].'|'.$repo_row['phone'].'|'.$repo_row['reg_date'];
    $sql = "insert into gn_report_table set userid='$repo_row[userid]',name='$repo_row['name']',phone='$repo_row[phone]',sign='$repo_row[sign]',reg_date='$repo_row['reg_date']',repo_id=$idx,cont=";
    $result = array();
    foreach($item_arr as $item){
        $arr = array($item['tag_id']=>$repo_row[$item['tag_id']]);
        array_push($result,$arr);
    }
    $sql .= "'".json_encode($result)."'";
    mysql_query($sql);
}*/
/*$card_sql = "select * from Gn_Iam_Name_Card order by idx";
$card_res = mysql_query($card_sql);
while($card_row = mysql_fetch_array($card_res)){
    $cont_sql = "select card_short_url,westory_card_url from Gn_Iam_Contents where card_idx=$card_row[idx]";
    $cont_res = mysql_query($cont_res);
    while($cont_row = mysql_fetch_array($cont_res)){

    }
}*/
/*$card_sql = "select * from Gn_Iam_Con_Card order by idx";
$card_res = mysqli_query($self_con, $card_sql);
while($card_row = mysqli_fetch_array($card_res)){
    $cont_sql = "select count(idx) from Gn_Iam_Contents where idx={$card_row['cont_idx']}";
    $cont_res = mysqli_query($self_con,  $cont_sql);
    $cont_row = mysqli_fetch_array($cont_res);
    if($cont_row[0] == 0){
        
        echo "content ".$card_row['cont_idx']." deleting.<br>";
        $sql = "delete from Gn_Iam_Con_Card where idx = $card_row[idx]";
        mysqli_query($self_con,  $sql) or die(mysqli_error($self_con));
    }
}*/
/*$f_sql = "select * from Gn_Iam_Friends order by idx";
$f_res = mysqli_query($self_con, $f_sql);
while($friend_row = mysqli_fetch_array($f_res)){
    $card_sql = "select mem_id from Gn_Iam_Name_Card where idx={$friend_row['friends_card_idx']}";
    $card_res = mysqli_query($self_con,  $card_sql);
    $card_row = mysqli_fetch_array($card_res);
    if($card_row['mem_id'] == ""){
        echo $friend_row['idx']." deleting.<br>";
        $sql = "delete from Gn_Iam_Friends where idx = $friend_row[idx]";
        mysqli_query($self_con,  $sql) or die(mysqli_error($self_con));
    }else{
        echo $friend_row['idx']." changed.<br>";
        $sql = "update Gn_Iam_Friends set friend_id='{$card_row['mem_id']}' where idx = $friend_row[idx]";
        mysqli_query($self_con,  $sql) or die(mysqli_error($self_con));
    }
}*/
$sql = "select idx,mem_id from Gn_Iam_Service where auto_join_event_idx=0";
$res = mysql_query($sql);
while($row = mysql_fetch_assoc($res)){
    $sql1 = "select event_idx from Gn_event where event_name_kor='단체회원자동가입및아이엠카드생성' and m_id='{$row['mem_id']}' order by regdate desc";
    $res1 = mysql_query($sql1);
    $row1 = mysql_fetch_assoc($res1);
    if($row1['event_idx']){
        mysql_query("update Gn_Iam_Service set auto_join_event_idx = '{$row1['event_idx']}' where idx={$row['idx']}");
        echo "update ".$row['mem_id']."=>".$row1['event_idx']."<br>";
    }
}
echo "completed!!!";
//echo "result=";//.password_verify('2019-10-19 15:16:59'.'123456','$2y$12$'.'AyEnd5XdXhvQwFgt8NRNNOd6EI6s.vqU1ClLEOVDN0/di4KihS026');
//echo "completed!";
?>
