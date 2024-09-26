<?php
// header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";
//$userId = $_POST["id"];//��ȭ��ȣ(������ ��ȣ ����)
//$userId = $_POST["id"];//��ȭ��ȣ(������ ��ȣ ����)
$userId = $_REQUEST["id"];//��ȭ��ȣ(������ ��ȣ ����)
//$userId = "01089564253";
//$reg = time();
$now_date = date("Y-m-d"); //���糯¥
$now_time = date("H:i:s"); //����ð�
$idx = $_REQUEST['idx'];
$mem_id = $_POST["mem_id"]; // �߰�
//$date3 = time()-(60*5);            // ����ð� - 5�� ->
//$date3 = date("H:i:s",$date3);

	// $sql = "insert into chk_log_app (userId, idx, regdate) values ('$userId', '$idx', NOW())";
	// mysql_query($sql);

$phone_num = $userId;
if(strlen($phone_num) > 0)
{
	$time = date("Y-m-d H:i:s");
	$sql="select idx from call_api_log where phone_num='$phone_num'";
	$res=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_array($res);
	if($row['idx'] != "") {
		$sql="update call_api_log set get_task='$time' where idx='$row[idx]'";
		mysql_query($sql);	
	}
	else{
		$sql ="insert into call_api_log set get_task='$time', phone_num='$phone_num'";
		mysql_query($sql);	
	}
}  



// 16-01-20 ����߼� �� ���� ���� 30��
// 1) reservation ~ 30�б����� ��������. 
// 2) �ð� ���� ���� ���� ó�� : Gn_MMS_ReservationFail�� �̵�,result = 3
$sql_where = "where now() > adddate(reservation,INTERVAL 30 Minute) and result = 1 and send_num = '".$userId."' and (reservation is null or reservation <= now())";
$sql = "insert into Gn_MMS_ReservationFail select `idx`, `mem_id`, `send_num`, `recv_num`, `uni_id`, `content`, `title`, `type`, `delay`, `delay2`, `close`, `jpg`, `result`, `reg_date`, `up_date`, `url`, `reservation` from Gn_MMS $sql_where";
mysql_query($sql);
//$sql = "delete from Gn_MMS $sql_where";
//mysql_query($sql);
$sql = "update Gn_MMS_ReservationFail set result = 3 $sql_where";
mysql_query($sql);

//$sql = "select * from Gn_MMS where 1 and result > 0 and send_num = '".$userId."' order by idx limit 1"; 
//������ ���� ����, �ش� ������ ��ȣ
//$sql = "select * from Gn_MMS where 1 and result > 0 and send_num = '".$userId."' and reg_date < now() and (now() < adddate(reg_date,INTERVAL 30 Minute))  and (reservation is null or reservation <= now())  order by idx asc limit 1";
$addQuery = "";
//if($idx) {
	$addQuery = " and idx ='$idx'";
	//$addQuery = " and idx ='$idx' and mem_id='$mem_id'";
//}
$sql = "select * from Gn_MMS where 1 and result > 0 and send_num = '".$userId."' and  (reg_date < now() and reg_date >= adddate(reg_date,INTERVAL -40 Minute)) and (reservation is null or reservation <= DATE_ADD(NOW(), INTERVAL 30 MINUTE))  $addQuery order by idx asc limit 1";
$query = mysql_query($sql);
$row = mysql_fetch_array($query);
//echo $sql."<BR>";
if($row[0] == "") {
	//$sql = "select * from Gn_MMS where 1 and result > 0 and send_num = '".$userId."' and  ((reg_date < now() and reg_date >= CURDATE()) or (reservation is null or reservation <= now()))  order by idx asc limit 1";
	/* �ӽ� ���� */
	/*
	$sql = "select * from Gn_MMS where 1 and result > 0 and send_num = '".$userId."' and  (((reg_date < now() and reg_date >= now()) and reservation is null) or ((reservation <= DATE_ADD(NOW(), INTERVAL 30 MINUTE)) and reservation >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)))  order by idx asc limit 1";
	
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);    
	*/
	//echo $sql."<BR>";
}
mysql_free_result($query);

//$sql = "insert into chk_log_app (userId, idx, regdate,q) values ('$userId', '$idx', NOW(),'".addslashes($sql)."')";
//echo $sql;
//mysql_query($sql);

