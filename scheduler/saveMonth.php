#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";

$date = date("Ym", time()-1000);
$sql_all = "insert into Gn_MMS_Number_monthly select '{$date}', mem_id, sendnum, max_cnt, user_cnt, gl_cnt, cnt1, cnt2 from Gn_MMS_Number";
$resul_all = mysqli_query($self_con, $sql_all);

mysqli_close($self_con);
?>
