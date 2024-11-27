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
	$at_cross_key		= "";	// ������ CrossKey��

	// Set Value
	// -------------------------------------------------------------------
	$at_shop_id			= "";	// ShopId��(�ִ� 20Byte)
	$at_cash_bill_no	= "";	// ���ݿ������Ϸù�ȣ(�ִ� 10Byte)
	$at_supply_amt		= "";	// ��Ұ��ް���(�ִ� 10Byte)
	$at_vat_amt			= "";	// ���VAT�ݾ�(�ִ� 10Byte)
	$at_reg_business_no	= "";	// ����һ���ڹ�ȣ(�ִ� 10Byte):���� ID�� �ٸ����

	// set Enc Data
	// -------------------------------------------------------------------
	$at_enc=setValue($at_enc,"allat_shop_id",$at_shop_id);
	$at_enc=setValue($at_enc,"allat_cash_bill_no",$at_cash_bill_no);
	$at_enc=setValue($at_enc,"allat_supply_amt",$at_supply_amt);
	$at_enc=setValue($at_enc,"allat_vat_amt",$at_vat_amt);
	$at_enc=setValue($at_enc,"allat_reg_business_no",$at_reg_business_no);
	$at_enc=setValue($at_enc,"allat_test_yn","N");		// �׽�Ʈ :Y, ���� :N
	$at_enc=setValue($at_enc,"allat_opt_pin","NOUSE");	// ��������(�þ� ���� �ʵ�)
	$at_enc=setValue($at_enc,"allat_opt_mod","APP");	// ��������(�þ� ���� �ʵ�)

	// Set Request Data
	//---------------------------------------------------------------------
	$at_data   = "allat_shop_id=".$at_shop_id.
				"&allat_enc_data=".$at_enc.
				"&allat_cross_key=".$at_cross_key;

	// �þܰ� ��� �� ����� �ޱ� : CashCanReq->����Լ�
	//-----------------------------------------------------------------
	$at_txt = CashCanReq($at_data,"SSL");

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
		$CANCEL_YMDHMS    =getValue("cancel_ymdhms",$at_txt);
		$PART_CANCEL_FLAG =getValue("part_cancel_flag",$at_txt);
		$REMAIN_AMT       =getValue("remain_amt",$at_txt);

		echo "����ڵ�	: ".$REPLYCD."<br>";
		echo "����޼���	: ".$REPLYMSG."<br>";
		echo "����Ͻ�	: ".$CANCEL_YMDHMS."<br>";
		echo "��ҿ���	: ".$PART_CANCEL_FLAG."<br>"; //���: 0, �κ����: 1
		echo "�ܾ�		: ".$REMAIN_AMT."<br>";
	}else{
		// reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
		// reply_msg �� ���п� ���� �޼���
		echo "����ڵ�	: ".$REPLYCD."<br>";
		echo "����޼���	: ".$REPLYMSG."<br>";
	}
?>
</body>
</html>
