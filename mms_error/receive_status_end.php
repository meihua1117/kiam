<?
//header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

/*
수신번호별 발송상태를 
IN
    idx
    send_num
    recv_num
    status
    regdate
*/

$_POST['idx'] =$_REQUEST['idx'];
$_POST['send_num'] =$_REQUEST['send_num'];
$_POST['recv_num'] =$_REQUEST['recv_num'];
$_POST['status'] =$_REQUEST['status'];
$_POST['end_time'] =$_REQUEST['end_time'];
//file_put_contents("log.txt", print_r($_POST, 1));
$idx = $_POST["idx"]; 
$send_num = $num = $_POST["send_num"];      


$recv_num = explode(",", $_POST["recv_num"]);
$status = explode(",", $_POST['status']);
$end_time = explode(",", $_POST['end_time']);

$query = "insert into GN_MMS_status_log set idx='$_POST[idx]',
                                            send_num='$_POST[send_num]',
                                            recv_num='$_POST[recv_num]',
                                            status='$_POST[status]',
                                            end_time='$_POST[end_time]'                                            
";
mysql_query($query) or die(mysql_error());
if($_POST['idx'] == "")  exit;
if($_POST['send_num'] == "")  exit;

if(count($recv_num) == 1) {
        $sql="select idx from Gn_MMS_status where idx='{$_POST[idx]}' and send_num='{$_POST[send_num]}' and recv_num='{$_POST[recv_num]}'";
        
        $resul=mysql_query($sql) or die(mysql_error());
        $row=mysql_fetch_array($resul);    
        if($row['idx'] == "") {
            $sql_insert = "insert into Gn_MMS_status set idx='$_POST[idx]',
                                                         send_num='$_POST[send_num]',
                                                         recv_num='$_POST[recv_num]',
                                                         status='$_POST[status]',
                                                         regdate='$_POST[end_time]'";
             mysql_query($sql_insert);         
        }    
} else {
    for($i=0;$i < count($recv_num);$i++) {
            $sql="select idx from Gn_MMS_status where idx='{$idx}' and send_num='{$send_num}' and recv_num='{$recv_num[$i]}'";
            $resul=mysql_query($sql) or die(mysql_error());
            $row=mysql_fetch_array($resul);    
            if($row['idx'] == "") {
                $sql_insert = "insert into Gn_MMS_status set idx='$idx',
                                                             send_num='$send_num',
                                                             recv_num='$recv_num[$i]',
                                                             status='$status[$i]',
                                                             regdate='$end_time[$i]'";
                 mysql_query($sql_insert);         
            }
    }
}
$send_arr = explode(",", $_POST["send_num"]);
if(count($send_arr))
{
    preg_match('/(01[016789])([0-9]{3,4})([0-9]{4})/', $send_arr[0], $match);
    $phone_num = $match[0];
    if(strlen($phone_num) > 0)
    {
        
        $time = date("Y-m-d H:i:s");
        $sql="select idx from call_api_log where phone_num='$phone_num'";
        $res=mysql_query($sql) or die(mysql_error());
        $row=mysql_fetch_array($res);
        if($row['idx'] != "") {
            $sql="update call_api_log set receive_status_end='$time' where idx='$row[idx]'";
            mysql_query($sql);	
        }
        else{
            $sql ="insert into call_api_log set receive_status_end='$time', phone_num='$phone_num'";
            mysql_query($sql);	
        }
    }
}
$result = "success";
echo "{\"result\":\"$result\"}";   
exit;
?>