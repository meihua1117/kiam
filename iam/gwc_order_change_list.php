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
</style>
<div id="container" class="sub_wrap" style="margin-top: 165px;">
    <div id="content_title" style="padding-bottom:20px;">
        <span>취소/반품/교환내역</span>
    </div>

    <div style="padding:10px;">
        <!-- <form method="get" name="search_form" id="search_form " class="form-inline" style="display:flex;">
            <select name="search_title" class="form-control input-sm " style="margin-right:5px;width: 80px;">
                <option value="">전체</option>
                <option value="1" <?if($_REQUEST['search_title'] == "1") echo "selected"?>>결제일</option>
                <option value="2" <?if($_REQUEST['search_title'] == "2") echo "selected"?>>상품명</option>
            </select>
            <input type="date" name="search_start_date" style="border: 2px solid lightgrey;width: 115px;">~<input type="date" name="search_end_date" style="border: 2px solid lightgrey;width: 115px;">
            <input type="text" name="search_key" id="search_key"   style="width:100px" class="form-control input-sm pull-right" placeholder="" value="<?=$_REQUEST['search_title'] == "2"?$search_key:''?>">
            <button style="margin-left:5px" class="btn btn-sm btn-default pull-right"><i class="fa fa-search"></i></button>
        </form> -->
        <li style="color:grey;">취소/반품/교환 신청한 내역을 확인할수 있습니다.</li>
        <li style="color:grey;">하단 상품목록에 없는 상품은 1:1문의 또는 고객센터()로 문의주세요.</li>
    </div>
    <div class="tbl_head02 tbl_wrap">
		<table>
		<tbody>
		<?php
        $searchStr = '';
        if($search_title == 1){
            $join_str = "";
            if($search_start_date && $search_end_date)
                $searchStr .= " AND a.reg_date >= '$search_start_date' and a.reg_date <= '$search_end_date'";
        }
        else if($search_title == 2){
            if($search_key){
                $join_str = "INNER JOIN Gn_Iam_Contents_Gwc c on a.contents_idx = c.idx ";
                $searchStr .= " AND c.contents_title like '%$search_key%'";
            }
        }
        else{
            $join_str = "";
            $searchStr = '';
        }

        $sql = "select a.* from Gn_Gwc_Order a ".$join_str." where a.mem_id='{$_SESSION['iam_member_id']}' and prod_state!=0 ".$searchStr." order by a.reg_date desc";
        $result = mysqli_query($self_con,$sql);
        $cart_count = mysqli_num_rows($result);
		for($i=0; $row=mysqli_fetch_array($result); $i++) {
			$sql = " select * from Gn_Iam_Contents_Gwc where idx = '$row[contents_idx]' ";
			$res = mysqli_query($self_con,$sql);
			$row_con = mysqli_fetch_array($res);

            if(strpos($row_con[contents_img], ",") !== false){
                $img_arr = explode(",", $row_con[contents_img]);
                $img = $img_arr[0];
            }
            else{
                $img = $row_con[contents_img];
            }

            $sql_delivery = "select * from delivery_list where id='{$row[delivery]}'";
            $res_delivery = mysqli_query($self_con,$sql_delivery);
            $row_delivery = mysqli_fetch_array($res_delivery);

            $show_link = "http://kiam.kr/iam/gwc_order_pay.php?contents_idx=".$row['contents_idx']."&contents_cnt=".$row['contents_cnt']."&contents_price=".$row['contents_price']."&contents_salary=".$row['salary_price']."&seller_id=".$row['seller_id']."&order_option=".$row['order_option']."&admin=M&order_id=".$row['id']."&mem_id=".$row['mem_id']."&use_point_val=".$row['use_point']."&pay_method=".$row['payMethod'];

            if($row[prod_state] == "1"){
                $state = "취소";
            }
            if($row[prod_state] == "2"){
                $state = "반품";
            }
            if($row[prod_state] == "3"){
                $state = "교환";
            }

            if($row[change_prod_req_date]){
                $process = $state."완료";
            }
            else{
                $process = "";
            }

            if($row[prod_req_state] == "1"){
                $req_state = "취소완료";
            } elseif ($row[prod_req_state] == "2"){
                $req_state = "카드사환불예정";
            } elseif($row[prod_req_state] == "3"){
                $req_state = "교환예정";
            } elseif($row[prod_req_state] == "4"){
                $req_state = "교환완료";
            } elseif($row[prod_req_state] == "5"){
                $req_state = "환불완료";
            } else {
                $req_state = "없음";
            }
		?>
		<tr class="rows">
			<td class="tac" style="border-right: 1px solid #e4e5e7;padding: 0px;">
                <div style="background-color: #efefef;padding: 7px;text-align: left;">
                    <span><?=$state?>접수일 : <?=substr($row[prod_req_date],0,10)?>&nbsp;&nbsp;&nbsp;&nbsp;|</span>
                    <span style="margin-left:10px;">주문일 : <?=substr($row[reg_date],0,10)?>&nbsp;&nbsp;&nbsp;&nbsp;|</span>
                    <span style="margin-left:10px;">주문번호 : <?=$row[pay_order_no]?></span>
                </div>
                <div class="ini_wrap" style="padding: 7px;">
					<table class="wfull">
                        <tr>
                            <td class="vat tal" style="padding: 10px;width:45%;border-right: 1px solid #d5d5d5 !important;">
                                <?=$row_con[contents_title]?>
                            </td>
                            <td class="vat tal" style="padding: 10px;width:15%;border-right: 1px solid #d5d5d5 !important;">
                                <?php echo $row['contents_cnt']; ?>개
                                <p class="padt3 fc_999"><?php echo number_format($row['salary_price'] * 1 + $row['contents_price'] * 1); ?>원</p>
                            </td>
                            <td class="vat tal" style="padding: 10px;width:15%;border-right: 1px solid #d5d5d5 !important;">
                                <?=$process?>
                                <p class="padt3 fc_999"><?php echo $req_state ?></p>
                            </td>
                            <td class="vat tal" style="padding: 10px;width:10%;">
                                <button onclick="show_detail('<?=$row['id']?>')" style="background-color: white;color: #86ca3d;border: 1px solid #86ca3d;border-radius: 5px;">상세정보</button>
                            </td>
                        </tr>
					</table>
				</div>
			</td>
		</tr>
		<?php 
		}
		if($i==0)
			echo '<tr><td colspan="4" class="empty_list">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
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

        function show_detail(id){
            location.href="gwc_order_change_detail.php?id="+id;
        }
    </script>
</div>
<div id="ajax_div" style="display:none"></div>