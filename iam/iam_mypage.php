<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/m/include_once/iam.header.inc.php";
if($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/m/';</script>";
}
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$sql="select * from Gn_Member  where mem_id='".$_SESSION['iam_member_id']."'";
$sresul_num=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($sresul_num);	
$m_birth_arr = explode("-",$data['mem_birth']);

?>
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
                    <div style="float:right">
                        <div>
                            <?php include_once $_SERVER['DOCUMENT_ROOT']. "";?>
                        </div>
                    </div>
                    <h2 class="title">회원정보수정</h2>
                    <form name="join_form" id="join_form" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="join_modify" value="<?php echo $data['mem_code']?>">
                        <input type="hidden" name="id" value="<?php echo $data['mem_id']?>">
                        <input type="hidden" name="pwd" value="<?php echo $data['mem_id']?>">
                        <section class="input-field">
                            <h3 class="title">기본정보수정</h3>
                            <div class="utils clearfix"></div>
                            <div class="form-wrap">
                                <div class="attr-row is-account">
                                    <div class="attr-name">아이디(필수)</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <?php echo $data['mem_id'];?>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">회원구분</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <?if($member_iam['mem_leb'] == "50") {?>
                                                사업회원
                                            <?} else if($member_iam['mem_leb'] == "22"){?>
                                                일반회원
                                            <?} else if($member_iam['mem_leb'] == "21"){?>
                                                강사회원
                                            <?} else if($member_iam['mem_leb'] == "60"){?>
                                                홍보회원
                                            <?}
                                            if($member_iam['service_type'] == "0") {?>
                                                / 이용자
                                            <?}else if($member_iam['service_type'] == "1") {?>
                                                / 리셀러
                                            <?}else if($member_iam['service_type'] == "3") {?>
                                                / 분양자
                                            <?}?>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row is-account">
                                    <div class="attr-name">폰번호</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <?php echo $data['mem_phone'];?>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row is-phone">
                                    <div class="attr-name">성명/성별</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" class="input"  name="name"  id="name" value="<?php echo $data['mem_name'];?>">
                                            <div class="check-wrap">
                                                <input type="checkbox" class="check" id="mem_sex_m" name="mem_sex" value="m" <?php echo $data['mem_sex']=="m"?"checked":"";?>>
                                                <label for="mem_sex_m">남자</label>
                                            </div>
                                            <div class="check-wrap">
                                                <input type="checkbox" class="check"  id="mem_sex_f" name="mem_sex" value="f" <?php echo $data['mem_sex']=="f"?"checked":"";?>>
                                                <label for="mem_sex_f">여자</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">프로필사진</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="file" name="profile" id="profile" itemname='사진' /><?php if($member_iam['profile'] != "") { echo "<img src='{$member_1['profile']}'>"; }?>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">생년월일</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <select name="birth_1" class="lselect" required itemname='생년'>
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
                                            <select name="birth_2" required class="lselect" itemname='월'>
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
                                            <select name="birth_3" required class="lselect" itemname='일'>
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
                                            <input type="text" name="zy" id="zy" class="input" placeholder="회사명, 단체명, 직장명" value="<?php echo $data['zy'];?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">집주소</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" name="add1" id="add1" required  class="input" itemname="회사/단체, 직장 주소입력" placeholder="회사/단체, 직장 주소입력" value="<?php echo $data['mem_add1'];?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row is-mail">
                                    <div class="attr-name">이메일주소</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" style="width:135px" class="input mail-first" name="email_1" required itemname='이메일' id="email_1" value="<?php echo $email[0]; ?>">
                                            <span class="bridge">@</span>
                                            <input type="text" style="width:calc( 100% - 235px );"  class="input mail-second" name="email_2" id='email_2' itemname='이메일' required  value="<?php echo $email[1];?>">
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
                                    <div class="attr-name">추천링크</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <?
                                            $query = "select * from Gn_Iam_Name_Card where mem_id  = '{$member_iam['mem_id']}' ORDER BY idx";
                                            $result = mysqli_query($self_con,$query);
                                            $row = mysqli_fetch_array($result);
                                            $card_url = $row['card_short_url'];
                                            ?>
                                           <span id="sHtml" style="display:none"><?='http://'.$HTTP_HOST.'/iam/?'.$card_url?></span>
                                           <input type="button" name="" value="복사하기" onclick="copyHtml()">
                                        </div>
                                    </div>
                                </div>

                                <div class="attr-row">
                                    <div class="attr-name">추천인ID</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <?=$member_1['recommend_id']?>
                                        </div>
                                    </div>
                                </div>
                                                                
                                <div class="attr-row">
                                    <div class="attr-name">앱다운받기</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                        온리원셀링앱을 설치하면 콜백문자, 대량문자, 포털디비수집, 365일 자동메시지발송, 자동예약메시지기능, 아이엠기능 등을 사용할수 있습니다."
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">소식받기</div>
                                    <div class="attr-value">
                                        <div class="check-wrap">
                                            <input type="checkbox" class="check" id="is_message" name="is_message" <?=$data['is_message']=="Y"?"checked":""?>>
                                            <label for="is_message">온리원그룹의 소식 받기</label>
                                        </div>
                                        <div class="desc">
                                        <ul>
                                            <li>※ 아이엠, 셀링솔루션, 셀링대회, 제휴업체, 셀링교육, 마케팅지원과 온리원그룹 활동 및 사업소식을 전달합니다. </li>
                                        </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div class="button-wrap">
                            <a href="javascript:history.back(-1);" class="button is-grey">취소</a>
                            <a href="javascript:save_form()" class="button is-pink">정보수정</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="inner-wrap">
            <form name="web_pwd_form" action="" method="post">
                <section class="input-field">
                    <h3 class="title">비밀번호수정</h3>
                    <div class="utils clearfix"></div>
                    <div class="form-wrap">
                        <div class="attr-row">
                            <div class="attr-name">기존비밀번호</div>
                            <div class="attr-value">
                                <div class="input-wrap">
                                    <input type="password" class="input" name="old_pwd" id="old_pwd" itemname='기존 웹비밀번호' required />
                                </div>
                            </div>
                        </div>

                        <div class="attr-row">
                            <div class="attr-name">새비밀번호</div>
                            <div class="attr-value">
                                <div class="input-wrap">
                                    <input type="password" class="input" name="pwd" id="pwd" itemname='새 웹비밀번호' required onkeyup="pwd_check('1')" onblur="pwd_check('1')" />
                                </div>
                            </div>
                        </div>

                        <div class="attr-row">
                            <div class="attr-name">새비번확인</div>
                            <div class="attr-value">
                                <div class="input-wrap">
                                    <input type="password" class="input" name="pwd_cfm" id="pwd_cfm" itemname='새비밀번호확인' required onblur="pwd_cfm_check('1')"/>
                                    <input type="hidden" name="pwd_status" class="input" required itemname='새비밀번호확인' />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="button-wrap">
                    <a href="javascript:history.back(-1);" class="button is-grey">취소</a>
                    <a href="javascript:void(0)" onclick="pwd_change(web_pwd_form,'1')" class="button is-pink">비번수정</a>
                </div>
            </form>
        </div>
        <div class="inner-wrap">
            <form name="leave_form" action="" method="post">
                <section class="input-field">
                    <a href="javascript:void(0)" onclick="showInfoOut()"><h3 class="title">회원탈퇴(클릭)</h3></a>
                    <div class="utils clearfix"></div>
                    <div class="contents form-wrap" style="display:none">
                        <div class="attr-row is-account">
                            <div class="attr-name">비밀번호확인</div>
                            <div class="attr-value">
                                <div class="input-wrap">
                                    <input type="password" class="input" name="leave_pwd" id="leave_pwd">
                                </div>
                            </div>
                        </div>
                        <div class="attr-row is-account">
                            <div class="attr-name">탈퇴사유</div>
                            <div class="attr-value">
                                <div class="input-wrap">
                                    <input type="text" class="input" name="leave_liyou" id="leave_liyou">
                                </div>
                            </div>
                        </div>
                        <div class="desc-wrap">
                            <div class="inner">
                                <ul>
                                    <li>① 회원탈퇴시 계정에 저장된 주소록,  텍스트메시지, 이미지, 수발신 기록, 수신거부, 수신동의정보를 사용할수 없습니다.</li>
                                    <li>② 회원탈퇴시 수당지급정보와 이후 수당지급이  중단됩니다. </li>
                                    <li>③ 회원탈퇴시 e프로필정보와 e프로필수발신 정보 및 대화정보를 사용할수 없게 됩니다.</li>
                                    <li>④  회원탈퇴시 원퍼널솔루션의 수신동의자 정보와 랜딩정보, 예약메시지정보를  모두 사용할 수 없게 됩니다. </li>
                                    <li>⑤ 탈퇴 후 정보는 1개월 이내에 복구 가능하며 복구비용을 납부해야 합니다.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="button-wrap">
                            <a href="/m/" class="button is-grey">고민중입니다</a>
                            <a href="#" onclick="member_leave()" class="button is-pink">회원탈퇴합니다</a>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
