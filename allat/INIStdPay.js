//-------------------------------------------------------------------------------------------
// $jINI script 媛� browser �� 濡쒕뵫�섏뼱 �덉� �딆쑝硫� 濡쒕뵫 �쒗궎�꾨줉 �쒕떎. (紐⑤뱺 釉뚮씪�곗� �명솚)
// Created by Inicis
//-------------------------------------------------------------------------------------------

var INIopenDomain = "https://stdpay.inicis.com/";
var cdnDomain = "https://stdux.inicis.com/stdpay";

var INImsgTitle = {
	info : "[INIStdPay Info] "
	,dev_err : "[INIStdPay / Dev. Error]\n\n"
};
var INImsg = {
		alert : function(msg) {
				alert(msg);
		}
		,info1		: INImsgTitle.info		+ "INIStdPay �쇱씠釉뚮윭由щ� 濡쒕뵫以묒엯�덈떎.\n�좎떆留� 湲곕떎�� 二쇱떗�몄슂"
		,dev_err1	: INImsgTitle.dev_err	+ "�숈씪�� �대쫫�� form 媛앹껜媛� 議댁옱�⑸땲��."
		,dev_err2	: INImsgTitle.dev_err	+ "form 媛앹껜瑜� 李얠쓣�� �놁뒿�덈떎."
		,dev_err3	: INImsgTitle.dev_err	+ "�꾩닔 蹂���(#)媛� 議댁옱�섏� �딆뒿�덈떎."
		,dev_err4	: INImsgTitle.dev_err	+ "蹂���(#)�� 媛믪씠 �놁뒿�덈떎."
		,dev_err5	: INImsgTitle.dev_err	+ "蹂���(#1)�� 媛믪뿉 湲몄씠 臾몄젣媛� �덉뒿�덈떎.\n\n(媛�:#2)\n(湲몄씠:#3)\n(�쒗븳湲몄씠:#4)"
		,dev_err6	: INImsgTitle.dev_err	+ "�듭떊�� �ㅽ뙣�섏��듬땲��.\n�좎떆�� �ㅼ떆 �쒕룄�대낫�쒓린 諛붾엻�덈떎."
		,dev_err7	: INImsgTitle.dev_err	+ "�좏깮�� 寃곗젣�섎떒�� 怨꾩빟�섏� �딆� 寃곗젣 �섎떒�낅땲��."
		,dev_err8	: INImsgTitle.dev_err	+ "payViewType瑜� popup�쇰줈 �ㅼ젙�� 寃쎌슦 諛섎뱶�� popupUrl瑜� �낅젰�댁빞 �⑸땲��."
		,dev_err9	: INImsgTitle.dev_err   + "�좊��뺣낫�� ���� 媛믪씠 議댁옱�섏� �딆뒿�덈떎."
		,dev_err10 	: INImsgTitle.dev_err	+ "移대뱶肄붾뱶媛� �낅젰�섏� �딆븯�듬땲��."
		,dev_err11 	: INImsgTitle.dev_err	+ "�대떦湲곌린濡쒕뒗 �뺤긽�곸씤 寃곗젣媛� 吏꾪뻾�섏� �딆쓣 �� �덉뒿�덈떎. PC濡� 寃곗젣 吏꾪뻾�� 遺��곷뱶由쎈땲��."
};

var paramList = [
		"mid"			+":String,1~10"
		,"oid"			+":String,1~40"
		,"price"		+":Number,1~64"
		,"currency"		+":String,3"
		,"buyertel"		+":String,1~20"
		,"timestamp"	+":String,1~20"
		,"mKey"			+":String,1~128"
];



var INIUtil = {

	randomKey : function(str) {
		return str +"_"+ (Math.random() * (1 << 30)).toString(16).replace('.', '');

	}

};

