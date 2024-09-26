<?
include_once "../lib/rlatjd_fun.php";
ini_set('display_errors', 'off');
extract($_POST);
$debug_mode = false;
//문자 저장
if ($_POST['send_save_mms']) { //메시지 저장

	if (strlen($_POST['send_txt']) <= 0 && $_POST['send_img'] == "")
		$message_info['msg_type'] = "C";
	else {
		if ($_POST['send_img']) //메시지 타입
			$message_info['msg_type'] = "B";
		else
			$message_info['msg_type'] = "A";
	}
	$sql = "insert into Gn_MMS_Message set "; //발송
	$message_info['mem_id'] = $_SESSION['one_member_id'];
	$message_info['title'] = htmlspecialchars(str_replace("{|name|}", "{|REP|}", $_POST['send_title']));
	$message_info['message'] = htmlspecialchars(str_replace("{|name|}", "{|REP|}", $_POST['send_txt']));
	$message_info['img'] = $_POST['send_img'];
	foreach ($message_info as $key => $v) {
		$sql .= " $key='$v' , ";
	}
	$sql .= " reg_date=now() ";

	if ($debug_mode == false) {
		mysqli_query($self_con, $sql);
		echo "true";
	}
}
?>
