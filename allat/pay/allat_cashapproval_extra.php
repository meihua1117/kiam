<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<title>CashApprovalPhp</title>
</head>
<body>
<?php
	include_once "./allatutil.php";

	// Set CrossKey 
	// -------------------------------------------------------------------
	$at_cross_key="";		// ������ CrossKey��

	// Set Value
	// -------------------------------------------------------------------
	$at_shop_id			= "";	// ShopId��(�ִ� 20Byte)
	$at_apply_ymdhms	= "";	// �ŷ���û����(�ִ� 14Byte)
	$at_shop_member_id	= "";	// ���θ��� ȸ��ID(�ִ� 20Byte)
	$at_cert_no			= "";	// ��������(�ִ� 13Byte) : �ڵ�����ȣ,�ֹι�ȣ,����ڹ�ȣ
	$at_supply_amt		= "";	// ���ް���(�ִ� 10Byte) : ���� + �鼼
	$at_vat_amt			= "";	// VAT�ݾ�(�ִ� 10Byte)
	$at_product_nm		= "";	// ��ǰ��(�ִ� 100Byte)
	$at_receipt_type	= "";	// ���ݿ���������(�ִ� 6Byte):������ü(ABANK),������(NBANK)
	$at_seq_no			= "";	// �ŷ��Ϸù�ȣ(�ִ� 10Byte)
	$at_reg_business_no	= "";	// ����һ���ڹ�ȣ(�ִ� 10Byte):���� ID�� �ٸ����
	$at_buyer_ip		= "";	// ������IP(�ִ� 15Byte) : BuyerIp�� ������ ���ٸ� "Unknown"���� ����

	// set Enc Data
	// -------------------------------------------------------------------
	$at_enc=setValue($at_enc,"allat_shop_id",$at_shop_id);
	$at_enc=setValue($at_enc,"allat_apply_ymdhms",$at_apply_ymdhms);
	$at_enc=setValue($at_enc,"allat_shop_member_id",$at_shop_member_id);
	$at_enc=setValue($at_enc,"allat_cert_no",$at_cert_no);
	$at_enc=setValue($at_enc,"allat_supply_amt",$at_supply_amt);
	$at_enc=setValue($at_enc,"allat_vat_amt",$at_vat_amt);
	$at_enc=setValue($at_enc,"allat_product_nm",$at_product_nm);
	$at_enc=setValue($at_enc,"allat_receipt_type",$at_receipt_type);
	$at_enc=setValue($at_enc,"allat_seq_no",$at_seq_no);
	$at_enc=setValue($at_enc,"allat_reg_business_no",$at_reg_business_no);
	$at_enc=setValue($at_enc,"allat_buyer_ip",$at_buyer_ip);
	$at_enc=setValue($at_enc,"allat_test_yn","N");		// �׽�Ʈ :Y, ���� :N
	$at_enc=setValue($at_enc,"allat_opt_pin","NOUSE");	// ��������(�þ� ���� �ʵ�)
	$at_enc=setValue($at_enc,"allat_opt_mod","APP");	// ��������(�þ� ���� �ʵ�)

	// Set Request Data
	//---------------------------------------------------------------------
	$at_data   = "allat_shop_id=".$at_shop_id.
				"&allat_enc_data=".$at_enc.
				"&allat_cross_key=".$at_cross_key;

	// �þܰ� ��� �� ����� �ޱ� : CashAppReq->����Լ�
	//-----------------------------------------------------------------
	$at_txt=CashAppReq($at_data,"SSL");

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
		$APPROVAL_NO  =getValue("approval_no",$at_txt);
		$CASH_BILL_NO =getValue("cash_bill_no",$at_txt);

		echo "����ڵ�			: ".$REPLYCD."<br>";
		echo "����޼���			: ".$REPLYMSG."<br>";
		echo "���ݿ����� �Ϸù�ȣ	: ".$CASH_BILL_NO."<br>";
		echo "���ݿ����� ���ι�ȣ	: ".$APPROVAL_NO."<br>";		
	}else{
		// reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
		// reply_msg �� ���п� ���� �޼���
		echo "����ڵ�	: ".$REPLYCD."<br>";
		echo "����޼���	: ".$REPLYMSG."<br>";
	}
?>
</body>
</html>
