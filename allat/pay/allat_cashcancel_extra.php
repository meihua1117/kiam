<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<title>CashCancelPhp</title>
</head>
<body>
<?php
	include_once "./allatutil.php";

	// Set CrossKey 
	// -------------------------------------------------------------------
	$at_cross_key		= "";	// 가맹점 CrossKey값

	// Set Value
	// -------------------------------------------------------------------
	$at_shop_id			= "";	// ShopId값(최대 20Byte)
	$at_cash_bill_no	= "";	// 현금영수증일련번호(최대 10Byte)
	$at_supply_amt		= "";	// 취소공급가액(최대 10Byte)
	$at_vat_amt			= "";	// 취소VAT금액(최대 10Byte)
	$at_reg_business_no	= "";	// 등록할사업자번호(최대 10Byte):상점 ID와 다른경우

	// set Enc Data
	// -------------------------------------------------------------------
	$at_enc=setValue($at_enc,"allat_shop_id",$at_shop_id);
	$at_enc=setValue($at_enc,"allat_cash_bill_no",$at_cash_bill_no);
	$at_enc=setValue($at_enc,"allat_supply_amt",$at_supply_amt);
	$at_enc=setValue($at_enc,"allat_vat_amt",$at_vat_amt);
	$at_enc=setValue($at_enc,"allat_reg_business_no",$at_reg_business_no);
	$at_enc=setValue($at_enc,"allat_test_yn","N");		// 테스트 :Y, 서비스 :N
	$at_enc=setValue($at_enc,"allat_opt_pin","NOUSE");	// 수정금지(올앳 참조 필드)
	$at_enc=setValue($at_enc,"allat_opt_mod","APP");	// 수정금지(올앳 참조 필드)

	// Set Request Data
	//---------------------------------------------------------------------
	$at_data   = "allat_shop_id=".$at_shop_id.
				"&allat_enc_data=".$at_enc.
				"&allat_cross_key=".$at_cross_key;

	// 올앳과 통신 후 결과값 받기 : CashCanReq->통신함수
	//-----------------------------------------------------------------
	$at_txt = CashCanReq($at_data,"SSL");

	// 결과값
	//----------------------------------------------------------------
	$REPLYCD     = getValue("reply_cd",$at_txt);       //결과코드
	$REPLYMSG    = getValue("reply_msg",$at_txt);      //결과 메세지

	// 결과값 처리
	//--------------------------------------------------------------------------
	// 결과 값이 '0000'이면 정상임. 단, allat_test_yn=Y 일경우 '0001'이 정상임.
	// 실제 결제   : allat_test_yn=N 일 경우 reply_cd=0000 이면 정상
	// 테스트 결제 : allat_test_yn=Y 일 경우 reply_cd=0001 이면 정상
	//--------------------------------------------------------------------------
	if( !strcmp($REPLYCD,"0000") ){
		// reply_cd "0000" 일때만 성공
		$CANCEL_YMDHMS    =getValue("cancel_ymdhms",$at_txt);
		$PART_CANCEL_FLAG =getValue("part_cancel_flag",$at_txt);
		$REMAIN_AMT       =getValue("remain_amt",$at_txt);

		echo "결과코드	: ".$REPLYCD."<br>";
		echo "결과메세지	: ".$REPLYMSG."<br>";
		echo "취소일시	: ".$CANCEL_YMDHMS."<br>";
		echo "취소여부	: ".$PART_CANCEL_FLAG."<br>"; //취소: 0, 부분취소: 1
		echo "잔액		: ".$REMAIN_AMT."<br>";
	}else{
		// reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
		// reply_msg 가 실패에 대한 메세지
		echo "결과코드	: ".$REPLYCD."<br>";
		echo "결과메세지	: ".$REPLYMSG."<br>";
	}
?>
</body>
</html>
