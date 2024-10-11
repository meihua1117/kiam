<?php

include_once "_head.php";
 

//$sql = "select * from tjd_pay_amount where pay_id = '$_GET[mid]'";
//$res_result = mysqli_query($self_con,$sql);
//$data = mysqli_fetch_array($res_result);  
//print_r($_SESSION);
  // �þܰ��� �Լ� Include
  //----------------------
  include_once "./allatutil.php";

  //Request Value Define
  //----------------------
  $at_cross_key = "382331665febf9857b8a8b47f9a02e04";	//�����ʿ� [����Ʈ ���� - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
  $at_shop_id   = "welcome101";		//�����ʿ�
  $at_amt=$_SESSION['allat_amt'];						//���� �ݾ��� �ٽ� ����ؼ� ������ ��(��ŷ����), ( session, DB ��� )

  // ��û ������ ����
  //----------------------
  $at_data   = "allat_shop_id=".$at_shop_id.
               "&allat_amt=".$at_amt.
               "&allat_enc_data=".$_POST["allat_enc_data"].
               "&allat_cross_key=".$at_cross_key;


  // �þ� ���� ������ ��� : ApprovalReq->����Լ�, $at_txt->�����
  //----------------------------------------------------------------
  // PHP5 �̻� SSL ��밡��
  $at_txt = ApprovalReq($at_data,"SSL");
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
  if( !strcmp($REPLYCD,"0000") ){
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
    $SAVE_AMT         =getValue("save_amt",$at_txt);
	$CARD_POINTDC_AMT =getValue("card_pointdc_amt",$at_txt);
    $BANK_ID          =getValue("bank_id",$at_txt);
    $BANK_NM          =getValue("bank_nm",$at_txt);
    $CASH_BILL_NO     =getValue("cash_bill_no",$at_txt);
    $ESCROW_YN        =getValue("escrow_yn",$at_txt);
    $ACCOUNT_NO       =getValue("account_no",$at_txt);
    $ACCOUNT_NM       =getValue("account_nm",$at_txt);
    $INCOME_ACC_NM    =getValue("income_account_nm",$at_txt);
    $INCOME_LIMIT_YMD =getValue("income_limit_ymd",$at_txt);
    $INCOME_EXPECT_YMD=getValue("income_expect_ymd",$at_txt);
    $CASH_YN          =getValue("cash_yn",$at_txt);
    $HP_ID            =getValue("hp_id",$at_txt);
    $TICKET_ID        =getValue("ticket_id",$at_txt);
    $TICKET_PAY_TYPE  =getValue("ticket_pay_type",$at_txt);
    $TICKET_NAME      =getValue("ticket_nm",$at_txt);	
    $PARTCANCEL_YN    =getValue("partcancel_yn",$at_txt);	

    echo "����ڵ�              : ".$REPLYCD."<br>";
    echo "����޼���            : ".$REPLYMSG."<br>";
    echo "�ֹ���ȣ              : ".$ORDER_NO."<br>";
    echo "���αݾ�              : ".$AMT."<br>";
    echo "���Ҽ���              : ".$PAY_TYPE."<br>";
    echo "�����Ͻ�              : ".$APPROVAL_YMDHMS."<br>";
    echo "�ŷ��Ϸù�ȣ          : ".$SEQ_NO."<br>";
    echo "����ũ�� ���� ����    : ".$ESCROW_YN."<br>";
    echo "=============== �ſ� ī�� ===============================<br>";
    echo "���ι�ȣ              : ".$APPROVAL_NO."<br>";
    echo "ī��ID                : ".$CARD_ID."<br>";
    echo "ī���                : ".$CARD_NM."<br>";
    echo "�Һΰ���              : ".$SELL_MM."<br>";
    echo "�����ڿ���            : ".$ZEROFEE_YN."<br>";   //������(Y),�Ͻú�(N)
    echo "��������              : ".$CERT_YN."<br>";      //����(Y),������(N)
    echo "�����Ϳ���            : ".$CONTRACT_YN."<br>";  //3�ڰ�����(Y),��ǥ������(N)
    echo "���̺� ���� �ݾ�      : ".$SAVE_AMT."<br>";
    echo "����Ʈ���� ���� �ݾ�  : ".$CARD_POINTDC_AMT."<br>";
    echo "=============== ���� ��ü / ������� ====================<br>";
    echo "����ID                : ".$BANK_ID."<br>";
    echo "�����                : ".$BANK_NM."<br>";
    echo "���ݿ����� �Ϸ� ��ȣ  : ".$CASH_BILL_NO."<br>";
    echo "=============== ������� ================================<br>";
    echo "���¹�ȣ              : ".$ACCOUNT_NO."<br>";
    echo "�Աݰ��¸�            : ".$INCOME_ACC_NM."<br>";
    echo "�Ա��ڸ�              : ".$ACCOUNT_NM."<br>";
    echo "�Աݱ�����            : ".$INCOME_LIMIT_YMD."<br>";
    echo "�Աݿ�����            : ".$INCOME_EXPECT_YMD."<br>";
    echo "���ݿ�������û ����   : ".$CASH_YN."<br>";
    echo "=============== �޴��� ���� =============================<br>";
    echo "�̵���Ż籸��        : ".$HP_ID."<br>";
    echo "=============== ��ǰ�� ���� =============================<br>";
    echo "��ǰ�� ID             : ".$TICKET_ID."<br>";
    echo "��ǰ�� �̸�           : ".$TICKET_NAME."<br>";
    echo "��������              : ".$TICKET_PAY_TYPE."<br>";

	echo "�κ���Ұ��ɿ��� : ".$PARTCANCEL_YN."<br>";

  }else{
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
    $SAVE_AMT         =getValue("save_amt",$at_txt);
	$CARD_POINTDC_AMT =getValue("card_pointdc_amt",$at_txt);
    $BANK_ID          =getValue("bank_id",$at_txt);
    $BANK_NM          =getValue("bank_nm",$at_txt);
    $CASH_BILL_NO     =getValue("cash_bill_no",$at_txt);
    $ESCROW_YN        =getValue("escrow_yn",$at_txt);
    $ACCOUNT_NO       =getValue("account_no",$at_txt);
    $ACCOUNT_NM       =getValue("account_nm",$at_txt);
    $INCOME_ACC_NM    =getValue("income_account_nm",$at_txt);
    $INCOME_LIMIT_YMD =getValue("income_limit_ymd",$at_txt);
    $INCOME_EXPECT_YMD=getValue("income_expect_ymd",$at_txt);
    $CASH_YN          =getValue("cash_yn",$at_txt);
    $HP_ID            =getValue("hp_id",$at_txt);
    $TICKET_ID        =getValue("ticket_id",$at_txt);
    $TICKET_PAY_TYPE  =getValue("ticket_pay_type",$at_txt);
    $TICKET_NAME      =getValue("ticket_nm",$at_txt);	
    $PARTCANCEL_YN    =getValue("partcancel_yn",$at_txt);	

    echo "����ڵ�              : ".$REPLYCD."<br>";
    echo "����޼���            : ".$REPLYMSG."<br>";
    echo "�ֹ���ȣ              : ".$ORDER_NO."<br>";
    echo "���αݾ�              : ".$AMT."<br>";
    echo "���Ҽ���              : ".$PAY_TYPE."<br>";
    echo "�����Ͻ�              : ".$APPROVAL_YMDHMS."<br>";
    echo "�ŷ��Ϸù�ȣ          : ".$SEQ_NO."<br>";
    echo "����ũ�� ���� ����    : ".$ESCROW_YN."<br>";
    echo "=============== �ſ� ī�� ===============================<br>";
    echo "���ι�ȣ              : ".$APPROVAL_NO."<br>";
    echo "ī��ID                : ".$CARD_ID."<br>";
    echo "ī���                : ".$CARD_NM."<br>";
    echo "�Һΰ���              : ".$SELL_MM."<br>";
    echo "�����ڿ���            : ".$ZEROFEE_YN."<br>";   //������(Y),�Ͻú�(N)
    echo "��������              : ".$CERT_YN."<br>";      //����(Y),������(N)
    echo "�����Ϳ���            : ".$CONTRACT_YN."<br>";  //3�ڰ�����(Y),��ǥ������(N)
    echo "���̺� ���� �ݾ�      : ".$SAVE_AMT."<br>";
    echo "����Ʈ���� ���� �ݾ�  : ".$CARD_POINTDC_AMT."<br>";
    echo "=============== ���� ��ü / ������� ====================<br>";
    echo "����ID                : ".$BANK_ID."<br>";
    echo "�����                : ".$BANK_NM."<br>";
    echo "���ݿ����� �Ϸ� ��ȣ  : ".$CASH_BILL_NO."<br>";
    echo "=============== ������� ================================<br>";
    echo "���¹�ȣ              : ".$ACCOUNT_NO."<br>";
    echo "�Աݰ��¸�            : ".$INCOME_ACC_NM."<br>";
    echo "�Ա��ڸ�              : ".$ACCOUNT_NM."<br>";
    echo "�Աݱ�����            : ".$INCOME_LIMIT_YMD."<br>";
    echo "�Աݿ�����            : ".$INCOME_EXPECT_YMD."<br>";
    echo "���ݿ�������û ����   : ".$CASH_YN."<br>";
    echo "=============== �޴��� ���� =============================<br>";
    echo "�̵���Ż籸��        : ".$HP_ID."<br>";
    echo "=============== ��ǰ�� ���� =============================<br>";
    echo "��ǰ�� ID             : ".$TICKET_ID."<br>";
    echo "��ǰ�� �̸�           : ".$TICKET_NAME."<br>";
    echo "��������              : ".$TICKET_PAY_TYPE."<br>";

	echo "�κ���Ұ��ɿ��� : ".$PARTCANCEL_YN."<br>";    
    // reply_cd �� "0000" �ƴҶ��� ���� (�ڼ��� ������ �Ŵ�������)
    // reply_msg �� ���п� ���� �޼���
    echo "����ڵ�  : ".$REPLYCD."<br>";
    echo "����޼���: ".$REPLYMSG."<br>";
  }

