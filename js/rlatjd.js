// 날짜 확인
function check_date(date) {
    /*if($('#rday').val() != "") {
        if($('#rday').val().replace("-", "").replace("-", "") < date) {
            alert('현재보다 이전시간을 선택하였습니다.\n발송불가하니 재설정하시기 바랍니다');
            $('#rday').val('');
        } 
    } */   
}
//로그아웃
function logout(type = "selling") {
	if (confirm('로그아웃하시겠습니까?')) {
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				logout_go: type
			},
			success: function (data) {
				try {
					window.AppScript.setLogout();
				} catch (e) {}
				ajax_state = 1;
				$("#ajax_div").html(data);
			}
		});
	}
}
//로그인체크
function login_check(frm) {
	if (!wrestSubmit(frm)) {
		return false;
	}
	return true;
}
//아이디 중복확인
function id_check(frm, frm_str) {
	if (!frm.id.value) {
		frm.id.focus();
		return;
	}
	if (frm.id.value.length < 4) {
		alert('아이디는 4자 이상 사용이 가능합니다.')
		frm.id.focus();
		return;

	}
	var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
	if (!pattern.test(frm.id.value)) {
		document.getElementById('id_html').innerHTML = '올바른 회원아이디 형식이 아닙니다.';
		frm.id_status.value = '';
		frm.id.value = '';
		frm.id.focus();
		return;
	} else {
		document.getElementById('id_html').innerHTML = '';
	}
	$.ajax({
		type: "POST",
		url: "/ajax/ajax.php",
		data: {
			id_che: frm.id.value,
			id_che_form: frm_str,
			solution_type: frm.site.value,
			solution_name: frm.site_name.value
		},
		success: function (data) {
			$("#ajax_div").html(data);
		}
	});
}
//닉네임 중복확인
function nick_check(frm, frm_str) {
	if (!frm.nick.value) {
		frm.nick.focus();
		return;
	}
	var pattern = /[가-힣\x200-9]/ig;
	//var pattern = /[^\u4e00-\u9fa5]/i;
	if (!pattern.test(frm.nick.value)) {
		document.getElementById('nick_html').innerHTML = '한글 혹은 숫자만 입력가능합니다.';
		frm.nick_status.value = '';
		frm.nick.value = '';
		frm.nick.focus();
		return;
	} else {
		if (frm.nick.value.length > 8) {
			document.getElementById('nick_html').innerHTML = '8자 이내로만 입력가능합니다.';
			frm.nick_status.value = '';
			frm.nick.value = '';
			frm.nick.focus();
			return;
		}
		$.ajax({
			type: "POST",
			url: "ajax/ajax.php",
			data: {
				nick_che: frm.nick.value,
				nick_che_form: frm_str
			},
			success: function (data) {
				$("#ajax_div").html(data)
			}
		});
	}
}
//회원가입체크
function join_check(frm, modify) {
	//console.log("==================1");
	if (!wrestSubmit(frm)) {
		return false;
	}
	//console.log("==================2");
	/*if($('#id').val() == $('#recommend_id').val())
	{
		alert('자신의 아이디는 추천에 입력되지 않습니다.');
		return;
	}

	if($('#is_exist_recommender').length != 0 &&  $('#is_exist_recommender').val() == "N" )
	{
		alert('추천인을 정확히 입력해 주세요.');
		return;			
	}*/

	var id_str = "";
	var app_pwd = "";
	var web_pwd = "";
	var phone_str = "";
	var site_type = frm.site.value;
	var site_name = frm.site_name.value;
	if (document.getElementsByName('pwd')[0]) {
		app_pwd = document.getElementsByName('pwd')[0].value;
	}
	if (document.getElementsByName('pwd')[1]) {
		web_pwd = document.getElementsByName('pwd')[1].value;
	}
	if (frm.id) {
		id_str = frm.id.value;
	}
	var msg = modify ? "수정하시겠습니까?" : "등록하시겠습니까?";
	if (!modify) {
		phone_str = frm.mobile_1.value + "-" + frm.mobile_2.value + "-" + frm.mobile_3.value;
		if ($('#code') == 'KR') {
			if ($('#rnum').val() === "") {
				alert('인증번호를 입력해주세요.');
				return;
			}

			if ($('#check_rnum').val() != "Y") {
				alert('인증번호 확인해주세요.');
				return;
			}
		}
	}
	var birth_str = frm.birth_1.value + "-" + frm.birth_2.value + "-" + frm.birth_3.value;
	var is_message_str = frm.is_message.checked ? "Y" : "N";
	var recommend_id = "";
	var bank_name = "";
	var bank_account = "";
	var bank_owner = "";
	var recommend_branch = "";
	var mem_name = "";
	var mem_addr = "";
	var mem_zy = "";
	var email_str = "";
	try {
		bank_name = frm.bank_name.value;
		bank_account = frm.bank_account.value;
		bank_owner = frm.bank_owner.value;
	} catch (e) {}

	try {
		recommend_id = frm.recommend_id.value;
	} catch (e) {}

	try {
		recommend_branch = frm.recommend_branch.value;
	} catch (e) {}
	try {
		email_str = frm.email_1.value + "@" + frm.email_2.value + frm.email_3.value;
	} catch (e) {}
	try {
		mem_name = frm.name.value;
	} catch (e) {}
	try {
		mem_addr = frm.add1.value;
	} catch (e) {}
	try {
		mem_zy = frm.zy.value;
	} catch (e) {}
	if (confirm(msg)) {
		$.ajax({
			type: "POST",
			url: "ajax/ajax.php",
			data: {
				join_id: id_str,
				join_nick: "join",
				join_pwd: app_pwd,
				join_web_pwd: web_pwd,
				join_name: mem_name,
				join_email: email_str,
				join_phone: phone_str,
				join_add1: mem_addr,
				join_zy: mem_zy,
				join_birth: birth_str,
				join_is_message: is_message_str,
				join_modify: modify,
				bank_name: bank_name,
				bank_account: bank_account,
				bank_owner: bank_owner,
				recommend_id: recommend_id,
				recommend_branch: recommend_branch,
				solution_type: site_type,
				solution_name: site_name,
				rnum: $('#rnum').val(),
				country_code: $('#code').val()
			},
			success: function (data) {
				$("#ajax_div").html(data);
			}
		});
	}
}
//비밀번호 보안등급
function pwd_check(i) {
	var iss = {
		color: ["#999", "#00F", "#F00", "#000"],
		text: ["약 <img src='/images/check.gif' />", "중 <img src='/images/check.gif' />", "강 <img src='/images/check.gif' />", "검사중..", "비밀번호는 6~15 자내로 입력해야 합니다."],
		width: ["50", "100", "150", "10"],
		reset: function () {
			$($(".pwd_html")[i]).html(iss.text[3]);
			document.getElementsByName('pwd_status')[i].value = '';
		},
		level0: function () {
			$($(".pwd_html")[i]).css("color", iss.color[2]);
			$($(".pwd_html")[i]).html(iss.text[0]);
			document.getElementsByName('pwd_status')[i].value = 'ok';
		},
		level1: function () {

			$($(".pwd_html")[i]).css("color", iss.color[2]);
			$($(".pwd_html")[i]).html(iss.text[1]);
			document.getElementsByName('pwd_status')[i].value = 'ok';
		},
		level2: function () {
			$($(".pwd_html")[i]).css("color", iss.color[2]);
			$($(".pwd_html")[i]).html(iss.text[2]);
			document.getElementsByName('pwd_status')[i].value = 'ok';
		},
		level3: function () {
			$($(".pwd_html")[i]).css("color", iss.color[2]);
			$($(".pwd_html")[i]).html(iss.text[3]);
			document.getElementsByName('pwd_status')[i].value = '';
			document.getElementsByName('pwd')[i].value = '';
			document.getElementsByName('pwd')[i].focus();
		},
		level4: function () {
			$($(".pwd_html")[i]).css("color", iss.color[2]);
			$($(".pwd_html")[i]).html(iss.text[4]);
			document.getElementsByName('pwd_status')[i].value = '';
		}
	}
	var regexp = /^[\S]*$/;
	if (regexp.test(document.getElementsByName('pwd')[i].value)) {
		if (document.getElementsByName('pwd')[i].value.length < 6 || document.getElementsByName('pwd')[i].value.length > 15) {
			iss.level4();
			return
		}
		var lv = -1;
		if (document.getElementsByName('pwd')[i].value.match(/[a-z]/ig)) {
			lv++;
		}
		if (document.getElementsByName('pwd')[i].value.match(/[0-9]/ig)) {
			lv++;
		}
		if (document.getElementsByName('pwd')[i].value.match(/([!@#$%^&*()_+])/ig)) {
			lv++;
		}
		switch (lv) {
			case 0:
				iss.level0();
				break;
			case 1:
				iss.level1();
				break;
			case 2:
				iss.level2();
				break;
			default:
				iss.reset();
				break;
		}
	} else {
		iss.level3();
	}
}
//비밀번호 재확인
function pwd_cfm_check(i) {
	console.log(document.getElementsByName('pwd_cfm')[i].value + "^" + document.getElementsByName('pwd')[i].value)
	if (document.getElementsByName('pwd_cfm')[i].value != document.getElementsByName('pwd')[i].value) {
		$($(".pwd_cfm_html")[i]).html("두번 입력한 비밀번호가 틀립니다.");
		document.getElementsByName('pwd_status')[i].value = '';
		//document.getElementsByName('pwd_cfm')[i].focus();
		return;
	} else {
		$($(".pwd_cfm_html")[i]).html("<img src='/images/check.gif' />");
	}
}
//번호 체크삭제
function num_del() {
	var name_arr = document.getElementsByName('seq[]');
	var num_a = [];
	for (var i = 0; i < name_arr.length; i++) {
		if (name_arr[i].checked)
			num_a.push(name_arr[i].value);
	}
	if (!num_a.length) {
		alert('적어도 하나는 선택해야 합니다.');
		return;
	}
	if (confirm('삭제하시겠습니까?')) {
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				num_del_num_a: num_a
			},
			success: function (data) {
				$("#ajax_div").html(data)
			}
		})
	}
}
//전송안된문자 삭제
function no_msg_del() {
	if (confirm('전송 안 된 문자를 삭제하시겠습니까?')) {
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				no_msg_del_ok: "ok"
			},
			success: function (data) {
				$("#ajax_div").html(data)
			}
		})
	}
}

// 발송순서 저장
function save_sort() {
	var kk = 1;
	var sort_no = "";
	var sendnum = "";
	$('.sendnum').each(function () {
		//html += $(this).parent().find("input[name='sort_no']").val()+"==="+$(this).text()+"\n";

		if (kk == 1) {
			sort_no = $(this).parent().find("input[name='sort_no']").val();
			sendnum = $(this).text();
		} else {
			sort_no += "," + $(this).parent().find("input[name='sort_no']").val();
			sendnum += "," + $(this).text();
		}
		kk++;
	});
	//alert(html);

	if (confirm('발송순서를 저장하시겠습니까?')) {
		$.ajax({
			type: "POST",
			url: "ajax/ajax_save.php",
			data: {
				mode: "save_sort",
				sendnum: sendnum,
				sort_no: sort_no

			},
			success: function (data) {
				$("#ajax_div").html(data)
			}
		})
	}
}

