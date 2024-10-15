<?
// header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";

$_POST['id'] =$_REQUEST['id'];
$_POST['num'] =$_REQUEST['num'];
$_POST['send_num'] =$_REQUEST['send_num'];
$_POST['sms'] =$_REQUEST['sms'];
$result=0;

$userId = $_POST["id"]; 
$token = $_POST['mem_token'];
$phone_num = $_POST['phone_num'];

if(!$token || !check_token($phone_num, $token)){
	$token_res = 0;
}
else{
	$recv_num_info = $num = $_POST["num"];      
	// 추후 발신자 전화번호 추가
	$send_num = $_POST["send_num"];
	$app_num='';
	$sms = $_POST["sms"];
	
	
	// Cooper Add 앱 콜 일어나는지 확인로그
	if($sms != "") {
		$sql = "insert into call_app_log (api_name, mem_id, send_num, recv_num, sms, regdate) values ('receive_sms', '$userId', '$send_num', '$recv_num_info', '$sms', now())";
		mysqli_query($self_con,$sql);
	}
	
	
	$enc = mb_detect_encoding($sms);
	if($userId)
	{
	$sql="insert into tjd_receive set id='$userId', app_num='', num='$num' , enc='$enc' , sms='$sms' , date=now()";
	mysqli_query($self_con,$sql);
	}
	if($enc == "UTF-8")
	{
		$msg=$sms;
		if(strpos($msg,"번호변경")||strpos($msg,"번호로 번호 변경"))
			$sql_flag = " msg_flag = '1'";
		else if(strpos($msg,"없는 가입자")||strpos($msg,"유효하지 않은 번호") || strpos($msg,"지원하지 않는 휴대폰") || strpos($msg,"수신할 수 없는") || strpos($msg,"전송실패") || strpos($msg,"전송이 실패") || strpos($msg,"수신번호가 정확하지"))
			$sql_flag = " msg_flag = '2'";
		else if(strpos($msg,"전송이 정상적으로 이루어지지 않았습니다"))
			$sql_flag = " msg_flag = '3'";
	}
	else if($enc == "ASCII")
	{
		$msg = iconv("ASCII","utf-8",$sms);
		if(strpos($msg,"번호변경")||strpos($msg,"번호로 번호 변경"))
			$sql_flag = " msg_flag = '1'";
		else if(strpos($msg,"없는 가입자")||strpos($msg,"유효하지 않은 번호") || strpos($msg,"지원하지 않는 휴대폰") || strpos($msg,"수신할 수 없는") || strpos($msg,"전송실패") || strpos($msg,"전송이 실패") || strpos($msg,"수신번호가 정확하지"))
			$sql_flag = " msg_flag = '2'";
		else if(strpos($msg,"전송이 정상적으로 이루어지지 않았습니다"))
			$sql_flag = " msg_flag = '3'";
	}
	preg_match_all("/[0][0-9]{9,10}/",$msg,$recv_num,PREG_SET_ORDER);
	if($sql_flag)
	{
		$ori_num=$recv_num[0][0];
		$chg_num=$recv_num[1][0];
		//$sql_g="select grp_id from Gn_MMS_Receive where recv_num='{$ori_num}' limit 0,1 ";
		$sql_g="select grp_id from Gn_MMS_Receive where recv_num='{$ori_num}' order by reg_date desc limit 0,1 ";
		$resul_g=mysqli_query($self_con,$sql_g) or die(mysqli_error($self_con));
		$row_g=mysqli_fetch_array($resul_g);
		if($row_g['grp_id'])
		{
		$sql_g2="select grp from Gn_MMS_Group where idx='{$row_g['grp_id']}' ";
		$resul_g2=mysqli_query($self_con,$sql_g2) or die(mysqli_error($self_con));
		$row_g2=mysqli_fetch_array($resul_g2);
		}
		
		$ori_num = $ori_num==""?$recv_num_info:$ori_num;
		
		// 그룹명 찾기 추가 Cooper Add
		$sql_sg = "select mem_id, send_num, recv_num, grp_name, reg_date from Gn_MMS_Send_Log where recv_num='{$ori_num}' order by reg_date desc limit 0,1";
		$resul_sg=mysqli_query($self_con,$sql_sg) or die(mysqli_error($self_con));
		$row_sg=mysqli_fetch_array($resul_sg);	
		if($row_sg[0] != "") {
			$row_g2['grp'] = $row_sg['grp_name'];
		}	
		
		$sql_s="select seq from sm_log where {$sql_flag} and ori_num='{$ori_num}' and mem_id='{$userId}' ";
		$resul_s=mysqli_query($self_con,$sql_s) or die(mysqli_error($self_con));
		$row_s=mysqli_fetch_array($resul_s);
		
		// 추후 발신자 전화번호 추가
		if($send_num) $num = $send_num;
		
		
		if($row_s['seq']) {
			$sql="update sm_log set mem_id='{$userId}', dest='{$num}' , msg_text='{$msg}' , reservation_time=now() , chg_num='{$chg_num}' , grp_name='{$row_g2['grp']}' where seq='{$row_s['seq']}' ";
			
			// Cooper Add 자동 변경 내역 저장 chg_num이 있을경우
			if($chg_num != "" ) {
				if(cell_change_log ($chg_num, $ori_num) === true) {
					// 저장
					$chg_result = true;
				}		
			}    	
			
			/* 2016-03-25 추가 cooper add*/
			$sql_s="select idx from Gn_MMS_Deny where mem_id='$userId' and recv_num='$ori_num' and send_num='$num' ";
			$resul_s=mysqli_query($self_con,$sql_s);
			$row_s=mysqli_fetch_array($resul_s);
			if($row_s['idx'] == "" && $num != $ori_num) {
				$sql_insert = "insert into Gn_MMS_Deny set send_num='$num',
														   recv_num='$ori_num',
														   reg_date=now(),
														   mem_id='$userId',
														   title='번호 변경 및 수신 불가',
														   content='$msg',
														   status='A',
														   up_date=now() ";
				 //mysqli_query($self_con,$sql_insert);
			}
			
		} else {
			$sql = "insert into sm_log set mem_id='{$userId}', dest = '{$num}', msg_text = '{$msg}' , {$sql_flag} , reservation_time = now(), ori_num = '{$ori_num}', chg_num = '{$chg_num}' , result='{$result}', grp_name='{$row_g2['grp']}' ";
	
			// Cooper Add 자동 변경 내역 저장 chg_num이 있을경우
			if($chg_num != "" ) {
				if(cell_change_log ($chg_num, $ori_num) === true) {
					// 저장
					$chg_result = true;
				}		
			}    	
			
			/* 2016-03-25 추가 cooper add*/
			$sql_s="select idx from Gn_MMS_Deny where mem_id='$userId' and recv_num='$ori_num' and send_num='$num' ";
			$resul_s=mysqli_query($self_con,$sql_s);
			$row_s=mysqli_fetch_array($resul_s);
			if($row_s['idx'] == "" && $num != $ori_num) {
				$sql_insert = "insert into Gn_MMS_Deny set send_num='$num',
														   recv_num='$ori_num',
														   reg_date=now(),
														   mem_id='$userId',
														   title='번호 변경 및 수신 불가',
														   content='$msg',
														   status='A',
														   up_date=now() ";
				 //mysqli_query($self_con,$sql_insert);
			}        
		}
			
		mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
	}
	
	$sql="select * from tjd_filter_rslt where SEND_NUMBER='$recv_num_info' and RECEIVE_NUMBER='$send_num'  and SUCCESS_YN='N' order by REG_DATE DESC";
	$result_=mysqli_query($self_con,$sql);
	$row=mysqli_fetch_array($result_);
	if($row[0] != "") {
		$sql="update tjd_filter_rslt set SUCCESS_YN='Y',UPD_DATE=NOW() where RECEIVE_NUMBER='$send_num' and GROUP_SPAM='$row[GROUP_SPAM]' and SUCCESS_YN='N' ";
		$result_=mysqli_query($self_con,$sql);
			
	
		$mysql_host = 'localhost';
		$mysql_user = 'root';
		$mysql_password = 'onlyone123$%^';
		$mysql_db = 'sms';
		$con=mysqli_connect($mysql_host, $mysql_user,$mysql_password,$mysql_db) ;
	/*    
		$sql="select * from RECEIVE_NUMBER where RECEIVE_NUMBER='$recv_num_info' and GROUP_SPAM='$row[GROUP_SPAM]'   and SUCCESS_YN='O' ";
		//echo $sql."<BR>";
		$res=mysqli_query($con, $sql);
		$srow=mysqli_fetch_array($res);
		//print_r($srow);
	*/
	
	
		$sql="update TS_FILTER_RSLT set SUCCESS_YN='Y' where RECEIVE_NUMBER='$send_num' and GROUP_SPAM='$row[GROUP_SPAM]' and SUCCESS_YN='O' ";
		$result_=mysqli_query($con, $sql);
		
	}
	
	preg_match('/(01[016789])([0-9]{3,4})([0-9]{4})/', $send_num, $match);
	$phone_num =  $match[0];
	if(strlen($phone_num) > 0)
	{
		$time = date("Y-m-d H:i:s");
		$sql="select idx from call_api_log where phone_num='$phone_num'";
		$res=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
		$row=mysqli_fetch_array($res);
		if($row['idx'] != "") {
			$sql="update call_api_log set receive_sms='$time' where idx='{$row['idx']}'";
			mysqli_query($self_con,$sql);	
		}
		else{
			$sql ="insert into call_api_log set receive_sms='$time', phone_num='$phone_num'";
			mysqli_query($self_con,$sql);	
		}
	}	
	$token_res = 1;
}

echo json_encode(array("result"=>$result,"token_res"=>$token_res));
?>