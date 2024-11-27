<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<title>SanctionPhp</title>
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
	$at_seq_no		= "";	// �ŷ��Ϸù�ȣ(�ִ� 10Byte) : �ɼ��ʵ���

	// set Enc Data
	// -------------------------------------------------------------------
	$at_enc=setValue($at_enc,"allat_shop_id",$at_shop_id);
	$at_enc=setValue($at_enc,"allat_order_no",$at_order_no);
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
	$at_txt=SanctionReq($at_data,"SSL");

	// �����
	//----------------------------------------------------------------
	$REPLYCD     = getValue("reply_cd",$at_txt);	//����ڵ�
	$REPLYMSG    = getValue("reply_msg",$at_txt);	//��� �޼���

	// ����� ó��
	//------------------------------------------------------------------
	if( $REPLYCD == "0000" ){
		// reply_cd "0000" �϶��� ����
		$SANCTION_YMDHMS=getValue("sanction_ymdhms",$at_txt);

		echo "����ڵ�	: ".$REPLYCD."<br>";
		echo "����޼���	: ".$REPLYMSG."<br>";
		echo "���Գ�¥	: ".$SANCTION_YMDHMS."<br>";
	} else {
		// reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
		// reply_msg �� ���п� ���� �޼���
		echo "����ڵ�	: ".$REPLYCD."<br>";
		echo "����޼���	: ".$REPLYMSG."<br>";
	}
?>
</body>
</html>