//그룹생성고
function group_create() {
	var num_s = "";
	var name_arr = document.getElementsByName('seq[]');
	var num_a = [];
	for (var i = 0; i < name_arr.length; i++) {
		if (name_arr[i].checked)
			num_a.push("'" + name_arr[i].value + "'");
	}
	num_s = num_a.join(",");
	if (num_s == "") {
		alert('적어도 하나는 선택해야 합니다.');
		return;
	}
	$($(".loading_div")[0]).show();
	$.ajax({
		type: "POST",
		url: "/ajax/ajax_session.php",
		data: {
			group_create_go: "ok",
			group_create_nums: num_s
		},
		success: function (data) {
			$($(".loading_div")[0]).hide();
			$("#ajax_div").html(data)
		}
	})
}
//그룹생성오케이
function group_create_ok(frm) {
	if (!wrestSubmit(frm))
		return false;
	if (confirm('생성하겠습니까?')) {
		$($(".loading_div")[0]).show();
		close_div(open_group_create);
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				group_create_ok: "ok",
				group_create_ok_nums: group_create_form.seq.value,
				group_create_ok_name: group_create_form.g_name.value
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//메시지발송
function send_msg(frm) {
	if (!document.getElementsByName('group_num')[0].value && !document.getElementsByName('num')[0].value) {
		alert('발송번호가 없습니다.');
		return false;
	}
	var go_num_name_arr = document.getElementsByName('go_num');
	var go_num_arr = [];
	var go_user_cnt_name_arr = document.getElementsByName('go_user_cnt');
	var go_user_cnt_arr = [];
	var go_max_cnt_name_arr = document.getElementsByName('go_max_cnt');
	var go_max_cnt_arr = [];
	var go_memo2_name_arr = document.getElementsByName('go_memo2');
	var go_memo2_arr = [];
	var go_cnt1_name_arr = document.getElementsByName('go_cnt1');
	var go_cnt1_arr = [];
	var go_cnt2_name_arr = document.getElementsByName('go_cnt2');
	var go_cnt2_arr = [];
	for (var i = 0; i < go_num_name_arr.length; i++) {
		if (go_num_name_arr[i].checked) {
			go_num_arr.push(go_num_name_arr[i].value);
			go_user_cnt_arr.push(go_user_cnt_name_arr[i].value);
			go_max_cnt_arr.push(go_max_cnt_name_arr[i].value);
			go_memo2_arr.push(go_memo2_name_arr[i].value);
			go_cnt1_arr.push(go_cnt1_name_arr[i].value);
			go_cnt2_arr.push(go_cnt2_name_arr[i].value);
		}
	}
	if (!go_num_arr.length) {
		alert('발송가능한 휴대폰을 선택해주세요.')
		go_num_name_arr[0].focus();
		return
	}
	var type_s = "";
	var type_arr = document.getElementsByName('type');
	for (var i = 0; i < type_arr.length; i++) {
		if (type_arr[i].checked)
			type_s = type_arr[i].value;
	}
	if (type_s == "") {
		alert('발송종류 선택해주세요.');
		document.getElementsByName('type')[0].focus();
		return false;
	}
	var save_mms_s = "";
	if (frm.save_mms.checked)
		save_mms_s = "ok";
	var deny_wushi_0_s = "";
	var deny_wushi_1_s = "";
	var deny_wushi_2_s = "";
	var deny_wushi_3_s = "";
	var deny_msg_s = "";
	var ssh_check_s = "";
	var ssh_check2_s = "";
	if (document.getElementsByName('deny_wushi[]')[0].checked)
		deny_wushi_0_s = "ok";
	if (document.getElementsByName('deny_wushi[]')[1].checked)
		deny_wushi_1_s = "ok";
	if (document.getElementsByName('deny_wushi[]')[2].checked)
		deny_wushi_2_s = "ok";
	if (document.getElementsByName('deny_wushi[]')[3].checked)
		deny_wushi_3_s = "ok";
	if (document.getElementsByName('deny_msg')[0].checked)
		deny_msg_s = "ok";
	if (document.getElementsByName('ssh_check')[0].checked)
		ssh_check_s = "ok";
	if (document.getElementsByName('ssh_check')[1].checked)
		ssh_check2_s = "ok";
	if (document.getElementsByName('ssh_check')[2].checked)
		ssh_check3_s = "ok";
	var txt_s = "";
	if (document.getElementsByName('fs_msg')[0].checked)
		txt_s = frm.txt.value + document.getElementsByName('onebook_url')[0].value + "\n\n" + document.getElementsByName('fs_txt')[0].value;
	else
		txt_s = frm.txt.value + document.getElementsByName('onebook_url')[0].value;
	if (!wrestSubmit(frm))
		return false;

	if (confirm("발송하시겠습니까?")) {
		$($(".loading_div")[0]).show();
		close_div(open_group_create);
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				send_title: frm.title.value,
				send_num: document.getElementsByName('num')[0].value,
				send_txt: txt_s,
				send_rday: frm.rday.value,
				send_htime: frm.htime.value,
				send_mtime: frm.mtime.value,
				send_type: type_s,
				send_chk: document.getElementsByName('group_num')[0].value,
				send_img: frm.upimage_str.value,
				send_save_mms: save_mms_s,
				send_deny_wushi_0: deny_wushi_0_s,
				send_deny_wushi_1: deny_wushi_1_s,
				send_deny_wushi_2: deny_wushi_2_s,
				send_deny_wushi_3: deny_wushi_3_s,
				send_deny_msg: deny_msg_s,
				send_ssh_check: ssh_check_s,
				send_ssh_check2: ssh_check2_s,
				send_ssh_check3: ssh_check3_s,
				send_delay: frm.delay.value,
				send_delay2: frm.delay2.value,
				send_close: frm.close.value,
				send_onebook_status: frm.onebook_status.value,
				send_go_num: go_num_arr,
				send_go_user_cnt: go_user_cnt_arr,
				send_go_max_cnt: go_max_cnt_arr,
				send_go_memo2: go_memo2_arr,
				send_go_cnt1: go_cnt1_arr,
				send_go_cnt2: go_cnt2_arr
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				var arrData = data.split('|');
				//parent.alert('발송시도: ' + arrData[0] + '\n\n발송시도실패: ' + arrData[1] + '\n\n전송성공건수: ' + arrData[2] + '\n\n전송실패건수(수신처제한): ' + arrData[3]);
				var msg = "";
				msg += '발송시도: ' + arrData[0];
				msg += '\n\n발송시도실패: ' + arrData[1];
				msg += '\n\n전송성공건수: ' + arrData[2];
				msg += '\n\n전송실패건수(수신처제한): ' + arrData[3];
				msg += '\n\n전송실패건수(수신불가): ' + arrData[4];
				msg += '\n\n전송실패건수(기타번호): ' + arrData[5];
				msg += '\n\n전송실패건수(없는번호): ' + arrData[6];
				msg += '\n\n전송실패건수(수신불가번호): ' + arrData[7];
				parent.alert(msg);
				parent.location.reload();
			}
		})
	}
}
//디테일 박스선택
function group_choice(g,t, start, end) {
	var name_arr = document.getElementsByName('chk');
	var group_arr = [];
	if (document.getElementsByName('group_num')[0].value)
		group_arr = document.getElementsByName('group_num')[0].value.split(",");
	if (g) {
		if (name_arr[g].checked) {
			if (jQuery.inArray(name_arr[g].value, group_arr) == -1) {
				if(t == "Y"){
					group_arr.push(name_arr[g].value + "(" + start + "-" + end + ")");
				}
				else{
					group_arr.push(name_arr[g].value);
				}
                $.ajax({
                    type:"POST",
                    url:"/ajax/sendmmsPrc.2020.php",
                    data: {
                        method : "check_recv_name",
                        group_arr : name_arr[g].value
                    },
                    dataType:"json",
                    success:function(data){
                        if(data.result != "success"){
                            alert(data.result + "그룹에 이름이 없는 회원이 있습니다.엑셀디비에 이름이 기입되어 있어야 치환기능이 됩니다");
                        }
                    },
                    error: function(){
                    }
                });
            }
		} else {
			for (var a = 0; a < group_arr.length; a++) {
				if(group_arr[a].indexOf(name_arr[g].value) != -1)
					group_arr.splice(a, 1);
			}
		}
	} else {
		for (var i = 0; i < name_arr.length; i++) {
			if (name_arr[i].checked) {
				if (jQuery.inArray(name_arr[i].value, group_arr) == -1)
					group_arr.push(name_arr[i].value);
			} else {
				for (var a = 0; a < group_arr.length; a++) {
					if (name_arr[i].value == group_arr[a])
						group_arr.splice(a, 1);
				}
			}
		}
        $.ajax({
            type:"POST",
            url:"/ajax/sendmmsPrc.2020.php",
            data: {
                method : "check_recv_name",
                group_arr : group_arr.toString()
            },
            dataType:"json",
            success:function(data){
                if(data.result != "success"){
                    alert(data.result + "그룹에 이름이 없는 회원이 있습니다.엑셀디비에 이름이 기입되어 있어야 치환기능이 됩니다");
                }
            },
            error: function(){

            }
        });
	}
    document.getElementsByName('group_num')[0].value = group_arr.join(",");
    document.getElementsByName('group_num')[0].focus();
}
//디테일 박스선택
function box_ed(g,name) {
	var name_arr = document.getElementsByName('name_box');
	var group_arr = [];
	if (window.parent.document.getElementsByName('num')[0].value)
		group_arr = window.parent.document.getElementsByName('num')[0].value.split(",");

	if (g  >= 0) {
		if (name_arr[g].checked) {
			if( name == '') {
                window.parent.parent_alert("엑셀디비에 이름이 기입되어 있어야 치환기능이 됩니다.");
            }
			if (jQuery.inArray(name_arr[g].value, group_arr) == -1)
				group_arr.push(name_arr[g].value);
		} else {
			for (var a = 0; a < group_arr.length; a++) {
				if (name_arr[g].value == group_arr[a])
					group_arr.splice(a, 1);
			}
		}
	} else {
		for (var i = 0; i < name_arr.length; i++) {
			if (name_arr[i].checked) {
				if (jQuery.inArray(name_arr[i].value, group_arr) == -1)
					group_arr.push(name_arr[i].value);
			} else {
				for (var a = 0; a < group_arr.length; a++) {
					if (name_arr[i].value == group_arr[a])
						group_arr.splice(a, 1);
				}
			}
		}
		if(name_arr[0].checked) {
            $.ajax({
                type: "POST",
                url: "/ajax/sendmmsPrc.2020.php",
                data: {
                    method: "check_recv_name",
                    group_arr: name
                },
                dataType: "json",
                success: function (data) {
                    if (data.result != "success") {
                        window.parent.alert(data.result + "그룹에 이름이 없는 회원이 있습니다.엑셀디비에 이름이 기입되어 있어야 치환기능이 됩니다");
                    }
                },
                error: function () {
                }
            });
        }
	}
	window.parent.document.getElementsByName('num')[0].value = group_arr.join(",");
	window.parent.document.getElementsByName('num')[0].focus();
}
//디테일 보이기
function show_detail(src, i) {
	open_div(open_pop_div, 70, 420);
	$($(".group_title_open")[0]).html(document.getElementsByName('group_title')[i].value);
	$("#pop_iframe").attr("src", src);
}
//그룹명수정
function group_title_modify(idx, i) {
	if (!document.getElementsByName('group_title')[i].value) {
		document.getElementsByName('group_title')[i].focus();
		return false;
	}
	$($(".loading_div")[0]).show();
	$.ajax({
		type: "POST",
		url: "/ajax/ajax_session.php",
		data: {
			group_modify_idx: idx,
			group_modify_title: document.getElementsByName('group_title')[i].value
		},
		success: function (data) {
			$($(".loading_div")[0]).hide();
			$("#ajax_div").html(data)
		}
	})
}
//디테일 수정 보이기
function g_dt_show_cencle(c1, c2, c3, i, c4,c5) {
	if (c1) {
		$($("." + c1 + i)[0]).hide();
		$($("." + c1 + i)[1]).show();
	}
	if (c2) {
		$($("." + c2 + i)[0]).hide();
		$($("." + c2 + i)[1]).show();
	}
	if (c3) {
		$($("." + c3 + i)[0]).hide();
		$($("." + c3 + i)[1]).show();
	}
	if (c4) {
		$($("." + c4 + i)[0]).hide();
		$($("." + c4 + i)[1]).show();
	}
	if (c5) {
		$($("." + c5 + i)[0]).hide();
		$($("." + c5 + i)[1]).show();
	}
}
//디테일 수정 감추기
function g_dt_cencle(c1, c2, c3, i) {
	if (c1) {
		$($("." + c1 + i)[1]).hide();
		$($("." + c1 + i)[0]).show();
	}
	if (c2) {
		$($("." + c2 + i)[1]).hide();
		$($("." + c2 + i)[0]).show();
	}
	if (c3) {
		$($("." + c3 + i)[1]).hide();
		$($("." + c3 + i)[0]).show();
	}
}
//id_pw
function show_cencle(c1, i1, i2, c2, c3, type_i, type) {
	$($("." + c1)[i1]).show();
	$($("." + c1)[i2]).hide();
	$("." + c2).attr("required", "");
	$("." + c3).removeAttr("required");
	document.getElementsByName('serch_type')[type_i].value = type;
}
//아이디패스워드
function search_id_pw(frm) {
	if (!wrestSubmit(frm))
		return false;
	var mem_name_s = "";
	var mem_id_s = "";
	var phone_s = "";
	var email_s = "";
	if (frm.mem_name)
		mem_name_s = frm.mem_name.value;
	if (frm.mem_id)
		mem_id_s = frm.mem_id.value;
	if (frm.serch_type.value == "phone") {
		phone_s = frm.mobile_1.value + "-" + frm.mobile_2.value + "-" + frm.mobile_3.value;
		email_s = "";
	} else if (frm.serch_type.value == "email") {
		phone_s = "";
		if (frm.email_3.value)
			email_s = frm.email_1.value + "@" + frm.email_3.value;
		else
			email_s = frm.email_1.value + "@" + frm.email_2.value;
	}
	if (confirm("찾으시겠습니까?")) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "ajax/ajax.php",
			data: {
				search_id_pw_mem_name: mem_name_s,
				search_id_pw_mem_id: mem_id_s,
				search_id_pw_phone: phone_s,
				search_id_pw_email: email_s,
				search_id_pw_type: frm.search_type.value
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//디테일 수정 삭제 추가
function g_dt_fun(status, g_id, idx, cnt) {
	if (status != "del") {
		if (!document.getElementsByName('num_name')[cnt].value) {
			document.getElementsByName('num_name')[cnt].focus();
			return false
		}
		if (!document.getElementsByName('recv_num')[cnt].value) {
			document.getElementsByName('recv_num')[cnt].focus();
			return false
		}
	}
	switch (status) {
		case "modify":
			var msg = "수정하시겠습니까?";
			break;
		case "del":
			var msg = "삭제하시겠습니까?";
			break;
		case "add":
			var msg = "새로추가 하시겠습니까?";
			break;
	}
	if (confirm(msg)) {
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				g_dt_status: status,
				g_dt_g_id: g_id,
				g_dt_idx: idx,
				g_dt_grp_2: document.getElementsByName('xiao_grp')[cnt].value,
				g_dt_name: document.getElementsByName('num_name')[cnt].value,
				g_dt_num: document.getElementsByName('recv_num')[cnt].value,
				g_dt_email: document.getElementsByName('email')[cnt].value
			},
			success: function (data) {
				$("#ajax_div").html(data)
			}
		})
	}
}
//수신거부
function numchk(status) {
	var box_status = "";
	if (status != "4" && status != "5") {
		if (!document.getElementsByName('group_num')[0].value && !document.getElementsByName('num')[0].value) {
			alert('선택된 번호가 없습니다.');
			document.getElementsByName('deny_wushi[]')[status].checked = false;
			return false;
		}
	}
	var go_num_arr = document.getElementsByName('go_num');
	var send_num_a = [];
	var send_num_s = "";
	for (var i = 0; i < go_num_arr.length; i++) {
		if (go_num_arr[i].checked)
			send_num_a.push("`" + go_num_arr[i].value + "`");
	}
	send_num_s = send_num_a.join(",");
	if (status == "5") {
		if (send_num_s == "") {
			alert('발송가능 휴대폰을 선택해주세요.');
			go_num_arr[0].focus();
			document.getElementsByName('ssh_check')[1].checked = false;
			$($('.deny_msg_span')[2]).html('OFF');
			$($('.deny_msg_span')[2]).css('color', '#F00');
			$($(".num_check_c")[8]).html('0');
			return false;
		}
	}
	if (document.getElementsByName('group_num')[0].value || document.getElementsByName('num')[0].value) {

		//2016-05-08 추가
		var deny_wushi_0_s = "";
		var deny_wushi_1_s = "";
		var deny_wushi_2_s = "";
		var deny_wushi_3_s = "";
		var deny_msg_s = "";
		var ssh_check_s = "";
		var ssh_check2_s = "";
		var ssh_check3_s = "";
		var go_num_name_arr = document.getElementsByName('go_num');
		var go_num_arr = [];
		var go_user_cnt_name_arr = document.getElementsByName('go_user_cnt');
		var go_user_cnt_arr = [];
		var go_max_cnt_name_arr = document.getElementsByName('go_max_cnt');
		var go_max_cnt_arr = [];
		var go_memo2_name_arr = document.getElementsByName('go_memo2');
		var go_memo2_arr = [];
		var go_cnt1_name_arr = document.getElementsByName('go_cnt1');
		var go_cnt1_arr = [];
		var go_cnt2_name_arr = document.getElementsByName('go_cnt2');
		var go_cnt2_arr = [];
		var go_remain_name_arr = document.getElementsByName('go_remain_cnt');
		var go_remain_arr = [];
		for (var i = 0; i < go_num_name_arr.length; i++) {
			if (go_num_name_arr[i].checked) {
				go_num_arr.push(go_num_name_arr[i].value);
				go_user_cnt_arr.push(go_user_cnt_name_arr[i].value);
				go_max_cnt_arr.push(go_max_cnt_name_arr[i].value);
				go_memo2_arr.push(go_memo2_name_arr[i].value);
				go_cnt1_arr.push(go_cnt1_name_arr[i].value);
				go_cnt2_arr.push(go_cnt2_name_arr[i].value);
				go_remain_arr.push(go_remain_name_arr[i].value);
			}
		}

		if (document.getElementsByName('deny_wushi[]')[0].checked)
			deny_wushi_0_s = "ok";
		if (document.getElementsByName('deny_wushi[]')[1].checked)
			deny_wushi_1_s = "ok";
		if (document.getElementsByName('deny_wushi[]')[2].checked)
			deny_wushi_2_s = "ok";
		if (document.getElementsByName('deny_wushi[]')[3].checked)
			deny_wushi_3_s = "ok";
		if (document.getElementsByName('deny_msg')[0].checked)
			deny_msg_s = "ok";
		if (document.getElementsByName('ssh_check')[0].checked)
			ssh_check_s = "ok";
		if (document.getElementsByName('ssh_check')[1].checked)
			ssh_check2_s = "ok";
		if (document.getElementsByName('ssh_check')[2].checked)
			ssh_check3_s = "ok";

		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				send_rday: sub_4_form.rday.value,
				send_htime: sub_4_form.htime.value,
				send_mtime: sub_4_form.mtime.value,
				num_check_grp_id: document.getElementsByName('group_num')[0].value,
				num_check_num2: document.getElementsByName('num')[0].value,
				num_check_send_num: send_num_s,
				num_check_status: status,
				num_check_go: "ok",
				send_deny_wushi_0: deny_wushi_0_s,
				send_deny_wushi_1: deny_wushi_1_s,
				send_deny_wushi_2: deny_wushi_2_s,
				send_deny_wushi_3: deny_wushi_3_s,
				send_ssh_check: ssh_check_s,
				send_ssh_check2: ssh_check2_s,
				send_ssh_check3: ssh_check3_s,
				send_go_user_cnt: go_user_cnt_arr,
				send_go_memo2: go_memo2_arr

			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//실제발송
function send_sj_fun() {
	var go_num_arr = document.getElementsByName('go_num');
	var go_user_cnt_arr = document.getElementsByName('go_user_cnt');
	var sj_send_cnt = 0;
	for (var i = 0; i < go_num_arr.length; i++) {
		if (go_num_arr[i].checked)
			sj_send_cnt += parseInt(go_user_cnt_arr[i].value);
	}
	$($(".send_sj_c")[0]).html(number_format(sj_send_cnt));
	if (document.getElementsByName('ssh_check')[1].checked)
		numchk('5');
}
//all 그룹삭제
function all_group_del() {
	var checkboxValues = [];
	$("input[id='chk']:checked").each(function (i) {
		checkboxValues.push($(this).val());
	});
	if (!checkboxValues.length) {
		alert('그룹명을 선택해주세요.')
		return false;
	}
	if (confirm('삭제하시겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				all_group_chk: checkboxValues
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//엑셀인썰트
function excel_insert(frm, status) {
	if (status == "old") {
		if (!frm.excel_file.value) {
			alert('업로드할 파일을 선택해주세요.')
			return false;
		}
		var checkboxValues = [];
		$("input[id='chk']:checked").each(function (i) {
			checkboxValues.push($(this).val());
		});
		if (!checkboxValues.length) {
			alert('그룹명을 선택해주세요.')
			return false;
		}
		frm.old_group.value = checkboxValues.join(",");
	} else if (status == "new") {
		if (!frm.new_group.value) {
			alert('새로운 그룹명을 입력해주세요');
			frm.new_group.focus();
			return false;
		}
		if (!frm.excel_file.value) {
			alert('업로드할 파일을 선택해주세요.')
			return false;
		}
	}
	frm.target = 'excel_iframe';
	frm.action = "insert_excel.php?status=" + status;
	frm.submit();
}
//이미지 미리보기
function insertImage(frm) {
	var str = "";
	if (!frm.upimage.value.match(/\.(gif|jpg|png)$/i)) {
		alert("이미지파일을 첨부 해주세요!");
		return;
	}
	f.submit();
}

function onUploadImgChange(sender) {
	document.getElementById('preview_size_fake').style.display = '';
	if (!sender.value.match(/.jpg|.gif|.png|.bmp/i)) {
		alert('옳바른 이미지 파일이 아닙니다.');
		return false;
	}
	if (sender.files && sender.files[0]) {
		document.getElementById('preview').style.display = 'block';
		document.getElementById('preview').style.width = 'auto';
		document.getElementById('preview').style.height = 'auto';
		document.getElementById('preview').src = window.URL.createObjectURL(sender.files[0])
	} else if (document.getElementById('preview_fake').filters) {
		sender.select();
		document.getElementById('preview_fake').filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = document.selection.createRange().text;
		document.getElementById('preview_size_fake').filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = document.selection.createRange().text;
		autoSizePreview(document.getElementById('preview_fake'), document.getElementById('preview_size_fake').offsetWidth, document.getElementById('preview_size_fake').offsetHeight);
		document.getElementById('preview').style.display = 'none';
	}
}

function onPreviewLoad(sender) {
	autoSizePreview(sender, sender.offsetWidth, sender.offsetHeight);
}

function autoSizePreview(objPre, originalWidth, originalHeight) {
	var zoomParam = clacImgZoomParam(250, 150, originalWidth, originalHeight);
	objPre.style.width = zoomParam.width + 'px';
	objPre.style.height = zoomParam.height + 'px';
	objPre.style.marginTop = zoomParam.top + 'px';
	objPre.style.marginLeft = zoomParam.left + 'px';
	document.getElementById('preview_size_fake').style.display = 'none';
}

function clacImgZoomParam(maxWidth, maxHeight, width, height) {
	var param = {
		width: width,
		height: height,
		top: 0,
		left: 0
	};
	if (width > maxWidth || height > maxHeight) {
		rateWidth = width / maxWidth;
		rateHeight = height / maxHeight;
		if (rateWidth > rateHeight) {
			param.width = maxWidth;
			param.height = height / rateWidth;
		} else {
			param.width = width / rateHeight;
			param.height = maxHeight;
		}
	}
	//param.left = (maxWidth - param.width) / 2;  
	//param.top = (maxHeight - param.height) / 2;  
	return param;
}
//lms저장
function lms_save(frm, i, status, seq_num, idx) {
	var title_s = "";
	var lms_content_s = "";
	var upimage_str_s = "";
	if (!document.getElementsByName('title')[i].value) {
		document.getElementsByName('title')[i].focus();
		return false
	}
	title_s = document.getElementsByName('title')[i].value
	if (document.getElementsByName('lms_content')[i]) {
		if (!document.getElementsByName('lms_content')[i].value) {
			document.getElementsByName('lms_content')[i].focus();
			return false
		}
		lms_content_s = document.getElementsByName('lms_content')[i].value;
	}
	if (document.getElementsByName('upimage_str')[i]) {
		if (!document.getElementsByName('upimage_str')[i].value) {
			alert('업로드할 파일을 선택하세요.');
			return false
		}
		upimage_str_s = document.getElementsByName('upimage_str')[i].value;
	}
	if (status == "add") {
		var msg = "새로 등록하시겠습니까?";
	} else if (status == "modify") {
		var msg = "수정하시겠습니까?";
	}
	if (confirm(msg)) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				lms_save_title: title_s,
				lms_save_content: lms_content_s,
				lms_save_img: upimage_str_s,
				lms_save_status: status,
				lms_save_seq_num: seq_num,
				lms_save_idx: idx
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//lms삭제
function lms_del(idx) {
	if (confirm('삭제하시겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				lms_del_idx: idx
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//등록관리 설정저장
function set_save() {
	var name_arr = document.getElementsByName('seq[]');
	var num_a = [];

	var memo_arr = document.getElementsByName('memo');
	var memo_a = [];
	var memo2_arr = document.getElementsByName('memo2');
	var memo2_a = [];
	var max_cnt_arr = document.getElementsByName('max_cnt');
	var max_cnt_a = [];
	var gl_cnt_arr = document.getElementsByName('gl_cnt');
	var gl_cnt_a = [];
	var user_cnt_arr = document.getElementsByName('user_cnt');
	var user_cnt_a = [];
	for (var i = 0; i < name_arr.length; i++) {
		if (name_arr[i].checked) {
			num_a.push(name_arr[i].value);
			memo_a.push(memo_arr[i].value);
			if (!memo2_arr[i].value) {
				alert('통신사 선택해주세요.');
				memo2_arr[i].focus();
				return;
			}
			memo2_a.push(memo2_arr[i].value);
			max_cnt_a.push(max_cnt_arr[i].value);
			gl_cnt_a.push(gl_cnt_arr[i].value);
			user_cnt_a.push(user_cnt_arr[i].value);
		}
	}
	if (!num_a.length) {
		alert('적어도 하나는 선택해야 합니다.');
		return;
	}
	if (confirm('저장하겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				set_num: num_a,
				set_memo: memo_a,
				set_memo2: memo2_a,
				set_max_cnt: max_cnt_a,
				set_gl_cnt: gl_cnt_a,
				set_user_cnt: user_cnt_a
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//통신사선택
function ssc_show(i, v) {
	if (!v)
		return;
	switch (v) {
		case "SK":
			var age_str = "800";
			break;
		case "KT":
			var age_str = "2800";
			break;
		case "LG":
			var age_str = "2800";
			break;
	}
	$($(".agency_c")[i]).html(age_str);
}
//문자체크
function textCounter(theField, theCharCounter, maxChars, b) {
	var strCharCounter = 0;
	var intLength = theField.value.length;
	for (var i = 0; i < intLength; i++) {
		var charCode = theField.value.charCodeAt(i);
		if (charCode > 128)
			strCharCounter += 2;
		else
			strCharCounter++;

		if (strCharCounter < (maxChars + 1))
			$($("." + theCharCounter)[b]).html(strCharCounter);
		else {
			eval("alert('통신사 문자발송 글자수제한에 따라 한글" + maxChars / 2 + ", 영문" + maxChars + "자 제한입니다. 초과된 문자는 잘립니다.')");
			if (!cutStr(theField, i, theCharCounter, maxChars))
				alert("문자열 커트 함수가 작동되지 않습니다.");
			break;
		}
	}
}

function cutStr(theField, i, theCharCounter, maxChars) {
	var intLength = theField.value.length; //-- 실제 문자의 길이를 구한다.

	var strChar = theField.value.substring(0, i); //마지막 문자를 잘라낸다.

	theField.value = strChar;
	textCounter(theField, theCharCounter, maxChars);
	return true;
}
//수신거부등록수정
function deny_add(frm, i, idx, chanel) {
	if(chanel){
		reg_chanel = chanel;
	}
	else{
		reg_chanel = $("#reg_chanel").val();
	}
	if (!document.getElementsByName('deny_send')[i].value) {
		document.getElementsByName('deny_send')[i].focus();
		return false;
	}
	if (!document.getElementsByName('deny_recv')[i].value) {
		document.getElementsByName('deny_recv')[i].focus();
		return false;
	}
	var msg = idx ? "수정하시겠습니까?" : "새로 등록하시겠습니까?";
	if (confirm(msg)) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				deny_add_send: document.getElementsByName('deny_send')[i].value,
				deny_add_recv: document.getElementsByName('deny_recv')[i].value,
				reg_chanel:reg_chanel,
				deny_add_idx: idx
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}

//수신동의등록수정
function agree_add(frm, i, idx) {
	if (!document.getElementsByName('agree_send')[i].value) {
		document.getElementsByName('agree_send')[i].focus();
		return false;
	}
	if (!document.getElementsByName('agree_recv')[i].value) {
		document.getElementsByName('agree_recv')[i].focus();
		return false;
	}
	var msg = "새로 등록하시겠습니까?";
	if (confirm(msg)) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				agree_add_send: document.getElementsByName('agree_send')[i].value,
				agree_add_recv: document.getElementsByName('agree_recv')[i].value,
				agree_add_idx: idx
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//수신거부삭제
function deny_del(idx) {
	var ids = "";
	if (idx)
		ids = idx;
	else {
		var ida = [];
		$("input[name='idx_box']:checked").each(function (i) {
			ida.push($(this).val());
		});
		if (!ida.length) {
			alert('적어도 하나는 선택해야 합니다..')
			return false;
		}
		ids = ida.join(",");
	}
	if (confirm('삭제하시겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				deny_del_ids: ids
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//수신거부삭제
function agree_del(idx) {
	var ids = "";
	if (idx)
		ids = idx;
	else {
		var ida = [];
		$("input[name='idx_box']:checked").each(function (i) {
			ida.push($(this).val());
		});
		if (!ida.length) {
			alert('적어도 하나는 선택해야 합니다..')
			return false;
		}
		ids = ida.join(",");
	}
	if (confirm('삭제하시겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				agree_del_ids: ids
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//엑셀다운로드
function excel_down(action, e, box_name, idx) {
	if (excel_down_form.ids)
		excel_down_form.ids.value = "";
	var ids = "";
	if (idx) {
		var ida = [];
		$("input[name='idx_box']:checked").each(function (i) {
			ida.push($(this).val());
		});

		ids = ida.join(",");
		if (ids)
			excel_down_form.ids.value = ids;
	}

	if (box_name) {
		var box_arr = [];
		var box_a = [];
		var box_s = "";
		box_arr = document.getElementsByName(box_name);
		for (var i = 0; i < box_arr.length; i++) {
			if (box_arr[i].checked)
				box_a.push("`" + box_arr[i].value + "`");
		}
		box_s = box_a.join(",");
		if (box_s == "") {
			alert('적어도 하나는 선택해야 합니다.');
			return false;
		}
		excel_down_form.box_text.value = box_s;
		excel_down_form.excel_sql.value = "";
	}
	if (e)
		excel_down_form.grp_id.value = e;
	excel_down_form.action = action;
	excel_down_form.submit();
}
//수신거부 엑셀인설트
function deny_excel_insert(frm, status) {
	if (!frm.excel_file.value) {
		alert('업로드 할 파일을 선택해주세요.');
		return false;
	}
	frm.target = 'excel_iframe';
	frm.action = "insert_excel.php?status=" + status;
	frm.submit();
}
//선택앱체크
function select_app_check(dan_num) {
	var num_a = [];
	if (dan_num)
		num_a.push(dan_num)
	else {
		var num_arr = document.getElementsByName('seq[]');
		for (var i = 0; i < num_arr.length; i++) {
			if (num_arr[i].checked)
				num_a.push(num_arr[i].value)
		}
		if (!num_a.length) {
			alert('적어도 하나는 선택해야 합니다.');
			return;
		}
	}
	if (confirm('앱체크 시작하겠습니까?')) {
		for (var i = 0; i < num_a.length; i++) {
			$("#btn_" + num_a[i]).removeClass("btn_option_red");
			$("#btn_" + num_a[i]).html("<img src='images/ajax-loader.gif'>");
		}
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				select_app_check_num: num_a,
				select_app_check_i: 1
			},
			success: function (data) {
				$("#ajax_div").html(data)
			}
		})
	}
}

function select_app_check_push(dan_num) {
    var cbs=document.getElementsByName(dan_num)
    var check_num_arr = [];
    for (var i = 0; i < cbs.length; i ++){
    	if(cbs[i].checked == true){
            check_num_arr.push(cbs[i].value);
		}
    }
    if(check_num_arr.length == 0){
    	alert("앱 체크할 폰을 선택해주세요.");
    	return;
	}
	console.log(check_num_arr);
	if (confirm('앱체크 시작하겠습니까?')) {
        $($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "ajax/ajax_session_check.php",
			data: {
				mode: "app_check",
				phone_num : check_num_arr
			},
			success: function (data) {
				$("#ajax_div").html(data)
                $($(".loading_div")[0]).hide();
			}
		});
	}
}
//발신수신 삭제
function fs_del() {
	var num_s = "";
	var name_arr = document.getElementsByName('fs_idx');
	var num_a = [];
	for (var i = 0; i < name_arr.length; i++) {
		if (name_arr[i].checked)
			num_a.push(name_arr[i].value);
	}
	num_s = num_a.join(",");
	if (num_s == "") {
		alert('적어도 하나는 선택해야 합니다.');
		return;
	}
	if (confirm('삭제하시겠습니까?')) {
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				fs_del_num_s: num_s
			},
			success: function (data) {
				$("#ajax_div").html(data)
			}
		})
	}
}

function fs_del_num(fs_idx) {
	num_s = fs_idx;
	if (num_s == "") {
		alert('적어도 하나는 선택해야 합니다.');
		return;
	}
	if (confirm('삭제하시겠습니까?')) {
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				fs_del_num_s: num_s
			},
			success: function (data) {
				$("#ajax_div").html(data)
			}
		})
	}
}
//회원탈퇴
function member_leave(frm) {
	if (!wrestSubmit(frm))
		return false;
	if (confirm('탈퇴하시겠습니까?')) {
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				member_leave_pwd: frm.leave_pwd.value,
				member_leave_liyou: frm.leave_liyou.value
			},
			success: function (data) {
				$("#ajax_div").html(data)
			}
		});
	}
}
//패뤈트이동
function move_parent(status) {
	if (status == "photo") {
		if (document.getElementsByName('photo_content')[0].value) {
			window.opener.document.getElementsByName("upimage_str")[0].value = document.getElementsByName('photo_content')[0].value;
			window.opener.document.getElementsByName('title')[0].value = document.getElementsByName('title')[0].value;
			$($(".img_view", window.opener.document)[0]).html("<img src='" + document.getElementsByName('photo_content')[0].value + "' />")
			window.close();
		}
	} else {
		if (document.getElementsByName('lms_content')[0].value) {
			window.opener.document.getElementsByName("txt")[0].value = document.getElementsByName('lms_content')[0].value;
			window.opener.document.getElementsByName('title')[0].value = document.getElementsByName('title')[0].value;
			window.close();
		} else
			document.getElementsByName('lms_content')[0].focus();
	}
	window.opener.type_check();
}
//원북패뤈트이동
function move_parent2() {
	if (onebook_form.one_content.value) {
		window.opener.sub_4_form.txt.value = onebook_form.one_content.value;
		window.opener.sub_4_form.onebook_status.value = 'Y';
		window.opener.sub_4_form.onebook_url.value = onebook_url;
		window.opener.type_check();
		window.close();
	} else
		onebook_form.one_content.focus();
}

function html_entity_decode(message) {
	return message.replace(/[<>'"]/g, function (m) {
		return '&' + {
			'\'': 'apos',
			'"': 'quot',
			'&': 'amp',
			'<': 'lt',
			'>': 'gt',
		} [m] + ';';
	});
}
//문자저장 이동
function show_msg(i) {
	document.getElementsByName('title')[0].value = $($(".msg_title")[i]).html();
	document.getElementsByName('lms_content')[0].value = $($(".msg_content")[i]).html().replace("&gt;", ">").replace("&lt;", "<");
	document.getElementsByName('lms_content')[0].focus();
}
//포토저장 이동
function show_photo(i) {
	document.getElementsByName('title')[0].value = $($(".msg_title")[i - 1]).html();
	document.getElementsByName('photo_content')[0].value = document.getElementsByName('photo_content')[i].value;
	$($(".img_view")[0]).html("<img src='" + document.getElementsByName('photo_content')[i].value + "' />");
	if (document.getElementsByName('photo_content1')[i].value) {
		document.getElementsByName('photo_content1')[0].value = document.getElementsByName('photo_content1')[i].value;
		$($(".img_view")[1]).html("<img src='" + document.getElementsByName('photo_content1')[i].value + "' />");
	} else {
		document.getElementsByName('photo_content1')[0].value = "";
		$($(".img_view")[1]).html("");
	}

	if (document.getElementsByName('photo_content2')[i].value) {
		document.getElementsByName('photo_content2')[0].value = document.getElementsByName('photo_content2')[i].value;
		$($(".img_view")[2]).html("<img src='" + document.getElementsByName('photo_content2')[i].value + "' />");
	} else {
		document.getElementsByName('photo_content2')[0].value = "";
		$($(".img_view")[2]).html("");
	}
}
//원북내용 이동
function show_content(i) {
	document.getElementsByName('one_content')[0].value = $($(".a3_1")[i]).html().replace(/<br \/>|<br>/g, "\n");
	document.getElementsByName('one_content')[0].focus();
}
//원북검색 선택
var onebook_url = "";

function book_select(i, name) {
	var url = 'www.onepagebook.net/opb/app/sub/sub_gallery_view.php?it_id=' + document.getElementsByName('it_id')[i].value;
	onebook_url = "\n\n■ 원북 보러가기:" + url;
	var str1 = "[" + name + "님이 추천하는 원북]<br>";
	var str2 = "■ 제목:" + document.getElementsByName('it_name')[i].value + "<br>"; //제목
	var str3 = "(" + document.getElementsByName('it_origin')[i].value + "저)<br>"; //저자
	var str4 = document.getElementsByName('opb1')[i].value + "<br>"; //주제와요점
	var str5 = document.getElementsByName('opb2')[i].value + "<br>"; //제1장
	var str6 = document.getElementsByName('opb3')[i].value + "<br>"; //전체내용
	var str7 = document.getElementsByName('prset1')[i].value + "<br>"; //저자이해
	var str8 = document.getElementsByName('prset2')[i].value + "<br>"; //저자주장
	var str9 = document.getElementsByName('prset3')[i].value + "<br>"; //저자의도와목적	
	var str = "";
	$(".a3_1").each(function (c) {
		switch (c) {
			case 0:
				str = str1 + str2 + str3 + str6;
				break;
			case 1:
				str = str1 + str2 + str3 + str4;
				break;
			case 2:
				str = str1 + str2 + str3 + str7;
				break;
			case 3:
				str = str1 + str2 + str3 + str8;
				break;
			case 4:
				str = str1 + str2 + str3 + str9;
				break;
			case 5:
				str = str1 + str2 + str3 + str5;
				break;
			case 6:
				str = str1 + str2 + str3 + str5;
				break;
			case 7:
				str = str1 + str2 + str3 + str5;
				break;
		}
		$(this).html(str);
	})
}
//원북내용 삭제
function one_del() {
	var num_s = "";
	var name_arr = document.getElementsByName('one_idx');
	var num_a = [];
	for (var i = 0; i < name_arr.length; i++) {
		if (name_arr[i].checked)
			num_a.push(name_arr[i].value);
	}
	num_s = num_a.join(",");
	if (num_s == "") {
		alert('적어도 하나는 선택해야 합니다.');
		return;
	}
	if (confirm('삭제하시겠습니까?')) {
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				one_del_num_s: num_s
			},
			success: function (data) {
				$("#ajax_div").html(data)
			}
		})
	}
}
//비밀번호 변경
function pwd_change(frm, i) {
	if (!wrestSubmit(frm))
		return false;
	if (confirm('변경하시겠습니까?')) {
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				pwd_change_old_pwd: document.getElementsByName('old_pwd')[i].value,
				pwd_change_new_pwd: document.getElementsByName('pwd')[i].value,
				pwd_change_status: i
			},
			success: function (data) {
				$("#ajax_div").html(data)
			}
		});
	}
}
//수신거부 개별등록
function deny_g_add() {
	var name_arr = document.getElementsByName('seq[]');
	var num_a = [];
	for (var i = 0; i < name_arr.length; i++) {
		if (name_arr[i].checked)
			num_a.push(name_arr[i].value);
	}
	if (!num_a.length) {
		alert('적어도 하나는 선택해야 합니다.');
		return;
	}
	if (!document.getElementsByName('deny_num')[0].value) {
		document.getElementsByName('deny_num')[0].focus();
		return false;
	}
	if (confirm('등록하시겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				deny_g_add_recv_num: document.getElementsByName('deny_num')[0].value,
				deny_g_add_send_num: num_a
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		});
	}
}
//휴대폰 상세정보등록
function set_save_xx() {
	var name_arr = document.getElementsByName('seq[]');
	var num_a = [];
	var device_arr = document.getElementsByName('device');
	var device_a = [];
	var memo3_arr = document.getElementsByName('memo3');
	var memo3_a = [];
	for (var i = 0; i < name_arr.length; i++) {
		if (name_arr[i].checked) {
			num_a.push(name_arr[i].value);
			device_a.push(device_arr[i].value);
			memo3_a.push(memo3_arr[i].value);
		}
	}
	if (!num_a.length) {
		alert('적어도 하나는 선택해야 합니다.');
		return;
	}
	if (confirm('저장하겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				set_save_num: num_a,
				set_save_device: device_a,
				set_save_memo3: memo3_a
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		});
	}
}
//수신번호 보이기
function show_recv(name, c, t, status) {
	if (!document.getElementsByName(name)[c].value)
		return;
	open_div(open_recv_div, 100, 1, status);
	if (name == "show_jpg")
		$($(".open_recv")[0]).html("<img src='" + document.getElementsByName(name)[c].value + "' />");
	else if (name == "show_jpg1")
		$($(".open_recv")[0]).html("<img src='" + document.getElementsByName(name)[c].value + "' />");
	else if (name == "show_jpg2")
		$($(".open_recv")[0]).html("<img src='" + document.getElementsByName(name)[c].value + "' />");
	else
		$($(".open_recv")[0]).html(document.getElementsByName(name)[c].value.replace(/\n/g, "<br/>"));
	$($(".open_recv_title")[0]).html(t);
}
//미리보기
function ml_view(name, c, t) {
	open_div(open_recv_div, 100, 1);
	var content = "";
	var contents = "";
	if (document.getElementsByName('fs_msg')[0].checked)
		content = document.getElementsByName(name)[c].value + document.getElementsByName('onebook_url')[c].value + "\n\n" + document.getElementsByName('fs_txt')[c].value;
	else
		content = document.getElementsByName(name)[c].value + document.getElementsByName('onebook_url')[c].value;
	if ($('[name=upimage_str]').val() != "") {
		contents += "<br><img src='" + $('[name=upimage_str]').val() + "' style='width:150px;height:150x;'>";
	}
	if ($('[name=upimage_str1]').val() != "") {
		contents += "<br><img src='" + $('[name=upimage_str1]').val() + "' style='width:150px;height:150x;'>";
	}
	if ($('[name=upimage_str2]').val() != "") {
		contents += "<br><img src='" + $('[name=upimage_str2]').val() + "' style='width:150px;height:150x;'>";
	}
	$($(".open_recv")[0]).html(content.replace(/\n/g, "<br/>") + contents);
	$($(".open_recv_title")[0]).html(t);
}
//로그기록삭제
function log_del(idx) {
	var ids = "";
	if (idx)
		ids = idx;
	else {
		var ida = [];
		$("input[name='idx_box']:checked").each(function (i) {
			ida.push($(this).val());
		});
		if (!ida.length) {
			alert('적어도 하나는 선택해야 합니다..')
			return false;
		}
		ids = ida.join(",");
	}
	if (confirm('삭제하시겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				log_del_ids: ids
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		});
	}
}
//로그기록 추가 삭제
function log_add(frm, i, msg_flag, idx) {
	if (!document.getElementsByName('log_dest')[i].value) {
		document.getElementsByName('log_dest')[i].focus();
		return false;
	}
	if (!document.getElementsByName('log_ori')[i].value) {
		document.getElementsByName('log_ori')[i].focus();
		return false;
	}
	var msg = idx ? "수정하시겠습니까?" : "새로 등록하시겠습니까?";
	if (confirm(msg)) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				log_add_dest: document.getElementsByName('log_dest')[i].value,
				log_add_ori: document.getElementsByName('log_ori')[i].value,
				log_add_msg_flag: msg_flag,
				log_add_idx: idx
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		});
	}
}

