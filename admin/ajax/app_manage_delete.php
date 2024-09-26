<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);


    $query="delete from Gn_app_version WHERE seq='$seq'
                                 ";
    mysqli_query($self_con, $query);	

echo "<script>alert('삭제되었습니다.');location='/admin/app_manage_list.php';</script>";
exit;
?>