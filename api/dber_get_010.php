<?php
//include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
@ini_set('display_errors', false);
$mysql_host = '175.126.176.97';
$mysql_user = 'remo';
$mysql_password = 'Onlyone123!@#';
$mysql_db = 'kiam';
$self_con = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);
if (!$self_con) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
//mysql_select_db($mysql_db) or die(mysqli_error($self_con));
mysqli_query($self_con, "set names utf8");
/*
광고 관리
*/
$region  = trim($_POST["region"]);
$keyword = trim($_POST["keyword"]);
$limit = trim($_POST["limit"]);
$addQuery = "";
if ($region != "")
    $addQuery = "and region like '%$region%'";

$sql = "select * from crawler_data_supply where keyword like '%$keyword%' and data_type='지도' $addQuery order by seq desc limit $limit";
$result = mysqli_query($self_con, $sql);
$num_rows = mysqli_num_rows($result);
$info["count"] = $num_rows;
$k = 0;
while ($row = mysqli_fetch_array($result)) {
    $info['data'][$k]['keyword'] = $row['keyword'];
    $info['data'][$k]['data_type'] = $row['data_type'];
    $info['data'][$k]['cell'] = $row['cell'];
    $info['data'][$k]['url'] = $row['url'];
    $info['data'][$k]['company_name'] = $row['company_name'];
    $info['data'][$k]['company_type'] = $row['company_type'];
    $info['data'][$k]['address'] = $row['address'];
    $k++;
}
echo json_encode($info);
?>