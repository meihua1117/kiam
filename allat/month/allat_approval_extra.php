<?php
  // �þܰ��� �Լ� Include
  //----------------------
  include_once "./allatutil.php";

    $at_enc       = "";
    $at_data      = "";
    $at_txt       = "";


    // �ʼ� �׸�
    $at_cross_key      = "";   //CrossKey��(�ִ�200��)
    $at_fix_key        = "";   //ī��Ű(�ִ� 24��)
    $at_sell_mm        = "";   //�Һΰ�����(�ִ�  2��)
    $at_amt            = "";   //�ݾ�(�ִ� 10��)
    $at_business_type  = "";   //������ ī������(�ִ� 1��)       : ����(0),����(1)
    $at_registry_no    = "";   //�ֹι�ȣ(�ִ� 13�ڸ�)           : szBusinessType=0 �ϰ��
    $at_biz_no         = "";   //����ڹ�ȣ(�ִ� 20�ڸ�)         : szBusinessType=1 �ϰ��
    $at_shop_id        = "";   //����ID(�ִ� 20��)
    $at_shop_member_id = "";   //ȸ��ID(�ִ� 20��)               : ���θ�ȸ��ID
    $at_order_no       = "";   //�ֹ���ȣ(�ִ� 80��)             : ���θ� ���� �ֹ���ȣ
    $at_product_cd     = "";   //��ǰ�ڵ�(�ִ� 1000��)           : ���� ��ǰ�� ��� ������ �̿�, ������('||':������ 2��)
    $at_product_nm     = "";   //��ǰ��(�ִ� 1000��)             : ���� ��ǰ�� ��� ������ �̿�, ������('||':������ 2��)
    $at_cardcert_yn    = "";   //ī����������(�ִ� 1��)          : ����(Y),����������(N),���������(X)
    $at_zerofee_yn     = "";   //�Ϲ�/������ �Һ� ��� ����(�ִ� 1��) : �Ϲ�(N), ������ �Һ�(Y)
    $at_buyer_nm       = "";   //�����ڼ���(�ִ� 20��)
    $at_recp_nm        = "";   //�����μ���(�ִ� 20��)
    $at_recp_addr      = "";   //�������ּ�(�ִ� 120��)
    $at_buyer_ip       = "";   //������ IP(�ִ�15��) - BuyerIp�� ������ ���ٸ� "Unknown"���� ����
    $at_email_addr     = "";   //������ �̸��� �ּ�(256��)
    $at_bonus_yn       = "";   //���ʽ�����Ʈ ��뿩��(�ִ�1��)  : ���(Y), ������(N)
    $at_gender         = "";   //������ ����(�ִ� 1��)           : ����(M)/����(F)
    $at_birth_ymd      = "";   //�������� �������(�ִ� 8��)     : YYYYMMDD����

    $at_enc = setValue($at_enc,"allat_card_key"         ,   $at_fix_key        );
    $at_enc = setValue($at_enc,"allat_sell_mm"          ,   $at_sell_mm        );
    $at_enc = setValue($at_enc,"allat_amt"              ,   $at_amt            );
    $at_enc = setValue($at_enc,"allat_business_type"    ,   $at_business_type  );
    if( strcmp($at_business_type,"0") == 0 ){
        $at_enc = setValue($at_enc,"allat_registry_no"  ,   $at_registry_no    );
    }else{
        $at_enc = setValue($at_enc,"allat_biz_no"       ,   $at_biz_no         );
    }
    $at_enc = setValue($at_enc,"allat_shop_id"          ,   $at_shop_id        );
    $at_enc = setValue($at_enc,"allat_shop_member_id"   ,   $at_shop_member_id );
    $at_enc = setValue($at_enc,"allat_order_no"         ,   $at_order_no       );
    $at_enc = setValue($at_enc,"allat_product_cd"       ,   $at_product_cd     );
    $at_enc = setValue($at_enc,"allat_product_nm"       ,   $at_product_nm     );
    $at_enc = setValue($at_enc,"allat_cardcert_yn"      ,   $at_cardcert_yn    );
    $at_enc = setValue($at_enc,"allat_zerofee_yn"       ,   $at_zerofee_yn     );
    $at_enc = setValue($at_enc,"allat_buyer_nm"         ,   $at_buyer_nm       );
    $at_enc = setValue($at_enc,"allat_recp_name"        ,   $at_recp_nm        );
    $at_enc = setValue($at_enc,"allat_recp_addr"        ,   $at_recp_addr      );
    $at_enc = setValue($at_enc,"allat_user_ip"          ,   $at_buyer_ip       );
    $at_enc = setValue($at_enc,"allat_email_addr"       ,   $at_email_addr     );
    $at_enc = setValue($at_enc,"allat_bonus_yn"         ,   $at_bonus_yn       );
    $at_enc = setValue($at_enc,"allat_gender"           ,   $at_gender         );
    $at_enc = setValue($at_enc,"allat_birth_ymd"        ,   $at_birth_ymd      );
    $at_enc = setValue($at_enc,"allat_pay_type"         ,   "FIX"              );  //��������(������� ����)
    $at_enc = setValue($at_enc,"allat_test_yn"          ,   "N"                );  //�׽�Ʈ :Y, ���� :N
    $at_enc = setValue($at_enc,"allat_opt_pin"          ,   "NOUSE"            );  //��������(�þ� ���� �ʵ�)
    $at_enc = setValue($at_enc,"allat_opt_mod"          ,   "APP"              );  //��������(�þ� ���� �ʵ�)

    $at_data = "allat_shop_id=".$at_shop_id.
               "&allat_amt=".$at_amt.
               "&allat_enc_data=".$at_enc.
               "&allat_cross_key=".$at_cross_key;

    // �þ� ���� ������ ��� : ApprovalReq->����Լ�, $at_txt->�����
    //----------------------------------------------------------------
    $at_txt = ApprovalReq($at_data,"SSL");

    // ���� ��� �� Ȯ��
    //------------------
    $REPLYCD   =getValue("reply_cd",$at_txt);        //����ڵ�
    $REPLYMSG  =getValue("reply_msg",$at_txt);       //��� �޼���

    if( $REPLYCD == "0000" ){
        // reply_cd "0000" �϶��� ����
        $ORDER_NO         =getValue("order_no",$at_txt);
        $AMT              =getValue("amt",$at_txt);
        $PAY_TYPE         =getValue("pay_type",$at_txt);
        $APPROVAL_YMDHMS  =getValue("approval_ymdhms",$at_txt);
        $SEQ_NO           =getValue("seq_no",$at_txt);
        $APPROVAL_NO      =getValue("approval_no",$at_txt);
        $CARD_ID          =getValue("card_id",$at_txt);
        $CARD_NM          =getValue("card_nm",$at_txt);
        $SELL_MM          =getValue("sell_mm",$at_txt);
        $ZEROFEE_YN       =getValue("zerofee_yn",$at_txt);
        $CERT_YN          =getValue("cert_yn",$at_txt);
        $CONTRACT_YN      =getValue("contract_yn",$at_txt);

        echo "����ڵ�              : ".$REPLYCD."<br>";
        echo "����޼���            : ".$REPLYMSG."<br>";
        echo "�ֹ���ȣ              : ".$ORDER_NO."<br>";
        echo "���αݾ�              : ".$AMT."<br>";
        echo "���Ҽ���              : ".$PAY_TYPE."<br>";
        echo "�����Ͻ�              : ".$APPROVAL_YMDHMS."<br>";
        echo "�ŷ��Ϸù�ȣ          : ".$SEQ_NO."<br>";
        echo "���ι�ȣ              : ".$APPROVAL_NO."<br>";
        echo "ī��ID                : ".$CARD_ID."<br>";
        echo "ī���                : ".$CARD_NM."<br>";
        echo "�Һΰ���              : ".$SELL_MM."<br>";
        echo "�����ڿ���            : ".$ZEROFEE_YN."<br>";   //������(Y),�Ͻú�(N)
        echo "��������              : ".$CERT_YN."<br>";      //����(Y),������(N)
        echo "�����Ϳ���            : ".$CONTRACT_YN."<br>";  //3�ڰ�����(Y),��ǥ������(N)
    }else{
        // reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
        // reply_msg �� ���п� ���� �޼���
        echo "����ڵ�  : ".$REPLYCD."<br>";
        echo "����޼���: ".$REPLYMSG."<br>";
    }

?>
