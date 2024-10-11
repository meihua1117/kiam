<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

$sql="select * from Gn_event  where event_idx='".$event_idx."'";
$res=mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($res);	
if($row[0]) {
    $sql="select * from Gn_Member  where mem_id='".$row[m_id]."'";
    $sresul_num=mysqli_query($self_con,$sql);
    $data=mysqli_fetch_array($sresul_num);
}

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
<script>
	$(document).ready(function(){
		$("select[name=htime]").val(<?=$row[event_sms_desc]?>);
		$("select[name=mtime]").val(<?=$row[event_type]?>);
	});
</script>
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

		<form method="post" id="sub_4_form" name="sub_4_form" action="/ajax/edit_event_daily.php"
			enctype="multipart/form-data">
			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="box">
						<div class="box-header">
							<h3 class="box-title">분양사 데일리 메시지 상세정보</h3>
						</div><!-- /.box-header -->
						<div class="box-body">
							<input type="hidden" name="save" value="save" />
							<input type="hidden" name="admin" value="admin" />
							<input type="hidden" name="daily_event_idx" value="<?php echo $event_idx;?>" />
							<div>
								<div class="p1">
									<table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<th class="w200">아아디</th>
											<td><input type="text" name="m_id" id="m_id" value="<?=$row[m_id]?>" disabled></td>
										</tr>
										<tr>
											<th class="w200">이벤트타이틀</th>
											<td><input type="text" name="daily_event_title_intro" id="daily_event_title_intro" value="<?=$row[event_title]?>"></td>
										</tr>
										<tr>
											<th class="w200">이벤트메시지</th>
											<td><textarea name="daily_event_desc_intro" id="daily_event_desc_intro" style="width: 300px;height: 100px;" value="<?=$row[event_desc]?>"><?=$row[event_desc]?></textarea></td>
										</tr>
										<tr>
											<th class="w200">제목</th>
											<td><input type="text" name="daily_event_title" id="daily_event_title" value="<?=$row[event_info]?>"></td>
										</tr>
										<tr>
											<th class="w200">내용</th>
											<td><textarea name="daily_event_desc" id="daily_event_desc" style="width: 300px;height: 100px;" value="<?=$row[event_req_link]?>"><?=$row[event_req_link]?></textarea></td>
										</tr>
										<tr>
											<th class="w200">단축주소</th>
											<td><input type="text" name="daily_short_url" id="daily_short_url" value="<?=$row[short_url]?>"></td>
										</tr>
										<tr>
											<th class="w200">링크주소</th>
											<td><input type="text" name="daily_req_link" id="daily_req_link" value="<?=$row[daily_req_link]?>"></td>
										</tr>
										<tr>
											<th class="w200">조회수</th>
											<td><input type="text" name="daily_read_cnt" id="daily_read_cnt" value="<?=$row[read_cnt]?>"></td>
										</tr>
										<tr>
											<th class="w200">이미지</th>
											<td><input type="file" name="daily_img" style="width:93px;"><?if($row['object'] != ""){?><img class="zoom" src="http://www.kiam.kr/<?=$row['object']?>" style="width:200px;"><?}?></td>
										</tr>
										<tr>
											<th class="w200">일발송량</th>
											<td><input type="text" name="daily_send_cnt" id="daily_send_cnt" value="<?=$row[callback_no]?>"></td>
										</tr>
										<tr>
											<th class="w200">발송시간</th>
											<td>
												<select name="htime" style="width:50px;">
													<?
														for($i=9; $i<22; $i++)
														{
															$iv=$i<10?"0".$i:$i;
															?>
													<option value="<?=$iv?>"><?=$iv?></option>
													<?
														}
														?>
												</select>
												<select name="mtime" style="width:50px;">
													<?
														for($i=0; $i<31; $i+=30)
														{
															$iv=$i==0?"00":$i;
															?>
													<option value="<?=$iv?>"><?=$iv?></option>
													<?
														}
														?>
												</select>
											</td>
										</tr>
										<tr>
											<th class="w200">등록일시</th>
											<td><input type="text" name="daily_regdate1" id="daily_regdate1" value="<?=$row['regdate']?>"></td>
										</tr>
									</table>
								</div>
								<input type="hidden" name="send_date" id="send_date" value="" />
								<div class="p1" style="text-align:center;margin-top:20px;">
									<button class="btn btn-primary" style="margin-right: 5px;" onclick="save_form();return false;"><i class="fa fa-save"></i> 저장</button>
									<button class="btn btn-primary" style="margin-right: 5px;" onclick="location='daily_msg_list_service.php';return false;"><i class="fa fa-list"></i> 목록</button>
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
	function save_form() {
		$('#sub_4_form').submit();
	}
</script>
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>