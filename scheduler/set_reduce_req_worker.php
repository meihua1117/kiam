#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";
/*$mysql_host = 'localhost';
$mysql_user = 'kiam';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';
$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysql_error());
mysql_select_db($mysql_db) or die(mysql_error());
mysql_query("set names utf8");
*/
echo date("Y-m-d H:i:s") . ">>";

$sql_card_set = "select idx from Gn_Iam_Name_Card where sale_cnt_set != 0";
$res_card_set = mysqli_query($self_con, $sql_card_set);
while ($row_card_set = mysqli_fetch_array($res_card_set)) {
    echo "reset_card_idx::" . $row_card_set['idx'] . "\n";
    $sql_update = "update Gn_Iam_Name_Card set sale_cnt=sale_cnt_set where idx='{$row_card_set['idx']}'";
    mysqli_query($self_con, $sql_update);
}

$cur_time_com = time() - (86400 * 15);
$cur_com_date = date("Y-m-d H:i:s", $cur_time_com);

$sql_worker = "select idx, req_worker_date, org_use_state, req_worker_id from Gn_Iam_Name_Card where worker_service_state=1 and req_worker_id!=''";
$res_worker = mysqli_query($self_con, $sql_worker);
while ($row_worker = mysqli_fetch_array($res_worker)) {
    if ($cur_com_date > $row_worker['req_worker_date'] && !$row_worker['org_use_state']) {
        echo "reset_worker_state::" . $row_worker['req_worker_id'] . "\n";
        $sql_update1 = "update Gn_Iam_Name_Card set req_worker_id='' where idx='{$row_worker['idx']}'";
        mysqli_query($self_con, $sql_update1);
    }
}

mysqli_close($self_con);
?>