/*
    [�ſ�ī�� ��ǥ��� ����]

    ������ ���������� �Ϸ�Ǹ� �Ʒ��� �ҽ��� �̿��Ͽ�, �������� �ſ�ī�� ��ǥ�� ������ �� �ֽ��ϴ�.
    ��ǥ ��½� �������̵�� �ֹ���ȣ�� �����Ͻñ� �ٶ��ϴ�.

    var urls ="http://www.allatpay.com/servlet/AllatBizPop/member/pop_card_receipt.jsp?shop_id=�������̵�&order_no=�ֹ���ȣ";
    window.open(urls,"app","width=410,height=650,scrollbars=0");

    ���ݿ����� ��ǥ �Ǵ� �ŷ�Ȯ�μ� ��¿� ���� ���Ǵ� �þ����� ����Ʈ�� 1:1����� �̿��Ͻðų�
    02) 3788-9990 ���� ��ȭ �ֽñ� �ٶ��ϴ�.

    ��ǥ��� �������� ���� �þ� Ȩ�������� �Ϻην�, Ȩ������ ���� ���� ������ ���Ͽ� ������ ���� �Ǵ� URL ������ ���� ��
    �ֽ��ϴ�. Ȩ������ ������ ���� ������ ���� ���, ��ǥ��� URL�� Ȯ���Ͻñ� �ٶ��ϴ�.
*/
?>
<div class="big_main">
	<div class="big_1">
    	<div class="m_div">
        	<div class="left_sub_menu">
                <a href="./">Ȩ</a> > 
                <a href="pay_return.php">�������</a>
            </div>
            <div class="right_sub_menu">&nbsp;</div>
            <p style="clear:both;"></p>
    	</div>
   </div>
   <div class="m_div">
   		<div><img src="images/sub_02_visual_03.jpg" /></div>
       <div class="pay">      		
            <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                <td colspan="2" style="font-size:16px;">
                PG log</td>
                </tr>
                    <tr>
                    <td colspan="2" style="text-align:center;">
                    <h3><?
                    if($row[resultCode]=="0000")
                    {
                        if($row[payMethod]=="VBank")
                        echo "�Աݿ����ð����� �Ʒ� ������·� �Ա��Ͻø� ���Ű� �Ϸ�˴ϴ�.";
                        else
                        echo "������ ���������� �̷�������ϴ�.";
                    }
                    else
                        echo "���������Ͽ����ϴ�.�ٽ� �õ��Ͻðų� Ȩ������ �����ڿ� �����ϼ���.";
                    ?></h3></td>
                    </tr>
                <tr>
                <td>����ڵ�</td>
                <td><?=$row[resultCode]?></td>
                </tr>                    
                <tr>
                <td>����޽���</td>
                <td><?=iconv("euc-kr","utf-8",$inipay->GetResult('ResultMsg'))?></td>
                </tr>
                <tr>
                <td>TID</td>
                <td><?=$row[tid]?></td>
                </tr>                                                    
                <tr>
                <td>���Ҽ���</td>
                <td><?=$row[payMethod]?></td>
                </tr>
                <tr>
                <td>�ֹ���ȣ</td>
                <td><?=$row[orderNumber]?></td>
                </tr>                    
                <tr>
                <tr>
                <td>�����ڸ�</td>
                <td><?=$row[VACT_InputName]?></td>
                </tr>                                        
                <td>���ұݾ�</td>
                <td><?=$row[TotPrice]?></td>
                </tr>
                <tr>
                <td>���ҽð�</td>
                <td><?=$row[applDate]?><?=$row[applTime]?></td>
                </tr>
                <?
                if($row[payMethod]=="VBank")
                {
                    ?>                       
                    <tr>
                    <td>������</td>
                    <td><?=$row[VACT_Name]?></td>
                    </tr>
                    <tr>
                    <td>�����ڵ�</td>
                    <td><?=$row[VACT_BankCode]?></td>
                    </tr>
                    <tr>
                    <td>������¹�ȣ</td>
                    <td><?=$row[VACT_Num]?></td>
                    </tr>
                    <tr>
                    <td>�Աݿ����ð�</td>
                    <td><?=$row[VACT_Date]?></td>
                    </tr>
                    <?
                }
                ?>
                <tr>
                	<td colspan="2" style="text-align:center;">
                    	<input type="button" value="��������" onclick="location.replace('/')"  />
                        &nbsp;&nbsp;&nbsp;
                    	<input type="button" value="�����ٽ��ϱ�" onclick="location.replace('pay.php')"  />                        
                    </td>
                </tr>
            </table>
       </div>
   </div>
</div>
<?
include_once "_foot.php";
?>