</main><!-- // 컨텐츠 영역 끝 -->
<div id="ajax_div" style="display:none"></div>
<?php include_once $_SERVER['DOCUMENT_ROOT']."/m/include_once/menu.inc.php"; ?>
<?php include_once $_SERVER['DOCUMENT_ROOT']."/m/include_once/footer.inc.php"; ?>
<script language="javascript">
function showInfoOut() {
    $('.contents').show();
}    
$(function() {
    $('#mem_sex_m').on("click", function() {
        $('#mem_sex_f').prop("checked", false);
    });    
    $('#mem_sex_f').on("click", function() {
        $('#mem_sex_m').prop("checked", false);
    });
});
    function save_form() {
        
        if($('#name').val() == "") {
            alert('성명을 입력해 주세요.');
            return;                                    
        }       
        
        if($('#zy').val() == "") {
            alert('소속을 입력해 주세요.');
            return;                                                
        }                                         
        /*
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
        */       
        if($('#add1').val() == "") {
            alert('주소를 입력해 주세요.');
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
 
 
        $('#join_form').attr("action","/m/ajax/ajax.php");
        $('#join_form').submit();
        
    }
    function chk_sms()   {
        if($('input[name=mobile_1]').val() == "" || $('input[name=mobile_2]').val() == "" || $('input[name=mobile_3]').val() == "") {
            alert('인증받으실 전화번호를 입력해주세요.')
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
 
function id_check(frm,frm_str) {
	if(!frm.id.value)
	{
		frm.id.focus();
		return
	}
    var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
    if (!pattern.test(frm.id.value)) 
    {
        alert('영문과 숫자만 사용이 가능합니다.');
	 	frm.id_status.value=''
	    frm.id.value=''
	    frm.id.focus();
	    return;
    }

	 $.ajax({
		 type:"POST",
		 url:"/ajax/ajax.php",
		 data:{
			 	id_che:frm.id.value,
		 		id_che_form:frm_str
		 },
		 success:function(data){
		    $("#ajax_div").html(data);
		 }
	 });
}
function inmail(v,id)
{
    $("#"+id).val(v);
	//if(v)
	//  {
	//	$("#"+id).val("");
	//	$("#"+id).removeAttr("required");
	//	$("#"+id).hide();
	//  }
	//else
	//  {
	//	$("#"+id).val("");
	//	$("#"+id).show();
	//	$("#"+id).focus();		
	//  }
}
function searchManagerInfo() {
        var winw_pop = window.open('searchManagerInfo.php', 'popup', 'width=624, height=744, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,scrolling=yes');
        if(winw_pop == null) {
            alert('팝업 차단을 해제 해 주세요.');
        } else {
            winw_pop.focus();
        }
    
}


function change_message(form) {
	if(form.intro_message.value == "") {
		  alert('정보를 입력해주세요.');
		  form.intro_message.focus();
		  return false;
	}

	$.ajax({
		 type:"POST",
		 url:"ajax/ajax.php",
		 data:{
			 mode : "intro_message",
			 intro_message: form.intro_message.value
			 },
		 success:function(data){
		 	$("#ajax_div").html(data);
		 	alert('저장되었습니다.');
		 	}
		});
		return false;
}
function showInfo() {
    if($('#outLayer').css("display") == "none") {
        $('#outLayer').show();
    } else {
        $('#outLayer').hide();
    }
}

function copyHtml(){
    //oViewLink = $( "ViewLink" ).innerHTML;
    ////alert ( oViewLink.value );
    //window.clipboardData.setData("Text", oViewLink);
    //alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
    var trb = $.trim($('#sHtml').html());
    var IE=(document.all)?true:false;
    if (IE) {
        if(confirm("이 소스코드를 복사하시겠습니까?")) {
            window.clipboardData.setData("Text", trb);
        } 
    } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
    }

} 

//비밀번호 보안등급
function pwd_check(i)
{ 	
}
//비밀번호 재확인
function pwd_cfm_check(i)
{
	if($('#pwd_cfm').val() != $('#pwd').val())
	  {
		  	alert("두번 입력한 비밀번호가 틀립니다.");
			return;
	  }
	else
	  {
		
	  }	  
}

function pwd_change(frm,i)
{
	if($('#pwd_cfm').val() != $('#pwd').val())
	  {
		  	alert("두번 입력한 비밀번호가 틀립니다.");
			return;
	  }
	else
	  {
		
	  }	  
		
	if(confirm('변경하시겠습니까?'))
	{
		$.ajax({
			 type:"POST",
			 url:"/ajax/ajax_session.php",
			 data:{
					pwd_change_old_pwd:$('#old_pwd').val(),
					pwd_change_new_pwd:$('#pwd').val(),
					pwd_change_status:i
				  },
			 success:function(data){$("#ajax_div").html(data)}
			})		
	}		
}
</script>
