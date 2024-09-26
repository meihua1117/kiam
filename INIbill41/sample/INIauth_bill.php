<?php


/* INIauth_bill.php
 *
 * �̴����� �÷������� ���� ��û�� �ǽð� �ſ�ī�� ���� ����� ó���Ѵ�.
 * �ڵ忡 ���� �ڼ��� ������ �Ŵ����� �����Ͻʽÿ�.
 * <����> �������� ������ �ݵ�� üũ�ϵ����Ͽ� �����ŷ��� �����Ͽ� �ֽʽÿ�.
 *  
 * http://www.inicis.com
 * Copyright (C) 2004 Inicis Co., Ltd. All rights reserved.
 */

	/**************************
	 * 1. ���̺귯�� ��Ŭ��� *
	 **************************/
	require("INIpay41Lib.php");
	
	
	/***************************************
	 * 2. INIpay41 Ŭ������ �ν��Ͻ� ���� *
	 ***************************************/
	$inipay = new INIpay41;

	

	/***********************
	 * 2. ���� ���� *
	 ***********************/
	$inipay->m_inipayHome = $_SERVER['DOCUMENT_ROOT']."/INIbill41"; 	// �̴����� Ȩ���͸�
	$inipay->m_keyPw = "1111"; 					// Ű�н�����(�������̵� ���� ����)
	$inipay->m_type = "auth_bill"; 					// ���� (���� ��������)
	$inipay->m_pgId = "INIpayBill"; 				// ���� (���� ��������)
	$inipay->m_subPgIp = "203.238.3.10"; 				// ���� (���� ��������)
	$inipay->m_payMethod = $paymethod;				// ���� (���� ��������)
	$inipay->m_billtype = "Card";					// ���� (���� ��������)
	$inipay->m_debug = "true"; 					// �α׸��("true"�� �����ϸ� ���� �αװ� ������)
	$inipay->m_mid = $mid; 						// �������̵�
	$inipay->m_goodName = $goodname; 				// ��ǰ�� (�ִ� 40��)
	$inipay->m_buyerName = $buyername; 				// ������ (�ִ� 15��)
	$inipay->m_url = "http://www.kiam.kr";				// ����Ʈ URL		
	$inipay->m_merchantReserved3 = $merchantreserved3; 		// ȸ�� ID
	$inipay->m_encrypted = $encrypted;
	$inipay->m_sessionKey = $sessionkey;

	/**************************************************************
	 * 3. �������� ������ ���� �ǽð� �ſ�ī�� ���� ��� ��ûó�� *
	 **************************************************************/
	
	$inipay->startAction();


	/**************************************************************
	 *   4. �������� ������ ���� �ǽð� �ſ�ī�� ���� ��� ���   *
	 **************************************************************
	 *                                                   	      *
	 * $inipay->m_resultCode           // "00"�̸� ��Ű���� ����  *
	 * $inipay->m_resultMsg            // ����� ���� ����        *
	 * $inipay->m_cardCode             // ī��� �ڵ�             *
	 * $inipay->m_billKey              // BILL KEY                *
	 * $inipay->m_cardPass             // ī�� ��й�ȣ �� ���ڸ� *
	 * $inipay->m_cardKind             // ī������(����-0,����-1) *
	 * $inipay->m_tid                  // �ŷ���ȣ                * 
	 **************************************************************/
?>

<html>
<head>

<title>INIpay �ǽð� �ſ�ī�� ���� ��� ��� ����</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">

<script>
	var openwin=window.open("childwin.html","childwin","width=299,height=149");
	openwin.close();
</script>

<style type="text/css">
	BODY{font-size:9pt; line-height:160%}
	TD{font-size:9pt; line-height:160%}
	INPUT{font-size:9pt;}
	.emp{background-color:#E0EFFE;}
</style>

</head>

<body>
<table border=0 width=500>
<tr>
<td>
<hr noshade size=1>
<b> �ǽð� �ſ�ī�� ���� ��� ���</b>
<hr noshade size=1>
</td>
</tr>
</table>
<br>

<table border=0 width=500>
	<tr>
		<td align=right nowrap>����ڵ� : </td>
		<td><?php echo($inipay->m_resultCode); ?></td>
	</tr>
	<tr>
		<td align=right nowrap>������� : </td>
		<td><font class=emp><?php echo($inipay->m_resultMsg); ?></font></td>
	</tr>
	<tr>
		<td align=right nowrap>ī��� �ڵ� : </td>
		<td><?php echo($inipay->m_cardCode); ?></td>
	</tr>
	<tr>
		<td align=right nowrap>BILL KEY  : </td>
		<td><?php echo($inipay->m_billKey); ?></td>
	</tr>
	<tr>
		<td align=right nowrap>��й�ȣ �� 2�ڸ� : </td>
		<td><?php echo($inipay->m_cardPass); ?></td>
	</tr>
	<tr>
		<td align=right nowrap>CARD KIND  : </td>
		<td><?php echo($inipay->m_cardKind); ?>&nbsp <b>(0 - ����, 1 - ����)</b></td>
	</tr>
	<tr>
		<td colspan=2><hr noshade size=1></td>
	</tr>
	<tr>
		<td align=right colspan=2>Copyright Inicis, Co.<br>www.inicis.com</td>
	</tr>
</table>
</body>
</html>
