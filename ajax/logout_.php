<?
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';
$self_con=mysql_connect($mysql_host,$mysql_user,$mysql_password) or die(mysqli_error($self_con));
mysql_select_db($mysql_db) or die(mysqli_error($self_con));
mysqli_query($self_con,"set names utf8");

	if($_SESSION['one_member_id'])
	  {
        $_SESSION['one_member_id'] = "";
	    $_SESSION['iam_member_id'] = "";
	    $_SESSION['one_member_admin_id'] = "";
	    $_SESSION['one_member_subadmin_domain'] = "";
	    $_SESSION['iam_member_subadmin_domain'] = "";
	    $_SESSION['one_member_subadmin_id'] = "";
	    $_SESSION['iam_member_subadmin_id'] = "";
	    $_SESSION['one_mem_leb'] = "";
	    $_SESSION['iam_member_leb'] = "";
		?>
		<script language="javascript">
		//alert('로그아웃되었습니다.')
		location.replace('/ma.php');
		</script>
        <?

		exit;
	  }
?>
