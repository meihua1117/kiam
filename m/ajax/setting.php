<?
include_once "../../lib/rlatjd_fun.php";
$sql=" update Gn_Member set ";
if($_POST['exe_1'])
    $sql.=" exe_1 = '".$_POST['exe_1']."' ";
if($_POST['exe_2'])
    $sql.=" exe_2 = '".$_POST['exe_2']."' ";    
if($_POST['exe_3'])
    $sql.=" exe_3 = '".$_POST['exe_3']."' ";    
if($_POST['exe_4'])
    $sql.=" exe_4 = '".$_POST['exe_4']."' ";        
if($_POST['exe_5'])
    $sql.=" exe_5 = '".$_POST['exe_5']."' ";            
if($_POST['exe_6'])
    $sql.=" exe_6 = '".$_POST['exe_6']."' ";
$sql.=" where mem_id='{$_SESSION['one_member_id']}' ";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
?>