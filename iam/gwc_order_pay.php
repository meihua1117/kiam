<?php
include "inc/header.inc.php";
extract($_GET);
if ($admin == "Y" || $admin == "M") {
    $mem_id_page = $mem_id;
} else {
    $mem_id_page = $_SESSION['iam_member_id'];
}
if ($mem_id_page == "") {
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
$cont_provide_price = array();
$order_option = $_GET['order_option'];
$mid = date("YmdHis") . rand(10, 99);
$gwc_order_option_content = json_decode($_GET['gwc_order_option_content'], true);

?>
<script>
    let gwc_order_option_content = '<? echo $_GET['gwc_order_option_content']; ?>';
    if (gwc_order_option_content != '') {
        gwc_order_option_content = JSON.parse(gwc_order_option_content);
    }
    if ("<?= $platform ?>" != "mobile" && navigator.userAgent.toLocaleLowerCase().search("android") > -1) {
        location.href = "<?= $_SERVER['REQUEST_URI'] ?>&platform=mobile";
    }
</script>
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<?
if ($_GET['shop'] == "gallery")
    $table = "Gn_Iam_Contents";
else
    $table = "Gn_Iam_Contents_Gwc";
if ($cart_ids != '') {
    $ids_arr = explode(',', $cart_ids);
    for ($i = 0; $i < count($ids_arr); $i++) {
        $sql_update = "update Gn_Gwc_Order set pay_order_no='{$mid}' where id='{$ids_arr[$i]}'";
        mysqli_query($self_con,$sql_update);
        $sql_data = "select * from Gn_Gwc_Order where id='{$ids_arr[$i]}'";
        $res_data = mysqli_query($self_con,$sql_data);
        $row_data = mysqli_fetch_array($res_data);

        if ($table == "Gn_Iam_Contents_Gwc")
            $sql_con = "select contents_title, contents_img, contents_url, send_provide_price, over_send_salary from {$table} where idx='{$row_data['contents_idx']}'";
        else
            $sql_con = "select contents_title, contents_img, contents_url, send_provide_price from {$table} where idx='{$row_data['contents_idx']}'";
        $res_con = mysqli_query($self_con,$sql_con);
        $row_con = mysqli_fetch_array($res_con);
        if ($row_con['send_provide_price'] == "")
            $row_con['send_provide_price'] = 0;

        if (strpos($row_con['contents_img'], ",") !== false) {
            $img_arr = explode(",", $row_con['contents_img']);
            $img_link = $img_arr[0];
        } else {
            $img_link = $row_con['contents_img'];
        }

        if (($row_data['contents_price'] >= $row_con['over_send_salary']) && $row_con['over_send_salary']) {
            $row_data['salary_price'] = 0;
        }
        array_push($con_idx, $row_data['contents_idx']);
        array_push($con_title, $row_con['contents_title']);
        array_push($con_img, $img_link);
        array_push($con_sell_price, $row_data['contents_price']);
        array_push($con_salary, $row_data['salary_price']);
        array_push($con_cnt, $row_data['contents_cnt']);
        array_push($order_opt, $row_data['order_option']);
        array_push($cont_url, $row_con['contents_url']);
        array_push($cont_provide_price, $row_con['send_provide_price']);
    }
} else {
    $sql_cont_data = "select contents_title, contents_img, contents_url, send_provide_price from {$table} where idx='{$contents_idx}'";
    $res_cont_data = mysqli_query($self_con,$sql_cont_data);
    $row_cont_data = mysqli_fetch_array($res_cont_data);
    if ($row_cont_data['send_provide_price'] == "")
        $row_cont_data['send_provide_price'] = 0;

    if (strpos($row_cont_data['contents_img'], ",") !== false) {
        $img_arr = explode(",", $row_cont_data['contents_img']);
        $img_link = $img_arr[0];
    } else {
        $img_link = $row_cont_data['contents_img'];
    }
    $contents_salary == "NaN" ? $contents_salary = 0 : $contents_salary = $contents_salary;

    if (($contents_price >= $over_salary) && $over_salary != "NaN") {
        $contents_salary = 0;
    }
    array_push($con_idx, $contents_idx);
    array_push($con_title, $row_cont_data['contents_title']);
    array_push($con_img, $img_link);
    array_push($con_sell_price, $contents_price);
    array_push($con_salary, $contents_salary);
    array_push($con_cnt, $contents_cnt);
    array_push($order_opt, $order_option);
    array_push($cont_url, $row_cont_data['contents_url']);
    array_push($cont_provide_price, $row_cont_data['send_provide_price']);
}

if ($platform == "mobile") {
    echo "<script type='text/javascript' charset='euc-kr' src='https://tx.allatpay.com/common/AllatPayM.js'></script>";
} else {
    echo "<script language=JavaScript charset='euc-kr' src='https://tx.allatpay.com/common/NonAllatPayREPlus.js'></script>";
    echo "<script language=JavaScript charset='euc-kr' src='https://tx.allatpay.com/common/NonAllatPayRE.js'></script>";
}

$sql_main_addr = "select * from Gn_Order_Address where mem_id='{$mem_id_page}' and main_addr=1 order by id desc limit 1";
$res_main_addr = mysqli_query($self_con,$sql_main_addr);
$row_main_addr = mysqli_fetch_array($res_main_addr);
?>
<script language=Javascript>
    $(function() {
        $(document).ajaxStop(function() {
            $("#ajax-loading").delay(10).hide(1);
        });
        <? if ($_SERVER['SERVER_NAME'] == "center.kiam.kr") { ?>
            pay_total_v('add_phone_');
        <? } ?>
        // 팝업 닫기 스크립트
        $('.popup-overlay, #closePopup').on('click', function() {
            $('.daily_popup').css('display', 'none');
            return false;
        });
        $("#item_count").keyup(function() {
            var item_count = $("#item_count").val();
            if (item_count == "" || item_count == 0) {
                $("#item_price").val(<?= $item_price ?>);
            } else {
                $("#item_price").val($("#item_price").val() * $("#item_count").val());
            }
        });
    });
    // 카드단회결제
    function ftn_approval(dfm, price, gwc_order_option_content) {
        $.ajax({
            type: "POST",
            url: "/makeData_item.php",
            data: $('#pay_form').serialize() + '&gwc_order_option_content=' + gwc_order_option_content,
            dataType: 'html',
            success: function(data) {},
            error: function(request, status, error) {
                console.log('code: ' + request.status + "\n" + 'message: ' + request.responseText + "\n" + 'error: ' + error);
            }
        });
        if (dfm.payMethod.value == "BANK") {
            location.href = 'bank_pay_intro.php?price=' + dfm.tot_price.value + '&item_name=' + dfm.item_name.value;
        } else {
            if (price != 0) {
                pay_form.acceptCharset = 'euc-kr';
                document.charset = 'euc-kr';
                dfm.allat_product_nm.value = dfm.item_name.value;
                var navCase = navigator.userAgent.toLocaleLowerCase();
                if (navCase.search("android") > -1) {
                    dfm.allat_app_scheme.value = navCase;
                    Allat_Mobile_Approval(dfm, 0, 0);
                } else {
                    dfm.allat_app_scheme.value = '';
                    AllatPay_Approval(dfm);
                    // 결제창 자동종료 체크 시작
                    AllatPay_Closechk_Start();
                }
            } else {
                $.ajax({
                    type: "POST",
                    url: "/makeData_item_point.php",
                    data: {
                        payMethod: "order_change",
                        payMethod1: "order_complete",
                        allat_order_no: dfm.allat_order_no.value
                    },
                    dataType: 'json',
                    success: function(data) {
                        // alert("문자발송되었습니다.");
                        location.reload();
                    },
                });
                alert('주문 완료 되었습니다.');
                location.href = '/iam/gwc_order_list.php';
            }
        }
    }

    // 결과값 반환( receive 페이지에서 호출 )
    function result_submit(result_cd, result_msg, enc_data) {
        //pay_form.acceptCharset = 'utf-8';
        //document.charset = 'utf-8';
        var navCase = navigator.userAgent.toLocaleLowerCase();
        if (navCase.search("android") > -1) {
            Allat_Mobile_Close();
        } else {
            // 결제창 자동종료 체크 종료
            AllatPay_Closechk_End();
        }
        if (result_cd == '0000') { //pay_test
            pay_form.acceptCharset = 'utf-8';
            document.charset = 'utf-8';
            pay_form.allat_enc_data.value = enc_data;
            pay_form.action = "/pay_end_allat_item.php";
            pay_form.method = "post";
            pay_form.target = "_self";
            pay_form.submit();
        } else {
            //window.setTimeout(function(){alert(result_cd + " : " + result_msg);},1000);
            alert(result_cd + " : " + result_msg);
            location.reload();
        }
    }

    //결제고고
    function pay_go(frm) {
        errmsg = "";
        errfld = "";

        let min_point = parseInt("5000");
        let temp_point = parseInt(no_comma(frm.use_point.value));
        let sell_price = parseInt(frm.org_price.value);
        let send_cost2 = parseInt(frm.baesong_price2.value);
        let mb_point = parseInt(frm.mb_point.value);
        let tot_price = sell_price + send_cost2;
        let gwc_order_option_content = '<? echo $_GET['gwc_order_option_content']; ?>';

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
        if (!frm.mid.value) {
            alert('결제종류를 선택해주세요.');
            return false;
        }
        if ($('#member_type').val() == "") {
            alert('상품명이 존재하지 않습니다.');
            return;
        }
        if (confirm('주문내역이 정확하며, 주문 하시겠습니까?')) {
            if (price != 0)
                $('#pay_form').attr("action", "/allat/mp/allat_fix_spec.php"); // 카드 결제인경우
            else
                $('#pay_form').attr("action", "gwc_order_list.php");
            ftn_approval(document.pay_form, price, gwc_order_option_content);
        }
    }
</script>
<link rel="stylesheet" href="/iam/css/style_gwc.css">
<div id='contents_page' style='left:0px; top:0px; width:320px; height:600px; position:absolute;  z-index:1000; display:none; background-color:white;'></div>
<div id="container" class="sub_wrap" style="margin-top: 85px;position: relative;">
    <div id='order_address' style='left:0px; top:0px; width:320px; height:600px; position:absolute;  z-index:1000; display:none; background-color:white;'></div>
    <div id="content_title">
        <span>주문서작성</span>
    </div>
    <!-- 주문서작성 시작 { -->
    <div id="sod_approval_frm">
    </div>

    <div id="sod_frm">
        <form name="pay_form" id="pay_form" method="post" action="" autocomplete="off">
            <!-- <input type="hidden" name="contents_idx" value="<?= $contents_idx ?>"> -->
            <input type="hidden" name="mb_point" value="<?= $member_iam['mem_cash'] ?>">
            <input type="hidden" name="pt_id" value="<?= $mem_id_page ?>">
            <input type="hidden" name="baesong_price" value="<?= implode(',', $con_salary) ?>">
            <input type="hidden" name="baesong_price2" value="<?= array_sum($con_salary) ?>">
            <input type="hidden" name="org_price" value="<?= array_sum($con_sell_price) ?>">
            <input type="hidden" name="org_price1" value="<?= implode(',', $con_sell_price) ?>">
            <input type="hidden" name="item_name" id="item_name" value="<?= implode('||', $con_title) ?>">
            <input type="hidden" name="order_option" value="<?= implode(',', $order_opt) ?>">
            <input type="hidden" name="contents_cnt" value="<?= implode(',', $con_cnt) ?>">
            <input type="hidden" name="contents_url" value="<?= implode(',', $cont_url) ?>">
            <input type="hidden" name="contents_provide_price" value="<?= implode(',', $cont_provide_price) ?>">

            <!--주문정보암호화필드-->
            <input type="hidden" name="allat_enc_data" value=''>
            <input type="hidden" name="allat_app_scheme" value=''>
            <input type="hidden" name="allat_autoscreen_yn" value='Y'>
            <!--상점 ID-->
            <input type="hidden" name="allat_shop_id" id="allat_shop_id" value="welcome101" size="19" maxlength=20>
            <!--주문번호-->
            <input type="hidden" name="allat_order_no" id="allat_order_no" value="<?= $mid; ?>" size="19" maxlength=70>
            <!--인증정보수신URL-->
            <input type="hidden" name="shop_receive_url" id="shop_receive_url" value="https://<?php echo $_SERVER['SERVER_NAME']; ?>/allat/pay/allat_receive.php?mid=<?php echo $mid; ?>" size="19">
            <!--승인금액-->
            <input type="hidden" name="allat_amt" id="allat_amt" value="" size="19" maxlength=10>
            <!--승인포인트-->
            <input type="hidden" name="allat_amt_point" id="allat_amt_point" value="" size="19" maxlength=10>
            <!--회원ID-->
            <input type="hidden" name="allat_pmember_id" value="<?= $mem_id_page; ?>" size="19" maxlength=20>
            <!--상품코드-->
            <input type="hidden" name="allat_product_cd" id="allat_product_cd" value="상품결제" size="19" maxlength=1000>
            <!--상품명-->
            <input type="hidden" name="allat_product_nm" id="allat_product_nm" value="상품결제" size="19" maxlength=1000>
            <!--결제자성명-->
            <input type="hidden" name="allat_buyer_nm" value="<?= $Gn_mem_row['mem_name']; ?>" size="19" maxlength=20>
            <!--수취인성명-->
            <input type="hidden" name="allat_recp_nm" value="<?= $Gn_mem_row['mem_name']; ?>" size="19" maxlength=20>
            <!--수취인주소-->
            <!-- <input type="hidden" name="allat_recp_addr" id="allat_recp_addr" value="<?= $Gn_mem_row['mem_add1']; ?>" size="19" maxlength=120> -->
            <!--배당율-->
            <input type="hidden" name="pay_percent" id="pay_percent" value="90">
            <input type="hidden" name="seller_id" id="seller_id" value="<?= $seller_id ?>">
            <input type="hidden" name="cont_idx" id="cont_idx" value="<?= implode(',', $con_idx) ?>">
            <input type="hidden" name="gopaymethod" />
            <input type="hidden" name="db_cnt" id="db_cnt" value="0">
            <input type="hidden" name="email_cnt" id="email_cnt" value="0">
            <input type="hidden" name="shop_cnt" id="shop_cnt" value="0">
            <input type="hidden" name="onestep1" id="onestep1" value="OFF">
            <input type="hidden" name="onestep2" id="onestep2" value="OFF">
            <input type="hidden" name="member_cnt" id="member_cnt" value="0">
            <input type="hidden" name="add_phone" id="add_phone" value="0">
            <!-- <input type="hidden" name="payMethod" id="payMethod"/> -->
            <input type="hidden" name="pay_type" id="pay_type" />
            <input type="hidden" name="max_cnt" />
            <input type="hidden" name="month_cnt" id="month_cnt" value="120" />
            <input type="hidden" name="member_type" id="member_type" value="굿마켓상품" />
            <input type="hidden" name="fujia_status" id="fujia_status" />
            <input type="hidden" name="mid" value="obmms20151" />
            <input type="hidden" name="gwc_order" value="1" />

            <p>주문하실 상품을 확인하세요.</p>
            <? for ($i = 0; $i < count($con_idx); $i++) { ?>
                <ul class="sod_list">
                    <li class="sod_li">
                        <div class="li_name">
                            <strong><?= $con_title[$i] ?></strong>
                            <div class="sod_opt">
                                <ul>
                                    <?
                                    foreach ($gwc_order_option_content as $value) {
                                        echo '<li>' . $value["name"] . ' : ' . $value["number"] . '개, +' . $value["opt_price"] . '원</li>';
                                    } ?>
                                </ul>
                            </div>
                            <div class="li_mod" style="padding-left:100px;"></div>
                            <span class="total_img"><img onclick="show_prod_page('<?= $con_idx[$i] ?>')" src="<?= $con_img[$i] ?>" width="80" height="80"></span>
                        </div>
                        <div class="li_prqty">
                            <span class="prqty_price li_prqty_sp"><span>판매가</span>
                                <?= number_format(($con_sell_price[$i] * 1) / ($con_cnt[$i] * 1)) ?></span>
                            <span class="prqty_qty li_prqty_sp"><span>총 수량</span>
                                <?= number_format($con_cnt[$i]) ?></span>
                            <span class="prqty_sc li_prqty_sp"><span>합계</span>
                                <?= number_format($con_sell_price[$i]) ?></span>
                        </div>
                    </li>

                </ul>
            <? } ?>
            <dl id="sod_bsk_tot">
                <dt class="sod_bsk_sell"><span>주문</span></dt>
                <dd class="sod_bsk_sell"><strong><?= number_format(array_sum($con_sell_price)) ?> 원</strong></dd>
                <dt class="sod_bsk_dvr" style="margin-left: 1px;"><span>배송비</span></dt>
                <dd class="sod_bsk_dvr" style="margin-left: -1px;"><strong><?= number_format(array_sum($con_salary)) ?> 원</strong></dd>
                <dt class="sod_bsk_cnt" style="height: 41px;"><span>총계</span></dt>
                <dd class="sod_bsk_cnt"><strong><?= number_format(array_sum($con_sell_price) * 1 + array_sum($con_salary) * 1) ?> 원</strong></dd>
            </dl>
            <section id="sod_frm_orderer">
                <h2 class="anc_tit">주문하시는 분</h2>
                <div class="odf_tbl">
                    <table>
                        <tbody>
                            <tr>
                                <th scope="row">이름</th>
                                <td><input type="text" name="name" value="<?= $row_main_addr['name'] ? $row_main_addr['name'] : $member_iam['mem_name'] ?>" required="" class="frm_input required" maxlength="20"></td>
                            </tr>
                            <tr>
                                <th scope="row">핸드폰</th>
                                <td><input type="text" name="cellphone" value="<?= $row_main_addr['phone_num'] ? $row_main_addr['phone_num'] : $member_iam['mem_phone'] ?>" required="" class="frm_input required" maxlength="20"></td>
                            </tr>
                            <tr>
                                <th scope="row">전화번호</th>
                                <td><input type="text" name="telephone" value="<?= $row_main_addr['phone_num1'] ? $row_main_addr['phone_num1'] : $member_iam['mem_phone1'] ?>" class="frm_input" maxlength="20"></td>
                            </tr>
                            <!-- <tr>
                                <th scope="row">주소</th>
                                <td>
                                    <input type="text" name="zip" value="<?= $row_main_addr['zip'] ? $row_main_addr['zip'] : '' ?>" required="" class="frm_input required" size="5" maxlength="5">
                                    <button type="button" onclick="win_zip('pay_form', 'zip', 'allat_recp_addr', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</button><br>
                                    <input type="text" name="allat_recp_addr" id="allat_recp_addr" value="<?= $row_main_addr['address1'] ? $row_main_addr['address1'] : $member_iam['mem_add1'] ?>" required="" class="frm_input frm_address required"><br>
                                    <input type="text" name="addr2" value="<?= $row_main_addr['address2'] ? $row_main_addr['address2'] : '' ?>" class="frm_input frm_address"><br>
                                    <input type="text" name="addr3" value="" class="frm_input frm_address" readonly="" hidden>
                                    <input type="hidden" name="addr_jibeon" value="R">
                                </td>
                            </tr> -->
                            <tr>
                                <th scope="row">E-mail</th>
                                <td><input type="text" name="email" value="<?= $member_iam['mem_email'] ?>" required="" class="frm_input required wfull"></td>
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
                                    <!-- <input type="radio" name="ad_sel_addr" value="1" id="sel_addr1"
                                        class="css-checkbox lrg">
                                    <label for="sel_addr1" class="css-label padr5">주문자와 동일</label><br> -->
                                    <input type="radio" name="ad_sel_addr" value="2" id="sel_addr2" class="css-checkbox lrg">
                                    <label for="sel_addr2" class="css-label">신규배송지</label>
                                    <br><input type="radio" name="ad_sel_addr" value="3" id="sel_addr3" class="css-checkbox lrg">
                                    <label for="sel_addr3" class="css-label">배송지목록</label>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">이름</th>
                                <td><input type="text" name="b_name" required="" value="<?= $row_main_addr['name'] ? $row_main_addr['name'] : $member_iam['mem_name'] ?>" class="frm_input required"></td>
                            </tr>
                            <tr>
                                <th scope="row">핸드폰</th>
                                <td><input type="text" name="b_cellphone" required="" value="<?= $row_main_addr['phone_num'] ? $row_main_addr['phone_num'] : $member_iam['mem_phone'] ?>" class="frm_input required"></td>
                            </tr>
                            <tr>
                                <th scope="row">전화번호</th>
                                <td><input type="text" name="b_telephone" value="<?= $row_main_addr['phone_num1'] ? $row_main_addr['phone_num1'] : $member_iam['mem_phone1'] ?>" class="frm_input"></td>
                            </tr>
                            <tr>
                                <th scope="row">주소</th>
                                <td>
                                    <input type="text" name="b_zip" value="<?= $row_main_addr['zip'] ? $row_main_addr['zip'] : '' ?>" required="" class="frm_input required" size="5" maxlength="5">
                                    <button type="button" onclick="win_zip('pay_form', 'b_zip', 'allat_recp_addr', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">주소검색</button><br>
                                    <input type="text" name="allat_recp_addr" id="allat_recp_addr" value="<?= $row_main_addr['address1'] ? $row_main_addr['address1'] : $member_iam['mem_add1'] ?>" required="" class="frm_input frm_address required"><br>
                                    <input type="text" name="b_addr2" value="<?= $row_main_addr['address2'] ? $row_main_addr['address2'] : '' ?>" class="frm_input frm_address"><br>
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
                                <th scope="row">결제방법</th>
                                <td>
                                    <select name="payMethod" id="payMethod" class="form-control input-sm pull-left" style="width:100px;">
                                        <option value="CARD" <?= $pay_method == "CARD" ? "selected" : "" ?>>카드결제</option>
                                        <option value="BANK" <?= $pay_method == "BANK" ? "selected" : "" ?>>무통장결제</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">합계</th>
                                <td><strong><?= number_format(array_sum($con_sell_price)) ?>원</strong></td>
                            </tr>
                            <tr>
                                <th scope="row">추가배송비</th>
                                <td>
                                    <strong><span id="send_cost2"><?= number_format(array_sum($con_salary)) ?></span>원</strong>
                                    <span class="fc_999">(지역에 따라 추가되는 배송비)</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">포인트결제</th>
                                <td>
                                    <input type="text" name="use_point" value="<?= $admin == 'Y' || $admin == 'M' ? number_format($use_point_val) : '0' ?>" onkeyup="calculate_temp_point(this.value); this.value=number_format(this.value);" class="frm_input w100"> 원
                                    <!-- <div>씨드포인트 잔액 : <b><?= number_format($member_iam['mem_point']) ?>P</b></div> -->
                                    <div>캐시포인트 잔액 : <b><?= number_format($member_iam['mem_cash']) ?>P</b></div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">총 결제금액</th>
                                <td>
                                    <?
                                    if ($admin == "Y" || $admin == "M") {
                                        $tot_price_val = number_format(array_sum($con_sell_price) * 1 + array_sum($con_salary) * 1 - $use_point_val * 1);
                                    } else {
                                        $tot_price_val = number_format(array_sum($con_sell_price) * 1 + array_sum($con_salary) * 1);
                                    }
                                    ?>
                                    <input type="text" name="tot_price" value="<?= $tot_price_val ?>" class="frm_input w100" readonly="" style="background:#f1f1f1;color:red;font-weight:bold;"> 원
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </form>
        <div class="btn_confirm" style="text-align: center;padding: 10px;">
            <? if ($admin == "M") { ?>
                <input type="button" onclick="edit_order('1', '<?= $order_id ?>');" value="주문취소" class="btn_medium bx-white">
            <? } else { ?>
                <input type="button" onclick="pay_go(document.pay_form)" value="주문하기" class="btn_medium wset">
                <input type="button" onclick="history.go(-1);" value="주문취소" class="btn_medium bx-white">
            <? } ?>
        </div>
    </div>
    <div id="edit_order_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:500px;">
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <img src="/iam/img/menu/icon_close.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: left;color:white;border-bottom: 1px solid #c8c9c8;background-color: grey;">
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
                    <button type="button" class="btn-link" style="width: 35%;background: white;color: #6e6a6a;padding: 10px 0px;border: 1px solid #99cc00;border-radius: 10px;" onclick="close_modal()">취소하기</button>
                    <button type="button" class="btn-link" style="width: 35%;background: #99cc00;color: white;padding: 10px 0px;border-radius: 10px;border: 1px solid #99cc00;" onclick="req_report()">신청하기</button>
                </div>
            </div>
        </div>
    </div>
    <div id="req_detail_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false" style="top: 100px;">
        <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:300px;">
            <div class="modal-content">
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;color:black;border-bottom: 1px solid #c8c9c8;">
                    <label style="padding:7px 20px;font-weight: 100;"><img src="/iam/img/menu/req_info.png" style="width:25px;margin-bottom: 5px;margin-right: 7px;"></img>교환, 환불 신청안내</label>
                </div>
                <div class="modal-body" style="text-align:center;">
                    <p style="text-align:center;">
                        교환, 환불 가능한 기간이 지났습니다.<br>
                        교환, 환불은 배송완료 후<br>
                        7일 이내에 가능합니다.
                    </p>
                </div>
                <div class="modal-footer" style="text-align: center;padding:5px;">
                    <button type="button" class="btn-link" style="width: 50%;background: #99cc00;color: white;padding: 7px 0px;border-radius: 10px;border: 1px solid #99cc00;" onclick="close_modal()">확인</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            var zipcode = "";

            $("input[name=b_addr2]").focus(function() {
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
            $("input[name=ad_sel_addr]").on("click", function() {
                var addr = $(this).val();

                if (addr == "2") {
                    gumae2baesong(false);
                } else {
                    $("#order_address").html("<iframe id='ALLAT_MOBILE_FRAME' name='ALLAT_MOBILE_FRAME' src='https://<?= $HTTP_HOST ?>/iam/orderaddress.php' frameborder=0 width=100%px height=100%px scrolling=no></iframe>");
                    document.getElementById("order_address").style.width = "100%";
                    document.getElementById("order_address").style.height = "100%";
                    document.getElementById("order_address").style.left = 0 + "px";
                    document.getElementById("order_address").style.top = 0 + "px";
                    document.getElementById("order_address").style.display = "block";
                    $('body,html').animate({
                        scrollTop: 0,
                    }, 100);
                    // win_open('/iam/orderaddress.php', 'win_address', "483", "600");
                }
            });

            $("select[name=sel_memo]").change(function() {
                $("textarea[name=memo]").val($(this).val());
            });

            $("input[name=report_title]").on('click', function() {
                if ($(this).attr('id') == "other") {
                    $("#other_detail").show();
                } else {
                    $("#other_detail").hide();
                }
            })

            $("#report_other_msg").on('keyup', function() {
                var str = $(this).val();
                var len = str.length;
                var rest = 250 - len;
                $("#other_desc_letter").text(len + '/' + rest);
            })
        });

        // 5자리 우편번호 도로명 우편번호 창
        function win_zip(frm_name, frm_zip, frm_addr1, frm_addr2, frm_addr3, frm_jibeon) {
            var url = "https://<?= $HTTP_HOST ?>/iam/zip.php?frm_name=" + frm_name + "&frm_zip=" + frm_zip + "&frm_addr1=" + frm_addr1 + "&frm_addr2=" + frm_addr2 + "&frm_addr3=" + frm_addr3 + "&frm_jibeon=" + frm_jibeon;
            // win_open(url, "winZip", "483", "600", "yes");
            $("#order_address").html("<iframe id='ALLAT_MOBILE_FRAME' name='ALLAT_MOBILE_FRAME' src='" + url + "' frameborder=0 width=100%px height=100%px scrolling=no></iframe>");
            document.getElementById("order_address").style.width = "100%";
            document.getElementById("order_address").style.height = "100%";
            document.getElementById("order_address").style.left = 0 + "px";
            document.getElementById("order_address").style.top = 0 + "px";
            document.getElementById("order_address").style.display = "block";
            $('body,html').animate({
                scrollTop: 0,
            }, 100);
        }

        function calculate_order_price() {
            var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
            var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
            var mb_point = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //포인트결제
            var tot_price = sell_price + send_cost2 - (mb_coupon + mb_point);

            $("input[name=tot_price]").val(number_format(String(tot_price)));
        }

        function save_baesong_add() {
            var f = document.pay_form;
            if (f.b_name.value == '' || f.b_cellphone.value == '' || f.b_zip.value == '') {
                alert('배송지정보를 정확히 입력하세요.');
                return;
            } else {
                if (confirm("기본배송지로 저장할가요?")) {
                    var main_addr_val = 1;
                } else {
                    var main_addr_val = 0;
                }
                $.ajax({
                    type: "POST",
                    url: "ajax/manage_request_list.php",
                    data: {
                        mode: 'save_address',
                        name: f.b_name.value,
                        phone: f.b_cellphone.value,
                        phone1: f.b_telephone.value,
                        zip: f.b_zip.value,
                        address1: f.allat_recp_addr.value,
                        address2: f.b_addr2.value,
                        main_addr_val: main_addr_val
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == "1") {
                            alert('저장 되었습니다.');
                        } else {
                            alert('업데이트 되었습니다.');
                        }
                    },
                    error: function(request, status, error) {
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
                if (val == '') {
                    f.tot_price.value = number_format(String(tot_price));
                } else {
                    f.tot_price.value = number_format(String(tot_price - temp_point));
                }
            }
        }

        // 구매자 정보와 동일합니다.
        function gumae2baesong(checked) {
            var f = document.pay_form;

            if (checked == true) {
                // f.b_name.value = f.name.value;
                // f.b_cellphone.value = f.cellphone.value;
                // f.b_telephone.value = f.telephone.value;
                // f.b_zip.value = f.zip.value;
                // f.b_addr1.value = f.allat_recp_addr.value;
                // f.b_addr2.value = f.addr2.value;
                // f.b_addr3.value = f.addr3.value;
                // f.b_addr_jibeon.value = f.addr_jibeon.value;

                // calculate_sendcost(String(f.b_zip.value));
            } else {
                f.b_name.value = '';
                f.b_cellphone.value = '';
                f.b_telephone.value = '';
                f.b_zip.value = '';
                f.allat_recp_addr.value = '';
                f.b_addr2.value = '';
                f.b_addr3.value = '';
                f.b_addr_jibeon.value = '';

                // calculate_sendcost('');
            }
        }

        gumae2baesong(true);

        function edit_order(type, order_id) {
            $("#edit_type").val(type);
            $("#od_id").val(order_id);
            $.ajax({
                type: "POST",
                url: 'ajax/product_mng.php',
                data: {
                    mode: 'check_prod_order_date',
                    order_id: order_id
                },
                dataType: 'json',
                success: function(data) {
                    $("#ajax-loading").hide();
                    if (data.result == "0") {
                        $("#req_detail_modal").modal('show');
                        return;
                    } else {
                        $("#edit_order_modal").modal("show");
                    }
                }
            })
        }

        function req_report() {
            var type = $("input[name=report_title]:checked").val();
            var edit_type = $("#edit_type").val();
            var od_id = $("#od_id").val();
            if (type == undefined) {
                alert('해당 항목 체크를 꼭 하시거나, 추가 설명을 입력하셔야 합니다.');
                return;
            } else {
                if (type == "8") {
                    var title = $("#report_other_msg").val();
                    if (title == '') {
                        alert('해당 항목 체크를 꼭 하시거나, 추가 설명을 입력하셔야 합니다.');
                        return;
                    }
                } else {
                    var title = type;
                }
                $.ajax({
                    type: "POST",
                    url: 'ajax/product_mng.php',
                    data: {
                        mode: 'req_edit_order',
                        order_id: od_id,
                        title: title,
                        edit_type: edit_type
                    },
                    dataType: 'json',
                    success: function(data) {
                        $("#ajax-loading").hide();
                        alert('신청되었습니다.');
                        location.href = '/iam/gwc_order_list.php';
                    }
                })
            }
        }

        function close_modal() {
            $("#req_detail_modal").modal('hide');
            $("#edit_order_modal").modal("hide");
        }

        function show_prod_page(con_idx) {
            var navCase = navigator.userAgent.toLocaleLowerCase();
            if (navCase.search("android") > -1) {
                var height = $(window).height();
                console.log(height);
                // return;
                var url = "http://<?= $HTTP_HOST ?>/iam/contents_gwc.php?contents_idx=" + con_idx + "&gwc=Y&mobile=Y&order=Y";
                $("#contents_page").html("<iframe id='ALLAT_MOBILE_FRAME' name='ALLAT_MOBILE_FRAME' src='" + url + "' frameborder=0 width=100%px height=100%px scrolling=no></iframe>");
                document.getElementById("contents_page").style.width = "100%";
                document.getElementById("contents_page").style.height = height + 'px';
                document.getElementById("contents_page").style.left = 0 + "px";
                document.getElementById("contents_page").style.top = 0 + "px";
                document.getElementById("contents_page").style.display = "block";
                document.getElementById("container").style.display = "none";
                $('body,html').animate({
                    scrollTop: 0,
                }, 100);
            } else {
                window.open('/iam/contents_gwc.php?contents_idx=' + con_idx + '&gwc=Y', '_blank');
            }
        }
    </script>
    <!-- } 주문서작성 끝 -->

</div>
<div id="ajax_div" style="display:none"></div>