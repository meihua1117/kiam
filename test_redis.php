<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";


$redisCache = new RedisCache();
$redisCache -> set_debug(true);

$sql = "SELECT * FROM Gn_Ad";
$list = $redisCache -> get_query_to_array($sql, 10);
echo "list count=". count($list) . "<br>";

$card_sql ="select count(idx) as cnt from Gn_Iam_Name_Card where req_data >'2022-01-20'";
$list = $redisCache->get_query_to_data($card_sql, 30);
//print_r($redisCache->get_debug_info());
echo $redisCache->get_debug_string(true);
//print_r($list);