function comma(str) {
	str = String(str);
	return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}
//결제토탈
function pay_total(status) {
	var add_phone_cnt = 0;
	switch (status) {
		case "add_msg":
			$('#money1').val(Math.ceil(($('#add_msg').val() * 1) / 1000) * 500);
			$('#sum1').html($('#money1').val());
			break;
		case "add_term":
			if ($('#add_term').val() > 3) $('#add_term').val('3');
			$('#term').html(number_format($('#add_term').val()));
			break;
		case "add_phone":
			if (!document.getElementsByName(status)[0].value)
				add_phone_cnt = 0;
			else {
				if (isNaN(document.getElementsByName(status)[0].value)) {
					document.getElementsByName(status)[0].value = "";
					add_phone_cnt = 0;
				} else
					add_phone_cnt = document.getElementsByName(status)[0].value
			}
			document.getElementsByName('add_money')[0].value = parseInt(add_phone_cnt) * 2000;
			$($(".add_money_span")[0]).html(number_format(document.getElementsByName('add_money')[0].value));
			break;
		case "add_service":
			if (document.getElementsByName(status)[0].checked) {
				document.getElementsByName('add_money')[1].value = 30000;
				$('#money2').val('30000');
			} else {
				document.getElementsByName('add_money')[1].value = 0;
				$('#money2').val(0);
			}
			$($(".add_money_span")[1]).html(comma(number_format(document.getElementsByName('add_money')[1].value)));
			break;
	}
	var price = parseInt(($('#money1').val() * 1) + ($('#money2').val() * 1));
	$('#total_money_span').html(number_format(price * $('#add_term').val()));
	$('#total_money_l').html(number_format(price * $('#add_term').val()));
	$('#price').val(price * $('#add_term').val());
	var type_arr = document.getElementsByName('money_type');
	for (var i = 0; i < type_arr.length; i++) {
		if (type_arr[i].checked) {
			if (i == 0) {
				document.getElementsByName('mid')[0].value = 'obmms20151';
				var goodname = "온리원문자 - 직접결제 - " + $('#add_term').val() + "개월기간 ";
				$($(".auto_pay_c")[0]).hide();
			} else {
				document.getElementsByName('mid')[0].value = 'obmms20152';
				var goodname = "온리원문자 - 자동결제 - " + $('#add_term').val() + "개월기간 ";
				$($(".auto_pay_c")[0]).show();
			}
			document.getElementsByName('goodname')[0].value = goodname;
			document.getElementsByName('max_cnt')[0].value = ($('#money1').val() * 1);
			document.getElementsByName('month_cnt')[0].value = $('#add_term').val();
		}
	}
}
//결제토탈
function pay_total_v(status) {
	var add_phone_cnt = 0;
	switch (status) {
		case "add_phone":
			$('#add_money_total').html(($('#add_phone').val() * 1) * 9900);
			$('#add_money').val(($('#add_phone').val() * 1) * 9900);
			$('#total_count').html(($('#add_phone').val() * 1) * 9000);
			break;
	}
	if (($('input[name=money_type]:checked').val() * 1) == 3) {
		$('#total_amount').html(($('#add_phone').val() * 1) * 9900 * ($('input[name=money_type]:checked').val() * 1) * 0.95);
		$('#price').val(($('#add_phone').val() * 1) * 9900 * ($('input[name=money_type]:checked').val() * 1) * 0.95);
	} else if (($('input[name=money_type]:checked').val() * 1) == 6) {
		$('#total_amount').html(($('#add_phone').val() * 1) * 9900 * ($('input[name=money_type]:checked').val() * 1) * 0.92);
		$('#price').val(($('#add_phone').val() * 1) * 9900 * ($('input[name=money_type]:checked').val() * 1) * 0.92);
	} else if (($('input[name=money_type]:checked').val() * 1) == 12) {
		$('#total_amount').html(($('#add_phone').val() * 1) * 9900 * ($('input[name=money_type]:checked').val() * 1) * 0.9);
		$('#price').val(($('#add_phone').val() * 1) * 9900 * ($('input[name=money_type]:checked').val() * 1) * 0.9);
	} else {
		$('#total_amount').html(($('#add_phone').val() * 1) * 9900 * ($('input[name=money_type]:checked').val() * 1));
		$('#price').val(($('#add_phone').val() * 1) * 9900 * ($('input[name=money_type]:checked').val() * 1));
	}
	var type_arr = document.getElementsByName('money_type');
	for (var i = 0; i < type_arr.length; i++) {
		if (type_arr[i].checked) {
			if (i == 0) {
				document.getElementsByName('mid')[0].value = 'obmms20151';
				var goodname = "온리원문자 - " + ($('input[name=money_type]:checked').val() * 1) + "개월기간 ";
				$($(".auto_pay_c")[0]).hide();
			} else {
				document.getElementsByName('mid')[0].value = 'obmms20151';
				var goodname = "온리원문자 - " + ($('input[name=money_type]:checked').val() * 1) + "개월기간 ";
				$($(".auto_pay_c")[0]).show();
			}
			document.getElementsByName('goodname')[0].value = goodname;
			document.getElementsByName('max_cnt')[0].value = (($('#add_phone').val() * 1) * 9000);
			document.getElementsByName('month_cnt')[0].value = ($('input[name=money_type]:checked').val() * 1);
		}
	}
}
//결제고고
function pay_go(frm) {
	if (!frm.mid.value) {
		alert('결제종류를 선택해주세요.');
		document.getElementsByName('money_type')[0].focus();
		return false;
	}
	if (!document.getElementsByName('price')[0].value || parseInt(document.getElementsByName('price')[0].value) <= 0) {
		alert('결제하실 금액이 존재하지 않습니다.');
		return
	}
	if (confirm('결제시작하시겠습니까?')) {
		if ($('input[name=payment_type]:checked').val() == "bank") {
			frm.action = 'pay_cash.php';
			frm.submit();
		} else if ($('input[name=payment_type]:checked').val() == "month") {
			frm.action = 'pay_month.php';
			frm.submit();
		} else {
			if (frm.mid.value == "obmms20151") {
				frm.target = 'pay_iframe';
				frm.action = 'pay_start.php';
				frm.submit();
			} else if (frm.mid.value == "obmms20152") {
				frm.target = 'pay_iframe';
				frm.action = 'pay_auto_start.php';
				frm.submit();
			}
		}
	}
}

