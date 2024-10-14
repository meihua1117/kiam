<?
include_once "../lib/rlatjd_fun.php";
//로그아웃

if($_POST['mode'] =="save") {
	$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."'";
	$sresul_num=mysqli_query($self_con,$sql);
	$data=mysqli_fetch_array($sresul_num);	    
 
	
	    // 사업 타입인지 확인
	    if($_REQUEST['type']) {
	        $service_want_type = $_REQUEST['type'];
	    }
	    else
	        $service_want_type = $data['service_type'] + 1;
	    

    	$sql="select * from Gn_Member_Business_Request  where mem_id='".$_SESSION['one_member_id']."' and service_want_type='$service_want_type'";
    	$result=mysqli_query($self_con,$sql);
    	$sdata=mysqli_fetch_array($result);	    
    	if($sdata['mem_id']) {
    	    echo '{"result":"fail","msg":"이미 신청 정보가 있습니다."}';
    	    exit;
    	}
    	
    		    
    	$sql="select * from Gn_Member  where mem_id='".$data['recommend_id']."'";
    	$sresul_num=mysqli_query($self_con,$sql);
    	$sdata=mysqli_fetch_array($sresul_num);	    
    	
    	
    	$query = "select count(*) cnt,sum(TotPrice) TotPrice  from tjd_pay_result a left join Gn_Member b on b.mem_id=a.buyer_id 
                          where recommend_id ='".$_SESSION['one_member_id']."' and end_status='Y'";
    	$result=mysqli_query($self_con,$query);
    	$total=mysqli_fetch_array($result);	                              
		    
	    $query = "insert into Gn_Member_Business_Request set mem_id='".$_SESSION['one_member_id']."',
	                                                         mem_name='{$data['mem_name']}',
	                                                         service_type='{$data['service_type']}',
	                                                         recommend_cnt='{$total['cnt']}',
	                                                         recommend_money='{$total['TotPrice']}',
	                                                         recommend_id='{$data['recommend_id']}',
	                                                         recommend_name='{$sdata['mem_name']}',
	                                                         service_want_type='$service_want_type',
	                                                         status='Y',
	                                                         status_date=now(),
	                                                         regdate=NOW()
	    ";
	    mysqli_query($self_con,$query);
	    
	    //$query = "update Gn_Member set service_type='$service_want_type' where mem_id='".$_SESSION['one_member_id']."' ";
	    //mysqli_query($self_con,$query);
	    
	    echo '{"result":"success","msg":"신청 되었습니다."}';
	    exit;	    
    
}
?>