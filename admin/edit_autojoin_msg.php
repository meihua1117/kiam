<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
$sql="select * from Gn_event  where event_idx='".$event_idx."'";
$res=mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($res);	
if($row[0]) {
    $sql="select * from Gn_Member  where mem_id='".$row['m_id']."'";
    $sresul_num=mysqli_query($self_con,$sql);
    $data=mysqli_fetch_array($sresul_num);
}

?>
<style>
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
	thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
	.wrapper{height:100%;overflow:auto !important;}
	.content-wrapper{min-height : 80% !important;}
	.box-body{overflow:auto;padding:0px !important;}
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
				오토회원 메시지 관리
				<small>분양사 오토회원 메시지를 관리합니다.</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">오토회원 메시지관리</li>
			</ol>
		</section>

		<form method="post" id="sub_4_form" name="sub_4_form" action="/ajax/edit_event.php"
			enctype="multipart/form-data">
			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">오토회원 메시지 상세정보</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<input type="hidden" name="save" value="save" />
							<input type="hidden" name="event_idx" value="<?php echo $event_idx;?>" />
							<div>
								<div class="p1">
									<table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<th class="w200">아아디</th>
											<td><input type="text" name="m_id" id="m_id" value="<?=$row['m_id']?>"></td>
										</tr>
										<tr>
											<th class="w200">이벤트타이틀</th>
											<td><input type="text" name="event_title" id="event_title" value="<?=$row['event_title']?>"></td>
										</tr>
										<tr>
											<th class="w200">이벤트메시지</th>
											<td><textarea name="event_desc" id="event_desc" style="width: 300px;height: 100px;" value="<?=$row['event_desc']?>"><?=$row['event_desc']?></textarea></td>
										</tr>
										<tr>
											<th class="w200">카드링크</th>
											<td>
												<input type="text" id="card_short_url" name="card_short_url" value="<?=$row['event_info']?>" hidden>
												<div id="cardsel1" onclick="limit_selcard1()" style="margin-top:15px;">
													<?
													$sql5="select card_short_url,phone_display, card_title from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$row['m_id']}' order by req_data asc";
													$result5=mysqli_query($self_con,$sql5);
													$i = 0;
													while($row5=mysqli_fetch_array($result5)) {
														if($i == 0){
															$hidden = "hidden";
														}
														else{
															$hidden = "";
														}

														$checked = "";
														if($row['event_info'] != ""){
															$card_idx_arr = array();
															if(strpos($row['event_info'], ",") !== false){
																$card_idx_arr = explode(",", $row['event_info']);
															}
															else{
																$card_idx_arr[0] = $row['event_info'];
															}
															$num = $i+1;
															for($c = 0; $c < count($card_idx_arr); $c++){
																if($card_idx_arr[$c] == $num){
																	$checked = "checked";
																}
															}
														}
														?>
														<input type="checkbox" id="multi_westory_card_url1_<?= $i+1 ?>" name="multi_westory_card_url1"
															class="we_story_radio1"
															value="<?= $i+1 ?>" <? if($row5['phone_display']=="N"
														){echo "onclick='locked_card_click();'" ;} ?> <?=$hidden?> <?=$checked?>
															>
														<span <? if($row5['phone_display']=="N" ){echo "class='locked' title='비공개카드'" ;} ?> <?=$hidden?>>
															<?=$i+1?>번(<?=$row5['card_title']?>)
														</span>
														<?
														$i++;
													}
													?>
												</div>
											</td>
										</tr>
										<tr>
											<th class="w200">버튼타이틀</th>
											<td><input type="text" name="btn_title" id="btn_title" value="<?=$row[event_type]?>"></td>
										</tr>
										<tr>
											<th class="w200">버튼링크</th>
											<td><input type="text" name="btn_link" id="btn_link" value="<?=$row[event_sms_desc]?>"></td>
										</tr>
										<tr>
											<th class="w200">단축주소</th>
											<td><input type="text" name="short_url" id="short_url" value="<?=$row[short_url]?>"></td>
										</tr>
										<tr>
											<th class="w200">이벤트신청주소</th>
											<td><input type="text" name="event_req_link" id="event_req_link" value="<?=$row['event_req_link']?>"></td>
										</tr>
										<tr>
											<th class="w200">조회수</th>
											<td><input type="text" name="read_cnt" id="read_cnt" value="<?=$row['read_cnt']?>"></td>
										</tr>
										<tr>
											<th class="w200">이미지</th>
											<td><input type="file" name="autojoin_img" style="width:93px;"><?if($row['object'] != ""){?><img class="zoom" src="<?=$row['object']?>" style="width:200px;"><?}?></td>
										</tr>
										<?
										$sql_step = "select a.idx, a.send_num, a.allow_state, a.reserv_sms_id, b.step, b.title, b.content from gn_automem_sms_reserv a inner join Gn_event_sms_step_info b on a.reserv_sms_id=b.sms_idx where a.auto_event_id={$event_idx}";
										// echo $sql_step;
										$res_step = mysqli_query($self_con,$sql_step);
										$row_step = mysqli_fetch_array($res_step);
										if($row_step['idx']){
											if($row_step[allow_state]){
												$checked = "checked";
											}
											else{
												$checked = "";
											}
										?>
										<tr>
											<th class="w200">스텝문자정보</th>
											<td>
												<input type="text" style="width:45%;" name="step_title" id="step_title" value="<?=$row_step['title']?>" disabled>
												<input type="text" style="width:100px;" name="step_phone" id="step_phone" value="<?=$row_step['send_num']?>" disabled>
												<input type="text" style="width:50px;" name="step_cnt" id="step_cnt" value="<?=$row_step['step']?>" disabled><br><br>
												적용상황
												<label class="step_switch">
													<input type="checkbox" name="step_allow_state" id="step_allow_state" value="<?=$row_step['idx']?>" <?=$checked?>>
													<span class="slider round" name="step_status_round" id="step_status_round"></span>
												</label>
												수정하기
												<p id="step_info" style="display:inline-block;">
													<a class="reserv_btn" href="/mypage_reservation_create.php?sms_idx=<?=$row_step[reserv_sms_id]?>" target="_blank">GO</a>
												</p>
											</td>
										</tr>
										<?}?>
										<tr>
											<th class="w200">등록일시</th>
											<td><input type="text" name="regdate" id="regdate" value="<?=$row['regdate']?>"></td>
										</tr>
									</table>
								</div>
								<input type="hidden" name="send_date" id="send_date" value="" />
								<div class="p1" style="text-align:center;margin-top:20px;">
									<button class="btn btn-primary" style="margin-right: 5px;" onclick="save_form();return false;"><i class="fa fa-save"></i> 저장</button>
									<button class="btn btn-primary" style="margin-right: 5px;" onclick="location='autojoin_msg_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
								</div>
							</div>
						</div><!-- /.box-body -->
					</div><!-- /.box -->
				</div><!-- /.col -->
			</div><!-- /.row -->
		</section><!-- /.content -->
	</form>
	<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
</div><!-- /content-wrapper -->
<!-- Footer -->
<script language="javascript">
	$(function() {
		var contHeaderH = $(".main-header").height();
		var navH = $(".navbar").height();
		if(navH != contHeaderH)
			contHeaderH += navH - 50;
		$(".content-wrapper").css("margin-top",contHeaderH);
		var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 235;
		if(height < 375)
			height = 375;
		$(".box-body").css("height",height);
	});
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