$msg = str_replace("{|name|}", "{|REP|}", $row[content]);
$msg = str_replace("{|email|}", "{|REP1|}", $msg);
//$msg = str_replace("\n","\\n",$msg);
$msg = json_encode($msg);
$title = str_replace("{|name|}", "{|REP|}", $row[title]);
$title = str_replace("{|email|}", "{|REP1|}", $title);
$title = json_encode($title);
//$encode = array('ASCII', 'UTF-8', 'EUC-KR');
//$title = mb_detect_encoding($row[title]);
$url = $row[url];
$url = json_encode($url);
$url = str_replace('"','',$url);
//$url = str_replace(',','',$url);

$now = date("Y-m-d H:i:s");
if($row[reservation])
{
	//if($row[reservation] > $now)
	//exit;
}

if($query)
{
	$upt_sql = "update Gn_MMS set result = '0', up_date = now() where idx = '".$row[idx]."'";
	$upt_query = mysql_query($upt_sql);
}
//echo "{\"txt\":\"$msg\",\"reqid\":\"$reg\",\"pnum\":[{\"num\":\"$row[recv_num]\"}]}";

// Cooper add 2016.02.23 �������� ġȯ ���� �˻�
if(strstr($msg, "{|REP|}"))
	$rep = "{|REP|}";
else if(strstr($msg, "{|name|}"))
	$rep = "{|REP|}";
else    
	$rep = "";
	
	

if(strpos($row[recv_num],",")){
	$receive = explode(",",$row[recv_num]);	
	$replacement1 = explode(",",$row[replacement1]);	
	$replacement2 = explode(",",$row[replacement2]);	

	$url_ex = explode(",",$url);	
	$row[jpg] = str_replace("null","", $row[jpg]);
	
	for($i=0;$i<count($receive);$i++){
		$string .= $string ? ",". "{\"bnc\":\"$url_ex[$i]\",\"num\":\"$receive[$i]\",\"rep\":\"$replacement1[$i]\",\"rep1\":\"$replacement2[$i]\"}" : "{\"bnc\":\"$url_ex[$i]\",\"num\":\"$receive[$i]\",\"rep\":\"$replacement1[$i]\", \"rep1\":\"$replacement2[$i]\"}";
	//	$string .= $string ? ",". "{\"num\":\"$receive[$i]\"}" : "{\"num\":\"$receive[$i]\"}";
	}
	echo "{\"txt\":$msg,\"reqid\":\"$row[uni_id]\",\"delay\":\"$row[delay]\",\"close\":\"24\",\"idx\":\"$row[idx]\",\"type\":\"$row[type]\",\"title\":$title,\"jpg\":\"$row[jpg]\",\"jpg1\":\"$row[jpg1]\",\"jpg2\":\"$row[jpg2]\",\"pnum\":[$string]}";
	$data = "{\"txt\":$msg,\"reqid\":\"$row[uni_id]\",\"delay\":\"$row[delay]\",\"close\":\"24\",\"idx\":\"$row[idx]\",\"type\":\"$row[type]\",\"title\":$title,\"jpg\":\"$row[jpg]\",\"jpg1\":\"$row[jpg1]\",\"jpg2\":\"$row[jpg2]\",\"pnum\":[$string]}";
}else{
	$row[jpg] = str_replace("null","", $row[jpg]);
	$url = str_replace("null","", $url);
	echo "{\"txt\":$msg,\"reqid\":\"$row[uni_id]\",\"type\":\"$row[type]\",\"idx\":\"$row[idx]\",\"delay\":\"$row[delay]\",\"close\":\"24\",\"title\":$title,\"jpg\":\"$row[jpg]\",\"jpg1\":\"$row[jpg1]\",\"jpg2\":\"$row[jpg2]\",\"pnum\":[{\"bnc\":\"$url\",\"num\":\"$row[recv_num]\",\"rep\":\"$row[replacement1]\",\"rep1\":\"$row[replacement2]\"}]}";
	$data = "{\"txt\":$msg,\"reqid\":\"$row[uni_id]\",\"type\":\"$row[type]\",,\"idx\":\"$row[idx]\"\"delay\":\"$row[delay]\",\"close\":\"24\",\"title\":$title,\"jpg\":\"$row[jpg]\",\"jpg1\":\"$row[jpg1]\",\"jpg2\":\"$row[jpg2]\",\"pnum\":[{\"bnc\":\"$url\",\"num\":\"$row[recv_num]\",\"rep\":\"$row[replacement1]\",\"rep1\":\"$row[replacement2]\"}]}";
}


if($row[idx]) {
	$sql_j = "insert Gn_MMS_Json set mms_idx = '".$row[idx]."', data = '".$data."', reg_date = now()";
	//$query_j = mysql_query($sql_j);
}
//"{\"num\":\"01042322595\"},{\"num\":\"01042322595\"}"
?>