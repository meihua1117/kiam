<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<title>EscrowReturnPhp</title>
</head>
<body>
<?php
	include_once "./allatutil.php";

	// Set CrossKey 
	// -------------------------------------------------------------------
	$at_cross_key			= "";	// 가맹점 CrossKey값

	// Set Value
	// -------------------------------------------------------------------
	$at_shop_id				= "";	// ShopId값(최대 20Byte)
	$at_order_no			= "";	// 주문번호(최대 80Byte)
	$at_pay_type			= "";	// 원거래건의 결제방식[카드:CARD,계좌이체:ABANK]
	$at_es_return_flag		= "";	// 반품처리코드(교환처리 : C, 반품처리 : R)
	$at_return_ymd			= "";	// 입금교환일(최대 8Byte)
	$at_custom_bank_nm		= "";	// 고객환불은행(최대 20Byte) : 반품처리시 필수 필드
	$at_custom_account_no	= "";	// 고객환불계좌(최대 24Byte) : 반품처리시 필수 필드
	$at_return_amt			= "";	// 입금금액(최대 10Byte) : 반품처리시 필수 필드
	$at_return_addr			= "";	// 교환처리주소(최대 120Byte) : 교환처리시 필수 필드
	$at_return_express_nm	= "";	// 이용택배사(최대 50Byte) : 교환처리시 필수 필드
	$at_return_send_no		= "";	// 운송장번호(최대 24Byte) : 교환처리시 필수 필드
	$at_custom_tel_no		= "";	// 고객연락처(최대 20Byte) : 교환처리시 필수 필드
	$at_seq_no				= "";	// 거래일련번호(최대 10Byte) : 옵션필드임

	// set Enc Data
	// -------------------------------------------------------------------
	$at_enc=setValue($at_enc,"allat_shop_id",$at_shop_id);
	$at_enc=setValue($at_enc,"allat_order_no",$at_order_no);
	$at_enc=setValue($at_enc,"allat_pay_type",$at_pay_type);
	$at_enc=setValue($at_enc,"allat_es_return_flag",$at_es_return_flag);
	$at_enc=setValue($at_enc,"allat_return_ymd",$at_return_ymd);
	$at_enc=setValue($at_enc,"allat_custom_bank_nm",$at_custom_bank_nm);
	$at_enc=setValue($at_enc,"allat_custom_account_no",$at_custom_account_no);
	$at_enc=setValue($at_enc,"allat_return_amt",$at_return_amt);
	$at_enc=setValue($at_enc,"allat_return_addr",$at_return_addr);
	$at_enc=setValue($at_enc,"allat_return_express_nm",$at_return_express_nm);
	$at_enc=setValue($at_enc,"allat_return_send_no",$at_return_send_no);
	$at_enc=setValue($at_enc,"allat_custom_tel_no",$at_custom_tel_no);
	$at_enc=setValue($at_enc,"allat_seq_no",$at_seq_no);
	$at_enc=setValue($at_enc,"allat_test_yn","N");		// 테스트 :Y, 서비스 :N
	$at_enc=setValue($at_enc,"allat_opt_pin","NOUSE");	// 수정금지(올앳 참조 필드)
	$at_enc=setValue($at_enc,"allat_opt_mod","APP");	// 수정금지(올앳 참조 필드)

	// Set Request Data
	//---------------------------------------------------------------------
	$at_data   = "allat_shop_id=".$at_shop_id.
				"&allat_enc_data=".$at_enc.
				"&allat_cross_key=".$at_cross_key;

	// 올앳과 통신 후 결과값 받기 : CancelReq->통신함수
	//-----------------------------------------------------------------
	$at_txt=EscrowRetReq($at_data,"SSL");

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
		echo "결과코드	: ".$REPLYCD."<br>";
		echo "결과메세지	: ".$REPLYMSG."<br>";
	} else {
		// reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
		// reply_msg 가 실패에 대한 메세지
		echo "결과코드	: ".$REPLYCD."<br>";
		echo "결과메세지	: ".$REPLYMSG."<br>";
	}
?>
</body>
</html>
