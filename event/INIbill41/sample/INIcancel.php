<?php

/* INIcancel.php
 *
 * �̹� ���ε� ������ ����Ѵ�.
 * ������� ��ü , �������Ա��� �� ����� ���� ��� �Ұ���.
 *  [���������ü�� �������� ��ȸ������ (https://iniweb.inicis.com)�� ���� ��� ȯ�� �����ϸ�, �������Ա��� ��� ����� �����ϴ�.]  
 *  
 * Date : 2006/04
 * Author : ts@inicis.com
 * Project : INIpay V4.11 for Unix
 * 
 * http://www.inicis.com
 * Copyright (C) 2006 Inicis, Co. All rights reserved.
 */


	/**************************
	 * 1. ���̺귯�� ��Ŭ��� *
	 **************************/
	require("INIpay41Lib.php");
	
	
	/***************************************
	 * 2. INIpay41 Ŭ������ �ν��Ͻ� ���� *
	 ***************************************/
	$inipay = new INIpay41;
	
	
	/*********************
	 * 3. ��� ���� ���� *
	 *********************/
	$inipay->m_inipayHome = "/usr/local/INIpay41"; // �̴����� Ȩ���͸�
	$inipay->m_type = "cancel"; // ����
	$inipay->m_subPgIp = "203.238.3.10"; // ����
    /**************************************************************************************************
     * m_keyPw �� Ű�н����� �������Դϴ�. �����Ͻø� �ȵ˴ϴ�. 1111�� �κи� �����ؼ� ����Ͻñ� �ٶ��ϴ�.
     * Ű�н������ ���������� ������(https://iniweb.inicis.com)�� ��й�ȣ�� �ƴմϴ�. ������ �ֽñ� �ٶ��ϴ�.
     * Ű�н������ ���� 4�ڸ��θ� �����˴ϴ�. �� ���� Ű���� �߱޽� �����˴ϴ�.
     * Ű�н����� ���� Ȯ���Ͻ÷��� �������� �߱޵� Ű���� ���� readme.txt ������ ������ �ֽʽÿ�.
     **************************************************************************************************/
	$inipay->m_keyPw = "1111"; // Ű�н�����(�������̵� ���� ����)
	$inipay->m_debug = "true"; // �α׸��("true"�� �����ϸ� �󼼷αװ� ������.)
	$inipay->m_mid = $mid; // �������̵�
	$inipay->m_tid = $tid; // ����� �ŷ��� �ŷ����̵�
	$inipay->m_cancelMsg = $msg; // ��һ���

	
	/****************
	 * 4. ��� ��û *
	 ****************/
	$inipay->startAction();
	
	
	/****************************************************************
	 * 5. ��� ���                                           	*
	 *                                                        	*
	 * ����ڵ� : $inipay->m_resultCode ("00"�̸� ��� ����)  	*
	 * ������� : $inipay->m_resultMsg (��Ұ���� ���� ����) 	*
	 * ��ҳ�¥ : $inipay->m_pgCancelDate (YYYYMMDD)          	*
	 * ��ҽð� : $inipay->m_pgCancelTime (HHMMSS)            	*
	 * ���ݿ����� ��� ���ι�ȣ : $inipay->m_rcash_cancel_noappl    *
	 * (���ݿ����� �߱� ��ҽÿ��� ���ϵ�)                          * 
	 ****************************************************************/
?>

