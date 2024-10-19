<?
$path="./";
include_once "_head.php";
$code = whois_ascc($whois_api_key, $_SERVER['REMOTE_ADDR']);
$link  = $_GET["link"];
if(!$_SESSION['one_member_id']){?>
    <script language="javascript">
    location.replace('/ma.php');
    </script>
    <?exit;
}
	$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."' and site != ''";
	$sresul_num=mysqli_query($self_con,$sql);
	$data=mysqli_fetch_array($sresul_num);	
	if($data['intro_message'] =="") {
		$data['intro_message'] = "안녕하세요\n
                                \n
                                귀하의 휴대폰으로\n
                                기부문자발송을 시작합니다.\n
                                \n
                                협조해주셔서 감사합니다^^";
	}
?>
<script>
function copyHtml(){
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
$(function(){
	$(".popbutton").click(function(){
		$('.ad_layer_info').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	});
    $("#profile").on("change",function(){
        $("#profile_img").prop("src",window.URL.createObjectURL($("#profile")[0].files[0]));
    });
});
</script>
<style>
.pop_right {
    position: relative;
    right: 2px;
    display: inline;
    margin-bottom: 6px;
    width: 5px;
}    
.gwc_chk{
    height:14px !important;
}
</style>
 
<div class="big_sub">
   <?php include "mypage_base_navi.php";?>
    <input type = "hidden" name = "link" id = "link" value="<?=$link?>">
    <div id='order_address' style='left:0px; top:0px; width:320px; height:600px;  z-index:1000; display:none; background-color:white;'></div>
   <div class="m_div" id="m_div_mp">
        <div class="join">
            <form name="edit_form" id="edit_form" method="post"  enctype="multipart/form-data">
                <input type="hidden" name="join_modify" value="<?php echo $member_1['mem_code'];?>" />
                <div class="a1">
                    <li style="float:left;">회원정보 수정</li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div>
                <table class="write_table" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px">
                    <colgroup>
                        <col width="100" />
                        <col width="300" />
                        <col width="100" />
                        <col width="300" />
                    </colgroup>
                    <tr>
                        <td>아이디</td>
                        <td colspan="3"><?=$member_1['mem_id']?></td>
                    </tr>
                    <tr>
                        <td>회원구분</td>
                        <td colspan="3">
                            <? 
                            $mem_leb = "";
                            if($member_1['mem_leb'] == "50") {
                                $mem_leb = "사업회원";
                            } else if($member_1['mem_leb'] == "22"){
                                $mem_leb="일반회원";
                            } else if($member_1['mem_leb'] == "21"){
                                $mem_leb="강사회원";
                            } else if($member_1['mem_leb'] == "60"){
                                $mem_leb="홍보회원";
                            } 
                            if($member_1['service_type'] == "0") {
                                $mem_leb .= " / FREE";
                            } else if($member_1['service_type'] == "1") {
                                $mem_leb .= " / 이용자";
                            } else if($member_1['service_type'] == "2") {
                                $mem_leb .= " / 리셀러";
                            } else if($member_1['service_type'] == "3") {
                                $mem_leb .= " / 분양자";
                            }
                            if($member_1['gwc_state']){
                                if($member_1['gwc_leb'] == "4"){
                                    $mem_leb .= " / 굿웰스-일반회원";
                                }else if($member_1['gwc_leb'] == "1"){
                                    $mem_leb .= " / 굿웰스-굿슈머";
                                }else if($member_1['gwc_leb'] == "2"){
                                    $mem_leb .= " / 굿웰스-공급사";
                                }else if($member_1['gwc_leb'] == "3"){
                                    $mem_leb .= " / 굿웰스-센터";
                                }
                            }
                            $spec_arr = explode(",",$member_1['special_type']);
                            foreach($spec_arr as $spec_leb){
                                if($spec_leb == 1)
                                    $mem_leb .= " / 판매자";
                                else if($spec_leb == 2)
                                    $mem_leb .= " / 전문가";
                                else if($spec_leb == 3)
                                    $mem_leb .= " / 구인자";
                                else if($spec_leb == 4)
                                    $mem_leb .= " / 구직자";
                                else if($spec_leb == 5)
                                    $mem_leb .= " / 리포터";
                                else if($spec_leb == 6)
                                    $mem_leb .= " / 아티스트";
                            }
                            echo $mem_leb; 
                            ?>
                        </td>
                    </tr>

                    <tr>
                        <td>휴대폰번호</td>
                        <td  colspan="3"><?=$member_1['mem_phone']?></td>
                    </tr>
                    <tr>
                        <td>일반폰번호</td>
                        <td  colspan="3"><?=$member_1['mem_phone1']?></td>
                    </tr>
                    <tr>
                        <td>성명</td>
                        <td  colspan="3"><input type="text" name="name" itemname='성명' value="<?=$member_1['mem_name']?>" required readonly/></td>
                    </tr>
                    <tr>
                        <td>프로필사진</td>
                        <td colspan="3">
                            <input type="file" name="profile" id="profile" itemname='사진' />
                            <?
                            if($member_1['profile'] != ""){
                                if(strstr($member_1['profile'], "kiam")) {
                                    $member_1['profile'] = str_replace("https://kiam.kr", "", $member_1['profile']);
                                    $member_1['profile'] = str_replace("https://www.kiam.kr", "", $member_1['profile']);
                                    $image_link = $cdn_ssl.$member_1['profile'];
                                } else if(!strstr($member_1['profile'], "http") && $member_1['profile']) {
                                    $image_link = $cdn_ssl.$member_1['profile'];
                                }else{
                                    $image_link = $member_1['profile'];
                                }
                                $image_link = cross_image($image_link);
                            }else{
                                $image_link = "/iam/img/profile_img.png";
                            }?>
                            <img style='width:60%' src='<?=$image_link?>' id = "profile_img">
                        </td>
                    </tr>
                    <tr>
                        <td>생일/성별</td>
                        <td colspan="3">
                            <li>
                                <select name="birth_1" type='select-one' itemname='생년'>
                                    <option value="">년</option>
                                    <?for($i=date("Y"); $i>1899; $i--)
                                    {
                                        $selected=$m_birth_arr[0]==$i?"selected":"";?>
                                        <option value="<?=$i?>" <?=$selected?>><?=$i?></option>
                                    <?}?>
                                </select>
                            </li>
                            <li>
                                <select name="birth_2" type='select-one' itemname='월'>
                                    <option value="">월</option>
                                    <?
                                    for($i=1; $i<13; $i++)
                                    {
                                        $k=$i<10?"0".$i:$i;
                                        $selected=$m_birth_arr[1]==$k?"selected":"";
                                        ?>
                                        <option value="<?=$k?>" <?=$selected?>><?=$k?></option>
                                    <?}?>
                                </select>
                            </li>
                            <li>
                                <select name="birth_3" type='select-one' itemname='일'>
                                    <option value="">일</option>
                                    <?
                                    for($i=1; $i<32; $i++)
                                    {
                                        $k=$i<10?"0".$i:$i;
                                        $selected=$m_birth_arr[2]==$k?"selected":"";
                                        ?>
                                        <option value="<?=$k?>" <?=$selected?>><?=$k?></option>
                                    <?}?>
                                </select>
                            </li>

                            남<input type="radio" name="mem_sex" value="m" <? echo $member_1['mem_sex']=="m"?"checked":""?>>
                            여<input type="radio" name="mem_sex" value="f"  <? echo $member_1['mem_sex']=="f"?"checked":""?>>
                        </td>
                    </tr>
                    <tr>
                        <td>소속/직책</td>
                        <td colspan="3"><input type="text" name="zy" itemname='소속' style="width:20%;" value="<?=$member_1['zy']?>" /></td>
                    </tr>
                    <tr>
                        <td>자택주소</td>
                        <td colspan="3">
                            <input type="text" name="zip" id="zip" required  class="input" placeholder="우편번호"  value="<?php echo $member_1['mem_zip'];?>" style="width: 35%;margin-bottom: 5px;">
                            <button type="button" onclick="win_zip('edit_form', 'zip', 'add1', 'add2', 'add3', 'b_addr_jibeon');" class="btn_small grey" style="background: #888;color: white;padding: 3px;border-color: #888;">주소검색</button>
                            <input type="text" name="add1" id="add1" required  class="input" placeholder="도로명"  value="<?php echo $member_1['mem_add1'];?>" style="width: 100%">
                            <input type="text" name="add2" id="add2"  class="input" placeholder="집주소 상세정보를 입력하세요."  value="<?php echo $member_1['mem_add2'];?>" style="width: 100%;background-color: rgb(200, 237, 252);margin-top: 5px;">
                            <input type="text" name="add3" class="frm_input frm_address" readonly="" hidden>
                            <input type="hidden" name="b_addr_jibeon" value="R">
                      </td>
                    </tr>
                    <tr>
                        <td>이메일</td>
                        <td colspan="3">
                            <input type="text" name="email_1" itemname='이메일' style="width:70px;" value="<?=$m_email_arr[0]?>" /> @ <input type="text" name="email_2" id='email_2' itemname='이메일' style="width:70px;" value="<?=$m_email_arr[1]?>" />
                            <select name="email_3" itemname='이메일' onchange="inmail(this.value,'email_2')" style="background-color:#c8edfc;">
                            <?foreach($email_arr as $key=>$v){?>
                                <option value="<?=$key?>"><?=$v?></option>
                            <?}?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>API입력</td>
                        <td colspan="5"><input type="text" name="gpt_chat_api_key" id="gpt_chat_api_key" class="input" placeholder="알지(챗GPT)사용을 위한 본인의 API를 입력하세요." value="<?php echo $member_iam['gpt_chat_api_key'];?>" style="width: 305px;">
                                ※ API 받으러가기 : <button onclick="window.open('https://tinyurl.com/3hzsk9p7', '_blank')">Click here!!</button><button onclick='alert("1. 알지 GPT는 자신의 API 키로 이용할 수 있습니다.\n2. 혹시 사용하다 알지 GPT 작동이 안되면 API 키 이용한도 초과된 것이므로, 유료 API 키를 입력하거나, 다른 명의의 API키를 다시 발급받아 입력하세요.");' style="border-radius: 15px;margin-left: 10px;border: 1px solid;">?</button></td>
                    </tr>
                    <tr>
                        <td>추천링크</td>
                        <td colspan="3">
                            <input type="hidden" name="nick" itemname='닉네임' value="<?=$member_1['mem_name']?>" />
                            <span id="sHtml" style="display:none"><?='https://'.$HTTP_HOST.'/ma.php?mem_code='.$member_1['mem_code']?></span>
                            <input type="button" name="" value="복사하기" onclick="copyHtml()">
                        </td>
                    </tr>
                    <tr>
                        <td>추천아이디</td>
                        <td colspan="3"><?=$member_1['recommend_id']?></td>
                    </tr>
                    <? if($member_1['mem_leb'] == "50") {?>
                    <tr>
                        <td>통장내역</td>
                        <td  colspan="3">
                            <table style="width:100%;font-size: 12px" >
                                <tr>
                                    <td>은행명</td><td><input type="text" name="bank_name"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_name']?>" /></td>
                                </tr>
                                <tr>
                                    <td>계좌번호</td><td><input type="text" name="bank_account"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_account']?>" /></td>
                                </tr>
                                <tr>
                                    <td>이름</td><td><input type="text" name="bank_owner"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_owner']?>" /></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <?}else{?>
                        <input type="hidden" name="bank_name"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_name']?>" />
                        <input type="hidden" name="bank_account"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_account']?>" />
                        <input type="hidden" name="bank_owner"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_owner']?>" />
                    <?}?>

		            <tr>
                        <td>앱다운받기</td>
                        <td colspan="3">
                            <p style="width:80%; display: inline-block;">온리원셀링앱을 설치하면 콜백문자, 대량문자, 포털디비수집, 365일 자동메시지발송, 자동예약메시지기능, 아이엠기능 등을 사용할수 있습니다.</p>
                            <input type="button" name="" value="셀링앱다운받기" a href="https://bit.ly/2wg4v3g" target="_blank" style="float: right;" / >
                        </td>
                    </tr>
                    <tr>
                        <td>소식받기</td>
                        <td colspan="3"><label><input type="checkbox" name="is_message" <?=$member_1['is_message']=="Y"?"checked":""?> checked />※ 아이엠, 셀링솔루션, 셀링대회, 제휴업체, 셀링교육, 마케팅지원과 온리원그룹 활동 및 사업소식을 전달합니다.</label></td>
                    </tr>
                    <!-- <tr>
                        <td>굿웰스클럽<br>회원 신청</td>
                        <td colspan="3">
                            <div class="check-wrap">
                                굿웰스클럽은 모든 회원이 공급과 소비를 동시에 할수 있는 1인 플랫폼과 홍보와 판매를 할수 있는 자동 플랫폼을 제공하여 부의 균형을 해결하고자 합니다. [자세히보기 클릭]
                            </div>
                            <div class="desc">
                            <ul style="display:flex;margin-top:10px;flex-wrap:wrap">
                                [신청하기]
                                <input type="checkbox" class="gwc_chk" id="chk1" style="display:block;" <?=$member_1['gwc_req_leb'] == "1"?'checked':'';?>> 가입회원
                                <input type="checkbox" class="gwc_chk" id="chk2" style="display:block;" <?=$member_1['gwc_req_leb'] == "2"?'checked':'';?>> 소비회원
                                <input type="checkbox" class="gwc_chk" id="chk3" style="display:block;" <?=$member_1['gwc_req_leb'] == "3"?'checked':'';?>> 공급회원
                                <input type="checkbox" class="gwc_chk" id="chk4" style="display:block;" <?=$member_1['gwc_req_leb'] == "4"?'checked':'';?>> 판매회원
                                <input type="checkbox" class="gwc_chk" id="chk5" style="display:block;" <?=$member_1['gwc_req_leb'] == "5"?'checked':'';?>> 팀리더
                                <input type="checkbox" class="gwc_chk" id="chk6" style="display:block;" <?=$member_1['gwc_req_leb'] == "6"?'checked':'';?>> 그룹리더
                                <input type="checkbox" class="gwc_chk" id="chk7" style="display:block;" <?=$member_1['gwc_req_leb'] == "7"?'checked':'';?>> 지역리더
                                <input type="checkbox" class="gwc_chk" id="chk8" style="display:block;" <?=$member_1['gwc_req_leb'] == "8"?'checked':'';?>> 국가리더
                                <input type="checkbox" class="gwc_chk" id="chk9" style="display:block;" <?=$member_1['gwc_req_leb'] == "9"?'checked':'';?>> 국제리더
                            </ul>
                            </div>
                        </td>
                        <input type="hidden" name="gwc_leb" id="gwc_leb" value="<?=$member_1['gwc_req_leb']?>">
                    </tr> -->
                    <tr>
                        <td colspan="4" style="text-align:center;padding:30px;">
                        <a href="javascript:void(0)" onclick="join_check(edit_form,'<?=$member_1['mem_code']?>')"><img src="images/sub_mypage_07.jpg" /></a>
                        </td>
                    </tr>
                 </table>
            </form>

            <form name="web_pwd_form" action="" method="post">
                <div class="a1">
                    <li style="float:left;">비밀번호수정</li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div>
                <table class="write_table" width="100%" cellspacing="0" cellpadding="0" style="font-size: 12px;border:none">
                    <tr>
                        <td>기존비밀번호</td>
                        <td>
                            <li><input type="password" name="old_pwd" itemname='기존비밀번호' required /></li>
                        </td>
                    </tr>
                    <tr>
                        <td>새비밀번호</td>
                        <td>
                            <li><input type="password" name="pwd" itemname='새비밀번호' required onkeyup="pwd_check('0')" onblur="pwd_check('0')" /></li>
                            <li class='pwd_html'></li>
                        </td>
                    </tr>
                    <tr>
                        <td>새비번확인</td>
                        <td>
                            <li><input type="password" name="pwd_cfm" itemname='새비번확인' required onblur="pwd_cfm_check('0')"/></li>
                            <li class='pwd_cfm_html'></li>
                            <input type="hidden" name="pwd_status" required itemname='새비번확인' />
                        </td>
                    </tr>
                        <td colspan="2" style="text-align:center;padding:30px;">
                            <a href="javascript:void(0)" onclick="pwd_change(web_pwd_form,'0')"><img src="images/btn_web_change password.gif" /></a>
                        </td>
                    </tr>
                </table>
            </form>
            <form name="leave_form" action="" method="post">
                <div class="a1">
                    <li style="float:left;"><a onclick="showInfo()">회원탈퇴(클릭)</a></li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div>
                <div id="outLayer" style="display:none">
                    <div style="margin-bottom:5px">회원탈퇴를 신청하시면 즉시 해제되며 다시 로그인 할수 없습니다.회원탈퇴를 신청하시려면 아래 작성후 회원탈퇴를 클릭해주세요.</div>
                    <div style="margin-bottom:5px; color:red;">결제상품이 있으시면 반드시 사이트 상단 마이페이지 >> 결제정보에서 해지신청을 해주세요!!</div></br>
                    <div class="desc-wrap" style="text-align: left;">
                        <div class="inner">
                            <ul>[탈퇴시 삭제 사항]
                                <li style="text-align:left">① 회원탈퇴시 아이디, 이름, 폰번호, 저장된 주소록, 텍스트메시지, 이미지, 수발신 기록, 수신거부, 수신동의정보가 삭제됩니다.</li>
                                <l style="text-align:left"i>② 회원탈퇴시 수당지급정보와 이후 수당지급이 중단됩니다. </li>
                                <li style="text-align:left">③ 회원탈퇴시 e프로필정보와 e프로필수발신 정보 및 대화정보가 삭제됩니다.</li>
                                <li style="text-align:left">④ 회원탈퇴시 원스텝솔루션의 수신동의자 정보와 랜딩정보, 예약메시지정보가 모두 삭제됩니다. </li>
                            </ul></br>
                            <ul>[탈퇴 세부 안내]
                                <li style="text-align:left">① 폰에 설치된 '아이엠'앱을 삭제합니다.</li>
                                <li style="text-align:left">② 탈퇴신청 후 1개월까지는 고객 정보가 보관됩니다. </li>
                                <li style="text-align:left">③ 탈퇴 후 정보는 1개월 이내에 복구 가능하며 복구비용을 납부해야 합니다.</li>
                                <li style="text-align:left">④ 탈퇴후 1개월 이내 복구 요청시 이메일로 복구요청을 해주세요. (개발자 계정 : 1pagebook@naver.com) </li>
                            </ul>
                        </div>
                    </div>
                    <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 12px">
                        <tr>
                            <td>아이디</td>
                            <td><input type="id" name="id" required itemname='아이디' /></td>
                        </tr>
                        <tr>
                            <td>비밀번호</td>
                            <td><input type="password" name="leave_pwd" required itemname='비밀번호' /></td>
                        </tr>
                            <td>탈퇴사유</td>
                            <td><input type="text" name="leave_liyou" required itemname='탈퇴사유' style="width:90%;" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center;padding:30px;">
                                <a href="javascript:void(0)" onclick="member_leave(leave_form)"><img src="images/sub_mypage_17.jpg" /></a>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
   </div>
</div><!--end big_sub-->
    <?
    include_once "_foot.php";
    ?>

<script>
    function showInfo() {
        if($('#outLayer').css("display") == "none") {
            $('#outLayer').show();
        } else {
            $('#outLayer').hide();
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
$("document").ready(function(){
    $("#value_region_province").on('change', function(){
        var province = $(this).val();
        $("#value_region_city").html('<option value="">-시/군/구-</option>');
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('location.php', {'type':'cities', 'location':province}, function(res){
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
        $.post('location.php', {'type':'towns', 'location':city}, function(res){
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

    $('.gwc_chk').on('click', function(){
        alert("<?=str_replace("\n", '\n', $gwc_req_alarm)?>");
        var state = $(this).prop('checked');
        var id = $(this).attr('id');
        var order = id.substring(3,4);
        if(id == "chk4"){
            $.ajax({
                type:"POST",
                url:"/ajax/get_mem_address.php",
                data:{mode:'reseller_state', mem_id:'<?=$_SESSION['one_member_id']?>'},
                dataType:'json',
                success:function(data){
                    if(data != "1"){
                        $('.gwc_chk').prop("checked", false);
                        alert("본 회원은 리셀러 이상 등급이어야 신청이 가능합니다");
                        return;
                    }
                }
            });
        }
        if(order > 4){
            $.ajax({
                type:"POST",
                url:"/ajax/get_mem_address.php",
                data:{mode:'cur_gwcleb_state', mem_id:'<?=$_SESSION['one_member_id']?>'},
                dataType:'json',
                success:function(data){
                    if(data != "1"){
                        $('.gwc_chk').prop("checked", false);
                        alert("본 회원은 공급회원 이상 등급이어야 신청이 가능합니다");
                        location.reload();
                    }
                }
            });
        }
        $('.gwc_chk').prop("checked", false);
        $("#gwc_leb").val(order);
        $(this).prop('checked', true);
    })
})
.ajaxStop(function() {
    $("#ajax-loading").delay(10).hide(1);
});
//회원가입체크
function join_check(frm,modify)
{
	if(!wrestSubmit(frm))
		return  false;
	var id_str="";
	var app_pwd="";
	var web_pwd="";
	var phone_str="";
	if(document.getElementsByName('pwd')[0])
	app_pwd=document.getElementsByName('pwd')[0].value;
	if(document.getElementsByName('pwd')[1])
	web_pwd=document.getElementsByName('pwd')[1].value;	
	if(frm.id)	
	id_str=frm.id.value;	
	var msg=modify?"수정하시겠습니까?":"등록하시겠습니까?";	
    var form = $('#edit_form')[0];
    var formData = new FormData(form);
    formData.append("profile", $("#profile")[0].files[0])
    console.log(formData);
	
	if(confirm(msg))
	{
		$.ajax({
			 type:"POST",
			 url:"/ajax/ajax.member.php",
             processData: false,
             contentType: false,
			 data:formData,
			 success:function(data){
                 $("#ajax_div").html(data);
                 if($("#link").val() != ""){
                    if($("#link").val() == "pay_point"){
                	history.go(-1);
                    }
                    else{
                	 location.href = $("#link").val() + ".php";
            	    }
                 }
             }
		});
	}
}

function monthly_remove(no) {
    if(confirm('정기결제 해지신청하시겠습니까?')) {
		$.ajax({
			 type:"POST",
			 url:"ajax/ajax_add.php",
			 data:{
				 mode:"monthly",
				 no:no
				 },
			 success:function(data){alert('신청되었습니다.');location.reload();}
        });
    }
}

// 5자리 우편번호 도로명 우편번호 창
function win_zip(frm_name, frm_zip, frm_addr1, frm_addr2, frm_addr3, frm_jibeon)
{
    var url = "https://<?=$HTTP_HOST?>/iam/zip.php?frm_name="+frm_name+"&frm_zip="+frm_zip+"&frm_addr1="+frm_addr1+"&frm_addr2="+frm_addr2+"&frm_addr3="+frm_addr3+"&frm_jibeon="+frm_jibeon+"&selling_mp=Y";
    // win_open(url, "winZip", "483", "600", "yes");
    $("#order_address").html("<iframe id='ALLAT_MOBILE_FRAME' name='ALLAT_MOBILE_FRAME' src='"+url+"' frameborder=0 width=100% height=100% scrolling=no></iframe>");
    document.getElementById("order_address").style.width="1000px";
    document.getElementById("order_address").style.height="600px";
    document.getElementById("order_address").style.left = 0 + "px";
    document.getElementById("order_address").style.top = 0 + "px";
    document.getElementById("order_address").style.display = "block";
    document.getElementById("m_div_mp").style.display = "none";
    $('body,html').animate({
        scrollTop: 0 ,
        }, 100
    );
}
</script>