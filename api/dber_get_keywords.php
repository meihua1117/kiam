<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
검색키워드 리턴
입력파라미터
 token : 유저토큰
출력파라미터
 result: 0-검색키워드,1-토큰 정확하지 않음
*/
$token = trim($_POST["token"]);


$sql = "SELECT user_id FROM crawler_member_real WHERE token = '$token'";
$result = mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($result);
if($row['user_id'] == "") {
    echo json_encode(array('result' => 1));
    exit;
}

$sql = "SELECT shop_keyword FROM crawler_conf";
$result = mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($result);

echo json_encode(array('result' => 0, 'keywords' => $row[0]));
?>