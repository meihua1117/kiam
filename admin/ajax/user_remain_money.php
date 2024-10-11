<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 레벨 수정
*/
extract($_POST);

$mem_id = $_POST["mem_id"]; 
$remain_money = $_POST["remain_money"]; 
$result=0;

// 정보 확인
$sql="select * from Gn_Member where mem_id='$mem_id'";
$resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($resul);    

if($row[0] == "") {
    $result = -1;
    echo "<script>alert('정보가없습니다.');history.go(-1);</script>";
    exit;
}

if($_POST['remain_money'] == "") {
    $result = -1;
    echo "<script>alert('정보가없습니다.');history.go(-1);</script>";
    exit;
}


    $sql="update Gn_Member set remain_money='".$_POST['remain_money']."' 
                             where mem_id='$mem_id'";
    mysqli_query($self_con,$sql);	
    

echo "<script>alert('수정되었습니다.');location='/admin/member_manager_list.php';</script>";
exit;
?>