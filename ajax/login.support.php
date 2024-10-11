<?
include_once "../lib/rlatjd_fun.php";
$_POST['one_id']  = $_REQUEST['one_id'];
$_POST[one_pwd]= $_REQUEST[one_pwd];
if($_POST['one_id'] && $_POST[one_pwd])
{
	$mem_pass=$_POST[one_pwd];
	$sql="select mem_code, is_leave, mem_leb,site,site_iam,iam_leb from Gn_Member use index(login_index) where mem_leb>0 and ((mem_id = '{$_POST['one_id']}' and web_pwd=password('$mem_pass')) or (mem_email = '{$_POST['one_id']}' and web_pwd=password('$mem_pass'))) ";
	$resul=mysqli_query($self_con,$sql);

	$row=mysqli_fetch_array($resul);
    if(($row['mem_code'] and $row[is_leave] == 'N' ))// || ($_POST['one_id'] == "obmms02" && $mem_pass == "obmms01") || ($_POST['one_id'] == "stp119" && $mem_pass == "obmms01"))
	{
		//$parse = parse_url($_SERVER[HTTP_HOST]);
		$site = explode(".", $HTTP_HOST);
		// 관리자 권한이 있으면 관리자 세션 추가 Add Cooper
		$admin_sql = "select mem_id from Gn_Admin where mem_id= '{$_POST['one_id']}'";
		$admin_result = mysqli_query($self_con,$admin_sql);
		$admin_row = mysqli_fetch_array($admin_result);
		if ($admin_row[0] != "") {
			$_SESSION['one_member_admin_id'] = $_POST['one_id'];
		}
		//if($row['site'] == $site[0]) {
		if($row['site'] != "") {
			$_SESSION['one_member_id'] = $_POST['one_id'];
			$_SESSION[one_mem_lev] = $row[mem_leb];
			$service_sql = "select mem_id,sub_domain from Gn_Service where mem_id = '{$_POST['one_id']}'";
			$service_result = mysqli_query($self_con,$service_sql);
			$service_row = mysqli_fetch_array($service_result);
			if ($service_row[mem_id] != "") {
				$url = parse_url($service_row[sub_domain]);
				$_SESSION['one_member_subadmin_id'] = $_POST['one_id'];
				$_SESSION['one_member_subadmin_domain'] = $url[host];
			}
		}
		//if($HTTP_HOST == 'kiam.kr')
		//if($row['site_iam'] == $site[0]){
		if($row['site_iam'] != ""){
			$_SESSION['iam_member_id'] = $_POST['one_id'];;
			$_SESSION['iam_member_leb'] = $row[iam_leb];
			$iam_sql = "select mem_id,sub_domain from Gn_Iam_Service where mem_id= '{$_POST['one_id']}'";
			$iam_result = mysqli_query($self_con,$iam_sql);
			$iam_row = mysqli_fetch_array($iam_result);
			if ($iam_row[mem_id] != "") {
				$url = parse_url($iam_row[sub_domain]);
				$_SESSION['iam_member_subadmin_id'] = $_POST['one_id'];
				$_SESSION['iam_member_subadmin_domain'] = $url[host];
			}
		}
		// 마지막 접속 시간 기록 Add Cooper
		// $memToken = generateRandomString(10);
		$sql = "update Gn_Member set login_date=now(),ext_recm_id='$site[0]' where mem_id= '{$_POST['one_id']}'";
		$resul = mysqli_query($self_con,$sql);

		if($row['site'] == $site[0]) {?>
			<script language="javascript">
				window.parent.location.replace('/sub_6.php');
			</script>
		<?}else if($row['site'] != "") {
			/*$sess_id =session_id();
			session_write_close();
			session_id(generateRandomString(32));
			session_start();*/
    		//session_start_samesite();
			$site = $row['site'].".";
			if($row['site'] == "kiam")
			  $site = "";
			?>
			<iframe src="http://<?=$site?>kiam.kr/ajax/login.support.php?one_id=<?php echo $_POST['one_id'];?>&one_pwd=<?php echo $_POST['one_pwd'];?>"></iframe>
			<script language="javascript">
				var url = "http://"+"<?=$site?>"+"kiam.kr/index.php?key="+'<?=$sess_id?>';
				
				window.parent.location.href=url;
			</script>
		<?
		
		}
	}else if($row['mem_code'] and $row[is_leave] == 'Y'){?>
  	<script language="javascript">alert('탈퇴한 회원 아이디입니다.');window.parent.login_form.one_id.focus();</script>
<?	}else{?>
  	<script language="javascript">alert('아이디 혹은 비밀번호가 틀렸습니다.');window.parent.login_form.one_id.focus();</script>
<?	}
}?>