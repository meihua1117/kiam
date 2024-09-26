<?php


/* INIreq_hpp.php
 *
 * �ǽð� �޴��� ���� ����ó���Ѵ�.
 * �� �������� ��ü ���ȼ��� �����Ƿ�, �ݵ�� Secure Web Server���� ����Ͻʽÿ�.
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
	$inipay->m_inipayHome = "/usr/local/INIbill"; 	    // INIpay Home (�����η� ������ ����)
	$inipay->m_keyPw = "1111"; 			    // Ű�н�����(�������̵� ���� ����)
	$inipay->m_type = "reqrealbill"; 		    // ���� (���� ��������)
	$inipay->m_pgId = "INIpayATCD"; 		    // ���� (���� ��������)
	$inipay->m_payMethod = "HPP";			    // ���� (���� ��������)
	$inipay->m_billtype = "HPP";		    	    // ���� (���� ��������)
	$inipay->m_subPgIp = "203.238.3.10"; 		    // ���� (���� ��������)
	$inipay->m_debug = "true"; 			    // �α׸��("true"�� �����ϸ� ���� �αװ� ������)
	$inipay->m_mid = $mid; 				    // �������̵�
	$inipay->m_billKey = $billkey; 			    // billkey �Է�
	$inipay->m_goodName = $goodname; 		    // ��ǰ�� (�ִ� 40��)
	$inipay->m_currency = "WON"; 		    	    // ȭ����� 
	$inipay->m_price = $price; 			    // ���� 
	$inipay->m_buyerName = $buyername; 		    // ������ (�ִ� 15��) 
	$inipay->m_buyerTel = $buyertel; 		    // �������̵���ȭ 
	$inipay->m_buyerEmail = $buyeremail; 		    // �������̸���
	$inipay->m_regNumber = $regnumber; 		    // �ֹ� ��ȣ
	$inipay->m_url = "http://www.your_domain.co.kr";    // ���� ���ͳ� �ּ�
	
		

	/******************
	 * 3. �ǽð� ���� ��û *
	 ******************/
	$inipay->startAction();


	/************************************************************
	 * 4. �ǽð� ���� ���                                      *
	 ************************************************************
	 *                                                          *
	 * $inipay->m_tid 	// �ŷ���ȣ                         *
	 * $inipay->m_resultCode  // "00"�̸� ����                  *
	 * $inipay->m_resultMsg   // ����� ���� ����               *
	 * $inipay->m_pgAuthDate  // �̴Ͻý� ���γ�¥ (YYYYMMDD)   *
	 * $inipay->m_pgAuthTime  // �̴Ͻý� ���νð� (HHMMSS)     *
         *                                                          *
         ************************************************************/

?>

<html>
<head>

<title>INIpay �ǽð� �޴��� ���� ����</title>
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
<b>�ǽð� �޴��� ���� ��û ���</b>
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
		<td align=right nowrap>�ŷ���ȣ : </td>
		<td><?php echo($inipay->m_tid); ?></td>
	</tr>
	<tr>
		<td align=right nowrap>���γ�¥ : </td>
		<td><?php echo($inipay->m_pgAuthDate); ?></td>
	</tr>
	<tr>
		<td align=right nowrap>���νð� : </td>
		<td><?php echo($inipay->m_pgAuthTime); ?></td>
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