function npay_go(frm) {
	if (!frm.mid.value) {
		alert('결제종류를 선택해주세요.');
		document.getElementsByName('money_type')[0].focus();
		return false;
	}
	if (!document.getElementsByName('price')[0].value || parseInt(document.getElementsByName('price')[0].value) <= 0) {
		alert('결제하실 금액이 존재하지 않습니다.');
		return
	}
	if (confirm('결제시작하시겠습니까?')) {
		if ($('input[name=payment_type]:checked').val() == "bank") {
			//frm.target='pay_iframe';
			frm.action = 'pay_cash.php';
			frm.submit();
		} else if ($('input[name=payment_type]:checked').val() == "month") {
			//frm.target='pay_iframe';
			frm.action = 'pay_month.php';
			frm.submit();
		} else {
			if (frm.mid.value == "obmms20151") {
				frm.target = 'pay_iframe';
				frm.action = 'pay_start.php';
				frm.submit();
			} else if (frm.mid.value == "obmms20152") {
				frm.target = 'pay_iframe';
				frm.action = 'pay_auto_start.php';
				frm.submit();
			}
		}
	}
}
//고객센터 글등록
function board_save(frm, no, category) {
	var content_s = "";
	var mobile_s = "";
	var email_s = "";
	var status_1_s = "";
	var adjunct_1_s = "";
	var adjunct_2_s = "";
	var adjunct_memo_s = "";
	var up_path_s = "";
	var return_url = "";
	if (!wrestSubmit(frm))
		return false;
	var fl_s = "";
	var pop_yn = frm.popup_yn.checked ? 'Y' : 'N';
	var important_yn = frm.important_yn.checked ? 'Y' : 'N';
	var display_yn = frm.display_al.value;
	var start_date = frm.start_date.value;
	var end_date = frm.end_date.value;

	if (document.getElementsByName('fl')[0]) {
		var fl_arr = document.getElementsByName('fl');
		for (var i = 0; i < fl_arr.length; i++) {
			if (fl_arr[i].checked)
				fl_s = fl_arr[i].value;
		}
		if (fl_s == "") {
			alert('분류 필수선택입니다.')
			document.getElementsByName('fl')[0].focus();
			return;
		}
	}
	if (frm.ir1) {
		oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
		content_s = frm.ir1.value.replace(/\(/g, "&#40;").replace(/\)/g, "&#41;");
		if (content_s == "<p>&nbsp;</p>") {
			alert('내용 필수입력입니다.')
			return false
		}
	}
	if (frm.mobile_1)
		mobile_s = frm.mobile_1.value + "-" + frm.mobile_2.value + "-" + frm.mobile_3.value;
	if (frm.email_1)
		email_s = frm.email_1.value + "@" + frm.email_2.value + frm.email_3.value;
	if (frm.status_1)
		status_1_s = frm.status_1.checked ? "Y" : "N";
	if (frm.return_url)
		return_url = frm.return_url.value;
	if (document.getElementsByName('board_write_form_memo_hid')[0]) {
		adjunct_1_s = document.getElementsByName('board_write_form_img_hid')[0].value;
		adjunct_2_s = document.getElementsByName('board_write_form_img_hid_2')[0].value;
		var memo_name_arr = window.frames["upload_iframe"].document.getElementsByName("adjunct_memo");
		var adjunct_memo_a = [];
		for (var i = 0; i < memo_name_arr.length; i++) {
			adjunct_memo_a.push(memo_name_arr[i].value)
		}
		adjunct_memo_s = adjunct_memo_a.join("\n");
		up_path_s = document.getElementsByName('up_path')[0].value;
	}
	var msg = no ? "수정하시겠습니까?" : "새로 등록하시겠습니까?";
	if (confirm(msg)) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				board_save_category: category,
				board_save_fl: fl_s,
				board_save_title: document.getElementsByName('title')[0].value,
				board_save_phone: mobile_s,
				board_save_email: email_s,
				board_save_status_1: status_1_s,
				board_save_content: content_s,
				board_save_adjunct_1: adjunct_1_s,
				board_save_adjunct_2: adjunct_2_s,
				board_save_adjunct_memo: adjunct_memo_s,
				board_save_up_path: up_path_s,
				board_save_no: no,
				board_save_pop_yn: pop_yn,
				board_save_important_yn: important_yn,
				board_save_display_yn: display_yn,
				board_save_start_date: start_date,
				board_save_end_date: end_date,
				return_url: return_url
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		});
	}
}

