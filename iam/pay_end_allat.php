<?php
  include "inc/header.inc.php";
  // 올앳관련 함수 Include
  //----------------------
  include $_SERVER['DOCUMENT_ROOT']."/allat/mp/allatutil.php";
  //Request Value Define
  //----------------------
  $at_cross_key = "382331665febf9857b8a8b47f9a02e04";	//설정필요 [사이트 참조 - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
  $at_shop_id   = "welcome101";		//설정필요
  $at_amt=$_SESSION['allat_amt'];						//결제 금액을 다시 계산해서 만들어야 함(해킹방지), ( session, DB 사용 )

  // 요청 데이터 설정
  //----------------------
  $at_data   = "allat_shop_id=".$at_shop_id.
               "&allat_amt=".$at_amt.
               "&allat_enc_data=".$_POST["allat_enc_data"].
               "&allat_cross_key=".$at_cross_key;


  // 올앳 결제 서버와 통신 : ApprovalReq->통신함수, $at_txt->결과값
  //----------------------------------------------------------------
  // PHP5 이상만 SSL 사용가능
  $at_txt = ApprovalReq($at_data,"SSL");
  // $at_txt = ApprovalReq($at_data, "NOSSL"); // PHP5 이하버전일 경우
  // 이 부분에서 로그를 남기는 것이 좋습니다.
  // (올앳 결제 서버와 통신 후에 로그를 남기면, 통신에러시 빠른 원인파악이 가능합니다.)

  // 결제 결과 값 확인
  //------------------
  $REPLYCD   =getValue("reply_cd",$at_txt);        //결과코드
  $REPLYMSG  =getValue("reply_msg",$at_txt);       //결과 메세지

  // 결과값 처리
  //--------------------------------------------------------------------------
  // 결과 값이 '0000'이면 정상임. 단, allat_test_yn=Y 일경우 '0001'이 정상임.
  // 실제 결제   : allat_test_yn=N 일 경우 reply_cd=0000 이면 정상
  // 테스트 결제 : allat_test_yn=Y 일 경우 reply_cd=0001 이면 정상
  //--------------------------------------------------------------------------
  if($REPLYCD =="0000"){//pay_test
    // reply_cd "0000" 일때만 성공
    $ORDER_NO         =getValue("order_no",$at_txt);
    $AMT              =getValue("amt",$at_txt);
    $PAY_TYPE         =getValue("pay_type",$at_txt);
    $APPROVAL_YMDHMS  =getValue("approval_ymdhms",$at_txt);
    $SEQ_NO           =getValue("seq_no",$at_txt);
    $APPROVAL_NO      =getValue("approval_no",$at_txt);
    $CARD_ID          =getValue("card_id",$at_txt);
    $CARD_NM          =getValue("card_nm",$at_txt);
    $SELL_MM          =getValue("sell_mm",$at_txt);
    $ZEROFEE_YN       =getValue("zerofee_yn",$at_txt);
    $CERT_YN          =getValue("cert_yn",$at_txt);
    $CONTRACT_YN      =getValue("contract_yn",$at_txt);
    $SAVE_AMT         =getValue("save_amt",$at_txt);
	$CARD_POINTDC_AMT =getValue("card_pointdc_amt",$at_txt);
    $BANK_ID          =getValue("bank_id",$at_txt);
    $BANK_NM          =getValue("bank_nm",$at_txt);
    $CASH_BILL_NO     =getValue("cash_bill_no",$at_txt);
    $ESCROW_YN        =getValue("escrow_yn",$at_txt);
    $ACCOUNT_NO       =getValue("account_no",$at_txt);
    $ACCOUNT_NM       =getValue("account_nm",$at_txt);
    $INCOME_ACC_NM    =getValue("income_account_nm",$at_txt);
    $INCOME_LIMIT_YMD =getValue("income_limit_ymd",$at_txt);
    $INCOME_EXPECT_YMD=getValue("income_expect_ymd",$at_txt);
    $CASH_YN          =getValue("cash_yn",$at_txt);
    $HP_ID            =getValue("hp_id",$at_txt);
    $TICKET_ID        =getValue("ticket_id",$at_txt);
    $TICKET_PAY_TYPE  =getValue("ticket_pay_type",$at_txt);
    $TICKET_NAME      =getValue("ticket_nm",$at_txt);	
    $PARTCANCEL_YN    =getValue("partcancel_yn",$at_txt);	

    $sql="select * from tjd_pay_result where orderNumber='$_POST[allat_order_no]' and buyer_id='{$member_iam['mem_id']}' ";
    $resul=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
    $row=mysqli_fetch_array($resul);
      $no = $row['no'];

    $sql = "update tjd_pay_result set end_status='Y' where  orderNumber='$_POST[allat_order_no]' and buyer_id='{$member_iam['mem_id']}'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $sql = "select * from Gn_Member where mem_id='{$member_iam['mem_id']}' ";
    $sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $srow = mysqli_fetch_array($sresult);

    $sql = "select * from crawler_member_real where user_id='{$member_iam['mem_id']}' ";
    $sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $crow = mysqli_fetch_array($sresult);
    $user_id = $srow['mem_id'];
    $user_name = $srow['mem_name'];
    $password = $srow['mem_pass'];
    $cell = $srow['mem_phone'];
    $email = $srow['mem_email'];
    $address = $srow['mem_add1'];
    $status = "Y";
    $use_cnt = $row['db_cnt'];
    $last_time = date("Y-m-d H:i:s", strtotime("+ 120 month"));
    $search_email_date = substr($last_time, 0, 10);
    $search_email_cnt = $row['email_cnt'];
    $term = substr($last_time, 0, 10);
    if($row['member_type'] == "dber"){
        if ($crow[0] == "") {
            $query = "insert into crawler_member_real set user_id='$user_id',
                                            user_name='$user_name',
                                            password='$password',
                                            cell='$cell',
                                            email='$email',
                                            address='$address',
                                            term='$term',
                                            status='$status',
                                            use_cnt='$use_cnt',
                                            regdate=NOW(),
                                            search_email_yn='Y',
                                            search_email_date='$search_email_date',
                                            search_email_cnt='$search_email_cnt',
                                            shopping_end_date='$search_email_date',
                                            extra_db_cnt = '{$row['db_cnt']}',
                                            extra_email_cnt = '{$row['email_cnt']}',
                                            extra_shopping_cnt = '$row[shop_cnt]'";
            mysqli_query($self_con,$query);
        } else {
            $query = "update crawler_member_real set
                                            extra_db_cnt = extra_db_cnt + '{$row['db_cnt']}',
                                            extra_email_cnt = extra_email_cnt + '{$row['email_cnt']}',
                                            extra_shopping_cnt = extra_shopping_cnt + '$row[shop_cnt]'
                                            where user_id='$user_id'
                                            ";
            mysqli_query($self_con,$query);
        }
    }

    $sql_m = "update Gn_Member set fujia_date1=now() , fujia_date2=date_add(now(),INTERVAL 120 month)  where mem_id='{$member_iam['mem_id']}' ";
    mysqli_query($self_con,$sql_m) or die(mysqli_error($self_con));

    $add_phone = $row['phone_cnt'] / 9000;
    $sql_m = "update Gn_Member set   phone_cnt=phone_cnt+'$add_phone' where mem_id='{$member_iam['mem_id']}' ";
    mysqli_query($self_con,$sql_m) or die(mysqli_error($self_con));

    /*if ($srow['recommend_id'] != "") {
        $sql = "select * from Gn_Member where mem_id='$srow[recommend_id]' ";
        $rresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        if (mysqli_num_rows($rresult) > 0) {
            $rrow = mysqli_fetch_array($rresult);
            $branch_share_id = "";
            $addQuery = "";
            $branch_share_per = 0;
            // 리셀러 / 분양 회원 확인
            // 리셀러 회원인경우 분양회원 아이디 확인
            if ($rrow[service_type] == 2) {
                // 추천인의 추천인 검색 및 등급 확인
                $sql = "select * from Gn_Member where mem_id='$rrow[recommend_id]'";
                $rresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                $trow = mysqli_fetch_array($rresult);

                $share_per = $recommend_per = $rrow['share_per'] ? $rrow['share_per'] : 30;
                if ($trow[0] != "") {
                    $recommend_per = $trow['share_per'] ? $trow['share_per'] : 50;
                    $branch_share_per = $recommend_per - $share_per;
                    $branch_share_id = $trow['mem_id'];
                }
            } else if ($rrow[service_type] == 3) {
                $share_per = $recommend_per = $rrow['share_per'] ? $rrow['share_per'] : 50;
                $branch_share_per = 0;
            }

            $sql = "update tjd_pay_result set share_per='$share_per', branch_share_per = '$branch_share_per', share_id='$srow[recommend_id]', branch_share_id='$branch_share_id' where no='$no'";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        }
    }*/
/*
    echo "결과코드              : ".$REPLYCD."<br>";
    echo "결과메세지            : ".$REPLYMSG."<br>";
    echo "주문번호              : ".$ORDER_NO."<br>";
    echo "승인금액              : ".$AMT."<br>";
    echo "지불수단              : ".$PAY_TYPE."<br>";
    echo "승인일시              : ".$APPROVAL_YMDHMS."<br>";
    echo "거래일련번호          : ".$SEQ_NO."<br>";
    echo "에스크로 적용 여부    : ".$ESCROW_YN."<br>";
    echo "=============== 신용 카드 ===============================<br>";
    echo "승인번호              : ".$APPROVAL_NO."<br>";
    echo "카드ID                : ".$CARD_ID."<br>";
    echo "카드명                : ".$CARD_NM."<br>";
    echo "할부개월              : ".$SELL_MM."<br>";
    echo "무이자여부            : ".$ZEROFEE_YN."<br>";   //무이자(Y),일시불(N)
    echo "인증여부              : ".$CERT_YN."<br>";      //인증(Y),미인증(N)
    echo "직가맹여부            : ".$CONTRACT_YN."<br>";  //3자가맹점(Y),대표가맹점(N)
    echo "세이브 결제 금액      : ".$SAVE_AMT."<br>";
    echo "포인트할인 결제 금액  : ".$CARD_POINTDC_AMT."<br>";
    echo "=============== 계좌 이체 / 가상계좌 ====================<br>";
    echo "은행ID                : ".$BANK_ID."<br>";
    echo "은행명                : ".$BANK_NM."<br>";
    echo "현금영수증 일련 번호  : ".$CASH_BILL_NO."<br>";
    echo "=============== 가상계좌 ================================<br>";
    echo "계좌번호              : ".$ACCOUNT_NO."<br>";
    echo "입금계좌명            : ".$INCOME_ACC_NM."<br>";
    echo "입금자명              : ".$ACCOUNT_NM."<br>";
    echo "입금기한일            : ".$INCOME_LIMIT_YMD."<br>";
    echo "입금예정일            : ".$INCOME_EXPECT_YMD."<br>";
    echo "현금영수증신청 여부   : ".$CASH_YN."<br>";
    echo "=============== 휴대폰 결제 =============================<br>";
    echo "이동통신사구분        : ".$HP_ID."<br>";
    echo "=============== 상품권 결제 =============================<br>";
    echo "상품권 ID             : ".$TICKET_ID."<br>";
    echo "상품권 이름           : ".$TICKET_NAME."<br>";
    echo "결제구분              : ".$TICKET_PAY_TYPE."<br>";

	echo "부분취소가능여부 : ".$PARTCANCEL_YN."<br>";
	*/

  }else{
    // reply_cd "0000" 일때만 성공
    $ORDER_NO         =getValue("order_no",$at_txt);
    $AMT              =getValue("amt",$at_txt);
    $PAY_TYPE         =getValue("pay_type",$at_txt);
    $APPROVAL_YMDHMS  =getValue("approval_ymdhms",$at_txt);
    $SEQ_NO           =getValue("seq_no",$at_txt);
    $APPROVAL_NO      =getValue("approval_no",$at_txt);
    $CARD_ID          =getValue("card_id",$at_txt);
    $CARD_NM          =getValue("card_nm",$at_txt);
    $SELL_MM          =getValue("sell_mm",$at_txt);
    $ZEROFEE_YN       =getValue("zerofee_yn",$at_txt);
    $CERT_YN          =getValue("cert_yn",$at_txt);
    $CONTRACT_YN      =getValue("contract_yn",$at_txt);
    $SAVE_AMT         =getValue("save_amt",$at_txt);
	$CARD_POINTDC_AMT =getValue("card_pointdc_amt",$at_txt);
    $BANK_ID          =getValue("bank_id",$at_txt);
    $BANK_NM          =getValue("bank_nm",$at_txt);
    $CASH_BILL_NO     =getValue("cash_bill_no",$at_txt);
    $ESCROW_YN        =getValue("escrow_yn",$at_txt);
    $ACCOUNT_NO       =getValue("account_no",$at_txt);
    $ACCOUNT_NM       =getValue("account_nm",$at_txt);
    $INCOME_ACC_NM    =getValue("income_account_nm",$at_txt);
    $INCOME_LIMIT_YMD =getValue("income_limit_ymd",$at_txt);
    $INCOME_EXPECT_YMD=getValue("income_expect_ymd",$at_txt);
    $CASH_YN          =getValue("cash_yn",$at_txt);
    $HP_ID            =getValue("hp_id",$at_txt);
    $TICKET_ID        =getValue("ticket_id",$at_txt);
    $TICKET_PAY_TYPE  =getValue("ticket_pay_type",$at_txt);
    $TICKET_NAME      =getValue("ticket_nm",$at_txt);	
    $PARTCANCEL_YN    =getValue("partcancel_yn",$at_txt);	
/*
    echo "결과코드              : ".$REPLYCD."<br>";
    echo "결과메세지            : ".$REPLYMSG."<br>";
    echo "주문번호              : ".$ORDER_NO."<br>";
    echo "승인금액              : ".$AMT."<br>";
    echo "지불수단              : ".$PAY_TYPE."<br>";
    echo "승인일시              : ".$APPROVAL_YMDHMS."<br>";
    echo "거래일련번호          : ".$SEQ_NO."<br>";
    echo "에스크로 적용 여부    : ".$ESCROW_YN."<br>";
    echo "=============== 신용 카드 ===============================<br>";
    echo "승인번호              : ".$APPROVAL_NO."<br>";
    echo "카드ID                : ".$CARD_ID."<br>";
    echo "카드명                : ".$CARD_NM."<br>";
    echo "할부개월              : ".$SELL_MM."<br>";
    echo "무이자여부            : ".$ZEROFEE_YN."<br>";   //무이자(Y),일시불(N)
    echo "인증여부              : ".$CERT_YN."<br>";      //인증(Y),미인증(N)
    echo "직가맹여부            : ".$CONTRACT_YN."<br>";  //3자가맹점(Y),대표가맹점(N)
    echo "세이브 결제 금액      : ".$SAVE_AMT."<br>";
    echo "포인트할인 결제 금액  : ".$CARD_POINTDC_AMT."<br>";
    echo "=============== 계좌 이체 / 가상계좌 ====================<br>";
    echo "은행ID                : ".$BANK_ID."<br>";
    echo "은행명                : ".$BANK_NM."<br>";
    echo "현금영수증 일련 번호  : ".$CASH_BILL_NO."<br>";
    echo "=============== 가상계좌 ================================<br>";
    echo "계좌번호              : ".$ACCOUNT_NO."<br>";
    echo "입금계좌명            : ".$INCOME_ACC_NM."<br>";
    echo "입금자명              : ".$ACCOUNT_NM."<br>";
    echo "입금기한일            : ".$INCOME_LIMIT_YMD."<br>";
    echo "입금예정일            : ".$INCOME_EXPECT_YMD."<br>";
    echo "현금영수증신청 여부   : ".$CASH_YN."<br>";
    echo "=============== 휴대폰 결제 =============================<br>";
    echo "이동통신사구분        : ".$HP_ID."<br>";
    echo "=============== 상품권 결제 =============================<br>";
    echo "상품권 ID             : ".$TICKET_ID."<br>";
    echo "상품권 이름           : ".$TICKET_NAME."<br>";
    echo "결제구분              : ".$TICKET_PAY_TYPE."<br>";

	echo "부분취소가능여부 : ".$PARTCANCEL_YN."<br>";    
    // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
    // reply_msg 는 실패에 대한 메세지
    echo "결과코드  : ".$REPLYCD."<br>";
    echo "결과메세지: ".$REPLYMSG."<br>";
*/
  }

