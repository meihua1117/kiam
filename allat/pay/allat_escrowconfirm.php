<?php
	// �þܰ��� �Լ� Include
	//----------------------
	include_once "./allatutil.php";

	//Request Value Define
	//----------------------
	$at_cross_key = "������ CrossKey";     //�����ʿ� [����Ʈ ���� - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
	$at_shop_id   = "������ ShopId";       //�����ʿ�

	// ��û ������ ����
	//----------------------
	$at_data	= "allat_shop_id=".$at_shop_id.
				"&allat_enc_data=".$_POST["allat_enc_data"].
				"&allat_cross_key=".$at_cross_key;


	// �þ� ���� ������ ��� : ApprovalReq->����Լ�, $at_txt->�����
	//----------------------------------------------------------------
	// PHP5 �̻� SSL ��밡��
	$at_txt = EscrowConfirmReq($at_data,"SSL");
	// $at_txt = ApprovalReq($at_data, "NOSSL"); // PHP5 ���Ϲ����� ���
	// �� �κп��� �α׸� ����� ���� �����ϴ�.
	// (�þ� ���� ������ ��� �Ŀ� �α׸� �����, ��ſ����� ���� �����ľ��� �����մϴ�.)

	// ���� ��� �� Ȯ��
	//------------------
	$REPLYCD   =getValue("reply_cd",$at_txt);        //����ڵ�
	$REPLYMSG  =getValue("reply_msg",$at_txt);       //��� �޼���

	// ����� ó��
	//--------------------------------------------------------------------------
	// ��� ���� '0000'�̸� ������. ��, allat_test_yn=Y �ϰ�� '0001'�� ������.
	// ���� ����   : allat_test_yn=N �� ��� reply_cd=0000 �̸� ����
	// �׽�Ʈ ���� : allat_test_yn=Y �� ��� reply_cd=0001 �̸� ����
	//--------------------------------------------------------------------------
	if( $REPLYCD == "0000" ){
		// reply_cd "0000" �϶��� ����
		$ES_CONFIRM_YN	= getValue("es_confirm_yn",$at_txt);
		$ES_REJECT		= getValue("es_reject",$at_txt);

		echo "����ڵ�		: ".$REPLYCD."<br>";
		echo "����޼���		: ".$REPLYMSG."<br>";
		echo "���Ű���		: ".$ES_CONFIRM_YN."<br>";
		echo "���Űźλ���	: ".$ES_REJECT."<br>";
	}else{
		// reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
		// reply_msg �� ���п� ���� �޼���
		echo "����ڵ�		: ".$REPLYCD."<br>";
		echo "����޼���		: ".$REPLYMSG."<br>";
	}
?>