<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
키에 대응하는 값 리턴
입력파라미터
 token: 토큰
 key : 키값
출력파라미터
 result: 0-키에 해당한 값
*/

$token = trim($_POST["token"]);


$sql = "SELECT user_id FROM crawler_member_real WHERE token = '$token'";
$result = mysqli_query($self_con, $sql);
$row=mysqli_fetch_array($result);
if($row['user_id'] == "") {
    echo json_encode(array('result' => 1));
    exit;
}

$sql = "SELECT * FROM gn_cities";
$result = mysqli_query($self_con, $sql);
$regions = array();
while($row=mysqli_fetch_array($result))
{
    $regions[] = $row;
}
echo json_encode(array('result' => 0, 'regions' => $regions));
?>