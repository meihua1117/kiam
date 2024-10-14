<?php include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?php
    $lang = $_COOKIE['lang']?$_COOKIE['lang']:"kr";
    $sql = "select * from Gn_Iam_lang where menu='IAM_PROFILE'";
    $result = mysqli_query($self_con,$sql);
    while($row = mysqli_fetch_array($result)) {
        $MENU[$row['menu']][$row['pos']] = $row[$lang];
    }
?>
<?
$member = $_GET['member'];
if($member == 'on') {
	$sql="select mem_name, zy, mem_phone, mem_email, mem_add1 from Gn_Member where mem_id = '{$_SESSION['iam_member_id']}'";
	$result=mysqli_query($self_con,$sql);
	$row=mysqli_fetch_array($result);
	//$card_idx = $row['idx'];
	$card_name = $row['mem_name'];
	$card_company = $row['zy'];
	// $card_position = $row['card_position'];
	$card_phone = $row['mem_phone'];
	$card_email = $row['mem_email'];
	$card_addr = $row['mem_add1'];
	// $profile_logo = $row['profile_logo'];
	$email_array = explode('@',$card_email);
}

?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>명함추가</title>
	<link rel="stylesheet" href="css/notokr.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/grid.min.css">
	<link rel="stylesheet" href="css/slick.min.css">
