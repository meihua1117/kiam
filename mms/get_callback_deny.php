<?php
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";

$res = array();
$memID = $_REQUEST['mem_id'];
$sendNum = $_REQUEST['send_num'];
$recvNum = $_REQUEST['recv_num'];
$token = $_REQUEST['mem_token'];
$phone_num = $_REQUEST['phone_num'];

//if(!$token || !check_token($phone_num, $token)){
//    $token_res = 0;
//    $res['token_res'] = 0;
//    $res['result'] = 0;
//}
//else{
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
    $res['token_res'] = 1;
//}
echo json_encode($res);
exit;

?>