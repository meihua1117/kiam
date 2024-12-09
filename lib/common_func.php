<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";

function generateRandomString($length = 10){
	global $self_con;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	if($length == 32) {
		return $randomString;
	}
	else {
		$name_count_sql = "select count(idx) from Gn_Iam_Name_Card where card_short_url = '$randomString'";
		$name_count_result = mysqli_query($self_con,$name_count_sql);
		$name_count_row = mysqli_fetch_array($name_count_result);

		if ((int)$name_count_row[0]) {
			generateRandomString();
		} else {
			return $randomString;
		}
	}
}

function check_token($phone_num, $token){
	global $self_con;
	$sql_chk = "select token from gn_mms_token where phone_num='{$phone_num}' and token='{$token}'";
	$res_chk = mysqli_query($self_con,$sql_chk);
	$row_chk = mysqli_fetch_array($res_chk);
	if($row_chk['token'] != ''){
		return true;
	}
	else{
		return false;
	}
}

function sql_password($value)
{
	$value = md5($value);
	return $value;
}

function make_folder_month($n){
	$month = date('m');
	$year = date("Y");
	if($n == 2){
		$pre = '../../';
		$pre1 = '/';
	}
	else if($n == 1){
		$pre = '../';
		$pre1 = '/';
	}
	else{
		$pre = '';
		$pre1 = '';
	}
	$struct1 = $pre.'naver_editor/upload_month/upload_'.$year."_".$month;
	$struct = '';
	if(!is_dir($struct1)){
		if(mkdir($struct1, 0777, true)){
			chmod($struct1, 0777);
			$struct = $pre1.'upload_month/upload_'.$year."_".$month.'/';
		}
		else{
			return '';
		}
	}
	else{
		$struct = $pre1.'upload_month/upload_'.$year."_".$month.'/';
	}
	return $struct;
}
?>