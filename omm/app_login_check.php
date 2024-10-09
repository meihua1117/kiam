<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";
$PG_table = "Gn_Member";
$JO_table = "";
$URL = ($URL)?$URL:$_SERVER['HTTP_REFERER'];
$mb_id = $_POST[mb_id];
$mb_pass = $_POST[mb_pass];
$mem_code = $_POST[mem_code];
$mb_leb = "22";

if(isset($mb_id) && isset($mb_pass)) 
{
	$sql = " SELECT * FROM Gn_Member WHERE mem_id = '{$mb_id}' or (id_type='hp' and replace(mem_id,'-','') = replace('{$mb_id}','-',''))";
	$res = mysql_query($sql);
	$mb = mysql_fetch_array($res);
	$check=true;
}

$sql="select mem_code, mem_id, is_leave, mem_leb, iam_leb,site, site_iam from Gn_Member use index(login_index) where mem_leb>0 and (mem_id = '$mb_id' and mem_code='$mem_code') ";
$resul=mysql_query($sql);
$row=mysql_fetch_array($resul);
if($row[mem_code] and $row[is_leave] == 'N')
{
	$site = explode(".", $HTTP_HOST);
	if($row['site'] != "") {
		$_SESSION[one_member_id] = $_POST[one_id];
		$_SESSION[one_mem_lev] = $row[mem_leb];
		$service_sql = "select mem_id,sub_domain from Gn_Service where mem_id= '$_POST[one_id]'";
		$service_result = mysql_query($service_sql);
		$service_row = mysql_fetch_array($service_result);
		if ($service_row[mem_id] != "") {
			$url = parse_url($service_row[sub_domain]);
			$_SESSION[one_member_subadmin_id] = $_POST[one_id];
			$_SESSION[one_member_subadmin_domain] = $url[host];
		}
	}
	if($row['site_iam'] != ""){
		$_SESSION[iam_member_id] = $_POST[one_id];;
		$_SESSION[iam_member_leb] = $row[iam_leb];
		$iam_sql = "select mem_id,sub_domain from Gn_Iam_Service where mem_id= '$_POST[one_id]'";
		$iam_result = mysql_query($iam_sql);
		$iam_row = mysql_fetch_array($iam_result);
		if ($iam_row[mem_id] != "") {
			$url = parse_url($iam_row[sub_domain]);
			$_SESSION[iam_member_subadmin_id] = $_POST[one_id];
			$_SESSION[iam_member_subadmin_domain] = $url[host];
		}
	}
	// $memToken = generateRandomString(10);
	$sql = "update Gn_Member set login_date=now(),ext_recm_id='$site[0]' where mem_id= '$_POST[one_id]'";
	$resul = mysql_query($sql);
}

if($_POST[mb_id]==FALSE) {
	alert("���������� �α��� �Ͽ� �ּ���", "/omm/app_login.php");
} else if($check==FALSE) {
	alert("���̵� Ȥ�� ��й�ȣ�� ��ġ���� �ʽ��ϴ�.\\n(��й�ȣ�� ��ҹ��ڸ� �����մϴ�.)", "/omm/app_login.php?URL={$URL}");
}
echo "123"; exit;
if($check==TRUE) {
	echo "login ok!";
}else{
	echo "login fail!";
}
?>
