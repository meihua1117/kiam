<?
include_once "lib/rlatjd_fun.php";
if(!$_SESSION['one_member_id'])
{
?>
<script language="javascript">
window.parent.location.replace('/ma.php');
</script>
<?
exit;
}
if($_POST[auto_pay_status]==1)
{
	require("INIbill41/sample/INIpay41Lib.php");
	$inipay = new INIpay41;	
	$inipay->m_inipayHome = $_SERVER['DOCUMENT_ROOT']."/INIbill41"; 	// 이니페이 홈디렉터리
	$inipay->m_keyPw = "1111"; 					// 키패스워드(상점아이디에 따라 변경)
	$inipay->m_type = "auth_bill"; 					// 고정 (절대 수정금지)
	$inipay->m_pgId = "INIpayBill"; 				// 고정 (절대 수정금지)
	$inipay->m_subPgIp = "203.238.3.10"; 				// 고정 (절대 수정금지)
	$inipay->m_payMethod = $paymethod;				// 고정 (절대 수정금지)	
	$inipay->m_billtype = "Card";					// 고정 (절대 수정금지)
	$inipay->m_debug = "true"; 					// 로그모드("true"로 설정하면 상세한 로그가 생성됨)
	$inipay->m_mid = $mid; 						// 상점아이디
	$inipay->m_goodName = $goodname; 				// 상품명 (최대 40자)
	$inipay->m_buyerName = $buyername; 				// 구매자 (최대 15자)
	$inipay->m_url = "http://www.kiam.kr";				// 사이트 URL		
	$inipay->m_merchantReserved3 = $merchantreserved3; 		// 회원 ID
	$inipay->m_encrypted = $encrypted;
	$inipay->m_sessionKey = $sessionkey;
	$inipay->startAction();	
}
$orderNumber=$member_1['mem_code']."_".date("ymdhis");
//$cardquota=$_POST['month_cnt']<10?"0".$_POST['month_cnt']:$_POST['month_cnt'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>무제 문서</title>
<script language=javascript src="http://plugin.inicis.com/pay40_auth.js"></script>
<script language="javascript">
StartSmartUpdate();
window.onload=function()
{
	<?
	if($_POST[auto_pay_status])
	{
		if($inipay->m_resultCode=="00")
		{
			$_SESSION[form_submit]="ok";
	?>
		auto_frm.billkey.value='<?=$inipay->m_billKey?>';
		auto_frm.cardpass.value='<?=$inipay->m_cardPass?>';
		auto_frm.cardKind.value='<?=$inipay->m_cardKind?>';
		auto_frm.tid.value='<?=$inipay->m_tid?>';
		auto_frm.resultMsg.value='<?=$inipay->m_resultMsg?>';
		auto_frm.auto_pay_status.value='2';
		auto_frm.target='_parent';
		auto_frm.action="pay_auto_end.php";
		auto_frm.submit();
    <?
		}
		else
		{
		?>
			alert('<?=iconv("euc-kr","utf-8",$inipay->m_resultMsg)?>');		
		<?
		}
	}
	else
	{
	?>
        if(document.INIpay==null||document.INIpay.object==null )
        {
            alert("플러그인을 설치 후 다시 시도 하십시오.");
            return false;
        }
        else
        {
            auto_frm.clickcontrol.value = "enable";
            if (MakeAuthMessage(auto_frm))
            {
                auto_frm.clickcontrol.value = "disable";
                auto_frm.auto_pay_status.value='1';			
                auto_frm.action='pay_auto_start.php';
                auto_frm.submit();
            }
            else
            {
                alert("결제를 취소하셨습니다.");
                return false;
            }
        }
    <?
	}
	?>
}
</script>
</head>
<body>
<form name="auto_frm" action="" method="post">
    <input type="text" name="buyername" value="<?=$member_1['mem_name']?>">
    <input type="text" name="goodname" value="<?=$_POST[goodname]?>">
    <input type="text" name="mid" value="<?=$_POST[mid]?>">
    <input type="text" name="price" value="<?=$_POST[price]?>">
    <input type="text" name="ini_offer_period" value="<?=date("Ymd").date("Ymd",strtotime("+{$_POST['month_cnt']} month"))?>">
    <input type="text" name="print_msg" value="고객님의 매월 결제일은 <?=date("d")?>일 입니다.">
   	       
    <input type="text" name="authentification" value="01"> <!--본인인증 00 함 01 안함-->
    <input type="text" name="quotainterest" value="0"><!--일반할부 0 무이자 1 안함-->
    <input type="text" name="currency" value="WON">
    <input type="text" name="cardquota" value="00"><!--할부기간 00일시불 02 2개월-->
    <input type="text" name="buyertel" value="<?=$member_1['mem_phone']?>">
    <input type="text" name="buyeremail" value="<?=$member_1['mem_email']?>">
    <input type="text" name="cardpass" value=""><!--비밀번호 2자리-->
    <input type="text" name="regnumber" value=""><!--주민번호-->
    <input type="text" name="oid" value="<?=$orderNumber?>"><!--주문번호-->
    
    <input type="text" name="billkey" value="">
    <input type="text" name="cardKind" value=""><!--카드종류 개인0 법인1-->
    <input type="text" name="tid" value=""><!--거래번호-->
    <input type="text" name="resultMsg" value=""><!--거래메시지-->     
                
    <!-- 삭제/수정 불가 -->
    <input type="hidden" name="acceptmethod" value="BILLAUTH:FULLVERIFY">
    <input type="hidden" name="encrypted" value="">
    <input type="hidden" name="sessionkey" value="">
    <input type="hidden" name="cardcode" value="">
    <input type="hidden" name="paymethod" value="Card">
    <input type="hidden" name="uid" value="">
    <input type="hidden" name="version" value="4000">
    <input type="hidden" name="clickcontrol" value="">
    <input type="hidden" name="merchantreserved3"  value="<?=$member_1['mem_id']?>">
    <input type="hidden" name="auto_pay_status">
    
    <input type="text" name="phone_cnt" value="<?=$_POST['add_phone']?>" />
    <input type="text" name="month_cnt" value="<?=$_POST['month_cnt']?>" />
    <input type="text" name="fujia_status" value="<?=$_POST['fujia_status']?>" />
    <input type="hidden" name="pay_ex_no" value="<?=$_POST[pay_ex_no]?>" />    
    <input type="hidden" name="pay_ex_end_date" value="<?=$_POST[pay_ex_end_date]?>" />     
</form>
</body>
</html>
