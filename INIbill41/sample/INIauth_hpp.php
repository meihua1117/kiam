<?php


/* INIauth_hpp.php
 *
 * �̴����� �÷������� ���� ��û�� �ǽð� �޴��� ���� ����� ó���Ѵ�.
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
	$inipay->m_inipayHome = "/usr/local/INIbill"; 		// �̴����� Ȩ���͸�
	$inipay->m_keyPw = "1111"; 						// Ű�н�����(�������̵� ���� ����)
	$inipay->m_type = "auth_bill"; 						// ���� (���� ��������)
	$inipay->m_pgId = "INIpayATCD"; 					// ���� (���� ��������)
	$inipay->m_subPgIp = "203.238.3.10"; 					// ���� (���� ��������)
	$inipay->m_payMethod = $paymethod;					// ���� (���� ��������)
	$inipay->m_billtype = "HPP";						// ���� (���� ��������)	
	$inipay->m_debug = "true"; 						// �α׸��("true"�� �����ϸ� ���� �αװ� ������)
	$inipay->m_mid = $mid; 							// �������̵�
	$inipay->m_goodName = $goodname; 					// ��ǰ�� (�ִ� 40��)
	$inipay->m_buyerName = $buyername; 					// ������ (�ִ� 15��)
	$inipay->m_url = "http://test.co.kr";					// ����Ʈ URL
	$inipay->m_merchantReserved1 = $merchantreserved1; 			// �����ֹ���ȣ
	$inipay->m_merchantReserved3 = $merchantreserved3; 			// ȸ�� ID
	$inipay->m_encrypted = $encrypted;
	$inipay->m_sessionKey = $sessionkey;


	/************************************************************
	 * 3. �������� ������ ���� �ǽð� �޴��� ���� ��� ��ûó�� *
	 ************************************************************/
	
	$inipay->startAction();


	/************************************************************
	 *   4. �������� ������ ���� �ǽð� �޴��� ���� ��� ���   *
	 ************************************************************
	 *                                                          *
	 * $inipay->m_resultCode         // "00"�̸� ��Ű���� ����  *
	 * $inipay->m_resultMsg          // ����� ���� ����        *
	 * $inipay->m_billKey            // BILL KEY                *
	 * $inipay->m_tid                // �ŷ���ȣ                * 
	 * $inipay->m_nohpp		 // �޴��� ��ȣ             *
	 * $inipay->m_hcorp		 // ����� ����             *
	 ************************************************************/
?>

<html>
<head>

<title>INIpay �ǽð� �޴��� ���� ��� ��� ����</title>
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
<b> �ǽð� �޴��� ���� ��� ���</b>
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
		<td align=right nowrap>BILL KEY  : </td>
		<td><?php echo($inipay->m_billKey); ?></td>
	</tr>
	<tr>
		<td align=right nowrap>�޴�����ȣ  : </td>
		<td><?php echo($inipay->m_nohpp); ?></td>
	</tr>	
	<tr>
		<td align=right nowrap>���������  : </td>
		<td><?php echo($inipay->m_hcorp); ?></td>
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
