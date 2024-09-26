var _allat_tx_url = "tx.allatpay.com";

document.write("<div id='ALLAT_MOBILE_PAY' style='left:0px; top:0px; width:320px; height:600px; position:absolute;  z-index:1000; display:none; background-color:white;'><iframe id='ALLAT_MOBILE_FRAME' name='ALLAT_MOBILE_FRAME' src='https://" + _allat_tx_url + "/common/iframe_blank.jsp' frameborder=0 width=100%px height=100%px scrolling=no></iframe></div>");

function Allat_Mobile_Approval(dfm, x, y) {
	
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

	// allat_allat_autoscreen_yn 필드에 따른 결제창크기 수정
	if( dfm.allat_autoscreen_yn != undefined && f_trim(dfm.allat_autoscreen_yn.value).toUpperCase() == 'Y' ){
		document.getElementById("ALLAT_MOBILE_PAY").style.width="100%";
	} else {
		document.getElementById("ALLAT_MOBILE_PAY").style.width="320px";
	}

	/* x,y,w,h 값 체크 */
	if(!f_checkNumber(x)) x=0;
	if(!f_checkNumber(y)) y=0;

	/* div 설정 */
	document.getElementById("ALLAT_MOBILE_PAY").style.left = x + "px";
	document.getElementById("ALLAT_MOBILE_PAY").style.top = y + "px";
	document.getElementById("ALLAT_MOBILE_PAY").style.display = "block";
	
	if(dfm.allat_asiana_mile_yn == undefined || f_trim(dfm.allat_asiana_mile_yn.value).toUpperCase() != "Y"){
	
		if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
			dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/mobile/mobile_main.jsp";
		} else {
			dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/mobile/mobile_main.jsp";
		}
		
	}else{
		
		if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
			dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/mobile/mobile_mileage_main.jsp";
		} else {
			dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/mobile/mobile_mileage_main.jsp";
		}		
		
	}

	dfm.target = "ALLAT_MOBILE_FRAME";
	dfm.submit();
}

