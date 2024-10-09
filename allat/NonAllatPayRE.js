var _allat_tx_url = "tx.allatpay.com";

var _allat_pay_win;
var _allat_settimeout_obj;
var _allat_user_agent;

// IE Check
function allat_explore_check(){
	if( navigator.appName.indexOf("Microsoft")==-1 && navigator.userAgent.indexOf("Trident")==-1 ){
		return false;
	} else {
		return true
	}
}

// 스마트폰 Check
function allat_smarthp_check(){
	if( navigator.userAgent.match("(.)*(iPhone|iPad|Android)(.)*") ){
		return true;
	} else {
		return false;
	}
}

// Windows OS Check
function allat_windows_check(){
	if( navigator.userAgent.indexOf("Windows")==-1 ){
		return false;
	} else {
		return true;
	}
}

// 팝업종료 체크 Start
function AllatPay_Closechk_Start(){
	window.clearTimeout(_allat_settimeout_obj);
	if( _allat_pay_win && _allat_pay_win.closed ){
		if( result_submit != undefined ){
			result_submit('9998','결제를 완료하지 않았습니다.');
		}
	} else {
		_allat_settimeout_obj = window.setTimeout( "AllatPay_Closechk_Start()", 3000 );
	}
}

// 팝업종료 체크 END
function AllatPay_Closechk_End(){
	_allat_pay_win = undefined;
	window.clearTimeout(_allat_settimeout_obj);
	/* div 설정 */
	var divAllatPay = document.getElementById("ALLAT_PAY");
	var dibAllatPayDim = document.getElementById("ALLAT_PAY_DIM");
	if(divAllatPay != null && dibAllatPayDim != null) {
		divAllatPay.style.display = "none";
		dibAllatPayDim.style.display = "none";
	}
}


