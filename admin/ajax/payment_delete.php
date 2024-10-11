<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 결제 정보 삭제
*/
extract($_POST);
$no_array = explode(",",$no);
for($i = 0 ; $i < count($no_array); $i++){
    $no = $no_array[$i];
    $query = "select * from tjd_pay_result where no='$no'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    if ($row[no] == "") exit;
    $query = "delete from tjd_pay_result where `no`='$no'";
    mysqli_query($self_con,$query);
}
echo "삭제되었습니다.";
exit;
?>