</head>
<body>
	<div id="wrap" class="common-wrap">
		<main id="register" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="inner-wrap">
							<h2 class="title"><?php echo $MENU['IAM_PROFILE']['TITLE'];?></h2>
							<section class="input-field">
								<h3 class="title"><?php echo $MENU['IAM_PROFILE']['SUB_TITLE'];?></h3>
								 <div class="utils clearfix">
									<a href="?member=on" class="button"><?php echo $MENU['IAM_PROFILE']['TOP_BTN'];?></a>
								</div>
								<div class="form-wrap">
                  					<form name="namecard_form" method="post" enctype="multipart/form-data">
                    					<input type="hidden" name="check_rnum" id="check_rnum" value="Y">
										<input type="hidden" name="mode" id="mode" value="creat">
										<input type="hidden" name="mem_id" id="mem_id" value="<?=$_SESSION['iam_member_id']?>">
										<div class="attr-row">
											<div class="attr-name"><?php echo $MENU['IAM_PROFILE']['TITLE1'];?></div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="text" id="card_title" name="card_title" class="input" maxlength="10">
												</div>
											</div>
										</div>
										<div class="attr-row">
											<div class="attr-name"><?php echo $MENU['IAM_PROFILE']['TITLE2'];?></div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="text" id="card_name" name="card_name" class="input" value="<?=$card_name?>">
												</div>
											</div>
										</div>
										<div class="attr-row">
											<div class="attr-name"><?php echo $MENU['IAM_PROFILE']['TITLE3'];?></div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="text" class="input" id="card_company" name="card_company" placeholder="<?php echo $MENU['IAM_PROFILE']['TITLE3_TITLE'];?>" value="<?=$card_company?>">
												</div>
											</div>
										</div>
										<div class="attr-row">
											<div class="attr-name"><?php echo $MENU['IAM_PROFILE']['TITLE4'];?></div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="text" class="input" id="card_position" name="card_position" placeholder="<?php echo $MENU['IAM_PROFILE']['TITLE4_TITLE'];?>" value="<?=$card_position?>">
												</div>
											</div>
										</div>
										<div class="attr-row is-phone">
											<div class="attr-name"><?php echo $MENU['IAM_PROFILE']['TITLE5'];?></div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="tel" id="card_phone" name="card_phone" class="input"  style="width: 100%" value="<?=$card_phone?>">
												</div>
											</div>
										</div>

										<div class="attr-row is-mail">
											<div class="attr-name"><?=$MENU['IAM_PROFILE']['TITLE6'];?></div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="text" style="width:135px" class="input mail-first" name="email_1" required itemname='이메일' id="email_1" value="<?=$email_array[0]?>">
													<span class="bridge">@</span>
													<input type="text" style="width:calc( 100% - 235px );"  class="input mail-second" name="email_2" id='email_2' itemname='이메일' required  value="<?php echo $email_array[1];?>">
													<select class="select" name="email_3" itemname='이메일' onchange="inmail(this.value,'email_2')">
														<?
														foreach($email_arr as $key=>$v)
														{
															?>
															<option value="<?=$key?>"><?=$v?></option>
														<?
														}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="attr-row">
											<div class="attr-name"><?php echo $MENU['IAM_PROFILE']['TITLE7'];?></div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="text" id="card_addr" name="card_addr" class="input" placeholder="<?php echo $MENU['IAM_PROFILE']['TITLE7_TITLE'];?>" value="<?=$card_addr?>">
												</div>
											</div>
										</div>

										<div class="attr-row">
											<div class="attr-name"><?php echo $MENU['IAM_PROFILE']['TITLE8'];?></div>
											<div class="attr-value">
												<div class="input-wrap">
													<textarea style="height:60px" id="card_keyword" name="card_keyword" class="input" placeholder="<?php echo $MENU['IAM_PROFILE']['TITLE8_TITLE'];?>"></textarea>
												</div>
											</div>
										</div>
										<div class="attr-row">
											<div class="attr-name"><?php echo $MENU['IAM_PROFILE']['TITLE9'];?></div>
											<input type="hidden" name="story_title4" id="story_title4" value="온라인정보" >
											<div class="attr-value">
												<div class="input-wrap">
													<div class="input-row">
														<input type="text" class="input" name="story_online1_text" id="story_online1_text" value="<?=$story_online1_text?>" style="width:40%" placeholder="<?php echo $MENU['IAM_PROFILE']['TITLE9_TITLE1'];?>">
													<input type="text" class="input" name="story_online1" id="story_online1" value="<?=$story_online1?>" style="width:50%" placeholder="<?php echo $MENU['IAM_PROFILE']['TITLE9_TITLE2'];?>">
													<input type="checkbox" id="radio_01" name="radio_01" class="radio" checked="true">

												</div>
												<div class="input-row">
														<input type="text" class="input" name="story_online2_text" id="story_online2_text" value="<?=$story_online2_text?>" style="width:40%" placeholder="<?php echo $MENU['IAM_PROFILE']['TITLE9_TITLE1'];?>">
													<input type="text" class="input" name="story_online2" id="story_online2" value="<?=$story_online2?>" style="width:50%" placeholder="<?php echo $MENU['IAM_PROFILE']['TITLE9_TITLE2'];?>">
													<input type="checkbox" id="radio_02" name="radio_01" class="radio">

												</div>
												</div>
											</div>
										</div>
									  	<div class="attr-row">
											<div class="attr-name"><?php echo $MENU['IAM_PROFILE']['TITLE10'];?></div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="file" class="input" id="profile_logo" name="profile_logo" placeholder="파일">
												</div>
											</div>
									  	</div>
										<div class="attr-row">
											<div class="attr-name"><?php echo $MENU['IAM_PROFILE']['TITLE11'];?></div>
											<div class="attr-value">
												<div class="input-wrap">
													<?php echo $MENU['IAM_PROFILE']['TITLE11_TITLE'];?>
								       			</div>
								   			</div>
											<!--div class="utils clearfix">
												<a href="https://bit.ly/2wg4v3g" target="_blank" class="button"><?php echo $MENU['IAM_PROFILE']['TITLE11_BTN'];?></a>
											</div-->
								    	</div>
					                </form>
								</div>
							</section>
							<div class="button-wrap">
								<a href="/iam/" class="button is-grey"><?php echo $MENU['IAM_PROFILE']['BTN_1'];?></a>
								<a href="javascript:namecard_check();" class="button is-pink"><?php echo $MENU['IAM_PROFILE']['BTN_2'];?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main><!-- // 컨텐츠 영역 끝 -->
	</div>


