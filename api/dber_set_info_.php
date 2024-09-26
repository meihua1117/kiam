<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
유저정보 설정
입력파라미터
 token : 토큰값
 type: 1-폰, 2-메일, 3-쇼핑
출력파라미터
 result: 0-남은 갯수 , 1-토큰이 정확하지 않음
*/
$token = trim($_POST["token"]);
$type = trim($_POST["type"]);
$collect_count = trim($_POST['collect_count']);

if($type == 1){
    $sql = "update crawler_member_real set monthly_cnt=monthly_cnt+$collect_count WHERE token='$token'";
    $result = mysql_query($sql);

    $sql = "SELECT user_id, use_cnt, monthly_cnt FROM crawler_member_real WHERE token='$token'";
    $result = mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }    
    
    $count = $row['use_cnt'] - $row['monthly_cnt'];
    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count));

}else if( $type == 2){
    $sql = "update crawler_member_real set search_email_use_cnt=search_email_use_cnt+$collect_count,search_email_total_cnt=search_email_total_cnt+$collect_count WHERE token='$token'";
    $result = mysql_query($sql);

    $sql = "SELECT user_id, search_email_cnt, search_email_use_cnt FROM crawler_member_real WHERE token='$token'";
    $result = mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }    
    
    $count = $row['search_email_cnt'] - $row['search_email_use_cnt'];
    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count));
}
else if( $type == 3){
    $sql = "update crawler_member_real set shopping_use_cnt=shopping_use_cnt+$collect_count WHERE token='$token'";
    $result = mysql_query($sql);

    $sql = "SELECT user_id, shopping_use_cnt,shopping_cnt FROM crawler_member_real WHERE token='$token'";
    $result = mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }    
    
    $count = $row['shopping_cnt'] - $row['shopping_use_cnt'];
    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count));
}



?>