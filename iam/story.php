<?php include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?
// echo "<script>console.log('console : ". $_SESSION['one_member_id']. "');</script>"

$card_idx = $_GET['card_num'];

$sql="select * from Gn_Iam_Name_Card where idx = '$card_idx' and mem_id = '{$_SESSION['iam_member_id']}'";
$result=mysqli_query($self_con, $sql);
$row=mysqli_fetch_array($result);

// $card_idx = $row['idx'];
$story_title1 = $row[story_title1];
$story_title2 = $row[story_title2];
$story_title3 = $row[story_title3];
$story_title4 = $row[story_title4];

if(!$story_title1) {
	$story_title1 = "내 소개";
}
if(!$story_title2) {
	$story_title2 = "현재소속";
}
if(!$story_title3) {
	$story_title3 = "경력소개";
}
if(!$story_title4) {
	$story_title4 = "온라인정보";
}

$story_online1_text = $row[story_online1_text];
$story_online2_text = $row[story_online2_text];

$story_myinfo = $row[story_myinfo];
$story_company = $row[story_company];
$story_career = $row[story_career];
$story_online1 = $row[story_online1];
$online1_check = $row[online1_check];
$story_online2 = $row[story_online2];
$online2_check = $row[online2_check];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>온리원 아이엠</title>
	<link rel="stylesheet" href="css/notokr.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/grid.min.css">
	<link rel="stylesheet" href="css/slick.min.css">
</head>
<body>
	<div id="wrap" class="common-wrap">

		<header id="header"><!-- 헤더 시작 -->
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="inner-wrap">
							<h2 class="logo">
								<a href="#">
									<img src="img/common/logo-2.png" alt="아이엠 로고 이미지" style="height:23.813px;">
								</a>
							</h1>
						</div>
					</div>
				</div>
			</div>
		</header><!-- // 헤더 끝 -->

		<main id="star" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
			<section id="middle"><!-- 중단 컨텐츠 영역 시작 -->
				<div class="content-wrap">
					<div class="content-box active" id="story"><!-- 스토리 시작 -->
						<div class="box-body">
							<div class="inner">

								<!--
									아래 부분은 편집버튼 클릭 시 활성화 되도록 작업 부탁드립니다.
									제가 단순히 display none <-> block 으로 토글 되도록 할 작업이 아니라고 판단됩니다.-->
								<form name="story_form" method="post">
									<input type="hidden" name="mem_id" id="mem_id" value="<?=$_SESSION['iam_member_id']?>">
									<input type="hidden" name="card_idx" id="card_idx" value="<?=$card_idx?>">
									<input type="hidden" name="online1_check" id="online1_check" value="<?=$online1_check?>">
									<input type="hidden" name="online2_check" id="online2_check" value="<?=$online2_check?>">
									<div class="story-item">
										<!-- <div class="title"><span class="text">내 소개</span></div> -->
											<input type="text" id="story_title1" value="<?=$story_title1?>" maxlength="8" oninput="MaxLength(this);" style="border: 1px solid #bfbfbf;">
										<div class="content">
											<textarea class="textarea" name="story_myinfo" id="story_myinfo"><?=$story_myinfo?></textarea>
										</div>
									</div>
									<div class="story-item">
										<!-- <div class="title"><span class="text">현재소속</span></div> -->
											<input type="text" id="story_title2" value="<?=$story_title2?>" maxlength="8" oninput="MaxLength(this);" style="border: 1px solid #bfbfbf;">
										<div class="content">
											<textarea class="textarea" name="story_company" id="story_company"><?=$story_company?></textarea>
										</div>
									</div>
									<div class="story-item">
										<!-- <div class="title"><span class="text">경력소개</span></div> -->
											<input type="text" id="story_title3" value="<?=$story_title3?>" maxlength="8" oninput="MaxLength(this);" style="border: 1px solid #bfbfbf;">
										<div class="content">
											<textarea class="textarea" name="story_career" id="story_career"><?=$story_career?></textarea>
										</div>
									</div>
									<!-- <div class="story-item">
											<input type="text" id="story_title4" value="<?=$story_title4?>" maxlength="8" oninput="MaxLength(this);" style="border: 1px solid #bfbfbf;">
										<div class="content">
											<div class="input-row">
													<input type="text" class="input" name="story_online1_text" id="story_online1_text" value="<?=$story_online1_text?>" style="width:30%">
												<input type="text" class="input" name="story_online1" id="story_online1" value="<?=$story_online1?>" style="width:50%">
												<input type="checkbox" id="radio_01" name="radio_01" class="radio" checked=true>

											</div>
											<div class="input-row">
													<input type="text" class="input" name="story_online2_text" id="story_online2_text" value="<?=$story_online2_text?>" style="width:30%">
												<input type="text" class="input" name="story_online2" id="story_online2" value="<?=$story_online2?>" style="width:50%">
												<input type="checkbox" id="radio_02" name="radio_01" class="radio">

											</div>
										</div>
									</div> -->

									<div class="button-wrap">
										<a href="javascript:history.back()" class="buttons cancel">취소</a>
										<a href="javascript:story_upload();" class="buttons save">저장</a>
									</div>

                				</form>
							</div>
						</div>

					</div><!-- // 스토리 끝 -->
				</div>
			</section><!-- // 중단 컨텐츠 영역 끝 -->
		</main><!-- // 컨텐츠 영역 끝 -->
	</div>

