<?php
$path = "../../";
include_once "../../_head.php";
// 올앳관련 함수 Include
//----------------------
include "./allatutil.php";
$at_enc       = "";
$at_data      = "";
$at_txt       = "";
$ORDER_NO = $_GET['ORDER_NO'];
$link = $_GET['link'];

$sql="select * from Gn_Item_Pay_Result where order_number='$ORDER_NO'";
$resul=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
$row=mysqli_fetch_array($resul);
$mem_id = $row['buyer_id'];
// 필수 항목
$at_cross_key      = "304f3a821cac298ff8a0ef504e1c2309";   //CrossKey값(최대200자)
$at_fix_key        = $row[billkey];   //카드키(최대 24자)
$at_sell_mm        = "00";   //할부개월값(최대  2자)
$at_amt            = $row[item_price];   //금액(최대 10자)
$at_business_type  = "0";   //결제자 카드종류(최대 1자)       : 개인(0),법인(1)
$at_registry_no    = "";   //주민번호(최대 13자리)           : szBusinessType=0 일경우
$at_biz_no         = "";   //사업자번호(최대 20자리)         : szBusinessType=1 일경우
$at_shop_id        = "bwelcome12";   //상점ID(최대 20자)
$at_shop_member_id = $mem_id;   //회원ID(최대 20자)               : 쇼핑몰회원ID
$at_order_no       = $ORDER_NO;   //주문번호(최대 80자)             : 쇼핑몰 고유 주문번호
$at_product_cd     = "";   //상품코드(최대 1000자)           : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
$at_product_nm     = iconv("UTF-8","EUC-KR",$row['item_name']);   //상품명(최대 1000자)             : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
$at_cardcert_yn    = "N";   //카드인증여부(최대 1자)          : 인증(Y),인증사용않음(N),인증만사용(X)
$at_zerofee_yn     = "";   //일반/무이자 할부 사용 여부(최대 1자) : 일반(N), 무이자 할부(Y)
$at_buyer_nm       = iconv("UTF-8","EUC-KR",$row['VACT_InputName']);   //결제자성명(최대 20자)
$at_recp_nm        = iconv("UTF-8","EUC-KR",$row['VACT_InputName']);   //수취인성명(최대 20자)
$at_recp_addr      = "";   //수취인주소(최대 120자)
$at_buyer_ip       = "Unknown";   //결제자 IP(최대15자) - BuyerIp를 넣을수 없다면 "Unknown"으로 세팅
$at_email_addr     = "";   //결제자 이메일 주소(50자)
$at_bonus_yn       = "";   //보너스포인트 사용여부(최대1자)  : 사용(Y), 사용않음(N)
$at_gender         = "";   //구매자 성별(최대 1자)           : 남자(M)/여자(F)
$at_birth_ymd      = "";   //구매자의 생년월일(최대 8자)     : YYYYMMDD형식

$at_enc = setValue($at_enc,"allat_card_key",$at_fix_key);
$at_enc = setValue($at_enc,"allat_sell_mm",$at_sell_mm);
$at_enc = setValue($at_enc,"allat_amt",$at_amt);
$at_enc = setValue($at_enc,"allat_business_type",$at_business_type);
if( strcmp($at_business_type,"0") == 0 ){
    $at_enc = setValue($at_enc,"allat_registry_no",$at_registry_no);
}else{
    $at_enc = setValue($at_enc,"allat_biz_no",$at_biz_no);
}
$at_enc = setValue($at_enc,"allat_shop_id",$at_shop_id);
$at_enc = setValue($at_enc,"allat_shop_member_id",$at_shop_member_id);
$at_enc = setValue($at_enc,"allat_order_no",$at_order_no);
$at_enc = setValue($at_enc,"allat_product_cd",$at_product_cd);
$at_enc = setValue($at_enc,"allat_product_nm",$at_product_nm);
$at_enc = setValue($at_enc,"allat_cardcert_yn",$at_cardcert_yn);
$at_enc = setValue($at_enc,"allat_zerofee_yn",$at_zerofee_yn);
$at_enc = setValue($at_enc,"allat_buyer_nm",$at_buyer_nm);
$at_enc = setValue($at_enc,"allat_recp_name",$at_recp_nm);
$at_enc = setValue($at_enc,"allat_recp_addr",$at_recp_addr);
$at_enc = setValue($at_enc,"allat_user_ip",$at_buyer_ip);
$at_enc = setValue($at_enc,"allat_email_addr",$at_email_addr);
$at_enc = setValue($at_enc,"allat_bonus_yn",$at_bonus_yn);
$at_enc = setValue($at_enc,"allat_gender",$at_gender);
$at_enc = setValue($at_enc,"allat_birth_ymd",$at_birth_ymd);
$at_enc = setValue($at_enc,"allat_pay_type","FIX");  //수정금지(결제방식 정의)
$at_enc = setValue($at_enc,"allat_test_yn","N");  //테스트 :Y, 서비스 :N
$at_enc = setValue($at_enc,"allat_opt_pin","NOUSE");  //수정금지(올앳 참조 필드)
$at_enc = setValue($at_enc,"allat_opt_mod","APP");  //수정금지(올앳 참조 필드)

$at_data = "allat_shop_id=".$at_shop_id.
"&allat_amt=".$at_amt.
"&allat_enc_data=".$at_enc.
"&allat_cross_key=".$at_cross_key;

// 올앳 결제 서버와 통신 : ApprovalReq->통신함수, $at_txt->결과값
//----------------------------------------------------------------
$at_txt = ApprovalReq($at_data,"SSL");

// 결제 결과 값 확인
//------------------
$REPLYCD   =getValue("reply_cd",$at_txt);        //결과코드
$REPLYMSG  =getValue("reply_msg",$at_txt);       //결과 메세지

if(!strcmp($REPLYCD,"0000")){//pay_test
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
    $sql = "insert into tjd_pay_result_month set pay_idx='$row[order_number]',
                                                    order_number='$row[order_number]',
                                                    pay_yn='Y',
                                                    msg='성공_mp_item',
                                                    regdate = NOW(),
                                                    amount='$row[item_price]',
                                                    buyer_id='$mem_id'";
    mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
}
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
        <div><img src="/images/sub_02_visual_03.jpg" /></div>
        <div class="pay">
            <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="2" style="font-size:16px;">PG log</td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center;">
                        <h3><?if($REPLYCD=="0000") {//pay_test
                                echo "결제가 성공적으로 이루어졌습니다.";
                            }
                            else {
                                echo "결제실패하였습니다.다시 시도하시거나 홈페이지 관리자에 문의하세요.";
                                $sql = "insert into tjd_pay_result_month set pay_idx='$row[order_number]',
                                                                                order_number='$ORDER_NO',
                                                                                regdate = NOW(),
                                                                                pay_yn='N',
                                                                                msg='".iconv("euc-kr","utf-8",$REPLYMSG)."_mp_item"."',
                                                                                amount='$row[item_price]',
                                                                                buyer_id='$mem_id'";
                                mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
                            }?>
                        </h3>
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
                    <td colspan="2" style="text-align:center;">
                        <input type="button" value="메인으로" onclick="location.replace('/')"  />
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?
include_once "_foot.php";
?>