/*
    [신용카드 전표출력 예제]

    결제가 정상적으로 완료되면 아래의 소스를 이용하여, 고객에게 신용카드 전표를 보여줄 수 있습니다.
    전표 출력시 상점아이디와 주문번호를 설정하시기 바랍니다.

    var urls ="http://www.allatpay.com/servlet/AllatBizPop/member/pop_card_receipt.jsp?shop_id=상점아이디&order_no=주문번호";
    window.open(urls,"app","width=410,height=650,scrollbars=0");

    현금영수증 전표 또는 거래확인서 출력에 대한 문의는 올앳페이 사이트의 1:1상담을 이용하시거나
    02) 3788-9990 으로 전화 주시기 바랍니다.

    전표출력 페이지는 저희 올앳 홈페이지의 일부로써, 홈페이지 개편 등의 이유로 인하여 페이지 변경 또는 URL 변경이 있을 수
    있습니다. 홈페이지 개편에 관한 공지가 있을 경우, 전표출력 URL을 확인하시기 바랍니다.
*/
?>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<script src="/admin/bootstrap/js/bootstrap.js"></script>
<script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
<style>
tr td:first-child {
    font-size : 13px;
    padding: 10px;
    background-color: #F0F0F0;
    width: 15%;
}
td{
    font-size : 13px;
    padding: 10px;
    border-left: 1px solid #CCC;
    border-top: 1px solid #CCC;
}
</style>
<div class="big_main" style="margin-top:86px">
   <div class="m_div" style="width:100%">
   		<div>
           <img src="/images/sub_02_visual_03.jpg" style="width:100%"/>
        </div>
        <div style="padding: 20px;">      		
            <table class="write_table" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="2" style="font-size:16px;">PG log</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center;">
                        <p style="font-size:15px;font-weight:bold">
                        <?if($REPLYCD=="0000"){//pay_test
                            if($row[payMethod]=="VBank")
                                echo "입금예정시간내로 아래 가상계좌로 입금하시면 구매가 완료됩니다.";
                            else
                                echo "결제가 성공적으로 이루어졌습니다.";
                        }else
                            echo "결제실패하였습니다.다시 시도하시거나 홈페이지 관리자에 문의하세요.";
                        ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td>결과코드</td>
                    <td><?=$REPLYCD?></td>
                </tr>                    
                <tr>
                    <td>결과메시지</td>
                    <td><?=iconv("euc-kr","utf-8",$REPLYMSG)?></td>
                </tr>
                <tr>
                    <td>TID</td>
                    <td><?=$ORDER_NO?></td>
                </tr>                                                    
                <tr>
                    <td>지불수단</td>
                    <td>카드</td>
                </tr>
                <tr>
                    <td>주문번호</td>
                    <td><?=$ORDER_NO?></td>
                </tr>                    
                <tr>
                    <td>구매자명</td>
                    <td><?=$row[VACT_InputName]?></td>
                </tr>                                        
                <tr>
                    <td>지불금액</td>
                    <td><?=$row[TotPrice]?></td>
                </tr>
                <tr>
                    <td>지불시간</td>
                    <td><?=$row[applDate]?><?=$row[applTime]?></td>
                </tr>
                <?if($row[payMethod]=="VBank"){?>                       
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
                <?}?>
                <tr>
                	<td colspan="2" style="text-align:center;">
                        <input type="button" value="메인으로" onclick="location.href='/'" />
                    	<input type="button" value="결제다시하기" onclick="location.href='/iam/pay.php'" />
                    </td>
                </tr>
            </table>
       </div>
   </div>
</div>
<?
include_once "_foot.php";
?>
<script>
$(function(){
    $(document).ajaxStop(function() {
        $("#ajax-loading").delay(10).hide(1);
    });
});
</script>