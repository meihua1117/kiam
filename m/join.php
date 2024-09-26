<?php 
include $_SERVER['DOCUMENT_ROOT']."/m/include/header.inc.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if($_COOKIE['mem_code']) {
	$sql="select * from Gn_Member where mem_code='$_COOKIE[mem_code]' and site != ''";
	$result=mysqli_query($self_con, $sql);
	$info=mysqli_fetch_array($result);

	//$sql="select * from Gn_Service where mem_id='$info['mem_id']'";
	//$result=mysqli_query($self_con, $sql);
	//$sinfo=mysqli_fetch_array($result);
}
?>
<style>
.desc li {
    margin-bottom: 5px;
    font-size: 12px;
    line-height: 18px;
}    
.input-wrap a {
    float: right;
    width: 75px;
    display: block;
    margin-left: 5px;
    padding: 7px 5px;
    font-size: 11px;
    color: #3B240B;
    line-height: 14px;
    background-color: #F6D8CE;
    text-align: center;
}
.check-wrap .check ~ label:before {
    content: '';
    position: absolute;
    top: 3px;
    left: 0;
    width: 18px;
    height: 18px;
    background-color: #fff;
    border: 1px solid #ccc;
}
.check-wrap .check ~ label {
    position: relative;
    display: inline-block;
    padding-left: 25px;
    line-height: 24px;
}
.check-wrap .check:checked ~ label:after { content: '\f00c'; position: absolute; top: 1px; left: 2px; color: #fff; font-family: 'Fontawesome'; font-size: 13px; }
.check-wrap .check:checked ~ label:before { background-color: #ff0066; border-color: #ff0066; }
.lselect {
    float: left;
    width: 70px;
    height: 28px;
    background-color: #fff;
    border: 1px solid #ccc;
    font-size: 12px;
    line-height: 16px;
}
</style>
		<main id="register" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="inner-wrap">
							<h2 class="title">회원가입하기</h2>
                            <form name="join_form" id="join_form" method="post" >
                                <input type="hidden" name="check_rnum" id="check_rnum" value="">
								<?$site = explode(".",$HTTP_HOST);$site_name = $site[0];?>
								<input type="hidden" name="site" id="site" value="site">
								<input type="hidden" name="site_name" id="site_name" value='<?=$site_name?>'>
								<section class="input-field">
									<h3 class="title">1. 아이엠과 온리원셀링 통합회원가입</h3>
									<div class="utils clearfix">
										<span class="notice">회원가입 동시에 온리원셀링과 아이엠을 함께 사용합니다.
 													<br>그래서 <font color="red">모든 정보가 필수정보로 되어 있으니</font> 빠짐없이 정확하게 입력해주세요.</span>
							    	 	<div class="utils clearfix">
									        <a href="/?DOSEhGYXOC14769" target="_blank" class="button"><font color="black">아이엠 샘플보기</font></a>
								     	</div>
									</div>
									<div class="form-wrap">
										<div class="attr-row is-account">
											<div class="attr-name">아이디</div>
											<div class="attr-value">
												<div class="input-wrap" style="display: flex">
													<input type="text" class="input" name="id" id="id" placeholder="4자 ~ 15자 입력" style="width: 50%;">
													<a href="javascript:;;" onClick="id_check(join_form,'join_form')"><font color="black">중복확인</font></a>
													&nbsp; <p id='id_html'style="font-weight:normal; font-size:13px;">  &nbsp;&nbsp; ※ 아이디 중복확인을 클릭해주세요.</p>
													<input type="hidden" name="id_status" id="id_status" itemname='아이디중복확인' required />
												</div>
											</div>
										</div>
										<div class="attr-row">
											<div class="attr-name">비밀번호</div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="password" class="input"  name="pwd" id="pwd" placeholder="6자 ~ 15자 입력">
												</div>
											</div>
										</div>
										<div class="attr-row">
											<div class="attr-name">비번확인</div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="password" class="input" name="pwd_cfm"  id="pwd_cfm" required>
												</div>
											</div>
										</div>
										<div class="attr-row is-phone">
											<div class="attr-name">폰번호</div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="number" class="input" name="mobile_1" id="mobile_1" required>
													<span class="bridge">-</span>
													<input type="number" class="input" name="mobile_2" id="mobile_2" required>
													<span class="bridge">-</span>
													<input type="number" class="input" name="mobile_3" id="mobile_3" required>
												</div>
												<div class="desc">
												<ul>
													<li>※ 입력한 폰번호는 수정되지 않습니다. 다시 한번 확인하세요.</li>
												</ul>
												</div>
											</div>
										</div>
										<div class="attr-row is-phone">
											<div class="attr-name">인증번호</div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="text" class="input" name="rnum" id="rnum" required>
													<a href="javascript:;;" onClick="chk_sms()" >인증번호받기</a>
												</div>
											</div>
										</div>
										<div class="attr-row is-phone">
											<div class="attr-name">성명/성별</div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="text" class="input"  name="name"  id="name" required>
												<div class="check-wrap">
													<input type="checkbox" class="check" id="mem_sex_m" name="mem_sex" value="m">
													<label for="mem_sex_m">남자</label>
												</div>
												<div class="check-wrap">
													<input type="checkbox" class="check"  id="mem_sex_f" name="mem_sex" value="f">
													<label for="mem_sex_f">여자</label>
												</div>
												</div>
											</div>
										</div>
										<div class="attr-row">
											<div class="attr-name">생년월일</div>
											<div class="attr-value">
												<div class="input-wrap">
													<select name="birth_1" </li> required itemname='생년'>
													<option value="">년</option>
														<?
														for($i=date("Y"); $i>1899; $i--)
														{
															$selected=$m_birth_arr[0]==$i?"selected":"";
														?>
														<option value="<?=$i?>" <?=$selected?>><?=$i?></option>
														<?
														}
														?>
													</select>
													<select name="birth_2" required class="select"itemname='월'>
														<option value="">월</option>
														<?
														for($i=1; $i<13; $i++)
														{
															$k=$i<10?"0".$i:$i;
															$selected=$m_birth_arr[1]==$k?"selected":"";
														?>
														<option value="<?=$k?>" <?=$selected?>><?=$k?></option>
														<?
														}
														?>
													</select>
													<select name="birth_3" required class="select" itemname='일'>
														<option value="">일</option>
														<?
														for($i=1; $i<32; $i++)
														{
															$k=$i<10?"0".$i:$i;
															$selected=$m_birth_arr[2]==$k?"selected":"";
														?>
														<option value="<?=$k?>" <?=$selected?>><?=$k?></option>
														<?
														}
														?>
													</select>
												</div>
											</div>
										</div>
										<div class="attr-row">
											<div class="attr-name">소속/직책</div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="text" name="zy" id="zy" class="input" placeholder="단체(직장)명과 직책을 '/'로 구분 입력">
												</div>
											</div>
										</div>
										<div class="attr-row">
											<div class="attr-name">자택주소</div>
											<div class="attr-value">
												<div class="input-wrap">
												<?php
													// 광역시도 목록
													$province_list = array();
													$query = "SELECT province FROM gn_cities group by province";
													$res = mysqli_query($self_con, $query);
													while($row = mysqli_fetch_array($res)) {
														$province_list[] = $row['province'];
													}
												?>
													<select itemname="주소" id = "value_region_province" class="select" style="height: 28px;margin-top:5px;">
														<option value="">-시/도-</option>
														<?php foreach($province_list as $province) {?>
															<option value="<?=$province?>"><?=$province?></option>
														<?php } ?>
													</select>
													<select id="value_region_city" itemname="주소" class="select" style="height: 28px;margin-top:5px;">
														<option value="">-군/구-</option>
													</select>
													<select id="value_region_town" itemname="주소" class="select" style="height: 28px;margin-top:5px;">
														<option value="">-읍/면/동-</option>
													</select>
													<input type="text" name="add1" id="add1" required  class="input" placeholder="지역통계를 위해 읍,면,동까지 입력" style="display: none">
												</div>
											</div>
										</div>
										<div class="attr-row is-mail">
											<div class="attr-name">이메일</div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="text" class="input mail-first" name="email_1" required itemname='이메일' id="email_1">
													<span class="bridge">@</span>
													<input type="text" class="input mail-second" name="email_2" id='email_2' itemname='이메일' required >
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
										<div class="attr-row  is-phone">
											<div class="attr-name">추천인 ID</div>
											<div class="attr-value">
												<div class="input-wrap">
													<input type="text"  class="input" name="recommend_id" id="recommend_id" onblur="check_recommender()" itemname='추천인ID' value="<?echo $info['mem_id']?>" <?if($info['mem_id']!="") echo  "readonly";?>/>
													<input type="hidden" id="is_exist_recommender" name="is_exist_recommender">
												</div>
											   	<div class="desc">
													<ul>
														<li>※ 입력한 ID는 수정되지 않습니다. (없으면 패스)</li>
													</ul>
												</div>
											</div>
										</div>

										<div class="attr-row">
											<div class="attr-name">앱다운받기</div>
											<div class="attr-value">
												<div class="input-wrap">
												<textarea style="height:70px"
												class="input" name="keywords"
												id="keywords" placeholder="온리원셀링앱을 설치하면 콜백문자, 대량문자, 포털디비수집, 365일 자동메시지발송, 자동예약메시지기능, 아이엠기능 등을 사용할수 있습니다."></textarea>
												</div>
											</div>
										</div>
									</div>
									<div class="agreement-field">
										<div class="agreement-wrap">
											<div class="agreement-item">
												<div class="check-wrap">
													<input type="checkbox" class="check" id="is_message" name="is_message" checked>
													<label for="is_message">온리원그룹 소식받기</label>
													<br>※ 아이엠, 셀링솔루션, 셀링대회, 제휴업체, 셀링교육, 마케팅지원과 온리원그룹 활동 및 사업소식을 전달합니다.
												</div>
											</div>
										</div>
									</div>
								</section>

								<section class="agreement-field">
									<h3 class="title">2. 약관 동의하기</h3>
									<p class="notice">서비스 이용을 위한 이용약관, 개인정보 처리방침 등 필수항목에 동의해주세요.</p>
									<div class="agreement-wrap">
										<div class="agreement-item is-checkall">
											<div class="check-wrap">
												<input type="checkbox" class="check" id="checkAll" >
												<label for="checkAll">모두 동의</label>
											</div>
										</div>
										<div class="agreement-item">
											<div class="check-wrap">
												<input type="checkbox" class="check checkAgree" id="checkPersonal" >
												<label for="checkPersonal">개인정보수집동의</label>
											</div>
											<a href="/m/privacy.php"><font color="black">내용보기</font></a>
										</div>
										<div class="agreement-item">
											<div class="check-wrap">
												<input type="checkbox" class="check checkAgree" id="checkTerms" >
												<label for="checkTerms">회원이용약관</label>
											</div>
											<a href="/m/terms.php"><font color="black">내용보기</font></a>
										</div>
										<div class="agreement-item">
											<div class="check-wrap">
												<input type="checkbox" class="check checkAgree" id="checkReceive" >
												<label for="checkReceive">메시지수신동의</label>
											</div>
											<div class="desc">
												<ul>
													<li>① 메시지 종류 : 아이엠 프로필 및 솔루션의 기능개선 메시지정보, 앱체크정보, 회원관리정보, 공익정보, 유익정보,  회원프로필정보를 발송합니다.</li>
													<li>② 메시지 발송 방법 : 고객님이 설치한 문자앱을 통해 고객님 폰의 문자를 활용하여 고객님의 계정에서 볼수 있게 합니다.</li>
												</ul>
											</div>
										</div>
										<div class="agreement-item">
											<div class="check-wrap">
												<input type="checkbox" class="check checkAgree" id="checkThirdparty" >
												<label for="checkThirdparty">개인정보 제3자 제공 동의</label>
											</div>
											<div class="desc">
												<ul>
													<li>① 제공받는 자 : 본 서비스를 개발하는 온리원계열사, 본 서비스 제공을 지원하는 협업사, 상품을 제공하는 쇼핑몰  관계사, 기타 본서비스 제공에 필요한 기관</li>
													<li>② 개인정보 이용 목적 : 서비스 제공을 위한 고객정보의 활용, 서비스 정보의 제공, e프로필서비스의 공유, 회원간의 품앗이 정보공유 등</li>
													<li>③ 개인정보의 항목 : 개인정보 제공에 동의한 내용</li>
													<li>④ 보유 및 이용 기간 :본 서비스를 이용하는 기간</li>
													<li>⑤ 제공 동의에 거부시 본 서비스가 제공되지 않습니다.</li>
												</ul>
											</div>
										</div>
									</div>
								</section>
                            
								<div class="button-wrap">
									<a href="javascript:history.back(-1);" class="button is-grey">취소</a>
									<a href="javascript:save_form()" class="button is-pink">회원가입</a>
								</div>
                            </form>
						</div>
					</div>
				</div>
			</div>
		</main><!-- // 컨텐츠 영역 시작 -->
        <div id="ajax_div" style="display:none"></div>
		<?php include $_SERVER['DOCUMENT_ROOT']."/m/include/menu.inc.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT']."/m/include/footer.inc.php"; ?>
<script language="javascript">
$(function(){
   $('.checkAgree').on("change",function(){
       $('.checkAgree').each(function(){
            if($(this).prop("checked") == false) {
                $('#checkAll').prop("checked", false);
            }
       });
   });
    
    $('#mem_sex_m').on("click",function(){
        $('#mem_sex_f').prop("checked", false);
    });
    $('#mem_sex_f').on("click",function(){
        $('#mem_sex_m').prop("checked", false);
    });    
    $("#value_region_province").on('change', function(){
        var province = $(this).val();
        $("#value_region_city").html('<option value="">-시/군/구-</option>');
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('/location.php', {'type':'cities', 'location':province}, function(res){
            if(res.status == '1') {
                var locations = res.locations;
                var html = '<option value="">-시/군/구-</option>';
                for(var i = 0; i < locations.length; i++) {
                    var location = locations[i];
                    html += '<option value="'+location+'">'+location+'</option>';
                }
                $("#value_region_city").html(html);
            }
        }, 'json');
    });

    $("#value_region_city").on('change', function(){
        var city = $(this).val();
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('/location.php', {'type':'towns', 'location':city}, function(res){
            if(res.status == '1') {
                var locations = res.locations;
                var html = '<option value="">-읍/면/동-</option>';
                for(var i = 0; i < locations.length; i++) {
                    var location = locations[i];
                    html += '<option value="'+location+'">'+location+'</option>';
                }
                $("#value_region_town").html(html);
            }
        }, 'json');
    });

    $("#value_region_town").on('change', function(){
        if($(this).val() != "") {
            var address = $("#value_region_province").val() + " " + $("#value_region_city").val() + " " + $(this).val();
            $("#add1").val(address);
        }
    });
}) 

function check_recommender()
{
	if($('#recommend_id').val() != '')
	{
		$.ajax({
			 type:"POST",
			 url:"/ajax/join.proc.php",
			 cache: false,
			 dataType:"json",
			 data:{
				 mode:"check_recommender",
				 rphone: $('#recommend_id').val()
				 },
			 success:function(data){
			    if(data.result == "success") {
					$('#is_exist_recommender').val("Y");
			    } else{
					$('#is_exist_recommender').val("N"); 
					alert('아이디가 없습니다.');
				}
			    }
			})    		
	}
}
    function save_form() {
        if($('#id').val() == "") {
            alert('아이디를 입력해 주세요.');
            return;
        }
        
        if($('#id').val().length < 4 || $('#pwd').val().length >15) {
            alert('아이디는 4자~15자로 입력해 주세요.');
            return;            
        }                 
        
        if($('#pwd').val() == "") {
            alert('비밀번호를 입력해 주세요.');
            return;            
        }        
        if($('#pwd').val().length < 6 || $('#pwd').val().length >15) {
            alert('비밀번호는 6자~15자로 입력해 주세요.');
            return;            
        }         
        
        if($('#pwd_cfm').val() == "") {
            alert('비밀번호 확인를 입력해 주세요.');
            return;                        
        }                
        
        if($('#pwd_cfm').val() != $('#pwd').val()){
            alert('비밀번호 확인를 입력해 주세요.');
            return;                                    
        }                
        
        if($('#name').val() == "") {
            alert('성명/성별을 입력해 주세요.');
            return;                                    
        }       
        
        if($('#zy').val() == "") {
            alert('소속/직책을 입력해 주세요.');
            return;                                                
        }                                         
        
        if($('#mobile_1').val() == "") {
            alert('휴대폰 번호를 입력해 주세요.');
            return;                                    
        }
        
        if($('#mobile_2').val() == "") {
            alert('휴대폰 번호를 입력해 주세요.');
            return;                                                
        }
                
        if($('#mobile_3').val() == "") {
            alert('휴대폰 번호를 입력해 주세요.');
            return;                                                
        }
        
        if($('#rnum').val() == "") {
            alert('휴대폰 인증번호를 입력해 주세요.');
            return;      
        }        
        if($('#add1').val() == "") {
            alert('자택주소를 입력해 주세요.');
            return;                                                
        }        
                
        if($('#email_1').val() == "") {
            alert('이메일을 입력해 주세요.');
            return;                                                
        }        
        if($('#email_2').val() == "") {
            alert('이메일을 입력해 주세요.');
            return;                                                
		}
		
		if($('#id').val() == $('#recommend_id').val())
		{
			alert('자신의 아이디는 추천에 입력되지 않습니다.');
			return;
		}

		/*if($('#is_exist_recommender').val() == "N" )
		{
			alert('추천인을 정확히 입력해 주세요.');
			return;			
		}*/
        
        if($("#checkPersonal").is(":checked") == false) {
            alert('개인정보수집동의에 동의해주세요.');
            return;
        }
        if($("#checkTerms").is(":checked") == false) {
            alert('회원이용약관에 동의해주세요.');
            return;
        }
        if($("#checkReceive").is(":checked") == false) {
            alert('메시지수신동의에 동의해주세요.');
            return;
        }
        if($("#checkThirdparty").is(":checked") == false) {
            alert('개인정보 제3자 제공 동의에 동의해주세요.');
            return;
        }
        if(confirm('등록하시겠습니까?')) {
            $('#join_form').attr("action","/m/ajax/ajax.php");
            $('#join_form').submit();
        }
        
    }
    function chk_sms()   {
        if($('input[name=mobile_1]').val() == "" || $('input[name=mobile_2]').val() == "" || $('input[name=mobile_3]').val() == "") {
            alert('인증받으실 휴대폰번호를 입력해주세요.')
            return;
        }
        if(($('input[name=mobile_1]').val()) != '010' && ($('input[name=mobile_1]').val()) != '011' && ($('input[name=mobile_1]').val()) != '016' && ($('input[name=mobile_1]').val()) != '017' && ($('input[name=mobile_1]').val()) != '018' && ($('input[name=mobile_1]').val()) != '019') {
            alert('전화번호 첫번째 자리는 010, 011, 016, 017, 018, 019만 가능합니다.')
            return;                        
        }
        
        if(($('input[name=mobile_1]').val()).length <3 || ($('input[name=mobile_1]').val()).length > 3) {
            alert('인증받으실 전화번호를 확인해주세요.')
            return;            
        }
        
        if(($('input[name=mobile_2]').val()).length <3 || ($('input[name=mobile_2]').val()).length > 4) {
            alert('인증받으실 전화번호를 확인해주세요.')
            return;            
        }

        if(($('input[name=mobile_3]').val()).length <4 || ($('input[name=mobile_1]').val()).length > 4) {
            alert('인증받으실 전화번호를 확인해주세요.')
            return;            
        }           
		$.ajax({
			 type:"POST",
			 url:"/ajax/join.proc.php",
			 cache: false,
			 dataType:"json",
			 data:{
				 mode:"send_sms",
				 rphone:$('input[name=mobile_1]').val()+"-"+$('input[name=mobile_2]').val()+"-"+$('input[name=mobile_3]').val()
				 },
			 success:function(data){
			    if(data.result == "success") {
			        $('#check_rnum').val("Y");
			    } else 
			         $('#check_rnum').val("");
			         
			    alert(data.msg);
			    }
			})    
    }    
    $(function(){
        $('#checkAll').on("change",function() {
            if($('#checkAll').prop("checked") == true) {
                $("#checkPersonal").prop("checked", true);
                $("#checkTerms").prop("checked", true);
                $("#checkReceive").prop("checked", true);
                $("#checkThirdparty").prop("checked", true);
            } else {
                $("#checkPersonal").prop("checked", false);
                $("#checkTerms").prop("checked", false);
                $("#checkReceive").prop("checked", false);
                $("#checkThirdparty").prop("checked", false);
            }
        })
    });
 
function id_check(frm,frm_str) {
	if(!frm.id.value)
	{
		frm.id.focus();
		return
	}
        if($('#id').val().length < 4 ) {
            alert('아이디는 4자~15자를 입력해 주세요.');
			$('#id').focus();
            return;            
        }    	
    var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
    if (!pattern.test(frm.id.value)) 
    {
        alert('영문 소문자와 숫자만 사용이 가능합니다.');
	 	frm.id_status.value='';
	    frm.id.value='';
	    frm.id.focus();
	    return;
    }

	 $.ajax({
		 type:"POST",
		 url:"/ajax/ajax.php",
		 data:{
			 	id_che:frm.id.value,
		 		id_che_form:frm_str,
			 	solution_type : frm.site.value,
			 	solution_name : frm.site_name.value
		 },
		 success:function(data){
		    $("#ajax_div").html(data);
		    //alert('사용 가능한 아이디 입니다.');
		 }
	 });
}
function inmail(v,id)
{
    $("#"+id).val(v);
}
function searchManagerInfo() {
        var winw_pop = window.open('/searchManagerInfo.php', 'popup', 'width=624, height=744, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,scrolling=yes');
        if(winw_pop == null) {
            alert('팝업 차단을 해제 해주세요.');
        } else {
            winw_pop.focus();
        }
    
}
function searchBranchManagerInfo() {
        var winw_pop = window.open('/searchBranchManagerInfo.php', 'popup', 'width=624, height=744, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,scrolling=yes');
        if(winw_pop == null) {
            alert('팝업 차단을 해제 해주세요.');
        } else {
            winw_pop.focus();
        }
    
}
</script>
