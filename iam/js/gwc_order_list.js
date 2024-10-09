$(function () {
    $("input[name=report_title]").on('click', function(){
        if($(this).attr('id') == "other"){
            $("#other_detail").show();
        }
        else{
            $("#other_detail").hide();
        }
    })

    $("input[name=report_title1]").on('click', function(){
        if($(this).attr('id') == "other1"){
            $("#other_detail1").show();
        }
        else{
            $("#other_detail1").hide();
        }
    })

    $("#report_other_msg").on('keyup', function(){
        var str = $(this).val();
        var len = str.length;
        var rest = 250 - len;
        $("#other_desc_letter").text(len + '/' + rest);
    })

    $("#report_other_msg1").on('keyup', function(){
        var str = $(this).val();
        var len = str.length;
        var rest = 250 - len;
        $("#other_desc_letter1").text(len + '/' + rest);
    })
});

function close_modal(){
    $("#req_detail_modal").modal('hide');
    $("#edit_order_modal").modal("hide");
    $("#edit_order_modal1").modal("hide");
}

function show_prod_page(con_idx, host){
    var navCase = navigator.userAgent.toLocaleLowerCase();
    if(navCase.search("android") > -1){
        var height = $(window).height();
        console.log(height);
        // return;
        var url = "http://"+host+"/iam/contents_gwc.php?contents_idx="+con_idx+"&gwc=Y&mobile=Y&order=Y";
        $("#contents_page").html("<iframe id='ALLAT_MOBILE_FRAME' name='ALLAT_MOBILE_FRAME' src='"+url+"' frameborder=0 width=100%px height=100%px scrolling=no></iframe>");
        document.getElementById("contents_page").style.width="100%";
        document.getElementById("contents_page").style.height=height + 'px';
        document.getElementById("contents_page").style.left = 0 + "px";
        document.getElementById("contents_page").style.top = 0 + "px";
        document.getElementById("contents_page").style.display = "block";
        document.getElementById("container").style.display = "none";
        $('body,html').animate({
                scrollTop: 0 ,
            }, 100
        );
    }
    else{
        window.open('/iam/contents_gwc.php?contents_idx='+con_idx+'&gwc=Y', '_blank');
    }
}


function req_report(val){
    var type = $("input[name=report_title"+val+"]:checked").val();
    var edit_type = $("input[id=edit_type"+val+"]").val();
    var od_id = $("input[id=od_id"+val+"]").val();
    if(type == undefined){
        alert('해당 항목 체크를 꼭 하시거나, 추가 설명을 입력하셔야 합니다.');
        return;
    }
    else{
        if(type == "8"){
            var title = $("textarea[id=report_other_msg"+val+"]").val();
            if(title == ''){
                alert('해당 항목 체크를 꼭 하시거나, 추가 설명을 입력하셔야 합니다.');
                return;
            }
        }
        else{
            var title = type;
        }
        $.ajax({
            type:"POST",
            url:'ajax/product_mng.php',
            data:{mode:'req_edit_order', order_id:od_id, title:title, edit_type:edit_type},
            dataType:'json',
            success:function(data){
                $("#ajax-loading").hide();
                alert('신청되었습니다.');
                location.reload();
            }
        })
    }
}

function edit_order(type, order_id){
    if(type == "1"){
        $("#edit_type").val(type);
        $("#od_id").val(order_id);
    }
    else{
        $("#edit_type1").val(type);
        $("#od_id1").val(order_id);
    }
    $.ajax({
        type:"POST",
        url:'ajax/product_mng.php',
        data:{
            mode:'check_prod_order_date',
            order_id:order_id
        },
        dataType:'json',
        success:function(data){
            $("#ajax-loading").hide();
            if(data.result == "0"){
                $("#req_detail_modal").modal('show');
                return;
            }
            else{
                if(type == "1"){
                    $("#edit_order_modal").modal("show");
                }
                else{
                    $("#edit_order_modal1").modal("show");
                }
            }
            let cancel_type;

            if (type == 1) {
                cancel_type = '주문취소'
            } else if (type == 2) {
                cancel_type = '반품신청'
            } else {
                cancel_type = '교환신청'
            }
            $.ajax({
                type: "POST",
                url: "/makeData_item_point.php",
                data: {
                    payMethod: "order_change",
                    order_id : order_id,
                    type : cancel_type
                },
                dataType: 'json',
                success: function (data) {
                    // alert("문자발송되었습니다.");
                    location.reload();
                },
            });
        }
    })


}

function fsubmit_check(f) {
    if ($("input[name^=ct_chk]:checked").length < 1) {
        alert("구매하실 상품을 하나이상 선택해 주십시오.");
        return false;
    }

    return true;
}

function gotoDetail() {
    var link = 'http://' + arguments[0] + '/iam/gwc_order_pay.php?order_id=' + arguments[1] + '&admin=M&mem_id=' + arguments[10] + '&contents_idx=' + arguments[2] + '&contents_cnt=' + arguments[3] + '&contents_price=' + arguments[4] + '&salary_price=' +  + arguments[5] + '&seller_id='  + arguments[6] + '&order_option=' + arguments[7] + '&use_point_val=' + arguments[8] + '&payMethod=' + arguments[9] + '&gwc_order_option_content=' + arguments[11];
    if(arguments[12].indexOf("gallery") == 0)
        link += "&shop=gallery";
    location.href = link;
}
