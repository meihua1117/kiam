<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$query = "select count(*) from Gn_Iam_automem where status=1";
$res = mysqli_query($self_con, $query);
$res = mysqli_fetch_array($res);
$count = $res[0];
echo '{"status":'.$count.'}';
//exit;
?>
