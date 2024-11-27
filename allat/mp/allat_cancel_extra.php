<?php
  // �þܰ��� �Լ� Include
  //----------------------
  include "./allatutil.php";

	$at_enc       = "";
	$at_data      = "";
	$at_txt       = "";


	// �ʼ� �׸�
	$at_cross_key      = "CrossKey";	//������ Cross Key
    $at_shop_id        = "ShopId";		//����ID(�ִ� 20��)
    $at_cross_key = "304f3a821cac298ff8a0ef504e1c2309";	//�����ʿ� [����Ʈ ���� - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
    $at_shop_id   = "bwelcome12";		//�����ʿ�      
    $at_amt            = "1000";		//�ݾ�(�ִ� 10��)
    $at_order_no       = "test0001";	//�ֹ���ȣ(�ִ� 80��)         : ���θ� ���� �ֹ���ȣ 
	$at_pay_type       = "CARD";		//���ŷ����� �������[CARD]
	$at_seq_no         = "";			//�ŷ��Ϸù�ȣ (�ִ�  10�ڸ�) : �ɼ��ʵ���
	
    $at_enc = setValue($at_enc,"allat_shop_id"          ,   $at_shop_id        );
	$at_enc = setValue($at_enc,"allat_amt"              ,   $at_amt            );
	$at_enc = setValue($at_enc,"allat_order_no"         ,   $at_order_no       );
	$at_enc = setValue($at_enc,"allat_pay_type"         ,   $at_pay_type       );
	$at_enc = setValue($at_enc,"allat_seq_no"           ,   $at_seq_no         );
    $at_enc = setValue($at_enc,"allat_test_yn"          ,   "N"                );  //�׽�Ʈ :Y, ���� :N
    $at_enc = setValue($at_enc,"allat_opt_pin"          ,   "NOUSE"            );  //��������(�þ� ���� �ʵ�)
    $at_enc = setValue($at_enc,"allat_opt_mod"          ,   "APP"              );  //��������(�þ� ���� �ʵ�)

	$at_data = "allat_shop_id=".$at_shop_id.
			   "&allat_amt=".$at_amt.
			   "&allat_enc_data=".$at_enc.
			   "&allat_cross_key=".$at_cross_key;
	
	// �þ� ���� ������ ��� : ApprovalReq->����Լ�, $at_txt->�����
	//----------------------------------------------------------------
	$at_txt = CancelReq($at_data,"SSL");

	// ���� ��� �� Ȯ��
	//------------------
	$REPLYCD   =getValue("reply_cd",$at_txt);        //����ڵ�
	$REPLYMSG  =getValue("reply_msg",$at_txt);       //��� �޼���

	if( $REPLYCD == "0000" ){
		// reply_cd "0000" �϶��� ����
		$CANCEL_YMDHMS    =getValue("cancel_ymdhms",$at_txt);
		$PART_CANCEL_FLAG =getValue("part_cancel_flag",$at_txt);
		$REMAIN_AMT       =getValue("remain_amt",$at_txt);
		$PAY_TYPE         =getValue("pay_type",$at_txt);

		echo "����ڵ�              : ".$REPLYCD."<br>";
		echo "����޼���            : ".$REPLYMSG."<br>";
		echo "��ҳ�¥              : ".$CANCEL_YMDHMS."<br>";
		echo "��ұ���              : ".$PART_CANCEL_FLAG."<br>";
		echo "�ܾ�                  : ".$REMAIN_AMT."<br>";
		echo "�ŷ���ı���          : ".$PAY_TYPE."<br>";
	}else{
		// reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
		// reply_msg �� ���п� ���� �޼���
		echo "����ڵ�  : ".$REPLYCD."<br>";
		echo "����޼���: ".$REPLYMSG."<br>";
	}

?>
