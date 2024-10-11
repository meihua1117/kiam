<?php 
include_once "../lib/rlatjd_fun.php";
echo '<script type="text/JavaScript" src="/iam/js/jquery-3.1.1.min.js"></script>'.PHP_EOL;

if($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/';</script>";
}
extract($_POST);
$sql_order = "select * from Gn_Gwc_Order where id='{$gs_id}'";
$result = mysqli_query($self_con,$sql_order);

?>
<script src="js/shop.js"></script>
<!-- 장바구니 옵션 시작 { -->
<form name="foption" method="post" action="ajax/manage_request_list.php" onsubmit="return formcheck(this);">
    <input type="hidden" name="mode" value="optionmod">
    <input type="hidden" name="gs_id" value="<?php echo $gs_id;?>">
    <input type="hidden" name="order_option" id="order_option" value="">
    <input type="hidden" name="cont_count" id="cont_count" value="0">
    <input type="hidden" name="gwc_ori_opt_price" id="gwc_ori_opt_price" val='0'>
    <input type="hidden" name="gwc_ori_sup_price" id="gwc_ori_sup_price" val='0'>
    <div class="sp_wrap" style="border-top:0">
        <?php
        for($i=0; $row=mysqli_fetch_array($result); $i++) {
            $sql_contents = "select contents_title, prod_order_option from Gn_Iam_Contents_Gwc where idx='{$row[contents_idx]}'";
            $res_contents = mysqli_query($self_con,$sql_contents);
            $row_contents = mysqli_fetch_array($res_contents);
            $order_option = htmlspecialchars_decode(str_replace("disabled", "", $row_contents[prod_order_option]));
            if($row[order_option] != ''){
                $val_arr = explode('>>', $row[order_option]);
                for($i = 1; $i < count($val_arr); $i+=2){
                    $com_str = 'value="'.$val_arr[$i].'"';
                    $change_str = $com_str.' selected';
                    if(strpos($order_option, $com_str) !== false){
                        $order_option = str_replace($com_str, $change_str, $order_option);
                    }
                    //인쇄>>레이져,0,999999999,10600,0,0>>패키지##기본케이스,0,999999999,0##선물포장##선물포장 있음,0,999999999,0##
                    if(strpos($val_arr[$i+1], "##") !== false){
                        $add_arr = explode("##", $val_arr[$i+1]);
                        for($j = 1; $j < count($add_arr); $j+=2){
                            $com_str1 = 'value="'.$add_arr[$j].'"';
                            $change_str1 = $com_str1.' selected';
                            if(strpos($order_option, $com_str1) !== false){
                                $order_option = str_replace($com_str1, $change_str1, $order_option);
                            }
                        }
                    }
                }
            }
        ?>
        <input type="hidden" id="it_price" name="it_price" value="<?=$row[contents_price] * 1 / $row[contents_cnt] * 1?>">
        <input type="hidden" class="io_price" name="io_price" value="<?=$row[salary_price]?$row[salary_price]:'0'?>">

        <div class="sp_tbox" style="border-top:0">
            <?if($row_contents[prod_order_option] != ''){
                echo $order_option;
            }?>
        </div>
        <div id="option_set_list">
            <ul id="option_set_added">
                <li class="sit_opt_list">
                    <div class="sp_opt_set">
                        <ul>
                            <li class="it_name"><span class="sit_opt_subj"><?=$row_contents[contents_title]?></span></li>
                            <li class="it_qty">
                                <?
                                $gwc_order_option_content = json_decode($row['gwc_order_option_content'],true);
                                    if (count($gwc_order_option_content) > 0) {
                                        foreach ($gwc_order_option_content as $data) {
                                            ?>
                                            <dl class="fl d-flex justify-content-between align-items-center">
                                                <p><? echo $data['name'];?></p>
                                                <div class="d-flex">
                                                    <dt class="fl padr3"><button type="button" class="btn_small grey">감소</button></dt>
                                                    <dt class="fl padr3"><input type="text" name="ct_qty[<?php echo $gs_id;?>][]" value="<?=$data['number'];?>" style="text-align: center"></dt>
                                                    <dt class="fl padr3"><button type="button" class="btn_small grey">증가</button></dt>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <p style="margin-right: 20px"><? echo '+ '.$data['opt_price'].' 원';?></p>
                                                    <p>&times;</p>
                                                </div>
                                            </dl>
                                            <?
                                        }
                                    } else {
                                ?>
                                <dl class="fl" style="display: inline-flex;">
                                    <dt class="fl padr3"><button type="button" class="btn_small grey">감소</button></dt>
                                    <dt class="fl padr3"><input type="text" name="ct_qty[<?php echo $gs_id;?>][]" value="<?=$row[contents_cnt]?>" style="text-align: center"></dt>
                                    <dt class="fl padr3"><button type="button" class="btn_small grey">증가</button></dt>
                                </dl>
                                <? }?>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <?php } ?>
        <div id="sit_tot_views" class="dn">
            <div class="sp_tot">
                <ul>
                    <li class='tlst strong'>총 합계금액</li>
                    <li class='trst'><span id="sit_tot_price" name="sit_tot_price" class="trss-amt"></span><span class="trss-amt">원</span></li>
                </ul>
            </div>
        </div>
        <div class="btn_confirm padb20">
            <input type="submit" value="확인" class="btn_medium" style="background-color:black;">
            <button type="button" id="mod_option_close" class="btn_medium bx-white">취소</button>
        </div>
    </div>
</form>

<script>
    $(function(){
        var opt_txt = '';
        var sup_txt = '';
        var opt_price = 0;
        var sup_price = 0;
        $(".sp_tbox dl dd select.it_option").on('change', function(){
            var option_val = $(this).val();
            $(this).find('option').each(function(){
                if($(this).attr('value') == option_val){
                    opt_txt = $(this).text();
                }
            })
            if(opt_txt.indexOf('(+') != -1 && opt_txt.indexOf('원)') != -1){
                opt_txt_arr = opt_txt.split('(+');
                opt_txt = opt_txt_arr[1].replace('원)', '');
                opt_price = opt_txt.replace(/,/g, '');
            }
            var opt_ori_price = $("#gwc_ori_opt_price").val();
            cur_price_1 = parseInt($("#sit_tot_price").text().replace(/,/g, ""));

            $("#sit_tot_price").text(number_format(cur_price_1 * 1 + opt_price * 1 - opt_ori_price * 1));
            $("#gwc_ori_opt_price").val(opt_price);
        });

        $(".sp_tbox dl dd select.it_supply").on('change', function(){
            var supply_val = $(this).val();
            $(this).find('option').each(function(){
                if($(this).attr('value') == supply_val){
                    sup_txt = $(this).text();
                }
            })
            if(sup_txt.indexOf('(+') != -1 && sup_txt.indexOf('원)') != -1){
                sup_txt_arr = sup_txt.split('(+');
                sup_txt = sup_txt_arr[1].replace('원)', '');
                sup_price = sup_txt.replace(/,/g, '');
            }
            var sup_ori_price = $("#gwc_ori_sup_price").val();
            cur_price_1 = parseInt($("#sit_tot_price").text().replace(/,/g, ""));
            $("#sit_tot_price").text(number_format(cur_price_1 * 1 + sup_price * 1 - sup_ori_price * 1));
            $("#gwc_ori_sup_price").val(sup_price);
        });
    });
    function formcheck(f) {
        var val, io_type, result = true;
        var sum_qty = 0;
        var min_qty = parseInt('<?php echo $it_buy_min_qty; ?>');
        var max_qty = parseInt('<?php echo $it_buy_max_qty; ?>');
        var $el_type = $("input[name^=io_type]");
        var str = '';
        var cnt = 0;

        $("input[name^=ct_qty]").each(function (index) {
            val = $(this).val();

            if (val.length < 1) {
                alert("수량을 입력해 주십시오.");
                result = false;
                return false;
            }

            if (val.replace(/[0-9]/g, "").length > 0) {
                alert("수량은 숫자로 입력해 주십시오.");
                result = false;
                return false;
            }

            if (parseInt(val.replace(/[^0-9]/g, "")) < 1) {
                alert("수량은 1이상 입력해 주십시오.");
                result = false;
                return false;
            }

            io_type = $el_type.eq(index).val();
            if (io_type == "0")
                sum_qty += parseInt(val);
        });

        if (!result) {
            return false;
        }

        if (min_qty > 0 && sum_qty < min_qty) {
            alert("주문옵션 개수 총합 " + number_format(String(min_qty)) + "개 이상 주문해 주십시오.");
            return false;
        }

        if (max_qty > 0 && sum_qty > max_qty) {
            alert("주문옵션 개수 총합 " + number_format(String(max_qty)) + "개 이하로 주문해 주십시오.");
            return false;
        }

        if($(".sp_tbox").text().trim() != ''){
            $(".sp_tbox dl dd select.it_option").each(function(){
                cnt++;
                str += $('.sp_tbox dl dt label[for=it_option_'+cnt+']').text()+'>>';
                str += $('.sp_tbox dl dd select[id=it_option_'+cnt+']').val()+'>>';
            });
            cnt = 0;
            $(".sp_tbox dl dd select.it_supply").each(function(){
                cnt++;
                str += $('.sp_tbox dl dt label[for=it_supply_'+cnt+']').text()+'##';
                str += $('.sp_tbox dl dd select[id=it_supply_'+cnt+']').val()+'##';
            });
            if(str.indexOf('>>>>') != -1){
                alert('옵션을 정확히 선택해 주세요.');
                return;
            }
            else{
                $("#order_option").val(str);
            }
        }
        return true;
    }
</script>
<!-- } 장바구니 옵션 끝 -->