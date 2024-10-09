<?
include_once "../../lib/rlatjd_fun.php";
if($_SESSION[one_member_id] != "")
{
    if($_POST['exe_1']) $squery  = " exe_1='$_POST[exe_1]'";
    if($_POST['exe_2']) $squery  = " exe_2='$_POST[exe_2]'";
    if($_POST['exe_3']) $squery  = " exe_3='$_POST[exe_3]'";
    if($_POST['exe_4']) $squery  = " exe_4='$_POST[exe_4]'";
    if($_POST['exe_5']) $squery  = " exe_5='$_POST[exe_5]'";
    if($_POST['exe_6']) $squery  = " exe_6='$_POST[exe_6]'";
    
	$sql="update Gn_Member set $squery where mem_id='$_SESSION[one_member_id]'";
	$resul=mysql_query($sql);
}
?>