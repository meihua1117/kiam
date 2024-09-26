// pay40_auth.js
// function : 실시간 빌링을 위한 신용카드/휴대폰 본인인증 플러그인의 인터페이스
// ⓒ 2008 INICIS.Co.,Ltd. All rights reserved.

var PLUGIN_SVR_NAME = "/wallet61/";
var PLUGIN_CLASSID = "<OBJECT ID=INIpay CLASSID=CLSID:24F6E6A8-852C-45A8-ADD3-C4AB0D6FD231 width=0 height=0 CODEBASE=http://plugin.inicis.com/wallet61/INIwallet61.cab#Version=1,0,0,1 onerror=OnErr()></OBJECT>";
var PLUGIN_ERRMSG = "고객님의 안전한 결제를 위하여 결제용 암호화 프로그램의 설치가 필요합니다.\n\n" +
		"다음 단계에 따라 진행하십시오.\n\n\n" +
		"1. 브라우저(인터넷 익스플로어) 상단 또는 하단의 노란색 알림 표시줄을 마우스로 클릭 하십시오.\n\n" +
		"2. 'ActiveX 컨트롤 설치'를 선택하십시오.\n\n" +
		"3. 보안 경고창이 나타나면 '설치'를 눌러서 진행하십시오.\n";
var JSINFO_NAME = "61_40auth";

var ini_IsVistaAfter = false;
var ini_Is64Bit = false;
var ini_IsNewVerOCX = false;
	
function MakeAuthMessage(payform)
{
	document.INIpay.IFplugin(100, "auth", "");

	if(ini_IsVistaAfter == true)
	{
		if(ini_Is64Bit == true)
		{
			if(document.INIpay.IFplugin(0, PLUGIN_SVR_NAME, "auth|0") == "ERROR") 
				return false;
		}
		else
		{
  			if(document.INIpay.IFplugin(0, PLUGIN_SVR_NAME, "auth|1") == "ERROR") 
  				return false;
		}
	}
	else
	{
		if(document.INIpay.IFplugin(0, PLUGIN_SVR_NAME, "auth") == "ERROR") 
			return false;
	}

	// Set options
	if(SetField(payform) == false) 
	{
   	 	document.INIpay.IFplugin(1, "", "");
		return false;
	}

	// Make the auth-message
	if(document.INIpay.IFplugin(4, "", "") == "ERROR") 
	{
	    document.INIpay.IFplugin(1, "", "");
	    return false;
	}

	// Get the auth-message
	if(GetField(payform) == false) 
	{
	    document.INIpay.IFplugin(1, "", "");
	    return false;
	}

	// Uninitialize
	document.INIpay.IFplugin(1, "", "");

	return true;
}

// Set options
function SetField(payform)
{
	var nField = payform.elements.length;

	for(i = 0; i < nField; i++)
	{
		if(payform.elements[i].name == "goodname")		
		{
			document.INIpay.IFplugin(2, "goodname", payform.goodname.value);
		}
		else if(payform.elements[i].name == "price")			
		{
			document.INIpay.IFplugin(2, "price", payform.price.value);
		}
		else if(payform.elements[i].name == "INIregno")
		{
			document.INIpay.IFplugin(2, "INIregno", payform.INIregno.value);
		}
		else if(payform.elements[i].name == "print_msg")		
		{
			document.INIpay.IFplugin(2, "print_msg", payform.print_msg.value);
		}
		else if(payform.elements[i].name == "ini_offer_period")    	
		{
			document.INIpay.IFplugin(2, "ini_offer_period", payform.ini_offer_period.value);
		}
	}

	document.INIpay.IFplugin(2, "mid", payform.mid.value);
	document.INIpay.IFplugin(2, "paymethod", "Auth");
	document.INIpay.IFplugin(2, "acceptmethod", payform.acceptmethod.value);
	document.INIpay.IFplugin(2, "plugin_jsinfo", JSINFO_NAME);

	return true;
}