/**------------------------ 승인 ------------------------------**/
function AllatPay_Approval(dfm) {
	
	// allat_end_data 필드 체크
	if( dfm.allat_enc_data == undefined ){
		alert( "allat_enc_data가 없습니다.");
		return;
	} else if( dfm.allat_enc_data.type != 'hidden' ){
		alert("allat_enc_data가 hidden이 아닙니다.");
		return;
	}

	// allat_shop_id 필드 체크
	if( dfm.allat_shop_id == undefined ){
		alert( "allat_shop_id가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_shop_id.value) == "" || f_textLen(dfm.allat_shop_id,20) ){
		alert("allat_shop_id는 1~20Byte 입니다.");
		return;
	}

	// allat_order_no 필드 체크
	if( dfm.allat_order_no == undefined ){
		alert("allat_order_no가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_order_no.value) == "" || f_textLen(dfm.allat_order_no,70) ){
		alert("allat_order_no는 1~70Byte 입니다.");
		return;
	}

	// allat_amt 필드 체크
	if( dfm.allat_amt == undefined ){
		alert("allat_amt가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_amt.value) == "" || !f_checkNumber(dfm.allat_amt.value) || f_textLen(dfm.allat_amt,12) ){
		alert("allat_amt는 1~9999999999999999 범위 입니다.");
		return;
	}

	// allat_pmember_id 필드 체크
	if( dfm.allat_pmember_id == undefined ){
		alert("allat_pmember_id가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_pmember_id.value) == "" || f_textLen(dfm.allat_pmember_id,20) ){
		alert("allat_pmember_id는 1~20Byte 입니다.");
		return;
	}

	// allat_product_cd 필드 체크
	if( dfm.allat_product_cd == undefined ){
		alert("allat_product_cd가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_product_cd.value) == "" || f_textLen(dfm.allat_product_cd,1000) ){
		alert("allat_product_cd는 1~1000Byte 입니다.");
		return;
	}

	// allat_product_nm 필드 체크
	if( dfm.allat_product_nm == undefined ){
		alert("allat_product_nm이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_product_nm.value) == "" || f_textLen(dfm.allat_product_nm,1000) ){
		alert("allat_product_nm은 1~1000Byte 입니다.");
		return;
	}

	// allat_buyer_nm 필드 체크
	if( dfm.allat_buyer_nm == undefined ){
		alert("allat_buyer_nm이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_buyer_nm.value) == "" || f_textLen(dfm.allat_buyer_nm,20) ){
		alert("allat_buyer_nm은 1~20Byte 입니다.");
		return;
	}

	// allat_recp_nm 필드 체크
	if( dfm.allat_recp_nm == undefined ){
		alert("allat_recp_nm이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_recp_nm.value) == "" || f_textLen(dfm.allat_recp_nm,20) ){
		alert("allat_recp_nm은 1~20Byte 입니다.");
		return;
	}

	// allat_recp_addr 필드 체크
	if( dfm.allat_recp_addr == undefined ){
		alert("allat_recp_addr이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_recp_addr.value) == "" || f_textLen(dfm.allat_recp_addr,120) ){
		alert("allat_recp_addr은 1~120Byte 입니다.");
		return;
	}

	// shop_receive_url 필드 체크
	if( dfm.shop_receive_url == undefined ){
		alert("shop_receive_url이 없습니다.");
		return;
	} else if( f_trim(dfm.shop_receive_url.value) == "" ){
		alert("shop_receive_url의 값이 없습니다.");
		return;
	}
	
	// 상점도메인 설정
	_allat_user_agent = navigator.userAgent;
	var shop_domain = String(document.location.href.match(/http[s]*:\/\/([a-zA-Z0-9\-\.]*)/)[0]);	
	var width	 = 785;
	var height	 = 555;
	var ad_width = 270;

	if(_allat_user_agent.indexOf("Edge")>-1) {
		width = 787;
		height = 556;
	}
	if(_allat_user_agent.indexOf("Trident")>-1) {
		width = 781;
		height = 549;
	}
		
	if( dfm.allat_layer_yn != undefined && f_trim(dfm.allat_layer_yn.value).toUpperCase() == "Y" &&
		document.getElementById("ALLAT_PAY") != undefined && document.getElementById("ALLAT_PAY_DIM") != undefined ) {
		var layer_x = 0;
		var layer_y = 0;

		if( dfm.allat_layer_x != undefined && dfm.allat_layer_x.value != "" ){
			layer_x = dfm.allat_layer_x.value;
		} else {
			if(document.body!=null) {
				if(document.body.clientWidth>=width) layer_x =(document.body.clientWidth-width)/2;
			}
		}
		if( dfm.allat_layer_y != undefined && dfm.allat_layer_y.value != "" ){
			layer_y = dfm.allat_layer_y.value;
		} else {
			if(document.body!=null) {
				if(document.body.clientHeight>=height) layer_y=(document.body.clientHeight-height)/2;
			}
		}
		width += ad_width;
		document.getElementById("ALLAT_PAY").style.width		= width  + "px";
		document.getElementById("ALLAT_PAY").style.height		= height + "px";
		document.getElementById("ALLAT_PAY").style.left			= layer_x + "px";
		document.getElementById("ALLAT_PAY").style.top			= layer_y + "px";
		document.getElementById("ALLAT_PAY").style.display		= "block";
		document.getElementById("ALLAT_BASIC_FRAME").style.width  = width  + "px";
		document.getElementById("ALLAT_BASIC_FRAME").style.height = height + "px";
		document.getElementById("ALLAT_BASIC_FRAME").src = "https://" + _allat_tx_url + "/common/iframe_blank.jsp";
		document.getElementById("ALLAT_PAY_DIM").style.display  = "block";
		
		dfm.target="ALLAT_BASIC_FRAME";
	} else {		
		var top		= (screen.availHeight / 2) - (height / 2); 
		var left	= (screen.availWidth / 2) - (width / 2);
		
		_allat_pay_win = window.open("https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_dummy.jsp?allat_shop_domain="+shop_domain, "NONALLATPAYRE_FRAME", "width="+width+",height="+height+",top="+top+",left="+left+",toolbar=0,menubar=0,status=0,scrollbar=0,resizable=0");

		if( !_allat_pay_win ){
			if(_allat_user_agent.indexOf("Firefox")>-1 || _allat_user_agent.indexOf("Chrome")>-1 || _allat_user_agent.indexOf("Safari")>-1) {
				alert("팝업이 차단되었습니다.\n팝업차단 해제 후 다시 시도해 주시기 바랍니다.");
			}
			return;
		}
		
		dfm.target="NONALLATPAYRE_FRAME";
	}
	
	if(dfm.allat_asiana_mile_yn == undefined || f_trim(dfm.allat_asiana_mile_yn.value).toUpperCase() != "Y") {
		if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
			dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonre/nonre_main.jsp";
		} else {
			dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_main.jsp";
		}
	} else if(f_trim(dfm.allat_asiana_mile_yn.value).toUpperCase() == "Y") {
		if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
			dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonmile/nonmile_main.jsp";
		} else {
			dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonmile/nonmile_main.jsp";
		}
	}

	dfm.method="post";
	dfm.submit();
}


/**------------------------ C2C 승인 ------------------------------**/
function AllatPay_ApprovalC2C(dfm) {
	
	// allat_end_data 필드 체크
	if( dfm.allat_enc_data == undefined ){
		alert( "allat_enc_data가 없습니다.");
		return;
	} else if( dfm.allat_enc_data.type != 'hidden' ){
		alert("allat_enc_data가 hidden이 아닙니다.");
		return;
	}
	
	// allat_market_id 필드 체크
	if( dfm.allat_market_id == undefined ){
		alert( "allat_market_id가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_market_id.value) == "" || f_textLen(dfm.allat_market_id,10) ){
		alert("allat_market_id는 1~10Byte 입니다.");
		return;
	}
	
	// allat_seller_id 필드 체크
	if( dfm.allat_seller_id == undefined ){
		alert( "allat_seller_id가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_seller_id.value) == "" || f_textLen(dfm.allat_seller_id,20) ){
		alert("allat_seller_id는 1~20Byte 입니다.");
		return;
	}

	// allat_order_no 필드 체크
	if( dfm.allat_order_no == undefined ){
		alert("allat_order_no가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_order_no.value) == "" || f_textLen(dfm.allat_order_no,70) ){
		alert("allat_order_no는 1~70Byte 입니다.");
		return;
	}

	// allat_amt 필드 체크
	if( dfm.allat_amt == undefined ){
		alert("allat_amt가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_amt.value) == "" || !f_checkNumber(dfm.allat_amt.value) || f_textLen(dfm.allat_amt,12) ){
		alert("allat_amt는 1~9999999999999999 범위 입니다.");
		return;
	}

	// allat_pmember_id 필드 체크
	if( dfm.allat_pmember_id == undefined ){
		alert("allat_pmember_id가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_pmember_id.value) == "" || f_textLen(dfm.allat_pmember_id,20) ){
		alert("allat_pmember_id는 1~20Byte 입니다.");
		return;
	}

	// allat_buyer_nm 필드 체크
	if( dfm.allat_buyer_nm == undefined ){
		alert("allat_buyer_nm이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_buyer_nm.value) == "" || f_textLen(dfm.allat_buyer_nm,20) ){
		alert("allat_buyer_nm은 1~20Byte 입니다.");
		return;
	}

	// allat_recp_nm 필드 체크
	if( dfm.allat_recp_nm == undefined ){
		alert("allat_recp_nm이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_recp_nm.value) == "" || f_textLen(dfm.allat_recp_nm,20) ){
		alert("allat_recp_nm은 1~20Byte 입니다.");
		return;
	}

	// allat_recp_addr 필드 체크
	if( dfm.allat_recp_addr == undefined ){
		alert("allat_recp_addr이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_recp_addr.value) == "" || f_textLen(dfm.allat_recp_addr,120) ){
		alert("allat_recp_addr은 1~120Byte 입니다.");
		return;
	}

	// shop_receive_url 필드 체크
	if( dfm.shop_receive_url == undefined ){
		alert("shop_receive_url이 없습니다.");
		return;
	} else if( f_trim(dfm.shop_receive_url.value) == "" ){
		alert("shop_receive_url의 값이 없습니다.");
		return;
	}	
	
	// 상점도메인 설정
	_allat_user_agent = navigator.userAgent;
	var shop_domain = String(document.location.href.match(/http[s]*:\/\/([a-zA-Z0-9\-\.]*)/)[0]);	
	var width	= 785;
	var height	= 554;
	var ad_width = 270;
	
	if(_allat_user_agent.indexOf("Edge")>-1) {
		width = 787;
		height = 556;
	}
	if(_allat_user_agent.indexOf("Trident")>-1) {
		width = 781;
		height = 549;
	}

	if( dfm.allat_layer_yn != undefined && f_trim(dfm.allat_layer_yn.value).toUpperCase() == "Y" &&
		document.getElementById("ALLAT_PAY") != undefined && document.getElementById("ALLAT_PAY_DIM") != undefined ) {
		var layer_x = 0;
		var layer_y = 0;

		if( dfm.allat_layer_x != undefined && dfm.allat_layer_x.value != "" ){
			layer_x = dfm.allat_layer_x.value;
		} else {
			if(document.body!=null) {
				if(document.body.clientWidth>=width) layer_x =(document.body.clientWidth-width)/2;
			}
		}
		if( dfm.allat_layer_y != undefined && dfm.allat_layer_y.value != "" ){
			layer_y = dfm.allat_layer_y.value;
		} else {
			if(document.body!=null) {
				if(document.body.clientHeight>=height) layer_y=(document.body.clientHeight-height)/2;
			}
		}
		width += ad_width;
		document.getElementById("ALLAT_PAY").style.width		= width  + "px";
		document.getElementById("ALLAT_PAY").style.height		= height + "px";
		document.getElementById("ALLAT_PAY").style.left			= layer_x + "px";
		document.getElementById("ALLAT_PAY").style.top			= layer_y + "px";
		document.getElementById("ALLAT_PAY").style.display		= "block";
		document.getElementById("ALLAT_BASIC_FRAME").style.width  = width  + "px";
		document.getElementById("ALLAT_BASIC_FRAME").style.height = height + "px";
		document.getElementById("ALLAT_BASIC_FRAME").src = "https://" + _allat_tx_url + "/common/iframe_blank.jsp";
		document.getElementById("ALLAT_PAY_DIM").style.display  = "block";
		
		dfm.target="ALLAT_BASIC_FRAME";
	} else {
		var top		= (screen.availHeight / 2) - (height / 2); 
		var left	= (screen.availWidth / 2) - (width / 2);
		
		_allat_pay_win = window.open("https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_dummy.jsp?allat_shop_domain="+shop_domain, "NONALLATPAYRE_FRAME", "width="+width+",height="+height+",top="+top+",left="+left+",toolbar=0,menubar=0,status=0,scrollbar=0,resizable=0");

		if( !_allat_pay_win ){
			if(_allat_user_agent.indexOf("Firefox")>-1 || _allat_user_agent.indexOf("Chrome")>-1 || _allat_user_agent.indexOf("Safari")>-1) {
				alert("팝업이 차단되었습니다.\n팝업차단 해제 후 다시 시도해 주시기 바랍니다.");
			}
			return;
		}
		dfm.target="NONALLATPAYRE_FRAME";
	}

	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonre/nonre_main.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_main.jsp";
	}

	dfm.method="post";
	dfm.submit();
}


/*---------------------------------- UTIL ---------------------------------*/
function f_textLen( obj, len ) {
     var t = obj.value;
     var tmp = 0;
     var Alpha = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890~! @#$%^&*()-_=+|\}{[]:;'<>,.?/" ;
     
     if (t.length > 0 ) {
          for (i=0; i<t.length; i++){
               if(Alpha.indexOf(t.substring(i,i+1))<0) {
                    tmp = parseInt(tmp,10) + 2;
               } else {
                    tmp = parseInt(tmp,10) + 1;
               }
          }
          if (len < tmp) {
               return true;                
          }
     }
     return false;  
}

//문자열의 앞뒤 공백문자 제거
function f_trim( value ) {
     value = value.replace(/^\s*/,'').replace(/\s*$/, ''); 
     return value; 
}

//숫자만으로 이루어져 있는지 체크
function f_checkNumber(input) {
	if( input == '' || !input.match("^[0-9]") ){
		return false;
	}
	return true;
}
