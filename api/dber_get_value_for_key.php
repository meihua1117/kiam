<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
키에 대응하는 값 리턴
입력파라미터
 key : 키값
출력파라미터
 result: 0-키에 해당한 값
*/

$key = trim($_POST["key"]);


$sql = "SELECT key_content FROM Gn_Search_Key WHERE key_id = '$key'";
$result = mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($result);

echo json_encode(array('result' => 0, 'value' => $row[0]));
?>