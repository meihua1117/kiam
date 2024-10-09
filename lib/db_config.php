<?
@ini_set('display_errors', false);
$mysql_host = 'localhost';
$mysql_user = 'kiam';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';
global $self_con;
$self_con = mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db) or die(mysqli_error($self_con));
if (!$self_con) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
//mysql_select_db($mysql_db) or die(mysqli_error($self_con));
mysqli_query($self_con, "set names utf8");
$post_Data = '';
$key = '';
?>