#!/opt/php/bin/php
<?php

set_time_limit(0);
ini_set('memory_limit','-1');

$mysql_host = 'localhost';
$mysql_user = 'kiam';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';

$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysql_error());
mysql_select_db($mysql_db) or die(mysql_error());
mysql_query("set names utf8");

echo "Started working with duplication\n";

$query = "start transaction";
$result = mysql_query($query) ;

$totalPhoneNum = 0;
while(true)
{
    $idxList = array();
    $query = "select seq from crawler_data_supply where cell != '' GROUP BY cell HAVING count(seq) > 1";
    $result = mysql_query($query) ;
    while($row=mysql_fetch_array($result)) {
        array_push($idxList, $row['seq']);
    }

    if(count($idxList) == 0)
        break;
    
    $totalPhoneNum += count($idxList);
    echo "=====>Found " . count($idxList) . " duplicated phone numbers\n";
    $idx_group = "'" . implode("','", $idxList) . "'";
    $sub_sql = "delete from crawler_data_supply where seq in ($idx_group)";
    mysql_query($sub_sql) ;

}

echo "Removed $totalPhoneNum phone number duplication\n";

$totalPhoneNum = 0;
while(true)
{
    $idxList = array();
    $query = "select seq from crawler_data_supply where cell ='' and email !='' GROUP BY email HAVING count(seq) > 1";
    $result = mysql_query($query);
    while($row=mysql_fetch_array($result)) {
        array_push($idxList, $row['seq']);
    }

    if(count($idxList) == 0)
        break;   

    $totalPhoneNum += count($idxList);
    echo "=====>Found " . count($idxList) . " duplicated emails\n";
    $idx_group = "'" . implode("','", $idxList) . "'";
    $sub_sql = "delete from crawler_data_supply where seq in ($idx_group)";
    mysql_query($sub_sql) ;
    
    
}

echo "Removed $totalPhoneNum email duplication\n";


$query = "commit";
$result = mysql_query($query) ;

echo "Well Done!!!\n";

mysql_close($self_con);
?>
