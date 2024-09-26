<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
// 올앳관련 함수 Include
//----------------------
include "./allatutil.php";
///Request Value Define
//----------------------
$at_cross_key = "가맹점 CrossKey";     //설정필요 [사이트 참조 - http://www.allatpay.com/servlet/AllatBiz/helpinfo/hi_install_guide.jsp#shop]
$at_shop_id   = "가맹점 ShopId";       //설정필요
$at_cross_key = "304f3a821cac298ff8a0ef504e1c2309";	//설정필요 [사이트 참조 - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
$at_shop_id   = "bwelcome12";		//설정필요
// 요청 데이터 설정
//----------------------
$at_data   = "allat_shop_id=".$at_shop_id.
    "&allat_enc_data=".$_POST["allat_enc_data"].
    "&allat_cross_key=".$at_cross_key;
// 올앳 서버와 통신
//--------------------------
$at_txt = CertRegReq($at_data,"SSL");
// 결제 결과 값 확인
//------------------
$REPLYCD  = getValue("reply_cd",$at_txt);        //결과코드
$REPLYMSG = getValue("reply_msg",$at_txt);       //결과 메세지
$ORDER_NO = $_POST['allat_order_no'];

$FIX_KEY   = getValue("fix_key",$at_txt);
$APPLY_YMD = getValue("apply_ymd",$at_txt);

