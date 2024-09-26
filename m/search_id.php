<?php 
include $_SERVER['DOCUMENT_ROOT']."/m/include/header.inc.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
?>

		<main id="find-info" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="inner-wrap">
                            <form name="id_form" action="" method="post">
							<section id="find-account" class="find-form"><!-- 아이디 찾기 시작 -->
								<h2 class="title">아이디 찾기</h2>
								<div class="form-wrap">
									<p class="type">회원유형</p>
									<p class="notice">아이디가 고객님의 등록 정보로 발송됩니다.</p>
									<div class="input-item">
										<div class="attr-name">이름</div>
										<div class="attr-value">
											<div class="input-wrap">
												<input type="text" class="input" name="mem_name" required itemname='이름' >
											</div>
										</div>
									</div>
									<div class="radio-wrap">
										<ul>
											<li>
												<input type="radio" id="accountPhone" class="radio" checked name="search_type" onClick="show_cencle('email_phone',0,1,'id_mobile','id_email','0','phone')">
												<label for="accountPhone">휴대폰으로 아이디를 검색합니다.</label>
											</li>
											<li>
												<input type="radio" id="accountMail" class="radio" name="search_type"  onClick="show_cencle('email_phone',1,0,'id_email','id_mobile','0','email')" >
												<label for="accountMail">이메일로 아이디를 검색합니다.</label>
											</li>
										</ul>
									</div>
									<div class="input-item email_phone">
										<div class="attr-name">휴대폰</div>
										<div class="attr-value is-phone">
											<div class="input-wrap">
												<input type="number" class="input id_mobile" name="mobile_1" required itemname='휴대폰' >
												<span class="bridge">-</span>
												<input type="number" class="input id_mobile" name="mobile_2" required itemname='휴대폰' >
												<span class="bridge">-</span>
												<input type="number" class="input id_mobile" name="mobile_3" required itemname='휴대폰' >
											</div>
										</div>
									</div>
									<div class="input-item email_phone" style="display:none">
										<div class="attr-name">이메일</div>
										<div class="attr-value is-phone">
											<div class="input-wrap">
												<input type="text" class="input mail-first id_email" name="email_1" required itemname='이메일' id="email_1">
												<span class="bridge">@</span>
												<input type="text" class="input mail-second id_email" name="email_2" id='email_2' itemname='이메일' required >
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
									<div class="button-wrap">
									    <input type="hidden" name="serch_type" value="phone" />
										<a href="javascript:;;" onClick="search_id_pw(id_form)" class="button is-grey">확인</a>
										<a href="javascript:location.reload()" class="button">취소</a>
									</div>
								</div>
							</section><!-- // 아이디 찾기 끝 -->
							</form>   <br><br><br><br>
                            <form name="pw_form" action="" method="post">
							<section id="find-password" class="find-form"><!-- 아이디 찾기 시작 -->
								<h2 class="title">비밀번호 찾기</h2>
								<div class="form-wrap">
									<p class="type">회원유형</p>
									<p class="notice">임시비밀번호가 고객님의 등록 정보로 발송됩니다.</p>
									<div class="input-item">
										<div class="attr-name">이름</div>
										<div class="attr-value">
											<div class="input-wrap">
												<input type="text" class="input" name="mem_name" required itemname='이름'>
											</div>
										</div>
									</div>
									<div class="input-item">
										<div class="attr-name">아이디</div>
										<div class="attr-value">
											<div class="input-wrap">
												<input type="text" class="input" name="mem_id" required >
											</div>
										</div>
									</div>
									<div class="radio-wrap">
										<ul>
											<li>
												<input type="radio" id="passwordPhone" class="radio" checked  name="search_type" onClick="show_cencle('email_phone',2,3,'pw_mobile','pw_email','1','phone')" >
												<label for="passwordPhone">휴대폰으로 비밀번호를 검색합니다.</label>
											</li>
											<li>
												<input type="radio" id="passwordMail" class="radio"  name="search_type"  onClick="show_cencle('email_phone',3,2,'pw_email','pw_mobile','1','email')" >
												<label for="passwordMail">이메일로 비밀번호를 검색합니다.</label>
											</li>
										</ul>
									</div>
									<div class="input-item email_phone">
										<div class="attr-name">휴대폰</div>
										<div class="attr-value is-phone">
											<div class="input-wrap">
												<input type="number" class="input pw_mobile" name="mobile_1" required itemname='휴대폰' >
												<span class="bridge">-</span>
												<input type="number" class="input pw_mobile" name="mobile_2" required itemname='휴대폰' >
												<span class="bridge">-</span>
												<input type="number" class="input pw_mobile" name="mobile_3" required itemname='휴대폰' >
											</div>
										</div>
									</div>
									<div class="input-item email_phone"  style="display:none">
										<div class="attr-name">이메일</div>
										<div class="attr-value is-phone">
											<div class="input-wrap">
												<input type="text" class="input mail-first pw_email" name="email_1" required itemname='이메일' id="email_1">
												<span class="bridge">@</span>
												<input type="text" class="input mail-second pw_email" name="email_2" id='email_2' itemname='이메일' required >
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
									<div class="button-wrap">
									    <input type="hidden" name="serch_type" value="phone" />
										<a href="javascript:;;" onClick="search_id_pw(pw_form)" class="button is-grey">확인</a>
										<a href="javascript:location.reload()" class="button">취소</a>
									</div>
								</div>
							</section><!-- // 아이디 찾기 끝 -->
                            </form>    
						</div>
					</div>
				</div>
			</div>
		</main><!-- // 컨텐츠 영역 시작 -->
        <div id="ajax_div" style="display:none"></div>
		<?php include $_SERVER['DOCUMENT_ROOT']."/m/include/menu.inc.php"; ?>

	</div>