<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/slick.min.js"></script>
<script src="js/main.js"></script>
<script>

if($('#online1_check').val() == "Y") {
  $('#radio_01').attr("checked", true);
}

if($('#online2_check').val() == "Y") {
  $('#radio_02').attr("checked", true);
}

//글자수 제한
function MaxLength(e){
	if(e.value.length > e.maxLength){
			e.value = e.value.slice(0, e.maxLength);
		}
}

// 대표이미지 업로드
function story_upload()   {
	var formData = new FormData();

  // if(!$('#story_myinfo').val()) {
	// 	alert("내소개를 입력하세요.");
	// 	$('#story_myinfo').focus();
	// 	return false;
	// }
  // if(!$('#story_company').val()) {
	// 	alert("현재소속을 입력하세요.");
	// 	$('#story_company').focus();
	// 	return false;
	// }
  // if(!$('#story_career').val()) {
	// 	alert("경력을 입력하세요.");
	// 	$('#story_career').focus();
	// 	return false;
	// }

  // if(!$('#story_online1').val()) {
	// 	alert("온라인정보를 입력하세요.");
	// 	$('#story_online1').focus();
	// 	return false;
	// }


  	formData.append('mem_id', $('#mem_id').val());
  	formData.append('card_idx', $('#card_idx').val());

	formData.append('story_title1', $('#story_title1').val());
	formData.append('story_title2', $('#story_title2').val());
	formData.append('story_title3', $('#story_title3').val());
	formData.append('story_title4', $('#story_title4').val());

  	formData.append('story_myinfo', $('#story_myinfo').val());
  	formData.append('story_company', $('#story_company').val());
  	formData.append('story_career', $('#story_career').val());

	// 2020/04/15 사이트정보 명함으로 이동
	// formData.append('story_online1_text', $('#story_online1_text').val());
	// formData.append('story_online2_text', $('#story_online2_text').val());
  	// formData.append('story_online1', $('#story_online1').val());
  	// formData.append('story_online2', $('#story_online2').val());
	//
  	// if($('#radio_01').is(":checked")) {
  	//   formData.append('online1_check', "Y");
  	// } else {
  	//   formData.append('online1_check', "N");
  	// }
  	// if($('#radio_02').is(":checked")) {
  	//   formData.append('online2_check', "Y");
  	// } else {
  	//   formData.append('online2_check', "N");
  	// }

	// for (var value of formData.values()) {
	// 	console.log(value);
	// }

	$.ajax({
		 type:"POST",
		 url:"ajax/story.proc.php",
		 data: formData,
		 contentType: false,
		 processData: false,
		 success:function(data){
			alert(data);
			
			//window.opener.location.reload();
			window.opener.refreshStoryTab($('#story_title1').val(),
										$('#story_myinfo').val(),
										$('#story_title2').val(),
										$('#story_company').val(),
										$('#story_title3').val(),
										$('#story_career').val());
			window.close();
		}
	});
}

</script>
</body>
</html>
