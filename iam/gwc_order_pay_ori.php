<?php 
include "inc/header.inc.php";
extract($_GET);
if($admin == "Y" || $admin == "M"){
    $mem_id_page = $mem_id;
}else{
    $mem_id_page = $_SESSION[iam_member_id];
}
if($mem_id_page == "") {
    echo "<script>location='/';</script>";
}
$con_idx = array();
$con_title = array();
$con_img = array();
$con_sell_price = array();
$con_cnt = array();
$con_salary = array();
$order_opt = array();
$cont_url = array();
$mid = date("YmdHis").rand(10,99);?>
<script>
    if("<?=$platform?>" != "mobile" && navigator.userAgent.toLocaleLowerCase().search("android") > -1) {
        location.href = "<?=$_SERVER[REQUEST_URI]?>&platform=mobile";
    }
</script>
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<?
if($cart_ids != ''){
    $ids_arr = explode(',', $cart_ids);
    for($i = 0; $i < count($ids_arr); $i++){
        $sql_update = "update Gn_Gwc_Order set pay_order_no='{$mid}' where id='{$ids_arr[$i]}'";
        mysql_query($sql_update);
        $sql_data = "select * from Gn_Gwc_Order where id='{$ids_arr[$i]}'";
        $res_data = mysql_query($sql_data);
        $row_data = mysql_fetch_array($res_data);

        $sql_con = "select contents_title, contents_img, contents_url from Gn_Iam_Contents_Gwc where idx='{$row_data[contents_idx]}'";
        $res_con = mysql_query($sql_con);
        $row_con = mysql_fetch_array($res_con);

        if(strpos($row_con[contents_img], ",") !== false){
            $img_arr = explode(",", $row_con[contents_img]);
            $img_link = $img_arr[0];
        }
        else{
            $img_link = $row_con[contents_img];
        }

        array_push($con_idx, $row_data[contents_idx]);
        array_push($con_title, $row_con[contents_title]);
        array_push($con_img, $img_link);
        array_push($con_sell_price, $row_data[contents_price]);
        array_push($con_salary, $row_data[salary_price]);
        array_push($con_cnt, $row_data[contents_cnt]);
        array_push($order_opt, $row_data[order_option]);
        array_push($cont_url, $row_con[contents_url]);
    }
}
else{
    $sql_cont_data = "select contents_title, contents_img, contents_url from Gn_Iam_Contents_Gwc where idx='{$contents_idx}'";
    $res_cont_data = mysql_query($sql_cont_data);
    $row_cont_data = mysql_fetch_array($res_cont_data);
    
    if(strpos($row_cont_data[contents_img], ",") !== false){
        $img_arr = explode(",", $row_cont_data[contents_img]);
        $img_link = $img_arr[0];
    }
    else{
        $img_link = $row_cont_data[contents_img];
    }
    $contents_salary == "NaN"?$contents_salary = 0:$contents_salary = $contents_salary;

    array_push($con_idx, $contents_idx);
    array_push($con_title, $row_cont_data[contents_title]);
    array_push($con_img, $img_link);
    array_push($con_sell_price, $contents_price);
    array_push($con_salary, $contents_salary);
    array_push($con_cnt, $contents_cnt);
    array_push($order_opt, $order_option);
    array_push($cont_url, $row_cont_data[contents_url]);
}

if($platform == "mobile"){
    echo "<script type='text/javascript' charset='euc-kr' src='https://tx.allatpay.com/common/AllatPayM.js'></script>";
}else{
    echo "<script language=JavaScript charset='euc-kr' src='https://tx.allatpay.com/common/NonAllatPayREPlus.js'></script>";
    echo "<script language=JavaScript charset='euc-kr' src='https://tx.allatpay.com/common/NonAllatPayRE.js'></script>";
}