$sql="select * from tjd_pay_result where orderNumber='$ORDER_NO'";
$resul=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_array($resul);
$no = $row['no'];
if(!strcmp($REPLYCD,"0000")){//pay_test
    $sql = "update tjd_pay_result set end_status='Y',billkey='$FIX_KEY',billdate='$APPLY_YMD' where  orderNumber='$ORDER_NO'";
    mysql_query($sql) or die(mysql_error());
    //디버회원가입하기
    $user_id = $member_1['mem_id'];
    $user_name = $member_1['mem_name'];
    $password = $member_1['mem_pass'];
    $cell = $member_1['mem_phone'];
    $email = $member_1['mem_email'];
    $address = $member_1['mem_add1'];
    $status = "Y";
    $use_cnt = $row[db_cnt];
    $last_time = date("Y-m-d H:i:s", strtotime("+$row[month_cnt] month"));
    $search_email_date = substr($last_time, 0, 10);
    $search_email_cnt = $row[email_cnt];
    $term = substr($last_time, 0, 10);

    $sql = "select count(cmid) from crawler_member_real where user_id='$member_1[mem_id]' ";
    $sresult = mysql_query($sql) or die(mysql_error());
    $crow = mysql_fetch_array($sresult);
    if ($crow[0] == 0) {
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
                                    shopping_end_date='$search_email_date'";
        mysql_query($query);
    } else {
        $query = "update crawler_member_real set
                                    cell='$cell',
                                    email='$email',
                                    address='$address',
                                    term='$term',
                                    status='$status',
                                    use_cnt='$use_cnt',
                                    monthly_cnt = 0,
                                    total_cnt = 0,
                                    regdate=NOW(),
                                    search_email_yn='Y',
                                    search_email_date='$search_email_date',
                                    search_email_cnt='$search_email_cnt',
                                    shopping_yn='N',
                                    shopping_end_date='$search_email_date',
                                    status='Y'
                                    where user_id='$user_id'";
        mysql_query($query);
    }
    $add_phone = $row[phone_cnt] / 9000;
    $sql_m = "update Gn_Member set fujia_date1=now() , fujia_date2=date_add(now(),INTERVAL 120 month),phone_cnt=phone_cnt+'$add_phone'";
    if($row['member_type'] == "year-professional")
        $sql_m .=",mem_point = mem_point + 1000000,service_type=2";  
    else if($row['member_type'] == "professional")
        $sql_m .=",service_type=1";
    else if($row['member_type'] == "enterprise")
        $sql_m .=",service_type=1";
    $sql_m .= " where mem_id='$member_1[mem_id]'";
    mysql_query($sql_m) or die(mysql_error());

    if($row['member_type'] == "year-professional"){
        $sql_data = "select mem_phone, mem_point, mem_cash, mem_name from Gn_Member where mem_id='{$member_1[mem_id]}'";
        $res_data = mysql_query($sql_data);
        $row_data = mysql_fetch_array($res_data);
        
        $sql_res = "insert into Gn_Item_Pay_Result set buyer_id='{$member_1[mem_id]}', buyer_tel='{$row_data['mem_phone']}', item_name='씨드포인트충전', item_price=1000000, pay_percent=90, current_point={$row_data['mem_point']}, current_cash={$row_data[mem_cash]}, pay_status='Y', VACT_InputName='{$row_data[mem_name]}', type='buy', seller_id='{$member_1[mem_id]}', pay_method='결제씨드충전', pay_date=now(), point_val=1";
    
        mysql_query($sql_res);
    }

    if ($member_1['recommend_id'] != "") {
        $sql = "select * from Gn_Member where mem_id='$member_1[recommend_id]' ";
        $rresult = mysql_query($sql) or die(mysql_error());
        if (mysql_num_rows($rresult) > 0) {
            $rrow = mysql_fetch_array($rresult);
            $branch_share_id = "";
            $addQuery = "";
            $branch_share_per = 0;
            // 리셀러 / 분양 회원 확인
            // 리셀러 회원인경우 분양회원 아이디 확인
            if ($rrow[service_type] == 2) {
                // 추천인의 추천인 검색 및 등급 확인
                $sql = "select * from Gn_Member where mem_id='$rrow[recommend_id]'";
                $rresult = mysql_query($sql) or die(mysql_error());
                $trow = mysql_fetch_array($rresult);
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

            $sql = "update tjd_pay_result set share_per='$share_per', branch_share_per = '$branch_share_per', share_id='$member_1[recommend_id]', branch_share_id='$branch_share_id' where no='$no'";
            mysql_query($sql) or die(mysql_error());
        }
    }//디버회원가입 완료
    $iam_card_cnt = $_POST[iam_card_cnt];
    $iam_share_cnt = $_POST[iam_share_cnt];
    $sql_m = "update Gn_Member set fujia_date1=now() ,
                                fujia_date2=date_add(now(),INTERVAL 120 month),
                                iam_card_cnt = iam_card_cnt + '$iam_card_cnt',
                                iam_share_cnt = iam_share_cnt + '$iam_share_cnt',
                                exp_start_status = 0,
                                exp_mid_status = 0,
                                exp_limit_status = 0,
                                exp_limit_date = NULL
                            where mem_id='$member_1[mem_id]' ";
    mysql_query($sql_m) or die(mysql_error());
    
    $at_enc       = "";
    $at_data      = "";
    $at_txt       = "";
    // 필수 항목
    $at_cross_key      = "304f3a821cac298ff8a0ef504e1c2309";   //CrossKey값(최대200자)
    $at_fix_key        = $FIX_KEY;   //카드키(최대 24자)
    $at_sell_mm        = "00";   //할부개월값(최대  2자)
    $at_amt            = $row[TotPrice];   //금액(최대 10자)
    $at_business_type  = "0";   //결제자 카드종류(최대 1자)       : 개인(0),법인(1)
    $at_registry_no    = "";   //주민번호(최대 13자리)           : szBusinessType=0 일경우
    $at_biz_no         = "";   //사업자번호(최대 20자리)         : szBusinessType=1 일경우
    $at_shop_id        = "bwelcome12";   //상점ID(최대 20자)
    $at_shop_member_id = $member_1[mem_id];   //회원ID(최대 20자)               : 쇼핑몰회원ID
    $at_order_no       = $ORDER_NO;   //주문번호(최대 80자)             : 쇼핑몰 고유 주문번호
    $at_product_cd     = "";   //상품코드(최대 1000자)           : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
    $at_product_nm     = iconv("UTF-8","EUC-KR",$row['member_type']);   //상품명(최대 1000자)             : 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개)
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

    if(!strcmp($REPLYCD,"0000")){//pay_test 성공이면
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
        $sql = "insert into tjd_pay_result_month set pay_idx='$row[idx]',
                                                    order_number='$row[idx]',
                                                    pay_yn='Y',
                                                    msg='성공_mp_fix_ajax',
                                                    regdate = NOW(),
                                                    amount='$row[TotPrice]',
                                                    buyer_id='$member_1[mem_id]'";
        mysql_query($sql)or die(mysql_error());
        $sql = "update tjd_pay_result set monthly_yn='Y', end_status='Y' where  orderNumber='$ORDER_NO' and buyer_id='$member_1[mem_id]'";
        mysql_query($sql)or die(mysql_error());

        ?>
        <script language="javascript">
            $("#iam_info_modal").modal("show");
        </script>
    <?
    }else{
        echo "결과코드  : ".$REPLYCD."<br>";
        echo "결과메세지: ".$REPLYMSG."<br>";
        $sql = "insert into tjd_pay_result_month set pay_idx='$row[idx]',
                                                        order_number='$ORDER_NO',
                                                        regdate = NOW(),
                                                        pay_yn='N',
                                                        msg='".iconv("euc-kr","utf-8",$REPLYMSG)."_mp_fix_ajax"."',
                                                        amount='$row[TotPrice]',
                                                        buyer_id='$member_1[mem_id]'";
    }
}else{
    echo "결과코드  : ".$REPLYCD."<br>";
    echo "결과메세지: ".$REPLYMSG."<br>";
    $sql = "insert into tjd_pay_result_month set pay_idx='$row[idx]',
                                                        order_number='$ORDER_NO',
                                                        regdate = NOW(),
                                                        pay_yn='N',
                                                        msg='".iconv("euc-kr","utf-8",$REPLYMSG)."_mp_fix_ajax"."',
                                                        amount='$row[TotPrice]',
                                                        buyer_id='$member_1[mem_id]'";
}
?>