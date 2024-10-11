<?php 
include "inc/header.inc.php";
if($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/iam/';</script>";
}
$sql_serch=" (r.seller_id ='{$_SESSION['iam_member_id']}' and r.point_val=0) or (r.point_val=1 and r.buyer_id ='{$_SESSION['iam_member_id']}' and r.site is not null and r.type='servicebuy') ";
if($_REQUEST[search_date])
{
    if($_REQUEST[rday1])
    {
        $start_time=strtotime($_REQUEST[rday1]);
        $sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
    }
    if($_REQUEST[rday2])
    {
        $end_time=strtotime($_REQUEST[rday2]);
        $sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
    }
}
if($_REQUEST[lms_text])
{
    $sql_serch.=" and {$_REQUEST[lms_select]} = '{$_REQUEST[lms_text]}' ";
}
$sql="select count(r.no) as cnt from Gn_Item_Pay_Result r where $sql_serch ";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($result);
$intRowCount=$row['cnt'];
if (!$_POST['lno'])
    $intPageSize =20;
else
    $intPageSize = $_POST['lno'];
if($_POST['page'])
{
    $page=(int)$_POST['page'];
    $sort_no=$intRowCount-($intPageSize*$page-$intPageSize);
}
else
{
    $page=1;
    $sort_no=$intRowCount;
}
if($_POST['page2'])
    $page2=(int)$_POST['page2'];
else
    $page2=1;
$int=($page-1)*$intPageSize;
if($_REQUEST['order_status'])
    $order_status=$_REQUEST['order_status'];
else
    $order_status="desc";
if($_REQUEST['order_name'])
    $order_name=$_REQUEST['order_name'];
else
    $order_name="no";
$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
$sql="select r.*,p.regdate as jongsan_date,p.balance_yn
      from Gn_Item_Pay_Result r left join Gn_Item_Pay_Result_Balance p on r.no = p.pay_no
      where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
