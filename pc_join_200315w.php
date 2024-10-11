<?
$path="./";
include_once "_head.php";
if($_COOKIE['mem_code']) {
	$sql="select * from Gn_Member where mem_code='{$_COOKIE['mem_code']}'";
	$result=mysqli_query($self_con,$sql);
	$info=mysqli_fetch_array($result);
}

?>
<script>
    function chk_sms()   {
    if($('input[name=mobile_1]').val() == "" || $('input[name=mobile_2]').val() == "" || $('input[name=mobile_3]').val() == "") {
        alert('인증받으실 휴대폰번호를 입력해주세요.')
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
			    if(data.result == "success")
			        $('#check_rnum').val("Y");
			    else
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
</script>

<style>
.desc li {
    margin-bottom: 5px;
    font-size: 12px;
    line-height: 18px;
}    
.input-wrap a {
    float: right;
    width: 65px;
    display: block;
    margin-left: 5px;
    padding: 7px 5px;
    font-size: 11px;
    color: #fff;
    line-height: 14px;
    background-color: #ccc;
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
</style>


<div class="big_sub">
	<div class="big_1">
    	<div class="m_div">
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="join.php">회원가입</a>
            </div>
            <div class="right_sub_menu">&nbsp;</div>
            <p style="clear:both;"></p>
    	</div>
   </div> 
   <div class="m_div">
        <div><img src="images/sub_reg_03.jpg" /></div>  
        <div class="join">
        <form name="join_form" method="post">
            <input type="hidden" name="check_rnum" id="check_rnum" value="Y">

        <div class="common-wrap"><!-- 컨텐츠 영역 시작 -->
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="inner-wrap">
							                           
							<section class="input-field">                           
                            <h3 class="title">1. 아이엠과 셀링솔루션 통합 회원가입</h3>
                          
                          
                          <div class="utils clearfix">
									<span class="notice">회원가입과 동시에 셀링솔루션과 아이엠을 함께 사용합니다. <br>그래서 <font color="red">모든 정보가 필수정보로 되어 있으니</font> 빠짐없이 정확하게 입력해주세요.</span>
								<!--	<a href="#" class="button">아이엠 샘플보기</a> -->
							    	
									  <a href="http://kiam.kr/iam/?DOSEhGYXOC" target="_blank" class="button"><font color="black">아이엠 샘플보기</font></a>
                                             
								     
						</div>
        				
                        
        <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td>아이디</td>
        <td>
        <li><input type="text" name="id" itemname='아이디'  placeholder="4자 ~ 15자 입력"/></li>
        <li><input type="button" value="중복확인" onClick="id_check(join_form,'join_form')" /></li>
        <li id='id_html'></li>
        <input type="hidden" name="id_status" itemname='아이디중복확인' required /> &nbsp;&nbsp; ※ 아이디 중복확인을 클릭해주세요.  
		</td>
        </tr>
        <tr>
        <td>비밀번호</td>
        <td>
        <li><input type="password" name="pwd" itemname='비밀번호' required onkeyup="pwd_check('0')" onblur="pwd_check('0')" placeholder="6자 ~ 15자 입력" /> 
        </li> 
        <li class='pwd_html'></li>
 
          
        </td>
        </tr>
        <tr>
        <td>비번확인</td>
        <td>
        <li><input type="password" name="pwd_cfm" itemname='비밀번호확인' required onblur="pwd_cfm_check('0')"/></li>
        <li class='pwd_cfm_html'></li>
        <input type="hidden" name="pwd_status" required itemname='비밀번호확인' />
        </td>
        </tr>
        <tr>
        <td>폰번호</td>
        <td>
        <input type="text" name="mobile_1" required itemname='휴대폰' maxlength="4" style="width:70px;" /> -
        <input type="text" name="mobile_2" required itemname='휴대폰' maxlength="4" style="width:70px;" /> -
        <input type="text" name="mobile_3" style="width:70px;" required itemname='휴대폰' maxlength="4" />  &nbsp;&nbsp; ※ 입력한 폰번호는 수정되지 않습니다. 다시 한번 확인하세요.            
        </td>
        </tr>
        <tr>
        <td>인증번호</td>
        <td>
            <input type="text" name="rnum" id="rnum"  itemname='인증번호' maxlength="10" style="width:70px;" />
            <input type="button" value="인증번호받기" class="button" onclick="chk_sms()">
        </td>
        </tr>         
        <tr>
        <td>성명/성별</td>
        <td><input type="text" name="name" itemname='성명'required>
            남<input type="radio" name="mem_sex" value="m">
            여<input type="radio" name="mem_sex" value="f" >  &nbsp;&nbsp; ※ 아이엠(e-명함)이 자동생성되므로 정확하게 입력하세요.             
            </td>
        </tr>
 <tr>
        <td>생년월일</td>
        <td>
        <li><select name="birth_1" type='select-one' required itemname='생년'>
        <option value="">년</option>
        <?
        for($i=date("Y"); $i>1899; $i--)
        {
        ?>
        <option value="<?=$i?>"><?=$i?></option>
        <?
        }
        ?>
        </select></li>
        <li>
        <select name="birth_2" required type='select-one' itemname='월'>
        <option value="">월</option>
        <?
        for($i=1; $i<13; $i++)
        {
        $k=$i<10?"0".$i:$i;
        ?>
        <option value="<?=$k?>"><?=$k?></option>
        <?
        }
        ?>                    
        </select></li>
        <li>
        <select name="birth_3" required type='select-one' itemname='일'>
        <option value="">일</option>
        <?
        for($i=1; $i<32; $i++)
        {
        $k=$i<10?"0".$i:$i;
        ?>
        <option value="<?=$k?>"><?=$k?></option>
        <?
        }
        ?>                    
        </select></li>
        </td>
        </tr>        

        <tr>
        <td>소속/직책</td>
        <td><input type="text" name="zy" required itemname='직업' style="width:60%;"placeholder="직장(단체)명과 직책을 '/' 로 구분하여 입력"/></td>
        </tr>       
        <tr>
        <td>자택주소</td>
        <td><input type="text" name="add1" required itemname='주소' style="width:60%;" placeholder="지역통계를 위해 읍,면,동까지 입력"/></td>
        </tr>
        <tr>
        <td>이메일</td>
        <td>
        <input type="text" name="email_1" required itemname='이메일' style="width:120px;" /> @ <input type="text" name="email_2" id='email_2' itemname='이메일' required style="width:120px;" />
        <select name="email_3" itemname='이메일' onchange="inmail(this.value,'email_2')">
        <?
        foreach($email_arr as $key=>$v)
        {
        ?>
        <option value="<?=$key?>"><?=$v?></option>                            
        <?
        }
        ?>
        </select>                
        </td>
        </tr>           
        <tr>
        <td>추천인ID</td>
            <td>
                <input type="text" name="recommend_id" id="recommend_id" itemname='추천인ID' style="width:21%;" value="<?php echo $info['mem_id']?>" <?php if($info['mem_id']!="") echo  "readonly";?>/>
                <?php if($_COOKIE['mem_code'] == "") {?>
                <!--<input type="button" name="검색" value="검색" onclick="searchManager()">-->
                <input type="button" name="추천인 찾기" value="추천인 찾기" onclick="searchManagerInfo()">
                <?php }?>
                <label>※입력한 ID는 수정되지 않습니다.</label>
            </td>
        </tr>        
        <td>대리점ID</td>
            <td>
                <input type="text" name="recommend_branch" id="recommend_branch" itemname='추천인대리점ID' style="width:21%;" value="<?php echo $info['mem_id']?>" <?php if($info['mem_id']!="") echo  "readonly";?>/>
                <?php if($_COOKIE['mem_code'] == "") {?>
                <!--<input type="button" name="검색" value="검색" onclick="searchManager()">-->
                <input type="button" name="대리점 찾기" value="대리점 찾기" onclick="searchBranchManagerInfo()">
                <?php }?>
                <label>※해당정보가 없으면 패스하세요.</label>
            </td>
        </tr>       
                         
    
                 
       <tr>
       <td>
        
										<div class="attr-name">앱다운받기</div></td>
										<td><div class="attr-value">
											<div class="utils clearfix" >
											<p style="width:85%"> 셀링앱을 설치하면 콜백문자, 대량문자, 포털디비수집, 365일 자동메시지발송, 자동예약메시지기능, 아이엠기능 등을 사용할수 있으니 회원가입후에 폰에서 다운받아 설치하세요.</p>
									      <a href="https://bit.ly/2wg4v3g" target="_blank" class="button"><font color="black">셀링앱 다운받기</font></a>  
								        </div>	                                    	
                                        </div>							   
        <tr>
        <td>온리원그룹 소식받기</td>
        <td colspan="3"><label><input type="checkbox" name="is_message" <?=$member_1[is_message]=="Y"?"checked":"checked"?> />※ 아이엠, 셀링솔루션, 셀링대회, 제휴업체, 셀링교육, 마케팅지원과 온리원그룹 활동 및 사업소식을 전달합니다.</label></td>
        </td>
        </tr>
        
       
        </table>
        
        </form>
        
        
        <section class="agreement-field">
								<h3 class="title">2. 약관 동의하기</h3>
								<p class="notice">서비스 이용을 위한 이용약관, 개인정보 처리방침 등 필수항목에 동의해주세요.</p>
								<div class="agreement-wrap">
									<div class="agreement-item is-checkall">
										<div class="check-wrap">
											<input type="checkbox" class="check" id="checkAll" required itemname='약관동의' >
											<label for="checkAll"> <strong>모두동의</strong> </label>
										</div>
									</div>
									<div class="agreement-item">
										<div class="check-wrap">
											<input type="checkbox" class="check" id="checkPersonal" >
											<label for="checkPersonal">개인정보수집동의</label>
										</div>
										<a href="/m/privacy.php" target="_blank"><font color="black">내용보기</font></a>
									</div>
									<div class="agreement-item">
										<div class="check-wrap">
											<input type="checkbox" class="check" id="checkTerms" >
											<label for="checkTerms">회원이용약관</label>
										</div>
										<a href="/m/terms.php" target="_blank"><font color="black">내용보기</font></a>
									</div>
									<div class="agreement-item">
										<div class="check-wrap">
											<input type="checkbox" class="check" id="checkReceive" >
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
											<input type="checkbox" class="check" id="checkThirdparty" >
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
                            
                          
				 
        <div colspan="2" style="text-align:center;padding:30px;">회원가입 후 휴대폰에 온리원셀링앱을 설치하세요.<br /><br />
        
        <a href="./" ><img src="images/sub_reg_19.jpg" /></a>
        <a href="javascript:void(0)" onclick="join_check(join_form)"><img src="images/sub_reg_17.jpg" /></a>
        </div>
        				
        
        </div>
        </div>
        <!-- // 컨텐츠 영역 종료 -->
   </div> 
</div> 
<?
include_once "_foot.php";
?>
<script language="javascript">


function searchManager() {
        var winw_pop = window.open('searchManager.php', 'popup', 'width=624, height=744, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,scrolling=yes');
        if(winw_pop == null) {
            alert('팝업 차단을 해제 해주세요.');
        } else {
            winw_pop.focus();
        }
    
}
function searchManagerInfo() {
        var winw_pop = window.open('searchManagerInfo.php', 'popup', 'width=624, height=744, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,scrolling=yes');
        if(winw_pop == null) {
            alert('팝업 차단을 해제 해주세요.');
        } else {
            winw_pop.focus();
        }
    
}
function searchBranchManagerInfo() {
        var winw_pop = window.open('searchBranchManagerInfo.php', 'popup', 'width=624, height=744, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,scrolling=yes');
        if(winw_pop == null) {
            alert('팝업 차단을 해제 해주세요.');
        } else {
            winw_pop.focus();
        }
    
}
</script>