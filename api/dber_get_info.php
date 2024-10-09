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
$result = mysql_query($sql);
$row=mysql_fetch_array($result);
$cur_time = strtotime($row[0]);

if($type == 1){

    $sql = "SELECT user_id, status, use_cnt, monthly_cnt, term, extra_db_cnt FROM crawler_member_real WHERE token='$token'";
    $result = mysql_query($sql);
    $row=mysql_fetch_array($result);

    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }

    //추가구매건수가 0이라면 승인상태를 판단한다.
    if($row['extra_db_cnt'] <= 0)
    {
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
    }
    
    if($row['status'] == "Y" && strtotime($row['term']) >= $cur_time)
        $count = $row['use_cnt'] - $row['monthly_cnt'] + $row['extra_db_cnt'];
    else
        $count = $row['extra_db_cnt'];

    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count, 'expire_date' => $row['term']));

}else if( $type == 2){
    $sql = "SELECT user_id, search_email_cnt, search_email_use_cnt, search_email_date, search_email_yn, extra_email_cnt FROM crawler_member_real WHERE token='$token'";
    $result = mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }

    if($row['extra_email_cnt'] <= 0)
    {
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
    }
    
    if($row['search_email_yn'] == "Y" && strtotime($row['search_email_date']) >= $cur_time)
        $count = $row['search_email_cnt'] - $row['search_email_use_cnt'] + $row['extra_email_cnt'];
    else
        $count = $row['extra_email_cnt'];

    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count, 'expire_date' => $row['search_email_date']));
}
else if( $type == 3){
    $sql = "SELECT user_id,shopping_yn, shopping_use_cnt, shopping_cnt, shopping_end_date, extra_shopping_cnt FROM crawler_member_real WHERE token='$token'";
    $result = mysql_query($sql);
    $row=mysql_fetch_array($result);
    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }

    if($row['extra_shopping_cnt'] <= 0)
    {
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
    }    

    if($row['shopping_yn'] == "Y" && strtotime($row['shopping_end_date']) >= $cur_time)
        $count = $row['shopping_cnt'] - $row['shopping_use_cnt'] + $row['extra_shopping_cnt'];
    else
        $count = $row['extra_shopping_cnt'];

    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count, 'limit_count' => $row['shopping_cnt'],'expire_date' => $row['shopping_end_date']));
}



?>