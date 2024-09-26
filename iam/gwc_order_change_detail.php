<?php 
include "inc/header.inc.php";
if($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/';</script>";
}
extract($_GET);

?>
<script src="js/shop.js"></script>
<link rel="stylesheet" href="/iam/css/button.css">
<link rel="stylesheet" href="/iam/css/style_gwc.css">
<style>
    .wset {
        background: #f33e31;
        border: 1px solid #f33e31;
        color: #fff !important;
    }
    .sound_only {
        display: inline-block;
        position: absolute;
        top: 0;
        left: 0;
        margin: 0 !important;
        padding: 0 !important;
        width: 1px !important;
        height: 1px !important;
        font-size: 0 !important;
        line-height: 0 !important;
        overflow: hidden;
    }
    table th {
        background-color: #f8f8f8;
        white-space: nowrap;
        text-overflow: ellipsis;
        line-height: 1.3em;
    }
    .fc_999 {
        color: #999999 !important;
    }
    .padt3 {
        padding-top: 3px !important;
    }
    .btn_small:hover{
        background:lightgrey;
    }
    #content_title:after {
        display: none;
    }
    .mb-2 {
        margin-bottom: 10px;
    }
</style>
<div id="container" class="sub_wrap" style="margin-top: 165px;">
    <?php
    $sql = "select a.* from Gn_Gwc_Order a where a.id= '$id' and prod_state!=0 order by a.reg_date desc";
    $result = mysqli_query($self_con, $sql);
    $detail = mysqli_fetch_array($result);
    $sql = " select * from Gn_Iam_Contents_Gwc where idx = {$detail['contents_idx']} ";

    $res = mysqli_query($self_con, $sql);
    $row_con = mysqli_fetch_array($res);
    $state = $detail['prod_req_state'];
    if ( $state == 1) {
        $state = '취소완료';
    } elseif ($state == 2) {
        $state = '카드사환불예정';
    } elseif ($state == 3) {
        $state = '교환예정';
    } elseif ($state == 4) {
        $state = '교환완료';
    } elseif ($state == 5){
        $state = '환불완료';
    } else {
        $state = '없음';
    }
    ?>
    <div id="content_title" style="padding-bottom:20px;">
        <span>취소/반품/교환내역</span>
    </div>
    <p style="margin-bottom: 10px">주문일 : <?=$detail['reg_date']?> | 주문번호 : <?=$detail['pay_order_no']?></p>
    <div class="tbl_head02 tbl_wrap">
		<table>
            <tbody>
                <tr class="rows">
                    <td>상품</td>
                    <td>금액</td>
                    <td>진행상태</td>
                </tr>
                <tr class="rows">
                    <td style="display: flex;align-items: center">
                        <img src="<?=$row_con['contents_img']?>" alt="" style="width: 70px;height: 70px;margin-right: 10px">
                        <div>
                            <p class="fc_999"><?=$row_con['contents_title']?></p>
                            <p class="fc_999"><?=$row_con['product_model_name']?></p>
                        </div>
                    </td>
                    <td>
                        <p class="fc_999"><?php echo $detail['contents_cnt']; ?> 개</p>
                        <p class="padt3 fc_999"><?php echo number_format($detail['salary_price'] * 1 + $detail['contents_price'] * 1); ?>원</p>
                    </td>
                    <td>
                        <p class="fc_999 mb-2"><?=$state?></p>
                    </td>
                </tr>
            </tbody>
		</table>
	</div>
    <p style="margin-bottom: 10px;margin-top: 50px">상세정보</p>
    <div class="tbl_head02 tbl_wrap" style="padding: 10px 0;">
        <div style="display: flex" class="mb-2">
            <p style="width: 150px">변경접수일자</p>
            <p><?=$detail['prod_req_date']?></p>
        </div>
        <div style="display: flex" class="mb-2">
            <p style="width: 150px">변경접수번호</p>
            <p><?=$detail['prod_req_no']?></p>
        </div>
        <div style="display: flex" class="mb-2">
            <p style="width: 150px">변경처리일자</p>
            <p><?=$detail['change_prod_req_date']?></p>
        </div>
	</div>
    <div class="d-flex justify-content-between">
        <p style="margin-bottom: 10px;margin-top: 50px">환불안내</p>
        <button style="margin-bottom: 10px;margin-top: 50px">취소영수증확인</button>
    </div>
    <div class="row">
        <div class="tbl_head02 tbl_wrap" style="padding: 10px 0;border-bottom: none">
            <div class="col-md-6">
                <div class="mb-2 d-flex justify-content-between">
                    <p style="width: 150px">상품금액</p>
                    <p><?=number_format($detail['contents_price'] * 1).' 원';?></p>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <p style="width: 150px">배송비</p>
                    <p><?=number_format($detail['salary_price'] * 1).' 원';?></p>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <p style="width: 150px">반품비</p>
                    <p><?='3,000 원'?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row" style="margin-bottom: 10px">
                    <div class="col-md-6">환불수단</div>
                    <div class="col-md-6 text-right"><?=$state?></div>
                </div>
                <div class="row">
                    <div class="col-md-6">환불완료</div>
                    <div class="col-md-6">
                        <p class="text-right">카드결제 <?=number_format($detail['contents_price'] * 1  + $detail['salary_price'] * 1 - $detail['use_point'] * 1);?> 원</p>
                        <p class="text-right">포인트결제 <?=number_format($detail['use_point'])?> P</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p style="border-top: 1px solid grey;font-size: 12px;text-align: right">카드사로 결제 취소 요청이 전달된 후 환불까지 평일 기준 3~7일이 소요됩니다.</p>
    <div class="d-flex justify-content-center">
        <a type="button" style="background-color: #5f9d2b;cursor: pointer; color: white;padding: 5px 20px;margin-top: 10px;letter-spacing: 1px">목록</a>
    </div>

    <script>
        $(function () {
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

        function fsubmit_check(f) {
            if ($("input[name^=ct_chk]:checked").length < 1) {
                alert("구매하실 상품을 하나이상 선택해 주십시오.");
                return false;
            }

            return true;
        }

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
                        location.reload();
                    }
                })
            }
        }

        function close_modal(){
            $("#req_detail_modal").modal('hide');
            $("#edit_order_modal").modal("hide");
        }
    </script>
</div>
<div id="ajax_div" style="display:none"></div>