//고객센터 글등록
function sellerboard_save(frm, no, category) {
	var content_s = "";
	var mobile_s = "";
	var email_s = "";
	var status_1_s = "";
	var adjunct_1_s = "";
	var adjunct_2_s = "";
	var adjunct_memo_s = "";
	var up_path_s = "";
	var return_url = "";
	if (!wrestSubmit(frm))
		return false;
	var fl_s = "";
	var pop_yn = frm.popup_yn.checked ? 'Y' : 'N';
	var important_yn = frm.important_yn.checked ? 'Y' : 'N';
	var display_yn = frm.display_al.value;
	if (!display_yn)
		display_yn = 'N';
	var start_date = frm.start_date.value;
	var end_date = frm.end_date.value;
	if (document.getElementsByName('fl')[0]) {
		var fl_arr = document.getElementsByName('fl');
		for (var i = 0; i < fl_arr.length; i++) {
			if (fl_arr[i].checked)
				fl_s = fl_arr[i].value;
		}
		if (fl_s == "") {
			alert('분류 필수선택입니다.')
			document.getElementsByName('fl')[0].focus();
			return;
		}
	}
	if (frm.ir1) {
		oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
		content_s = frm.ir1.value.replace(/\(/g, "&#40;").replace(/\)/g, "&#41;");
		if (content_s == "<p>&nbsp;</p>") {
			alert('내용 필수입력입니다.')
			return false
		}
	}
	if (frm.mobile_1)
		mobile_s = frm.mobile_1.value + "-" + frm.mobile_2.value + "-" + frm.mobile_3.value;
	if (frm.email_1)
		email_s = frm.email_1.value + "@" + frm.email_2.value + frm.email_3.value;
	if (frm.status_1)
		status_1_s = frm.status_1.checked ? "Y" : "N";
	if (frm.return_url)
		return_url = frm.return_url.value;
	if (document.getElementsByName('board_write_form_memo_hid')[0]) {
		adjunct_1_s = document.getElementsByName('board_write_form_img_hid')[0].value;
		adjunct_2_s = document.getElementsByName('board_write_form_img_hid_2')[0].value;
		var memo_name_arr = window.frames["upload_iframe"].document.getElementsByName("adjunct_memo");
		var adjunct_memo_a = [];
		for (var i = 0; i < memo_name_arr.length; i++) {
			adjunct_memo_a.push(memo_name_arr[i].value)
		}
		adjunct_memo_s = adjunct_memo_a.join("\n");
		up_path_s = document.getElementsByName('up_path')[0].value;
	}
	var msg = no ? "수정하시겠습니까?" : "새로 등록하시겠습니까?";
	if (confirm(msg)) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_sellerboard.php",
			data: {
				board_save_category: category,
				board_save_fl: fl_s,
				board_save_title: document.getElementsByName('title')[0].value,
				board_save_phone: mobile_s,
				board_save_email: email_s,
				board_save_status_1: status_1_s,
				board_save_content: content_s,
				board_save_adjunct_1: adjunct_1_s,
				board_save_adjunct_2: adjunct_2_s,
				board_save_adjunct_memo: adjunct_memo_s,
				board_save_up_path: up_path_s,
				board_save_no: no,
				board_save_pop_yn: pop_yn,
				board_save_important_yn: important_yn,
				board_save_display_yn: display_yn,
				board_save_start_date: start_date,
				board_save_end_date: end_date,
				return_url: return_url
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		});
	}
}