?>
<style>
.desc li {
    margin-bottom: 5px;
    font-size: 12px;
    line-height: 18px;
}    
.input-wrap a {
    float: right;
    width: 65px;
    display: block;
    margin-left: 5px;
    padding: 7px 5px;
    font-size: 11px;
    color: #fff;
    line-height: 14px;
    background-color: #ccc;
    text-align: center;
}
.check-wrap .check ~ label:before {
    content: '';
    position: absolute;
    top: 3px;
    left: 0;
    width: 18px;
    height: 18px;
    background-color: #fff;
    border: 1px solid #ccc;
}
.check-wrap .check ~ label {
    position: relative;
    display: inline-block;
    padding-left: 25px;
    line-height: 24px;
}
.check-wrap .check:checked ~ label:after { content: '\f00c'; position: absolute; top: 1px; left: 2px; color: #fff; font-family: 'Fontawesome'; font-size: 13px; }
.check-wrap .check:checked ~ label:before { background-color: #ff0066; border-color: #ff0066; }
input[type="radio"] {
    cursor: default;
    -webkit-appearance: radio;
    box-sizing: border-box;
    margin: 3px 3px 3px 4px;
    padding: initial;
    border: initial;
}
.container {
    background-color: #fff;
    -webkit-box-shadow: 0 0 0 1px rgba(0,0,0,0.05), 0 0 5px 0 rgba(0,0,0,0.1);
    padding: 0;
}
td {
    font-size: 11px !important;
    vertical-align: middle;
}
input:checked + .slider {
    background-color: #2196F3;
}
.slider.round {
    border-radius: 34px;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}
input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}
.slider.round:before {
    border-radius: 50%;
}
.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}
</style>
<link href='/css/main.css' rel='stylesheet' type='text/css'/>
<link href='/css/responsive.css' rel='stylesheet' type='text/css'/>
<main id="register" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
    <div class="container">
        <div class="inner-wrap">
                    <h2 class="title"></h2>
                    <div class="mypage_menu">
                        <div style="display:flex;float: right">
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_receive&modal=Y')" title = "<?=$MENU['IAM_MENU']['M7_TITLE'];?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">콘수신</p>
                                <label class="label label-sm" id = "share_recv_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_send&modal=Y')" title = "<?=$MENU['IAM_MENU']['M8_TITLE'];?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">콘전송</p>
                                <label class="label label-sm" id = "share_send_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=unread_post')" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">댓글수신</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="/iam/mypage_post_lock.php" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">댓글차단해지</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=request_list')" title = "<?='신청알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">이벤트신청</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                        </div>
                        <div style="display:flex;float: right;">
                            <?if($_SESSION['iam_member_subadmin_id'] == $_SESSION['iam_member_id']){?>
                            <a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&box=send&modal=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공지전송</p>
                                <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&modal=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공지수신</p>
                                <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <?}else{?>
                            <a class="btn  btn-link" title = "<?='공지알림';?>" href="javascript:iam_mystory('cur_win=unread_notice')" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공지</p>
                                <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <?}?>
                            <?if($is_pay_version){?>
                            <a class="btn  btn-link" title = "" href="/iam/mypage_refer.php" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">추천</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "" href="/iam/mypage_payment.php" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">결제</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "" href="/iam/mypage_payment_item.php" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:#99cc00">판매</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <?}?>
                            <?if($member_iam[service_type] < 2){
                                $report_link = "/iam/mypage_report_list.php";
                            }else{
                                $report_link = "/iam/mypage_report.php";
                            }
                            ?>
                            <a class="btn  btn-link" title = "" href="<?=$report_link?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">리포트</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "" href="/?cur_win=unread_notice&req_provide=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공급사신청</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                        </div>
                    </div>
                    <br>
                    <form name="pay_form" action="" method="post" class="my_pay" enctype="multipart/form-data">
                        <input type="hidden" name="order_name" value="<?=$order_name?>"  />
                        <input type="hidden" name="order_status" value="<?=$order_status?>"/>
                        <input type="hidden" name="page" value="<?=$page?>" />
                        <input type="hidden" name="page2" value="<?=$page2?>" />
                        <div style="text-align: center;margin-top: 70px;">
                            <h2 class="title">상품 판매 내역</h2>
                        </div>
                        <br>
                        <div class="sub_4_1_t7">
                            <div style="float:left;">
                                <select name="search_date">
                                    <option value="">전체</option>
                                    <option value="r.pay_date" <?=$_REQUEST[search_date]=='pay_date'?"selected":"";?>>결제일</option>
                                    <option value="p.regdate" <?=$_REQUEST[search_date]=='regdate'?"selected":"";?>>정산일</option>
                                </select>
                                <input type="date" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>"/> ~
                                <input type="date" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>"/>
                            </div>
                            <div style="float:right;">
                                <img src="/images/sub_button_703.jpg" onclick="pay_form.submit();" style="height: 30px" />
                            </div>
                            <div style="float:right;">
                                <select name="lms_select">
                                    <option value="">선택</option>
                                    <?
                                    $select_lms_arr=array("mem_name"=>"회원명","p.mem_id"=>"아이디","item_name"=>"상품명");
                                    foreach($select_lms_arr as $key=>$v)
                                    {
                                        $selected=$_REQUEST[lms_select]==$key?"selected":"";
                                        ?>
                                        <option value="<?=$key?>" <?=$selected?>><?=$v?></option>
                                    <?}?>
                                </select>
                                <input type="text" name="lms_text" value="<?=$_REQUEST[lms_text]?>" />
                            </div>
                            <p style="clear:both;"></p>
                        </div>
                        <div>
                            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width:5%;">번호</td>
                                    <td style="width:10%;">결제방식</td>
                                    <td style="width:13%;">상품명</td>
                                    <td style="width:12%;">이름/아이디</td>
                                    <td style="width:10%;">연락처</td>
                                    <td style="width:10%;">결제일</td>
                                    <td style="width:10%;">결제금액</td>
                                    <td style="width:10%;">구매확인/<br>확인일시</td>
                                    <td style="width:10%;">판매확인/<br>확인일시</td>
                                </tr>
                                <?
                                if($intRowCount)
                                {
                                    while($row=mysqli_fetch_array($result))
                                    {
                                        if($row[point_val] == 0){
                                            $method = "카드결제";
                                            $sql_mem_data = "select mem_id, mem_name, mem_phone from Gn_Member where mem_id='{$row['buyer_id']}'";
                                            $res_mem_data = mysqli_query($self_con,$sql_mem_data);
                                            $row_mem_data = mysqli_fetch_array($res_mem_data);
                                        }
                                        else{
                                            $method = "포인트결제";
                                        $sql_mem_data = "select mem_id, mem_name, mem_phone from Gn_Member where mem_id='{$row['seller_id']}'";
                                        $res_mem_data = mysqli_query($self_con,$sql_mem_data);
                                        $row_mem_data = mysqli_fetch_array($res_mem_data);
                                        }

                                        $buyer_no = $row['no'] * 1 - 1;

                                        if($row[pay_method] == "CARD"){
                                            $card = "card";
                                        }
                                        else{
                                            $card = "point";
                                        }
                                        if($row['gwc_cont_pay']){
                                            $sql_total_price = "select sum(item_price) from Gn_Item_Pay_Result where item_name='{$row[item_name]}' and order_number='{$row[order_number]}' and pay_date='{$row[pay_date]}'";
                                            $res_total_price = mysqli_query($self_con,$sql_total_price);
                                            $row_total_price = mysqli_fetch_array($res_total_price);
                                            $row['item_price'] = $row_total_price[0];
                                        }
                                        ?>
                                        <tr >
                                            <td><?=$sort_no?></td>
                                            <td><?=$method?></td>
                                            <td style="font-size:11px;"><?=$row[item_name]?></td>
                                            <td><?=$row_mem_data['mem_id']?>/<?=$row_mem_data['mem_name']?></td>
                                            <td><?=$row_mem_data['mem_phone']?></td>
                                            <td style="font-size:11px;"><?=$row[pay_date]?></td>
                                            <?php
                                            if($row[point_val] == 0){
                                            ?>
                                            <td><?=$row['item_price']?> 원</td>
                                            <?}else{?>
                                            <td><?=$row['item_price']?> P</td>
                                            <?}?>
                                            <td style="font-size:11px;">
                                                <?php
                                                if($row['gwc_cont_pay'] == 0){
                                                if($row[apply_buyer_date] == ''){
                                                    echo "확인대기";
                                                }
                                                else{
                                                    echo "확인<br>".$row[apply_buyer_date];
                                                }
                                                }
                                                ?>
                                            </td>
                                            <td style="font-size:11px;">
                                                <?php
                                                if($row['gwc_cont_pay'] == 0){
                                                if($row[apply_seller_date] == ''){
                                                ?>
                                                <label class="switch">
                                                    <input type="checkbox" name="auto_status" id="auto_stauts_<?php echo $row['no'];?>_<?=$card?>" value="<?php echo $row['no']."/".$buyer_no;?>">
                                                    <span class="slider round" name="auto_status" id="auto_stauts_<?php echo $row['no'];?>_<?=$card?>"></span>
                                                </label>
                                            <?}else{?>
                                                <label class="switch">
                                                    <input type="checkbox" name="auto_status" id="auto_stauts_<?php echo $row['no'];?>_<?=$card?>" value="<?php echo $row['no']."/".$buyer_no;?>" checked>
                                                    <span class="slider round" name="auto_status" id="auto_stauts_<?php echo $row['no'];?>_<?=$card?>"></span>
                                                </label><br>
                                                <? echo $row[apply_seller_date];}}?>
                                            </td>
                                            <!-- <td><?=$row['balance_yn'] != ''?$row['item_price'] * $row['pay_percent']/100:0?> 원</td> -->
                                        </tr>
                                        <?
                                        $sort_no--;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="11">
                                            <?
                                            page_f($page,$page2,$intPageCount,"pay_form");
                                            ?>
                                        </td>
                                    </tr>
                                <?
                                }
                                else
                                {
                                    ?>
                                    <tr>
                                        <td colspan="11">
                                            검색된 내용이 없습니다.
                                        </td>
                                    </tr>
                                <?
                                }
                                ?>
                            </table>
                        </div>
                    </form>
                </div>
    </div>
</main><!-- // 컨텐츠 영역 끝 -->
<div id="ajax_div" style="display:none"></div>
<script language="javascript">
function monthly_remove(no) {
    if(confirm('정기결제 해지신청하시겠습니까?')) {
        $.ajax({
            type:"POST",
            url:"/ajax/ajax_add.php",
            data:{
                mode:"monthly",
                no:no
            },
            success:function(data){alert('신청되었습니다.');location.reload();}
        })

    }
}
$("document").ready(function(){
    $(document).ajaxStop(function() {
        $("#ajax-loading").delay(10).hide(1);
    });
    $("#value_region_province").on('change', function(){
        var province = $(this).val();
        $("#value_region_city").html('<option value="">-시/군/구-</option>');
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('location.php', {'type':'cities', 'location':province}, function(res){
            if(res.status == '1') {
                var locations = res.locations;
                var html = '<option value="">-시/군/구-</option>';
                for(var i = 0; i < locations.length; i++) {
                    var location = locations[i];
                    html += '<option value="${location}">${location}</option>';
                }
                $("#value_region_city").html(html);
            }
        }, 'json');
    });

    $("#value_region_city").on('change', function(){
        var city = $(this).val();
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('location.php', {'type':'towns', 'location':city}, function(res){
            if(res.status == '1') {
                var locations = res.locations;
                var html = '<option value="">-읍/면/동-</option>';
                for(var i = 0; i < locations.length; i++) {
                    var location = locations[i];
                    html += '<option value="${location}">${location}</option>';
                }
                $("#value_region_town").html(html);
            }
        }, 'json');
    });

    $("#value_region_town").on('change', function(){
        if($(this).val() != "") {
            var address = $("#value_region_province").val() + " " + $("#value_region_city").val() + " " + $(this).val();
            $("#add1").val(address);
        }
    });

    $(".switch").on('change', function(){
        if($(this).find('input').prop('checked') != true){
            // console.log("clicked");
            $(this).find('input').prop('checked', true);
            return;
        }
        else{
            if(confirm("판매확인을 하시겠습니까?")){
                console.log("ok");
                var idx_type = $(this).find('input').attr('id');
                var data_arr = idx_type.split("_");
                var sell_no = data_arr[2];
                var type = data_arr[3];
                console.log(sell_no, type);
                // return;
                if(type == "point"){
                    $.ajax({
                        type: "POST",
                        url: "/makeData_item_point.php",
                        data: {
                            point_val: 1,
                            mypage: true,
                            mypage_sell: true,
                            db_idx_sell: sell_no
                        },
                        dataType: 'json',
                        success: function (data) {
                            console.log(data);
                            if(data == 1){
                                var buy_no = sell_no * 1 - 1;
                                location.href="ajax/apply_service_con_res.php?mode=sell&residx_buy="+buy_no+"&residx_sell="+sell_no+"&prev=mypage_sell";
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
                            db_idx_sell: sell_no
                        },
                        dataType: 'json',
                        success: function (data) {
                            console.log(data);
                            if(data == 1){
                                location.href="ajax/apply_service_con_res.php?mode=sell_card&residx="+sell_no+"&prev=mypage_sell";
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
function showInfoOut() {
    $('.contents').show();
}
$(function() {
    $('#mem_sex_m').on("click", function() {
        $('#mem_sex_f').prop("checked", false);
    });
    $('#mem_sex_f').on("click", function() {
        $('#mem_sex_m').prop("checked", false);
    });
});
function save_form(frm,modify) {
    if($('#name').val() == "") {
        alert('성명을 입력해 주세요.');
        return;
    }

    if($('#zy').val() == "") {
        alert('소속을 입력해 주세요.');
        return;
    }

    if($('#add1').val() == "") {
        alert('주소를 입력해 주세요.');
        return;
    }

    if($('#email_1').val() == "") {
        alert('이메일을 입력해 주세요.');
        return;
    }
    if($('#email_2').val() == "") {
        alert('이메일을 입력해 주세요.');
        return;
    }
    var msg=modify?"수정하시겠습니까?":"등록하시겠습니까?";
    var form = $('#edit_form')[0];
    var formData = new FormData(form);
    formData.append("profile", $("#profile")[0].files[0])
    console.log(formData);

    if(confirm(msg))
    {
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.member.php",
            processData: false,
            contentType: false,
            data:formData,
            success:function(data){$("#ajax_div").html(data)}
        })
    }
}
function chk_sms()   {
    if($('input[name=mobile_1]').val() == "" || $('input[name=mobile_2]').val() == "" || $('input[name=mobile_3]').val() == "") {
        alert('인증받으실 전화번호를 입력해주세요.')
        return;
    }
    $.ajax({
         type:"POST",
         url:"/ajax/join.proc.php",
         cache: false,
         dataType:"json",
         data:{
             mode:"send_sms",
             rphone:$('input[name=mobile_1]').val()+"-"+$('input[name=mobile_2]').val()+"-"+$('input[name=mobile_3]').val()
             },
         success:function(data){
            if(data.result == "success")
                $('#check_rnum').val("Y");
            else
                 $('#check_rnum').val("");

            alert(data.msg);
            }
    })
}
$(function(){
    $('#checkAll').on("change",function() {
        if($('#checkAll').prop("checked") == true) {
            $("#checkPersonal").prop("checked", true);
            $("#checkTerms").prop("checked", true);
            $("#checkReceive").prop("checked", true);
            $("#checkThirdparty").prop("checked", true);
        } else {
            $("#checkPersonal").prop("checked", false);
            $("#checkTerms").prop("checked", false);
            $("#checkReceive").prop("checked", false);
            $("#checkThirdparty").prop("checked", false);
        }
    })
});
 
function id_check(frm,frm_str) {
	if(!frm.id.value){
		frm.id.focus();
		return
	}
    var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
    if (!pattern.test(frm.id.value)) {
        alert('영문 소문자와 숫자만 사용이 가능합니다.');
	 	frm.id_status.value=''
	    frm.id.value=''
	    frm.id.focus();
	    return;
    }
    $.ajax({
		 type:"POST",
		 url:"/ajax/ajax.php",
		 data:{
            id_che:frm.id.value,
            id_che_form:frm_str
		 },
		 success:function(data){
		    $("#ajax_div").html(data);
		 }
	});
}
function inmail(v,id){
    $("#"+id).val(v);
}
function searchManagerInfo() {
    var winw_pop = window.open('searchManagerInfo.php', 'popup', 'width=624, height=744, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,scrolling=yes');
    if(winw_pop == null) {
        alert('팝업 차단을 해제 해 주세요.');
    } else {
        winw_pop.focus();
    }
}
function change_message(form) {
	if(form.intro_message.value == "") {
		  alert('정보를 입력해주세요.');
		  form.intro_message.focus();
		  return false;
	}
	$.ajax({
		 type:"POST",
		 url:"ajax/ajax.php",
		 data:{
			 mode : "intro_message",
			 intro_message: form.intro_message.value
			 },
		 success:function(data){
		 	$("#ajax_div").html(data);
		 	alert('저장되었습니다.');
		 	}
	});
    return false;
}
function showInfo() {
    if($('#outLayer').css("display") == "none") {
        $('#outLayer').show();
    } else {
        $('#outLayer').hide();
    }
}
function copyHtml(){
    var trb = $.trim($('#sHtml').html());
    var IE=(document.all)?true:false;
    if (IE) {
        if(confirm("이 소스코드를 복사하시겠습니까?")) {
            window.clipboardData.setData("Text", trb);
        } 
    } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
    }
}
//비밀번호 보안등급
function pwd_check(i){
}
//비밀번호 재확인
function pwd_cfm_check(i){
	if($('#pwd_cfm').val() != $('#pwd').val()){
        alert("두번 입력한 비밀번호가 틀립니다.");
        return;
    }
}
function pwd_change(frm,i){
    if($('#pwd_cfm').val() != $('#pwd').val()){
        alert("두번 입력한 비밀번호가 틀립니다.");
        return;
    }
	if(confirm('변경하시겠습니까?')){
		$.ajax({
			 type:"POST",
			 url:"/ajax/ajax_session.php",
			 data:{
					pwd_change_old_pwd:$('#old_pwd').val(),
					pwd_change_new_pwd:$('#pwd').val(),
					pwd_change_status:i
				  },
			 success:function(data){$("#ajax_div").html(data)}
        });
	}		
}
$("#value_region_province").on('change', function(){
    var province = $(this).val();
    $("#value_region_city").html('<option value="">-시/군/구-</option>');
    $("#value_region_town").html('<option value="">-읍/면/동-</option>');
    $("#add1").val("");
    $.post('/location.php', {'type':'cities', 'location':province}, function(res){
        if(res.status == '1') {
            var locations = res.locations;
            var html = '<option value="">-시/군/구-</option>';
            for(var i = 0; i < locations.length; i++) {
                var location = locations[i];
                html += '<option value="${location}">${location}</option>';
            }
            $("#value_region_city").html(html);
        }
    }, 'json');
});

$("#value_region_city").on('change', function(){
    var city = $(this).val();
    $("#value_region_town").html('<option value="">-읍/면/동-</option>');
    $("#add1").val("");
    $.post('/location.php', {'type':'towns', 'location':city}, function(res){
        if(res.status == '1') {
            var locations = res.locations;
            var html = '<option value="">-읍/면/동-</option>';
            for(var i = 0; i < locations.length; i++) {
                var location = locations[i];
                html += '<option value="${location}">${location}</option>';
            }
            $("#value_region_town").html(html);
        }
    }, 'json');
});
$("#value_region_town").on('change', function(){
    if($(this).val() != "") {
        var address = $("#value_region_province").val() + " " + $("#value_region_city").val() + " " + $(this).val();
        $("#mem_addr").val(address);
    }
});
</script>