function Allat_Mobile_ApprovalNewPlus(dfm, x, y) {
	
	// allat_end_data 필드 체크
	if( dfm.allat_enc_data == undefined ){
		alert( "allat_enc_data가 없습니다.");
		return;
	} else if( dfm.allat_enc_data.type != 'hidden' ){
		alert("allat_enc_data가 hidden이 아닙니다.");
		return;
	}

	// allat_pay_type 필드 체크
	if( dfm.allat_pay_type == undefined ){
		alert( "allat_pay_type가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_pay_type.value) == "" ){
		alert( "allat_pay_type의 값이 없습니다." );
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
		alert("allat_buyer_nm은 1~20Byte 입니다.");
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

	// allat_allat_autoscreen_yn 필드에 따른 결제창크기 수정
	if( dfm.allat_autoscreen_yn != undefined && f_trim(dfm.allat_autoscreen_yn.value).toUpperCase() == 'Y' ){
		document.getElementById("ALLAT_MOBILE_PAY").style.width="100%";
	} else {
		document.getElementById("ALLAT_MOBILE_PAY").style.width="320px";
	}

	/* x,y,w,h 값 체크 */
	if(!f_checkNumber(x)) x=0;
	if(!f_checkNumber(y)) y=0;

	/* div 설정 */
	document.getElementById("ALLAT_MOBILE_PAY").style.left = x + "px";
	document.getElementById("ALLAT_MOBILE_PAY").style.top = y + "px";
	document.getElementById("ALLAT_MOBILE_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/mobile/mobile_np_main.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/mobile/mobile_np_main.jsp";
	}

	dfm.target = "ALLAT_MOBILE_FRAME";
	dfm.submit();
}

function Allat_Mobile_Close(){
	/* div 설정 */
	document.getElementById("ALLAT_MOBILE_PAY").style.display = "none";
}

function Allat_Mobile_Cancel(dfm) {
	
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
	} else if( f_trim(dfm.allat_order_no.value) == "" || f_textLen(dfm.allat_order_no,80) ){
		alert("allat_order_no는 1~80Byte 입니다.");
		return;
	}

	// allat_amt 필드 체크
	if( dfm.allat_amt == undefined ){
		alert("allat_amt가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_amt.value) == "" || !f_checkNumber(dfm.allat_amt.value) || f_textLen(dfm.allat_amt,12) ){
		alert("allat_amt는 1~999999999999 범위 입니다.");
		return;
	}
	
	// allat_pay_type 필드 체크
	if( dfm.allat_pay_type == undefined ){
		alert("allat_pay_type이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_pay_type.value) == "" || f_textLen(dfm.allat_pay_type,6) ){
		alert("allat_pay_type 1~6Byte 입니다.");
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
	
	dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/mobile/mobile_cancel.jsp";
	dfm.target = "ALLAT_MOBILE_FRAME";
	dfm.submit();
}

function Allat_Mobile_Fix(dfm, x, y) {
	
	// allat_enc_data 필드 체크
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
	} else if( f_trim(dfm.allat_order_no.value) == "" || f_textLen(dfm.allat_order_no,80) ){
		alert("allat_order_no는 1~80Byte 입니다.");
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

	// allat_pmember_id 필드 체크
	if( f_trim(dfm.allat_pmember_id.value) != "" && f_textLen(dfm.allat_pmember_id,20) ){
		alert("allat_pmember_id는 1~20Byte 입니다.");
		return;
	}	
	// allat_amt 필드 체크
	if( f_trim(dfm.allat_amt.value) != "" && f_textLen(dfm.allat_amt,20) ){
		alert("allat_amt는 1~20Byte 입니다.");
		return;
	}	
	// allat_product_nm 필드 체크
	if( f_trim(dfm.allat_product_nm.value) != "" && f_textLen(dfm.allat_product_nm,100) ){
		alert("allat_product_nm는 1~100Byte 입니다.");
		return;
	}	
	// allat_email_addr 필드 체크
	if( f_trim(dfm.allat_email_addr.value) != "" && f_textLen(dfm.allat_email_addr,50) ){
		alert("allat_email_addr 1~50Byte 입니다.");
		return;
	}	
	// allat_registry_no 필드 체크
	if( dfm.allat_registry_no != "" && dfm.allat_registry_no.value.length != 0 && dfm.allat_registry_no.value.length != 6  ){
		alert("allat_registry_no 를 잘못 입력 하셨습니다.");
		return;
	}	
	
	document.getElementById("ALLAT_MOBILE_PAY").style.width="100%";
	
	document.getElementById("ALLAT_MOBILE_PAY").style.display = "block";
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/mobile/mobile_fix_main.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/mobile/mobile_fix_main.jsp";
	}

	dfm.target = "ALLAT_MOBILE_FRAME";
	dfm.submit();
}


function Allat_Mobile_Hp_Fix(dfm, x, y) {
	
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

	// allat_allat_autoscreen_yn 필드에 따른 결제창크기 수정
	if( dfm.allat_autoscreen_yn != undefined && f_trim(dfm.allat_autoscreen_yn.value).toUpperCase() == 'Y' ){
		document.getElementById("ALLAT_MOBILE_PAY").style.width="100%";
	} else {
		document.getElementById("ALLAT_MOBILE_PAY").style.width="320px";
	}

	/* x,y,w,h 값 체크 */
	if(!f_checkNumber(x)) x=0;
	if(!f_checkNumber(y)) y=0;

	/* div 설정 */
	document.getElementById("ALLAT_MOBILE_PAY").style.left = x + "px";
	document.getElementById("ALLAT_MOBILE_PAY").style.top = y + "px";
	document.getElementById("ALLAT_MOBILE_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/mobile/mobile_hp_fix_main.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/mobile/mobile_hp_fix_main.jsp";
	}

	dfm.target = "ALLAT_MOBILE_FRAME";
	dfm.submit();
}

// 문자열의 앞뒤 공백문자 제거
function f_trim( value ) {
     value = value.replace(/^\s*/,'').replace(/\s*$/, ''); 
     return value; 
}

// 문자열의 길이 Check
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

// 숫자만으로 이루어져 있는지 체크
function f_checkNumber(input) {
	if( input == '' || !input.match("^[0-9]") ){
		return false;
	}
	return true;
}