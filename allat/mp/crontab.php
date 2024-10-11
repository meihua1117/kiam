#!/usr/local/bin/php
<?php
$path = "../../";
$sess_path=$_SERVER['DOCUMENT_ROOT']."/_session";
ini_set("session.cache_expire", 60);  
ini_set("session.gc_maxlifetime", 86400);
@session_save_path($sess_path);
@session_start();
  include_once $_SERVER['DOCUEMNT_ROOT']."/lib/rlatjd_fun.php";
  // 올앳관련 함수 Include
  //----------------------
  include $_SERVER['DOCUEMNT_ROOT']."/allat/mp/allatutil.php";

    $at_enc       = "";
    $at_data      = "";
    $at_txt       = "";
    echo date("t");
    if(date("t") == date("d") && date("t") == 30) {
        $query = "SELECT * FROM tjd_pay_result WHERE idx > 0 AND end_status ='Y' AND billkey != '' 
        AND date < '".date('Y-m-d')."'  
        AND (date like '%-".date("d")." %' or date like '%-31 %')
        ORDER BY no asc";        
    } else if(date("t") == date("d") && date("t") == 29) {
        $query = "SELECT * FROM tjd_pay_result WHERE idx > 0 AND end_status ='Y' AND billkey != '' 
        AND date < '".date('Y-m-d')."'  
        AND (date like '%-".date("d")." %' or date like '%-30 %' or date like '%-31 %')
        ORDER BY no asc";        
    } else if(date("t") == date("d") && date("t") == 28) {
        $query = "SELECT * FROM tjd_pay_result WHERE idx > 0 AND end_status ='Y' AND billkey != '' 
        AND date < '".date('Y-m-d')."'  
        AND (date like '%-".date("d")." %' or date like '%-29 %' or date like '%-30 %' or date like '%-31 %')
        ORDER BY no asc";                
    } else {
        $query = "SELECT * FROM tjd_pay_result WHERE idx > 0 AND end_status ='Y' AND billkey != '' 
        AND date < '".date('Y-m-d')."'  
        AND date like '%-".date("d")." %'
        ORDER BY no asc";        

    }
    echo $query."<BR>";
    $res=mysqli_query($self_con,$query)or die(mysqli_error($self_con));
    while($data=mysqli_fetch_array($res)) {
        $ORDER_NO = $data['idx'];
        $member_1['mem_id'] = $data['buyer_id'];
            
        $sql="select * from tjd_pay_result where orderNumber='$ORDER_NO' and buyer_id='{$member_1['mem_id']}' ";
        $resul=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
        $row=mysqli_fetch_array($resul);	        

        $ORDER_NO = $row['ORDER_NO'];
        $member_1['mem_id'] = $row[one_member_id];
        
        if($ORDER_NO == "") exit;
        if($member_1['mem_id'] == "" ) exit;
      
/*        
        // 필수 항목
        $at_cross_key      = "304f3a821cac298ff8a0ef504e1c2309";   //CrossKey값(최대200자)
        $at_fix_key        = "$row[billkey]";   //카드키(최대 24자)
        $at_sell_mm        = "00";   //할부개월값(최대  2자)
        $at_amt            = "$row[TotPrice]";   //금액(최대 10자)
        $at_business_type  = "0";   //결제자 카드종류(최대 1자)       : 개인(0),법인(1)
        $at_registry_no    = "";   //주민번호(최대 13자리)           : szBusinessType=0 일경우
        $at_biz_no         = "";   //사업자번호(최대 20자리)         : szBusinessType=1 일경우
        $at_shop_id        = "bwelcome12";   //상점ID(최대 20자)
        $at_shop_member_id = $member_1['mem_id'];   //회원ID(최대 20자)               : 쇼핑몰회원ID
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

        $at_enc = setValue($at_enc,"allat_card_key"         ,   $at_fix_key        );
        $at_enc = setValue($at_enc,"allat_sell_mm"          ,   $at_sell_mm        );
        $at_enc = setValue($at_enc,"allat_amt"              ,   $at_amt            );
        $at_enc = setValue($at_enc,"allat_business_type"    ,   $at_business_type  );
        if( strcmp($at_business_type,"0") == 0 ){
            $at_enc = setValue($at_enc,"allat_registry_no"  ,   $at_registry_no    );
        }else{
            $at_enc = setValue($at_enc,"allat_biz_no"       ,   $at_biz_no         );
        }
        $at_enc = setValue($at_enc,"allat_shop_id"          ,   $at_shop_id        );
        $at_enc = setValue($at_enc,"allat_shop_member_id"   ,   $at_shop_member_id );
        $at_enc = setValue($at_enc,"allat_order_no"         ,   $at_order_no       );
        $at_enc = setValue($at_enc,"allat_product_cd"       ,   $at_product_cd     );
        $at_enc = setValue($at_enc,"allat_product_nm"       ,   $at_product_nm     );
        $at_enc = setValue($at_enc,"allat_cardcert_yn"      ,   $at_cardcert_yn    );
        $at_enc = setValue($at_enc,"allat_zerofee_yn"       ,   $at_zerofee_yn     );
        $at_enc = setValue($at_enc,"allat_buyer_nm"         ,   $at_buyer_nm       );
        $at_enc = setValue($at_enc,"allat_recp_name"        ,   $at_recp_nm        );
        $at_enc = setValue($at_enc,"allat_recp_addr"        ,   $at_recp_addr      );
        $at_enc = setValue($at_enc,"allat_user_ip"          ,   $at_buyer_ip       );
        $at_enc = setValue($at_enc,"allat_email_addr"       ,   $at_email_addr     );
        $at_enc = setValue($at_enc,"allat_bonus_yn"         ,   $at_bonus_yn       );
        $at_enc = setValue($at_enc,"allat_gender"           ,   $at_gender         );
        $at_enc = setValue($at_enc,"allat_birth_ymd"        ,   $at_birth_ymd      );
        $at_enc = setValue($at_enc,"allat_pay_type"         ,   "FIX"              );  //수정금지(결제방식 정의)
        $at_enc = setValue($at_enc,"allat_test_yn"          ,   "N"                );  //테스트 :Y, 서비스 :N
        $at_enc = setValue($at_enc,"allat_opt_pin"          ,   "NOUSE"            );  //수정금지(올앳 참조 필드)
        $at_enc = setValue($at_enc,"allat_opt_mod"          ,   "APP"              );  //수정금지(올앳 참조 필드)

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

        if( !strcmp($REPLYCD,"0000") ){
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
 
            
            $sql = "insert into tjd_pay_result_month set pay_idx='{$row['idx']}',
                                                         regdate = NOW(),
                                                         amount='$row[TotPrice]',
                                                         buyer_id='{$member_1['mem_id']}'
            ";
            echo $sql."\n";
            mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
            
        $sql = "update tjd_pay_result set monthly_yn='Y', end_status='Y' where  orderNumber='$ORDER_NO' and buyer_id='{$member_1['mem_id']}'";
        $resul=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
        
        $sql="select * from Gn_Member where mem_id='{$member_1['mem_id']}' ";
        $sresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
        $srow=mysqli_fetch_array($sresult);	
        
    	//$sql_num_up="update Gn_MMS_Number set end_status='Y' , end_date=date_add(now(),INTERVAL {$ro['month_cnt']} month) where end_date = '$_POST[pay_ex_end_date]' and mem_id='{$member_1['mem_id']}' ";
    	//mysqli_query($self_con,$sql_num_up) or die(mysqli_error($self_con));				    

        
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
        $use_cnt = $row[db_cnt];
        //$last_time = date("Y-m-d H:i:s", "+120 month");
        $last_time = date("Y-m-d H:i:s", strtotime("+ 120 month"));
        $search_email_date = substr($last_time,0,10);
        $search_email_cnt = $row[email_cnt];
        $term = substr($last_time,0,10);
                               
    */
    }
?>