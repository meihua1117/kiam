function monthly_remove(no, pay_type) {
    if(pay_type == "year-professional"){
        $.ajax({
            type:"POST",
            url:"/ajax/ajax_add.php",
            dataType:'json',
            data:{
                mode:"get_status",
                no:no
            },
            success:function(data){
                $("#first_date").text(data.date);
                $("#rest_month").text(data.months);
                $("#penalty").text(data.penalty);
                $("#ajax-loading").hide();
                $("#detail_intro_modal").modal('show');
            }
        });

    }
    else{
        if(confirm('정기결제 해지신청하시겠습니까?')) {
            $.ajax({
                type:"POST",
                url:"/ajax/ajax_add.php",
                data:{
                    mode:"monthly",
                    no:no
                },
                success:function(data){alert('해지신청되었습니다.담당자가 처리하면 해지완료가 됩니다.');location.reload();}
            })

        }
    }
}

function show_table(){
    $("#penalty_modal").modal('show');
}

function go_kakao(){
    window.open(
        'https://pf.kakao.com/_jVafC/chat',
        '_blank' // <- This is what makes it open in a new window.
    );
}

function close_table(){
    $("#penalty_modal").modal('hide');
}

$(function(){
    $(".popbutton").click(function(){
        $("#modalwindow").modal("show");
    })

    $(".switch").on('change', function(){
        if($(this).find('input').prop('checked') != true){
            // console.log("clicked");
            $(this).find('input').prop('checked', true);
            return;
        }
        else{
            if(confirm("구매확인을 하시겠습니까?")){
                console.log("ok");
                var idx_type = $(this).find('input').attr('id');
                var data_arr = idx_type.split("_");
                var buy_no = data_arr[2];
                var type = data_arr[3];
                console.log(buy_no, type);
                // return;
                if(type == "point"){
                    $.ajax({
                        type: "POST",
                        url: "/makeData_item_point.php",
                        data: {
                            point_val: 1,
                            mypage: true,
                            mypage_buy: true,
                            db_idx_buy: buy_no
                        },
                        dataType: 'json',
                        success: function (data) {
                            console.log(data);
                            if(data == 1){
                                var sell_no = buy_no * 1 + 1;
                                location.href="ajax/apply_service_con_res.php?mode=buy&residx_buy="+buy_no+"&residx_sell="+sell_no+"&prev=mypage_buy";
                            }
                            return;
                        }
                    });
                }
                else{
                    $.ajax({
                        type: "POST",
                        url: "/makeData_item_service.php",
                        data: {
                            mypage: true,
                            mypage_sell: true,
                            db_idx_sell: buy_no
                        },
                        dataType: 'json',
                        success: function (data) {
                            console.log(data);
                            if(data == 1){
                                location.href="ajax/apply_service_con_res.php?mode=buy_card&residx="+buy_no+"&prev=mypage_buy";
                            }
                            return;
                        }
                    });
                }
            }
            else{
                $(this).find('input').prop('checked', false);
            }
        }
    })
});

$('#platform_payment').on('change',function () {
    let value = this.value;
    if (value == 'all') {
        location.href = 'mypage_payment.php';
    }
});

$('#content_search_date').on('change',function () {
    let value = this.value;
    if (value == 'all') {
        location.href = 'mypage_payment.php';
    }
});
