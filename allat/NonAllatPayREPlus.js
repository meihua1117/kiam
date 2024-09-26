var _allat_tx_url = "tx.allatpay.com";
var _allat_userAgent = navigator.userAgent;

document.write("<div id='ALLAT_PLUS_PAY' style='left:0px; top:0px; width:0px; height:0px; position:absolute; z-index:1000; display:block; background-color:white;'><iframe id='ALLAT_PLUS_FRAME' name='ALLAT_PLUS_FRAME' src='https://" + _allat_tx_url + "/common/iframe_blank.jsp' frameborder=0 width=0px height=0px scrolling=no></iframe></div>");

function Allat_Plus_Approval(dfm, x, y) {
	var _spay_type;
		
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
	var shop_domain_input = document.createElement("input");
	shop_domain_input.type = "hidden";
	shop_domain_input.name = "allat_shop_domain";
	shop_domain_input.value = String(document.location.href.match(/http[s]*:\/\/([a-zA-Z0-9\-\.]*)/)[0]);
	dfm.appendChild(shop_domain_input);
	
	if(dfm.allat_spay_type==undefined || f_trim(dfm.allat_spay_type.value)=="" ){
	  _spay_type="";
	} else {
	  _spay_type=f_trim(dfm.allat_spay_type.value).toUpperCase();
	}	

	if(_spay_type == "SSGPAY") { 
		if(dfm.allat_spay_hp_no == undefined) {
			alert("allat_spay_hp_no가 없습니다.");
			return;
		} else if(f_trim(dfm.allat_spay_hp_no.value)  == "" || !f_checkNumber(dfm.allat_spay_hp_no.value) || f_textLen(dfm.allat_spay_hp_no, 11)) {
			alert("휴대폰 번호를 바르게 입력하세요.");
			return;
		}
	}
	
	// 결제창타입에 따른 처리
	if( dfm.allat_layer_yn != undefined && f_trim(dfm.allat_layer_yn.value).toUpperCase() == "Y"
		&& dfm.allat_pay_type.value.match("CARD|NOR") ){

		var plus_layer_x = 0;
		var plus_layer_y = 0;
		var plus_layer_width  = 400;
		var plus_layer_height = 420;
		var pay_type  = f_trim(dfm.allat_pay_type.value);
		var card_code = f_trim(dfm.allat_card_code.value).toUpperCase();
		
		if( pay_type.match("CARD") ){
			if( card_code == "53"){
				plus_layer_width  = 641;
				plus_layer_height = 460;
			} else if( card_code == "63" || card_code == "82"){
				plus_layer_width  = 595;
				plus_layer_height = 435;
			} else if( card_code == "66"){
				plus_layer_width  = 404;
				plus_layer_height = 400;
			} else if( card_code == "67"){
				plus_layer_width  = 390;
				plus_layer_height = 410;
			} else if( card_code == "68"){
				plus_layer_width  = 639;
				plus_layer_height = 490;
			} else if( card_code == "71"){
				plus_layer_width  = 400;
				plus_layer_height = 400;
			} else if( card_code == "C1"){
				if( dfm.allat_sp_chain_code != undefined && dfm.allat_sp_chain_code.value == "SSPAY" &&
					dfm.allat_sp_order_user_id != undefined && dfm.allat_sp_order_user_id.value != "" ){
					plus_layer_width  = 505;
					plus_layer_height = 750;
				} else {
					plus_layer_width  = 400;
					plus_layer_height = 420;
				}
			} else if( card_code == "C8"){
				plus_layer_width  = 950;
				plus_layer_height = 580;
			} else if( card_code == "62" ){
				plus_layer_width  = 458;
				plus_layer_height = 456;
			} else if( card_code.match("61|73|34|35|37") ){
				plus_layer_width  = 410;
				plus_layer_height = 400;
			} else if( card_code.match("75") ) {
				plus_layer_width  = 600;
				plus_layer_height = 510;
			}
		} else {
			if(_spay_type.match("KAKAO|KAKAOMONEY")) {
				plus_layer_width  = 426;
				plus_layer_height = 550;
			} else if(_spay_type == "SSPAY") {
				plus_layer_width  = 400;
				plus_layer_height = 660;
			} else if(_spay_type == "SSGPAY") {
				plus_layer_width  = 640;
				plus_layer_height = 675;
			} else {
				plus_layer_width  = 410;
				plus_layer_height = 440;
			}
		}
		
		plus_layer_width += 12;
		plus_layer_height += 47;

		if( dfm.allat_layer_x != undefined && dfm.allat_layer_x.value != "" ){
			plus_layer_x = dfm.allat_layer_x.value;
		} else {
			if(document.body!=null) {
				if(document.body.clientWidth>=plus_layer_width) plus_layer_x =(document.body.clientWidth-plus_layer_width)/2;
			}
		}
		if( dfm.allat_layer_y != undefined && dfm.allat_layer_y.value != "" ){
			plus_layer_y = dfm.allat_layer_y.value;
		} else {
			if(document.body!=null) {
				if(document.body.clientHeight>=plus_layer_height) plus_layer_y=(document.body.clientHeight-plus_layer_height)/2;
			}
		}
		
		document.getElementById("ALLAT_PLUS_PAY").style.width    = plus_layer_width  + "px";
		document.getElementById("ALLAT_PLUS_PAY").style.height   = plus_layer_height + "px";
		document.getElementById("ALLAT_PLUS_PAY").style.left     = plus_layer_x + "px";
		document.getElementById("ALLAT_PLUS_PAY").style.top      = plus_layer_y + "px";
		document.getElementById("ALLAT_PLUS_FRAME").style.width  = plus_layer_width  + "px";
		document.getElementById("ALLAT_PLUS_FRAME").style.height = plus_layer_height + "px";
		document.getElementById("ALLAT_PLUS_FRAME").style.border = "1px black solid";
	} else {
		document.getElementById("ALLAT_PLUS_PAY").style.width    = 0;
		document.getElementById("ALLAT_PLUS_PAY").style.height   = 0;
		document.getElementById("ALLAT_PLUS_PAY").style.left     = 0;
		document.getElementById("ALLAT_PLUS_PAY").style.top      = 0;
		document.getElementById("ALLAT_PLUS_FRAME").style.width  = 0;
		document.getElementById("ALLAT_PLUS_FRAME").style.height = 0;
	}

	document.getElementById("ALLAT_PLUS_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonre/nonre_plus.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_plus.jsp";
	}

	dfm.target = "ALLAT_PLUS_FRAME";
	dfm.submit();
}

