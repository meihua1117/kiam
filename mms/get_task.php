<?
// header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db_config.php";
$fp = fopen("get_task_".date("YmdHis").".log", "w+");
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
//include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";
$user_id = $_POST["user_id"];
$token = $_POST["mem_token"];
$phone_num = $_POST["phone_num"];
$empty_arr = array();
$userId = $_POST["id"]; //��ȭ��ȣ(������ ��ȣ ����)
$sql_chk = "select idx from Gn_MMS_Number where sendnum='{$userId}'";
fwrite($fp,"13:".$sql_chk."\r\n");
$res_chk = mysqli_query($self_con, $sql_chk);
$row_chk = mysqli_num_rows($res_chk);
if (!$row_chk || !$userId) {
	echo json_encode(array(
		"txt" => "",
		"reqid" => "",
		"delay" => "",
		"close" => "",
		"idx" => "",
		"type" => "",
		"title" => "",
		"jpg" => "",
		"jpg1" => "",
		"jpg2" => "",
		"pnum" => array()
	));
	exit;
}
$now_date = date("Y-m-d"); //
$now_time = date("H:i:s"); //
$idx = $_POST['idx'];
$mem_id = $_POST["mem_id"]; //
$phone_num = $userId;
if (strlen($phone_num) > 0) {
	$time = date("Y-m-d H:i:s");
	$sql = "select idx from call_api_log where phone_num='{$phone_num}'";
	fwrite($fp,"40:".$sql."\r\n");
	$res = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
	$row = mysqli_fetch_array($res);
	if ($row['idx'] != "") {
		$sql = "update call_api_log set get_task='{$time}' where idx='{$row['idx']}'";
		fwrite($fp,"45:".$sql."\r\n");
		mysqli_query($self_con, $sql);
	} else {
		$sql = "insert into call_api_log set get_task='{$time}', phone_num='$phone_num'";
		fwrite($fp,"49:".$sql."\r\n");
		mysqli_query($self_con, $sql);
	}
}

$sql_where = "where now() > adddate(reservation,INTERVAL 30 Minute) and result = 1 and send_num = '{$userId}' and (reservation is null or reservation <= now())";
$sql = "select count(idx) from Gn_MMS $sql_where";

$res = mysqli_query($self_con, $sql);
$row = mysqli_fetch_array($res);
if ($row[0] == 0) {
	$sql = "insert into Gn_MMS_ReservationFail select `idx`, `mem_id`, `send_num`, `recv_num`, `uni_id`, `content`, `title`, `type`, `delay`, `delay2`, `close`, `jpg`, `result`, `reg_date`, `up_date`, `url`, `reservation` from Gn_MMS $sql_where";
	fwrite($fp,"61:".$sql."\r\n");
	mysqli_query($self_con, $sql);
}
$sql = "update Gn_MMS_ReservationFail set result = 3 $sql_where";
fwrite($fp,"65:".$sql."\r\n");
mysqli_query($self_con, $sql);

$addQuery = " and idx ='$idx'";
$sql = "select * from Gn_MMS where result > 0 and send_num = '{$userId}' and  (reg_date < now() and reg_date >= adddate(reg_date,INTERVAL -40 Minute)) and (reservation is null or reservation <= DATE_ADD(NOW(), INTERVAL 30 MINUTE))  $addQuery order by idx asc limit 1";
fwrite($fp,"70:".$sql."\r\n");
$query = mysqli_query($self_con, $sql);
if(mysqli_num_rows($query) > 0){
	$row = mysqli_fetch_array($query);
	$upt_sql = "update Gn_MMS set result = '0', up_date = now() where idx = '{$row['idx']}'";
	fwrite($fp,"86:".$upt_sql."\r\n");
	$upt_query = mysqli_query($self_con, $upt_sql);
}

$msg = str_replace("{|name|}", "{|REP|}", $row['content']);
$msg = str_replace("{|email|}", "{|REP1|}", $msg);
$title = str_replace("{|name|}", "{|REP|}", $row['title']);
$title = str_replace("{|email|}", "{|REP1|}", $title);
//$title = json_encode($title);
$url = $row['url'];
//$url = json_encode($url);
$url = str_replace('"', '', $url);

$now = date("Y-m-d H:i:s");
if (strstr($msg, "{|REP|}"))
	$rep = "{|REP|}";
else if (strstr($msg, "{|name|}"))
	$rep = "{|REP|}";
else
	$rep = "";
$row['jpg'] = str_replace("null", "", $row['jpg']);
$data = array(
	"txt" => $msg,
	"reqid" => $row['uni_id'],
	"delay" => $row['delay'],
	"delay2" => $row['delay2'],
	"close" => 24,
	"idx" => $row['idx'],
	"type" => $row['type'],
	"title" => $title,
	"jpg" => $row['jpg'],
	"jpg1" => $row['jpg1'],
	"jpg2" => $row['jpg2']
);
if (strpos($row['recv_num'], ",")) {
	$receive = explode(",", $row['recv_num']);
	$replacement1 = explode(",", $row['replacement1']);
	$replacement2 = explode(",", $row['replacement2']);
	$url_ex = explode(",", $url);
	$string = array();
	for ($i = 0; $i < count($receive); $i++) {
		$array = array(
			"bnc" => $url_ex[$i],
			"num" => $receive[$i],
			"rep" => $replacement1[$i],
			"rep1" => $replacement2[$i]
		);
		array_push($string, $array);
		//$string .= $string ? "," . "{\"bnc\":\"$url_ex[$i]\",\"num\":\"$receive[$i]\",\"rep\":\"$replacement1[$i]\",\"rep1\":\"$replacement2[$i]\"}" : "{\"bnc\":\"$url_ex[$i]\",\"num\":\"$receive[$i]\",\"rep\":\"$replacement1[$i]\", \"rep1\":\"$replacement2[$i]\"}";
	}
	$data['pnum'] = $string;
	$data = json_encode($data);
	$data = preg_replace('/null(?=([^,{}]*[},])|($))/', '""', $data);
	echo $data;
} else {
	$url = str_replace("null", "", $url);
	$string = array();
	$array = array(
		"bnc" => $url,
		"num" => $row['recv_num'],
		"rep" => $row['replacement1'],
		"rep1" => $row['replacement2']
	);
	array_push($string, $array);
	$data['pnum'] = $string;
	$data = json_encode($data);
	$data = preg_replace('/null(?=([^,{}]*[},])|($))/', '""', $data);
	echo $data;
	//$data = "{\"txt\":$msg,\"reqid\":\"$row['uni_id']\",\"type\":\"$row['type']\",\"idx\":\"$row['idx']\",\"delay\":\"$row['delay']\",\"delay2\":\"$row['delay2']\",\"close\":\"24\",\"title\":$title,\"jpg\":\"$row['jpg']\",\"jpg1\":\"$row['jpg1']\",\"jpg2\":\"$row['jpg2']\",\"pnum\":[{\"bnc\":\"$url\",\"num\":\"$row['recv_num']\",\"rep\":\"$row['replacement1']\",\"rep1\":\"$row['replacement2']\"}]}";
}
if ($row['idx']) {
	$sql_j = "insert Gn_MMS_Json set mms_idx = '{$row['idx']}', data = '{$data}', reg_date = now()";
	fwrite($fp,"147:".$sql_j."\r\n");
	mysqli_query($self_con, $sql_j);
}
fwrite($fp,"150:end\r\n");
?>