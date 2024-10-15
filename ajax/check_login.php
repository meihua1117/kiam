<?
include_once "../lib/rlatjd_fun.php";
if($_REQUEST['id'] && $_REQUEST['pwd'])
{
    $mem_pass=$_REQUEST['pwd'];
	//$sql="select mem_code, is_leave, mem_leb from Gn_Member use index(login_index) where mem_leb>0 and ((mem_id = '{$_REQUEST['id']}' and web_pwd=password('$mem_pass')) or (mem_email = '{$_REQUEST['id']}' and web_pwd=password('$mem_pass'))) ";
	$sql="select mem_code, is_leave, mem_leb from Gn_Member use index(login_index) where mem_leb>0 and ((mem_id = '{$_REQUEST['id']}' or mem_email = '{$_REQUEST['id']}') and (mem_pass=md5('$mem_pass') or web_pwd=md5('$mem_pass'))) ";
	$resul=mysqli_query($self_con,$sql);
	$row=mysqli_fetch_array($resul);
    if(($row['mem_code'] and $row['is_leave'] == 'N' ) )
	{
		echo $_REQUEST['callback'].'(true)';
	}
	else
	{
        echo $_REQUEST['callback'].'(false)';
	}
}
?>