$sql_main_addr = "select * from Gn_Order_Address where mem_id='{$mem_id_page}' and main_addr=1 order by id desc limit 1";
$res_main_addr = mysql_query($sql_main_addr);
$row_main_addr = mysql_fetch_array($res_main_addr);
?>
<script language=Javascript>
    $(function() {
        $(document).ajaxStop(function() {
            $("#ajax-loading").delay(10).hide(1); 
        });
        <?if($_SERVER['SERVER_NAME'] == "center.kiam.kr") {?>
        pay_total_v('add_phone_');
        <?}?>
        // 팝업 닫기 스크립트
        $('.popup-overlay, #closePopup').on('click', function() {
            $('.daily_popup').css('display', 'none');
            return false;
        });
        $("#item_count").keyup(function () {
            var item_count = $("#item_count").val();
            if(item_count == "" || item_count == 0){
                $("#item_price").val(<?=$item_price?>);
            }
            else{
                $("#item_price").val($("#item_price").val() * $("#item_count").val());
            }
        });
    });
    // 카드단회결제
    function ftn_approval(dfm, price) {
        $.ajax({
            type:"POST",
            url:"/makeData_item.php",
            data:$('#pay_form').serialize(),
            dataType: 'html',
            success:function(data){
            },
            error: function (request, status, error) {
                console.log('code: ' + request.status + "\n" + 'message: ' + request.responseText + "\n" + 'error: ' + error);
            }
        });
        if(price != 0){
        pay_form.acceptCharset = 'euc-kr';
        document.charset = 'euc-kr';
        dfm.allat_product_nm.value = dfm.item_name.value;
        var navCase = navigator.userAgent.toLocaleLowerCase();
        if(navCase.search("android") > -1){
            dfm.allat_app_scheme.value = navCase;
            Allat_Mobile_Approval(dfm, 0, 0);
        }
        else{
            dfm.allat_app_scheme.value = '';
            AllatPay_Approval(dfm);
            // 결제창 자동종료 체크 시작
            AllatPay_Closechk_Start();
        }
        }
        else{
            alert('주문 완료 되었습니다.');
            location.href = 'mypage_payment.php';
        }
    }

    // 결과값 반환( receive 페이지에서 호출 )
    function result_submit(result_cd,result_msg,enc_data) {
        //pay_form.acceptCharset = 'utf-8';
        //document.charset = 'utf-8';
        var navCase = navigator.userAgent.toLocaleLowerCase();
        if(navCase.search("android") > -1) {
            Allat_Mobile_Close();
        }else {
            // 결제창 자동종료 체크 종료
            AllatPay_Closechk_End();
        }
        if( result_cd != '0000' ){//pay_test
            //window.setTimeout(function(){alert(result_cd + " : " + result_msg);},1000);
            alert(result_cd + " : " + result_msg);
            location.reload();
        } else {
            pay_form.acceptCharset = 'utf-8';
            document.charset = 'utf-8';
            pay_form.allat_enc_data.value = enc_data;
            pay_form.action = "/pay_end_allat_item.php";
            pay_form.method = "post";
            pay_form.target = "_self";
            pay_form.submit();
        }
    }

    //결제고고
    function pay_go(frm){
        errmsg = "";
        errfld = "";

        var min_point = parseInt("5000");
        var temp_point = parseInt(no_comma(frm.use_point.value));
        var sell_price = parseInt(frm.org_price.value);
        var send_cost2 = parseInt(frm.baesong_price2.value);
        var mb_point = parseInt(frm.mb_point.value);
        var tot_price = sell_price + send_cost2;

        if (frm.use_point.value == '') {
            alert('포인트사용 금액을 입력하세요. 사용을 원치 않을경우 0을 입력하세요.');
            frm.use_point.value = 0;
            frm.use_point.focus();
            return false;
        }

        if (temp_point > mb_point) {
            alert('포인트사용 금액은 현재 보유포인트 보다 클수 없습니다.');
            frm.tot_price.value = number_format(String(tot_price));
            frm.use_point.value = 0;
            frm.use_point.focus();
            return false;
        }

        if (temp_point > tot_price) {
            alert('포인트사용 금액은 최종결제금액 보다 클수 없습니다.');
            frm.tot_price.value = number_format(String(tot_price));
            frm.use_point.value = 0;
            frm.use_point.focus();
            return false;
        }

        if (temp_point > 0 && (mb_point < min_point)) {
            alert('포인트사용 금액은 ' + number_format(String(min_point)) + '원 부터 사용가능 합니다.');
            frm.tot_price.value = number_format(String(tot_price));
            frm.use_point.value = 0;
            frm.use_point.focus();
            return false;
        }

        if (errmsg) {
            alert(errmsg);
            errfld.focus();
            return false;
        }

        frm.use_point.value = no_comma(frm.use_point.value);
        frm.tot_price.value = no_comma(frm.tot_price.value);

        var price = frm.tot_price.value;
        var point = frm.use_point.value;
        $('#allat_amt').val(price);
        $('#allat_amt_point').val(point);
        $('#price').val(price);
        $('#member_type').val($("#item_name").val());
        $('#month_cnt').val(120);
        if(!frm.mid.value){
            alert('결제종류를 선택해주세요.');
            // document.getElementsByName('money_type')[0].focus();
            return false;
        }
        if($('#member_type').val() == ""){
            alert('상품명이 존재하지 않습니다.');
            return;
        }
        // if(price <= 0){
        //     alert('결제하실 금액이 존재하지 않습니다.');
        //     return;
        // }
        if(confirm('주문내역이 정확하며, 주문 하시겠습니까?')){
            // if($('#allat_recp_addr').val() == "") {
            //     $.ajax({
            //         type:"POST",
            //         url:"/ajax/get_mem_address.php",
            //         dataType:"json",
            //         data:{mem_id:'<?=$mem_id_page?>'},
            //         success: function(data){
            //             console.log(data.address);
            //             $('#allat_recp_addr').val(data.address);
            //             $('#pay_form').attr("action","/allat/mp/allat_fix_spec.php");
            //             ftn_approval(document.pay_form);
            //         },
            //         error: function () {
            //             alert('내 정보 수정에서 자택주소를 추가해주세요.');
            //             location.href = "mypage.php";
            //             return;
            //         }
            //     });
            // }else{
                if(price != 0){
                $('#pay_form').attr("action","/allat/mp/allat_fix_spec.php");
                }
                ftn_approval(document.pay_form, price);
            // }
        }
    }