<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/slick.min.js"></script>
<script src="js/main.js"></script>
<script>
function inmail(v,id)
{
	$("#"+id).val(v);
}
function chk_sms()   {
if($('input[name=card_phone]').val() == "") {
    alert('인증받으실 전화번호를 입력해주세요.')
    return;
}
$.ajax({
   type:"POST",
   url:"ajax/namecard.phone.proc.php",
   cache: false,
   dataType:"json",
   data:{
     mode:"send_sms",
	 rphone:$('input[name=card_phone]').val()
     //rphone:$('input[name=card_phone1]').val()+"-"+$('input[name=card_phone2]').val()+"-"+$('input[name=card_phone3]').val()
     },
   success:function(data){
      if(data.result == "success")
          $('#check_rnum').val("Y");
      else
           $('#check_rnum').val("");

      alert(data.msg);
      }
  })
}

function namecard_check() {
  var formData = new FormData();

  if(!$('#card_name').val()) {
		alert("성명을 입력하세요.");
		$('#card_name').focus();
		return false;
	}

  if(!$('#card_company').val()) {
		alert("소속과 직책을 입력하세요");
		$('#card_company').focus();
		return false;
	}

  if(!$('#card_position').val()) {
		alert("한문장으로 자기를 소개해요.");
		$('#card_position').focus();
		return false;
	}

  // if($('input[name=card_phone1]').val() == "" || $('input[name=card_phone2]').val() == "" || $('input[name=card_phone3]').val() == "") {
  //     alert('휴대폰번호를 입력하세요.')
  //     return false;
  // }

  // if(!$('#card_email').val()) {
	// 	alert("이메일을 입력하세요.");
	// 	$('#card_email').focus();
	// 	return false;
	// }
  //
  // if(!$('#card_addr').val()) {
	// 	alert("주소를 입력하세요.");
	// 	$('#card_addr').focus();
	// 	return false;
	// }

  // if(!$('#profile_logo').val()) {
	// 	alert("명함로고(파일이미지)를 첨부하세요.");
	// 	$('#profile_logo').focus();
	// 	return false;
	// }

    formData.append('mode', $('#mode').val());
	formData.append('mem_id', $('#mem_id').val());
	formData.append('card_title', $('#card_title').val());
    formData.append('card_name', $('#card_name').val());
    formData.append('card_company', $('#card_company').val());
    formData.append('card_position', $('#card_position').val());
    //formData.append('card_phone', $('#card_phone1').val()+"-"+$('#card_phone2').val()+"-"+$('#card_phone3').val());
	formData.append('card_phone', $('#card_phone').val());
    formData.append('card_email', $('#email_1').val() + "@" + $('#email_2').val());
    formData.append('card_addr', $('#card_addr').val());
    formData.append('card_map', $('#card_map').val());
	formData.append('card_keyword', $('#card_keyword').val());

	formData.append('story_title4', $('#story_title4').val());
	formData.append('story_online1_text', $('#story_online1_text').val());
	formData.append('story_online2_text', $('#story_online2_text').val());
	formData.append('story_online1', $('#story_online1').val());
	formData.append('story_online2', $('#story_online2').val());

	if($('#radio_01').is(":checked")) {
		formData.append('online1_check', "Y");
	} else {
		formData.append('online1_check', "N");
	}
	if($('#radio_02').is(":checked")) {
		formData.append('online2_check', "Y");
	} else {
		formData.append('online2_check', "N");
	}

  if($('#profile_logo')[0].files.length) {
		formData.append('uploadFile', $('#profile_logo')[0].files[0]);
	}

  // for (var value of formData.values()) {
  //   console.log(value);
  // }

  $.ajax({
		 type:"POST",
		 url:"ajax/namecard.proc.php",
		 data: formData,
		 contentType: false,
		 processData: false,
		 success:function(data){
		    if(data.length > 20) {
		        alert(data);
		    }else {
			alert("명함등록이 완료 되었습니다.");
            		location.href = "/index.php?" + data;
			}
		}
	});
}

</script>
</body>
</html>
