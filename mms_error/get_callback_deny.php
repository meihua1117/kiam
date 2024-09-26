<?php
//header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

$memID = $_REQUEST['mem_id'];
$sendNum = $_REQUEST['send_num'];
$recvNum = $_REQUEST['recv_num'];


$sql_num="select idx from Gn_MMS_Deny where send_num='$sendNum' and recv_num='$recvNum' and chanel_type=9 ";
$resul_num=mysql_query($sql_num);
$row_num=mysql_fetch_array($resul_num);
if(!$row_num[0])
{//거부설정이 없으면
    echo json_encode(array("result"=>"1"));
}
else{
    echo json_encode(array("result"=>"0"));
}
exit;

?>