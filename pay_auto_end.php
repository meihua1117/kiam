<?
$path="./";
include_once "_head.php";
if(!$_SESSION['one_member_id'] || !$_SESSION[form_submit])
{
?>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}
if($_POST[auto_pay_status]==2 && $_SESSION[form_submit])
{
	session_unregister("form_submit");
	require("INIbill41/sample/INIpay41Lib.php");
	$inipay = new INIpay41;
	$inipay->m_inipayHome = $_SERVER['DOCUMENT_ROOT']."/INIbill41"; 	// 이니페이 홈디렉터리
	$inipay->m_keyPw = "1111"; 			    // 키패스워드(상점아이디에 따라 변경)
	$inipay->m_type = "reqrealbill"; 		    // 고정 (절대 수정금지)
	$inipay->m_pgId = "INIpayBill"; 		    // 고정 (절대 수정금지)
	$inipay->m_payMethod = "Card";		    	    // 고정 (절대 수정금지)
	$inipay->m_billtype = "Card";		            // 고정 (절대 수정금지)
	$inipay->m_subPgIp = "203.238.3.10"; 		    // 고정 (절대 수정금지)
	$inipay->m_debug = "true"; 			    // 로그모드("true"로 설정하면 상세한 로그가 생성됨)
	$inipay->m_mid = $mid; 				    // 상점아이디
	$inipay->m_billKey = $billkey; 			    // billkey 입력
	$inipay->m_goodName = iconv("utf-8","euc-kr",$goodname); 		    // 상품명 (최대 40자)
	$inipay->m_currency = $currency; 		    // 화폐단위 
	$inipay->m_price = $price; 			    // 가격 
	$inipay->m_buyerName = iconv("utf-8","euc-kr",$buyername); 		    // 구매자 (최대 15자) 
	$inipay->m_buyerTel = $buyertel; 		    // 구매자이동전화 
	$inipay->m_buyerEmail = $buyeremail; 		    // 구매자이메일
	$inipay->m_cardQuota = $cardquota; 		    // 할부기간
	$inipay->m_quotaInterest = $quotainterest; 	    // 무이자 할부 여부 (1:YES, 0:NO)
	$inipay->m_url = "http://www.kiam.kr";    // 상점 인터넷 주소
	$inipay->m_cardPass = $cardpass; 		    // 키드 비번(앞 2자리)
	$inipay->m_regNumber = $regnumber; 		    // 주민 번호 및 사업자 번호 입력
	$inipay->m_authentification = $authentification;	//( 신용카드 빌링 관련 공인 인증서로 인증을 받은 경우 고정값 "01"로 세팅)  
	$inipay->m_oid = $oid;								//주문번호
	$inipay->m_merchantReserved1 = $merchantReserved1;  //Tax : 부가세 , TaxFree : 면세 (예 : Tax=10&TaxFree=10)
	$inipay->startAction();
	if($inipay->m_resultCode=="00")
	{
			$pay_info[phone_cnt]=$_POST[phone_cnt];//기부폰개수
			$pay_info['month_cnt']=$_POST['month_cnt'];//결제개월수
			if($_POST[fujia_status])
			$pay_info[fujia_status]="Y";//부가서비스			
			$pay_info[buyertel]=$_POST[buyertel];//전화번호
			$pay_info[buyeremail]=$_POST[buyeremail];//이메일
			$pay_info[cardpass]=$_POST[cardpass];//비밀번호앞 두자리
			$pay_info[regnumber]=$_POST[regnumber];//주민번호
			$pay_info[billkey]=$_POST[billkey];//빌키
			$pay_info[cardKind]=$_POST[cardKind];//카드종류
			$pay_info[ini_offer_period]=$_POST[ini_offer_period];//제공기간
			$pay_info[print_msg]=$_POST[print_msg];//결제일은 매월 24일입니다.					
			$pay_info[resultCode]=$inipay->m_resultCode;//승인결과코드
			$pay_info[resultMsg]=iconv("euc-kr","utf-8",$inipay->m_resultMsg);//결과메시지
			$pay_info[payMethod]="Auto_".$_POST[paymethod];//지불수단					
			$pay_info[orderNumber]=$_POST[oid];//주문번호
			$pay_info[tid]=$inipay->m_tid;//TID
			$pay_info[TotPrice]=$_POST[price];//승인금액	
			$pay_info[applDate]=$inipay->m_pgAuthDate;//승인일
			$pay_info[applTime]=$inipay->m_pgAuthTime;//승인시각
			$pay_info[mid]=$_POST[mid];//상점ID
			$pay_info[VACT_InputName]=$_POST[buyername];//구매자명
			$pay_info['buyer_id']=$member_1['mem_id'];
			$pay_info[ApplNum]=$inipay->m_authCode;//승인번호
			$pay_info[CARD_Quota]=$_POST[cardquota];//할부개월
			$pay_info[pc_mobile]="A";
			$pay_info['end_status']="Y";
			if($_POST[pay_ex_end_date] && $_POST[pay_ex_no])
			{
				$pay_info[cancel_ResultCode]="";//취소코드
				$pay_info[cancel_ResultMsg]="";//취소메시지
				$pay_info[cancel_CancelDate]="";//취소일
				$pay_info[cancel_CancelTime]="";//취소시각
				$pay_info[cancel_CSHR_CancelNum]="";//현금영수증 취소 승인번호(현금영수증 발급 취소시에만 리턴됨)
				$pay_info[cancel_status]="N";			
				$sql="update tjd_pay_result set ";
				foreach($pay_info as $key=>$v)
				{
					$sql.=" $key = '$v' , ";
				}
				$sql.=" end_date=date_add(now(),INTERVAL {$_POST['month_cnt']} month) , date=now() where no='$_POST[pay_ex_no]'";
				
				$sql_num_up="update Gn_MMS_Number set end_status='Y' , end_date=date_add(now(),INTERVAL {$_POST['month_cnt']} month) where end_date = '$_POST[pay_ex_end_date]' and mem_id='{$member_1['mem_id']}' ";
				mysqli_query($self_con,$sql_num_up) or die(mysqli_error($self_con));
				//$sql_back="insert into tjd_pay_result_back (select * from tjd_pay_result where no='{$_POST[pay_ex_no]}' )";
				//mysqli_query($self_con,$sql_back) or die(mysqli_error($self_con));
			}
			else
			{
				$sql="insert into tjd_pay_result set ";
				foreach($pay_info as $key=>$v)
				{
					$sql.=" $key = '$v' , ";
				}
				$sql.=" end_date=date_add(now(),INTERVAL {$_POST['month_cnt']} month) , date=now() ";
				mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
			}
			
			
			if($_POST[fujia_status])
			{
			$sql_m="update Gn_Member set fujia_date1=now() , fujia_date2=date_add(now(),INTERVAL {$_POST['month_cnt']} month) where mem_id='{$member_1['mem_id']}' ";
			mysqli_query($self_con,$sql_m)or die(mysqli_error($self_con));
			}
	}	
}
$sql="select * from tjd_pay_result where orderNumber='{$_POST[oid]}' and buyer_id='{$member_1['mem_id']}' ";
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
                    if($row[resultCode]=="0000")
                    {
                        if($row[payMethod]=="VBank")
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
                <td><?=$row[resultCode]?></td>
                </tr>                    
                <tr>
                <td>결과메시지</td>
                <td><?=iconv("euc-kr","utf-8",$inipay->m_resultMsg)?></td>
                </tr>
                <tr>
                <td>TID</td>
                <td><?=$row[tid]?></td>
                </tr>                                                    
                <tr>
                <td>지불수단</td>
                <td><?=$row[payMethod]?></td>
                </tr>
                <tr>
                <td>주문번호</td>
                <td><?=$row[orderNumber]?></td>
                </tr>                    
                <tr>
                <tr>
                <td>구매자명</td>
                <td><?=$row[VACT_InputName]?></td>
                </tr>                                        
                <td>지불금액</td>
                <td><?=$row[TotPrice]?></td>
                </tr>
                <tr>
                <td>지불시간</td>
                <td><?=$row[applDate]?><?=$row[applTime]?></td>
                </tr>
                <?
                if($row[payMethod]=="VBank")
                {
                    ?>                       
                    <tr>
                    <td>예금주</td>
                    <td><?=$row[VACT_Name]?></td>
                    </tr>
                    <tr>
                    <td>은행코드</td>
                    <td><?=$row[VACT_BankCode]?></td>
                    </tr>
                    <tr>
                    <td>가상계좌번호</td>
                    <td><?=$row[VACT_Num]?></td>
                    </tr>
                    <tr>
                    <td>입금예정시간</td>
                    <td><?=$row[VACT_Date]?></td>
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
