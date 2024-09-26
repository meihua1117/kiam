<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$idx = $_POST["idx"]; 
if($_POST['mode'] == "sort") {
    for($i = 0; $i< count($no);$i++) {
        $query="update      tjd_board set `sort_order`          ='$sort_order[$i]'
                             WHERE no='$no[$i]'";
                                    
        mysqli_query($self_con, $query);	
    }
} else if($_POST['mode'] == "diber") { 
        $query="update      tjd_board set `diber`          ='$diber'
                             WHERE no='$no'";
                                    
        echo $query."<BR>";
        mysqli_query($self_con, $query);	    
}
echo "<script>alert('저장되었습니다.');location='/admin/faq_list.php';</script>";
exit;
?>