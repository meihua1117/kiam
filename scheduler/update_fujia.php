#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";

$time = time();
$sql_all = "update Gn_Member set fujia_date2=fujia_date1 where unix_timestamp(fujia_date2) < $time and unix_timestamp(fujia_date2) <> '0'";
$resul_all = mysqli_query($self_con, $sql_all);

mysqli_close($self_con);
?>
