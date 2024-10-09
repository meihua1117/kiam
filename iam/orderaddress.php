<?php
include_once "../lib/rlatjd_fun.php";
echo '<meta name="viewport" content="width=device-width,initial-scale=1">';
echo '<script type="text/JavaScript" src="/iam/js/jquery-3.1.1.min.js"></script>'.PHP_EOL;

$sql = " select * from Gn_Order_Address where mem_id='{$_SESSION[iam_member_id]}' order by reg_date desc";
$result = mysql_query($sql);
$total_count = mysql_num_rows($res);
?>
<link rel="stylesheet" href="/iam/css/style_gwc.css">
<style>
	.new_win #win_title {
		margin: 0 0 20px;
		padding: 20px;
		border-top: 3px solid #4e5d60;
		border-bottom: 1px solid #e9e9e9;
		background: #fff;
		font-size: 1.2em;
	}

	.tbl_head02 {
		border-top: 1px solid #666;
		border-bottom: 1px solid #e4e5e7;
	}

	.tbl_head02 {
		border-top: 1px solid #666;
		border-bottom: 1px solid #e4e5e7;
	}
	.new_win .win_btn {
		clear: both;
		padding: 20px;
		text-align: center;
	}
	thead, tfoot {
		font-size: 12px;
		line-height: 1em;
		vertical-align: baseline;
	}
	tbody {
		display: table-row-group;
		vertical-align: middle;
		border-color: inherit;
	}
	.tbl_head02 td {
		padding: 9px;
		text-align: left;
		line-height: 1.4;
		border-top: 1px solid #e4e5e7;
		word-break: break-all;
	}
	.empty_list {
		padding: 30px 0 !important;
		color: #999;
		text-align: center !important;
		border-left: 0 !important;
	}
	.tbl_head02 th, .tbl_head02 td {
		font-size: 12px;
		border-left: 1px solid #e4e5e7;
		vertical-align: middle;
	}
	.tbl_head02 tr:not(.rows) th:first-child, .tbl_head02 tr:not(.rows) td:first-child {
		border-left: 0 !important;
	}
	.tbl_head02 th {
		padding: 9px 0;
		line-height: 1em;
		font-weight: 600;
	}
	.tbl_wrap table {
		width: 100%;
	}
	.new_win .win_btn {
		clear: both;
		padding: 20px;
		text-align: center;
	}
	.bx-white {
		background: #fff;
		border: 1px solid #ccc;
		color: #222 !important;
	}
	.new_win .win_btn a, .new_win .win_btn input, .new_win .win_btn button {
		margin: 0 1.5px;
	}
	.btn_lsmall {
		padding: 3px 10px;
		font-size: 12px;
		line-height: 1.666;
		font-weight: normal !important;
	}
	.btn_top1{
		font-size: 14px;
		padding: 5px;
	}
	.sel_main_address{
		color: white;
		background-color: #82c836;
		text-decoration: none;
	}
	.del_address{
		text-decoration: none;
		color: #857d7d;
		border: 1px	solid #82c836;
	}
</style>
<form name="forderaddress" id="forderaddress" method="post" class="new_win">
<h1 id="win_title">배송지 목록</h1>
<div style="text-align: right;">
	<a href="javascript:void(0);" class="sel_main_address btn_top1">기본배송지로 선택</a>
	<a href="javascript:deleteMultiRow();" class="del_address btn_top1">선택삭제</a>
