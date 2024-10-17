//메시지발송
var chk_time = false;
var msg_dp = false;
function send_msg_new(frm) {
  var free_use = "N";
  if ($("#free_use").is(":checked") == true) {
    free_use = "Y";
  }
  var free_donation_phone_chk = false;
  // 무료 사용자 체크 Cooper Add
  if (freeChk == "Y") {
    // 부가서비스 가능여부 체크
    if ($("#free_use").is(":checked") == false) {
      if (msg_dp == false) {
        msg_dp = true;
        $(".ad_layer3").lightbox_me({
          centered: true,
          onLoad: function () {},
        });

        return;
      }
    }
    // 기부폰 사용자 체크
    $("input[name=go_num]:checked").each(function () {
      // 자기자신폰에 체크되어있는지 체크 2016-04-04
      if (election_myphone == $(this).val()) {
        free_donation_phone_chk = true;
      }
    });
  }
  if (
    !document.getElementsByName("group_num")[0].value &&
    !document.getElementsByName("num")[0].value
  ) {
    alert("발송번호가 없습니다.");
    return false;
  }
  var go_num_name_arr = document.getElementsByName("go_num");
  var go_num_arr = [];
  var go_user_cnt_name_arr = document.getElementsByName("go_user_cnt");
  var go_user_cnt_arr = [];
  var go_max_cnt_name_arr = document.getElementsByName("go_max_cnt");
  var go_max_cnt_arr = [];
  var go_memo2_name_arr = document.getElementsByName("go_memo2");
  var go_memo2_arr = [];
  var go_cnt1_name_arr = document.getElementsByName("go_cnt1");
  var go_cnt1_arr = [];
  var go_cnt2_name_arr = document.getElementsByName("go_cnt2");
  var go_cnt2_arr = [];
  var go_remain_name_arr = document.getElementsByName("go_remain_cnt");
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
  if (!go_num_arr.length) {
    alert("발송가능한 휴대폰을 선택해주세요.");
    go_num_name_arr[0].focus();
    return;
  }
  var type_s = "";
  var type_arr = document.getElementsByName("type");
  for (var i = 0; i < type_arr.length; i++) {
    if (type_arr[i].checked) type_s = type_arr[i].value;
  }
  if (type_s == "") {
    alert("발송종류 선택해주세요.");
    document.getElementsByName("type")[0].focus();
    return false;
  }
  var save_mms_s = "";
  if (frm.save_mms.checked) save_mms_s = "ok";
  var deny_wushi_0_s = "";
  var deny_wushi_1_s = "";
  var deny_wushi_2_s = "";
  var deny_wushi_3_s = "";
  var deny_msg_s = "";
  var ssh_check_s = "";
  var ssh_check2_s = "";
  var ssh_check3_s = "";
  if (document.getElementsByName("deny_wushi[]")[0].checked)
    deny_wushi_0_s = "ok";
  if (document.getElementsByName("deny_wushi[]")[1].checked)
    deny_wushi_1_s = "ok";
  if (document.getElementsByName("deny_wushi[]")[2].checked)
    deny_wushi_2_s = "ok";
  if (document.getElementsByName("deny_wushi[]")[3].checked)
    deny_wushi_3_s = "ok";
  if (document.getElementsByName("deny_msg")[0].checked) deny_msg_s = "ok";
  if (document.getElementsByName("ssh_check")[0].checked) ssh_check_s = "ok";
  if (document.getElementsByName("ssh_check")[1].checked) ssh_check2_s = "ok";
  if (document.getElementsByName("ssh_check")[2].checked) ssh_check3_s = "ok";
  var txt_s = "";
  if (document.getElementsByName("fs_msg")[0].checked)
    txt_s =
      frm.txt.value +
      document.getElementsByName("onebook_url")[0].value +
      "\n\n" +
      document.getElementsByName("fs_txt")[0].value;
  else
    txt_s = frm.txt.value + document.getElementsByName("onebook_url")[0].value;
  if (!wrestSubmit(frm)) return false;

  // ============= Cooper Add 발송초과 체크 =============
  var chk_max = false;
  var chk_send = false;
  var chk_daily_msg = false;
  var chk_limit = false;
  var chk_max_msg = "";
  var chk_send_msg = "";
  var daily_msg = "";
  var election_myself = false; // 선거용 본인폰
  var do_send_cnt = 0; // 발송 가능폰수
  $("input[name=go_num]:checked").each(function () {
    if ($(this).attr("data-max-cnt") * 1 <= $(this).attr("data-send-cnt") * 1) {
      chk_max_msg += "[" + $(this).val() + "]";
      chk_max = true;
    }
    // 자기자신폰에 체크되어있는지 체크 2016-04-04
    if (election_myphone == $(this).val()) {
      election_myself = true;
    }
    var row_cnt =
      $(this).attr("data-max-cnt") * 1 - $(this).attr("data-send-cnt") * 1;
    var send_cnt = $(".num_check_c:eq(1)").html() * 1 - row_cnt;
    if (row_cnt <= 0) row_cnt = 0;
    // 수신처 잔여랑 체크
    // 본인 수신처 무제한의 경우 예외처리
    if (election_yn == true && election_myphone == $(this).val()) {
    } else {
      if ($(".num_check_c:eq(1)").html() * 1 > $(this).data("user_cnt")) {
        chk_send_msg +=
          "[" +
          $(this).attr("data-name") +
          "/" +
          $(this).val() +
          "]의  발송 가능 건수는 " +
          $(this).data("user_cnt") +
          "건입니다.\n" +
          $(".num_check_c:eq(1)").html() * 1 +
          "(실제발송)건 중에서 발송폰의 잔여수신처 " +
          $(this).data("user_cnt") +
          "건만 발송됩됩니다\n";
        chk_send = true;
      }
      if ($(".num_check_c:eq(1)").html() * 1 > row_cnt) {
        chk_send_msg +=
          "[" +
          $(this).attr("data-name") +
          "/" +
          $(this).val() +
          "]의  이달 수신처 잔여량은 " +
          row_cnt +
          "건입니다.\n" +
          $(".num_check_c:eq(1)").html() * 1 +
          "(실제발송)건 중에서 발송폰의 잔여수신처 " +
          row_cnt +
          "건만 발송되며\n이달 수신처를 초과한 발송폰은 기존수신처에만 발송됩니다\n";
        chk_send = true;
        //"발송할 휴대폰의 이달 수신처 잔여량은 00건입니다
        //00(실제발송건)건 중에서 발송폰의 잔여수신처 00건만 발송됩니다.
        //이달 수신처가 초과한 경우에는 기존수신처에만 발송됩니다"
        if (row_cnt <= 0) {
          chk_limit = true;
        }
      }
    }
  });
  // 일일 발송 건수 초과
  if (election_myself == true && election_yn == true) {
    if ($(".num_check_c:eq(1)").html() * 1 > election_cnt * 1) {
      //alert('발송가능한 건수 '+(election_cnt *1)+'를 초과했습니다. 다시설정해주세요');
      //return fasle;
    }
  } else {
    //if(($('.num_check_c:eq(1)').html()*1) > ($('.send_sj_c').html() *1)) {
    //    alert('발송가능한 건수 '+($('.send_sj_c').html() *1)+'를 초과했습니다. 다시설정해주세요');
    //    return fasle;
    //}
  }
  // 월별 수신처 초과
  if (chk_max == true) {
    chk_max_msg =
      "수신처 제한으로 발송이 안됩니다.\n 휴대폰등록 페이지를 확인해주세요";
    alert(chk_max_msg);
    return false;
  }

  if (chk_send == true) {
    //alert(chk_send_msg);
    //return;
  }
  // ============= Cooper Add 발송초과 체크=============
  var send_msg = "";
  var total_phone_cnt = $("input[name=go_num]:checked").length; // 발송 가능한 발송폰
  var error_phone = $("#recv_over").val(); // 수신처 초과 발송폰
  var send_possible_cnt = $(".send_sj_c")
    .html()
    .replace(",", "")
    .replace(",", ""); // 발송 가능한 문자건
  var send_total_cnt = $(".num_check_c:eq(1)")
    .html()
    .replace(",", "")
    .replace(",", ""); // 발송 요청한 문자건

  //alert(send_total_cnt+"^"+send_possible_cnt);
  // 수신처 우선발송
  var add_msg = "";
  if (error_phone == "") error_phone = "없음";
  else add_msg = "수신처 초과 발송폰이 있습니다. 확인이 필요합니다.\n";
  send_msg += "다음과 같이 문자를 발송합니다.\n";
  send_msg += "발송 요청한 문자건 : " + send_total_cnt + "건\n";
  send_msg += "발송 가능한 문자건 : " + send_possible_cnt + "건\n";
  send_msg += "발송 가능한 발송폰 : " + total_phone_cnt + "개\n\n";
  send_msg += "수신처 초과 발송폰 : " + error_phone + "\n";
  send_msg += "* 이달 수신처가 초과될 경우 실제발송량이 줄어듭니다.\n\n";
  if ($("#rday").val() != "") {
    send_msg +=
      "[발송예정일 : " +
      $("#rday").val().substring(0, 4) +
      "년 " +
      $("#rday").val().substring(5, 7) +
      "월 " +
      $("#rday").val().substring(8, 10) +
      "일 ]\n\n";
  }
  if (send_total_cnt == 0) {
    send_msg += "발송 요청한 문자건이 0 개 입니다. 확인이 필요합니다.\n";
  }
  if (add_msg != "") {
    send_msg += add_msg;
  }
  send_msg += "발송하시겠습니까?";
  if (confirm(send_msg)) {
    $($(".loading_div")[0]).show();
    close_div(open_group_create);
    if ($("#is_send_mail").val() == "Y") {
      //메일발송을 진행한다.
      $.ajax({
        type: "POST",
        url: "ajax/mail_msg_func.php",
        data: {
          mode: "send",
          mail_addr: $("#txt_mail_address").val(),
          mail_title: $("#txt_mail_title").val(),
          mail_content: $("#txt_mail_content").val(),
          attach_file: $("#link_attachment").val(),
          reserv_day: frm.rday.value,
          reserv_time: frm.htime.value + ":" + frm.mtime.value + ":00",
          group_nums: document.getElementsByName("group_num")[0].value,
        },
        success: function (data) {},
      });
    }
    $.ajax({
      type: "POST",
      url: "ajax/sendmmsPrc.2020.php",
      data: {
        send_title: frm.title.value,
        send_num: document.getElementsByName("num")[0].value,
        send_txt: txt_s,
        send_rday: frm.rday.value,
        send_htime: frm.htime.value,
        send_mtime: frm.mtime.value,
        send_type: type_s,
        send_chk: document.getElementsByName("group_num")[0].value,
        send_img: frm.upimage_str.value,
        send_img1: frm.upimage_str1.value,
        send_img2: frm.upimage_str2.value,
        send_save_mms: save_mms_s,
        send_agree_msg: $("#agreement_yn").is(":checked") == true ? "Y" : "N",
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
        send_go_cnt2: go_cnt2_arr,
        send_go_remain_cnt: go_remain_arr,
        send_cnt: $(".num_check_c:eq(1)").html() * 1,
        free_use: free_use,
      },
      success: function (data) {
        $($(".loading_div")[0]).hide();
        var arrData = data.split("|");
        //parent.alert('발송시도: ' + arrData[0] + '\n\n발송시도실패: ' + arrData[1] + '\n\n전송성공건수: ' + arrData[2] + '\n\n전송실패건수(수신처제한): ' + arrData[3]);
        var msg = "";
        //msg +='발송시도: ' + arrData[0];
        //msg +='\n\n발송시도실패: ' + arrData[1] ;
        //msg +='\n\n전송성공건수: ' + arrData[2] ;
        //msg +='\n\n전송실패건수(수신처제한): ' + arrData[3];
        //msg +='\n\n전송실패건수(수신거부): ' + arrData[4];
        //msg +='\n\n전송실패건수(기타번호): ' + arrData[5];
        //msg +='\n\n전송실패건수(없는번호): ' + arrData[6];
        //msg +='\n\n전송실패건수(수신불가): ' + arrData[7];
        msg += "발송시도폰 : " + arrData[0];
        msg += "\n\n발송시도실패폰 : " + arrData[1];
        msg += "\n\n문자전송건 : " + arrData[2] + " 건";
        if (document.getElementsByName("group_num")[0].value.indexOf("-") == -1)
          msg += "\n\n문자전송실패건 : " + arrData[3] + " 건";

        parent.alert(msg);
        parent.location.reload();
      },
    });
  }
}

