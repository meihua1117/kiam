<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<title>EscrowCheckPhp</title>
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
	$at_escrow_send_no		= "";	// 운송장번호(최대 50Byte)
	$at_escrow_express_nm	= "";	// 택배사(최대 30Byte)
	$at_pay_type			= "";	// 원거래건의 결제방식[카드:CARD,계좌이체:ABANK]
	$at_seq_no				= "";	// 거래일련번호(최대 10Byte) : 옵션필드임

	// set Enc Data
	// -------------------------------------------------------------------
	$at_enc=setValue($at_enc,"allat_shop_id",$at_shop_id);
	$at_enc=setValue($at_enc,"allat_order_no",$at_order_no);
	$at_enc=setValue($at_enc,"allat_escrow_send_no",$at_escrow_send_no);
	$at_enc=setValue($at_enc,"allat_escrow_express_nm",$at_escrow_express_nm);
	$at_enc=setValue($at_enc,"allat_pay_type",$at_pay_type);
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
	$at_txt=EscrowChkReq($at_data,"SSL");

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
		$ESCROWCHECK_YMDSHMS=getValue("escrow_check_ymdhms",$at_txt);

		echo "결과코드			: ".$REPLYCD."<br>";
		echo "결과메세지			: ".$REPLYMSG."<br>";
		echo "에스크로 배송 개시일	: ".$ESCROWCHECK_YMDSHMS."<br>";
	} else {
		// reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
		// reply_msg 가 실패에 대한 메세지
		echo "결과코드			: ".$REPLYCD."<br>";
		echo "결과메세지			: ".$REPLYMSG."<br>";
	}
?>
</body>
</html>
