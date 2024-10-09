#!/opt/php/bin/php
<?php
include_once "../lib/class.image.php";
set_time_limit(0);
ini_set('memory_limit','-1');

 $handle = new Image("/home/kiam/www/upload_month/upload_2024_04/020420241619480.JPG", 800);
 $handle->resize();
 echo $handle->log;
echo 'Done';
?>