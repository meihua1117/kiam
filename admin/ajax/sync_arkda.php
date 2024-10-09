<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

extract($_POST);

if(!$sync){
    exit;
}

$cur_time = date("Ymd");
$sql_state = "select res_crawler from crawler_shop_update where type=2 and cate_name='arkda' and sync_date='{$cur_time}' order by id desc limit 1";
$res_state = mysql_query($sql_state);
$row_state = mysql_fetch_array($res_state);

echo '{"result":"'.$row_state[0].'"}';
?>