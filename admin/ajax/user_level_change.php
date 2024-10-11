<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 레벨 수정
*/
extract($_POST);

$mem_code = $_POST["mem_code"]; 
$result=0;

// 정보 확인
$sql="select * from Gn_Member where mem_code='$mem_code'";
$resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($resul);    

if($_POST['mode'] == "") {
    $result = -1;
    echo "{\"result\":\"$result\"}";
    exit;
}

if($_POST['mem_leb'] == "") {
    $result = -1;
    echo "{\"result\":\"$result\"}";
    exit;
}

if($_POST['mem_code']) { 
    // 탈퇴
    $sql="update Gn_Member set mem_leb='".$_POST['mem_leb']."' 
                             where mem_code='$mem_code'";
    mysqli_query($self_con,$sql);	
    
}

echo "{\"result\":\"$result\"}";
?>