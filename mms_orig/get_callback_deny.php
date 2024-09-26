<?php
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";

$res = array();
$sendNum = $_REQUEST['send_num'];
$recvNum = $_REQUEST['recv_num'];

$sql_num="select idx from Gn_MMS_Deny where send_num='$sendNum' and recv_num='$recvNum' and chanel_type=9 ";
$resul_num=mysql_query($sql_num);
$row_num=mysql_fetch_array($resul_num);
if(!$row_num[0])
{//거부설정이 없으면
    $res['result'] = 1;
    // echo json_encode(array("result"=>"1"));
}
else{
    $res['result'] = 0;
    // echo json_encode(array("result"=>"0"));
}

echo json_encode($res);
exit;

?>