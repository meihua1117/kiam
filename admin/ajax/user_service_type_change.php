<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 레벨 수정
*/
extract($_POST);

$mem_code = $_POST["mem_code"]; 
$result=0;

if($_POST['mode'] == "" || $_POST['service_type'] == "") {
    echo json_encode(array("status"=>$result));
    exit;
}else if($_POST['mode'] == "updat"){
    $field = $_POST['service_type'];
    $value = $_POST['service_value'];
    $sql = "update Gn_Member set $field='{$value}' where mem_code={$mem_code}";
    $update_result = mysql_query($sql) or die(mysql_error());
    if ($field == "callback_times") {
        $mem_id = $member_iam['mem_id'];
        $sql = "select a.callback_no from Gn_event a inner join gn_mms_callback b on a.callback_no=b.idx where b.service_state=1 and a.m_id='{$mem_id}' and a.event_name_kor='콜백메시지관리자설정동의' order by b.regdate desc";
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $sql_update_mem = "update Gn_Member use index(callback) set $field='{$value}' where mem_callback={$row['callback_no']} 
                                                                    and ((phone_callback={$row['callback_no']} and mem_callback_phone_state=1) or (mun_callback={$row['callback_no']} and mem_callback_mun_state=1))
                                                                    and callback_times like 'a%'";
            mysql_query($sql_update_mem);
        }
    }
    echo json_encode(array("status" => $update_result));
    exit;
}
?>