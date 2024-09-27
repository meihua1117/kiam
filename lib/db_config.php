<?
@ini_set('display_errors', false);
$mysql_host = 'localhost';
//$mysql_host = '175.118.126.178';
$mysql_user = 'root';
//$mysql_password = '49155f27admin';
$mysql_password = 'Onlyone123!@#';
//$mysql_password = 'only12!@db';
$mysql_db = 'kiam';
//$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysqli_error($self_con));
//mysql_select_db($mysql_db) or die(mysqli_error($self_con));
global $self_con;
$self_con = mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db) or die(mysqli_error($self_con));
//$self_con = new mysqli($mysql_host,$mysql_user,$mysql_password,$mysql_db);
if (!$self_con) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
//mysql_select_db($mysql_db) or die(mysqli_error($self_con));
mysqli_query($self_con, "set names utf8");

?>