function Allat_Plus_Escrow(dfm, x, y) {
	
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

	// allat_pay_type 필드 체크
	if( dfm.allat_pay_type == undefined ){
		alert("allat_pay_type이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_pay_type.value) == "" || f_textLen(dfm.allat_pay_type,6) ){
		alert("allat_pay_type은 1~6Byte 입니다.");
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
	var shop_domain_input = document.createElement("input");
	shop_domain_input.type = "hidden";
	shop_domain_input.name = "allat_shop_domain";
	shop_domain_input.value = String(document.location.href.match(/http[s]*:\/\/([a-zA-Z0-9\-\.]*)/)[0]);
	dfm.appendChild(shop_domain_input);

	document.getElementById("ALLAT_PLUS_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonre/nonre_escrowconfirm.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_escrowconfirm.jsp";
	}

	dfm.target = "ALLAT_PLUS_FRAME";
	dfm.submit();
}

//C2C 에스크로 고객확인
function Allat_Plus_Escrow_C2C(dfm, x, y) {

	// allat_enc_data 필드 체크
	if( dfm.allat_enc_data == undefined ){
		alert( "allat_enc_data가 없습니다.");
		return;
	} else if( dfm.allat_enc_data.type != 'hidden' ){
		alert("allat_enc_data가 hidden이 아닙니다.");
		return;
	}

	// allat_market_id 필드 체크
	if( dfm.allat_market_id == undefined ){
		alert("allat_market_id가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_market_id.value) == "" || f_textLen(dfm.allat_market_id,10) ){
		alert("allat_market_id는 1~10Byte 입니다.");
		return;
	}

	// allat_seller_id 필드 체크
	if( dfm.allat_seller_id == undefined ){
		alert("allat_seller_id가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_seller_id.value) == "" || f_textLen(dfm.allat_seller_id,20) ){
		alert("allat_seller_id는 1~20Byte 입니다.");
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

	// allat_pay_type 필드 체크
	if( dfm.allat_pay_type == undefined ){
		alert("allat_pay_type이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_pay_type.value) == "" || f_textLen(dfm.allat_pay_type,6) ){
		alert("allat_pay_type은 1~6Byte 입니다.");
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
	var shop_domain_input = document.createElement("input");
	shop_domain_input.type = "hidden";
	shop_domain_input.name = "allat_shop_domain";
	shop_domain_input.value = String(document.location.href.match(/http[s]*:\/\/([a-zA-Z0-9\-\.]*)/)[0]);
	dfm.appendChild(shop_domain_input);
	
	document.getElementById("ALLAT_PLUS_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonre/nonre_escrowconfirm_c2c.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_escrowconfirm_c2c.jsp";
	}

	dfm.target = "ALLAT_PLUS_FRAME";
	dfm.submit();
}

function Allat_Plus_Fix(dfm, x, y) {
	
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

	document.getElementById("ALLAT_PLUS_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonre/nonre_fix.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_fix.jsp";
	}

	dfm.target = "ALLAT_PLUS_FRAME";
	dfm.submit();
}
function Allat_Plus_Fix_Cancel(dfm) {
	
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
	
	// allat_fix_key 필드 체크
	if( dfm.allat_fix_key == undefined ){
		alert("allat_fix_key이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_fix_key.value) == "" ){
		alert("allat_fix_key의 값이 없습니다.");
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
	document.getElementById("ALLAT_PLUS_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonre/nonre_fix_cancel.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_fix_cancel.jsp";
	}

	dfm.target = "ALLAT_PLUS_FRAME";
	dfm.submit();
}


/**------------------------ 승인 ------------------------------**/
function Allat_Plus_Hp_Fix(dfm) {
	
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
	var width	= 785;
	var height	= 554;
	
	if(_allat_user_agent.indexOf("Edge")>-1) {
		width = 787;
		height = 556;
	}
	
	var top		= (screen.availHeight / 2) - (height / 2); 
	var left	= (screen.availWidth / 2) - (width / 2);
	
	_allat_pay_win = window.open("https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_dummy.jsp?allat_shop_domain="+shop_domain, "NONALLATPAYRE_FIX_FRAME", "width="+width+",height="+height+",top="+top+",left="+left+",toolbar=0,menubar=0,status=0,scrollbar=0,resizable=0");

	if( !_allat_pay_win ){
		if(_allat_user_agent.indexOf("Firefox")>-1 || _allat_user_agent.indexOf("Chrome")>-1 || _allat_user_agent.indexOf("Safari")>-1) {
			alert("팝업이 차단되었습니다.\n팝업차단 해제 후 다시 시도해 주시기 바랍니다.");
		}
		return;
	}

	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonhpfix/nonre_hp_fix.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonhpfix/nonre_hp_fix.jsp";
	}

	dfm.method="post";
	dfm.target="NONALLATPAYRE_FIX_FRAME";
	dfm.submit();
}

function Allat_Plus_Hp_Fix_Cancel(dfm) {
	
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
	
	// allat_hp_fix_key 필드 체크
	if( dfm.allat_hp_fix_key == undefined ){
		alert("allat_hp_fix_key이 없습니다.");
		return;
	} else if( f_trim(dfm.allat_hp_fix_key.value) == "" ){
		alert("allat_hp_fix_key의 값이 없습니다.");
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
	document.getElementById("ALLAT_PLUS_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonhpfix/nonre_hp_fix_cancel.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonhpfix/nonre_hp_fix_cancel.jsp";
	}

	dfm.target = "ALLAT_PLUS_FRAME";
	dfm.submit();
}

function Allat_C2C_Fix(dfm, x, y) {
	// allat_enc_data 필드 체크
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

	document.getElementById("ALLAT_PLUS_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonre/nonre_fix_c2c.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_fix_c2c.jsp";
	}

	dfm.target = "ALLAT_PLUS_FRAME";
	dfm.submit();
}

function Allat_C2C_Fix_Cancel(dfm) {
	// allat_enc_data 필드 체크
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
	
	// allat_fix_key 필드 체크
	if( dfm.allat_fix_key == undefined ){
		alert("allat_fix_key가 없습니다.");
		return;
	} else if( f_trim(dfm.allat_fix_key.value) == "" ){
		alert("allat_fix_key의 값이 없습니다.");
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
	document.getElementById("ALLAT_PLUS_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonre/nonre_fix_cancel_c2c.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_fix_cancel_c2c.jsp";
	}

	dfm.target = "ALLAT_PLUS_FRAME";
	dfm.submit();
}

function Allat_Plus_Api(dfm) {
	
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
	
	// shop_receive_url 필드 체크
	if( dfm.shop_receive_url == undefined ){
		alert("shop_receive_url이 없습니다.");
		return;
	} else if( f_trim(dfm.shop_receive_url.value) == "" ){
		alert("shop_receive_url의 값이 없습니다.");
		return;
	}

	document.getElementById("ALLAT_PLUS_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonre/nonre_api.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_api.jsp";
	}

	dfm.target = "ALLAT_PLUS_FRAME";
	dfm.submit();
}

// C2C 취소
function Allat_Plus_Api_C2C(dfm) {
	
	// allat_enc_data 필드 체크
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
	} else if( f_trim(dfm.allat_market_id.value) == "" || f_textLen(dfm.allat_market_id,20) ){
		alert("allat_market_id가 1~20Byte 입니다.");
		return;
	}
	
	// allat_seller_id 필드 체크
	if( dfm.allat_seller_id != undefined ){
		if( f_trim(dfm.allat_seller_id.value) == "" || f_textLen(dfm.allat_seller_id,20) ){
			alert("allat_seller_id가 1~20Byte 입니다.");
			return;
		}
	}
	
	// shop_receive_url 필드 체크
	if( dfm.shop_receive_url == undefined ){
		alert("shop_receive_url이 없습니다.");
		return;
	} else if( f_trim(dfm.shop_receive_url.value) == "" ){
		alert("shop_receive_url의 값이 없습니다.");
		return;
	}

	document.getElementById("ALLAT_PLUS_PAY").style.display = "block";
	
	if( dfm.allat_encode_type != undefined && f_trim(dfm.allat_encode_type.value).toUpperCase() == "U" ){
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPayUtf8/nonactivex/nonre/nonre_api.jsp";
	} else {
		dfm.action = "https://" + _allat_tx_url + "/servlet/AllatPay/nonactivex/nonre/nonre_api.jsp";
	}

	dfm.target = "ALLAT_PLUS_FRAME";
	dfm.submit();
}

function Allat_Plus_Close(){
	/* div 설정 */
	document.getElementById("ALLAT_PLUS_PAY").style.display = "none";
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