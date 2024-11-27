<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<title>CancelPhp</title>
</head>
<body>
<?php
	include_once "./allatutil.php";

	// Set CrossKey 
	// -------------------------------------------------------------------
	$at_cross_key	= "";	// ������ CrossKey��

	// Set Value
	// -------------------------------------------------------------------
	$at_shop_id		= "";	// ShopId��(�ִ� 20Byte)
	$at_order_no	= "";	// �ֹ���ȣ(�ִ� 80Byte)
	$at_amt			= "";	// ��ұݾ�(�ִ� 10Byte)
	$at_pay_type	= "";	// ���ŷ����� �������[ī��:CARD,������ü:ABANK]
	$at_seq_no		= "";	// �ŷ��Ϸù�ȣ(�ִ� 10Byte) : �ɼ��ʵ���

	// set Enc Data
	// -------------------------------------------------------------------
	$at_enc=setValue($at_enc,"allat_shop_id",$at_shop_id);
	$at_enc=setValue($at_enc,"allat_order_no",$at_order_no);
	$at_enc=setValue($at_enc,"allat_amt",$at_amt);
	$at_enc=setValue($at_enc,"allat_pay_type",$at_pay_type);
	$at_enc=setValue($at_enc,"allat_seq_no",$at_seq_no);
	$at_enc=setValue($at_enc,"allat_test_yn","N");		// �׽�Ʈ :Y, ���� :N
	$at_enc=setValue($at_enc,"allat_opt_pin","NOUSE");	// ��������(�þ� ���� �ʵ�)
	$at_enc=setValue($at_enc,"allat_opt_mod","APP");	// ��������(�þ� ���� �ʵ�)

	// Set Request Data
	//---------------------------------------------------------------------
	$at_data   = "allat_shop_id=".$at_shop_id.
				"&allat_enc_data=".$at_enc.
				"&allat_cross_key=".$at_cross_key;

	// �þܰ� ��� �� ����� �ޱ� : CancelReq->����Լ�
	//-----------------------------------------------------------------
	$at_txt=CancelReq($at_data,"SSL");

	// �����
	//----------------------------------------------------------------
	$REPLYCD     = getValue("reply_cd",$at_txt);	//����ڵ�
	$REPLYMSG    = getValue("reply_msg",$at_txt);	//��� �޼���

	// ����� ó��
	//------------------------------------------------------------------
	if( $REPLYCD == "0000" ){
		// reply_cd "0000" �϶��� ����
		$CANCEL_YMDHMS=getValue("cancel_ymdhms",$at_txt);
		$PART_CANCEL_FLAG=getValue("part_cancel_flag",$at_txt);
		$REMAIN_AMT=getValue("remain_amt",$at_txt);
		$PAY_TYPE=getValue("pay_type",$at_txt);

		echo "����ڵ�		: ".$REPLYCD."<br>";
		echo "����޼���		: ".$REPLYMSG."<br>";
		echo "��ҳ�¥		: ".$CANCEL_YMDHMS."<br>";
		echo "��ұ���		: ".$PART_CANCEL_FLAG."<br>";
		echo "�ܾ�			: ".$REMAIN_AMT."<br>";
		echo "�ŷ���ı���	: ".$PAY_TYPE."<br>";
	} else {
		// reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
		// reply_msg �� ���п� ���� �޼���
		echo "����ڵ�		: ".$REPLYCD."<br>";
		echo "����޼���		: ".$REPLYMSG."<br>";
	}
?>
</body>
</html>
