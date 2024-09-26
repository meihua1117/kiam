<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$idx = $_POST["idx"]; 

    $query="delete from Gn_Iam_Name_Card WHERE idx='$idx'
                                 ";
    mysqli_query($self_con, $query);	

//echo "<script>alert('저장되었습니다.');location='/admin/card_click_list.php';</script>";
//exit;
?>