var $jINIBrowser = {

		underIE9 : function() {
				var rv = -1; // Return value assumes failure.

				var re = null;

				var ua = navigator.userAgent;

				if(navigator.appName.charAt(0) == "M"){

					re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");

					if (re.exec(ua) != null){
						rv = parseFloat(RegExp.$1);
					}

					if(rv <= 8 || document.documentMode == '7' ||  document.documentMode == '8'){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}

			}

};

var $jINILoader = {
		_startupJob : null

		,load: function(startupFunc) {
			_startupJob = startupFunc;

			if(this.$jINILoadChecker()) {
				_startupJob();
			}
			else {


				var jsUrl = cdnDomain+'/stdjs/INIStdPay_third-party.js'
				if($jINIBrowser.underIE9()){
					var jsUrl = cdnDomain+'/stdjs/INIStdPay_third-party_under_ie9.js'
				}

				var fileref=document.createElement('script');
				fileref.setAttribute("type","text/javascript");
				fileref.setAttribute("src", jsUrl);
				fileref.onload = _startupJob;
				document.getElementsByTagName("head")[0].appendChild(fileref);

				if (navigator.userAgent.toUpperCase().indexOf("MSIE")>-1){
					this.waitForJQueryLoad();
				}
			}

		}
		,waitForJQueryLoad: function () {
			setTimeout(function() { if(!$jINILoader.$jINILoadChecker()) { $jINILoader.waitForJQueryLoad(); } else { _startupJob(); }
			}, 100);
		}
		,$jINILoadChecker: function () { try {var win = $jINI(window); return true;}catch(ex){ return false; } }

	};

var $jINICSSLoader = {
		loadDefault: function(startupFunc) {

				$jINI("<link></link>").attr({
							href: INIopenDomain+'./stdcss/INIStdPay.css',
							type: 'text/css',
							rel: 'stylesheet'
					}).appendTo('head');

		}
};



var INIStdPay = {

		vForm 				: null
		,vIframeName		: null
		,vTitleName			: "KG�대땲�쒖뒪 寃곗젣李�" //2022-06-02 �뱀젒洹쇱꽦�쇰줈 �명븳 ���댄� 異붽� sej

		,vDefaultCharset	: "UTF-8"
		,vPageCharset		: null
		,vPayViewType		: "overlay"

		,vMethod 			: "POST"
		,vActionUrl			: INIopenDomain + "payMain/pay"				// 寃곗젣李� URL
		,vCheckActionUrl	: INIopenDomain + "jsApi/payCheck"

		,vParamSHA256Hash	: ""										// �꾩닔 �뚮씪誘명꽣 �댁돩

		,boolInitDone 				: false		// init�� �뺤긽 �ㅽ뻾�섏뿀�붿� ����
		,boolSubmitRunCheck			: false		// Submit�� �ㅽ뻾 �щ뒗吏� �щ� 泥댄겕
		,boolPayRequestCheck 		: false		// �뚮━誘명꽣 泥댄겕�� URL濡� �꾩넚 �щ� payRequestCheck()�� �댁꽌 �ъ슜
		,boolMobile					: false		// 紐⑤컮�� �щ� 泥댄겕
		,boolWinMetro				: false		// MetroStyle �щ� 泥댄겕
        ,boolViewParentWindow   	: true      // 遺�紐⑥갹�� 蹂댁씪源뚯슂? �딅낫�쇨퉴��?
        ,boolCard					: false		// �ㅼ씠�됲듃 移대뱶 �щ� 泥댄겕
        ,boolHpp					: false		// �ㅼ씠�됲듃 �대��� �щ� 泥댄겕
		,intMobileWidth				: 0			// 紐⑤컮�� �덈퉬

		,$formObj			: null		// form Object
		,$iframe			: null		// iframe Object
		,$iframe2			: null		// iframe Object
		,$modalDiv			: null		// 寃곗젣李� �덉씠�� Object
		,$overlay			: null		// 寃곗젣李� �ㅻ쾭�덉씠 援ъ뿰 Object($jINI Tools Overlay Object)

		,$modalDivMsg		: null
		,$stdPopup			: null
		,$stdPopupInterval	: null
		,frmobj             : null   
		,jsLoaded           : false

		// INIStdPay �쇱씠釉뚮윭由� 珥덇린��
		,init : function() {
			if (!window.$jINI) {
				$jINILoader.load( function() {
					INIStdPay.init();
				});
				return;
			}else{
			};

			if(!INIStdPay.boolInitDone){
				$jINI(document).ready(function(){

					$jINICSSLoader.loadDefault();

					INIStdPay.boolMobile = $jINI.mobileBrowser;

					// windows 8 �쇰븣 泥댄겕
					if("msie" == $jINI.ua.browser.name){

						try {
								new ActiveXObject("");

						}
						catch (e) {
							// FF has ReferenceError here
							if (e.name == 'TypeError' || e.name == 'Error') {

							}else{
								INIStdPay.boolMobile = true;
								INIStdPay.boolWinMetro = true;
							}

						}
					}

					if(!INIStdPay.boolMobile){
						INIStdPay.INIModal_init();
					}
					INIStdPay.boolInitDone = true;
				});
			}
			


		}

		// 紐⑤떖 珥덇린�� �명똿
		,INIModal_init : function(){

			// �ㅻ쾭�덉씠�� �ㅼ뼱媛� 紐⑤떖 DIV �ㅼ젙
			INIStdPay.vIframeName = INIUtil.randomKey("iframe");

			INIStdPay.$iframe = $jINI("<iframe name='"+INIStdPay.vIframeName+"' id='iframe'></iframe>")
							.addClass("inipay_iframe")
							.attr("frameborder","0")
							.attr("scrolling","no")
							.attr("allowtransparency","true")
							.attr("title",INIStdPay.vTitleName); 
			
			var modalCloseBtn = $jINI('<div class="inipay_close"><img src="https://stdux.inicis.com/stdpay/img/close.png"></div>');

			modalCloseBtn.click(function(){
				INIStdPay.viewOff();
			});

			var modalDivContant_header	= $jINI("<div>").addClass("inipay_modal-header").append(modalCloseBtn);
			var modalDivContant_body 	= $jINI("<div>").addClass("inipay_modal-body").append(INIStdPay.$iframe);
			var modalDivContant_footter = $jINI("<div>").addClass("inipay_modal-footer");


			INIStdPay.$modalDiv = $jINI('<div id="inicisModalDiv" class="inipay_modal fade" tabindex="-1" role="dialog" aria-hidden="true"></div>');
			INIStdPay.$modalDivMsg = $jINI('<div id="inicisModalDivMsg" class="inipay_modal_msg fade" tabindex="-1" role="dialog" aria-hidden="true"></div>');

			INIStdPay.$modalDivMsg.html("<div style='padding:5px;'><b>寃곗젣 �앹뾽李쎌쓣 �レ쓣 寃쎌슦 �� 5珥덊썑 �먮룞�쇰줈 �붾㈃ 蹂듦뎄�⑸땲��.</b></div><div style='padding:5px;'>(�꾩옱李쎌쓣 �덈줈怨좎묠, �リ린, �섏씠吏��대룞 �섎뒗 寃쎌슦 寃곗젣李쎌� �먮룞�쇰줈 醫낅즺�⑸땲��.)</div>");

			INIStdPay.$modalDivMsg2 = $jINI('<div id="inicisModalDivMsg" class="inipay_modal_msg fade" tabindex="-1" role="dialog" aria-hidden="true"></div>');

			INIStdPay.$modalDivMsg2.html("<div style='padding:5px;'><b>寃곗젣 紐⑤뱢 濡쒕뵫以묒엯�덈떎.</b></div><div style='padding:5px;'>(理쒕� 1遺� 媛��� �뚯슂�섎ŉ �꾩옱李쎌쓣 �덈줈怨좎묠, �リ린, �섏씠吏��대룞 �섎뒗 寃쎌슦 寃곗젣 �ㅻ쪟媛� 諛쒖깮 �� �� �덉뒿�덈떎.)</div>");

			INIStdPay.$modalDiv.append(modalDivContant_body);

			INIStdPay.$modalDiv.modal({
				keyboard: false
				,backdrop : 'static'
				,show : false
				,remote:false
			});


			INIStdPay.$modalDivMsg.modal({
				keyboard: false
				,backdrop : 'static'
				,show : false
				,remote:false
			});

			INIStdPay.$modalDivMsg2.modal({
				keyboard: false
				,backdrop : 'static'
				,show : false
				,remote:false
			});
		}
		
		,waitInit : function(){

			if(INIStdPay.boolInitDone){
				INIStdPay.pay(INIStdPay.frmobj);
			}else{
				setTimeout(function() {INIStdPay.waitInit();}, 100);
			}
		}
		// 珥덇린�� �꾨즺 �щ� 泥댄겕
		,init_check : function(call_f){

			if(!INIStdPay.boolInitDone){

				INIStdPay.waitInit();
				
			}else{
				
				return true;
			}

		}

		// 寃곗젣李� �쒖떆
		,viewOn : function(){


			$jINI(document).bind("dragstart", function(e) {
				return false;
			});
			$jINI(document).bind("selectstart", function(e) {
				return false;
			});
			$jINI(document).bind("contextmenu", function(e) {
				return false;
			});

			if(INIStdPay.init_check("INIModal_viewOn")){

				try{
					INIStdPay.$modalDiv.find(".header").html(INIStdPay.$formObj.find("[name=header]").val());
					INIStdPay.$modalDiv.find(".footer").html(INIStdPay.$formObj.find("[name=footer]").val());
				}catch(e){

				}

				INIStdPay.$modalDiv.modal('show');
			}




		}
		,hide : function(){
			INIStdPay.$modalDiv.modal('hide');
		}
		// 寃곗젣李� �④린湲�
		,viewOff : function(){

			INIStdPay.$modalDiv.modal('hide');
			INIStdPay.$modalDiv.remove();
			INIStdPay.$modalDivMsg.modal('hide');
			INIStdPay.$modalDivMsg.remove();
			INIStdPay.$modalDivMsg2.modal('hide');
			INIStdPay.$modalDivMsg2.remove();
		}


		// 寃곗젣李� �④린湲�
		,viewOffTriger : function(){

			INIStdPay.$iframe.attr("src","");
			INIStdPay.INIModal_init();			// Modal �� 珥덇린��

			$jINI(document).unbind("dragstart");
			$jINI(document).unbind("selectstart");

		}

		// �앹뾽李� �덉슜�섎룄濡� �ㅼ젙. 
		,allowpopup : function(obj){ 
 
			var i=document.createElement('IFRAME'); 
				i.src=INIopenDomain+"allowPopupIframe.jsp"; 
				i.width=0; 
				i.height=0; 
				document.body.appendChild(i); 
		} 		
		// 寃곗젣李� �몄텧
		,pay : function(obj){
			INIStdPay.init();

			//紐⑤컮�� 寃곗젣遺덇� 泥섎━
			if(INIStdPay.boolMobile){
				INImsg.alert(INImsg.dev_err11);
				return false;
			}
			
			INIStdPay.frmobj = obj;
			if(INIStdPay.init_check("INIpaySubmit")){

				INIStdPay.vMethod ="POST";

				if(!INIStdPay.formCheck(obj)){
					return false;
				}else if(!INIStdPay.paramCheck(INIStdPay.$formObj)){
					return false;
				}else{

					if(INIStdPay.$formObj != null){

						INIStdPay.checkBoolView(INIStdPay.$formObj.serialize());

					}

				}

			}

		}

        // �뚯뒪�몄슜 get 寃곗젣李� �몄텧
		,payGet : function(obj){

			if(INIStdPay.init_check("INIpaySubmit")){

				INIStdPay.vMethod ="GET";

				if(!INIStdPay.formCheck(obj)){
					return false;
				}else if(!INIStdPay.paramCheck(INIStdPay.$formObj)){
					return false;
				}else{

					if(INIStdPay.$formObj != null){
						// �뺣낫 議고쉶��
						INIStdPay.getBasicInfo("BASIC_INFO", INIStdPay.$formObj.serialize(), INIStdPay.submit);
					}

				}

			}

		}

		// 泥댄겕�� URL濡� �꾩넚
		,payReqCheck : function(obj){


			INIStdPay.boolPayRequestCheck = true;

			INIStdPay.pay(obj);


		}


		// 寃곗젣李� POST �몄텧
		,submit : function(jsonData, status, jqXHR ){

				INIStdPay.submitBefore();
				
				// Direct option�� 寃쎌슦 popupType�� overlay泥섎읆 吏꾪뻾 �④�
				if(!INIStdPay.boolMobile && ((INIStdPay.vPayViewType == "overlay" && ! INIStdPay.boolPayRequestCheck) || (!INIStdPay.boolViewParentWindow && ! INIStdPay.boolPayRequestCheck))){
					INIStdPay.$formObj.attr("target",INIStdPay.vIframeName);
					// 寃곗젣李� �꾩슦湲�
					if(INIStdPay.boolViewParentWindow || INIStdPay.boolCard || INIStdPay.boolHpp){
						INIStdPay.viewOn();
						INIStdPay.$modalDiv.hide();
						INIStdPay.$formObj.submit();
						INIStdPay.submitAfter();
						setTimeout(function(){INIStdPay.$modalDiv.show();}, 1000);	// iframe submit �좊븣 源쒕묀�� �덈낫�닿쾶 �섍린

					}else{
						INIStdPay.viewOn();
						INIStdPay.$modalDiv.hide();
						INIStdPay.$formObj.submit();

						INIStdPay.submitAfter();
					}

					return;
				}else if(!INIStdPay.boolMobile && INIStdPay.vPayViewType == "popup" && ! INIStdPay.boolPayRequestCheck){

						if($jINI.trim(INIStdPay.$formObj.find("input[name=popupUrl]").val()).length <= 0){
							INImsg.alert(INImsg.dev_err8);
							return false;
						}

						INIStdPay.$formObj.find("input[name=popupUrl]").val();

						INIStdPay.$modalDivMsg.modal('show');

						var y_gopaymethod = INIStdPay.$formObj.find('input[name=gopaymethod]').val();
						var y_acceptmethod = INIStdPay.$formObj.find('input[name=acceptmethod]').val();
						
						//naver nik 臾댄넻�� , �좎슜移대뱶�멸꼍�� �붾㈃�� 留욊쾶 �앹뾽Size 議곗젙��.
						if (('onlycard' == y_gopaymethod || 'onlyvbank' == y_gopaymethod ) && (-1 != y_acceptmethod.indexOf('site_id(nik)')) ) {	
							INIStdPay.$stdPopup = window.open(INIStdPay.$formObj.find("input[name=popupUrl]").val(),"iniStdPayPopupIframe","width=390,height=480,resizable=no,scroll=no,left="+(screen.availWidth-660)/2+",top="+(screen.availHeight-590)/2+",modal=yes");
						} else {
							INIStdPay.$stdPopup = window.open(INIStdPay.$formObj.find("input[name=popupUrl]").val(),"iniStdPayPopupIframe","width=820,height=600,resizable=no,scroll=yes,left="+(screen.availWidth-820)/2+",top="+(screen.availHeight-600)/2+",modal=yes");
						}


						INIStdPay.$stdPopupInterval = setInterval(function(){

							if(typeof(INIStdPay.$stdPopup)=='undefined' || INIStdPay.$stdPopup.closed) {
								clearInterval(INIStdPay.$stdPopupInterval);

								INIStdPay.popupClose();

							}

						}, 5000);

						return;

				}

		}
		,popupCallback : function(){

			INIStdPay.$formObj.attr("target","iniStdPayPopupIframe");

			INIStdPay.$formObj.submit();
			INIStdPay.submitAfter();

		}
		,popupClose : function(){

			INIStdPay.$modalDivMsg.modal('hide');
			INIStdPay.$modalDivMsg.remove();

			INIStdPay.viewOffTriger();

		}

		,submitBefore : function(){

			var $input;

			if($jINI("input[name=requestByJs]").size() >0 ){
				$input = INIStdPay.$formObj.find("input[name=requestByJs]");
			}else{
				$input = $jINI("<input/>")
							.attr("id", "requestByJs")
							.attr("name", "requestByJs")
							.attr("type", "hidden")
				INIStdPay.$formObj.append($input);
			}

			$input.val("true");

			if("" == $jINI.trim(INIStdPay.$formObj.find("[name=payViewType]").val())){
				INIStdPay.$formObj.find("[name=payViewType]").val("overlay");
			}

			INIStdPay.vPayViewType = $jINI.trim(INIStdPay.$formObj.find("[name=payViewType]").val());

			if(INIStdPay.vPayViewType == null || INIStdPay.vPayViewType == ""){
				INIStdPay.vPayViewType = "overlay";
			}

			INIStdPay.$formObj.attr("action",INIStdPay.vActionUrl);

			// method �명똿
			INIStdPay.$formObj.attr("method",INIStdPay.vMethod);

			INIStdPay.$formObj.attr("accept-charset",INIStdPay.vDefaultCharset);

			// charset �명똿
			if(document.all){
				INIStdPay.vPageCharset = document.charset;
				try {
					document.charset = INIStdPay.vDefaultCharset;
				} catch (e) {
					// TODO: handle exception
				}

			}

		}
		,submitAfter : function(){
			INIStdPay.$formObj = null;


			// �뚮씪誘몃뜑 �뚯뒪�� �꾩넚 泥댄겕 �곹깭 蹂듦�
			INIStdPay.boolPayRequestCheck = false;
			INIStdPay.boolCard = false;
			INIStdPay.boolHpp = false;
			
			// charset �먯긽蹂듦뎄
			if(document.all){
				try {
					document.charset = INIStdPay.vPageCharset;
				} catch (e) {
					// TODO: handle exception
				}

			}

		}

		// �� 媛앹껜 議댁옱 �щ�泥댄겕
		,formCheck : function(obj){

			if($jINI(obj).is("form")){
				INIStdPay.$formObj = $jINI(obj);
			}else if($jINI("#"+obj).is("form")){
				INIStdPay.$formObj = $jINI("#"+obj);
			}else if($jINI("[name="+obj+"]").is("form")){

				if($jINI("[name="+obj+"]").size() > 1){
					INImsg.alert(INImsg.dev_err1);
					return false;
				}

				INIStdPay.$formObj = $jINI("[name="+obj+"]");

			}else{
				INImsg.alert(INImsg.dev_err2);
				return false;
			}
			return true;
		}

		// �뚮씪誘명꽣 �좏슚�� 泥댄겕
		,paramCheck : function(){

			var paramCheckStatus = true;

			//var ParamHashValue = "";

			$jINI(paramList).each(function(){

				vName = this.split(":")[0];

				vType = this.split(":")[1].split(",")[0];
				vLength = this.split(":")[1].split(",")[1];

				$obj = INIStdPay.$formObj.find(":input[name="+vName+"]");


				// currency媛믪씠 "" �쇨꼍�� WON�쇰줈 媛뺤젣 �곸슜
				if(vName=="currency" && $obj.val().length <= 0 ){INIStdPay.$formObj.find("[name=currency]").val("WON");}
				
				// price 0�몄� 泥댄겕(鍮뚮쭅�� 寃쎌슦 �쒖쇅)
				if(vName=="price" && $obj.val() <= 0){
					var priceCheck = true;
					var acceptmethod = INIStdPay.$formObj.find(":input[name=acceptmethod]").val();
					
					if(acceptmethod != null){
						var beginIndex  = acceptmethod.toLowerCase().indexOf("billauth(");
						var temp = acceptmethod.substring(beginIndex + 9,acceptmethod.length);
						var endIndex = temp.indexOf(")");
						var billCheck = temp.substring(0, endIndex).toLowerCase();

						if(billCheck.length > 0 && (billCheck == "card" || billCheck == "hpp")){
							priceCheck = false;
						}
					}
					if(priceCheck){
						INImsg.alert(INImsg.dev_err4.replace("#",vName + "=" + $obj.val()));
						paramCheckStatus = false;
						return false;	// each以묒���
					}
				}
				
				if($obj.size() <= 0){
					INImsg.alert(INImsg.dev_err3.replace("#",vName));
					paramCheckStatus = false;
					return false;	// each以묒���
				}else if($obj.val().length <= 0){
					INImsg.alert(INImsg.dev_err4.replace("#",vName));
					paramCheckStatus = false;
					return false;	// each以묒���
				}else{
					if(vLength.indexOf("~") >= 0){

						var vLengthStart = vLength.split("~")[0];
						var vLengthEnd	 = vLength.split("~")[1];

						if($obj.val().length < Number(vLengthStart) || $obj.val().length > Number(vLengthEnd)){
							INImsg.alert(INImsg.dev_err5.replace("#1",vName).replace("#2",$obj.val()).replace("#3",$obj.val().length).replace("#4",vLength));
							paramCheckStatus = false;
							return false;	// each以묒���
						}
					}else{
						if($obj.val().length > Number(vLength)){
							INImsg.alert(INImsg.dev_err5.replace("#1",vName).replace("#2",$obj.val()).replace("#3",$obj.val().length).replace("#4",vLength));
							paramCheckStatus = false;
							return false;	// each以묒���
						}
					}

				}

			});

			return paramCheckStatus;

		}


		// �뺣낫 議고쉶
		,getBasicInfo : function(type, paramJson, callback_f){

			paramJson['callback'] = "?";

			$jINI.ajax({
				asyn : true
				, url:INIopenDomain+'jsopenapi/basicInfo'
				, data : paramJson
				, dataType:"jsonp"
				, contentType:"application/x-www-form-urlencoded;charset=UTF-8"
				, success:callback_f
				, error:function(jqXHR,status,errorThrown ){
						INIStdPay.jsonpError(type,jqXHR,status,errorThrown);
					}
				, complete:function(jqXHR,status){
					}
			});
		}
		,jsonpError : function(type, jqXHR, status, errorThrown ){

			INImsg.alert(INImsg.dev_err6);

		}
		,checkBoolView : function(param){

			var gopaymethod  = INIStdPay.$formObj.find(":input[name=gopaymethod]").val().toLowerCase();
			var acceptmethod = INIStdPay.$formObj.find(":input[name=acceptmethod]").val();
			var cardCode	 = INIStdPay.$formObj.find(":input[name=ini_cardcode]").val();
			var payViewType	 = INIStdPay.$formObj.find(":input[name=payViewType]").val(); // �대떎��
			if(acceptmethod != null){
				//�ш린�� gopaymethod�� site_id媛� 議댁옱�섎뒗吏� 泥댄겕 gopaymehod ='onlydbank' site_id�� 議댁옱 �щ�留�..
				var beginIndex  = acceptmethod.indexOf("site_id(");
				var temp = acceptmethod.substring(beginIndex + 8,acceptmethod.length);
				var endIndex = temp.indexOf(")");
				var site_id = temp.substring(0, endIndex);

				if(site_id.length > 0 ){
					if(site_id == "wmk" || site_id == "nikom" || site_id == "tmon"|| site_id == "nik" || site_id == "nexon"){ //�μ뒯 �꾩슜李� 異붽�
						if(gopaymethod == "onlydbank"){
							if(!INIStdPay.jsLoaded){
								$JSImport.load(INIopenDomain+"./stdjs/importPri.js", function(){
									INIStdPay.jsLoaded = true;
									fn_submit();
								});
							}else{
								fn_submit();
							}
							return;
						}
					}
				}
			}
			//onlyisp �� onlyacard �낅젰�쒖뿉留�
			if(gopaymethod == "onlyisp" || gopaymethod == "onlyacard" || gopaymethod == "onlyvcard"){
				if(acceptmethod.indexOf("cardonly") !=  -1){
					if("" != cardCode){
						param['callback'] = "?";

						$jINI.ajax({

							 url: INIStdPay.vCheckActionUrl
							, type : "POST"
							, data : param
							, dataType:"jsonp"
							, jsonp : "callback"
							, contentType:"application/x-www-form-urlencoded;charset=UTF-8"
							, success:function(jsonData,status,errorThrown){
								if(jsonData.resultCode == "0000"){
									if(jsonData.acceptData != null){
										INIStdPay.$formObj.find("input[name=acceptmethod]").val(jsonData.acceptData);
									}
									if(jsonData.viewState =="on"){
										INIStdPay.boolViewParentWindow = true
										INIStdPay.submit();
									}else{
										//�앹뾽�곹깭�대㈃ �ㅻ쾭�덉씠濡� 蹂�寃� 泥섎━(Direct�듭뀡�쇨꼍��)
										INIStdPay.boolViewParentWindow = false
										INIStdPay.boolCard = true;
										INIStdPay.submit();
									}

								}else{
									INImsg.alert(jsonData.resultMsg);
									INIStdPay.viewOff();
								}
							}
							, error:function(jqXHR,status,errorThrown ){
									alert("[Connection Failure] : "+errorThrown);
									INIStdPay.viewOff();
								}
							, complete:function(jqXHR,status){

								}
						});

					}else{
						INImsg.alert(INImsg.dev_err10);
						INIStdPay.viewOff();
					}

				}else{
					INIStdPay.boolViewParentWindow = true
					INIStdPay.submit();
				}
			}else if(gopaymethod == "onlyhpp" && payViewType == "popup" && acceptmethod.indexOf("onlyhpp_popup") > -1){ //�대��� �ㅼ씠�됲듃 寃곗젣�몃뜲 popup�쇰줈 吏꾪뻾�섎뒗 媛�留뱀젏 �뺣텇�� 異붽�
				//�앹뾽�곹깭�대㈃ �ㅻ쾭�덉씠濡� 蹂�寃� 泥섎━(Direct�듭뀡�쇨꼍��)
				INIStdPay.boolViewParentWindow = false
				INIStdPay.boolHpp = true;
				INIStdPay.submit();
			}else{
				INIStdPay.boolViewParentWindow = true
				INIStdPay.submit();
			}
		}

};

var $JSImport = {
        load : function(_url, callback) {
                 if (_url == undefined)
                        return;
                 if (_url.indexOf('.js') != -1) {

                        var head = document.getElementsByTagName('head').item(0);
                        var script = document.createElement('script');
                        script.type = 'text/javascript';
                        script.charset = "utf-8";
                        script.src = _url;
                        head.appendChild(script);

                        var jsExecuteChk = false;
                        script.onload = function() {
                            if (callback != undefined && !jsExecuteChk) {
                            		jsExecuteChk = true;
                                    callback();
                            }
                        }

                      //for IE Browsers
                    	if(navigator.userAgent.indexOf("MSIE 8.0") > -1 || navigator.userAgent.indexOf("MSIE 7.0") > -1|| document.documentMode == '7' ||  document.documentMode == '8') {
                    		ieLoadBugFix(script, function(){
                            	callback();
                            });

                    	}

                       function ieLoadBugFix(scriptElement, callback){
                               if (scriptElement.readyState=='loaded'  || scriptElement.readyState=='complete') {
                            	    if(!jsExecuteChk){
                            	    	callback();
                            	    }
                                }else {
                                    setTimeout(function() {ieLoadBugFix(scriptElement, callback); }, 100);
                                }
                        }

                }
        }

}

window.onbeforeunload = function(){

	if(INIStdPay.$stdPopup != null ){
			INIStdPay.$stdPopup.close();

	}
}

window.name = "INIpayStd_Return";
