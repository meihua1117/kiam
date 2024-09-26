<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<title>CashRegistryPhp</title>
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
	$at_reg_business_no	= "";	// 등록할사업자번호(최대 10Byte)
	$at_apply_ymdhms	= "";	// 거래요청일자(최대 14Byte)

	// set Enc Data
	// -------------------------------------------------------------------
	$at_enc=setValue($at_enc,"allat_shop_id",$at_shop_id);
	$at_enc=setValue($at_enc,"allat_reg_business_no",$at_reg_business_no);
	$at_enc=setValue($at_enc,"allat_apply_ymdhms",$at_apply_ymdhms);
	$at_enc=setValue($at_enc,"allat_test_yn","N");		// 테스트 :Y, 서비스 :N
	$at_enc=setValue($at_enc,"allat_opt_pin","NOUSE");	// 수정금지(올앳 참조 필드)
	$at_enc=setValue($at_enc,"allat_opt_mod","APP");	// 수정금지(올앳 참조 필드)

	// Set Request Data
	//---------------------------------------------------------------------
	$at_data   = "allat_shop_id=".$at_shop_id.
				"&allat_enc_data=".$at_enc.
				"&allat_cross_key=".$at_cross_key;

	// 올앳과 통신 후 결과값 받기 : CashAppReq->통신함수
	//-----------------------------------------------------------------
	$at_txt=CashRegReq($at_data,"SSL");

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
		echo "결과코드			: ".$REPLYCD."<br>";
		echo "결과메세지			: ".$REPLYMSG."<br>";
	}else{
		// reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
		// reply_msg 가 실패에 대한 메세지
		echo "결과코드	: ".$REPLYCD."<br>";
		echo "결과메세지	: ".$REPLYMSG."<br>";
	}
?>
</body>
</html>
