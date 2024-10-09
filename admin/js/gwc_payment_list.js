//주소록 다운
function excel_down_(){
    $("#excel_down_form").submit();
    return false;
}

function payment_save(fm) {
    var mem_id = fm.mem_id.value;
    if(mem_id != 'sungmheo'){
        if(confirm('상태를 변경하시겠습니까?')) {
            $(fm).submit();
            return false;
        }
    }
}
function addcomma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}
function deleteRow(no) {
    if(confirm('삭제하시겠습니까?')) {
        $.ajax({
            type:"POST",
            url:"/admin/ajax/payment_delete.php",
            data:{
                no:no
            },
            success:function(data){
                alert('정확히 삭제되었습니다.');
                location.reload();
            }
        })
    }
}

function deleteMultiRow() {
    if(confirm('삭제하시겠습니까?')) {
        var check_array = $("#example1").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });
        $.ajax({
            type:"POST",
            url:"/admin/ajax/gwc_order_save.php",
            data:{
                type:"delete_list",
                no:no_array.toString()
            },
            success:function(data){
                alert('정확히 삭제되었습니다.');
                location.reload();
            }
        });
    }
}

$(function(){
    $('.check').on("click",function(){
        if($(this).prop("id") == "check_all_member"){
            if($(this).prop("checked"))
                $('.check').prop("checked",true);
            else
                $('.check').prop("checked",false);
        }else if($(this).prop("id") == "check_one_member"){
            if(!$(this).prop("checked"))
                $('#check_all_member').prop("checked",false);
        }
    });
    $('.month_count').on("change",function(){
        var obj = $(this);
        $.ajax({
            type:"POST",
            url:"ajax/payment_save.php",
            dataType : "json",
            data:{
                type : "end_date",
                no : $(this).data("no"),
                month : $(this).val()
            },
            success:function(data){
                obj.parents("tr").find("span[id=end_date]").html(data.end_date);
            }
        });
    });
    $('.onestep2yak').on("change",function(){
        var yak = $(this).val();
        $.ajax({
            type:"POST",
            url:"ajax/payment_save.php",
            dataType : "json",
            data:{
                type : "onestep2_update",
                no : $(this).data("no"),
                yak : yak
            },
            success:function(data){
                location.reload();
            }
        });
    });
    $('select[name=yutong]').on('change', function(){
        console.log($(this).val());
        if($(this).val() == 1){
            $("#provider_sel").attr('style', 'display:inline-block');
        }
        else{
            $("#provider_sel").attr('style', 'display:none');
        }
    })
});

function save_delivery(id){
    var delivery = $("select[name=delivery_type_"+id+"]").val();
    var delivery_state = $("select[name=delivery_state_"+id+"]").val();
    var delivery_no = $("input[name=delivery_no_"+id+"]").val();
    $.ajax({
        type:"POST",
        url:"ajax/gwc_order_save.php",
        dataType : "json",
        data:{
            type : "delivery_save",
            delivery : delivery,
            delivery_no : delivery_no,
            delivery_state : delivery_state,
            id : id
        },
        success:function(data){
            location.reload();
        }
    });
}

function show_delivery_link(delivery_id){
    if(delivery_id == ''){
        alert('배송회사를 선택해 주세요.');
        return;
    }
    $.ajax({
        type:"POST",
        url:"ajax/gwc_order_save.php",
        dataType : "json",
        data:{
            type : "get_delivery_link",
            delivery_id : delivery_id
        },
        success:function(data){
            window.open(data.link, '_blank');
        }
    });
}

function show_order_page(link){
    window.open(link, '_blank');
}

function show_detail_prod(str){
    $("#state_detail").text(str);
    $("#show_paper_comment").modal('show');
}

function onShowOrderDetailModal(phone,email,address) {
    $('.order_phone')[0].innerText = phone;
    $('.order_email')[0].innerText = email;
    $('.order_address')[0].innerText = address;
    $("#orderDetailModal").modal('show');
}

function onShowDeliverDetailModal(phone,email,address, account) {
    $('.deliver_phone')[0].innerText = phone;
    $('.deliver_email')[0].innerText = email;
    $('.deliver_address')[0].innerText = address;
    $('.deliver_account')[0].innerText = account;
    $("#deliverDetailModal").modal('show');
}