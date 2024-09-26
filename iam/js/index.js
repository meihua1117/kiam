function changeNumber(evt, type, id, opt_price) {
    let value = $('#' + id)[0].value;
    let gwc_ori_price = $('#gwc_ori_price').val();
    let conts_cnt = $("#conts_cnt").val();

    if (type == 'minus') {
        if (value == 1) {
            alert('최소 구매수량은 1 이상 입니다.');
        } else {
            $('#' + id)[0].value = parseInt(value) - 1;
            $('#gwc_conts_price').text(number_format(parseInt($('#gwc_conts_price')[0].innerText.replace(/,/g,'')) - parseInt(gwc_ori_price) - parseInt(opt_price)));
            $("#conts_cnt").val(parseInt(conts_cnt) -  1);
        }
    } else {
        // gwc_ori_price
        // gwc_conts_price
        if (value == 49) {
            alert('최대 구매수량은 50 이하 입니다.');
        } else {
            $('#' + id)[0].value = parseInt(value) + 1;
            $('#gwc_conts_price').text(number_format(parseInt($('#gwc_conts_price')[0].innerText.replace(/,/g,'')) + parseInt(gwc_ori_price) + parseInt(opt_price)));
            $("#conts_cnt").val(parseInt(conts_cnt) +  1);
        }
    }
}

function remove_option(id, txt, opt_price) {
    let gwc_ori_price = $('#gwc_ori_price').val();
    let conts_cnt = $("#conts_cnt").val();
    if (option_array.length == 1) {
        $('#gwc_conts_price').text(number_format(gwc_ori_price));
        $("#conts_cnt").val(1);
    } else {
        $('#gwc_conts_price').text(
            number_format(
                parseInt($('#gwc_conts_price')[0].innerText.replace(/,/g,'')) - (parseInt(gwc_ori_price) * parseInt($('#' + id).val()) + (parseInt(opt_price) * parseInt($('#' + id).val())))
            )
        );
        $("#conts_cnt").val(parseInt(conts_cnt) - parseInt($('#'+id).val()));
    }
    for (let i = 0; i < option_array.length; i++) {
        if (option_array[i] == txt) {
            option_array.splice(i, i + 1);
        }
    }
    $('#' + id).parent().parent().remove();
}

function get_sms()   {
    if($('input[name=seller_mem_phone]').val() == "" ) {
        alert('인증받으실 휴대폰번호를 입력해주세요.')
        return;
    }
    if(($('input[name=seller_mem_phone]').val().substring(0,3)) != '010' && ($('input[name=seller_mem_phone]').val().substring(0,3)) != '011' && ($('input[name=seller_mem_phone]').val().substring(0,3)) != '016' && ($('input[name=seller_mem_phone]').val().substring(0,3)) != '017' && ($('input[name=seller_mem_phone]').val().substring(0,3)) != '018' && ($('input[name=seller_mem_phone]').val().substring(0,3)) != '019') {
        alert('전화번호 첫번째 자리는 010, 011, 016, 017, 018, 019만 가능합니다.')
        return;
    }

    if(($('input[name=seller_mem_phone]').val()).length <10 || ($('input[name=seller_mem_phone]').val()).length > 12) {
        alert('인증받으실 전화번호를 확인해주세요.')
        return;
    }

    $.ajax({
        type:"POST",
        url:"/ajax/join.proc.php",
        cache: false,
        dataType:"json",
        data:{
            mode:"send_sms",
            rphone:$('input[name=seller_mem_phone]').val(),
            test:""
        },
        success:function(data){
            if(data.result == "success") {
                $('#check_rnum').val("Y");
            } else
                $('#check_rnum').val("");

            alert(data.msg);
        }
    })
}

function chk_sms()   {
    $.ajax({
        type:"POST",
        url:"/ajax/join.proc.php",
        cache: false,
        dataType:"json",
        data:{
            mode:"check_sms",
            rphone:$('input[name=seller_mem_phone]').val(),
            rnum : $('#seller_rnum').val()
        },
        success:function(data){
            if(data.result == "success") {
                $('#check_rnum').val("Y");
                // $('#check_sms').html('<img src="/images/check.gif"> 인증되었습니다.</p>');
            } else {
                $('#check_rnum').val("");
                $('#check_sms').html('');
            }

            alert(data.msg);
        }
    })
}


