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


echo "Start working with daily update\n";

$query = "start transaction";
$result = mysql_query($query) ;

$sub_sql = "select end_idx from crawler_conf";
$result1 = mysql_query($sub_sql);
$row1=mysql_fetch_array($result1);
$startIdx = $row1['end_idx'];

echo "startIdx=". $startIdx . "\n";

$length =   1000000;
$endIdx = 0; 
$query = "select seq, user_id, keyword, incword, exword, data_type, cell, email, ceo, url, company_name, company_type, page_title, address, upload_date, tag, info, regdate from crawler_data_2022 where seq > $startIdx order by seq limit $length";
$result = mysql_query($query) ;
while($row=mysql_fetch_array($result)) {

    $title = str_replace("'","",$row['page_title']);	
    $info = str_replace("'","",$row['info']);
    $upload_date=($row['upload_date'] != '')?$row['upload_date'] : "0000-00-00";
    $keyword = str_replace("\n","",$row['keyword']);
    $keyword = trim($keyword);

    $query = "insert into crawler_data_supply set user_id='$row[user_id]',
    keyword='$keyword',
    incword='$row[incword]',
    exword='$row[exword]',
    data_type='$row[data_type]',
    cell='$row[cell]',
    email='$row[email]',
    ceo='$row[ceo]',
    url='$row[url]',
    company_name='$row[company_name]',
    company_type='$row[company_type]',
    page_title='$title',
    address='$row[address]',
    upload_date='$upload_date',
    tag='$row[tag]',
    info='$info',
    regdate='$row[regdate]'";
    mysql_query($query);

    //echo $query . "\n";
    $endIdx = $row['seq'];
}

echo "endIdx=". $endIdx. "\n";

if($endIdx > 0)
{
    $query = "update crawler_conf set end_idx='$endIdx'";
    mysql_query($query);
}

$query = "commit";
$result = mysql_query($query);

echo "Well Done !!!\n";
mysql_close($self_con);
?>
