<?
// header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db_config.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/common_func.php";
$fp = fopen("receive_status_end".date("Ymdhis").".log","w+");
/*
수신번호별 발송상태를 
IN
    idx
    send_num
    recv_num
    status
    regdate
*/
$user_id = $_POST["user_id"];
$token = $_POST["mem_token"];
$phone_num = $_POST["phone_num"];
$result = "";

$_POST['idx'] = $_REQUEST['idx'];
$_POST['send_num'] = $_REQUEST['send_num'];
$_POST['recv_num'] = $_REQUEST['recv_num'];
$_POST['status'] = $_REQUEST['status'];
$_POST['end_time'] = $_REQUEST['end_time'];


$send_num = explode(",",$_POST["send_num"]);
$idx = explode(",", $_POST["idx"]);
$recv_num = explode(",", $_POST["recv_num"]);
$status = explode(",", $_POST['status']);
$end_time = explode(",", $_POST['end_time']);

$query = "insert into GN_MMS_status_log set idx='{$idx[0]}',
                                                send_num='{$_POST['send_num']}',
                                                recv_num='{$_POST['recv_num']}',
                                                status='{$status[0]}',
                                                end_time='{$end_time[0]}'";
fwrite($fp,"38 : ".$query."\r\n");
mysqli_query($self_con, $query) or die(mysqli_error($self_con));
if ($_POST['idx'] == "")  exit;
if ($_POST['send_num'] == "")  exit;

if (count($recv_num) == 1) {
    $sql = "select idx from Gn_MMS_status where idx='{$_POST['idx']}' and send_num='{$_POST['send_num']}' and recv_num='{$_POST['recv_num']}'";
    fwrite($fp,"45 : ".$sql."\r\n");
    $resul = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($resul);
    if ($row['idx'] == "") {
        $sql_insert = "insert into Gn_MMS_status set idx='{$_POST['idx']}',
                                                             send_num='{$_POST['send_num']}',
                                                             recv_num='{$_POST['recv_num']}',
                                                             status='{$_POST['status']}',
                                                             regdate='{$_POST['end_time']}'";
        fwrite($fp,"54 : ".$sql_insert."\r\n");
        mysqli_query($self_con, $sql_insert);
    }
} else {
    for ($i = 0; $i < count($recv_num); $i++) {
        $sql = "select idx from Gn_MMS_status where idx='{$idx[$i]}' and send_num='{$send_num[$i]}' and recv_num='{$recv_num[$i]}'";
        fwrite($fp,"60 : ".$sql."\r\n");
        $resul = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $row = mysqli_fetch_array($resul);
        if ($row['idx'] == "") {
            $sql_insert = "insert into Gn_MMS_status set idx='{$idx[$i]}',
                                                                 send_num='{$send_num[$i]}',
                                                                 recv_num='{$recv_num[$i]}',
                                                                 status='{$status[$i]}',
                                                                 regdate='{$end_time[$i]}'";
            fwrite($fp,"69 : ".$sql_insert."\r\n");
            mysqli_query($self_con, $sql_insert);
        }
    }
}

$send_arr = explode(",", $_POST["send_num"]);
if (count($send_arr)) {
    preg_match('/(01[016789])([0-9]{3,4})([0-9]{4})/', $send_arr[0], $match);
    $phone_num = $match[0];
    if (strlen($phone_num) > 0) {

        $time = date("Y-m-d H:i:s");
        $sql = "select idx from call_api_log where phone_num='{$phone_num}'";
        $res = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $row = mysqli_fetch_array($res);
        if ($row['idx'] != "") {
            $sql = "update call_api_log set receive_status_end='{$time}' where idx='{$row['idx']}'";
            fwrite($fp,"87 : ".$sql."\r\n");
            mysqli_query($self_con, $sql);
        } else {
            $sql = "insert into call_api_log set receive_status_end='{$time}', phone_num='{$phone_num}'";
            fwrite($fp,"91 : ".$sql."\r\n");
            mysqli_query($self_con, $sql);
        }
    }
}
$result = "success";
$token_res = 1;

echo json_encode(array("result" => $result, "token_res" => $token_res));

exit;