//야간 제한 시간입력
function limitNight() {
  var closingTime = $("#close").val();

  if (isNaN(closingTime)) {
    $("#close").val("");
  } else {
    closingTime = parseInt(closingTime);
    if (closingTime < 8) {
      alert("오전 8시 이후부터 발송 가능합니다.");
      $("#close").val("8");
    }
    if (closingTime > $("#close").val() * 1) {
      alert($("#close").val() * 1 + "시 이전까지 발송 가능합니다.");
      $("#close").val($("#close").val() * 1);
    }
  }
}

//주소록 다운
function excel_down_p_group(pno) {
  $($(".loading_div")[0]).show();
  $($(".loading_div")[0]).css("z-index", 10000);
  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1; //January is 0!
  var yy = today.getFullYear().toString().substr(2, 2);
  if (dd < 10) {
    dd = "0" + dd;
  }
  if (mm < 10) {
    mm = "0" + mm;
  }

  $.ajax({
    type: "POST",
    dataType: "json",
    url: "ajax/ajax_session.php",
    data: {
      group_create_ok: "ok",
      group_create_ok_nums: pno,
      group_create_ok_name: pno.substr(3, 8) + "_" + "" + mm + "" + dd,
    },
    success: function (data) {
      $($(".loading_div")[0]).hide();
      parent.excel_down("excel_down/excel_down.php?down_type=1", data.idx);
    },
  });
}

//주소록 다운
function excel_down_c_group(pno) {
  if (confirm("소유자 휴대폰에 있는 DB를 가져오시겠습니까?")) {
    $($(".loading_div")[0]).show();
    $($(".loading_div")[0]).css("z-index", 10000);
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yy = today.getFullYear().toString().substr(2, 2);
    if (dd < 10) {
      dd = "0" + dd;
    }
    if (mm < 10) {
      mm = "0" + mm;
    }

    $.ajax({
      type: "POST",
      url: "ajax/ajax_session.php",
      data: {
        group_create_ok_: "ok",
        group_create_ok_nums: pno,
        group_create_ok_name: pno + "_" + "" + mm + "" + dd,
      },
      success: function (data) {
        $($(".loading_div")[0]).hide();
        //parent.excel_down('excel_down/excel_down.php?down_type=1',data.idx);
      },
    });
  }
}
