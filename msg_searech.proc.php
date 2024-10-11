<?
header("Content-type:text/html;charset=euc-kr");
include_once "lib/rlatjd_fun.php";

if($_REQUEST['mode']=="del")
{
	$sql="select * from Gn_MMS_Message where idx='$_REQUEST[idx]' and mem_id ='{$_SESSION['one_member_id']}' ";
	$resul=mysqli_query($self_con,$sql);
?>
<script>

	location.href='/msg_serch.php?status=1&status2=1';	

</script>
<?
}
?>


