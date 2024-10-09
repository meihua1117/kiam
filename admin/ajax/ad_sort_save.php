<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$gaid = $_POST["gaid"]; 
for($i=0;$i<count($gaid);$i++) {
    $query="update      Gn_Ad set `sort_order`      ='$sort_order[$i]'
                         WHERE gaid='$gaid[$i]'
                                 ";
    mysql_query($query);	
    echo "<script>alert('저장되었습니다.');location='/admin/ad_list.php';</script>";
}
exit;
?>