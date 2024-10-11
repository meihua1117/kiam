<?
include_once "lib/rlatjd_fun.php";
if($member_1['mem_id'] == "") {
    echo "<script>location.history(-1);</script>";
    exit;
}

if(!$_SESSION['one_member_id'])
{
?>
<script language="javascript">
window.parent.location.replace('/ma.php');
</script>
<?
exit;
}
/*
기본형 판매 수당 : 
1~100인 : 50%
101~1000인 : 60%
1001인 이상 : 70%

직원 판매수당 40%
대리점 소속일경우 (직판 50% , 직원판매시 10%) 5
지사 소속일경우 (직판 60% , 직원판매시 20%) 105
총판 소속일경우 (직판 70% , 직원판매시 30%) 1100

*/
require("inipay/libs/INILib.php");
$inipay = new INIpay50;
$inipay->SetField("inipayhome", $_SERVER['DOCUMENT_ROOT']."/inipay");       // 이니페이 홈디렉터리(상점수정 필요)
$inipay->SetField("type", "chkfake");      // 고정 (절대 수정 불가)
$inipay->SetField("debug", "true");        // 로그모드("true"로 설정하면 상세로그가 생성됨.)
$inipay->SetField("enctype", "asym");    //asym:비대칭, symm:대칭(현재 asym으로 고정)
$inipay->SetField("admin", "1111");     // 키패스워드(키발급시 생성, 상점관리자 패스워드와 상관없음)
$inipay->SetField("checkopt", "false");   //base64함:false, base64안함:true(현재 false로 고정)
$inipay->SetField("mid", $mid);            // 상점아이디
$inipay->SetField("price",$_POST[price]);                // 가격
$inipay->SetField("nointerest", "no");             //무이자여부(no:일반, yes:무이자)
//$inipay->SetField("quotabase", "선택:일시불:2개월:3개월:6개월"); //할부기간
if($_POST['month_plan'] == "Y")
    $inipay->SetField("quotabase", "일시불"); //할부기간
else
    $inipay->SetField("quotabase", "일시불:2개월:3개월:6개월:12개월"); //할부기간
//$inipay->SetField("quotabase", "선택:일시불:2개월:3개월:6개월"); //할부기간

$inipay->startAction();

if ($inipay->GetResult("ResultCode") != "00") {
	echo $inipay->GetResult("ResultMsg");
	exit(0);
}
$_SESSION['INI_MID'] = $_POST[mid]; //상점ID
$_SESSION['INI_ADMIN'] = "1111";   // 키패스워드(키발급시 생성, 상점관리자 패스워드와 상관없음)
$_SESSION['INI_PRICE'] = $_POST[price];     //가격 
$_SESSION['INI_RN'] = $inipay->GetResult("rn"); //고정 (절대 수정 불가)
$_SESSION['INI_ENCTYPE'] = $inipay->GetResult("enctype"); //고정 (절대 수정 불가)
$orderNumber=$member_1['mem_code']."_".date("ymdhis");
$_SESSION[form_submit]="ok";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>무제 문서</title>
<script language="javascript" src="/global/jquery-1.7.1.min.js"></script>
<script language=javascript src="http://plugin.inicis.com/pay61_secuni_cross.js"></script>
<script language="javascript">
StartSmartUpdate();
window.onload=function()
{
	if (ini_IsInstalledPlugin() == false) //플러그인 설치유무 체크
	{
		alert("\n이니페이 플러그인 128이 설치되지 않았습니다. \n\n안전한 결제를 위하여 이니페이 플러그인 128의 설치가 필요합니다. \n\n다시 설치하시려면 Ctrl + F5키를 누르시거나 메뉴의 [보기/새로고침]을 선택하여 주십시오.器。");
		return false;
	}
	if (MakePayMessage(send_form)) 
		send_form.clickcontrol.value = "disable" 
	else 
	{
		if (IsPluginModule()) 
		alert("결제를 취소하셨습니다.");
		return false;
	}	
	send_form.submit();	   
}
</script>
</head>
<body>
<form name="send_form" target="_parent" action="/pay_end.php" method="post">
    <input type="hidden" name="goodname"  value="<?=$_POST[goodname]?>">
    <input type="hidden" name="buyername"  value="<?=$member_1['mem_name']?>">
    <input type="hidden" name="buyeremail"  value="<?=$member_1['mem_email']?>">
    <input type="hidden" name="parentemail"  value="<?=$member_1['mem_email']?>">
    <input type="hidden" name="buyertel"  value="<?=$member_1['mem_phone']?>">
    <input type="hidden" name="currency"  value="WON">
    <input type="hidden" name="acceptmethod"  value="HPP(2):Card(0):OCB:receipt:cardpoint">
    <input type="hidden" name="ini_encfield" value="<?php echo($inipay->GetResult("encfield")); ?>">
    <input type="hidden" name="ini_certid" value="<?php echo($inipay->GetResult("certid")); ?>">
    <input type="hidden" name="quotainterest" value="">
    <input type="hidden" name="paymethod" value="">
	<input type="hidden" name="gopaymethod" value="Card" />    
    <input type="hidden" name="cardcode" value="">
    <input type="hidden" name="cardquota" value="">
    <input type="hidden" name="rbankcode" value="">
    <input type="hidden" name="reqsign" value="DONE">
    <input type="hidden" name="encrypted" value="">
    <input type="hidden" name="sessionkey" value="">
    <input type="hidden" name="uid" value=""> 
    <input type="hidden" name="sid" value="">
    <input type="hidden" name="oid"  value="<?=$orderNumber?>" />
    <input type="hidden" name="version" value="4000">
    <input type="hidden" name="clickcontrol" value="">
    
    <input type="hidden" name="add_phone" value="<?=$_POST['add_phone']?>" />
    <input type="hidden" name="phone_cnt" value="<?=$_POST[max_cnt]?>" />
    <input type="hidden" name="month_cnt" value="<?=$_POST['month_cnt']?>" />
    <input type="hidden" name="max_cnt" value="<?=$_POST[max_cnt]?>" />
    <input type="hidden" name="add_service" value="<?=$_POST[add_service]?>" />
    <input type="hidden" name="fujia_status" value="<?=$_POST[fujia_status]?>" />
    <input type="hidden" name="pay_ex_no" value="<?=$_POST[pay_ex_no]?>" />    
    <input type="hidden" name="pay_ex_end_date" value="<?=$_POST[pay_ex_end_date]?>" />    
    <input type="hidden" name="add_opt" value="<?=$_POST[add_opt]?>" />
    
    <input type="hidden" name="db_cnt" value="<?=$_POST['db_cnt']?>" />
    <input type="hidden" name="email_cnt" value="<?=$_POST['email_cnt']?>" />
    <input type="hidden" name="onestep1" value="<?=$_POST[onestep1]?>" />
    <input type="hidden" name="onestep2" value="<?=$_POST[onestep2]?>" />
    <input type="hidden" name="member_type" value="<?=$_POST['member_type']?>" />
    
</form>
</body>
</html>