</script>
<link rel="stylesheet" href="/iam/css/style_gwc.css">
<div id="container" class="sub_wrap" style="margin-top: 85px;">
    <script>
        //상단 슬라이드 메뉴
        // var menuScroll = null;
        // $(window).ready(function () {
        //     menuScroll = new iScroll('gnb', {
        //         hScrollbar: false,
        //         vScrollbar: false,
        //         bounce: false,
        //         click: true
        //     });
        // });
    </script>
    <div id="content_title">
        <span>주문서작성</span>
    </div>

    <!-- 주문서작성 시작 { -->
    <div id="sod_approval_frm">
    </div>

    <div id="sod_frm">
        <form name="pay_form" id="pay_form" method="post" action="" autocomplete="off" onsubmit="return pay_go(this);">
            <!-- <input type="hidden" name="contents_idx" value="<?=$contents_idx?>"> -->
            <input type="hidden" name="mb_point" value="<?=$member_iam[mem_cash]?>">
            <input type="hidden" name="pt_id" value="<?=$mem_id_page?>">
            <input type="hidden" name="baesong_price" value="<?=implode(',', $con_salary)?>">
            <input type="hidden" name="baesong_price2" value="<?=array_sum($con_salary)?>">
            <input type="hidden" name="org_price" value="<?=array_sum($con_sell_price)?>">
            <input type="hidden" name="org_price1" value="<?=implode(',', $con_sell_price)?>">
            <input type="hidden" name="item_name" id="item_name" value="<?=implode('||', $con_title)?>">
            <input type="hidden" name="order_option" value="<?=implode(',', $order_opt)?>">
            <input type="hidden" name="contents_cnt" value="<?=implode(',', $con_cnt)?>">
            <input type="hidden" name="contents_url" value="<?=implode(',', $cont_url)?>">

            <!--주문정보암호화필드-->
            <input type="hidden" name="allat_enc_data" value=''>
            <input type="hidden" name="allat_app_scheme" value=''>
            <input type="hidden" name="allat_autoscreen_yn" value='Y'>
            <!--상점 ID-->
            <input type="hidden" name="allat_shop_id" id="allat_shop_id" value="welcome101" size="19" maxlength=20>
            <!--주문번호-->
            <input type="hidden" name="allat_order_no" id="allat_order_no" value="<?=$mid;?>" size="19" maxlength=70>
            <!--인증정보수신URL-->
            <input type="hidden" name="shop_receive_url" id="shop_receive_url" value="https://<?php echo $_SERVER['SERVER_NAME'];?>/allat/pay/allat_receive.php?mid=<?php echo $mid;?>" size="19">
            <!--승인금액-->
            <input type="hidden" name="allat_amt" id="allat_amt" value="" size="19" maxlength=10>
            <!--승인포인트-->
            <input type="hidden" name="allat_amt_point" id="allat_amt_point" value="" size="19" maxlength=10>
            <!--회원ID-->
            <input type="hidden" name="allat_pmember_id" value="<?=$mem_id_page;?>" size="19" maxlength=20>
            <!--상품코드-->
            <input type="hidden" name="allat_product_cd" id="allat_product_cd" value="상품결제" size="19" maxlength=1000>
            <!--상품명-->
            <input type="hidden" name="allat_product_nm" id="allat_product_nm" value="상품결제" size="19" maxlength=1000>
            <!--결제자성명-->
            <input type="hidden" name="allat_buyer_nm" value="<?=$Gn_mem_row['mem_name'];?>" size="19" maxlength=20>
            <!--수취인성명-->
            <input type="hidden" name="allat_recp_nm" value="<?=$Gn_mem_row['mem_name'];?>" size="19" maxlength=20>
            <!--수취인주소-->
            <!-- <input type="hidden" name="allat_recp_addr" id="allat_recp_addr" value="<?=$Gn_mem_row['mem_add1'];?>" size="19" maxlength=120> -->
            <!--배당율-->
            <input type="hidden" name="pay_percent" id="pay_percent" value="90">
            <input type="hidden" name="seller_id" id="seller_id" value="<?=$seller_id?>">
            <input type="hidden" name="cont_idx" id="cont_idx" value="<?=implode(',', $con_idx)?>">
            <input type="hidden" name="gopaymethod" />
            <input type="hidden" name="db_cnt" id="db_cnt" value="0">
            <input type="hidden" name="email_cnt" id="email_cnt" value="0">
            <input type="hidden" name="shop_cnt" id="shop_cnt" value="0">
            <input type="hidden" name="onestep1" id="onestep1" value="OFF">
            <input type="hidden" name="onestep2" id="onestep2" value="OFF">
            <input type="hidden" name="member_cnt" id="member_cnt" value="0">
            <input type="hidden" name="add_phone" id="add_phone" value="0">
            <input type="hidden" name="payMethod" id="payMethod"/>
            <input type="hidden" name="pay_type" id="pay_type" />
            <input type="hidden" name="max_cnt" />
            <input type="hidden" name="month_cnt" id="month_cnt" value="120"/>
            <input type="hidden" name="member_type" id="member_type" value="굿마켓상품"/>
            <input type="hidden" name="fujia_status" id="fujia_status" />
            <input type="hidden" name="mid" value="obmms20151" />
            <input type="hidden" name="gwc_order" value="1" />

            <p>주문하실 상품을 확인하세요.</p>

            <?for($i = 0; $i < count($con_idx); $i++){?>
            <ul class="sod_list">
                <li class="sod_li">
                    <div class="li_name">
                        <strong><?=$con_title[$i]?></strong>
                        <div class="sod_opt">
                            <ul>
                            </ul>
                        </div>
                        <div class="li_mod" style="padding-left:100px;"></div>
                        <span class="total_img"><img src="<?=$con_img[$i]?>" width="80"
                                height="80"></span>
                    </div>
                    <div class="li_prqty">
                        <span class="prqty_price li_prqty_sp"><span>판매가</span>
                            <?=number_format(($con_sell_price[$i] * 1) / ($con_cnt[$i] * 1))?></span>
                        <span class="prqty_qty li_prqty_sp"><span>수량</span>
                            <?=number_format($con_cnt[$i])?></span>
                        <span class="prqty_sc li_prqty_sp"><span>합계</span>
                            <?=number_format($con_sell_price[$i])?></span>
                    </div>
                    <!-- <div class="li_total">
                        <span class="total_price total_span"><span>소계</span>
                            <strong><?=number_format($contents_price * 1 + $contents_salary * 1)?></strong></span>
                        <span class="total_point total_span"><span>적립포인트</span>
                            <strong>0</strong></span>
                    </div> -->
                </li>

            </ul>
            <?}?>

            <dl id="sod_bsk_tot">
                <dt class="sod_bsk_sell"><span>주문</span></dt>
                <dd class="sod_bsk_sell"><strong><?=number_format(array_sum($con_sell_price))?> 원</strong></dd>
                <dt class="sod_bsk_dvr" style="margin-left: 1px;"><span>배송비</span></dt>
                <dd class="sod_bsk_dvr" style="margin-left: -1px;"><strong><?=number_format(array_sum($con_salary))?> 원</strong></dd>
                <dt class="sod_bsk_cnt" style="height: 41px;"><span>총계</span></dt>
                <dd class="sod_bsk_cnt"><strong><?=number_format(array_sum($con_sell_price) * 1 + array_sum($con_salary) * 1)?> 원</strong></dd>
                <!-- <dt class="sod_bsk_point"><span>포인트</span></dt>
                <dd class="sod_bsk_point"><strong><?=number_format($member_iam[mem_cash])?> P</strong></dd> -->
            </dl>

            <section id="sod_frm_orderer">
                <h2 class="anc_tit">주문하시는 분</h2>
                <div class="odf_tbl">
                    <table>
                        <tbody>
                            <tr>
                                <th scope="row">이름</th>
                                <td><input type="text" name="name" value="<?=$row_main_addr[name]?$row_main_addr[name]:$member_iam[mem_name]?>" required="" class="frm_input required" maxlength="20"></td>
                            </tr>
                            <tr>
                                <th scope="row">핸드폰</th>
                                <td><input type="text" name="cellphone" value="<?=$row_main_addr[phone_num]?$row_main_addr[phone_num]:$member_iam[mem_phone]?>" required="" class="frm_input required" maxlength="20"></td>
                            </tr>
                            <tr>
                                <th scope="row">전화번호</th>
                                <td><input type="text" name="telephone" value="<?=$row_main_addr[phone_num1]?$row_main_addr[phone_num1]:$member_iam[mem_phone1]?>" class="frm_input" maxlength="20"></td>
                            </tr>
                            <tr>
                                <th scope="row">주소</th>
                                <td>
                                    <input type="text" name="zip" value="<?=$row_main_addr[zip]?$row_main_addr[zip]:''?>" required="" class="frm_input required" size="5" maxlength="5">
                                    <button type="button" onclick="win_zip('pay_form', 'zip', 'allat_recp_addr', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</button><br>
                                    <input type="text" name="allat_recp_addr" id="allat_recp_addr" value="<?=$row_main_addr[address1]?$row_main_addr[address1]:$member_iam[mem_add1]?>" required="" class="frm_input frm_address required"><br>
                                    <input type="text" name="addr2" value="<?=$row_main_addr[address2]?$row_main_addr[address2]:''?>" class="frm_input frm_address"><br>
                                    <input type="text" name="addr3" value="" class="frm_input frm_address" readonly="" hidden>
                                    <input type="hidden" name="addr_jibeon" value="R">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">E-mail</th>
                                <td><input type="text" name="email" value="<?=$member_iam[mem_email]?>" required=""
                                        class="frm_input required wfull"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="sod_frm_taker">
                <h2 class="anc_tit">받으시는 분</h2>
                <div class="odf_tbl">
                    <table>
                        <tbody>
                            <tr>
                                <th scope="row">배송지선택</th>
                                <td id="order_addr_sel">
                                    <input type="radio" name="ad_sel_addr" value="1" id="sel_addr1"
                                        class="css-checkbox lrg">
                                    <label for="sel_addr1" class="css-label padr5">주문자와 동일</label><br>
                                    <input type="radio" name="ad_sel_addr" value="2" id="sel_addr2"
                                        class="css-checkbox lrg">
                                    <label for="sel_addr2" class="css-label">신규배송지</label>
                                    <br><input type="radio" name="ad_sel_addr" value="3" id="sel_addr3"
                                        class="css-checkbox lrg">
                                    <label for="sel_addr3" class="css-label">배송지목록</label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">이름</th>
                                <td><input type="text" name="b_name" required="" class="frm_input required"></td>
                            </tr>
                            <tr>
                                <th scope="row">핸드폰</th>
                                <td><input type="text" name="b_cellphone" required="" class="frm_input required"></td>
                            </tr>
                            <tr>
                                <th scope="row">전화번호</th>
                                <td><input type="text" name="b_telephone" class="frm_input"></td>
                            </tr>
                            <tr>
                                <th scope="row">주소</th>
                                <td>
                                    <input type="text" name="b_zip" required="" class="frm_input required" size="5"
                                        maxlength="5">
                                    <button type="button"
                                        onclick="win_zip('pay_form', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');"
                                        class="btn_small grey">주소검색</button><br>
                                    <input type="text" name="b_addr1" required=""
                                        class="frm_input frm_address required"><br>
                                    <input type="text" name="b_addr2" class="frm_input frm_address"><br>
                                    <input type="text" name="b_addr3" class="frm_input frm_address" readonly="" hidden>
                                    <input type="hidden" name="b_addr_jibeon" value="R">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"></th>
                                <td>
                                    <input type="button" name="telephone" value="배송지 저장" onclick="save_baesong_add()" class="frm_input grey">
                                </td>
                            </tr>
                            <!-- <tr>
                                <th scope="row">전하실말씀</th>
                                <td>
                                    <select name="sel_memo" class="wfull">
                                        <option value="">요청사항 선택하기</option>
                                        <option value="부재시 경비실에 맡겨주세요.">부재시 경비실에 맡겨주세요</option>
                                        <option value="빠른 배송 부탁드립니다.">빠른 배송 부탁드립니다.</option>
                                        <option value="부재시 핸드폰으로 연락바랍니다.">부재시 핸드폰으로 연락바랍니다.</option>
                                        <option value="배송 전 연락바랍니다.">배송 전 연락바랍니다.</option>
                                    </select>
                                    <div class="padt5">
                                        <textarea name="memo" id="memo" class="frm_textbox"></textarea>
                                    </div>
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="sod_frm_pay">
                <h2 class="anc_tit">결제정보 입력</h2>
                <div class="odf_tbl">
                    <table>
                        <tbody>
                            <tr>
                                <th scope="row">합계</th>
                                <td><strong><?=number_format(array_sum($con_sell_price))?>원</strong></td>
                            </tr>
                            <tr>
                                <th scope="row">추가배송비</th>
                                <td>
                                    <strong><span id="send_cost2"><?=number_format(array_sum($con_salary))?></span>원</strong>
                                    <span class="fc_999">(지역에 따라 추가되는 배송비)</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">포인트결제</th>
                                <td>
                                    <input type="text" name="use_point" value="<?=$admin=='Y'?number_format($use_point_val):'0'?>"
                                        onkeyup="calculate_temp_point(this.value); this.value=number_format(this.value);"
                                        class="frm_input w100"> 원
                                    <!-- <div>씨드포인트 잔액 : <b><?=number_format($member_iam[mem_point])?>P</b></div> -->
                                    <div>캐시포인트 잔액 : <b><?=number_format($member_iam[mem_cash])?>P</b></div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">총 결제금액</th>
                                <td>
                                    <?
                                    if($admin == "Y"){
                                        $tot_price_val = number_format(array_sum($con_sell_price) * 1 + array_sum($con_salary) * 1 - $use_point_val * 1);
                                    }
                                    else{
                                        $tot_price_val = number_format(array_sum($con_sell_price) * 1 + array_sum($con_salary) * 1);
                                    }
                                    ?>
                                    <input type="text" name="tot_price" value="<?=$tot_price_val?>" class="frm_input w100" readonly="" style="background:#f1f1f1;color:red;font-weight:bold;"> 원
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="btn_confirm" style="text-align: center;padding: 10px;">
                <?if($admin == "M"){?>
                <a href="javascript:edit_order('1', '<?=$order_id?>');" class="btn_medium bx-white">주문취소</a>
                <?}else{?>
                <input type="submit" value="주문하기" class="btn_medium wset">
                <a href="javascript:history.go(-1);" class="btn_medium bx-white">주문취소</a>
                <?}?>
            </div>
        </form>
    </div>
    <div id="edit_order_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:500px;">
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <img src="/iam/img/menu/icon_close.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                    </button>
                </div>
                <div class = "modal-title" style="width:100%;font-size:18px;text-align: left;color:white;border-bottom: 1px solid #c8c9c8;background-color: grey;">
                    <label style="padding:7px 20px">어떤 문제가 있나요?</label>
                </div>
                <div class="modal-header" style="text-align:left;">
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title" value="상품이 마음에 들지 않음(단순변심)" id="mind" style="vertical-align: middle;">
                        <label for="mind">상품이 마음에 들지 않음(단순변심)</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title" value="다른 상품 추가 후 재주문 예정" id="undetail" style="vertical-align: middle;">
                        <label for="undetail">다른 상품 추가 후 재주문 예정</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title" value="상품의 옵션 선택을 잘못함" id="description" style="vertical-align: middle;">
                        <label for="description">상품의 옵션 선택을 잘못함</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title" value="다른 사이트의 가격이 더 저렴함" id="break" style="vertical-align: middle;">
                        <label for="break">다른 사이트의 가격이 더 저렴함</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title" value="8" id="other" style="vertical-align: middle;">
                        <label for="other">기타</label>
                    </div>
                    <input type="hidden" name="edit_type" id="edit_type" value="">
                    <input type="hidden" name="od_id" id="od_id" value="">
                </div>
                <div class="modal-body" id="other_detail" style="text-align:center;" hidden>
                    <textarea id="report_other_msg" maxlength="250" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;" placeholder="추가설명이 필요하면 입력해 주세요."></textarea>
                    <p id="other_desc_letter" style="text-align: right;">0/250</p>
                </div>
                <div class="modal-footer" style="text-align: center;padding:5px;">
                    <button type="button" class="btn-link" style="width: 35%;background: white;color: #6e6a6a;padding: 10px 0px;border: 1px solid #82c836;border-radius: 10px;" onclick="close_modal()">취소하기</button>
                    <button type="button" class="btn-link" style="width: 35%;background: #82c836;color: white;padding: 10px 0px;border-radius: 10px;border: 1px solid #82c836;" onclick="req_report()">신청하기</button>
                </div>
            </div>
        </div>
    </div>
    <div id="req_detail_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false" style="top: 100px;">
        <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:300px;">
            <div class="modal-content">
                <div class = "modal-title" style="width:100%;font-size:18px;text-align: center;color:black;border-bottom: 1px solid #c8c9c8;">
                    <label style="padding:7px 20px;font-weight: 100;"><img src="/iam/img/menu/req_info.png" style="width:25px;margin-bottom: 5px;margin-right: 7px;"></img>교환, 환불 신청안내</label>
                </div>
                <div class="modal-body" style="text-align:center;">
                    <p style="text-align:center;">
                        교환, 환불 가능한 기간이 지났습니다.<br>
                        교환, 환불은 배송완료 후<br>
                        30일 이내에 가능합니다.
                    </p>
                </div>
                <div class="modal-footer" style="text-align: center;padding:5px;">
                    <button type="button" class="btn-link" style="width: 50%;background: #82c836;color: white;padding: 7px 0px;border-radius: 10px;border: 1px solid #82c836;" onclick="close_modal()">확인</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(function () {
            var zipcode = "";

            $("input[name=b_addr2]").focus(function () {
                var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
                if (zip == "")
                    return false;

                var code = String(zip);

                if (zipcode == code)
                    return false;

                zipcode = code;
                // calculate_sendcost(code);
            });

            // 배송지선택
            $("input[name=ad_sel_addr]").on("click", function () {
                var addr = $(this).val();

                if (addr == "1") {
                    gumae2baesong(true);
                } else if (addr == "2") {
                    gumae2baesong(false);
                } else {
                    win_open('/iam/orderaddress.php', 'win_address', "483", "600");
                }
            });

            $("select[name=sel_memo]").change(function () {
                $("textarea[name=memo]").val($(this).val());
            });

            $("input[name=report_title]").on('click', function(){
                if($(this).attr('id') == "other"){
                    $("#other_detail").show();
                }
                else{
                    $("#other_detail").hide();
                }
            })

            $("#report_other_msg").on('keyup', function(){
                var str = $(this).val();
                var len = str.length;
                var rest = 250 - len;
                $("#other_desc_letter").text(len + '/' + rest);
            })
        });

        function calculate_order_price() {
            var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
            var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
            var mb_point = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //포인트결제
            var tot_price = sell_price + send_cost2 - (mb_coupon + mb_point);

            $("input[name=tot_price]").val(number_format(String(tot_price)));
        }

        function save_baesong_add(){
            var f = document.pay_form;
            if(f.b_name.value == '' || f.b_cellphone.value == '' || f.b_zip.value == ''){
                alert('배송지정보를 정확히 입력하세요.');
                return;
            }
            else{
                $.ajax({
                    type:"POST",
                    url:"ajax/manage_request_list.php",
                    data:{mode:'save_address', name:f.b_name.value, phone:f.b_cellphone.value, phone1:f.b_telephone.value, zip:f.b_zip.value, address1:f.b_addr1.value, address2:f.b_addr2.value},
                    dataType:'json',
                    success:function(data){
                        if(data.status == "1"){
                            alert('저장 되었습니다.');
                        }
                        else{
                            alert('이미 저장된 주소입니다.');
                        }
                    },
                    error: function (request, status, error) {
                        alert(error);
                    }
                });
            }
        }

        function calculate_temp_point(val) {
            var f = document.pay_form;
            var temp_point = parseInt(no_comma(f.use_point.value));
            var sell_price = parseInt(f.org_price.value);
            var send_cost2 = parseInt(f.baesong_price2.value);
            var tot_price = sell_price + send_cost2;

            if (!checkNum(no_comma(val))) {
                alert('포인트 사용액은 숫자이어야 합니다.');
                f.tot_price.value = number_format(String(tot_price));
                f.use_point.value = 0;
                f.use_point.focus();
                return;
            } else {
                if(val == ''){
                    f.tot_price.value = number_format(String(tot_price));
                }
                else{
                    f.tot_price.value = number_format(String(tot_price - temp_point));
                }
            }
        }

        // 구매자 정보와 동일합니다.
        function gumae2baesong(checked) {
            var f = document.pay_form;

            if (checked == true) {
                f.b_name.value = f.name.value;
                f.b_cellphone.value = f.cellphone.value;
                f.b_telephone.value = f.telephone.value;
                f.b_zip.value = f.zip.value;
                f.b_addr1.value = f.allat_recp_addr.value;
                f.b_addr2.value = f.addr2.value;
                f.b_addr3.value = f.addr3.value;
                f.b_addr_jibeon.value = f.addr_jibeon.value;

                // calculate_sendcost(String(f.b_zip.value));
            } else {
                f.b_name.value = '';
                f.b_cellphone.value = '';
                f.b_telephone.value = '';
                f.b_zip.value = '';
                f.b_addr1.value = '';
                f.b_addr2.value = '';
                f.b_addr3.value = '';
                f.b_addr_jibeon.value = '';

                // calculate_sendcost('');
            }
        }

        gumae2baesong(true);

        function edit_order(type, order_id){
            $("#edit_type").val(type);
            $("#od_id").val(order_id);
            $.ajax({
                type:"POST",
                url:'ajax/product_mng.php',
                data:{mode:'check_prod_order_date', order_id:order_id},
                dataType:'json',
                success:function(data){
                    $("#ajax-loading").hide();
                    if(data.result == "0"){
                        $("#req_detail_modal").modal('show');
                        return;
                    }
                    else{
                        $("#edit_order_modal").modal("show");
                    }
                }
            })
        }

        function req_report(){
            var type = $("input[name=report_title]:checked").val();
            var edit_type = $("#edit_type").val();
            var od_id = $("#od_id").val();
            if(type == undefined){
                alert('해당 항목 체크를 꼭 하시거나, 추가 설명을 입력하셔야 합니다.');
                return;
            }
            else{
                if(type == "8"){
                    var title = $("#report_other_msg").val();
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
                        location.href = 'gwc_order_list.php';
                    }
                })
            }
        }

        function close_modal(){
            $("#req_detail_modal").modal('hide');
            $("#edit_order_modal").modal("hide");
        }
    </script>
    <!-- } 주문서작성 끝 -->

</div>
<div id="ajax_div" style="display:none"></div>