<?
include_once "../../lib/rlatjd_fun.php";
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
		location.replace('/m/')
		</script>
        <?
        
		exit;
	  }	  
?>