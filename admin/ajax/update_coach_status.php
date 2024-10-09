<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment :  코치정보 수정
*/
extract($_POST);


if($_POST["mode"] == "update_agreement"){

	$coach_id = $_POST["coach_id"]; 
	$status = $_POST["status"];

	$query="update gn_coach_apply set agree='$status' where coach_id='$coach_id' ";
	mysql_query($query);  

	if($status == "1"){
		$query="update gn_coach_apply set agree_date=now() where coach_id='$coach_id' ";

		mysql_query($query);  
	} 

}

if($_POST["mode"] == "update_coach_type"){

	$coach_id = $_POST["coach_id"]; 
	$coach_type = $_POST["coach_type"]; 

	$query="update gn_coach_apply set coach_type='$coach_type' where coach_id='$coach_id' ";
	mysql_query($query);  	
}

//코티 테이블 코티 승인
if($_POST["mode"] == "update_coaching_agree"){

	$coty_id = $_POST["coty_id"]; 
	$status = $_POST["status"];

	$query="update gn_coaching_apply set agree='$status' where coty_id='$coty_id' ";
	mysql_query($query);  

	if($status == "1"){
		$query="update gn_coaching_apply set agree_date=now() where coty_id='$coty_id' ";

		mysql_query($query);  
	} 	
}

//코칭정보 테이블 승인하기
if($_POST["mode"] == "update_coaching_info_agree"){

	$coaching_id = $_POST["coaching_id"]; 
	$status = $_POST["status"];

	$query="update gn_coaching_info set agree='$status' where coaching_id='$coaching_id' ";
	mysql_query($query);  

	if($status == "1"){
		$query="update gn_coaching_info set agree_date=now() where coaching_id='$coaching_id' ";

		mysql_query($query);  
	} 	
}

if($_POST["mode"] == "coty_update_coach_id"){

	$coty_id = $_POST["coty_id"]; 
	$coach_id = $_POST["coach_id"];

	$query="update gn_coaching_apply set coach_id='$coach_id' where coty_id='$coty_id' ";

	echo "<script>console.log('"+$query+"');</script>";
	mysql_query($query);  
}

else if($mode == "coaching_info_del"){


    $coaching_id = $_POST['coaching_id'];
    $sql = "delete from gn_coaching_info where coaching_id=$coaching_id";
    
    echo $sql;
    $result=mysql_query($sql);  



    // /echo "<script>alert('삭제되었습니다.');location='mypage_coaching_list.php';</script>";    
    exit;   
}
//echo "<script>alert('저장되었습니다."+$coach_no+"');location='/admin/member_manager_request_coach.php';</script>";
exit;
?>