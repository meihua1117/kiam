<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if($_SESSION['one_member_id'] != "")
{
	if($_POST[mode] == "add")
	{
		if(isset($_POST['mail_title']) && isset($_POST['mail_content'])){

			$sql="insert into gn_mail_message set "; 
			$message_info['mem_id']=$_SESSION['one_member_id'];
			$message_info['title']=htmlspecialchars(str_replace("{|name|}", "{|REP|}",$_POST[mail_title]));
			$message_info['message']=htmlspecialchars(str_replace("{|name|}", "{|REP|}",$_POST[mail_content]));
			$message_info[file]=$_POST[attach_file];
			$message_info[img]=$_POST[send_img];
			$message_info[img1]=$_POST[send_img1];
			$message_info[img2]=$_POST[send_img2];
			foreach($message_info as $key=>$v){
				$sql.=" $key='$v' , ";
			}
			$sql.=" reg_date=now() ";
			mysqli_query($self_con,$sql); 
		}
	}
	else if($_POST[mode]=="del")
	{
		$sql="delete from gn_mail_message where idx='{$_REQUEST['idx']}' and mem_id ='{$_SESSION['one_member_id']}' ";
		echo $sql;
		$resul=mysqli_query($self_con,$sql);
	}
	else if($_POST[mode] == "send")
	{
		$recv_groups=explode(",",$_POST[group_nums]);
		$email_arr = array();
		foreach($recv_groups as $key=>$v){
			if(strpos($v, "(") !== false){
				$res1 = explode("(", $v);
				$grp_id = $res1[0];
				$res2 = explode("-", $res1[1]);
				$start = $res2[0];
				$res3 = explode(")", $res2[1]);
				$end = $res3[0];
				$end_1 = $end * 1 - $start * 1 + 1;
				$start_1 = $start * 1 - 1;
				$limit_str = " limit " . $start_1 . ", " . $end_1;
			}
			else{
				$grp_id = $v;
				$limit_str = "";
			}
			$sql = "select email from Gn_MMS_Receive where grp_id = '$grp_id' order by idx asc" . $limit_str;
			$result = mysqli_query($self_con,$sql);
			while($row=mysqli_fetch_array($result)) {
				if($row['email'] != "" && !in_array($row['email'], $email_arr))
					array_push($email_arr,$row['email']);
			}
		}

		if(count($email_arr) > 0)
		{
		//메일발송을 진행한다.
		$sender = $_POST['mail_addr'];
		$title = $_POST['mail_title'];
		$content = $_POST['mail_content'];
			$file = $_POST['attach_file'];			
			$receivers = implode(",", $email_arr);
			if($_POST['reserv_day']){
				$sendtime = $_POST['reserv_day'] . " " . $_POST['reserv_time'];
			}else{
				$sendtime = date("Y-m-d") . " " . $_POST['reserv_time'];
			}
			sendemail($sendtime, $receivers, $sender, $title, $content, $file);
		}

	}
} 
?>



