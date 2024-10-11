<?
include_once "../lib/rlatjd_fun.php";
//로그아웃
if($_POST['mode'] =="save_sort") {
    
    $sendnum = $_POST['sendnum'];
    $sort_no = $_POST['sort_no'];
    
    $sendnum = explode(",", $sendnum);
    $sort_no = explode(",", $sort_no);
    
    for($i=0;$i < count($sendnum);$i++) {
	    $sql_num="update Gn_MMS_Number set sort_no='".$sort_no[$i]."' where mem_id ='$_SESSION[one_member_id]' and sendnum='".$sendnum[$i]."'";
	    //echo $sql_num."\n";
	    mysqli_query($self_con,$sql_num);
	    
	}
	?>
    	<script language="javascript">
        alert('처리되었습니다.');
        //location.reload();
        </script>
    <?	    
} else if($_POST['mode'] =="save_business") {
    $org_mem_code = explode(",", $_POST['org_mem_code']);
    $mem_code = explode(",",$_POST['mem_code']);    
    for($i=0;$i < count($org_mem_code);$i++) {
	    $sql_num="update Gn_Member set business_yn='N' where mem_code ='".$org_mem_code[$i]."'";
	    //echo $sql_num."\n";
	    mysqli_query($self_con,$sql_num);
	    
	}
    for($i=0;$i < count($mem_code);$i++) {
	    $sql_num="update Gn_Member set business_yn='Y' where mem_code ='".$mem_code[$i]."'";
	    echo $sql_num."\n";
	    mysqli_query($self_con,$sql_num);
	    
	}	
}
?>