<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
$sql="select * from Gn_Iam_Mall where idx='".$idx."'";
$res=mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($res);	

?>
<style>
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
	.box-body th {
		background: #ddd;
	}
	.list_table1 tbody tr th,td{
		padding:5px;
		border-bottom:1px solid lightgray;
	}
	.step_switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
	.step_switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
	.reserv_btn {
        border: 1px solid;
        padding: 5px;
        color: white;
        background-color: blue;
    }
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script>

<link rel='stylesheet' id='jquery-ui-css' href='//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css?ver=4.5.2'
	type='text/css' media='all' />
<script src="http://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.min.js"
	integrity="sha256-55Jz3pBCF8z9jBO1qQ7cIf0L+neuPTD1u7Ytzrp2dqo=" crossorigin="anonymous"></script>

<div class="wrapper">

	<!-- Top 메뉴 -->
	<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>

	<!-- Left 메뉴 -->
	<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				아이엠몰 관리
				<small>아이엠몰을 관리합니다.</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">아이엠몰 관리</li>
			</ol>
		</section>

		<form method="post" id="sub_4_form" name="sub_4_form" action="/ajax/edit_iam_mall.php" enctype="multipart/form-data">
			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">아이엠몰 상세정보</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<input type="hidden" name="edit_mall" value="edit_mall" />
							<input type="hidden" name="idx" value="<?php echo $idx;?>" />
							<div>
								<div class="p1">
									<table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<th class="w200">아아디</th>
											<td><input type="text" name="m_id" id="m_id" value="<?=$row['mem_id']?>"></td>
										</tr>
										<tr>
											<th class="w200">상품제목</th>
											<td><input type="text" name="title" id="title" value="<?=$row['title']?>"></td>
										</tr>
										<tr>
											<th class="w200">상품부제목</th>
											<td><input type="text" name="sub_title" id="sub_title" value="<?=$row[sub_title]?>"></td>
										</tr>
										<tr>
											<th class="w200">이미지</th>
											<td><input type="text" name="img" id="img" value="<?=$row['img']?>"><?if($row['img'] != ""){?><br><img class="zoom" src="<?=$row['img']?>" style="width:200px;"><?}?></td>
										</tr>
										<tr>
											<th class="w200">상세설명</th>
											<td><textarea name="description" id="description" style="width: 300px;height: 100px;" value="<?=$row[description]?>"><?=$row[description]?></textarea></td>
										</tr>
										<tr>
											<th class="w200">검색키워드</th>
											<td><input type="text" name="keyword" id="keyword" value="<?=$row[keyword]?>"></td>
										</tr>
										<tr>
											<th class="w200">상품정가</th>
											<td><input type="text" name="price" id="price" value="<?=$row[price]?>"></td>
										</tr>
										<tr>
											<th class="w200">판매가격</th>
											<td><input type="text" name="sell_price" id="sell_price" value="<?=$row[sell_price]?>"></td>
										</tr>
									</table>
								</div>
								<input type="hidden" name="send_date" id="send_date" value="" />
								<div class="p1" style="text-align:center;margin-top:20px;">
									<button class="btn btn-primary" style="margin-right: 5px;" onclick="save_form();return false;"><i class="fa fa-save"></i> 저장</button>
									<button class="btn btn-primary" style="margin-right: 5px;" onclick="location='iam_mall_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
								</div>
							</div>
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div><!-- /.col -->
			</div><!-- /.row -->
		</section><!-- /.content -->
	</form>
</div><!-- /.content-wrapper -->
<!-- Footer -->
<script language="javascript">
	function form_save() {
		if ($('#title').val() == "") {
			alert('메시지 타이틀을 입력해주세요.');
			return;
		}
		if ($('#content').val() == "") {
			alert('메시지 콘텐츠를 입력해주세요.');
			return;
		}
		$('#dForm').submit();
	}

	$('.step_switch').on("change", function() {
		var id = $(this).find("input[type=checkbox]").val();
		var status = $(this).find("input[type=checkbox]").is(":checked")==true?"1":"0";
		$.ajax({
			type:"POST",
			url:"/ajax/edit_event.php",
			data:{
				update_state:true,
				id:id,
				status:status
			},
			success:function(data){
				alert('신청되었습니다.');
			}
		})
	});

	$(".date").datepicker({
		dateFormat: "yy-mm-dd",
		defaultDate: "+1w"
	});

	//시작일. 끝일보다는 적어야 되게끔
	$("#send_start_date").datepicker({
		dateFormat: "yy-mm-dd",
		defaultDate: "+1w",
		onClose: function (selectedDate) {
			$("#send_start_date").datepicker("option", "minDate", selectedDate);
		}
	});

	//끝일. 시작일보다는 길어야 되게끔
	$("#send_end_date").datepicker({
		dateFormat: "yy-mm-dd",
		defaultDate: "+1w",
		onClose: function (selectedDate) {
			$("#send_end_date").datepicker("option", "maxDate", selectedDate);
		}
	});

	function save_form() {
		$('#sub_4_form').submit();
	}

	function limit_selcard1(){
        var sel_card1 = new Array();
        var cnt1;
        $('input[class=we_story_radio1]:checked').each(function() {
            var idVal1 = $(this).attr("value");
            // console.log(idVal);
            cnt1 = sel_card1.push(idVal1);
            if(cnt1 > 4){
                alert('최대 4개까지 선택할수 있습니다.');
                $('input[id=multi_westory_card_url1_'+idVal1+']').prop("checked", false);
                return;
            }
            $("#card_short_url").val(sel_card1.join(","));
        });
    }
</script>
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>