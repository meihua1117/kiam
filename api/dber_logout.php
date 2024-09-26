<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
로그아웃
입력파라미터
 token : 토큰값
출력파라미터
 result: 0-유저정보 , 1-토큰이 정확하지 않음
*/
$token = trim($_POST["token"]);

$sql = "SELECT user_id FROM crawler_member_real WHERE token='$token'";
$result = mysqli_query($self_con, $sql);
$row=mysqli_fetch_array($result);
if($row['user_id'] == "") {
    echo json_encode(array('result' => 1));
    exit;
}
    
$sql = "update crawler_member_real set token='' WHERE token='$token'";
$result = mysqli_query($self_con, $sql); 
echo json_encode(array('result' => 0));

?>