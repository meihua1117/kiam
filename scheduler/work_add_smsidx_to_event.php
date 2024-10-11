#!/opt/php/bin/php
<?php
set_time_limit(0);
ini_set('memory_limit','-1');

$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';

$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysqli_error($self_con));
mysql_select_db($mysql_db) or die(mysqli_error($self_con));
mysqli_query($self_con,"set names utf8");


echo "Start working with daily update\n";


$query = "select event_idx,sms_idx from Gn_event_sms_info GROUP BY event_idx";
$result = mysqli_query($self_con,$query) ;
while($row=mysqli_fetch_array($result)) {

    $eventidx = $row['event_idx'];	
    $smsidx = $row['sms_idx'];	
    
    $query = "update Gn_event set sms_idx1=$smsidx where event_idx=$eventidx";
    mysqli_query($self_con,$query);


}


echo "Well Done !!!\n";
mysqli_close($self_con);
?>
