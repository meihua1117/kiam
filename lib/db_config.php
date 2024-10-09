<?
@ini_set('display_errors', false);
$mysql_host = 'localhost';
$mysql_user = 'kiam';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';
$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysql_error());
mysql_select_db($mysql_db) or die(mysql_error());
mysql_query("set names utf8");
$post_Data = '';
$key = '';
?>