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
    /* .w90 {
        width: 90px !important;
    }
    .w100 {
        width: 100px !important;
    }
    .w140 {
        width: 140px !important;
    } */
    .zoom {
        transition: transform .2s; /* Animation */
    }
    .zoom:hover {
        transform: scale(4); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        border:1px solid #0087e0;
        box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
    }
</style>
<div id='contents_page' style='left:0px; top:0px; width:320px; height:600px; position:absolute;  z-index:1000; display:none; background-color:white;'></div>
<div id="container" class="sub_wrap" style="margin-top: 95px;">
    <div id="content_title" style="padding-bottom:20px;">
        <span>공급사 신청 내역</span>
    </div>

    <div style="padding:10px;position: relative;height: 50px;">
        <form method="get" name="search_form" id="search_form " class="form-inline" style="display:flex;position: absolute;">
            <input type="text" name="search_key" id="search_key"   style="width:200px" class="form-control input-sm pull-right" placeholder="상품명" value="<?=$_REQUEST['search_key']?$search_key:''?>">
            <button style="margin-left:5px" class="btn btn-sm btn-default pull-right"><i class="fa fa-search"></i></button>
        </form>
        <div style="position: absolute;right: 10px;">
            <form name="fsellerexcel" id="fsellerexcel" method="post" onsubmit="return fsellerexcel_submit(this);" enctype="MULTIPART/FORM-DATA" style="display: inline;">
            <input type="submit" style="background-color: #82c836;color: white;padding: 4px;border: 1px solid #82c836;" value="일괄등록">
            <input type="file" name="excelfile" style="display: inline;width: 190px;">
            </form>
            <button style="background-color: #82c836;color: white;padding: 4px;border: 1px solid #82c836;" onclick="sample_download()">샘플다운로드</button>
            <button style="background-color: #82c836;color: white;padding: 4px;border: 1px solid #82c836;" onclick="new_req()">새로등록</button>
            <button style="background-color: white;border: 1px solid;padding: 4px;" onclick="deleteMultiRow()">선택삭제</button>
        </div>
    </div>
    <div class="tbl_head02 tbl_wrap">
		<table id="example1">
		<colgroup>
			<col class="w40">
			<col class="w80">
            <col class="w80">
            <col class="w80">
            <col class="w80">
            <col class="w80">
            <col class="w80">
            <col class="w80">
			<col class="w40">
			<col class="w40">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">번호</th>
			<th scope="col">상품명</th>
			<th scope="col">이미지</th>
			<th scope="col">시중가</th>
            <th scope="col">할인가</th>
			<th scope="col">공급가</th>
			<th scope="col">생산가</th>
			<th scope="col">등록일</th>
            <th scope="col">관리</th>
			<th scope="col">상태</th>
		</tr>
		</thead>
		<tbody>
		<?php
        $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
        $startPage = $nowPage?$nowPage:1;
        $pageCnt = 50;

        $searchStr = '';
        if($search_key){
            $searchStr .= " AND contents_title like '%$search_key%'";
        }

        $sql = "select * from Gn_Iam_Contents_Gwc where mem_id='{$_SESSION['iam_member_id']}' and provider_req_prod='Y' ".$searchStr." order by req_data desc";
        $res_cnt = mysqli_query($self_con,$sql);
        $cart_count = mysqli_num_rows($res_cnt);

        $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
        $number = $cart_count - ($nowPage - 1) * $pageCnt;
        $sql .= $limitStr;
        $result = mysqli_query($self_con,$sql);
		for($i=0; $row=mysqli_fetch_array($result); $i++) {
			if(strpos($row[contents_img], ",") !== false){
                $img_link_arr = explode(",", $row[contents_img]);
                $img_link = trim($img_link_arr[0]);
            }
            else{
                $img_link = $row[contents_img];
            }

            if($row[public_display] == "N"){
                $mng = '<a href="javascript:edit_prod('.$row['idx'].')">수정</a>';
                $allow = "대기";
            }
            else{
                $mng = '';
                $allow = "승인";
            }
		?>
		<tr class="rows">
			<td class="tac"><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['idx']?>"><?=$number--?></td>
            <td class="tac"><?=$row[contents_title]?></td>
            <td class="tac"><img class="zoom" src="<?=$img_link?>" style="width:50px;"></a></td>
            <td class="tac"><?=$row[contents_price]?></td>
            <td class="tac"><?=$row[contents_sell_price]?></td>
            <td class="tac"><?=$row[send_provide_price]?></td>
            <td class="tac"><?=$row[prod_manufact_price]?></td>
            <td class="tac"><?=$row[req_data]?></td>
            <td class="tac"><?=$mng?></td>
            <td class="tac"><?=$allow?></td>
		</tr>
		<?php 
		}
		if($i==0)
			echo '<tr><td colspan="10" class="empty_list">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>
    <div id="req_pagenation" style="position: absolute;left: 36%;">
        <?echo drawPagingAdminNavi_iama($cart_count, $nowPage,$pageCnt);?>
    </div>
    <div id="edit_order_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:500px;">
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <img src="/iam/img/menu/icon_close.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                    </button>
                </div>
                <div class = "modal-title" style="width:100%;font-size:18px;text-align: center;color:white;border-bottom: 1px solid #c8c9c8;background-color: #82c836;">
                    <label style="padding:7px 20px">신청상품 수정</label>
                </div>
                <div class="modal-header" style="text-align:left;">
                <form method="post" id="req_provider_form" name="req_provider_form" action="/iam/ajax/product_mng.php"  enctype="multipart/form-data">
                <section class="content">
                <div class="row">
                    <div class="box">       
                        <div style="padding:20px;">
                        <input type="hidden" id="mode" name="mode" value="req_provider">
                        <input type="hidden" id="gongup_id" name="gongup_id" value="<?=$member_iam['mem_id']?>">
                        <input type="hidden" id="gwc_worker_state" name="gwc_worker_state" value="<?=$member_iam[gwc_worker_state]?'1':'0'?>">
                        <div style="display:flex;margin-top:10px;">
                            공급사명:<input type="text" name="provider_name" id="provider_name" value="<?=$member_iam[gwc_provider_name]?>" style="width: 200px;height: 15px;padding: 10px;margin-left: 45px;border: 1px solid;">
                        </div>
                        <div style="display:<?=$member_iam[gwc_worker_state]?'flex':'none'?>;margin-top:10px;" id="worker_no_side">
                            사업자등록번호:<input type="text" name="worker_no" id="worker_no" value="<?=$member_iam[gwc_worker_no]?>" style="width: 200px;height: 15px;padding: 10px;margin-left: 6px;border: 1px solid;">
                        </div>
                        <div style="display:<?=$member_iam[gwc_worker_state]?'flex':'none'?>;margin-top:10px;" id="worker_img_side">
                            사업자등록증:<input type="file" name="worker_img" id="worker_img" value="<?=$member_iam[gwc_worker_img]?>" style="width: 200px;margin-left: 20px;border: 1px solid;">
                        </div>
                        <?if($member_iam[gwc_worker_img]){?>
                        <img src="<?="https://www.kiam.kr".$member_iam[gwc_worker_img]?>" style="width:80px;margin-left:100px;">
                        <?}?>
                        <div style="margin-top: 10px;width: 300px;text-align: left;height: 25px;">
                            <input type="checkbox" name="gwc_worker_state_" id="gwc_worker_state_" <?=$member_iam[gwc_worker_state]?'checked':''?> style="vertical-align: text-top;margin-left:100px;" onclick="gwc_worker()"><span style="margin-left:7px;">사업자</span>
                            <a href="javascript:save_req_provider();" id="save_req_provider_side" style="background-color: black;color: white;padding: 5px;border-radius: 7px;margin: 5px;cursor: pointer;float:right;" <?=$member_iam[gwc_worker_state]?'':'hidden'?>>저장</a>
                        </div>
                        </div>
                    </div>
                </div>
                </section>
                </form>
                </div>
                <div class="modal-header" style="text-align:left;">
                    <form method="post" id="dForm" name="dForm" action="/iam/ajax/contents.proc.php"  enctype="multipart/form-data">
                        <input type="hidden" name="contents_idx" value="" />
                        <input type="hidden" name="post_type" value="edit" />
                        <input type="hidden" name="contents_type" value="3" />
                        <input type="hidden" name="mem_id" value="<?=$_SESSION['iam_member_id']?>" />
                        <input type="hidden" name="admin" value="1" />
                        <input type="hidden" name="provider" value="Y" />
                        <input type="hidden" name="provider_iam" value="Y" />
                        <input type="hidden" name="card_short_url" value="" />
                        <input type="hidden" name="gwc_con_state" value="" >
                        <input type="hidden" name="westory_card_url" value="" />
                        <input type="hidden" name="contents_url_title" value="" />
                        <input type="hidden" name="except_keyword" value="" />
                        <input type="hidden" name="contents_display" value="" />
                        <input type="hidden" name="contents_westory_display" value="" />
                        <input type="hidden" name="contents_type_display" value="" />
                        <input type="hidden" name="contents_user_display" value="" />
                        <input type="hidden" name="contents_footer_display" value="" />
                        <input type="hidden" name="contents_share_text" value="" />
                        <input type="hidden" name="card_idx" value="" />
                        <input type="hidden" name="init_reduce_val" value="" />
                        <input type="hidden" name="reduce_val" value="" />
                        <input type="hidden" name="landing_mode" value="" />
                        <input type="hidden" name="contents_iframe" value="" />
                        <input type="hidden" name="source_iframe" value="" />
                        <!-- Main content -->
                        <section class="content">
                        <div class="row">
                            <div class="box">             
                                <div class="box-body">
                                <table class="table table-bordered table-striped" style="width:94%;margin-left:14px;">
                                    <!-- <colgroup>
                                    <col width="30%">
                                    <col width="60%">
                                    </colgroup> -->
                                    <tbody>
                                    <tr>
                                        <th>콘텐츠 제목</th>
                                        <td>
                                            <input type="text" style="width:90%;border: 1px solid;" name="contents_title" id="contents_title" value="" > 
                                        </td>
                                    </tr>
                                    <tr>                      
                                        <th>콘텐츠 내용</th>
                                        <td>
                                            <textarea name="contents_desc" id="contents_desc"  style="width:90%;height:100px;border: 1px solid;" ></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>이미지</th>
                                        <td>
                                        <input type="file" style="width:53%;border: 1px solid;" name="contents_img[]" id="contents_img" multiple>
                                        <input type="text" style="width:90%;" name="contents_img_url" id="contents_img_url" value="" readonly>
                                        </td>
                                    </tr>      
                                    <tr>
                                        <th>링크주소</th>
                                        <td>
                                            <input type="text" style="width:90%;border: 1px solid;" name="contents_url" id="contents_url" value="" >
                                        </td>
                                    </tr>
                                    <input type="hidden" name="open_type" id="open_type" value="" >
                                    <tr>                      
                                        <th>상품코드</th>
                                        <td>
                                        <input type="text" style="width:90%;border: 1px solid;" name="product_code" id="product_code" value="" >
                                        </td>
                                    </tr>
                                    <tr>                      
                                        <th>상품모델명</th>
                                        <td>
                                        <input type="text" style="width:90%;border: 1px solid;" name="product_model_name" id="product_model_name" value="" >
                                        </td>
                                    </tr>
                                    <tr>                      
                                        <th>상품분류정보</th>
                                        <td>
                                        <input type="text" style="width:90%;border: 1px solid;" name="product_seperate" id="product_seperate" value="" >
                                        </td>
                                    </tr>
                                    <tr>                      
                                        <th>시중가</th>
                                        <td>
                                        <input type="text" style="width:90%;border: 1px solid;" name="contents_price" id="contents_price" value="" >
                                        </td>
                                    </tr>
                                    <tr>                      
                                        <th>할인가</th>
                                        <td>
                                        <input type="text" style="width:90%;border: 1px solid;" name="contents_sell_price" id="contents_sell_price" value="" >
                                        </td>
                                    </tr>
                                    <tr>                      
                                        <th>공급가</th>
                                        <td>
                                        <input type="text" style="width:90%;border: 1px solid;" name="send_provide_price" id="send_provide_price" value="" >
                                        </td>
                                    </tr>
                                    <tr>                      
                                        <th>생산가</th>
                                        <td>
                                        <input type="text" style="width:90%;border: 1px solid;" name="prod_manufact_price" id="prod_manufact_price" value="" >
                                        </td>
                                    </tr>
                                    <tr>                      
                                        <th>배송료</th>
                                        <td>
                                        <input type="text" style="width:90%;border: 1px solid;" name="send_salary_price" id="send_salary_price" value="" >
                                        </td>
                                    </tr>
                                    <tr>                      
                                        <th>배송정보</th>
                                        <td>
                                            <input type="hidden" id="check_deliver_id_state" name="check_deliver_id_state" value="N">
                                            <input type="hidden" id="deliver_id_code" name="deliver_id_code" value="">
                                            <input type="checkbox" id="same_gonggupsa" name="same_gonggupsa" onclick="self_deliver()" style="vertical-align: text-top;margin-right:5px;">공급사와 동일
                                            <div style="display:flex;margin-top:10px;">
                                                아이디:<input type="text" name="deliver_id" id="deliver_id" value="" style="width: 200px;height: 15px;padding: 10px;margin-left: 20px;border: 1px solid;"><a href="javascript:check_deliver_id();" id="check_deliver_id" style="background-color: #82c836;color: white;padding: 2px 5px;margin: -1px 5px;cursor: pointer;">확인</a>
                                            </div>
                                            <div style="display:flex;margin-top:10px;">
                                                이름:<input type="text" name="deliver_name" id="deliver_name" value="" style="width: 200px;height: 15px;padding: 10px;margin-left: 33px;border: 1px solid;" readonly>
                                            </div>
                                            <div style="display:flex;margin-top:10px;">
                                                핸드폰:<input type="text" name="deliver_phone" id="deliver_phone" value="" style="width: 200px;height: 15px;padding: 10px;margin-left: 20px;border: 1px solid;" readonly>
                                            </div>
                                            <div style="display:flex;margin-top:10px;">
                                                주소:<input type="text" name="deliver_addr" id="deliver_addr" value="" style="width: 200px;height: 15px;padding: 10px;margin-left: 33px;border: 1px solid;" readonly>
                                            </div>
                                            <div style="display:flex;margin-top:10px;">
                                                이메일:<input type="text" name="deliver_email" id="deliver_email" value="" style="width: 200px;height: 15px;padding: 10px;margin-left: 20px;border: 1px solid;" readonly>
                                            </div>
                                            <div style="display:flex;margin-top:10px;">
                                                입금계좌:<input type="text" name="deliver_bank" id="deliver_bank" value="" style="width: 70px;height: 15px;padding: 10px;margin-left: 8px;border: 1px solid;" readonly>
                                                <input type="text" name="deliver_owner" id="deliver_owner" value="" style="width: 70px;height: 15px;padding: 10px;border: 1px solid;" readonly>
                                                <input type="text" name="deliver_account" id="deliver_account" value="" style="width: 70px;height: 15px;padding: 10px;border: 1px solid;" readonly>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>                      
                                        <th>카테고리</th>
                                        <td>
                                        <?
                                        $sql5="select card_short_url,card_title from Gn_Iam_Name_Card where mem_id = 'iamstore' and idx not in(934328, 2477701, 1274691, 1268514) order by req_data asc";
                                        $result5=mysqli_query($self_con,$sql5);
                                        $i = 0;
                                        while($row5=mysqli_fetch_array($result5)) {
                                            ?>
                                            <input type="radio" name="gwc_card_url"
                                                    class="my_info_check" id="gwc_card_check_<?= $row5[card_short_url] ?>"
                                                    value="<?= $row5[card_short_url] ?>">
                                            <?
                                                echo($i+1);
                                            echo "(".$row5[card_title].")";?>
                                            <?$i++;
                                        }
                                        ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                            <div class="box-footer" style="text-align:center">
                                <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                            </div>
                        </div><!-- /.row -->
                        </section><!-- /.content -->
                    </form>
                </div>
                <!-- <div class="modal-body" id="other_detail" style="text-align:center;" hidden>
                    <textarea id="report_other_msg" maxlength="250" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;" placeholder="추가설명이 필요하면 입력해 주세요."></textarea>
                    <p id="other_desc_letter" style="text-align: right;">0/250</p>
                </div>
                <div class="modal-footer" style="text-align: center;padding:5px;">
                    <button type="button" class="btn-link" style="width: 35%;background: white;color: #6e6a6a;padding: 10px 0px;border: 1px solid #82c836;border-radius: 10px;" onclick="close_modal()">취소하기</button>
                    <button type="button" class="btn-link" style="width: 35%;background: #82c836;color: white;padding: 10px 0px;border-radius: 10px;border: 1px solid #82c836;" onclick="req_report('')">신청하기</button>
                </div> -->
            </div>
        </div>
    </div>
    <script>
        $(function () {
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
        });

        function deleteMultiRow() {
            var check_array = $("#example1").children().find(".check");
            var no_array = [];
            var index = 0;
            check_array.each(function(){
                if($(this).prop("checked") && $(this).val() > 0)
                    no_array[index++] = $(this).val();
            });

            if(no_array.length == 0){
                alert("삭제할 상품을 선택하세요.");
                return;
            }
            if(confirm('삭제하시겠습니까?')) {
                $.ajax({
                    type:"POST",
                    url:"/admin/ajax/_db_controller.php",
                    dataType:"json",
                    data:{mode:"delete_gwc_contents", id:no_array.toString()},
                    success: function(data){
                        if(data == 1){
                            alert('삭제 되었습니다.');
                            window.location.reload();
                        }
                    }
                })
            }
        }

        function edit_prod(idx){
            $.ajax({
                type:"POST",
                url:"/iam/ajax/product_mng.php",
                dataType:"json",
                data:{mode:"get_prod_info", id:idx},
                success: function(data){
                    if(data.result == 1){
                        $("input[name=contents_idx]").val(idx);
                        $("input[name=card_short_url]").val(data.card_short_url);
                        $("input[name=westory_card_url]").val(data.westory_card_url);
                        $("input[name=contents_url_title]").val(data.contents_url_title);
                        $("input[name=except_keyword]").val(data.except_keyword);
                        $("input[name=contents_display]").val(data.contents_display);
                        $("input[name=contents_westory_display]").val(data.contents_westory_display);
                        $("input[name=contents_type_display]").val(data.contents_type_display);
                        $("input[name=contents_user_display]").val(data.contents_user_display);
                        $("input[name=contents_footer_display]").val(data.contents_footer_display);
                        $("input[name=contents_share_text]").val(data.contents_share_text);
                        $("input[name=card_idx]").val(data.card_idx);
                        $("input[name=init_reduce_val]").val(data.init_reduce_val);
                        $("input[name=reduce_val]").val(data.reduce_val);
                        $("input[name=landing_mode]").val(data.landing_mode);
                        $("input[name=contents_iframe]").val(data.contents_iframe);
                        $("input[name=source_iframe]").val(data.source_iframe);
                        $("input[name=contents_title]").val(data.contents_title);
                        $("textarea[name=contents_desc]").val(data.contents_desc);
                        // $("textarea[name=contents_desc]").html(data.contents_desc);
                        $("input[name=gwc_con_state]").val(data.gwc_con_state);
                        $("input[name=contents_img_url]").val(data.contents_img);
                        $("input[name=contents_url]").val(data.contents_url);
                        $("input[name=open_type]").val(data.open_type);
                        $("input[name=product_code]").val(data.product_code);
                        $("input[name=product_model_name]").val(data.product_model_name);
                        $("input[name=product_seperate]").val(data.product_seperate);
                        $("input[name=contents_price]").val(data.contents_price);
                        $("input[name=contents_sell_price]").val(data.contents_sell_price);
                        $("input[name=send_provide_price]").val(data.send_provide_price);
                        $("input[name=prod_manufact_price]").val(data.prod_manufact_price);
                        $("input[name=send_salary_price]").val(data.send_salary_price);
                        $("input[id=gwc_card_check_"+data.card_short_url+"]").prop('checked', true);

                        $("#deliver_id_code").val(data.delivery_id_code);
                        $("#deliver_id").val(data.delivery_mem_id);
                        $("#deliver_name").val(data.delivery_mem_name);
                        $("#deliver_phone").val(data.delivery_mem_phone);
                        $("#deliver_addr").val(data.delivery_mem_add1);
                        $("#deliver_email").val(data.delivery_mem_email);
                        $("#deliver_bank").val(data.delivery_bank_name);
                        $("#deliver_owner").val(data.delivery_bank_owner);
                        $("#deliver_account").val(data.delivery_bank_account);
                        if($("#deliver_id").val() != '' && $("#deliver_name").val() != '' && $("#deliver_phone").val() != '' && $("#deliver_addr").val() != '' && $("#deliver_email").val() != '' && $("#deliver_bank").val() != '' && $("#deliver_owner").val() != '' && $("#deliver_account").val() != ''){
                            $("#check_deliver_id_state").val('Y');
                        }
                        else{
                            $("#check_deliver_id_state").val('N');
                        }
                        if(data.same_id == "Y"){
                            $("#same_gonggupsa").prop('checked', true);
                        }
                    }
                }
            })
            $("#ajax-loading").hide();
            $("#edit_order_modal").modal('show');
        }

        function form_save(){
            if($("#deliver_id").val() == ''){
                alert('배송정보를 확인해주세요.');
                return;
            }
            if($("#check_deliver_id_state").val() == "N"){
                alert('배송정보를 모두 채워야합니다. 배송자 아이디 회원정보를 수정해주세요.');
            }
            if($("#contents_img").val() != ''){
                $("#contents_img_url").val('');
            }
            $('#dForm').submit();
        }

        function new_req(){
            gwc_tab('provider');
        }

        function self_deliver(){
            if($("#same_gonggupsa").prop('checked')){
                $("#deliver_id").val('<?=$_SESSION['iam_member_id']?>');
                $("#deliver_name").val('<?=$member_iam['mem_name']?>');
                $("#deliver_phone").val('<?=$member_iam['mem_phone']?>');
                $("#deliver_addr").val('<?=$member_iam[mem_add1]?>');
                $("#deliver_email").val('<?=$member_iam['mem_email']?>');
                $("#deliver_bank").val('<?=$member_iam[bank_name]?>');
                $("#deliver_owner").val('<?=$member_iam[bank_owner]?>');
                $("#deliver_account").val('<?=$member_iam[bank_account]?>');
                $("#check_deliver_id").hide();
                $("#deliver_id_code").val('<?=$member_iam['mem_code']?>');
                if($("#deliver_id").val() != '' && $("#deliver_name").val() != '' && $("#deliver_phone").val() != '' && $("#deliver_addr").val() != '' && $("#deliver_email").val() != '' && $("#deliver_bank").val() != '' && $("#deliver_owner").val() != '' && $("#deliver_account").val() != ''){
                    $("#check_deliver_id_state").val('Y');
                }
                else{
                    $("#check_deliver_id_state").val('N');
                }
            }
            else{
                $("#deliver_id").val('');
                $("#deliver_name").val('');
                $("#deliver_phone").val('');
                $("#deliver_addr").val('');
                $("#deliver_email").val('');
                $("#deliver_bank").val('');
                $("#deliver_owner").val('');
                $("#deliver_account").val('');
                $("#deliver_id_code").val('');
                $("#check_deliver_id_state").val('N');
                $("#check_deliver_id").show();
            }
        }

        function check_deliver_id(){
            var req_deliver_id = $("#deliver_id").val();
            if(req_deliver_id == ''){
                alert('아이디를 입력해 주세요.');
                $("#deliver_id").focus();
                $("#check_deliver_id_state").val('N');
                return;
            }
            else{
                $.ajax({
                    type:"POST",
                    url:"/ajax/get_mem_address.php",
                    dataType:"json",
                    data:{
                        deliver_id:req_deliver_id,
                        mode:"check_deliver_id"
                    },
                    success: function(data){
                        if(data.result == "0"){
                            alert('정확한 아이디를 입력하세요.');
                            $("#check_deliver_id_state").val('N');
                            return;
                        }
                        else{
                            $("#deliver_id_code").val(data.mem_code);
                            $("#deliver_id").val(data.mem_id);
                            $("#deliver_name").val(data.mem_name);
                            $("#deliver_phone").val(data.mem_phone);
                            $("#deliver_addr").val(data.mem_add1);
                            $("#deliver_email").val(data.mem_email);
                            $("#deliver_bank").val(data.bank_name);
                            $("#deliver_owner").val(data.bank_owner);
                            $("#deliver_account").val(data.bank_account);
                            if($("#deliver_id").val() != '' && $("#deliver_name").val() != '' && $("#deliver_phone").val() != '' && $("#deliver_addr").val() != '' && $("#deliver_email").val() != '' && $("#deliver_bank").val() != '' && $("#deliver_owner").val() != '' && $("#deliver_account").val() != ''){
                                $("#check_deliver_id_state").val('Y');
                            }
                            else{
                                $("#check_deliver_id_state").val('N');
                            }
                        }
                        $("#ajax-loading").hide();
                    }
                });
            }
        }

        function goPage(pgNum) {
            location.href = '?nowPage='+pgNum+"&search_key=<?=$_GET['search_key'];?>";
        }

        function save_req_provider(){
            $("#req_provider_form").submit();
        }

        function gwc_worker(){
            if($("#gwc_worker_state_").prop('checked')){
                $("#worker_no_side").css('display', 'flex');
                $("#worker_img_side").css('display', 'flex');
                $("#save_req_provider_side").show();
                $("#gwc_worker_state").val(1);
            }
            else{
                $("#worker_no_side").css('display', 'none');
                $("#worker_img_side").css('display', 'none');
                $("#save_req_provider_side").hide();
                $("#gwc_worker_state").val(0);
            }
        }

        function fsellerexcel_submit(f)
        {
            var provider_state = '<?=$member_iam[gwc_provider_name]?>';
            if(provider_state == ''){
                alert('공급사신청을 먼저 해주세요.');
                return false;
            }

            if(!f.excelfile.value) {
                alert('파일을 업로드해주십시오.');
                return false;
            }
            
            if((!f.excelfile.value.match(/\.(xls)$/i) && !f.excelfile.value.match(/\.(xlsx)$/i)) && f.excelfile.value) {
                alert('(*.xls, *.xlsx) 파일만 등록 가능합니다.');
                return false;
            }

            if(!confirm("공급사 일괄등록을 진행하시겠습니까?"))
                return false;
            
            f.action = "ajax/reg_seller_xls.php";
            return true;
        }

        function sample_download(){
            location.href="/data/sellerexcel.xls";
        }
    </script>
</div>
<div id="ajax_div" style="display:none"></div>