// Get the auth-message
function GetField(payform)
{
	var nField = payform.elements.length;

	for(i = 0; i < nField; i++)
	{
		if(payform.elements[i].name == "balance")
		{
			payform.balance.value	= document.INIpay.IFplugin(3, "balance", "");
		}
		else if(payform.elements[i].name == "mobile_co")
		{
			payform.mobile_co.value = document.INIpay.IFplugin(3, "mobile_co", "");
		}
		else if(payform.elements[i].name == "cardcode")
		{
			payform.cardcode.value = document.INIpay.IFplugin(3, "cardcode", "");
		}
	}

	if((payform.sessionkey.value = document.INIpay.IFplugin(3, "sessionkey", "")) == "ERROR") 
	{
	  	return false;
	}
	if(payform.sessionkey.value == "") 
	{
  		return false;
	}
	if((payform.encrypted.value = document.INIpay.IFplugin(3, "encrypted", "")) == "ERROR") 
	{
  		return false;
	}
	if(payform.encrypted.value == "") 
	{
  		return false;
	}

	payform.uid.value = document.INIpay.IFplugin(3, "uid", "");
	payform.paymethod.value = "Auth";


	return true;
}

function ini_IsInstalledPlugin()
{
	if( document.INIpay == null || document.INIpay.object == null )
		return false;

	return true;
}

function ini_GetUserInfo()
{
	var strAgent = navigator.userAgent.toLowerCase();

	//== windows Vista이후 여부 체크
	if(strAgent.indexOf("windows nt 6") > -1 || strAgent.indexOf("windows nt 10") > -1 )
	{
		ini_IsVistaAfter = true;
		ini_IsNewVerOCX = true;
				
		//== Share해결용 OCX 설치여부
		if(strAgent.indexOf("windows nt 6.1") > -1 || strAgent.indexOf("windows nt 6.0") > -1)  //-- Vista,Window7(IE10이하)은 예전 OCX 설치
			ini_IsNewVerOCX = false;
	}

	//== 64비트 체크
	var strAppVersion = window.navigator.appVersion.toLowerCase();
	if( strAppVersion.indexOf("win64") != -1 || strAppVersion.indexOf("wow64") != -1 ||  strAgent.indexOf("wow64") != -1 )
	{
		ini_Is64Bit = true;
	}
	
	
	//== IE11 이후버전인지 체크 
	if (strAgent.indexOf("msie") != -1 || strAgent.indexOf("rv:1") != -1 )
	{
		var nIdx = strAgent.indexOf('trident/', 0);
		if ( nIdx >= 0 )
		{
			var strTriVer = strAgent.substring(nIdx + 8);
		
			var nTriVer = parseInt(strTriVer);
		
			if(  nTriVer >= 7 )
				ini_IsNewVerOCX = true;
		}
	}
	
}
   
function SetEnvironment()        
{
	ini_GetUserInfo();
	
	if( ini_IsVistaAfter == true )
	{
			
		if( ini_IsNewVerOCX == true )
			PLUGIN_CLASSID = "<OBJECT ID=INIpay CLASSID=CLSID:24F6E6A8-852C-45A8-ADD3-C4AB0D6FD231 width=0 height=0 CODEBASE=http://plugin.inicis.com/wallet61/INIwallet61_win8.cab#Version=1,0,0,5 onerror=OnErr()></OBJECT>";
		else PLUGIN_CLASSID = "<OBJECT ID=INIpay CLASSID=CLSID:24F6E6A8-852C-45A8-ADD3-C4AB0D6FD231 width=0 height=0 CODEBASE=http://plugin.inicis.com/wallet61/INIwallet61_vista.cab#Version=1,0,0,1 onerror=OnErr()></OBJECT>";
	
	}
	else
	{
		if( navigator.userAgent.indexOf("Windows NT 5.1") <= -1 && navigator.userAgent.indexOf("Windows NT 5.2") <= -1 )
			PLUGIN_ERRMSG = "[INIpay전자지갑]이 설치되지 않았습니다.\n\n브라우저에서 [새로고침]버튼을 클릭하신 후 [보안경고]창이 나타나면 [예]버튼을 클릭하세요.";
	}     
}

function StartSmartUpdate()
{
	//Edge 확인
	if(ini_IsEdge() == true)
  {
    document.location.href = "http://plugin.inicis.com/html60/npapi/install/edge_pop.html";
    return;
  }
	SetEnvironment();
	document.writeln(PLUGIN_CLASSID);
}

function ini_IsEdge()
{
	var strAgent = navigator.userAgent.toLowerCase();
	if (strAgent.indexOf("edge") != -1)
		return true;
	
	return false;
}

function OnErr()
{
	alert(PLUGIN_ERRMSG);
}
