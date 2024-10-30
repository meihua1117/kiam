#!/opt/php/bin/php
<?php

set_time_limit(0);
ini_set('memory_limit', '-1');

$mysql_host = 'localhost';
$mysql_user = 'kiam';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';

$self_con = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);
if (!$self_con) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
mysqli_query($self_con, "set names utf8");
echo "Started working with duplication\n";

$query = "start transaction";
$result = mysqli_query($self_con, $query);

$totalPhoneNum = 0;
while (true) {
    $idxList = array();
    //$query = "select seq from crawler_data_supply where cell != '' GROUP BY cell HAVING count(seq) > 1";
    $query = "SELECT MIN(seq) as seq FROM crawler_data_supply WHERE cell != '' GROUP BY cell HAVING COUNT(seq) > 1";
    $result = mysqli_query($self_con, $query);
    while ($row = mysqli_fetch_array($result)) {
        array_push($idxList, $row['seq']);
    }

    if (count($idxList) == 0)
        break;

    $totalPhoneNum += count($idxList);
    echo "=====>Found " . count($idxList) . " duplicated phone numbers\n";
    $idx_group = "'" . implode("','", $idxList) . "'";
    $sub_sql = "delete from crawler_data_supply where seq in ($idx_group)";
    mysqli_query($self_con, $sub_sql);
}

echo "Removed $totalPhoneNum phone number duplication\n";

$totalPhoneNum = 0;
while (true) {
    $idxList = array();
    //$query = "select seq from crawler_data_supply where cell ='' and email !='' GROUP BY email HAVING count(seq) > 1";
    $query = "SELECT MIN(seq) AS seq FROM crawler_data_supply WHERE cell = '' AND email != '' GROUP BY email HAVING COUNT(seq) > 1";
    $result = mysqli_query($self_con, $query);
    while ($row = mysqli_fetch_array($result)) {
        array_push($idxList, $row['seq']);
    }

    if (count($idxList) == 0)
        break;

    $totalPhoneNum += count($idxList);
    echo "=====>Found " . count($idxList) . " duplicated emails\n";
    $idx_group = "'" . implode("','", $idxList) . "'";
    $sub_sql = "delete from crawler_data_supply where seq in ($idx_group)";
    mysqli_query($self_con, $sub_sql);
}

echo "Removed $totalPhoneNum email duplication\n";

$query = "commit";
$result = mysqli_query($self_con, $query);
echo "Well Done!!!\n";

mysqli_close($self_con);
?>