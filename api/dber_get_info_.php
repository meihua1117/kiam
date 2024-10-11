<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
유저정보 얻기
입력파라미터
 token : 토큰값
 type: 1-폰, 2-메일, 3-쇼핑
출력파라미터
 result: 0-유저정보 , 1-토큰이 정확하지 않음, 2-사용불가, 3-기간만료
*/
$token = trim($_POST["token"]);
$type = trim($_POST["type"]);

$sql = "SELECT date_format(now(), '%Y%m%d')";
$result = mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($result);
$cur_time = strtotime($row[0]);

if($type == 1){
    $sql = "SELECT user_id, status, use_cnt, monthly_cnt, term FROM crawler_member_real WHERE token='$token'";
    $result = mysqli_query($self_con,$sql);
    $row=mysqli_fetch_array($result);
    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }
    
    if( $row['status'] != "Y"){
        echo json_encode(array('result' => 2));
        exit;
    }
    $term = strtotime($row['term']);
    if($term < $cur_time)
    {
        echo json_encode(array('result' => 3));
        exit;      
    }
    $count = $row['use_cnt'] - $row['monthly_cnt'];
    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count, 'expire_date' => $row['term']));

}else if( $type == 2){
    $sql = "SELECT user_id, search_email_cnt, search_email_use_cnt, search_email_date, search_email_yn FROM crawler_member_real WHERE token='$token'";
    $result = mysqli_query($self_con,$sql);
    $row=mysqli_fetch_array($result);
    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }
    
    if( $row['search_email_yn'] != "Y"){
        echo json_encode(array('result' => 2));
        exit;
    }
    $term = strtotime($row['search_email_date']);
    if($term < $cur_time)
    {
        echo json_encode(array('result' => 3));
        exit;      
    }
    $count = $row['search_email_cnt'] - $row['search_email_use_cnt'];
    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count, 'expire_date' => $row['search_email_date']));
}
else if( $type == 3){
    $sql = "SELECT user_id,shopping_yn, shopping_use_cnt, shopping_cnt, shopping_end_date FROM crawler_member_real WHERE token='$token'";
    $result = mysqli_query($self_con,$sql);
    $row=mysqli_fetch_array($result);
    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }
    
    if( $row['shopping_yn'] != "Y"){
        echo json_encode(array('result' => 2));
        exit;
    }
    $term = strtotime($row['shopping_end_date']);
    if($term < $cur_time)
    {
        echo json_encode(array('result' => 3));
        exit;      
    }
    $count = $row['shopping_cnt'] - $row['shopping_use_cnt'];
    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count, 'limit_count' => $row['shopping_cnt'],'expire_date' => $row['shopping_end_date']));
}



?>