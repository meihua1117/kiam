<?
$path="./";
include_once "_head.php";
if(!$_SESSION['one_member_id'] || !$_SESSION['form_submit'])
{
?>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}

if($_SESSION['form_submit'])
{

    if($_POST['add_phone'] == "0" && $_SESSION['INI_PRICE'] == "594000")  {
        $_POST['add_phone'] = 10;    
        $_POST['phone_cnt'] = 90000;
        $_POST['max_cnt'] =  90000;
        
    }
     if($_POST['db_cnt'] > 0) {
        $_POST['max_cnt'] = $_POST['phone_cnt'] * 9000;
     } else {
        if($_POST['payment_yn'] != "") {

            if($_POST['payment_yn'] == "BA") {
                $_POST['member_type'] = '예약형';
                $_POST['add_phone'] = 5;    
                $_POST['phone_cnt'] = 45000;
                $_POST['db_cnt'] = 2500;
                $_POST['max_cnt'] =  45000;
                $_POST['month_cnt'] = $_POST['money_type'] = 2;    
            } else if($_POST['payment_yn'] == "ST") {
                $_POST['add_phone'] = 3;    
                $_POST['phone_cnt'] = 27000;
                $_POST['max_cnt'] =  27000;
                $_POST['month_cnt'] = $_POST['money_type'] = 1;    
            } else if($_POST['payment_yn'] == "BU") {
                $_POST['add_phone'] = 5;    
                $_POST['phone_cnt'] = 45000;
                $_POST['db_cnt'] = 2500;
                $_POST['max_cnt'] =  45000;
                $_POST['month_cnt'] = $_POST['money_type'] = 1;    
            } else if($_POST['payment_yn'] == "BY") {
                $_POST['add_phone'] = 5;    
                $_POST['phone_cnt'] = 45000;
                $_POST['db_cnt'] = 2500;
                $_POST['max_cnt'] =  45000;
                $_POST['month_cnt'] = $_POST['money_type'] = 12;                
            } else if($_POST['payment_yn'] == "BS") {
                $_POST['add_phone'] = 10;    
                $_POST['phone_cnt'] = 90000;
                $_POST['db_cnt'] = 10000;
                $_POST['max_cnt'] =  90000;
                $_POST['month_cnt'] = $_POST['money_type'] = 36;    
            } else if($_POST['payment_yn'] == "BC") {
                $_POST['add_phone'] = 10;    
                $_POST['phone_cnt'] = 90000;
                $_POST['db_cnt'] = 10000;
                $_POST['max_cnt'] =  90000;
                $_POST['month_cnt'] = $_POST['money_type'] = 36;    
            } else if($_POST['payment_yn'] == "BJ") {
                $_POST['add_phone'] = 1;    
                $_POST['phone_cnt'] = 9000;
                $_POST['db_cnt'] = 1500;
                $_POST['max_cnt'] =  9000;
                $_POST['month_cnt'] = $_POST['money_type'] = 1;                
            }
        }
    }
            
    $_SESSION["form_submit"] = "";
	//session_unregister("form_submit");
	//echo "test";
	require("inipay/libs/INILib.php");
	$inipay = new INIpay50;
	$inipay->SetField("inipayhome", $_SERVER['DOCUMENT_ROOT']."/inipay");       // 이니페이 홈디렉터리(상점수정 필요)
	$inipay->SetField("type", "securepay");                         // 고정 (절대 수정 불가)
	$inipay->SetField("pgid", "INIphp".$pgid);                      // 고정 (절대 수정 불가)
	$inipay->SetField("subpgip","203.238.3.10");                    // 고정 (절대 수정 불가)
	$inipay->SetField("admin", $_SESSION['INI_ADMIN']);    // 키패스워드(상점아이디에 따라 변경)
	$inipay->SetField("debug", "true");                             // 로그모드("true"로 설정하면 상세로그가 생성됨.)
	$inipay->SetField("uid", $uid);                                 // INIpay User ID (절대 수정 불가)
	$inipay->SetField("goodname", iconv("utf-8","euc-kr",$goodname));                       // 상품명 
	$inipay->SetField("currency", $currency);                       // 화폐단위
	
	$inipay->SetField("mid", $_SESSION['INI_MID']);        // 상점아이디
	$inipay->SetField("rn", $_SESSION['INI_RN']);          // 웹페이지 위변조용 RN값
	$inipay->SetField("price", $_SESSION['INI_PRICE']);		// 가격
	$inipay->SetField("enctype", $_SESSION['INI_ENCTYPE']);// 고정 (절대 수정 불가)
	
	$inipay->SetField("buyername", iconv("utf-8","euc-kr",$buyername));       // 구매자 명
	$inipay->SetField("buyertel",  $buyertel);        // 구매자 연락처(휴대폰 번호 또는 유선전화번호)
	$inipay->SetField("buyeremail",$buyeremail);      // 구매자 이메일 주소
	$inipay->SetField("paymethod", $paymethod);       // 지불방법 (절대 수정 불가)
	$inipay->SetField("encrypted", $encrypted);       // 암호문
	$inipay->SetField("sessionkey",$sessionkey);      // 암호문
	$inipay->SetField("url", "http://www.kiam.kr"); // 실제 서비스되는 상점 SITE URL로 변경할것
	$inipay->SetField("cardcode", $cardcode);         // 카드코드 리턴
	$inipay->SetField("parentemail", $parentemail);   // 보호자 이메일 주소(핸드폰 , 전화결제시에 14세 미만의 고객이 결제하면  부모 이메일로 결제 내용통보 의무, 다른결제 수단 사용시에 삭제 가능)
	
	$inipay->SetField("recvname",$recvname);	// 수취인 명
	$inipay->SetField("recvtel",$recvtel);		// 수취인 연락처
	$inipay->SetField("recvaddr",$recvaddr);	// 수취인 주소
	$inipay->SetField("recvpostnum",$recvpostnum);  // 수취인 우편번호
	$inipay->SetField("recvmsg",$recvmsg);		// 전달 메세지
	
	$inipay->SetField("joincard",$joincard);  // 제휴카드코드
	$inipay->SetField("joinexpire",$joinexpire);    // 제휴카드유효기간
	$inipay->SetField("id_customer",$id_customer);    //user_id
	$inipay->startAction();
	
	if($inipay->GetResult('ResultCode')=="00")
	{
	        if($_POST['phone_cnt'] == "")
	            $_POST['max_cnt'] = $_POST['phone_cnt'] = $_POST['add_phone'] * 9000;
	        $pay_info['add_opt']=$_POST['add_opt'];//기부폰개수
			$pay_info['phone_cnt']=$_POST['phone_cnt'];//기부폰개수
			$pay_info['month_cnt']=$_POST['month_cnt'];//결제개월수
			
			$pay_info['max_cnt']=$_POST['max_cnt'];//결제갯수
			$pay_info['add_service']=$_POST['add_service'];//결제개월수
			if($_POST['fujia_status'])
			$pay_info['fujia_status']="Y";//부가서비스
			$pay_info['buyertel']=$_POST['buyertel'];//전화번호
			$pay_info['buyeremail']=$_POST['buyeremail'];//이메일					
			$pay_info['resultCode']=$inipay->GetResult('ResultCode');//승인결과코드
			$pay_info['resultMsg']=iconv("euc-kr","utf-8",$inipay->GetResult('ResultMsg'));//결과메시지
			$pay_info['payMethod']=$inipay->GetResult('PayMethod');//지불수단					
			$pay_info['orderNumber']=$inipay->GetResult('MOID');//주문번호
			$pay_info['tid']=$inipay->GetResult('TID');//TID
			$pay_info['TotPrice']=$inipay->GetResult('TotPrice');//승인금액	
			$pay_info['applDate']=$inipay->GetResult('ApplDate');//승인일
			$pay_info['applTime']=$inipay->GetResult('ApplTime');//승인시각
			$pay_info['mid']=$inipay->GetResult('MID');//상점ID
			$pay_info['VACT_InputName']=$member_1['mem_name'];//구매자명
			$pay_info['buyer_id']=$member_1['mem_id'];
			$pay_info['ApplNum']=$inipay->GetResult('ApplNum');//승인번호
			$pay_info['CARD_Quota']=$inipay->GetResult('CARD_Quota');//할부개월
			$pay_info['CARD_Code']=$inipay->GetResult('CARD_Code');//카드코드
			$pay_info['CARD_BankCode']=$inipay->GetResult('CARD_BankCode');//발급사코드
			$pay_info['OCB_Num']=$inipay->GetResult('OCB_Num');//카드번호
			$pay_info['VACT_Num']=$inipay->GetResult('VACT_Num');//가상계좌번호
			$pay_info['VACT_Date']=$inipay->GetResult('VACT_Date');//입금예정일
			$pay_info['VACT_Time']=$inipay->GetResult('VACT_Time');//입금예정일
			$pay_info['VACT_Name']=iconv("euc-kr","utf-8",$inipay->GetResult('VACT_Name'));//	예금주
			$pay_info['VACT_BankCode']=$inipay->GetResult('VACT_BankCode');//은행코드
			$pay_info['pc_mobile']="A";
			$pay_info['end_status']="Y";
			
            $pay_info['db_cnt'] = $_POST['db_cnt'];
            $pay_info['email_cnt'] = $_POST['email_cnt'];
            $pay_info['onestep1'] = $_POST['onestep1'];
            $pay_info['onestep2'] = $_POST['onestep2'];

			if($_POST['month_cnt'] == 1) 
			    $pay_info['member_type'] = "일반결제-월간타입";
			else
    			$pay_info['member_type'] = "일반결제-연간타입";
			
			if($_POST['pay_ex_end_date'] && $_POST['pay_ex_no'])
			{
				$pay_info['cancel_ResultCode']="";//취소코드
				$pay_info['cancel_ResultMsg']="";//취소메시지
				$pay_info['cancel_CancelDate']="";//취소일
				$pay_info['cancel_CancelTime']="";//취소시각
				$pay_info['cancel_CSHR_CancelNum']="";//현금영수증 취소 승인번호(현금영수증 발급 취소시에만 리턴됨)
				$pay_info['cancel_status']="N";				
				$sql="update tjd_pay_result set ";
				foreach($pay_info as $key=>$v)
				{
					$sql.=" $key = '$v' , ";
				}
				$sql.=" end_date=date_add(now(),INTERVAL {$_POST['month_cnt']} month) , date=now() where no='{$_POST['pay_ex_no']}',add_phone='{$_POST['add_phone']}' where ";
				
				
				$sql_num_up="update Gn_MMS_Number set end_status='Y' , end_date=date_add(now(),INTERVAL {$_POST['month_cnt']} month) where end_date = '{$_POST['pay_ex_end_date']}' and mem_id='{$member_1['mem_id']}' ";
				mysqli_query($self_con,$sql_num_up) or die(mysqli_error($self_con));				
				//$sql_back="insert into tjd_pay_result_back (select * from tjd_pay_result where no='{$_POST['pay_ex_no']}' )";
				//mysqli_query($self_con,$sql_back) or die(mysqli_error($self_con));
			}
			else
			{
				$sql="insert into tjd_pay_result set ";
				foreach($pay_info as $key=>$v)
				{
					$sql.=" $key = '$v' , ";
				}
				$sql.=" end_date=date_add(now(),INTERVAL {$_POST['month_cnt']} month) , date=now(),add_phone='{$_POST['add_phone']}' ";
				mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
				$no = mysqli_insert_id($self_con);
			}
			
			
			
			//if($_POST['fujia_status'])
			//{
						
			$sql_m="update Gn_Member set fujia_date1=now() , fujia_date2=date_add(now(),INTERVAL {$_POST['month_cnt']} month)  where mem_id='{$member_1['mem_id']}' ";
			mysqli_query($self_con,$sql_m)or die(mysqli_error($self_con));
			//print_r($_POST);
			$sql_m="update Gn_Member set   phone_cnt=phone_cnt+'{$_POST['add_phone']}' where mem_id='{$member_1['mem_id']}' ";
			//echo $sql_m;
			
			mysqli_query($self_con,$sql_m)or die(mysqli_error($self_con));			
			

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
            // 등급에 따른 recommend_type 설정
            $sql="select * from Gn_Member where mem_id='{$member_1['mem_id']}' ";
            $sresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
            $srow=mysqli_fetch_array($sresult);	

            
            $sql="select * from crawler_member_real where user_id='{$member_1['mem_id']}' ";
            $sresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
            $crow=mysqli_fetch_array($sresult);	        
            $user_id=$srow['mem_id'];
            $user_name=$srow['mem_name'];
            $password=$srow['mem_pass'];
            $cell=$srow['mem_phone'];
            $email=$srow['mem_email'];
            $address=$srow['mem_add1'];
            $status="Y";
            $use_cnt = $_POST['db_cnt'];
            $last_time = date("Y-m-d H:i:s", "+{$_POST['month_cnt']} month");
            $search_email_date = substr($last_time,0,10);
            $search_email_cnt = $_POST['email_cnt'];
            $term = substr($last_time,0,10);
                            
            if($crow[0] == "") {

                $query = "insert into crawler_member_real set user_id='{$user_id}',
                                                    user_name='{$user_name}',
                                                    password='{$password}',
                                                    cell='{$cell}',
                                                    email='{$email}',
                                                    address='{$address}',
                                                    term='{$term}',
                                                    status='{$status}',
                                                    use_cnt='{$use_cnt}',
                                                    regdate=NOW(),
                                                    search_email_yn='Y',
                                                    search_email_date='{$search_email_date}',
                                                    search_email_cnt='{$search_email_cnt}',
                                                    shopping_end_date='{$search_email_date}'";
                 mysqli_query($self_con,$query);
            } else {
                $query = "update crawler_member_real set 
                                                    cell='{$cell}',
                                                    email='{$email}',
                                                    address='{$address}',
                                                    term='{$term}',
                                                    status='{$status}',
                                                    use_cnt='{$use_cnt}',
                                                    monthly_cnt = 0,
                                                    total_cnt = 0,                                                    
                                                    regdate=NOW(),
                                                    search_email_yn='Y',
                                                    search_email_date='{$search_email_date}',
                                                    search_email_cnt='{$search_email_cnt}',
                                                    shopping_yn='N',
                                                    shopping_end_date='{$search_email_date}',
                                                    status='Y'
                                                    where user_id='{$user_id}'
                                                    ";
                 mysqli_query($self_con,$query);                
            }
             
            // 등급에 따른 recommend_type 설정
            if($srow['recommend_id'] != "") {
                $sql="select * from Gn_Member where mem_id='{$srow['recommend_id']}' and service_type > 0";
                $rresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
                $rrow=mysqli_fetch_array($rresult);	    	
                $branch_share_id = "";
                if($rrow['service_type'] > 0) {
                    $addQuery = "";
                    $branch_share_per = 0;
                    // 결제 추천인수 
                    //$sql="select count(*) cnt from Gn_Member a
                    //               left join tjd_pay_result b
                    //                 on a.mem_id = b.buyer_id
                    //              where recommend_id='{$srow['recommend_id']}' and end_status='Y' $addQuery";
                    //$rresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
                    //$ttrow=mysqli_fetch_array($rresult);
                    
                    // 추천인의 추천인 검색 및 등급 확인
                    $sql="select * from Gn_Member where mem_id='{$rrow['recommend_id']}'";
                    $rresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
                    $trow=mysqli_fetch_array($rresult);
                    
                    // 리셀러 / 분양 회원 확인
                    // 리셀러 회원인경우 분양회원 아이디 확인

                    
                    if($rrow['service_type'] == 1) {
                        $share_per = $recommend_per = $rrow['share_per']?$rrow['share_per']:30;
                        if($trow[0] !="") {
                            $recommend_per = $trow['share_per']?$trow['share_per']:50;
                            $branch_share_per = $recommend_per - $share_per;
                            $branch_share_id = $trow['mem_id'];
                        }
                    } else if($rrow['service_type'] == 3) {
                        $share_per = $recommend_per = $rrow['share_per']?$rrow['share_per']:50;
                        $branch_share_per = 0;
                        //$recommend_type = 50;
                     }
                    
                	$sql_m="update Gn_Member set   recommend_type='{$recommend_type}' where mem_id='{$member_1['mem_id']}' ";
                	mysqli_query($self_con,$sql_m)or die(mysqli_error($self_con));			
                	
                	//$share_per = $recommend_type;
                	
                	$sql = "update tjd_pay_result set share_per='{$share_per}', branch_share_per = '{$branch_share_per}', share_id='{$srow['recommend_id']}', branch_share_id='{$branch_share_id}' where no='{$no}'";
                	mysqli_query($self_con,$sql)or die(mysqli_error($self_con));			
                }
                
            } else {
                //if($member_type=="기본문자" || $member_type=="Personal-월간결제" || $member_type=="Personal-년간결제") {
                //	$sql = "update tjd_pay_result set share_per='50', branch_share_per = '0' where no='{$no}'";
                //	mysqli_query($self_con,$sql)or die(mysqli_error($self_con));			        
                //}
            }
	}	
}
$sql="select * from tjd_pay_result where orderNumber='{$inipay->GetResult('MOID')}' and buyer_id='{$member_1['mem_id']}' ";
$resul=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
$row=mysqli_fetch_array($resul);	
?>
<div class="big_main">
	<div class="big_1">
    	<div class="m_div">
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="pay_return.php">결제결과</a>
            </div>
            <div class="right_sub_menu">&nbsp;</div>
            <p style="clear:both;"></p>
    	</div>
   </div>
   <div class="m_div">
   		<div><img src="images/sub_02_visual_03.jpg" /></div>
       <div class="pay">      		
            <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                <td colspan="2" style="font-size:16px;">
                PG log</td>
                </tr>
                    <tr>
                    <td colspan="2" style="text-align:center;">
                    <h3><?
                    if($row['resultCode']=="0000")
                    {
                        if($row['payMethod']=="VBank")
                        echo "입금예정시간내로 아래 가상계좌로 입금하시면 구매가 완료됩니다.";
                        else
                        echo "결제가 성공적으로 이루어졌습니다.";
                    }
                    else
                        echo "결제실패하였습니다.다시 시도하시거나 홈페이지 관리자에 문의하세요.";
                    ?></h3></td>
                    </tr>
                <tr>
                <td>결과코드</td>
                <td><?=$row['resultCode']?></td>
                </tr>                    
                <tr>
                <td>결과메시지</td>
                <td><?=iconv("euc-kr","utf-8",$inipay->GetResult('ResultMsg'))?></td>
                </tr>
                <tr>
                <td>TID</td>
                <td><?=$row['tid']?></td>
                </tr>                                                    
                <tr>
                <td>지불수단</td>
                <td><?=$row['payMethod']?></td>
                </tr>
                <tr>
                <td>주문번호</td>
                <td><?=$row['orderNumber']?></td>
                </tr>                    
                <tr>
                <tr>
                <td>구매자명</td>
                <td><?=$row['VACT_InputName']?></td>
                </tr>                                        
                <td>지불금액</td>
                <td><?=$row['TotPrice']?></td>
                </tr>
                <tr>
                <td>지불시간</td>
                <td><?=$row['applDate']?><?=$row['applTime']?></td>
                </tr>
                <?
                if($row['payMethod']=="VBank")
                {
                    ?>                       
                    <tr>
                    <td>예금주</td>
                    <td><?=$row['VACT_Name']?></td>
                    </tr>
                    <tr>
                    <td>은행코드</td>
                    <td><?=$row['VACT_BankCode']?></td>
                    </tr>
                    <tr>
                    <td>가상계좌번호</td>
                    <td><?=$row['VACT_Num']?></td>
                    </tr>
                    <tr>
                    <td>입금예정시간</td>
                    <td><?=$row['VACT_Date']?></td>
                    </tr>
                    <?
                }
                ?>
                <tr>
                	<td colspan="2" style="text-align:center;">
                    	<input type="button" value="메인으로" onclick="location.replace('/')"  />
                        &nbsp;&nbsp;&nbsp;
                    	<input type="button" value="결제다시하기" onclick="location.replace('pay.php')"  />                        
                    </td>
                </tr>
            </table>
       </div>
   </div>
</div>
<?
include_once "_foot.php";
?>
