<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

$memID = $_REQUEST['mem_id'];
$sendNum = $_REQUEST['send_num'];
$recvNum = $_REQUEST['recv_num'];
$uni_id = generateRndString(4);
$title = $_REQUEST['title'];
$content = $_REQUEST['content'];
$reservation = date("Y-m-d H:i:s");
$status = $_REQUEST['status'];

$sql_num="select sendnum from Gn_MMS_Number where mem_id ='$memID' and sendnum='$sendNum' ";
$resul_num=mysql_query($sql_num);
$row_num=mysql_fetch_array($resul_num);
if(!$row_num[0])
{
    echo json_encode(array("result"=>"1"));
    exit;
}

$query = "insert into Gn_MMS set mem_id='$memID',
            send_num='$sendNum',
            recv_num='$recvNum',
            uni_id='$uni_id',
            content='$content',
            title='$title',
            delay='60',
            delay2='60',
            close='24',
            type='9',
			result=0,
			up_date=NOW(),
            reg_date=NOW(),
            agreement_yn='Y',
            recv_num_cnt=1
        ";
mysql_query($query);
$idx = mysql_insert_id();

if($status){
    $sql_insert = "insert into Gn_MMS_status set idx='$idx',
                                                send_num='$sendNum',
                                                recv_num='$recvNum',
                                                status='0',
                                                regdate=now()";
    mysql_query($sql_insert);
}
echo json_encode(array("result"=>"0"));

function generateRndString($length = 10){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
    return $randomString;
}
?>