<html>
<head>
<title>INIpay41 ��������� ����</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel="stylesheet" href="css/group.css" type="text/css">
<style>
body, tr, td {font-size:9pt; font-family:����,verdana; color:#433F37; line-height:19px;}
table, img {border:none}

/* Padding ******/ 
.pl_01 {padding:1 10 0 10; line-height:19px;}
.pl_03 {font-size:20pt; font-family:����,verdana; color:#FFFFFF; line-height:29px;}

/* Link ******/ 
.a:link  {font-size:9pt; color:#333333; text-decoration:none}
.a:visited { font-size:9pt; color:#333333; text-decoration:none}
.a:hover  {font-size:9pt; color:#0174CD; text-decoration:underline}

.txt_03a:link  {font-size: 8pt;line-height:18px;color:#333333; text-decoration:none}
.txt_03a:visited {font-size: 8pt;line-height:18px;color:#333333; text-decoration:none}
.txt_03a:hover  {font-size: 8pt;line-height:18px;color:#EC5900; text-decoration:underline}
</style>

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>
<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0><center> 
<table width="632" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="83" background="<?php 
    					// ���Ҽ��ܿ� ���� ��� �̹����� ���� �ȴ�
    					
    				if($inipay->m_resultCode == "01"){
					echo "img/spool_top.gif";
				}
				else{
					echo "img/cancle_top.gif";
				}
				
				?>"style="padding:0 0 0 64">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="3%" valign="top"><img src="img/title_01.gif" width="8" height="27" vspace="5"></td>
          <td width="97%" height="40" class="pl_03"><font color="#FFFFFF"><b>��Ұ��</b></font></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td align="center" bgcolor="6095BC"><table width="620" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#FFFFFF" style="padding:0 0 0 56">
		  <table width="510" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="7"><img src="img/life.gif" width="7" height="30"></td>
                <td background="img/center.gif"><img src="img/icon03.gif" width="12" height="10"> 
                  <b>���Բ��� �̴����̸� ���� ����Ͻ� �����Դϴ�. </b></td>
                <td width="8"><img src="img/right.gif" width="8" height="30"></td>
              </tr>
            </table>
            <br>
            <table width="510" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td width="407"  style="padding:0 0 0 9"><img src="img/icon.gif" width="10" height="11"> 
                  <strong><font color="433F37">��ҳ���</font></strong></td>
                <td width="103">&nbsp;</td>
              </tr>
              <tr> 
                <td colspan="2"  style="padding:0 0 0 23">
		  <table width="470" border="0" cellspacing="0" cellpadding="0">
                    
                    <tr> 
                      <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                      <td width="109" height="26">�� �� �� ��</td>
                      <td width="343"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr> 
                            <td><?php echo($inipay->m_resultCode); ?></td>
                            <td width='142' align='right'>&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr> 
                      <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                    </tr>
                    <tr> 
                      <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                      <td width="109" height="25">�� �� �� ��</td>
                      <td width="343"><?php echo($inipay->m_resultMsg); ?></td>
                    </tr>
                    <tr> 
                      <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                    </tr>
                    <tr> 
                      <td width="18" align="center"><img src="img/icon02.gif" width="7" height="7"></td>
                      <td width="109" height="25">�� �� �� ȣ</td>
                      <td width="343"><?php echo($tid); ?></td>
                    </tr>
                    <tr> 
                      <td height="1" colspan="3" align="center"  background="img/line.gif"></td>
                    </tr>
                    <tr> 
                      <td width='18' align='center'><img src='img/icon02.gif' width='7' height='7'></td>
                      <td width='109' height='25'>�� �� �� ¥</td>
                      <td width='343'><?php echo($inipay->m_pgCancelDate); ?></td>
                    </tr>                	    
                    <tr> 
                      <td height='1' colspan='3' align='center'  background='img/line.gif'></td>
                    </tr>
                    <tr> 
                      <td width='18' align='center'><img src='img/icon02.gif' width='7' height='7'></td>
                      <td width='109' height='25'>�� �� �� ��</td>
                      <td width='343'><?php echo($inipay->m_pgCancelTime); ?></td>
                    </tr>
                    <tr> 
                      <td height='1' colspan='3' align='center'  background='img/line.gif'></td>
                    </tr>                    
                    <tr> 
                      <td width='18' align='center'><img src='img/icon02.gif' width='7' height='7'></td>
                      <td width='109' height='25'>���ݿ�����<br>��ҽ��ι�ȣ</td>
                      <td width='343'><?php echo($inipay->m_rcash_cancel_noappl); ?></td>
                    </tr>
                    <tr> 
                      <td height='1' colspan='3' align='center'  background='img/line.gif'></td>
                    </tr>
                    
                    
                  </table></td>
              </tr>
            </table>
            <br>
           </td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td><img src="img/bottom01.gif" width="632" height="13"></td>
  </tr>
</table>
</center></body>
</html>
