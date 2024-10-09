<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 레벨 수정
*/
extract($_POST);
extract($_GET);
if($mode == "update_status") {
    $lecture_id = $_POST["lecture_id"]; 
    $mem_id = $_POST["mem_id"]; 
    $status = $_POST["status"]; 

    // 정보 확인
    $sql="select * from Gn_lecture where lecture_id='$lecture_id'";
    $resul=mysql_query($sql) or die(mysql_error());
    $row=mysql_fetch_array($resul);    


    if($_POST['lecture_id']) { 
        
        $sql="update Gn_lecture set status='".$_POST['status']."' 
                                 where lecture_id='$lecture_id'";
        mysql_query($sql);	
        
        
    }
} else if($mode == "review_save") {
    $lecture_day = implode(",",$lecture_day);
	$sql = "insert into Gn_review set lecture_id='$lecture_id',
	                                   score='$score',
	                                   content='$content',
	                                   profile='$profile', 
	                                   mem_id='$_SESSION[one_member_id]',
	                                   status='N',
	                                   regdate=NOW()
	       ";
	$result=mysql_query($sql);    
	echo "<script>alert('등록되었습니다.');location='/admin/review_list.php';</script>";    
	exit;    
} else if($mode == "del") {
	$sql = "delete from Gn_review 
	                            where  review_id='$review_id'
	                                   
	       ";
	$result=mysql_query($sql);	    
	echo "<script>alert('삭제되었습니다.');location='/admin/review_list.php';</script>";    
    exit;
} else if($mode == "review_update") {
    $lecture_day = implode(",",$lecture_day);
	$sql = "update Gn_review set  
	                                   score='$score',
	                                   content='$content',
	                                   profile='$profile' 
	                            where  review_id='$review_id'
	                                   
	       ";
	$result=mysql_query($sql);	
	echo "<script>alert('수정되었습니다.');location='/admin/review_list.php';</script>";    
	exit;       
}

echo "{\"result\":\"$result\"}";
?>