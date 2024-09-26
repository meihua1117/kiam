<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
# 설정파일을 php자체 에서도 바꿀 수 있음(이 파일 위해서만), 1은 on을 의미, display_errors는 디버그

$str = $_POST['str'];
$mem_id = $_POST['mem_id'];
$card_idx = $_POST['card_idx'];

$sql="update Gn_Iam_Name_Card set $str = $str + 1 where idx = $card_idx and mem_id = '$mem_id'";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
exit;
?>
