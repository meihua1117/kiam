<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 레벨 수정
*/
extract($_POST);

$seq = $_POST["seq"]; 
$mem_id = $_POST["mem_id"]; 
$service_type = $_POST["service_type"]; 
$result=0;

// 정보 확인
$sql="select * from Gn_Member_Business_Request where seq='$seq'";
$resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($resul);    

if($_POST['mode'] == "") {
    $result = -1;
    echo "{\"result\":\"$result\",\"msg\":\"정보가 없습니다.\"}";
    exit;
}

if($_POST['mem_id'] == "") {
    $result = -1;
    echo "{\"result\":\"$result\",\"msg\":\"아이디가 없습니다.\"}";
    exit;
}

if($_POST['share_per'] != "") {
    $addQuery .= ", share_per='".$_POST['share_per']."'";
}
if($_POST['balance_per'] != "") {
    $addQuery .= ", balance_per='".$_POST['balance_per']."'";
}
    
$sql="update Gn_Member set service_type='".$_POST['service_type']."' $addQuery where mem_id='$mem_id'";
mysqli_query($self_con,$sql);	
echo "{\"result\":\"$result\"}";
?>