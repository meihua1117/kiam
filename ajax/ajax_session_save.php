<?
include_once "../lib/rlatjd_fun.php";
ini_set('display_errors', 'off');
extract($_POST);
$debug_mode = false;
//문자 저장
if($_POST[send_save_mms])
{ //메시지 저장
    
	if(strlen($_POST[send_txt]) <= 0 && $_POST[send_img] == "")
	$message_info['msg_type']="C";
	else
	{
		if($_POST[send_img]) //메시지 타입
		$message_info['msg_type']="B";
		else
		$message_info['msg_type']="A";
	}
	$sql="insert into Gn_MMS_Message set "; //발송
	$message_info['mem_id']=$_SESSION['one_member_id'];
	$message_info['title']=htmlspecialchars(str_replace("{|name|}", "{|REP|}",$_POST[send_title]));
	$message_info['message']=htmlspecialchars(str_replace("{|name|}", "{|REP|}",$_POST[send_txt]));
	$message_info['img']=$_POST[send_img];			
	foreach($message_info as $key=>$v)
	{
		$sql.=" $key='$v' , "; 
	}
	$sql.=" reg_date=now() ";
	
	if($debug_mode == false) {
		mysqli_query($self_con,$sql);
		echo "true";
    }
}
//if($_POST['lms_save_title'])
//{
//	if($_POST['lms_save_status']=="add")
//	{
//	$sql="insert into Gn_MMS_Message set ";
//	$message_info['seq_num']=$_POST['lms_save_seq_num'];
//	$message_info['mem_id']=$_SESSION['one_member_id'];	
//	}
//	else if($_POST['lms_save_status']=="modify")
//	{
//	$sql="update Gn_MMS_Message set ";		
//	}
//	$message_info['title']=htmlspecialchars($_POST['lms_save_title']);	
//	$message_info['message']=htmlspecialchars($_POST['lms_save_content']);
//	if($_POST['lms_save_img'])
//	$message_info['msg_type']="B";
//	else
//	$message_info['msg_type']="A";	
//	$message_info['img']=$_POST['lms_save_img'];
//	$i=0;
//	foreach($message_info as $key=>$v)
//	{
//		$bd=$i==count($message_info)-1?"":",";
//		$sql.=" $key='$v' $bd ";
//		$i++;
//	}
//	if($_POST['lms_save_status']=="add")
//	{
//		$sql.=" , reg_date=now() ";
//	}
//	else if($_POST['lms_save_status']=="modify")
//	{
//		$sql.=" where idx='$_POST[lms_save_idx]' ";		
//	}
//	if(mysqli_query($self_con,$sql))
//	{
//	}
//}
?>