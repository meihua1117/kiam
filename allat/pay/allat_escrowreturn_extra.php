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
	$at_cross_key			= "";	// ������ CrossKey��

	// Set Value
	// -------------------------------------------------------------------
	$at_shop_id				= "";	// ShopId��(�ִ� 20Byte)
	$at_order_no			= "";	// �ֹ���ȣ(�ִ� 80Byte)
	$at_pay_type			= "";	// ���ŷ����� �������[ī��:CARD,������ü:ABANK]
	$at_es_return_flag		= "";	// ��ǰó���ڵ�(��ȯó�� : C, ��ǰó�� : R)
	$at_return_ymd			= "";	// �Աݱ�ȯ��(�ִ� 8Byte)
	$at_custom_bank_nm		= "";	// ����ȯ������(�ִ� 20Byte) : ��ǰó���� �ʼ� �ʵ�
	$at_custom_account_no	= "";	// ����ȯ�Ұ���(�ִ� 24Byte) : ��ǰó���� �ʼ� �ʵ�
	$at_return_amt			= "";	// �Աݱݾ�(�ִ� 10Byte) : ��ǰó���� �ʼ� �ʵ�
	$at_return_addr			= "";	// ��ȯó���ּ�(�ִ� 120Byte) : ��ȯó���� �ʼ� �ʵ�
	$at_return_express_nm	= "";	// �̿��ù��(�ִ� 50Byte) : ��ȯó���� �ʼ� �ʵ�
	$at_return_send_no		= "";	// ������ȣ(�ִ� 24Byte) : ��ȯó���� �ʼ� �ʵ�
	$at_custom_tel_no		= "";	// ��������ó(�ִ� 20Byte) : ��ȯó���� �ʼ� �ʵ�
	$at_seq_no				= "";	// �ŷ��Ϸù�ȣ(�ִ� 10Byte) : �ɼ��ʵ���

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
	$at_txt=EscrowRetReq($at_data,"SSL");

	// �����
	//----------------------------------------------------------------
	$REPLYCD     = getValue("reply_cd",$at_txt);       //����ڵ�
	$REPLYMSG    = getValue("reply_msg",$at_txt);      //��� �޼���

	// ����� ó��
	//--------------------------------------------------------------------------
	// ��� ���� '0000'�̸� ������. ��, allat_test_yn=Y �ϰ�� '0001'�� ������.
	// ���� ����   : allat_test_yn=N �� ��� reply_cd=0000 �̸� ����
	// �׽�Ʈ ���� : allat_test_yn=Y �� ��� reply_cd=0001 �̸� ����
	//--------------------------------------------------------------------------
	if( $REPLYCD == "0000" ){
		// reply_cd "0000" �϶��� ����
		echo "����ڵ�	: ".$REPLYCD."<br>";
		echo "����޼���	: ".$REPLYMSG."<br>";
	} else {
		// reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
		// reply_msg �� ���п� ���� �޼���
		echo "����ڵ�	: ".$REPLYCD."<br>";
		echo "����޼���	: ".$REPLYMSG."<br>";
	}
?>
</body>
</html>
