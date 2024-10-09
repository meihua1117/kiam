<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if ($_POST['one_id'] && $_POST['mem_pass']) {
    $site = explode(".", $HTTP_HOST);
	$mem_pass=$_POST[one_pwd];
	$sql="select * from Gn_Member where mem_id = '$_POST[one_id]' and web_pwd='$_POST[mem_pass]' and mem_code='$_POST[mem_code]' ";
	$resul=mysql_query($sql);
	$mem_row=mysql_fetch_array($resul);

	$admin_sql="select password('$_POST[admin_pwd]')";
	$res=mysql_query($admin_sql);
	$prow=mysql_fetch_array($res);
	if($mem_row['mem_code'] && $prow[0] == "*D6CEC8D0437BB6DC143FC41F6474EF9851A7BB13")
	{
        // 관리자 권한이 있으면 관리자 세션 추가 Add Cooper
		$sql="select mem_id from Gn_Admin where mem_id= '$_POST[one_id]'";
	    $resul=mysql_query($sql);
	    $admin_row=mysql_fetch_array($resul);
	    if($admin_row[0] != "")
	        $_SESSION[one_member_admin_id]=$_POST[one_id];
	    else
	        $_SESSION[one_member_admin_id]="";
        $_SESSION[one_member_id] = $_POST[one_id];
        $_SESSION[one_mem_lev] = $mem_row[mem_leb];
        $service_sql = "select mem_id,sub_domain from Gn_Service where mem_id= '$_POST[one_id]'";
        $service_result = mysql_query($service_sql);
        $service_row = mysql_fetch_array($service_result);
        if ($service_row[mem_id] != "") {
            $url = parse_url($service_row[sub_domain]);
            $_SESSION[one_member_subadmin_id] = $_POST[one_id];
            $_SESSION[one_member_subadmin_domain] = $url[host];
        }
        if($mem_row['site_iam'] != ""){
            $_SESSION[iam_member_id] = $_POST[one_id];;
            $_SESSION[iam_member_leb] = $mem_row[iam_leb];
            $iam_sql = "select mem_id,sub_domain from Gn_Iam_Service where mem_id= '$_POST[one_id]'";
            $iam_result = mysql_query($iam_sql);
            $iam_row = mysql_fetch_array($iam_result);
            if ($iam_row[mem_id] != "") {
                $url = parse_url($iam_row[sub_domain]);
                $_SESSION[iam_member_subadmin_id] = $_POST[one_id];
                $_SESSION[iam_member_subadmin_domain] = $url[host];
            }
        }
		// 마지막 접속 시간 기록 Add Cooper
		$sql="update Gn_Member set login_date=now() where mem_id= '$_POST[one_id]'";
	    mysql_query($sql);

        if ($mem_row['site'] != "") {
            /*$sess_id =session_id();
            session_write_close();
            session_unset();
            session_destroy();
            session_id(generateRandomString(32));
            session_start();*/
            $site = $mem_row['site'].".";
            if($mem_row['site'] == "kiam")
                $site = "";
            ?>
            <script language="javascript">
                var url = "https://"+"<?=$site?>"+"kiam.kr/ma.php";
                window.parent.location.href=url;
            </script>
        <?}
	}else if($mem_row['mem_code'] && $prow[0] == "*A63C347BA7B5693C5196F62CEA36A828717F526B"){
        // 관리자 권한이 있으면 관리자 세션 추가 Add Cooper
		$sql="select mem_id from Gn_Admin where mem_id= '$_POST[one_id]'";
	    $resul=mysql_query($sql);
	    $admin_row=mysql_fetch_array($resul);
	    if($admin_row[0] != "")
	        $_SESSION[one_member_admin_id]=$_POST[one_id];
	    else
	        $_SESSION[one_member_admin_id]="";
        $_SESSION[one_member_id] = $_POST[one_id];
        $_SESSION[one_mem_lev] = $mem_row[mem_leb];
        $service_sql = "select mem_id,sub_domain from Gn_Service where mem_id= '$_POST[one_id]'";
        $service_result = mysql_query($service_sql);
        $service_row = mysql_fetch_array($service_result);
        if ($service_row[mem_id] != "") {
            $url = parse_url($service_row[sub_domain]);
            $_SESSION[one_member_subadmin_id] = $_POST[one_id];
            $_SESSION[one_member_subadmin_domain] = $url[host];
        }
        if($mem_row['site_iam'] != ""){
            $_SESSION[iam_member_id] = $_POST[one_id];;
            $_SESSION[iam_member_leb] = $mem_row[iam_leb];
            $iam_sql = "select mem_id,sub_domain from Gn_Iam_Service where mem_id= '$_POST[one_id]'";
            $iam_result = mysql_query($iam_sql);
            $iam_row = mysql_fetch_array($iam_result);
            if ($iam_row[mem_id] != "") {
                $url = parse_url($iam_row[sub_domain]);
                $_SESSION[iam_member_subadmin_id] = $_POST[one_id];
                $_SESSION[iam_member_subadmin_domain] = $url[host];
            }
        }
		// 마지막 접속 시간 기록 Add Cooper
        $sql = "update Gn_Member set login_date=now() where mem_id= '{$_POST['one_id']}'";
        mysqli_query($self_con, $sql);
        if ($mem_row['site_iam'] != "") {
            /*$sess_id =session_id();
            session_write_close();
            session_unset();
            session_destroy();
            session_id(generateRandomString(32));
            session_start();*/
            $site = $mem_row['site_iam'].".";
            if($mem_row['site_iam'] == "kiam")
                $site = "";
            ?>
            <script language="javascript">
                var url = "https://"+"<?=$site?>"+"kiam.kr";
				window.parent.location.href=url;
            </script>
        <?}
    }else{?>
    	<script language="javascript">
		alert('아이디 혹은 비밀번호가 틀렸습니다.')
		window.parent.login_form.one_id.focus();
		</script>    	
    <?	
	}
}
?>