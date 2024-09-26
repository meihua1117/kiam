<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/

$cur_time = date("Y-m-d H:i:s");
$cur_time1 = date("YmdHis");
extract($_POST);
if(!$grp_id){
    exit;
}

$sql_detail = "select grp from Gn_MMS_Group where idx='{$grp_id}' and mem_id='{$_SESSION['one_member_id']}'";
$res_detail = mysqli_query($self_con, $sql_detail);
$row_detail = mysqli_fetch_array($res_detail);

$sql_cnt = "select count(*) from Gn_MMS_Receive where grp_id='{$grp_id}' and mem_id='{$_SESSION['one_member_id']}'";
$res_cnt = mysqli_query($self_con, $sql_cnt);
$row_cnt = mysqli_fetch_array($res_cnt);

echo json_encode(array("grp"=>$row_detail['grp'], "count"=>$row_cnt[0]));
exit;
?>