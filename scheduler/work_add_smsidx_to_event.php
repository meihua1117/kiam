#!/opt/php/bin/php
<?php
set_time_limit(0);
ini_set('memory_limit','-1');

$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';

$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysql_error());
mysql_select_db($mysql_db) or die(mysql_error());
mysql_query("set names utf8");


echo "Start working with daily update\n";


$query = "select event_idx,sms_idx from Gn_event_sms_info GROUP BY event_idx";
$result = mysql_query($query) ;
while($row=mysql_fetch_array($result)) {

    $eventidx = $row['event_idx'];	
    $smsidx = $row['sms_idx'];	
    
    $query = "update Gn_event set sms_idx1=$smsidx where event_idx=$eventidx";
    mysql_query($query);


}


echo "Well Done !!!\n";
mysql_close($self_con);
?>