function board_save_reply(frm, no, category) {
	var content_s = "";
	var mobile_s = "";
	var email_s = "";
	var status_1_s = "";
	var adjunct_1_s = "";
	var adjunct_2_s = "";
	var adjunct_memo_s = "";
	var up_path_s = "";
	if (!wrestSubmit(frm))
		return false;
	var fl_s = "";
	if (document.getElementsByName('fl')[0]) {
		var fl_arr = document.getElementsByName('fl');
		for (var i = 0; i < fl_arr.length; i++) {
			if (fl_arr[i].checked)
				fl_s = fl_arr[i].value;
		}
		if (fl_s == "") {
			alert('분류 필수선택입니다.')
			document.getElementsByName('fl')[0].focus();
			return;
		}
	}
	if (frm.ir1) {
		oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
		content_s = frm.ir1.value.replace(/\(/g, "&#40;").replace(/\)/g, "&#41;");
		if (content_s == "<p>&nbsp;</p>") {
			alert('내용 필수입력입니다.')
			return false
		}
	}
	if (frm.mobile_1)
		mobile_s = frm.mobile_1.value + "-" + frm.mobile_2.value + "-" + frm.mobile_3.value;
	if (frm.email_1)
		email_s = frm.email_1.value + "@" + frm.email_2.value + frm.email_3.value;
	if (frm.status_1)
		status_1_s = frm.status_1.checked ? "Y" : "N";
	if (document.getElementsByName('board_write_form_memo_hid')[0]) {
		adjunct_1_s = document.getElementsByName('board_write_form_img_hid')[0].value;
		adjunct_2_s = document.getElementsByName('board_write_form_img_hid_2')[0].value;
		var memo_name_arr = window.frames["upload_iframe"].document.getElementsByName("adjunct_memo");
		var adjunct_memo_a = [];
		for (var i = 0; i < memo_name_arr.length; i++) {
			adjunct_memo_a.push(memo_name_arr[i].value)
		}
		adjunct_memo_s = adjunct_memo_a.join("\n");
		up_path_s = document.getElementsByName('up_path')[0].value;
	}
	var msg = no ? "수정하시겠습니까?" : "새로 등록하시겠습니까?";
	if (confirm(msg)) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				board_save_category: category,
				board_save_fl: fl_s,
				board_save_title: document.getElementsByName('title')[0].value,
				board_save_phone: mobile_s,
				board_save_email: email_s,
				board_save_status_1: status_1_s,
				board_save_reply: content_s,
				board_save_adjunct_1: adjunct_1_s,
				board_save_adjunct_2: adjunct_2_s,
				board_save_adjunct_memo: adjunct_memo_s,
				board_save_up_path: up_path_s,
				board_save_no: no
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//게시판
function board_del(no, status) {
	var ids = "";
	if (no)
		ids = no;
	else {
		var ida = [];
		$("input[name='no_box']:checked").each(function (i) {
			ida.push($(this).val());
		});
		if (!ida.length) {
			alert('적어도 하나는 선택해야 합니다..')
			return false;
		}
		ids = ida.join(",");
	}
	if (confirm('삭제하시겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				board_del_ids: ids,
				board_del_status: status
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//
function deny_msg_click(tthis, i) {
	if (tthis.checked) {
		if (i == 1) {
			document.getElementsByName('ssh_check')[1].checked = false;
			document.getElementsByName('ssh_check')[2].checked = false;
			$($('.deny_msg_span')[2]).html('OFF');
			$($('.deny_msg_span')[2]).css('color', '#F00');
			$($('.deny_msg_span')[3]).html('OFF');
			$($('.deny_msg_span')[3]).css('color', '#F00');
		}
		if (i == 2) {
			document.getElementsByName('ssh_check')[0].checked = false;
			document.getElementsByName('ssh_check')[2].checked = false;
			$($('.deny_msg_span')[1]).html('OFF');
			$($('.deny_msg_span')[1]).css('color', '#F00');
			$($('.deny_msg_span')[3]).html('OFF');
			$($('.deny_msg_span')[3]).css('color', '#F00');
		}
		if (i == 3) {
			document.getElementsByName('ssh_check')[0].checked = false;
			document.getElementsByName('ssh_check')[1].checked = false;
			$($('.deny_msg_span')[1]).html('OFF');
			$($('.deny_msg_span')[1]).css('color', '#F00');
			$($('.deny_msg_span')[2]).html('OFF');
			$($('.deny_msg_span')[2]).css('color', '#F00');
		}
		$($('.deny_msg_span')[i]).html('ON');
		$($('.deny_msg_span')[i]).css('color', '#00F');
	} else {
		$($('.deny_msg_span')[i]).html('OFF');
		$($('.deny_msg_span')[i]).css('color', '#F00');
	}
}
//등록관리 계산
function jiajian_oo(i) {
	var s1 = document.getElementsByName('gl_cnt')[i].value;
	if (!isNaN(s1) && s1 && parseInt(s1) < 500) {
		document.getElementsByName('max_cnt')[i].value = 500 - parseInt(s1);
		$($(".max_cnt_c")[i]).html(500 - parseInt(s1));
	} else {
		document.getElementsByName('max_cnt')[i].value = 500;
		$($(".max_cnt_c")[i]).html(500);
		document.getElementsByName('gl_cnt')[i].value = "";
	}
}

function jiajian_oo_1(i, today_cnt) {
	var user_cnt_s = document.getElementsByName('user_cnt')[i].value;
	var max_cnt_s = document.getElementsByName('max_cnt')[i].value;
	var cnt1_s = document.getElementsByName('cnt1')[i].value;
	var cnt2_s = document.getElementsByName('cnt2')[i].value;
	if (isNaN(user_cnt_s) || parseInt(user_cnt_s) > parseInt(max_cnt_s))
		document.getElementsByName('user_cnt')[i].value = "";
	if (parseInt(cnt1_s) == 10 && user_cnt_s > 200)
		document.getElementsByName('user_cnt')[i].value = "";
	if (parseInt(cnt1_s) == 10 && parseInt(cnt2_s) == 20)
		document.getElementsByName('user_cnt')[i].value = "";
	if (parseInt(today_cnt) > parseInt(max_cnt_s))
		document.getElementsByName('user_cnt')[i].value = "";
	if ((parseInt(today_cnt) + parseInt(user_cnt_s)) > parseInt(max_cnt_s))
		document.getElementsByName('user_cnt')[i].value = "";
}
//번호변경 덮어쓰기
function fugai_num(no, status) {
	var ids = "";
	if (status == "cho") {
		if (no)
			ids = no;
		else {
			var ida = [];
			$("input[name='idx_box']:checked").each(function (i) {
				ida.push($(this).val());
			});
			ids = ida.join(",");
		}
		if (ids == "") {
			alert('적어도 하나는 선택해야 합니다..')
			return false;
		}
	}
	if (confirm('덮어쓰기 하시겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				fugai_num_ids: ids,
				fugai_num_status: status
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//번호삭제 덮어쓰기
function delete_num(no, status) {
	var ids = "";

	if (no)
		ids = no;
	else {
		var ida = [];
		$("input[name='idx_box']:checked").each(function (i) {
			ida.push($(this).val());
		});
		ids = ida.join(",");
	}
	if (ids == "") {
		alert('적어도 하나는 선택해야 합니다..')
		return false;
	}

	if (confirm('삭제 하시겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				delete_num_ids: ids,
				delete_num_status: status
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//발송딜레이 체크
function send_delay(frm, tthis, status) {
	if (isNaN(tthis.value))
		tthis.value = '';
	if (status == 2) {
		if (parseInt(tthis.value) <= parseInt(frm.delay.value))
			tthis.value = '';
	}
}
//입력상태체크
function type_check() {
	if (document.getElementsByName('txt')[0].value)
		$($(".type_icon")[0]).children().show();
	else
		$($(".type_icon")[0]).children().hide();

	if (document.getElementsByName('upimage_str')[0].value)
		$($(".type_icon")[1]).children().show();
	else
		$($(".type_icon")[1]).children().hide();

	if (document.getElementsByName('onebook_status')[0].value == "Y")
		$($(".type_icon")[2]).children().show();
	else
		$($(".type_icon")[2]).children().hide();

	if (document.getElementsByName('ssh_check')[0].checked || document.getElementsByName('ssh_check')[1].checked)
		$($(".type_icon")[3]).children().show();
	else
		$($(".type_icon")[3]).children().hide();

	if (document.getElementsByName('deny_wushi[]')[0].checked || document.getElementsByName('deny_wushi[]')[1].checked || document.getElementsByName('deny_wushi[]')[2].checked || document.getElementsByName('deny_wushi[]')[3].checked)
		$($(".type_icon")[4]).children().show();
	else
		$($(".type_icon")[4]).children().hide();

	if (document.getElementsByName('rday')[0].value)
		$($(".type_icon")[5]).children().show();
	else
		$($(".type_icon")[5]).children().hide();
}
//결제해지
function pay_cancel(no, paymethod, mid, tid, end_date, fujia) {
	if (confirm('해당 결제를 해지하시겠습니까?')) {
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/ajax_session.php",
			data: {
				pay_cancel_no: no,
				pay_cancel_paymethod: paymethod,
				pay_cancel_mid: mid,
				pay_cancel_tid: tid,
				pay_cancel_end_date: end_date,
				pay_cancel_fujia: fujia
			},
			success: function (data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data)
			}
		})
	}
}
//결제연장
function pay_ex_go(no, end_date, is_chrome) {
	if (is_chrome) {
		alert('결제는 익스플로러에서만 가능합니다.')
		return false;
	} else {
		open_div(open_pay_extend, 100, 1);
		document.getElementsByName('pay_ex_no')[0].value = no;
		document.getElementsByName('pay_ex_end_date')[0].value = end_date;
	}
}
// 위콘 탭 슬라이드
function stateScrollObj(param,obj,btn,interval,speed,viewSize,moreSize,dir,data,auto,hover,method,op1){
	var param = $(param);
	var btn = $(btn);
	var obj = param.find(obj);

	var elem = 0;
	var objYScale = obj.eq(elem).outerHeight(true)+moreSize;
	var objXScale = obj.eq(elem).outerWidth(true)+moreSize;
	var str;
	var returnNodes;

	var playdir = data;
	var data = data; // 0:default, 1:prev

	var play = btn.find("[data-control=play]");
	var stop = btn.find("[data-control=stop]");
	
	if(auto == true) play.hide(); else stop.hide(); 
	if(op1 == true) obj.not(elem).css({opacity:0}).eq(elem).css({opacity:1});

	function movement(){

		switch(data){
			case 0:
				if(dir == "x"){
					obj.parent().stop(true,true).animate({left:-objXScale},{duration:speed,easing:method,complete:
						function(){
							obj.parent().css("left",0);
							str = obj.eq(elem).detach();
							obj.parent().append(str);
							if(elem == obj.length-1){
								elem = 0;
							}else{
								elem++;
							}
							objXScale = obj.eq(elem).outerWidth(true)+moreSize;

						}
					});
				}else{ 
					obj.parent().stop(true,true).animate({top:-objYScale},{duration:speed,easing:method,complete:
						function(){
							obj.parent().css("top",0);
							str = obj.eq(elem).detach();
							obj.parent().append(str);
							if(elem == obj.length-1){
								elem = 0;
							}else{
								elem++;
							}
							objYScale = obj.eq(elem).outerHeight(true)+moreSize;

						}
					});
				}

				if(op1 == true){
					obj.eq(elem).stop(true,true).animate({opacity:0},{duration:speed, easing:method, complete: function () {

						$(this).css('display', 'none');
					}});

					obj.eq(elem).next().css('display', 'block').stop(true,true).animate({opacity:1},{duration:speed,easing:method});
					//obj.eq(elem).stop(true,true).fadeOut(speed);
					//obj.eq(elem).next().stop(true,true).fadeIn(speed);
					//obj.eq(elem).css({"z-index":"0"});
					//obj.eq(elem).next().css({"z-index":"1"});					
				}

			break;
			
			case 1:
				if(dir == "x"){
					if(elem == 0){
						elem = obj.length-1;
					}else{
						elem--;
					}
					objXScale = obj.eq(elem).outerWidth()+moreSize;
					obj.parent().css("left",-objXScale);
					str = obj.eq(elem).detach();
					obj.parent().prepend(str);
					obj.parent().stop(true,false).animate({left:0},{duration:speed,easing:method});

				}else{
					if(elem == 0){
						elem = obj.length-1;
					}else{
						elem--;
					}
					objYScale = obj.eq(elem).outerHeight()+moreSize;
					obj.parent().css("top",-objYScale);
					str = obj.eq(elem).detach();
					obj.parent().prepend(str);
					obj.parent().stop(true,false).animate({top:0},{duration:speed,easing:method});

				}

				if(op1 == true){
					obj.eq(elem).stop(true,false).css('display', 'block').animate({opacity:1},{duration:speed,easing:method});
					obj.eq(elem).next().stop(true,false).animate({opacity:0},{duration:speed,easing:method, complete: function () {

						$(this).css('display', 'none');
					}});
					//obj.eq(elem).stop(true,false).fadeIn(speed);
					//obj.eq(elem).next().stop(true,false).fadeOut(speed);
					//obj.eq(elem).css({"z-index":"1"});
					//obj.eq(elem).next().css({"z-index":"0"});
				}

			break;
			
			default: alert("warning, 0:default, 1:prev, data:"+data);
		}
	}

	function rotate(){
		clearInterval(returnNodes);
		returnNodes = setInterval(function(){
			movement();
		},interval);
	}

	if(obj.length <= viewSize) return false;

	obj.find("a").on("focusin",function(){
		clearInterval(returnNodes);
	});

	btn.find("[data-control=play]").on("click",function(event){
		data = playdir;
		play.hide();
		stop.show();
		rotate();
		return false;
	});

	btn.find("[data-control=stop]").on("click",function(event){
		clearInterval(returnNodes);
		param.find(":animated").stop();
		stop.hide();
		play.show();
		return false;
	});

	btn.find("[data-control=prev]").on("click",function(event){
		if(obj.parent().find(":animated").length) return false;

		clearInterval(returnNodes);
		data = 1;
		movement();
		// add
		stop.hide();
		play.show();
		return false;
	});

	btn.find("[data-control=next]").on("click",function(event){
		if(obj.parent().find(":animated").length) return false;

		clearInterval(returnNodes);
		data = 0;
		movement();
		// add
		stop.hide();
		play.show();
		return false;
	});

	if(hover == true){
		obj.hover(function(){
			clearInterval(returnNodes);
		},function(){
			rotate();
		});
	}

	if(auto == true) rotate();

	// 터치 이벤트  플리킹 구현
	var xStartpoint,xEndpoint;

	function docSTART(event){
		if(event.originalEvent.changedTouches != undefined){
			xStartpoint = Math.floor(event.originalEvent.changedTouches[0].clientX - $(this).offset().left);
		}
	}

	function docEND(event){
		if(event.originalEvent.changedTouches != undefined){
			xEndpoint = Math.floor(event.originalEvent.changedTouches[0].clientX - $(this).offset().left) - xStartpoint;
		
			if(xEndpoint < -50){ 
				data = 0;
			}else if(xEndpoint > 50){
				data = 1;
			}else{
				return true;
			} 

			clearInterval(returnNodes);

			stop.hide();
			play.show();

			movement();

			event.preventDefault();
		}
	}

	if (dir == 'x') {
		param.on("touchstart",docSTART);
		param.on("touchend",docEND);
	}
}