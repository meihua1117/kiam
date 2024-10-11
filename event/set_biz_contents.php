<?
	include_once "../lib/rlatjd_fun.php";
	extract($_REQUEST);
	if($_GET['id']) $id = $_GET['id'];

	$sql_recom = "select * from get_crawler_bizinfo where id='{$id}'";
	$res = mysqli_query($self_con,$sql_recom);
	$row = mysqli_fetch_array($res);
	$info_source = $row['info_source'];
	$web_type = $row['web_type'];

	if($info_source == "국립산림과학원"){
		$info_source = "산림과학원";
	}
	$sql_reg = "select * from reg_biz_contents where info_source='{$info_source}' and info_type='{$web_type}'";
	// echo $sql_reg;
	$res_reg = mysqli_query($self_con,$sql_reg);
	$row_reg = mysqli_fetch_array($res_reg);
?>
<!DOCTYPE html>
<html lang="ko">
	<head>
	    <meta charset="UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="/css/main.css" type="text/css">
		<link rel="stylesheet" href="/admin/dist/css/AdminLTE.min.css">
		<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">

		<link rel="stylesheet" href="css/slick.css" type="text/css">
		<link rel="stylesheet" href="../css/responsive.css" type="text/css">
		<link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">

		<script src="/iam/js/jquery-3.1.1.min.js"></script>
		<script language="javascript" type="text/javascript" src="common.js"></script>
		<script language="javascript" type="text/javascript" src="jquery.rotate.js"></script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE"></script>
		<script language="javascript">
		
		</script>
		<style>
			
		</style>
	</head>
	<body class="body-event">
		<div class="content-wrapper" style="margin-left:0px;">
			<section class="content">
				<div class="row" id="register_contents" style="margin-left:7%;">
					<h2>콘텐츠수집 매칭/기준 등록</h2>
					<table id="example2" class="table table-bordered table-striped" style="width:80%;">
						<thead>
							<tr>
								<th style="width:7%;">NO</th>
								<th style="width:15%;">항목</th>
								<th style="width:75%;text-align:center;">수집키워드 및 설명</th>
							</tr>
						</thead>
						<tbody id="reg_table">
							<tr>
								<td>1</td>
								<td>정보출처</td>
								<td><input type="text" name="info_source" id="info_source" placeholder="페이지에서 출처를 입력합니다." value='<?=$info_source?>' style="width: 100%;"></td>
							</tr>
							<tr>
								<td>2</td>
								<td>정보구분</td>
								<td>
								<?php
								if($web_type == "지원사업"){
									$sel1 = "checked";
								}
								else if($web_type == "입찰공고"){
									$sel2 = "checked";
								}
								else if($web_type == "행사교육"){
									$sel3 = "checked";
								}
								else if($web_type == "기타정보"){
									$sel4 = "checked";
								}
								?>
									<input type="radio" name="work_type" id="support" style="vertical-align: top;" <?=$sel1?>>
									<label for="support" value="1" style="font-size:15px;">지원사업</label>
									<input type="radio" name="work_type" id="public" style="vertical-align: top;" <?=$sel2?>>
									<label for="public" value="2" style="font-size:15px;">입찰공고</label>
									<input type="radio" name="work_type" id="eventedu" style="vertical-align: top;" <?=$sel3?>>
									<label for="eventedu" value="3" style="font-size:15px;">행사교육</label>
									<input type="radio" name="work_type" id="other" style="vertical-align: top;" <?=$sel4?>>
									<label for="other" value="4" style="font-size:15px;">기타정보</label>
								</td>
							</tr>
							<tr>
								<td>3</td>
								<td>웹주소</td>
								<td><input type="text" name="web_address" id="web_address" placeholder="웹페이지 주소를 입력합니다." value="<?=$row_reg['web_address']?>" style="width: 100%;"></td>
							</tr>
							<tr>
								<td>4</td>
								<td>검색어</td>
								<td><input type="text" name="search_key" id="search_key" placeholder="관리자가 입력한 내용으로 검색합니다." style="width: 100%;" value='<?=$row_reg['search_key']?>'></td>
							</tr>
							<tr>
								<td>5</td>
								<td>진행/종료</td>
								<td>
								<?php
								$sta1 = "checked";
								if($row_reg['status'] == 1){
									$sta1 = "checked";
								}
								else if($row_reg['status'] == 2){
									// $sta1 = "";
									$sta2 = "checked";
								}
								?>
									<input type="radio" name="status" id="online" style="vertical-align: top;" <?=$sta1?>>
									<label for="online" value="1" style="font-size:15px;">진행사업</label>
									<input type="radio" name="status" id="end" style="vertical-align: top;" <?=$sta2?>>
									<label for="end" value="2" style="font-size:15px;">종료사업</label>
								</td>
							</tr>
							<tr>
								<td>6</td>
								<td>조회키워드</td>
								<td><input type="text" name="keyword" id="keyword" placeholder="페이지에서 조회할때 필요한 단어를 입력합니다." style="width: 100%;" value='<?=$row_reg['keyword']?>'></td>
							</tr>
							<tr>
								<td>7</td>
								<td onclick="show_hour()">
									<!-- <input type="checkbox" name="auto_upload_time" id="contents_auto_upload_time"> -->
									<label for="contents_auto_upload_time" style="margin-top: -7px;">수집시간</label>
								</td>
								<td><input type="text" name="upload_time" id="upload_time" placeholder="하루 1-24시간중 3회를 선택합니다." style="width: 100%;" value='<?=$row_reg['get_time']?>'></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td id="24_hours" style="display:none;" onclick="limit_sel_hour()">
								<?php
								for($i = 1; $i < 25; $i++){
								?>
								<input type="checkbox" name="select_hour" id="<?=$i?>hour">
								<label for="<?=$i?>hour" value="<?=$i?>" style="margin-top: -7px;"><?=$i?></label>
								<?}?>
								</td>
							</tr>
							<tr>
								<td rowspan="2" style="vertical-align: middle;">8</td>
								<td>기획내용</td>
								<td><input type="text" name="plan_detail" id="plan_detail" placeholder="해당 페이지에서 크롤링하는 기획내용을 입력합니다." style="width:100%;"></td>
							</tr>
							<tr>
								<td>파일첨부</td>
								<td><input type="text" name="add_file" id="add_file" placeholder="해당 페이지에서 크롤링하는 기획내용을 입력합니다." style="width:100%;"></td>
							</tr>
							<tr>
								<td>9</td>
								<td>코딩소스</td>
								<td><input type="text" name="code_source" id="code_source" placeholder="개발자가 위 기획내용을 보고 개발한 소스를 입력하면 해당 소스가 메인 소스에 적용되어 크롤링합니다." style="width:100%;"></td>
							</tr>
							<tr>
								<td>10</td>
								<td>메모내용</td>
								<td><input type="text" name="memo_detail" id="memo_detail" placeholder="관리자나 개발자가 필요한 메모를 합니다." style="width:100%;" value='<?=$row_reg['memo']?>'></td>
							</tr>
							<tr>
								<td>11</td>
								<td>게시일시</td>
								<td><input type="text" name="reg_date" id="reg_date" value="<?=$row_reg['reg_date']?>" style="width:100%;"></td>
							</tr>
							<tr>
								<td>12</td>
								<td>진행상태</td>
								<td>
								<?php
								if($row_reg['work_status'] == 1){
									$wors1 = "checked";
								}
								else if($row_reg['work_status'] == 2){
									$wors2 = "checked";
								}
								?>
									<input type="radio" name="status_work" id="goon" style="vertical-align: top;" <?=$wors1?>>
									<label for="goon" value="1" style="font-size:15px;">진행</label>
									<input type="radio" name="status_work" id="stop" style="vertical-align: top;" <?=$wors2?>>
									<label for="stop" value="2" style="font-size:15px;">대기</label>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</section>
		</div>
		
	</body>
</html>
