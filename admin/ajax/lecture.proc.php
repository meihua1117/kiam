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
    $resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row=mysqli_fetch_array($resul);    


    if($_POST['lecture_id']) { 
        
        $sql="update Gn_lecture set status='".$_POST['status']."' 
                                 where lecture_id='$lecture_id'";
        mysqli_query($self_con,$sql);	
        
        
    }
} else if($mode == "lecture_save") {
    $lecture_day = implode(",",$lecture_day);
	$sql = "insert into Gn_lecture set event_idx='$event_idx',
	                                   event_code='$event_code',
	                                   category='$category',
	                                   start_date='$start_date',
	                                   end_date='$end_date',
	                                   lecture_day='$lecture_day',
	                                   lecture_start_time='$lecture_start_time',
									   lecture_end_time='$lecture_end_time',
	                                   lecture_info='$lecture_info',
	                                   lecture_url='$lecture_url',
									   instructor='$instructor',
	                                   target='$target',
	                                   area='$area',
	                                   max_num='$max_num',
	                                   fee='$fee',
	                                   mem_id='{$_SESSION['one_member_id']}',
	                                   status='N',
	                                   regdate=NOW()
	       ";
	$result=mysqli_query($self_con,$sql);    
	echo "<script>alert('등록되었습니다.');location='/admin/lecture_list.php';</script>";    
	exit;    
} else if($mode == "lecture_update") {
    $lecture_day = implode(",",$lecture_day);
	$sql = "update  Gn_lecture set event_idx='$event_idx',
	                                   event_code='$event_code',
	                                   category='$category',
	                                   start_date='$start_date',
	                                   end_date='$end_date',
	                                   lecture_day='$lecture_day',
	                                   lecture_start_time='$lecture_start_time',
	                                   lecture_end_time='$lecture_end_time',
									   lecture_info='$lecture_info',
									   lecture_url='$lecture_url',						   
	                                   target='$target',
	                                   instructor='$instructor',
	                                   area='$area',
	                                   max_num='$max_num',
	                                   fee='$fee'
	                            where  lecture_id='$lecture_id'
	                                   
	       ";
	$result=mysqli_query($self_con,$sql);	
	echo "<script>alert('수정되었습니다.');location='/admin/lecture_list.php';</script>";    
	exit;       
} else if($mode == "del") {
	$sql = "delete from Gn_lecture 
	                            where  lecture_id='$lecture_id'
	                                   
	       ";
	$result=mysqli_query($self_con,$sql);	    
	echo "<script>alert('삭제되었습니다.');location='/admin/lecture_list.php';</script>";    
    exit;
}

echo "{\"result\":\"$result\"}";
?>