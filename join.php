<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<?
$path="./";
include_once "_head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if($_COOKIE['mem_code']) {
	$sql="select * from Gn_Member where mem_code='{$_COOKIE['mem_code']}' and site != ''";
	$result=mysqli_query($self_con,$sql);
	$info=mysqli_fetch_array($result);
}
$code = whois_ascc($whois_api_key, $_SERVER['REMOTE_ADDR']);
?>

<script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
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
    input {height: 25px;vertical-align: middle;border: 1px solid #CCC;font-family: "Arial" !important;font-weight: normal;font-size: 15px;line-height: normal;}
</style>

<script>
    function get_sms()   {
        if($('input[name=mobile_1]').val() == "" || $('input[name=mobile_2]').val() == "" || $('input[name=mobile_3]').val() == "") {
            alert('인증받으실 전화번호를 입력해주세요.')
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

        /*$.ajax({
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
        });*/
        var mode = "munja_send";
        var api = "7ueWJd5Iiw6syUwl";
        var phone = $('input[name=mobile_1]').val()+"-"+$('input[name=mobile_2]').val()+"-"+$('input[name=mobile_3]').val();
        $.ajax({
            type:"POST",
            url:"/ajax/phone_check.php",
            dataType:"json",
            data:{
                mode:mode,
                api_key:api,
                phone_number:phone
            },
            success:function(data){
                if(data.code == "1")
                    $('#check_rnum').val("Y");
                else
                     $('#check_rnum').val("");
                alert(data.result);
            },
            error: function(){
                alert('변경 실패');
            }
        });
    }
    function chk_sms()   {
        /*$.ajax({
            type:"POST",
            url:"/ajax/join.proc.php",
            cache: false,
            dataType:"json",
            data:{
                mode:"check_sms",
                rphone:$('input[name=mobile_1]').val()+"-"+$('input[name=mobile_2]').val()+"-"+$('input[name=mobile_3]').val(),
                rnum : $('#rnum').val()
            },
            success:function(data){
                if(data.result == "success") {
                    $('#check_rnum').val("Y");
                    $('#check_sms').html('<img src="/images/check.gif"> 인증되었습니다.</p>');
                } else {
                    $('#check_rnum').val("");
                    $('#check_sms').html('');
                }
                alert(data.msg);
            }
        })*/
        var mode = "munja_check";
        var api = "7ueWJd5Iiw6syUwl";
        var phone = $('input[name=mobile_1]').val()+"-"+$('input[name=mobile_2]').val()+"-"+$('input[name=mobile_3]').val();
        var num = $('#rnum').val();
        $.ajax({
            type:"POST",
            url:"/ajax/phone_check.php",
            dataType:"json",
            data:{
                mode:mode,
                api_key:api,
                check:num,
                phone_number:phone
            },
            success:function(data){
                if(data.code == "1") {
                    $('#check_rnum').val("Y");
                    $('#check_sms').html('<img src="/images/check.gif"> 인증되었습니다.</p>');
                } else {
                    $('#check_rnum').val("");
                    $('#check_sms').html('');
                }
                alert(data.result);
            },
            error: function(){
                alert('변경 실패');
            }
        });
    }
$(function(){
    $('#checkAll').on("change",function() {
        if($('#checkAll').prop("checked") == true) {
            $("#checkAll_title").prop("checked", true);
            $("#checkPersonal").prop("checked", true);
            $("#checkTerms").prop("checked", true);
            $("#checkReceive").prop("checked", true);
            $("#checkThirdparty").prop("checked", true);
        } else {
            $("#checkAll_title").prop("checked", false);
            $("#checkPersonal").prop("checked", false);
            $("#checkTerms").prop("checked", false);
            $("#checkReceive").prop("checked", false);
            $("#checkThirdparty").prop("checked", false);
        }
    });
    $('#checkAll_title').on("change",function() {
        if($('#checkAll_title').prop("checked") == true) {
            $("#checkAll").prop("checked", true);
            $("#checkPersonal").prop("checked", true);
            $("#checkTerms").prop("checked", true);
            $("#checkReceive").prop("checked", true);
            $("#checkThirdparty").prop("checked", true);
        } else {
            $("#checkAll").prop("checked", false);
            $("#checkPersonal").prop("checked", false);
            $("#checkTerms").prop("checked", false);
            $("#checkReceive").prop("checked", false);
            $("#checkThirdparty").prop("checked", false);
        }
    });
});
    function show_install_modal(){
        $("#install-modalwindow").modal("show");
    }
    function install_cancel(){
        $("#install-modalwindow").modal("hide");
        location.href='/ma.php';
    }
</script>
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
                <input type="hidden" name="check_rnum" id="check_rnum" value="">
                <?$site = explode(".",$HTTP_HOST);$site_name = $site[0];?>
                <input type="hidden" name="site" id="site" value="site">
                <input type="hidden" name="site_name" id="site_name" value='<?=$site_name?>'>
                <input type="hidden" name="code" id="code" value='<?=$code?>'>
                <div class="common-wrap"><!-- 컨텐츠 영역 시작 -->
                    <div class="container" style="max-width: 970px !important;">
                        <div class="row">
                            <div class="col-12">
                                <div class="inner-wrap">
                                    <section class="input-field">
                                        <h3 class="title">1. 아이엠과 셀링솔루션 통합 회원가입<span style="color:red">[필수]</span></h3>
                                        <div class="utils clearfix">
                                            <span class="notice">회원가입과 동시에 셀링솔루션과 아이엠을 함께 사용합니다. <br>그래서 <font color="red">모든 정보가 필수정보로 되어 있으니</font> 빠짐없이 정확하게 입력해주세요.</span>
                                              <a href="/?DOSEhGYXOC14769" target="_blank" class="button">아이엠샘플보기</a>
                                        </div>
                                        <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td>아이디</td>
                                                <td>
                                                    <li><input type="text" id="id" name="id" itemname='아이디'  placeholder="6-15자로 입력하세요." style="background-color:#c8edfc;"/></li>
                                                    <li><input type="button" value="중복확인" onClick="id_check(join_form,'join_form')" /></li>
                                                    <li id='id_html'style="font-weight:normal; font-size:13px;">  &nbsp;&nbsp; ※ 아이디 중복확인을 클릭해주세요.</li>
                                                    <input type="hidden" name="id_status" id="id_status" itemname='아이디중복확인' required />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>비밀번호</td>
                                                <td>
                                                    <li><input type="password" name="pwd" itemname='비밀번호' required onkeyup="pwd_check('0')" onblur="pwd_check('0')" placeholder="6~15자로 입력하세요." style="background-color:#c8edfc;"/>
                                                    </li>
                                                    <li class='pwd_html'></li>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>비번확인</td>
                                                <td>
                                                    <li><input type="password" name="pwd_cfm" itemname='비밀번호확인' required onblur="pwd_cfm_check('0')" style="background-color:#c8edfc;"/></li>
                                                    <li class='pwd_cfm_html'></li>
                                                    <input type="hidden" name="pwd_status" required itemname='비밀번호확인' />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>폰번호</td>
                                                <td>
                                                    <input type="text" name="mobile_1" required itemname='휴대폰' maxlength="3" style="width:70px;background-color:#c8edfc;" /> -
                                                    <input type="text" name="mobile_2" required itemname='휴대폰' maxlength="4" style="width:70px;background-color:#c8edfc;" /> -
                                                    <input type="text" name="mobile_3" required itemname='휴대폰' maxlength="4" style="width:70px;background-color:#c8edfc;"  />
                                                    <?php if($code == "KR") {?>
                                                        <input type="button" value="인증번호 받기" class="button" onclick="get_sms()">
                                                    <?}else{?>
                                                        <span style="margin-left:10px">EX) 82-10-8888-9999 국가번호-지역번호-전화번호</span>
                                                    <?}?>
                                                    <br>※ 입력한 휴대폰 번호는 수정되지 않습니다.
                                                </td>
                                            </tr>
                                            <?php if($code == "KR") {?>
                                            <tr>
                                                <td>인증번호</td>
                                                <td>
                                                    <input type="text" name="rnum" id="rnum"  itemname='인증번호' maxlength="10" style="width:120px;" />
                                                    <input type="button" value="인증번호 확인" class="button" onclick="chk_sms()">
                                                    <span id="check_sms"></span>
                                                </td>
                                            </tr>
                                            <?}?>
                                            <tr>
                                                <td>성명/성별</td>
                                                <td>
                                                    <input type="text" name="name" itemname='성명' required style="background-color:#c8edfc;"/>
                                                    남<input type="radio" name="mem_sex" value="m">
                                                    여<input type="radio" name="mem_sex" value="f" >  &nbsp;&nbsp; ※ 성별을 바르게 선택해야 아이엠과 솔루션 이용시 자동분류가 됩니다.
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>생년월일</td>
                                                <td>
                                                    <li>
                                                        <select name="birth_1" type='select-one' required itemname='생년' style="background-color:#c8edfc;">
                                                            <option value="">년</option>
                                                            <?
                                                            for($i=date("Y"); $i>1899; $i--)
                                                            {
                                                            ?>
                                                            <option value="<?=$i?>"><?=$i?></option>
                                                            <?
                                                            }
                                                            ?>
                                                        </select>
                                                    </li>
                                                    <li>
                                                        <select name="birth_2" required type='select-one' itemname='월' style="background-color:#c8edfc;">
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
                                                        </select>
                                                    </li>
                                                    <li>
                                                        <select name="birth_3" required type='select-one' itemname='일' style="background-color:#c8edfc;">
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
                                                        </select>
                                                    </li>
                                                </td>
                                            </tr>
                                            <!--tr>
                                                <td>소속/직책</td>
                                                <td><input type="text" name="zy" required itemname='직업' style="width:60%;background-color:#c8edfc;" placeholder="직장명(단체명)과 직책을 '/'으로 나누어 입력하세요"/></td>
                                            </tr>
                                            <tr>
                                                <td>자택주소</td>
                                                <td>
                                                <?
                                                    // 광역시도 목록
                                                    $province_list = array();
                                                    $query = "SELECT province FROM gn_cities group by province";
                                                    $res = mysqli_query($self_con,$query);
                                                    while($row = mysqli_fetch_array($res)) {
                                                        $province_list[] = $row['province'];
                                                    }
                                                ?>
                                                    <select itemname="주소" id = "value_region_province" style="background-color:#c8edfc;">
                                                        <option value="">-시/도-</option>
                                                        <?php foreach($province_list as $province) {?>
                                                            <option value="<?=$province?>"><?=$province?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <select id="value_region_city" style="background-color:#c8edfc;" itemname="주소">
                                                        <option value="">-군/구-</option>
                                                    </select>
                                                    <select id="value_region_town" style="background-color:#c8edfc;" itemname="주소">
                                                        <option value="">-읍/면/동-</option>
                                                    </select>
                                                    <input type="text" name="add1" required itemname='주소' style="width:60%;display: none" placeholder="통계정보이니 읍,면,동까지만 입력하세요" id="add1"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>이메일</td>
                                                <td>
                                                    <input type="text" name="email_1" required itemname='이메일' style="width:70px;background-color:#c8edfc;" /> @ <input type="text" name="email_2" id='email_2' itemname='이메일' required style="width:70px;background-color:#c8edfc;" />
                                                    <select name="email_3" itemname='이메일' onchange="inmail(this.value,'email_2')" style="background-color:#c8edfc;">
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
                                                    <input type="text" name="recommend_id" id="recommend_id" onblur="check_recommender()" itemname='추천인ID' style="width:21%;" value="<?php echo $info['mem_id']?>" <?php if($info['mem_id']!="") echo  "readonly";?>/>
                                                    <input type="hidden" id="is_exist_recommender" name="is_exist_recommender" >
                                                    <label>※추천인 아이디를 입력하되 추천자가 없을 경우 패스합니다. </label>
                                                    <input type="hidden" name="recommend_branch" id="recommend_branch" itemname='추천인대리점ID' style="width:21%;" value="<?php echo $info['mem_id']?>" <?php if($info['mem_id']!="") echo  "readonly";?>/>
                                                </td>
                                            </tr-->
                                            <?if($_REQUEST[recom_id]){?>
                                            <input type="hidden" id="recommend_id" name="recommend_id" value="<?=$_REQUEST[recom_id]?>">
                                            <input type="hidden" id="recommend_branch" name="recommend_branch" value="<?=$_REQUEST[recom_id]?>">
                                            <?}?>
                                            <tr>
                                                <td><div class="attr-name">앱다운받기</div></td>
                                                <td>
                                                   <div class="attr-value">
                                                        <div class="utils clearfix" >
                                                          <p style="width:85%"> 셀링앱을 설치하면 콜백문자, 대량문자, 포털디비수집, 365일 자동메시지발송, 자동예약메시지기능, 아이엠기능 등을 사용할수 있으니 회원가입후에 폰에서 다운받아 설치하세요.</p>
                                                          <!--a href="https://bit.ly/2wg4v3g" target="_blank" class="button">셀링앱다운받기</a-->
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>온리원그룹 소식받기</td>
                                                <td colspan="3"><label><input type="checkbox" name="is_message" <?=$member_1[is_message]=="Y"?"checked":"checked"?> />※ 아이엠, 셀링솔루션, 셀링대회, 제휴업체, 셀링교육, 마케팅지원과 온리원그룹 활동 및 사업소식을 전달합니다.</label></td>
                                                </td>
                                            </tr>
                                        </table>
                                        <section class="agreement-field">
                                            <h3 class="title" id = "agreement-field">
                                                2. 약관 동의하기<span style="color:red">[필수]</span>
                                                <input type="checkbox" class="check" id="checkAll_title" >
                                                <label for="checkAll_title">모두 동의</label>
                                            </h3>
                                            <p class="notice">서비스 이용을 위한 이용약관, 개인정보 처리방침 등 필수, 선택 등의 항목에 동의해주세요.</p>
                                            <div class="agreement-wrap" style="display: none" id = "agreement-wrap">
                                                <div class="agreement-item is-checkall">
                                                    <div class="check-wrap">
                                                        <input type="checkbox" class="check" id="checkAll" required itemname='약관동의' >
                                                        <label for="checkAll"> <strong>모두동의에 클릭하면 전체를 클릭합니다</strong> </label>
                                                    </div>
                                                </div>
                                                <div class="agreement-item">
                                                    <div class="check-wrap">
                                                        <input type="checkbox" class="check checkAgree" id="checkPersonal" >
                                                        <label for="checkPersonal">개인정보수집동의</label>
                                                    </div>
                                                    <a href="/m/privacy.php" target="_blank">전문보기</a>
                                                </div>
                                                <div class="agreement-item">
                                                    <div class="check-wrap">
                                                        <input type="checkbox" class="check checkAgree" id="checkTerms" >
                                                        <label for="checkTerms">회원이용약관</label>
                                                    </div>
                                                    <a href="/m/terms.php" target="_blank">전문보기</a>
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
                                        <div colspan="2" style="text-align:center;padding:30px;">회원가입 후 휴대폰에 문자앱을 설치하세요.<br /><br />
                                            <a href="./" ><img src="images/sub_reg_19.jpg" /></a>
                                            <a href="javascript:void(0)" onclick="join_check(join_form)"><img src="images/sub_reg_17.jpg" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="big_h_f">
                            <?php
                            if($sub_domain == true) {?>
                                <div class="midle_div foot_1">
                                    <a href="terms.php">이용약관</a>
                                    <a href="privacy.php">개인정보정책</a>
                                </div>
                            <?php }else{?>

                                <div class="midle_div foot_1">
                                    <a href="https://oog.kiam.kr/pages/page_3216.php" target="_blank">회사소개</a>
                                    <a href="cliente_list.php?status=1">공지사항</a>
                                    <a href="https://oog.kiam.kr/pages/page_3214.php" target="_blank">FAQ</a>
                                    <a href="http://pf.kakao.com/_jVafC/chat" target="_blank">카카오상담</a>
                                    <a href="cliente_list.php?status=2">1:1상담</a>
                                    <a href="terms.php">이용약관</a>
                                    <a href="privacy.php">개인정보정책</a>
                                </div>
                            <? } ?>
                        </div>
                        <div id='ajax_div'></div>
                        <?
                        //include_once "_foot.php";
                        ?>
                </div>
            </form>
        </div>
   </div>
</div>
<div id="install-modalwindow" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;width : 80%;max-width:600px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header" style="border:none;background-color: rgb(130,199,54)">
                <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 20px;text-align: center">
                    앱 설치하기 안내
                </div>
            </div>
            <div class="modal-body">
                <div>
                    <div class="a1">
                        ※ 앱을 설치하면 콜백문자, 대량문자, 포털디비수집, 365일 자동메시지발동, 자동예약메시지기능, IAM기능 등 통합적인 모든 기능을 사용할 수 있습니다.<br><br>
                        ※ 현재 앱설치는 안드로이드폰만 가능, IAM은 아이폰에서 [홈화면추가]로 이용 가능합니다.
                    </div>
                </div>
            </div>
            <div class="modal-footer"  style="border:none;text-align: center">
                <div >
                    <a href="https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms" class="btn login_signup" style="width: 40%;background-color: #ff0066;color: #ffffff">IAM앱 설치하기</a>
                    <a href="javascript:install_cancel()" class="btn login_signup" style="width: 40%;background-color: #bbbbbb;color: #ffffff">나중에 하기</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="javascript">

$(function() {
    $('#agreement-field').on("click",function(){
        if($('#agreement-wrap').css("display") == "none")
            $('#agreement-wrap').show();
        else
            $('#agreement-wrap').hide();
    });
    $('.checkAgree').on("change",function(){
        $('.checkAgree').each(function(){
            if($(this).prop("checked") == false) {
                $('#checkAll').prop("checked", false);
                $('#checkAll_title').prop("checked", false);
            }
        });
    });
    $("#value_region_province").on('change', function(){
        var province = $(this).val();
        $("#value_region_city").html('<option value="">-시/군/구-</option>');
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('location.php', {'type':'cities', 'location':province}, function(res){
            if(res.status == '1') {
                var locations = res.locations;
                var html = '<option value="">시군구</option>';
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
});
function searchManagerInfo() {
        var winw_pop = window.open('searchManagerInfo.php', 'popup', 'width=624, height=744, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,scrolling=yes');
        if(winw_pop == null) {
            alert('팝업 차단을 해제 해 주세요.');
        } else {
            winw_pop.focus();
        }
    
}
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
</script>