</div>
<div class="tbl_head02 tbl_wrap" style="margin-top:10px;">
	<table id="example1">
	<colgroup>
		<col class="w50">
		<col>
		<col class="w50">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">주소</th>
		<th scope="col">선택</th>
	</tr>
	</thead>
	<tbody>
	<?php
		$sep = chr(30);
		$k = 0; $ar_mk = array();
		for($i=0; $row=sql_fetch_array($result); $i++)
		{
			$info = array();
			$info[] = $row['name'];
			$info[] = $row['phone_num'];
			$info[] = $row['phone_num1'];
			$info[] = $row['zip'];
			$info[] = $row['address1'];
			$info[] = $row['address2'];

			$addr = implode($sep, $info);
			$addr = get_text($addr);

			if(!in_array($addr, $ar_mk)) {
				$k++;
				$ar_mk[$i] = $addr;

				$bg = 'list'.($k%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="tac"><input type="checkbox" class="check" id="check_one_add" name="" value="<?=$row['id']?>"><?php echo $k; ?></td>
		<td><?php echo print_address($row['address1'], $row['address2']); ?></td>
		<td class="tac">
			<input type="hidden" id="address<?=$k?>" value="<?php echo $addr; ?>">	
			<a href="javascript:void(0);" class="sel_address btn_small">선택</a>
		</td>
	</tr>
	<?php
		}
	}

	if($k==0)
		echo '<tr><td colspan="3" class="empty_list">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<div class="win_btn">
	<a href="javascript:close_frame()" class="btn_lsmall bx-white">창닫기</a>
</div>	
</form>

<script>
$(function() {
    $(".sel_address").on("click", function() {
        var addr = $(this).siblings("input").val().split(String.fromCharCode(30));

        var f = window.parent.document.pay_form;
		f.b_name.value			= addr[0];
		f.b_cellphone.value		= addr[1];
		f.b_telephone.value		= addr[2];
		f.b_zip.value			= addr[3];
		f.b_addr1.value			= addr[4];
		f.b_addr2.value			= addr[5];

        var zip = addr[3].replace(/[^0-9]/g, "");
        if(zip != "") {
            var code = String(zip);
			// window.opener.calculate_sendcost(code);
        }

        // window.close();
		window.parent.document.getElementById('order_address').style.display = "none";
		// window.document.getElementById("order_address").style.display = "none";
    });

	$(".sel_main_address").on("click", function() {
		var no_array = [];
		var index = 0;
		var index1 = 0;
		var check_array = $("#example1").children().find(".check");
		check_array.each(function(){
			if($(this).prop("checked") && $(this).val() > 0)
				no_array[index++] = $(this).val();
		});
		if(no_array.length == 0){
			alert("주소를 선택하세요.");
			return;
		}
		check_array.each(function(){
			index1++;
			if($(this).prop("checked")){
				$.ajax({
					type:"POST",
					url:"ajax/manage_request_list.php",
					dataType:"json",
					data:{mode:"set_main_address", id:$(this).val()},
					success: function(data){
						
					}
				})

				var addr = $("input[id=address"+index1+"]").val().split(String.fromCharCode(30));

				var f = window.parent.document.pay_form;
				f.b_name.value			= addr[0];
				f.b_cellphone.value		= addr[1];
				f.b_telephone.value		= addr[2];
				f.b_zip.value			= addr[3];
				f.allat_recp_addr.value			= addr[4];
				f.b_addr2.value			= addr[5];

				var zip = addr[3].replace(/[^0-9]/g, "");
				if(zip != "") {
					var code = String(zip);
					// window.opener.calculate_sendcost(code);
				}
				// window.close();
				window.parent.document.getElementById('order_address').style.display = "none";
			}
		});
    });
});
function close_frame(){
	window.parent.document.getElementById('order_address').style.display = "none";
}
function deleteMultiRow() {
	var check_array = $("#example1").children().find(".check");
	var no_array = [];
	var index = 0;
	check_array.each(function(){
		if($(this).prop("checked") && $(this).val() > 0)
			no_array[index++] = $(this).val();
	});

	if(no_array.length == 0){
		alert("삭제할 주소를 선택하세요.");
		return;
	}
	if(confirm('삭제하시겠습니까?')) {
		$.ajax({
			type:"POST",
			url:"ajax/manage_request_list.php",
			dataType:"json",
			data:{mode:"delete_address", id:no_array.toString()},
			success: function(data){
				alert('삭제 되었습니다.');
				window.location.reload();
			}
		})
	}
}
</script>