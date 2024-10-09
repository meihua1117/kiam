<?
include_once "../lib/rlatjd_fun.php";

//번호체크삭제
if($_POST[recv_num] && $_POST[grp_id])
{
	$num_a=explode(",", $_POST[recv_num]);
	
	$recv_num = "'".implode("','", $num_a)."'";
	
	$sql="delete from Gn_MMS_Receive where  mem_id ='$_SESSION[one_member_id]' and grp_id = '$_POST[grp_id]' and recv_num in ($recv_num) ";
	mysql_query($sql);
}
?>