﻿<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
//print_r($_SESSION);
//print_R($_POST);
if($member_1['mem_id'] == "") {
    echo "<script>location.history(-1);</script>";
    exit;
}
/*
기본형 판매 수당 : 
1~100인 : 50%
101~1000인 : 60%
1001인 이상 : 70%

직원 판매수당 40%
대리점 소속일경우 (직판 50% , 직원판매시 10%) 5
지사 소속일경우 (직판 60% , 직원판매시 20%) 105
총판 소속일경우 (직판 70% , 직원판매시 30%) 1100

*/
$ORDER_NO = $_POST['allat_order_no'];
$pay_info['idx'] = $_POST['allat_order_no'];
$pay_info['orderNumber'] = $_POST['allat_order_no'];
$pay_info['VACT_InputName'] =$member_iam['mem_name'];
$pay_info['TotPrice'] = $_POST['allat_amt']; //금액
$pay_info['month_cnt'] = $_POST['month_cnt'];
$pay_info['end_status'] = "N";
$pay_info['buyertel'] = $member_iam['mem_phone']; //구매자 전화번호
$pay_info['buyeremail'] = $member_iam['mem_email']; //구매자 연락처
$pay_info['payMethod'] = "BANK";
$pay_info['buyer_id'] = $member_iam['mem_id'];
$pay_info['member_type'] = $_POST['member_type'];
$pay_info['phone_cnt'] = $pay_info['max_cnt'] =$_POST['phone_cnt']; // 추가갯수
$pay_info['db_cnt'] = $_POST['db_cnt'];
$pay_info['email_cnt'] = $_POST['email_cnt'];
$pay_info['shop_cnt'] = $_POST['shop_cnt'];
$pay_info['fujia_status'] = "N"; 
$pay_info['resultCode'] = "00";
$pay_info['resultMsg'] = "무통장입금요청";
$pay_info['pc_mobile'] = "A"; //금액
$pay_info['add_opt'] = $_POST['add_opt'];
$pay_info['add_phone'] = $_POST['add_phone'];
$pay_info['onestep1'] = $_POST['onestep1'];
$pay_info['onestep2'] = $_POST['onestep2'];    
$last_time = date("Y-m-d H:i:s", strtotime("+{$pay_info['month_cnt']} month"));
$pay_info['end_date'] = $last_time;    
$sql="insert into tjd_pay_result set ";
foreach($pay_info as $key=>$v)
{
    $sql.=" $key = '$v' , ";
}
$sql.=" date=now() ";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $no = mysqli_insert_id($self_con);

$sql="select * from tjd_pay_result where orderNumber='{$ORDER_NO}' and buyer_id='{$member_1['mem_id']}' ";
$resul=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
$row=mysqli_fetch_array($resul);	
/*
기본형 판매 수당 : 
1~100인 : 50%
101~1000인 : 60%
1001인 이상 : 70%

직원 판매수당 40%
대리점 소속일경우 (직판 50% , 직원판매시 10%) 5
지사 소속일경우 (직판 60% , 직원판매시 20%) 105
총판 소속일경우 (직판 70% , 직원판매시 30%) 1100
*/
$sql="select * from Gn_Member where mem_id='{$member_1['mem_id']}' ";
$sresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
$srow=mysqli_fetch_array($sresult);	

$sql="select * from crawler_member_real where user_id='{$member_1['mem_id']}' ";
$sresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
$crow=mysqli_fetch_array($sresult);	        
if($crow[0] == "") {
    $user_id=$srow['mem_id'];
    $user_name=$srow['mem_name'];
    $password=$srow['mem_pass'];
    $cell=$srow['mem_phone'];
    $email=$srow['mem_email'];
    $address=$srow['mem_add1'];
    $status="N";
    $use_cnt = $_POST['db_cnt'];
    $search_email_date = substr($last_time,0,10);
    $search_email_cnt = $_POST['email_cnt'];
    $term = substr($last_time,0,10);
    $query = "insert into crawler_member_real set user_id='{$user_id}',
                                        user_name='{$user_name}',
                                        password='{$password}',
                                        cell='{$cell}',
                                        email='{$email}',
                                        address='{$address}',
                                        term='{$term}',
                                        status='N',
                                        use_cnt='{$use_cnt}',
                                        regdate=NOW(),
                                        search_email_yn='N',
                                        search_email_date='{$search_email_date}',
                                        search_email_cnt='{$search_email_cnt}',
                                        shopping_end_date='{$search_email_date}'";
        mysqli_query($self_con,$query);
}

if($srow['recommend_id'] != "") {
    $sql="select * from Gn_Member where mem_id='{$srow['recommend_id']}' ";
    $rresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));

    if(mysqli_num_rows($rresult) > 0) {
        $rrow=mysqli_fetch_array($rresult);	    	
        $addQuery = "";
        $branch_share_per = 0;
        // 리셀러 / 분양 회원 확인
        // 리셀러 회원인경우 분양회원 아이디 확인
        if($rrow['service_type'] == 2) {
            // 추천인의 추천인 검색 및 등급 확인
            $sql="select * from Gn_Member where mem_id='{$rrow['recommend_id']}'";
            $rresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
            $trow=mysqli_fetch_array($rresult);
            $share_per = $recommend_per = $rrow['share_per']?$rrow['share_per']:30;
            if($trow[0] !="") {
                $recommend_per = $trow['share_per']?$trow['share_per']:50;
                $branch_share_per = $recommend_per - $share_per;
                $branch_share_id = $trow['mem_id'];
            }
        } else if($rrow['service_type'] == 3) {
            $share_per = $recommend_per = $rrow['share_per']?$rrow['share_per']:50;
            $branch_share_per = 0;
        }
        
        $sql = "update tjd_pay_result set share_per='{$share_per}', branch_share_per = '{$branch_share_per}', share_id='{$srow['recommend_id']}', branch_share_id='{$branch_share_id}' where no='{$no}'";
        mysqli_query($self_con,$sql)or die(mysqli_error($self_con));			
    }
} 
echo 1;
?>
