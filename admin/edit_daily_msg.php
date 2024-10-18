<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

$sql="select * from Gn_daily  where gd_id='".$gd_id."'";
$sresul_num=mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($sresul_num);	
if($row[0]) {
    $sql="select * from Gn_MMS_Group where idx='$row[group_idx]'";
    $sresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));					  	 
    $krow = mysqli_fetch_array($sresult);   
    
    $sql="select * from Gn_Member  where mem_id='".$row['mem_id']."'";
    $sresul_num=mysqli_query($self_con,$sql);
    $data=mysqli_fetch_array($sresul_num);
}
if(!$_REQUEST['daily_cnt']){
    $daily_cnt = 50;
}
else{
    $daily_cnt = $_REQUEST['daily_cnt'];
}
if($type == "service") $link = "daily_msg_list_service.php";
else $link = "daily_msg_list_mem.php";
?>
<style>
	.box-body th {
		background: #ddd;
	}
	.list_table1 tbody tr th,td{
		padding:5px;
		border-bottom:1px solid lightgray;
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
				분양사 데일리 메시지 관리
				<small>분양사 데일리 메시지를 관리합니다.</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">분양사 데일리 메시지관리</li>
			</ol>
		</section>

		<form method="post" id="sub_4_form" name="sub_4_form" action="/admin/ajax/daily_update.php"
			enctype="multipart/form-data">
			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">데일리 메시지 상세정보</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<input type="hidden" name="mode" value="daily_update" />
							<input type="hidden" name="gd_id" value="<?php echo $gd_id;?>" />
							<input type="hidden" name="total_count" id="total_count" value="<?php echo $_GET[address_cnt]?$_GET[address_cnt]:$row['total_count'];?>" />
							<div>
								<div class="p1">
									<table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<th class="w200">[발송폰선택]</th>
											<td>
												<select name="send_num">
													<option value="<?=str_replace("-", "", $row['send_num'])?>">
														<?php echo str_replace("-","",$row['send_num']);?></option>
													<?php
													$query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' order by sort_no asc, user_cnt desc , idx desc";
													$resul=mysqli_query($self_con,$query);
													while($korow=mysqli_fetch_array($resul)) {
													?>
													<option value="<?=str_replace("-","",$korow['sendnum'])?>"
														<?php echo $row['send_num']==str_replace("-","",$korow['sendnum'])?"selected":""?>>
														<?php echo str_replace("-","",$korow['sendnum']);?></option>
													<?php }?>
												</select>
											</td>
										</tr>
										<tr>
											<th class="w200">[주소록선택]</th>
											<td>
												<input type="hidden" name="group_idx" placeholder="" id="address_idx" value="<?php echo $_GET[address_idx]?$_GET[address_idx]:$row[group_idx];?>" readonly style="width:100px" />
												<input type="text" name="address_name" placeholder="" id="address_name" value="<?php echo $_GET[address_name]?$_GET[address_name]:$krow['grp']?>" readonly style="width:100px" />
												<input type="button" value="주소록 조회" class="button " id="searchBtn">[선택건수]<span id="address_cnt"><?php echo $_GET[address_cnt]?$_GET[address_cnt]:$row['total_count'];?></span>
											</td>
										</tr>
										<tr>
											<th class="w200">[일발송량]</th>
											<td><input type="text" name="daily_cnt" id="daily_cnt" style="width:60px;" value="<?=$daily_cnt?>" onkeyup="if(this.value >100)this.value=50;" />
												<a href="javascript:apply();" style="text-decoration-line: blink;background-color: #5f72e6;padding: 3px;color: white;border-radius: 5px;">적용</a>
												데일리 발송은 고객의 폰에서 일발송량 100건 이하로 설정하는 걸 권장합니다.
											</td>
										</tr>
										<tr>
											<th class="w200">[메시지제목]</th>
											<td><input type="text" name="title" itemname='제목' required placeholder="제목을 입력하세요" style="width:100%;" value="<?=$row['title']?>" /></td>
										</tr>
										<tr>
											<th class="w200">[메시지내용]</th>
											<td>
												<textarea style="width:200px; height:200px;" id="txt" name="txt" itemname='내용' id='txt' required placeholder="보내고 싶은 메시지를 입력하세요" onkeydown="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);" onkeyup="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();" onfocus="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();"><?=$row['content']?></textarea>
											</td>
										</tr>
										<tr>
											<th class="w200">[수신거부]</th>
											<td><input type="checkbox" id="send_deny_msg" name="send_deny_msg" onclick="deny_msg_click(this,0)" style="float:left;">
												<div class="deny_msg_span" style="float:left;color:#F00;">OFF</div>
											</td>
										</tr>
										<tr>
											<th class="w200">[이미지1]</th>
											<td>
												<input type="file" name="upimage" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=&target=upimage_str';sub_4_form.target='excel_iframe';sub_4_form.submit();" />
												<?php if($row['jpg'] != "") {?>
												<img src="<?php echo $row['jpg']?"http://www.kiam.kr".$row['jpg']:"";?>" style="width:200px">
												<?php }?>
											</td>
										</tr>
										<tr>
											<th class="w200">[이미지2]</th>
											<td>
												<input type="file" name="upimage1" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=1&target=upimage_str1';sub_4_form.target='excel_iframe';sub_4_form.submit();" />
												<?php if($row['jpg1'] != "") {?>
												<img src="<?php echo $row['jpg1']?"http://www.kiam.kr".$row['jpg1']:"";?>" style="width:200px">
												<?php }?>
											</td>
										</tr>
										<tr>
											<th class="w200">[이미지3]</th>
											<td>
												<input type="file" name="upimage2" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=2&target=upimage_str2';sub_4_form.target='excel_iframe';sub_4_form.submit();" />
												</div>
												<?php if($row['jpg2'] != "") {?>
												<img src="<?php echo $row['jpg2']?"http://www.kiam.kr".$row['jpg2']:"";?>" style="width:200px">
												<?php }?>
											</td>
										</tr>
										<tr>
										<th class="w200">[발송시간선택]</th>
										<td>
											<select name="htime" style="width:50px;">
												<?
												for($i=9; $i<20; $i++)
												{
													$iv=$i<10?"0".$i:$i;
													$selected=$row['htime']==$iv?"selected":"";
													?>
												<option value="<?=$iv?>" <?=$selected?>><?=$iv?></option>
												<?
												}
												?>
											</select>
											<select name="mtime" style="width:50px;">
												<?
												for($i=0; $i<31; $i+=30)
												{
													$iv=$i==0?"00":$i;
													$selected=$row['mtime']==$iv?"selected":"";
													?>
															<option value="<?=$iv?>" <?=$selected?>><?=$iv?></option>
															<?
												}
												?>
											</select>
										</td>
									</tr>
									<tr>
										<th class="w200">[발송일자선택]<br><br>
											<!--※발송일자는 폰주소록건수/100건으로 자동계산하여 발송일자가 세팅되며 삭제기능으로 발송일자를 개별적으로 제외시킬 수 있습니다. -->
										</th>
										<td>
											<!-- <input type="date" style="width:200px;" name="search_email_date" id="search_email_date" value="<?=$data['search_email_date']?>" > -->
											<div style="width:30%;text-align:left;margin:0px;">
												<ul id="date_list">
													<?php
														if(isset($_GET[address_cnt])){
															$day = ceil($_GET[address_cnt] / $_REQUEST['daily_cnt']);
														}
														else{
															$day = ceil($row['total_count'] / $_REQUEST['daily_cnt']);
														}
														for($i = 1; $i <= $day;$i++) {
															$today = date("Y-m-d", strtotime("+$i day"));
													?>
													<li id="<?php echo $today;?>"><?php echo $today;?><a href="javascript:removeDate('<?php echo $today;?>')">[삭제]</a>
													</li>
													<?php }?>
												</ul>
											</div>
										</td>
									</tr>
									</table>
								</div>
								<div class="a2" style="display:none">
									<div class="b2" style="float:left">이미지 미리보기</div>
									<div style="float:right">
										<button id="show1" onclick="showImage('');return false;">1</button>
										<button id="show2" onclick="showImage('1');return false;">2</button>
										<button id="show3" onclick="showImage('2');return false;">3</button>
									</div>
									<div id="preview_wrapper" class="img_view" style="display:inline-block;width:100%;">
										<div id="preview_fake"
											style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);">
											<img id="preview" onload="onPreviewLoad(this)" />
										</div>
									</div>
									<img id="preview_size_fake"
										style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);visibility:hidden; height:0;" />
									<div><input type="hidden" name="upimage_str" value="<?php echo $row['jpg'];?>" />
									</div>
									<div><input type="hidden" name="upimage_str1" value="<?php echo $row['jpg1'];?>" />
									</div>
									<div><input type="hidden" name="upimage_str2" value="<?php echo $row['jpg2'];?>" />
									</div>
								</div>
								<input type="hidden" name="send_date" id="send_date" value="" />
								<div class="p1" style="text-align:center;margin-top:20px;">
									<button class="btn btn-primary" style="margin-right: 5px;" onclick="send_daily();return false;"><i class="fa fa-save"></i> 저장</button>
									<button class="btn btn-primary" style="margin-right: 5px;" onclick="location='<?=$link?>';return false;"><i class="fa fa-list"></i> 목록</button>
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

	function send_daily() {
		if($('#gd_id').val() == "") {
			alert('주소록을 선택해 주세요.');
			return;
		}

		if($('#title').val() == "") {
			alert('제목을 입력해주세요.');
			return;
		}

		if($('#content').val() == "") {
			alert('내용을 입력해주세요.');
			return;
		}
		if($('#daily_cnt').val() > 100) {
			alert('일발송량의 최대수는 100입니다.');
			return;
		}
		var send_date = "";
		$('#date_list li').each(function() {
			if(send_date  == "")
				send_date = $(this).text().replace("[삭제]", "");
			else
				send_date += ","+$(this).text().replace("[삭제]", "");
		});
		$('#send_date').val(send_date);
		if($('#send_date').val() == "") {
			alert('발송일을 선택해주세요.');
			return;
		}

		$('#total_count').val($('#address_cnt').text());
		sub_4_form.action = "mypage.proc.php";
		sub_4_form.target='';
		$('#sub_4_form').submit();
	}

	function apply() {
		var daily_cnt = $("#daily_cnt").val();
		<?
		if($gd_id == ""){
		?>
		location.href = '<?=$_SERVER['PHP_SELF']?>' + '?daily_cnt=' + daily_cnt + '&address_cnt=' + $("#address_cnt").text() + '&address_name=' + $("#address_name").val() + '&address_idx=' + $("#address_idx").val();
		<?}
		else{?>
		location.href = '<?=$_SERVER['REQUEST_URI']?>' + '&daily_cnt=' + daily_cnt;
		<?}?>
	}
</script>
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>