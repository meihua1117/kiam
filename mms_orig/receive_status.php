<?
// header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";

/*
수신번호별 발송상태를 
IN
    idx
    send_num
    recv_num
    status
    regdate
*/
$result = "fail";

$_POST['idx'] =$_REQUEST['idx'];
$_POST['send_num'] =$_REQUEST['send_num'];
$_POST['recv_num'] =$_REQUEST['recv_num'];
$_POST['status'] =$_REQUEST['status'];

if($_POST['idx'] == "")  exit;
if($_POST['send_num'] == "")  exit;

$idx = $_POST["idx"]; 
$send_num = $num = $_POST["send_num"];      
$recv_num = $_POST["recv_num"];
$status = $_POST['status'];

$sql="select idx from Gn_MMS where idx='{$idx}' and send_num='{$send_num}'";
$resul=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_array($resul);
if($row['idx'] != "") {
    $sql="select idx from Gn_MMS_status where idx='{$idx}' and send_num='{$send_num}' and recv_num='{$recv_num}'";
    $resul=mysql_query($sql) or die(mysql_error());
    $row=mysql_fetch_array($resul);    
    if($row['idx'] != "") {
        $sql_insert = "update Gn_MMS_status set status='$status',regdate=now() where idx='$idx' and 
                                                        send_num='$send_num' and 
                                                        recv_num='$recv_num'
                                                        ";
            mysql_query($sql_insert); 
    } else {
        $sql_insert = "insert into Gn_MMS_status set idx='$idx',
                                                        send_num='$send_num',
                                                        recv_num='$recv_num',
                                                        status='$status',
                                                        regdate=now()";
            mysql_query($sql_insert);         
    }

    $phone_num = $send_num;
    if(strlen($phone_num) > 0)
    {
        $time = date("Y-m-d H:i:s");
        $sql="select idx from call_api_log where phone_num='$phone_num'";
        $res=mysql_query($sql) or die(mysql_error());
        $row=mysql_fetch_array($res);
        if($row['idx'] != "") {
            $sql="update call_api_log set receive_status='$time' where idx='$row[idx]'";
            mysql_query($sql);	
        }
        else{
            $sql ="insert into call_api_log set receive_status='$time', phone_num='$phone_num'";
            mysql_query($sql);	
        }
    }
    
    $result = "success";
    echo "{\"result\":\"$result\"}";        
} else {
    $result = "fail";
    echo "{\"result\":\"$result\"}";    
}

exit;

?>