<?php include $_SERVER['DOCUMENT_ROOT']."/m/include/footer.inc.php"; ?>
<script>
function search_id_pw(frm)
{
    if(frm.mem_name.value == "") {
        alert('이름을 입력해주세요');
        return;
    }
	var mem_name_s="";
	var mem_id_s="";
	var phone_s="";
	var email_s="";
	if(frm.mem_name)
	mem_name_s=frm.mem_name.value;
	if(frm.mem_id)
	mem_id_s=frm.mem_id.value;
	if(frm.serch_type.value=="phone")
	{
		phone_s=frm.mobile_1.value+"-"+frm.mobile_2.value+"-"+frm.mobile_3.value;
		email_s="";
	}
	else if(frm.serch_type.value=="email")
	{
		phone_s="";
        if(frm.email_3.value)
            email_s=frm.email_1.value+"@"+frm.email_3.value;
        else
            email_s=frm.email_1.value+"@"+frm.email_2.value;
	}
	if(confirm("정보를 찾으시겠습니까?"))
	{
		$($(".loading_div")[0]).show();
		$.ajax({
			 type:"POST",
			 url:"/m/ajax/ajax.php",
			 data:{
					search_id_pw_mem_name:mem_name_s,
					search_id_pw_mem_id:mem_id_s,
					search_id_pw_phone:phone_s,
					search_id_pw_email:email_s,
					search_id_pw_type:frm.search_type.value
				  },
			 success:function(data){$($(".loading_div")[0]).hide();$("#ajax_div").html(data)}
			})			
	}	
}    
function show_cencle(c1,i1,i2,c2,c3,type_i,type)
{
	$($("."+c1)[i1]).show();
	$($("."+c1)[i2]).hide();
	$("."+c2).attr("required","");
	$("."+c3).removeAttr("required");
	document.getElementsByName('serch_type')[type_i].value=type;
}
    $(function(){
        $('input[type=radio]').bind("click",function(){
           $('.pw_mobile').val('');
           $('.pw_email').val('');
           $('.id_mobile').val('');
           $('.id_email').val('');           
        });
    })
</script>