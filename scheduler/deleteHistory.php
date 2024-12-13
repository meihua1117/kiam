#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";

$lastweek  = date("YmdHis", mktime(0, 0, 0, date("m"), date("d") - 30, date("Y")));
$sql_all = "delete from gn_hist_login where regdate < '{$lastweek}'";
$resul_all = mysqli_query($self_con, $sql_all);

$days  = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - 5, date("Y")));
$sql_all = "delete from gn_hist_access where regdate < '{$days}'";
$resul_all = mysqli_query($self_con, $sql_all);

mysqli_close($self_con);
?>