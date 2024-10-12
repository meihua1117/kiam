<?php
include "inc/header.inc.php";
?>
<main id="register" class="common-wrap" style="margin-top: 86px"><!-- 컨텐츠 영역 시작 -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="inner-wrap">
                    <h3 class="title">회원가입하기</h3>
                    <form name="join_form" id="join_form" method="post" enctype="multipart/form-data">
                        <?$site = explode(".",$HTTP_HOST);$site_name = $site[0];?>
                        <input type="hidden" name="site" id="site" value="site_iam">
                        <input type="hidden" name="check_rnum" id="check_rnum" value="">
                        <input type="hidden" name="test" id="test" value="<?php echo $_GET['test'];?>">
                        <input type="hidden" name="site_name" id="site_name" value='<?=$site_name?>'>
                        <input type="hidden" name="code" value='<?=$code?>'>
                        <!--04-07-->
                        <input type="hidden" name="contents_count" id="contents_count" value='1'>
                        <!---->
                        <section class="input-field">
                            <?if($domainData[service_type] == 0) {?>
                                <h3 class="title">1. 회원가입 유형 확인*</h3>
                                <input type="radio" name="service_type" value="0" class="check" checked>무료회원
                                <div class="utils clearfix">
                                    <span class="notice">
                                        1.본 플랫폼 제공자는 플랫폼 개발 연구소와 협의 하에 회원들에게 베이직상품을 지원합니다.<br>
                                        2.본 플랫폼의 1번 카드가 공유되며,홍보용 유용콘텐츠나 알림 수신이 동의 처리 됩니다.
                                    </span>
                                </div>
                            <?} elseif($domainData[service_type] == 1) {?>
                                <h3 class="title">1. 회원가입 유형 확인*</h3>
                                <input type="radio" name="service_type" value="4" class="check" checked>유료회원
                                <div class="utils clearfix">
                                    <span class="notice">
                                        1.본 플랫폼 회원은 가입시 다른 아이엠 보기,내 홈피에 저장하기가 가능합니다.<br>
                                        2.내 아이엠을 만들 때는 정기결제를 진행해야 합니다.
                                    </span>
                                </div>
                            <?} elseif($domainData[service_type] == 2) {?>
                                <h3 class="title">1. 회원가입 유형 확인*</h3>
                                <input type="radio" name="service_type" value="5" class="check" checked>단체회원
                                <div class="utils clearfix">
                                    <span class="notice">
                                        1.본 플랫폼 제공자는 플랫폼 개발 연구소와 협의 하에 회원들에게 베이직상품을 지원합니다.<br>
                                        2.본 플랫폼의 1번 카드가 공유되며,홍보용 유용콘텐츠나 알림 수신이 동의 처리 됩니다.
                                    </span>
                                </div>
                            <?}?>
                        </section>
                        <section class="input-field">
                            <h3 class="title">2. 아이엠과 온리원셀링 통합회원가입*</h3>
                            <div class="form-wrap">
                                <div class="attr-row is-account">
                                    <div class="attr-name">아이디</div>
                                    <div class="attr-value">
                                        <div>
                                            <div class="input-wrap" style="display: flex;justify-content: space-between;">
                                                <input type="text" class="input" name="id" id="id" placeholder="4자 ~ 15자 입력">
                                                <a href="javascript:id_check(join_form,'join_form')"><font color="white">중복확인</font></a>
                                            </div>
                                        </div>
                                        <p id='id_html' style="font-weight:normal; font-size:13px;"></p>
                                        <input type="hidden" name="id_status" id="id_status" itemname='아이디중복확인' required />
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">비밀번호</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="password" class="input"  name="pwd" id="pwd_join" placeholder="6자 ~ 15자 입력" style="width:calc(100% - 65px)">
                                        </div>
                                        <div class="input-wrap" style="margin-top : 10px">
                                            <input type="password" class="input" name="pwd_cfm"  id="pwd_cfm_join" required placeholder="비밀번호 확인" style="width:calc(100% - 65px)">
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row is-phone">
                                    <div class="attr-name">폰번호</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <div class="input-wrap" style="display: flex;justify-content: space-between;">
                                                <input type="number" class="input" name="mobile" id="mobile_1" required>
                                                <?if($code == "KR") {?>
                                                <a href="javascript:get_sms()">인증번호받기</a>
                                                <?}else{?>
                                                <span style="margin-left:10px;color:#555555">EX) 82-10-8888-9999 국가번호-지역번호-전화번호</span>
                                                <?}?>
                                            </div>
                                        </div>
                                        <div class="desc">
                                        <ul>
                                            <li style="color:#555555">※ 입력한 폰번호는 수정되지 않습니다. 다시 한번 확인하세요.</li>
                                        </ul>
                                        </div>
                                    </div>
                                </div>
                                <? if($code == "KR") {?>
                                <div class="attr-row is-phone">
                                    <div class="attr-name">인증번호</div>
                                    <div class="attr-value">
                                        <div class="input-wrap" style="display: flex;justify-content: space-between;">
                                            <input type="text" class="input" name="rnum" id="rnum" required>
                                            <a href="javascript:chk_sms()">인증번호확인</a>
                                            <span id="check_sms"></span>
                                        </div>
                                    </div>
                                </div>
                                <? }?>
                                <div class="attr-row is-phone">
                                    <div class="attr-name">성명</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" class="input"  name="name"  id="name" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row is-phone">
                                    <div class="attr-name">성별</div>
                                    <div class="attr-value" style="display: flex;">
                                        <div class="check-wrap">
                                            <input type="checkbox" class="check" id="mem_sex_m" name="mem_sex" value="m">
                                            <label for="mem_sex_m">남</label>
                                        </div>
                                        <div class="check-wrap">
                                            <input type="checkbox" class="check"  id="mem_sex_f" name="mem_sex" value="f">
                                            <label for="mem_sex_f">여</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">출생년도</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <select name="birth_1" required itemname='생년'>
                                                <option value="">년</option>
                                                <?
                                                for($i=date("Y"); $i>1899; $i--)
                                                {
                                                    $selected=$iam_birth_arr[0]==$i?"selected":"";
                                                ?>
                                                <option value="<?=$i?>" <?=$selected?>><?=$i?></option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                            <br><span style="color:#555">※출생년도를 정확히 입력해야 출생년도에 따른 맞춤정보를 받을 수 있습니다.</span>
                                            <!--
                                            <select name="birth_2" required class="select"itemname='월'>
                                                <option value="">월</option>
                                                <?
                                                for($i=1; $i<13; $i++)
                                                {
                                                    $k=$i<10?"0".$i:$i;
                                                    $selected=$iam_birth_arr[1]==$k?"selected":"";
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
                                                    $selected=$iam_birth_arr[2]==$k?"selected":"";
                                                ?>
                                                <option value="<?=$k?>" <?=$selected?>><?=$k?></option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                            -->
                                        </div>
                                    </div>
                                </div>
                                <!--
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
                                            $res = mysqli_query($self_con,$query);
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
                                -->
                                <div class="attr-row  is-phone" style="display:none">
                                    <div class="attr-name">추천인 ID</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text"  class="input" name="recommend_id" id="recommend_id" onblur="check_recommender()" itemname='추천인ID' value="<?=$_GET[recommend_id];?>"/>
                                            <input type="hidden" id="is_exist_recommender" name="is_exist_recommender">
                                        </div>
                                        <div class="desc">
                                            <ul>
                                                <li>※ 입력한 ID는 수정되지 않습니다. (없으면 패스)</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="attr-row" style="display:none">
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
                                            <input type="checkbox" class="check" id="is_message" name="is_message" checked value="Y">
                                            <label for="is_message">온리원그룹 소식받기</label>
                                            <br>※ 아이엠, 셀링솔루션, 셀링대회, 제휴업체, 셀링교육, 마케팅지원과 온리원그룹 활동 및 사업소식을 전달합니다.
                                        </div>
                                    </div>
                                </div>
                                <div class="agreement-wrap">
                                    <div class="agreement-item">
                                        <div class="check-wrap">
                                            <input type="checkbox" class="check" id="is_message1" name="is_message1" checked value="Y">
                                            <label for="is_message1">앱설치 이해하기</label>
                                            <br>
                                            ※앱을 설치하면 콜백문자, 대량문자, 포털디비수집, 365일 자동메시지발동, 자동예약메시지기능, IAM기능 등 통합적인 모든 기능을 사용할 수 있습니다.<br>
                                            ※현재 앱설치는 안드로이드폰만 가능, IAM은 아이폰에서 [홈화면추가]로 이용가능합니다.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="agreement-field">
                            <h3 class="title tabmenu1" style="display: flex;justify-content: space-between;">
                                3. 약관 동의하기*
                                <div class="agreement-item is-checkall">
                                    <div class="check-wrap">
                                        <input type="checkbox" class="check" id="checkAll_title_join" >
                                        <label for="checkAll_title_join" style="margin-right:20px">모두 동의</label>
                                    </div>
                                </div>
                            </h3>
                            <!--p class="notice">서비스 이용을 위한 이용약관, 개인정보 처리방침 등 필수항목에 동의해주세요.</p-->
                            <div class="agreement-wrap" style="display:none" id="form1">
                                <div class="agreement-item is-checkall">
                                    <div class="check-wrap">
                                        <input type="checkbox" class="check" id="checkAll_join" >
                                        <label for="checkAll_join">모두 동의</label>
                                    </div>
                                </div>
                                <div class="agreement-item">
                                    <div class="check-wrap">
                                        <input type="checkbox" class="check checkAgree" id="checkPersonal_join" >
                                        <label for="checkPersonal_join">개인정보수집동의</label>
                                    </div>
                                    <a href="privacy.php">내용보기</a>
                                </div>
                                <div class="agreement-item">
                                    <div class="check-wrap">
                                        <input type="checkbox" class="check checkAgree" id="checkTerms_join" >
                                        <label for="checkTerms_join">회원이용약관</label>
                                    </div>
                                    <a href="terms.php">내용보기</a>
                                </div>
                                <div class="agreement-item">
                                    <div class="check-wrap">
                                        <input type="checkbox" class="check checkAgree" id="checkReceive_join" >
                                        <label for="checkReceive_join">메시지수신동의</label>
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
                                        <input type="checkbox" class="check checkAgree" id="checkThirdparty_join" >
                                        <label for="checkThirdparty_join">개인정보 제3자 제공 동의</label>
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

                        <section class="input-field" style="margin-top:25px;">
                            <h3 class="title tabmenu2">4. IAM 카드정보 입력(선택)</h3>
                            <p class="notice" style="margin-left:20px;color:#555">입력하면 1번카드에 적용되어 생성됩니다.</p>
                            <div class="form-wrap"  id="iam_form" style="display:none">
                            <div>
                                <h4>1. 포토등록 <span class="popbutton1 pop_view" style="color:#000;padding:0px 4px;">?</span></h4>
                                <h5 style="color:#555;margin-top:10px;margin-bottom:10px">*파일과 웹주소 중 하나만 이미지 업로드 가능</h5>
                            </div>
                            <div class="attr-row">
                                <div class="attr-name">대표이미지1</div>
                                <div class="attr-value">
                                    <div class="input-wrap" style="display: flex;justify-content: space-evenly;background: #ededed;border: 1px solid black;">
                                        <div><input type="radio" name="main_type1" value="f" checked>파일</div>
                                        <div><input type="radio" name="main_type1" value="u" id = "main_type1">이미지주소</div>
                                    </div>
                                    <div class="input-wrap">
                                        <div style="text-align:center;margin-top:10px;border: 1px dashed #ddd;padding: 3px 0px;" id="uploadFileBox1">
                                            <input type="file" class="input" name="uploadFile1" id="uploadFile1" accept=".jpg,.jpeg,.png,.gif,.svc" style="display: none">
                                            <a onclick="$('#uploadFile1').click();">이미지 추가+</a>
                                        </div>
                                        <div style="margin-top:10px;padding:3px 0px">
                                            <input type="text" class="input"  style="display: none" name="main_img1_link" id="main_img1_link" placeholder="웹페이지주소" style="width:50%">
                                        </div>
                                        <div style="width:50%;margin:0px auto">
                                            <img  id = "main_upload_img1" style="width:100%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="attr-row">
                                <div class="attr-name">대표이미지2</div>
                                <div class="attr-value">
                                    <div class="input-wrap" style="display: flex;justify-content: space-evenly;background: #ededed;border: 1px solid black;">
                                        <div><input type="radio" name="main_type2" value="f" checked>파일</div>
                                        <div><input type="radio" name="main_type2" value="u" id = "main_type2">이미지주소</div>
                                    </div>
                                    <div class="input-wrap">
                                        <div style="text-align:center;margin-top:10px;border: 1px dashed #ddd;padding: 3px 0px;" id="uploadFileBox2">
                                            <input type="file" class="input" name="uploadFile2" id="uploadFile2" accept=".jpg,.jpeg,.png,.gif,.svc" style="display: none">
                                            <a onclick="$('#uploadFile2').click();">이미지 추가+</a>
                                        </div>
                                        <div style="margin-top:10px;padding:3px 0px">
                                            <input type="text" class="input"  style="display: none" name="main_img2_link" id="main_img2_link" placeholder="웹페이지주소" style="width:50%">
                                        </div>
                                        <div style="width:50%;margin:0px auto">
                                            <img  id = "main_upload_img2" style="width:100%">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="attr-row">
                                <div class="attr-name">대표이미지3</div>
                                <div class="attr-value">
                                    <div class="input-wrap" style="display: flex;justify-content: space-evenly;background: #ededed;border: 1px solid black;">
                                        <div><input type="radio" name="main_type3" value="f" checked>파일</div>
                                        <div><input type="radio" name="main_type3" value="u" id = "main_type1">이미지주소</div>
                                    </div>
                                    <div class="input-wrap">
                                        <div style="text-align:center;margin-top:10px;border: 1px dashed #ddd;padding: 3px 0px;" id="uploadFileBox3">
                                            <input type="file" class="input" name="uploadFile3" id="uploadFile3" accept=".jpg,.jpeg,.png,.gif,.svc" style="display: none">
                                            <a onclick="$('#uploadFile3').click();">이미지 추가+</a>
                                        </div>
                                        <div style="margin-top:10px;padding:3px 0px">
                                            <input type="text" class="input"  style="display: none" name="main_img3_link" id="main_img3_link" placeholder="웹페이지주소" style="width:50%">
                                        </div>
                                        <div style="width:50%;margin:0px auto">
                                            <img  id = "main_upload_img3" style="width:100%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="margin-bottom:10px">
                                <h4>2. 프로필 등록 <span class="popbutton2 pop_view" style="color:#000;padding:0px 4px;">?</span></h4>
                            </div>
                            <div class="attr-row">
                                <div class="attr-name">명함로고</div>
                                <div class="attr-value">
                                    <div class="input-wrap" style="display: flex;justify-content: space-evenly;background: #ededed;border: 1px solid black;">
                                        <div><input type="radio" name="main_type" value="f" checked>파일</div>
                                        <!--input type="radio" name="main_type" value="l" id = "main_type">웹주소-->
                                        <div><input type="radio" name="main_type" value="u" id = "main_type">이미지주소</div>
                                    </div>
                                    <div class="input-wrap" >
                                        <div style="text-align:center;margin-top:10px;border: 1px dashed #ddd;padding: 3px 0px;" id="uploadFileBox">
                                            <input type="file" class="input" name="uploadFile" id="uploadFile" accept=".jpg,.jpeg,.png,.gif,.svc" style="display: none">
                                            <a onclick="$('#uploadFile').click();">이미지 추가+</a>
                                        </div>
                                        <div style="margin-top:10px;padding: 3px 0px;">
                                            <input type="text" class="input" style="display: none" name="logo_link" id="logo_link" placeholder="웹페이지주소">
                                            <!--input type="button" class="button is-grey" style="display: none" name="btn_img_logo" id="btn_img_logo" onclick="javascript:getLogoImage();" value="가져오기"-->
                                        </div>
                                        <div style="width:50%;margin:0px auto">
                                            <img  id = "logo_img" style="width:100%">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="attr-row">
                                <div class="attr-name">카드제목</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <input type="text" id="card_title" name="card_title" class="input" maxlength="10">
                                    </div>
                                </div>
                            </div>
                            <div class="attr-row">
                                <div class="attr-name">프로필이름</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <input type="text" id="card_name" name="card_name" class="input">
                                    </div>
                                </div>
                            </div>
                            <div class="attr-row">
                                <div class="attr-name">소속/직책</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <input type="text" class="input" id="card_company" name="card_company" placeholder="소속/직책을 '/'로 나누어 입력하세요">
                                    </div>
                                </div>
                            </div>
                            <div class="attr-row">
                                <div class="attr-name">자기소개</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <input type="text" class="input" id="card_position" required name="card_position" placeholder="자신을 한문장으로 소개해봐요">
                                    </div>
                                </div>
                            </div>
                            <!--
                            <div class="attr-row is-phone">
                                <div class="attr-name">휴대폰번호</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <input type="tel" maxlength="3" id="mobile_1" name="mobile_1" class="input">
                                        <span class="bridge">-</span>
                                        <input type="tel" maxlength="4" id="mobile_2" name="mobile_2" class="input">
                                        <span class="bridge">-</span>
                                        <input type="tel" maxlength="4" id="mobile_3" name="mobile_3" class="input">
                                    </div>
                                </div>
                            </div>
                                <div class="attr-row is-phone">
                                    <div class="attr-name">인증번호</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" class="input" name="rnum" id="rnum" required>
                                            <input type="button" value="인증번호 받기" class="button" onclick="chk_sms()">
                                        </div>
                                    </div>
                                </div>
                                -->
                            <div class="attr-row is-mail">
                                <div class="attr-name">프로필이메일</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <input type="text" id="card_email" name="card_email" class="input">
                                    </div>
                                </div>
                            </div>
                            <div class="attr-row">
                                <div class="attr-name">프로필주소</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <?php
                                        // 광역시도 목록
                                        /*$province_list = array();
                                        $query = "SELECT province FROM gn_cities group by province";
                                        $res = mysqli_query($self_con,$query);
                                        while($row = mysqli_fetch_array($res)) {
                                            $province_list[] = $row['province'];
                                        }
                                        if($code == "KR") {?>
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
                                        <?php } else {*/?>
                                        <input type="text" name="card_addr" id="card_addr" required  class="input" placeholder="address etc">
                                        <?php //}?>
                                    </div>
                                </div>
                            </div>

                            <div class="attr-row">
                                <div class="attr-name">관심키워드</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <textarea style="height:60px" id="card_keyword" name="card_keyword"
                                                  class="input"
                                                  placeholder="아이엠에서 나를 검색할수 있는 단어 (30개 이내)로 입력하고, 입력시 [,]으로 구분하세요. (예시 : 강사,마케터,변호사,대안학교,노래방, 공부방 등"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="attr-row">
                                <div class="attr-name">사이트입력</div>
                                <input type="hidden" name="story_title4" id="story_title4" value="온라인정보">
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <div class="input-row" style="display: flex;justify-content: space-around;">
                                            <input type="checkbox" id="radio_01" name="radio_01"
                                                   class="radio">
                                            <input type="text" class="input" name="story_online1_text"
                                                   id="story_online1_text" style="width:40%" placeholder="사이트명">
                                            <input type="text" class="input" name="story_online1"
                                                   id="story_online1" style="width:50%" placeholder="사이트주소">
                                        </div>
                                        <div class="input-row" style="display: flex;justify-content: space-around;margin-top:10px">
                                            <input type="checkbox" id="radio_02" name="radio_02"
                                                   class="radio">
                                            <input type="text" class="input" name="story_online2_text"
                                                   id="story_online2_text" style="width:40%" placeholder="사이트명">
                                            <input type="text" class="input" name="story_online2"
                                                   id="story_online2" style="width:50%" placeholder="사이트주소">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4>3. 마이스토리등록 <span class="popbutton3 pop_view" style="color:#000;padding:0px 4px;">?</span></h4>
                            </div>
                            <div class="attr-row" style="margin-top:10px">
                                <div class="attr-name" style="text-align:center">마이스토리<br>자기소개</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <input type="text" class="input" name="story_myinfo_title" id="story_myinfo_title" placeholder="자기소개 제목"/>
                                        <textarea type="text" style="height:60px;margin-top:10px" name="story_myinfo" id="story_myinfo" class="input"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="attr-row">
                                <div class="attr-name" style="text-align:center">마이스토리<br>기관소개</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <input type="text" class="input" name="story_company_title" id="story_company_title" placeholder="회사소개 제목"/>
                                        <textarea type="text" style="height:60px;margin-top:10px" name="story_company" id="story_company" class="input"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="attr-row">
                                <div class="attr-name" style="text-align:center">마이스토리<br>이력소개</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <input type="text" class="input" name="story_career_title" id="story_career_title" placeholder="이력소개 제목"/>
                                        <textarea type="text" style="height:60px;margin-top:10px" name="story_career" id="story_career" class="input"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4>4. 콘텐츠 등록 <span class="popbutton4 pop_view" style="color:#000;padding:0px 4px;">?</span></h4>
                            </div>
                            <div class="attr-row" style="margin-top:10px">
                                <div class="attr-name" style="text-align:center">콘텐츠1번<br>제목</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <input type="text" class="input" name="contents_title1" id="contents_title1" />
                                    </div>
                                </div>
                            </div>
                            <div class="attr-row">
                                <div class="attr-name" style="text-align:center">콘텐츠1번<br>이미지</div>
                                <div class="attr-value">
                                    <div class="input-wrap" style="text-align:center;border: 1px dashed #ddd;padding: 3px 0px;">
                                        <input type="file" name="contents_img1" id="contents_img1"  class="input" style="display:none">
                                        <a onclick="$('#contents_img1').click();">이미지 추가+</a>
                                    </div>
                                </div>
                            </div>
                            <div class="attr-row">
                                <div class="attr-name" style="text-align:center">콘텐츠1번<br>링크</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <input type="text" name="contents_url1" id="contents_url1" class="input"/>
                                    </div>
                                </div>
                            </div>
                            <div class="attr-row">
                                <div class="attr-name" style="text-align:center">컨텐츠1번<br>설명</div>
                                <div class="attr-value">
                                    <div class="input-wrap">
                                        <textarea type="text" style="height:60px" name="contents_desc1" id="contents_desc1" class="input"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="attr-row" id="add_btn1">
                                <div class="attr-value">
                                    <div class="input-wrap" style="text-align: right">
                                        <a href="javascript:add_contents_row();" id="add_btn" data-num = "1"><img src="img/star/icon-profile.png" style="right: 10px; top : 20px;" width="25" height="25" alt=""></a>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>
                    <div class="button-wrap">
                        <a href="javascript:history.back(-1);" class="button is-grey">취소</a>
                        <a href="javascript:save_form()" class="button is-success">회원가입</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main><!-- // 컨텐츠 영역 시작 -->
<div id="ajax_div" style="display:none"></div>
<style>.modal {display:none;}</style>
<script language="javascript">
$(function(){
    $(document).ajaxStop(function() {
        $("#ajax-loading").delay(10).hide(1);
    });
    $('input[name=main_type]').change(function(){
        $('#logo_img').attr('src', "");
        if($(this).val() == 'f'){
            $('#uploadFileBox').show();
            //$('#uploadFile').attr("style", "display:block");
            //$('#uploadFile').attr("style", "width:100%");
            $('#logo_link').attr("style", "display:none");
            $('#btn_img_logo').attr("style", "display:none");
            $('#logo_link').val("");
        }else if($(this).val() == 'u'){
            $("#uploadFile").val("");
            $('#uploadFileBox').hide();
            $('#logo_link').attr("style", "display:block");
            $('#logo_link').attr("style", "width:100%");
            $('#logo_link').attr("placeholder", "이미지주소");
            $('#btn_img_logo').attr("style", "display:none");
        }else{
            $("#uploadFile").val("");
            $('#uploadFile').attr("style", "display:none");
            $('#logo_link').attr("style", "display:block");
            $('#logo_link').attr("style", "width:100%");
            $('#logo_link').attr("placeholder", "웹페이지주소");
            $('#btn_img_logo').attr("style", "display:block");
        }
    });
    $('input[name=main_type1]').change(function(){
        $('#main_upload_img1').attr('src', "");
        if($(this).val() == 'f'){
            $('#uploadFileBox1').show();
            //$('#uploadFile1').attr("style", "display:block");
            //$('#uploadFile1').attr("style", "width:100%");
            $('#main_img1_link').attr("style", "display:none");
            $('#btn_img1').attr("style", "display:none");
            $('#main_img1_link').val("");
        }else if($(this).val() == 'u'){
            $("#uploadFile1").val("");
            $('#uploadFileBox1').hide();
            //$('#uploadFile1').attr("style", "display:none");
            $('#main_img1_link').attr("style", "display:block");
            $('#main_img1_link').attr("style", "width:100%");
            $('#main_img1_link').attr("placeholder", "이미지주소");
            $('#btn_img1').attr("style", "display:none");
        }else{
            $("#uploadFile1").val("");
            $('#uploadFile1').attr("style", "display:none");
            $('#main_img1_link').attr("style", "display:block");
            $('#main_img1_link').attr("style", "width:100%");
            $('#main_img1_link').attr("placeholder", "웹페이지주소");
            $('#btn_img1').attr("style", "display:block");
        }
    });
    $('input[name=main_type2]').change(function(){
        $('#main_upload_img2').attr('src', "");
        if($(this).val() == 'f'){
            $('#uploadFileBox2').show();
            //$('#uploadFile2').attr("style", "display:block");
            //$('#uploadFile2').attr("style", "width:100%");
            $('#main_img2_link').attr("style", "display:none");
            $('#btn_img2').attr("style", "display:none");
            $('#main_img2_link').val("");
        }else if($(this).val() == 'u'){
            $("#uploadFile2").val("");
            //$('#uploadFile2').attr("style", "display:none");
            $('#uploadFileBox2').hide();
            $('#main_img2_link').attr("style", "display:block");
            $('#main_img2_link').attr("style", "width:100%");
            $('#main_img2_link').attr("placeholder", "이미지주소");
            $('#btn_img2').attr("style", "display:none");
        }else{
            $("#uploadFile2").val("");
            $('#uploadFile2').attr("style", "display:none");
            $('#main_img2_link').attr("style", "display:block");
            $('#main_img2_link').attr("style", "width:100%");
            $('#main_img2_link').attr("placeholder", "웹페이지주소");
            $('#btn_img2').attr("style", "display:block");
        }
    });
    $('input[name=main_type3]').change(function(){
        $('#main_upload_img3').attr('src', "");
        if($(this).val() == 'f'){
            $('#uploadFileBox3').show();
            //$('#uploadFile3').attr("style", "display:block");
            //$('#uploadFile3').attr("style", "width:100%");
            $('#main_img3_link').attr("style", "display:none");
            $('#btn_img3').attr("style", "display:none");
            $('#main_img3_link').val("");
        }else if($(this).val() == 'u'){
            $("#uploadFile3").val("");
            //$('#uploadFile3').attr("style", "display:none");
            $('#uploadFileBox3').hide();
            $('#main_img3_link').attr("style", "display:block");
            $('#main_img3_link').attr("style", "width:100%");
            $('#main_img3_link').attr("placeholder", "이미지주소");
            $('#btn_img3').attr("style", "display:none");
        }else{
            $("#uploadFile3").val("");
            $('#uploadFile3').attr("style", "display:none");
            $('#main_img3_link').attr("style", "display:block");
            $('#main_img3_link').attr("style", "width:100%");
            $('#main_img3_link').attr("placeholder", "웹페이지주소");
            $('#btn_img3').attr("style", "display:block");
        }
    });
   $('.checkAgree').on("change",function(){
       $('.checkAgree').each(function(){
            if($(this).prop("checked") == false) {
                $('#checkAll_join').prop("checked", false);
                $('#checkAll_title_join').prop("checked", false);
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

function check_recommender(){
	if($('#recommend_id').val() != ''){
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
        if($('#id').val().length < 4 || $('#pwd_join').val().length >15) {
            alert('아이디는 4자~15자로 입력해 주세요.');
            return;            
        }                 
        if($('#pwd_join').val() == "") {
            alert('비밀번호를 입력해 주세요.');
            return;            
        }        
        if($('#pwd_join').val().length < 6 || $('#pwd_join').val().length >15) {
            alert('비밀번호는 6자~15자로 입력해 주세요.');
            return;            
        }         
        if($('#pwd_cfm_join').val() == "") {
            alert('비밀번호 확인를 입력해 주세요.');
            return;                        
        }                
        if($('#pwd_cfm_join').val() != $('#pwd_join').val()){
            alert('비밀번호 확인를 입력해 주세요.');
            return;                                    
        }                
        if($('#name').val() == "") {
            alert('성명/성별을 입력해 주세요.');
            return;                                    
        }       
        //if($('#zy').val() == "") {
        //    alert('소속/직책을 입력해 주세요.');
        //    return;                                                
        //}                                         
        if($('#mobile').val() == "") {
            alert('휴대폰 번호를 입력해 주세요.');
            return;                                    
        }
        <?php if($code == "KR") {?>
        if($('#check_rnum').val() == "") {
            alert('휴대폰 인증번호를 정확히 입력해 주세요.');
            return;      
        }        
        <?php }?>
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
		if($('#id').val() == $('#recommend_id').val()){
			alert('자신의 아이디는 추천에 입력되지 않습니다.');
			return;
		}
		if($('#is_exist_recommender').val() == "N" ){
			alert('추천인을 정확히 입력해 주세요.');
			return;			
		}
        if($("#checkPersonal_join").is(":checked") == false) {
            alert('약관동의하기를 체크해주세요.');
            return;
        }
        if($("#checkTerms_join").is(":checked") == false) {
            alert('회원이용약관에 동의해주세요.');
            return;
        }
        if($("#checkReceive_join").is(":checked") == false) {
            alert('메시지수신동의에 동의해주세요.');
            return;
        }
        if($("#checkThirdparty_join").is(":checked") == false) {
            alert('개인정보 제3자 제공 동의에 동의해주세요.');
            return;
        }
        /*var count = $('#add_btn').attr("data-num");
        for(var i = 1 ; i <= count;i++)
        {
            if($('#contents_title' + i).val() == '')//contents_img1,contents_url1,contents_desc1
            {
                alert("컨텐츠"+ i + "번 제목을 입력하세요.");
                $('#contents_title' + i).focus();
                return;
            }
            else if($('#contents_img' + i).val() == '')
            {
                alert("컨텐츠"+ i + "번 이미지를 첨부해 주세요.");
                $('#contents_img' + i).focus();
                return;
            }
        }*/
        if(confirm('등록하시겠습니까?')) {
            var formData = new FormData($('#join_form')[0]);
            if ($('#uploadFile')[0].files.length)
                formData.append('uploadFile', $('#uploadFile')[0].files[0]);
            if ($('#uploadFile1')[0].files.length)
                formData.append('uploadFile1', $('#uploadFile1')[0].files[0]);
            if ($('#uploadFile2')[0].files.length)
                formData.append('uploadFile2', $('#uploadFile2')[0].files[0]);
            if ($('#uploadFile3')[0].files.length)
                formData.append('uploadFile3', $('#uploadFile3')[0].files[0]);
            var count = $('#add_btn').attr("data-num");
            for(var i = 1 ; i <= count;i++) {
                if ($('#contents_img' + i)[0].files.length) {
                    var img = 'contents_img' + i;
                    formData.append(img, $('#contents_img' + i)[0].files[0]);
                }
            }
            $.ajax({
                type:"POST",
                url:"ajax/ajax.v1.php",
                dataType:"json",
                contentType: false,
                processData: false,
                data:formData,
                success:function(data){
                    if(data.result == "success") {
                        //$("#install-modalwindow").modal("show");
                        install_cancel();
                    }else if(data.result == "failed"){
                        alert(data.msg);
                        location.href = "/";
                    }else{
                        //$("#install-modalwindow").modal("show");
                        $("#result").html(data.result);
                    }
                },
                error: function(xhr,status,error){
                    console.log(xhr.responseText);
                }
            });
        }

    }
    function get_sms()   {
        if($('input[name=mobile]').val() == "" ) {
            alert('인증받으실 휴대폰번호를 입력해주세요.')
            return;
        }
        if(($('input[name=mobile]').val().substring(0,3)) != '010' && ($('input[name=mobile]').val().substring(0,3)) != '011' && ($('input[name=mobile]').val().substring(0,3)) != '016' && ($('input[name=mobile]').val().substring(0,3)) != '017' && ($('input[name=mobile]').val().substring(0,3)) != '018' && ($('input[name=mobile]').val().substring(0,3)) != '019') {
            alert('전화번호 첫번째 자리는 010, 011, 016, 017, 018, 019만 가능합니다.')
            return;                        
        }
        
        if(($('input[name=mobile]').val()).length <10 || ($('input[name=mobile]').val()).length > 12) {
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
				 rphone:$('input[name=mobile]').val(),
				 test:"<?php echo $_GET['test'];?>"
				 },
			 success:function(data){
			    if(data.result == "success") {
			        $('#check_rnum').val("Y");
			    } else 
			         $('#check_rnum').val("");
			         
			    alert(data.msg);
			    }
        })*/   
        var mode = "munja_send";
        var api = "7ueWJd5Iiw6syUwl";
        var phone = $('input[name=mobile]').val();
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
                if(data.code == "1") {
		    $('#check_rnum').val("Y");
		} else {
		    $('#check_rnum').val("");
		}	         
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
                rphone:$('input[name=mobile]').val(),
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
        var num = $('#rnum').val();
        var phone = $('input[name=mobile]').val();
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
        $('#checkAll_join').on("change",function() {
            if($('#checkAll_join').prop("checked") == true) {
                $("#checkAll_title_join").prop("checked", true);
                $("#checkPersonal_join").prop("checked", true);
                $("#checkTerms_join").prop("checked", true);
                $("#checkReceive_join").prop("checked", true);
                $("#checkThirdparty_join").prop("checked", true);
            } else {
                $("#checkAll_title_join").prop("checked", false);
                $("#checkPersonal_join").prop("checked", false);
                $("#checkTerms_join").prop("checked", false);
                $("#checkReceive_join").prop("checked", false);
                $("#checkThirdparty_join").prop("checked", false);
            }
        });
        $('#checkAll_title_join').on("change",function() {
            if($('#checkAll_title_join').prop("checked") == true) {
                $("#checkAll_join").prop("checked", true);
                $("#checkPersonal_join").prop("checked", true);
                $("#checkTerms_join").prop("checked", true);
                $("#checkReceive_join").prop("checked", true);
                $("#checkThirdparty_join").prop("checked", true);
            } else {
                $("#checkAll_join").prop("checked", false);
                $("#checkPersonal_join").prop("checked", false);
                $("#checkTerms_join").prop("checked", false);
                $("#checkReceive_join").prop("checked", false);
                $("#checkThirdparty_join").prop("checked", false);
            }
        })
    });
 
function id_check(frm,frm_str) {
	if(!frm.id.value){
        alert('아이디를 입력해 주세요.');
		frm.id.focus();
		return
	}
    if($('#id').val().length < 4 ) {
        alert('아이디는 4자~15자를 입력해 주세요.');
        $('#id').focus();
        return;
    }
    var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
    if (!pattern.test(frm.id.value)){
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
		 }
	 });
}
function inmail(v,id){
    $("#"+id).val(v);
}
function install_cancel(){
    $("#install-modalwindow").modal("hide");
    alert('회원가입되었습니다.');
    $.ajax({
        type:"POST",
        url:"ajax/self_card.php",
        data:{join:true},
        success:function(data){
            res = JSON.parse(data);
            var short_url = res.short_url;
            var mem_code = res.mem_code
            location.href='/?'+short_url+mem_code+"&tutorial=Y";
            // console.log(data);
        }
    });
}
</script>
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
                        <div class="a1" id = "result">

                        </div>
                    </div>
                </div>
                <div class="modal-footer"  style="border:none;text-align: center">
                    <div >
                        <a href="https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms" class="btn login_signup" style="width: 40%;background-color: #ff0066">IAM앱 설치하기</a>
                        <a href="javascript:install_cancel()" class="btn login_signup" style="width: 40%;background-color: #bbbbbb">나중에 하기</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="sns-modalwindow" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm" role="document" style="margin-top: 200px;max-width:300px;margin-left:auto;margin-right:auto;">
            <div class="modal-content">
                <div class="modal-header" style="border:none;">  
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ><img src = "img/icon_close_black.svg"></button>
                </div>
                <div class="modal-body">
                    <div class="center_text">
                        <div class="sns_item" onclick="daily_send_pop()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon1.png"></div><div class="sns_item_text" >데일리<br>문자발송</div></div>
                        <div class="sns_item" onclick="sns_sendSMS()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon2.png"></div><div class="sns_item_text" >문자<br>보내기</div></div>
                        <div class="sns_item" onclick="sns_shareKakaoTalk()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon3.png"></div><div class="sns_item_text" >카톡<br>공유</div></div>
                    </div>  
                    <div class="center_text">
           <!--        <div class="sns_item" onclick="sns_shareInsta()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon7.png"></div><div class="sns_item_text" >인스타<br>공유</div></div>
                        <div class="sns_item" onclick="sns_shareBand()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon6.png"></div><div class="sns_item_text" >밴드<br>공유</div></div> --> 
                        <div class="sns_item" onclick="sns_shareEmail()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon8.png"></div><div class="sns_item_text" >이메일<br>공유</div></div>
                        <div class="sns_item" onclick="sns_shareFaceBook()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon5.png"></div><div class="sns_item_text" >페북<br>공유</div></div>
                        <div class="sns_item" onclick="sns_copyContacts()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon4.png"></div><div class="sns_item_text" >주소<br>복사</div></div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <!--데일리 발송 팝업-->
    <div id="popup" class="daily_popup">
        <!-- 팝업 시작 -->
        <div class="popup-wrap" id="dailysend">
            <div class="text-wrap">
                <h3>내 아이엠을 내 폰 지인 모두에게 보내기!</h3><br><br>
                새명함이 나오면 지인들께 보내고<br>
                싶은데 방법이 마땅치 않지요?<br><br>

                ①데일리발송기능과 ②내 폰안의 무료 문자를<br>
                이용하여 내 폰주소록의 모든 지인에게<br>
                매일매일 자동으로 발송해보세요!<br>
                <br><br>
                <h3>내 아이엠을 보내는 절차!</h3><br><br>

                <a href="join.php">첫째 회원가입 먼저 해야지요(클릭)</a><br>
                <a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690">둘째 내 폰의 문자를 보내려면 앱을
                    설치해야지요!(클릭)</a><br>
                셋째 데일리발송을 시작해요!<br>
                ※ 아이엠을 보내는 기능은 무료이지만 일반 메시지를 보내는 것은 유료입니다.</h3>
            </div>
            <div class="button-wrap">
                <?if($_SESSION['iam_member_id']) {?>
                    <a href="#" id="closePopup" class="buttons is-cancel">다음에보내기</a>
                    <a  id="daily_popup_content" href="#" target="_blank" onclick="daily_send_pop_close()" class="buttons is-save">시작하기</a>
                <?} else {?>

                    <a href="#" id="closePopup" class="buttons is-cancel">다음에보내기</a>
                    <a href="login.php"
                       target="_blank" class="buttons is-save" onclick="daily_send_pop_close()">시작하기</a>
                <?}?>
            </div>
        </div>

        <div class="popup-overlay"></div>
    </div><!-- // 팝업 끝 -->
    <div class="ad_layer1">
        <div class="layer_in" style="height:500px;overflow-y:scroll">
            <span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>
            <img src="/iam/img/iam_poto.png">
        </div>
    </div>
    <div class="ad_layer2">
        <div class="layer_in" style="height:500px;overflow-y:scroll">
            <span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>
            <img src="/iam/img/iam_profile.png">
        </div>
    </div>
    <div class="ad_layer3" style="height:535px">
        <div class="layer_in" style="height:500px;overflow-y:scroll">
            <span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>
            <img src="/iam/img/iam_mystory.png">
        </div>
    </div>
    <div class="ad_layer4" style="width: 484px;height:535px">
        <div class="layer_in" style="height:500px;overflow-y:scroll">
            <span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>
            <img src="/iam/img/iam_contents.png">
        </div>
    </div>

<script>
	// #asd############ 스크립트부분
	var tid;
    function sns_sendSMS(){
        $("#sns-modalwindow").modal("hide");
        var iam_link = '<?=$G_card['card_name']?>님의 명함 <?=$G_card['card_company']?> <?=$G_card['card_position']?> <?=$G_card['card_phone']?> <?php echo $domainData['sub_domain'];?>/?<?=$G_card['card_short_url']?>';
        iam_sms(iam_link);
    }
    function sns_shareEmail(){
        $("#sns-modalwindow").modal("hide");
    }
    function sns_shareKakaoTalk(){
        $("#sns-modalwindow").modal("hide");
        shareKakaotalk();
    }
    function sns_shareInsta(){
        $("#sns-modalwindow").modal("hide");
    }
    function sns_shareBand(){
        $("#sns-modalwindow").modal("hide");
    }
    function sns_shareFaceBook(){
        $("#sns-modalwindow").modal("hide");
        var iam_link = '<?=$domainData['sub_domain'];?>/?<?=$G_card['card_short_url']?>';
        shareFaceBook('<?=$G_card['card_name']?>님의 명함 <?=$G_card['card_company']?> <?=$G_card['card_position']?>', iam_link);
    }
    function sns_copyContacts(){
        $("#sns-modalwindow").modal("hide");
        copy();
    }
    function show_share_user_list(){
        //$("#share_user_modal").modal("show");
        var win = window.open("/iam/_pop_public_profile_info.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }
    function add_contents_row(){
        var index = $('#add_btn').attr('data-num');
        index ++;
        console.log(index);
        var cont = "<div class='attr-row' name = 'del_btn' data-num = " + index + ">"+
                        "<div class='attr-value'>"+
                            "<div class='input-wrap' style='text-align: right'>"+
                                "<a href='javascript:del_contents_row("+index+");'>"+
                                    "<img src='img/star/icon-bin2.png' style='right: 10px; top : 20px;' width='25' height='25'></a>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='attr-row' name = 'contents_title' data-num = " + index + ">" +
                        "<div class='attr-name' style='text-align:center'>컨텐츠"+index+"번<br>제목</div>"+
                        "<div class='attr-value'>"+
                            "<div class='input-wrap'>"+
                                "<input type='text' class='input' name='contents_title"+index+"' id='contents_title"+index+"' />"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='attr-row' name = 'contents_img' data-num = " + index + ">"+
                        "<div class='attr-name' style='text-align:center'>컨텐츠"+index+"번<br>이미지</div>"+
                        "<div class='attr-value'>"+
                            "<div class='input-wrap' style='text-align:center;border: 1px dashed #ddd;padding: 3px 0px;'>"+
                                "<input type='file' name='contents_img"+index+"' id='contents_img" + index+"' class='input' style='display:none'>"+
                                "<a onclick=\"$('#contents_img" + index + "').click();\">이미지 추가+</a>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='attr-row' name = 'contents_link' data-num = " + index + ">"+
                        "<div class='attr-name' style='text-align:center'>컨텐츠"+index+"번<br>링크</div>"+
                        "<div class='attr-value'>"+
                            "<div class='input-wrap'>"+
                                "<input type='text' name='contents_url"+index+"' id='contents_url"+index+"' class='input'/>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='attr-row' name = 'contents_desc' data-num = " + index + ">"+
                        "<div class='attr-name' style='text-align:center'>컨텐츠"+index+"번<br>설명</div>"+
                        "<div class='attr-value'>"+
                            "<div class='input-wrap'>"+
                                "<textarea type='text' style='height:60px' name='contents_desc"+index+"' id='contents_desc"+index+"' class='input'></textarea>"+
                            "</div>"+
                        "</div>"+
                    "</div>";
        $('#iam_form').append(cont);
        //$html = $('#add_btn1').html();
        $html = "";
        $html += '<div class="attr-row" id="add_btn1">';
        $html += '    <div class="attr-value">';
        $html += '        <div class="input-wrap" style="text-align: right">';
        $html += '            <a href="javascript:add_contents_row();" id="add_btn" data-num = "1"><img src="img/star/icon-profile.png" style="right: 10px; top : 20px;" width="25" height="25" alt=""></a>';
        $html += '        </div>';
        $html += '    </div>';
        $html += '</div>';
        $('#add_btn1').remove();
        $('#iam_form').append($html);
        $('#add_btn').attr("data-num", index);
        //04-07
        $('#contents_count').val(index);
    }
    function del_contents_row(index) {
        $('#join_form').find('div[data-num='+index+']').remove();
        var addIndex =  $('#add_btn').attr('data-num');
        $('#add_btn').attr('data-num', (addIndex - 1));
        $('#join_form').find('div[name=del_btn]').each(function(){
            var curIndex = $(this).attr("data-num");
            if(curIndex > index)
            {
                $(this).find('a').prop('href',"javascript:del_contents_row("+(curIndex-1)+");");
                $(this).attr("data-num", (curIndex - 1));
            }
        });

        $('#join_form').find('div[name=contents_title]').each(function(){
            var curIndex = $(this).attr("data-num");
            if(curIndex > index)
            {
                $(this).attr("data-num", (curIndex - 1));
                $(this).find('.attr-name').html("컨텐츠"+(curIndex-1)+"번<br>제목");
                $(this).find('input').prop("name","contents_title"+(curIndex-1));
                $(this).find('input').prop("id","contents_title"+(curIndex-1));
            }
        });

        $('#join_form').find('div[name=contents_img]').each(function(){
            var curIndex = $(this).attr("data-num");
            if(curIndex > index)
            {
                $(this).attr("data-num", (curIndex - 1));
                $(this).find('.attr-name').html("컨텐츠"+(curIndex-1)+"번<br>이미지");
                $(this).find('input').prop("name","contents_img"+(curIndex-1));
                $(this).find('input').prop("id","contents_img"+(curIndex-1));
                $(this).find('a').attr("onClick","$('#contents_img"+(curIndex - 1)+"').click();");
            }
        });

        $('#join_form').find('div[name=contents_link]').each(function(){
            var curIndex = $(this).attr("data-num");
            if(curIndex > index)
            {
                $(this).attr("data-num", (curIndex - 1));
                $(this).find('.attr-name').html("컨텐츠"+(curIndex-1)+"번<br>링크");
                $(this).find('input').prop("name","contents_url"+(curIndex-1));
                $(this).find('input').prop("id","contents_url"+(curIndex-1));
            }
        });

        $('#join_form').find('div[name=contents_desc]').each(function(){
            var curIndex = $(this).attr("data-num");
            if(curIndex > index)
            {
                $(this).attr("data-num", (curIndex - 1));
                $(this).find('.attr-name').html("컨텐츠"+(curIndex-1)+"번<br>설명");
                $(this).find('textarea').prop("name","contents_desc"+(curIndex-1));
                $(this).find('textarea').prop("id","contents_desc"+(curIndex-1));
            }
        });
    }        
    $(document).on("keypress", ".contents_search_input", function(e) {
        if (e.which == 13) {
            var inputVal = $(this).val();
            iam_mystory('<?=$request_short_url?>&cur_win=<?=$cur_win?>&search_key=' + inputVal + '#bottom');
        }
    });
    function search_clicked() {
        var inputVal = $(".contents_search_input").val();
        iam_mystory('<?=$request_short_url?>&cur_win=<?=$cur_win?>&search_key=' + inputVal + '#bottom');
    }
    <? if($tab) { ?>
    $(".<?=$tab?>-tab").addClass("active");
    $(".<?=$tab?>-tab-content").addClass("in active");
    <? } else {?>
        $(".profile-tab").addClass("active");
        $(".profile-tab-content").addClass("in active");
    <? }?>
    function getLogoImage(){
        if($('#logo_link').val()){
            $('#btn_img_logo').attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "https://www.goodhow.com/crawler/scrape/getImage.php",
                dataType: "json",
                data: {
                    web_address: $('#logo_link').val()
                },
                success: function (result) {
                    $('#logo_link').val(result.link);
                    $('#logo_img').attr('src', result.link);
                    $('#btn_img_logo').attr("disabled", false);
                },
                error: function () {
                    $('#btn_img_logo').attr("disabled", false);
                    alert("웹주소를 크롤링할수 없습니다.");
                }
            });
        }
        else
            alert("로고 웹주소를 입력해주세요.");
    }
    function getMainImage(index){
        console.log(index);
        if($('#main_img'+index+'_link').val()){
            console.log($('#main_img'+index+'_link').val());
            $('#btn_img'+index).attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "https://www.goodhow.com/crawler/scrape/getImage.php",
                dataType: "json",
                data: {
                    web_address: $('#main_img'+index+'_link').val()
                },
                success: function (result) {
                    $('#main_img'+index+'_link').val(result.link);
                    $('#main_upload_img'+ index).attr('src', result.link);
                    $('#btn_img'+index).attr("disabled", false);
                },
                error: function () {
                    $('#btn_img'+index).attr("disabled", false);
                    alert("웹주소를 크롤링할수 없습니다.");
                }
            });
        }
        else
            alert("웹주소"+index+"를 입력해주세요.");
    }
    $("#main_img1_link").keyup(function () {
        $('#main_upload_img1').attr('src', $(this).val());
    });
    $("#uploadFile1").change(function () {
        var input = this;
        var url = $(this).val();
        if (input.files && input.files[0])
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#main_upload_img1').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
    $("#main_img2_link").keyup(function () {
        $('#main_upload_img2').attr('src', $(this).val());
    });
    $("#uploadFile2").change(function () {
        var input = this;
        var url = $(this).val();
        if (input.files && input.files[0])
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#main_upload_img2').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
    $("#main_img3_link").keyup(function () {
        $('#main_upload_img3').attr('src', $(this).val());
    });
    $("#uploadFile3").change(function () {
        var input = this;
        var url = $(this).val();
        if (input.files && input.files[0])
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#main_upload_img3').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
    $("#logo_link").keyup(function () {
        $('#logo_img').attr('src', $(this).val());
    });
    $("#uploadFile").change(function () {
        var input = this;
        var url = $(this).val();
        if (input.files && input.files[0])
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#logo_img').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
    $(document).ready(function() {
       <?php if($show_iam_like == "Y" && $_SESSION['iam_member_id'] != $card_owner) {?>
           $('.second-box').hide();
       <?php }?>
    });
</script>
    <script>
    $(function() {
        $('.tabmenu1').on("click",function(){
            if($('#form1').css("display") == "none")
                $('#form1').show();
            else 
                $('#form1').hide();
        });
        
        $('.tabmenu2').on("click",function(){
            if($('#iam_form').css("display") == "none")
                $('#iam_form').show();
            else 
                $('#iam_form').hide();
        });        
    });
    // 팝업위치 조정
    $('.utils a').on('click', function() {

        jQuery.fn.center = function() {
            this.css('position', 'absolute');
            this.css('width', '100%');
            this.css('top', Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window)
                .scrollTop()) + 'px');
            this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window)
                .scrollLeft()) + 'px');
            return this;
        }
    })
    //데일리 발송 팝업
    function daily_send_pop() {
        $("#sns-modalwindow").modal("hide");
        iam_count('iam_msms');
        console.log($(window).height() + "|" + $(this).outerHeight() + "|" + $(window).scrollTop());
        jQuery.fn.center = function() {
            this.css('position', 'absolute');
            this.css('width', '100%');
            this.css('top', Math.min(400, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + 'px');
            this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window)
                .scrollLeft()) + 'px');
            return this;
        }
        $(".daily_popup").center();

        $('.daily_popup').css('display', 'block');
        var iam_link = "daily_write_iam.php?msg=" + '<?=$G_card['card_name']?>' + "님의 명함입니다.  " +
            "<?=$G_card['card_company'].'<br>'.$G_card['card_position'].'<br>'.$G_card['card_phone'].'<br>'.$domainData['sub_domain'].'/?'.$G_card['card_short_url']?>" +
            " 모바일 명함을 새로 만들었습니다. 휴대폰에 저장부탁해요. 혹시 명함 만들면 저에게도 보내주시구요. 감사합니다. ";
        //location = iam_link;
        $("#daily_popup_content").prop("href",iam_link);
    }

    function daily_send_pop_close() {
        $('.daily_popup').css('display', 'none');
    }
    // 팝업 닫기 스크립트
    $('.popup-overlay, #closePopup').on('click', function() {
        $('.daily_popup').css('display', 'none');
        return false;
    });
    function shareFaceBook(desc, url) {
        var text = encodeURIComponent(desc);
        var linkUrl = encodeURIComponent(url);
        var title = '아이엠으로 나를 브랜딩하기';
        var description = desc;
        var imgUrl = '<?=$main_img1?>';

        if (!$('meta[property="og:title"').attr('content')) {
            $('head').append(format('<meta property="og:title" content="{0}" />', title));
        } else {
            $('meta[property="og:title"').attr('content', title);
        }
        if (!$('meta[property="og:description"').attr('content')) {
            $('head').append(format('<meta property="og:description" content="{0}" />', description));
        } else {
            $('meta[property="og:description"').attr('content', description);
        }
        if (!$('meta[property="og:image"').attr('content')) {
            $('head').append(format('<meta property="og:image" content="{0}" />', imgUrl));
        } else {
            $('meta[property="og:image"').attr('content', imgUrl);
        }

        iam_count('iam_facebook');

        window.open('http://www.facebook.com/sharer.php?u=' + linkUrl + '&t=' + text);
    }
    // 카카오톡
    try{
        Kakao.init("<?php echo $domainData['kakao_api_key']!=""?$domainData['kakao_api_key']:"c0550dad4e9fdb8a298f6a5ef39ebae6";?>"); // 사용할 앱의 JavaScript 키를 설정
        //Kakao.init("2e50869591823e28ed57afa55ff56b47");      // 사용할 앱의 JavaScript 키를 설정
    }catch(e){
        console.log("Kakao 로딩 failed : " + e);
    }
    function shareKakaotalk() {
        var iam_link = '<?=$domainData['sub_domain'];?>/?<?=$G_card['card_short_url']?>';
        iam_count('iam_kakao');
        try{
            Kakao.Link.sendDefault({
                objectType: "feed",
                content: {
                    title: "아이엠으로 나를 브랜딩하기", // 콘텐츠의 타이틀
                    description: "<?=$G_card['card_name']?>/<?=$G_card['card_company']?>/<?=$G_card['card_position']?>/<?=$G_card['card_phone']?>", // 콘텐츠 상세설명
                    imageUrl: "<?=$main_img1?>", // 썸네일 이미지
                    link: {
                        mobileWebUrl: iam_link, // 모바일 카카오톡에서 사용하는 웹 링크 URL
                        webUrl: iam_link // PC버전 카카오톡에서 사용하는 웹 링크 URL
                    }
                },
                buttons: [{
                    title: "우리 프렌즈해요!", // 버튼 제목
                    link: {
                        mobileWebUrl: iam_link, // 모바일 카카오톡에서 사용하는 웹 링크 URL
                        webUrl: iam_link // PC버전 카카오톡에서 사용하는 웹 링크 URL
                    }
                }]
            });
        }catch(e){
        alert("Kakao talk 과 연동할수 없습니다.");
        }
    }
    //텍스트 복사
    function copy() {
        iam_count('iam_share');
        var iam_link = '<?=$domainData['sub_domain'];?>/?<?=$G_card['card_short_url']?>';
        // 글을 쓸 수 있는 란을 만든다.
        var aux = document.createElement("input");
        // 지정된 요소의 값을 할당 한다.
        aux.setAttribute("value",
            "<?=$G_card['card_name']?>님의 명함 <?=$G_card['card_company']?> <?=$G_card['card_position']?> <?=$G_card['card_phone']?> "+iam_link
        );
        // bdy에 추가한다.
        document.body.appendChild(aux);
        // 지정된 내용을 강조한다.
        aux.select();
        // 텍스트를 카피 하는 변수를 생성
        document.execCommand("copy");
        // body 로 부터 다시 반환 한다.
        document.body.removeChild(aux);
        alert("복사에 성공 하였습니다.");
    }
    function iam_sms(url) {
        // location.href = '';
        iam_count('iam_sms');
        location.href = "sms:" + "<?echo (preg_match('/iPhone/',$_SERVER['HTTP_USER_AGENT']))?'&':'?';?>body=" + url;
    }
    function iam_count(str) {
        var member_id = '<?=$card_owner?>';
        var card_idx = '<?=$G_card['idx']?>';
        var formData = new FormData();
        formData.append('str', str);
        formData.append('mem_id', member_id);
        formData.append('card_idx', card_idx);
        $.ajax({
            type: "POST",
            url: "ajax/iam_count.proc.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {

            }
        })
    }
    function showSNSModal(kind){
        $("#sns-modalwindow").modal("show");
    }
</script>
<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css'/>

<script type="text/javascript" src="../jquery.lightbox_me.js"></script>
<script>
$(function(){
	$(".popbutton1").click(function(){
		$('.ad_layer1').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	});
	
	$(".popbutton2").click(function(){
		$('.ad_layer2').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	});
	
	$(".popbutton3").click(function(){
		$('.ad_layer3').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	});
	
	
	$(".popbutton4").click(function(){
		$('.ad_layer4').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	});			
});
</script>
