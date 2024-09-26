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

$sql = "SELECT date_format(now(), '%Y%m%d')";
$result = mysqli_query($self_con, $sql);
$row=mysqli_fetch_array($result);
$cur_time = strtotime($row[0]);

if($type == 1){
    $sql = "SELECT user_id, status, use_cnt, monthly_cnt, term, extra_db_cnt FROM crawler_member_real WHERE token='$token'";
    $result = mysqli_query($self_con, $sql);
    $row=mysqli_fetch_array($result);
    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }

    $remain_regular_count = 0;
    $remain_extra_count = 0;
    if( $row['status'] == "Y" && strtotime($row['term']) >= $cur_time)
    {
        $remain_count = $row['use_cnt'] - $row['monthly_cnt'];
        if($collect_count < $remain_count)
        {
            $sql = "update crawler_member_real set monthly_cnt=monthly_cnt+$collect_count WHERE token='$token'";
            $result = mysqli_query($self_con, $sql);
            $remain_regular_count = $remain_count - $collect_count;
        }
        else
        {
            $sql = "update crawler_member_real set monthly_cnt=use_cnt WHERE token='$token'";
            $result = mysqli_query($self_con, $sql);
            $remain_extra_count = $collect_count - $remain_count;       
        }
    }
    else
    {
        $remain_extra_count = $collect_count;
    }

    if($remain_extra_count > 0)
    {
        $sql = "update crawler_member_real set extra_db_cnt=extra_db_cnt-$remain_extra_count WHERE token='$token'";
        $result = mysqli_query($self_con, $sql);
        $remain_extra_count =  $row['extra_db_cnt'] - $remain_extra_count;
    } 

    $count = $remain_regular_count + $remain_extra_count;
    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count));

}else if( $type == 2){

    $sql = "SELECT user_id, search_email_cnt, search_email_use_cnt, search_email_date, search_email_yn, extra_email_cnt FROM crawler_member_real WHERE token='$token'";
    $result = mysqli_query($self_con, $sql);
    $row=mysqli_fetch_array($result);
    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }

    $remain_regular_count = 0;
    $remain_extra_count = 0;
    if( $row['search_email_yn'] == "Y" && strtotime($row['search_email_date']) >= $cur_time)
    {
        $remain_count = $row['search_email_cnt'] - $row['search_email_use_cnt'];
        if($collect_count < $remain_count)
        {
            $sql = "update crawler_member_real set search_email_use_cnt=search_email_use_cnt+$collect_count,search_email_total_cnt=search_email_total_cnt+$collect_count WHERE token='$token'";
            $result = mysqli_query($self_con, $sql);
            $remain_regular_count = $remain_count - $collect_count;
        }
        else
        {
            $sql = "update crawler_member_real set search_email_use_cnt=search_email_cnt WHERE token='$token'";
            $result = mysqli_query($self_con, $sql);
            $remain_extra_count = $collect_count - $remain_count;       
        }
    }
    else
        $remain_extra_count = $collect_count;

    if($remain_extra_count > 0)
    {
        $sql = "update crawler_member_real set extra_email_cnt=extra_email_cnt-$remain_extra_count WHERE token='$token'";
        $result = mysqli_query($self_con, $sql);
        $remain_extra_count =  $row['extra_email_cnt'] - $remain_extra_count;
    } 

    $count = $remain_regular_count + $remain_extra_count;
    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count));   
}
else if( $type == 3){
    $sql = "SELECT ser_id,shopping_yn, shopping_use_cnt, shopping_cnt, shopping_end_date, extra_shopping_cnt FROM crawler_member_real WHERE token='$token'";
    $result = mysqli_query($self_con, $sql);
    $row=mysqli_fetch_array($result);
    if($row['user_id'] == "") {
        echo json_encode(array('result' => 1));
        exit;
    }

    $remain_regular_count = 0;
    $remain_extra_count = 0;
    if( $row['shopping_yn'] == "Y" && strtotime($row['shopping_end_date']) >= $cur_time)
    {
        $remain_count = $row['shopping_cnt'] - $row['shopping_use_cnt'];
        if($collect_count < $remain_count)
        {
            $sql = "update crawler_member_real set shopping_use_cnt=shopping_use_cnt+$collect_count WHERE token='$token'";
            $result = mysqli_query($self_con, $sql);
            $remain_regular_count = $remain_count - $collect_count;
        }
        else
        {
            $sql = "update crawler_member_real set shopping_use_cnt=shopping_cnt WHERE token='$token'";
            $result = mysqli_query($self_con, $sql);
            $remain_extra_count = $collect_count - $remain_count;     
        }
    }
    else
        $remain_extra_count = $collect_count;

    if($remain_extra_count > 0)
    {
        $sql = "update crawler_member_real set extra_shopping_cnt=extra_shopping_cnt-$remain_extra_count WHERE token='$token'";
        $result = mysqli_query($self_con, $sql);
        $remain_extra_count =  $row['extra_shopping_cnt'] - $remain_extra_count;
    } 

    $count = $remain_regular_count + $remain_extra_count;
    if($count < 0)
        $count = 0;
    echo json_encode(array('result' => 0, 'count' => $count));  

}



?>