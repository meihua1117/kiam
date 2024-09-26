<?
include "inc/header.inc.php";
if($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/';</script>";
}
extract($_GET);
$sql = "select * from Gn_Gwc_Order where mem_id='{$_SESSION['iam_member_id']}' and page_type=1 order by reg_date desc";
$result = mysqli_query($self_con, $sql);
$cart_count = mysqli_num_rows($result);
?>
<script src="js/shop.js"></script>
<link rel="stylesheet" href="/iam/css/button.css">
<link rel="stylesheet" href="/iam/css/style_gwc.css">
<style>

</style>
<div id="container" class="sub_wrap" style="margin-top: 85px;">
    <div id="content_title">
        <span>장바구니</span>
    </div>

    <!-- 장바구니 시작 { -->

    <div class="stit_txt">
        ※ 총 <?=number_format($cart_count); ?>개의 상품이 담겨 있습니다.
    </div>

    <div id="sod_bsk">
        <form name="frmcartlist" id="sod_bsk_list" method="post" action="ajax/manage_request_list.php">
            <input type="hidden" name="mode" value="">
            <input type="hidden" name="cart_ids" value="">
            <input type="hidden" name="shop" value="<?=$_GET['shop']?>">
            <? if ($cart_count) { ?>
            <div id="sod_chk">
                <input type="checkbox" name="ct_all" value="1" id="ct_all" checked="checked">
                <label for="ct_all">전체상품 선택</label>
            </div>
            <? } ?>

            <ul class="sod_list">
                <?
                $tot_sell_price = 0;
                $tot_opt_price	= 0;

                for ($i = 0; $row = mysqli_fetch_array($result); $i++) {
                    if (strpos($row['order_option'], "gallery>>") == 0)
                        $sql_contents = "select contents_title, contents_img from Gn_Iam_Contents where idx={$row['contents_idx']}";
                    else
                        $sql_contents = "select contents_title, contents_img from Gn_Iam_Contents_Gwc where idx={$row['contents_idx']}";
                    $res_contents = mysqli_query($self_con,$sql_contents);
                    $row_contents = mysqli_fetch_array($res_contents);
                    if(strpos($row_contents['contents_img'], ",") !== false){
                        $img_arr = explode(",", $row_contents['contents_img']);
                        $img_link = $img_arr[0];
                    } else {
                        $img_link = $row_contents['contents_img'];
                    }
                    $order_option = $row['order_option'];
                ?>
                <li class="sod_li">
                        <input type="hidden" name="gs_id[<? echo $i; ?>]" value="<? echo $row['id']; ?>">
                    <div class="li_chk">
                            <label for="ct_chk_<? echo $i; ?>" class="sound_only">상품</label>
                            <input type="checkbox" class="gwc_card_sel" name="ct_chk[<? echo $i; ?>]" value="<? echo $row['id']; ?>" id="ct_chk_<? echo $i; ?>" checked="checked">
                    </div>
                    <div class="li_name">
                        <a>
                                <?
                            $gwc_order_option_content = json_decode($row['gwc_order_option_content'],true);

                            if ($gwc_order_option_content > 0) {
                                $content = '';
                                foreach ($gwc_order_option_content as $data) {
                                    $content .= $data['name'].' : '.$data['number'].'개 +'.$data['opt_price'].'원, ';
                                }
                                echo stripslashes($row_contents['contents_title'].'<p>'.$content.'</p>');
                            } else {
                                echo stripslashes($row_contents['contents_title']);
                            }
                            ?>
                        </a>
                        <span class="total_img"><img src="<?=$img_link?>" style="width:80px;height:80px;"></span>
                        <div class="li_mod" style="padding-left:100px;margin-top: 11px;">
                                <button type="button" id="mod_opt_<? echo $row['id']; ?>" class="mod_btn mod_options">옵션변경/추가</button>
                        </div>
                    </div>
                    <div class="li_prqty">
                        <span class="prqty_price li_prqty_sp"><span>판매가</span>
                                <? echo number_format($row['contents_price'] * 1 / $row['contents_cnt'] * 1); ?></span>
                        <span class="prqty_qty li_prqty_sp"><span>수량</span>
                                <? echo number_format($row['contents_cnt']); ?></span>
                        <span class="prqty_sc li_prqty_sp"><span>배송비</span>
                                <? echo number_format($row['salary_price']); ?></span>
                    </div>
                    <!-- <div class="li_total">
                        <span class="total_price total_span"><span>소계</span>
                            <strong><? echo number_format($sell_price); ?></strong></span>
                        <span class="total_point total_span"><span>적립포인트</span>
                            <strong><? echo number_format($point); ?></strong></span>
                    </div> -->
                </li>
                <?
                $tot_sell_price += $row['contents_price'] * 1 + $row['salary_price'] * 1;
                $tot_opt_price += $row['salary_price'] * 1;
                } // for 

                if($i == 0) {
                    echo '<li class="empty_list">장바구니에 담긴 상품이 없습니다.</li>';
                }
                ?>
            </ul>

            <? if ($i > 0) { ?>
            <dl id="sod_bsk_tot">
                    <? if ($tot_opt_price > 0) { // 배송비가 0 보다 크다면 (있다면) 
                    ?>
                <dt class="sod_bsk_dvr" style="margin-left: 1px;"><span>배송비</span></dt>
                        <dd class="sod_bsk_dvr" style="margin-left: -1px;"><strong id="salary_sum"><? echo number_format($tot_opt_price); ?> 원</strong></dd>
                    <? } ?>

                    <? if ($tot_sell_price > 0) { ?>
                <dt class="sod_bsk_cnt" style="height: 41px;"><span>총계</span></dt>
                        <dd class="sod_bsk_cnt"><strong id="price_sum"><? echo number_format($tot_sell_price); ?> 원</strong></dd>
                <!-- <dt><span>포인트</span></dt>
                <dd><strong><? echo number_format($tot_point); ?> P</strong></dd> -->
                    <? } ?>
            </dl>
            <? } ?>

            <div id="sod_bsk_act" class="btn_confirm">
                <?
                if ($_GET['shop'] == "gallery") { ?>
                    <a href="/?cur_win=iam_mall&mall_type=main_mall&mall_type1=gallery" class="btn_medium bx-black">쇼핑 계속하기</a>
                <? } else { ?>
                <a href="javascript:gwc_tab();" class="btn_medium bx-black">쇼핑 계속하기</a>
                <? }
                if ($i > 0) { ?>
                <button type="button" onclick="return form_check('buy');" class="btn_medium wset">주문하기</button>
                    <div>
                        <button type="button" onclick="return form_check('seldel');" class="btn01">선택삭제</button>
                        <button type="button" onclick="return form_check('alldel');" class="btn01">비우기</button>
                    </div>
                <? } ?>
            </div>
        </form>
    </div>
    <!-- } 장바구니 끝 -->
    <script>
        $(function () {
            var close_btn_idx;
            var salary = 0;
            var cnt = 0;
            var con_price = 0;

            // 선택사항수정
            $(".mod_options").click(function () {
                var gs_id = $(this).attr("id").replace("mod_opt_", "");
                var $this = $(this);
                close_btn_idx = $(".mod_options").index($(this));
                console.log(gs_id);
                // return;
                $.post(
                    "gwc_cart_option.php", {
                        gs_id: gs_id
                    },
                    function (data) {
                        console.log(data);
                        $("#ajax-loading").delay(10).hide(1);
                        $("#mod_option_frm").remove();
                        $this.after("<div id=\"mod_option_frm\"></div>");
                        $("#mod_option_frm").html(data);
                        price_calculate();
                    }
                );
            });

            // 모두선택
            $("input[name=ct_all]").click(function () {
                if ($(this).is(":checked"))
                    $("input[name^=ct_chk]").prop("checked", true);
                else
                    $("input[name^=ct_chk]").prop("checked", false);
            });

            // 옵션수정 닫기
            $(document).on("click", "#mod_option_close", function () {
                $("#mod_option_frm").remove();
                $("#win_mask, .window").hide();
                $(".mod_options").eq(close_btn_idx).focus();
            });
            $("#win_mask").click(function () {
                $("#mod_option_frm").remove();
                $("#win_mask").hide();
                $(".mod_options").eq(close_btn_idx).focus();
            });

            $('#ct_all').on('change', function(){
                if($(this).prop('checked')){
                    $("#salary_sum").text(number_format('<?=$tot_opt_price?>') + ' 원');
                    $("#price_sum").text(number_format('<?=$tot_sell_price?>') + ' 원');
                } else {
                    $("#salary_sum").text(number_format(0) + ' 원');
                    $("#price_sum").text(number_format(0) + ' 원');
                }
            })

            $(".gwc_card_sel").on("click", function(){
                var sum_price = no_comma($("#price_sum").text().replace('원', '').trim());
                var sum_salary = no_comma($("#salary_sum").text().replace('원', '').trim());
                $(this).closest('li.sod_li').find('div.li_prqty span.li_prqty_sp').each(function(){
                    // console.log($(this).text());
                    if($(this).text().indexOf('판매가') != -1){
                        con_price = no_comma($(this).text().replace('판매가', '').trim());
                    }
                    if($(this).text().indexOf('수량') != -1){
                        cnt = no_comma($(this).text().replace('수량', '').trim());
                    }
                    if($(this).text().indexOf('배송비') != -1){
                        salary = no_comma($(this).text().replace('배송비', '').trim());
                    }
                })
                console.log((con_price * 1) * (cnt * 1), salary * 1);
                if($(this).prop('checked')){
                    // alert('ok');
                    var show_sal = sum_salary * 1 + salary * 1;
                    var show_pri = sum_price * 1 + (con_price * 1) * (cnt * 1) + salary * 1;
                } else {
                    var show_sal = sum_salary * 1 - salary * 1;
                    var show_pri = sum_price * 1 - (con_price * 1) * (cnt * 1) - salary * 1;
                }
                $("#salary_sum").text(number_format(show_sal) + ' 원');
                $("#price_sum").text(number_format(show_pri) + ' 원');
            })
        });

        function fsubmit_check(f) {
            if ($("input[name^=ct_chk]:checked").length < 1) {
                alert("구매하실 상품을 하나이상 선택해 주십시오.");
                return false;
            }

            return true;
        }

        function form_check(mode) {
            var f = document.frmcartlist;
            var ids_arr = new Array();
            // var cnt = f.records.value;

            if (mode == "buy") {
                if ($("input[name^=ct_chk]:checked").length < 1) {
                    alert("주문하실 상품을 하나이상 선택해 주십시오.");
                    return false;
                }
                f.mode.value = mode;
                $("input[name^=ct_chk]:checked").each(function(){
                    ids_arr.push($(this).val());
                })
                f.cart_ids.value = ids_arr.toString();
                f.submit();
            } else if (mode == "alldel") {
                f.mode.value = mode;
                f.submit();
            } else if (mode == "seldel") {
                if ($("input[name^=ct_chk]:checked").length < 1) {
                    alert("삭제하실 상품을 하나이상 선택해 주십시오.");
                    return false;
                }

                f.mode.value = mode;
                f.submit();
            }
            return true;
        }
    </script>
</div>
<div id="ajax_div" style="display:none"></div>