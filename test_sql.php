<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$date = date("Y-m-d H:i:s");
$sql="select * from Gn_Member where mem_id='onlymain' ";
$resul=mysqli_query($self_con, $sql);
$data=mysqli_fetch_array($resul);
echo $data['mem_name'];
?>
