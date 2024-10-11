<? include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if($_COOKIE[recommender_code]) {
    $sql="select * from Gn_Member where mem_code='$_COOKIE[recommender_code]'";
    $result=mysqli_query($self_con,$sql);
    $info=mysqli_fetch_array($result);
    $recommmender = $info[mem_id];
}
else {
    if ($HTTP_HOST != "kiam.kr") {
        $sql = "select * from Gn_Iam_Service where sub_domain like '%http://" . $HTTP_HOST . "'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $recommmender = $row['mem_id'];
    } else {
        $recommmender = 'onlymain';
    }
}
if($member == 'on' && $_SESSION['iam_member_id']) {
    $sql="select mem_name, zy, mem_phone, mem_email, mem_add1 from Gn_Member where mem_id = '{$_SESSION['iam_member_id']}'";
    $result=mysqli_query($self_con,$sql);
    $row=mysqli_fetch_array($result);
    //$card_idx = $row[idx];
    $card_name = $row['mem_name'];
    $card_company = $row['zy'];
    // $card_position = $row[card_position];
    $card_phone = $row['mem_phone'];
    $card_email = $row['mem_email'];
    $card_addr = $row['mem_add1'];
    // $profile_logo = $row[profile_logo];
    $email_array = explode('@',$card_email);
}
$lang = $_COOKIE['lang']?$_COOKIE['lang']:"kr";
$sql = "select * from Gn_Iam_lang where menu='IAM_PROFILE'";
$result = mysqli_query($self_con,$sql);
while($row = mysqli_fetch_array($result)) {
    $MENU[$row[menu]][$row[pos]] = $row[$lang];
}
$country_code = whois_ascc($whois_api_key, $_SERVER['REMOTE_ADDR']);
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>통합 아이엠 만들기</title>
    <link rel="stylesheet" href="css/notokr.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/grid.min.css">
    <link rel="stylesheet" href="css/slick.min.css">
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script language="javascript" src="/js/rlatjd.js"></script>
    <link rel='stylesheet' href='../plugin/toastr/css/toastr.css' />
    <script src='../plugin/toastr/js/toastr.min.js'></script>
    <style>
input[type=button] {
    vertical-align: middle;
    border: 1px solid #CCC;
}        
    </style>
</head>

<body>
<div id="wrap" class="common-wrap">
    <main id="register" class="common-wrap">
        <!-- 컨텐츠 영역 시작 -->
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="inner-wrap">
                        <h2 class="title">통합 아이엠 만들기</h2>
                        <section class="input-field">
                            <div class="utils clearfix">
                                <span class="notice">※아래 항목에 해당 정보를 입력하면 회원가입과 함께 아이엠을 한번에 만들수 있습니다.</span>
                            </div>
                            <div class="form-wrap">
                                <form name="iam_form" id ="iam_form" method="post" enctype="multipart/form-data" onsubmit="javascript:return false;">
                                    <input type="hidden" name="check_rnum" id="check_rnum" value="">
                                    <input type="hidden" name="mode" id="mode" value="creat"/>
                                    <input type="hidden" name="memid" id="memid" value = <?=$_SESSION['iam_member_id']?>>
                                    <input type="hidden" name="site" id="site" value="site_iam">
                                    <?$site = explode(".",$HTTP_HOST);$site_name = $site[0];?>
                                    <input type="hidden" name="site_name" id="site_name" value='<?=$site_name?>'>
                                <?if(!$_SESSION['iam_member_id']) {?>
                                    <h3 class="title">아이엠 회원정보 입력</h3>
                                    <br>
                                    <div class="attr-row" >
                                        <div class="attr-name">회원 ID</div>
                                        <div class="attr-value">
                                            <div class="input-wrap" style="display: flex">
                                                <input type="text" class="input" id="id" name="id" itemname='아이디'  placeholder="6-15자로 입력하세요." style="width:40%">
                                                <input type="button"  class="button is-grey" value="중복확인"  onClick="id_check(iam_form,'iam_form')" style="width:20%">
                                                &nbsp; <p id='id_html'style="font-weight:normal; font-size:13px;">  &nbsp;&nbsp; ※ 아이디 중복확인을 클릭해주세요.</p>
                                            </div>
                                            <input type="hidden" name="id_status" id="id_status" itemname='아이디중복확인' required />
                                        </div>
                                    </div>
                                    <div class="attr-row">
                                        <div class="attr-name">비밀번호</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="password" class="input" name="passwd" id="passwd" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="attr-row">
                                        <div class="attr-name">생년월일</div>
                                        <div class="attr-value">
                                            <div class="input-wrap" style="display: flex">
                                                <select name="birth_1"  itemname='생년' style="height: 27px">
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
                                                &nbsp;
                                                <select name="birth_2" itemname='월' style="height: 27px">
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
                                                &nbsp;
                                                <select name="birth_3" itemname='일' style="height: 27px">
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
                                            </div>
                                        </div>
                                    </div>
                                    <div class="attr-row  is-phone">
                                        <div class="attr-name">추천인 ID</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text"  class="input" name="recommend_id" id="recommend_id" onblur="check_recommender()" itemname='추천인ID' value="<?echo $recommmender?>" <?if($recommmender!="") echo  "readonly";?>/>
                                                <input type="hidden" id="is_exist_recommender" name="is_exist_recommender">
                                            </div>
                                            <div class="desc">
                                                <ul>
                                                    <li>※ 입력한 ID는 수정되지 않습니다. (없으면 패스)</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                <?}?>
                                    <h3 class="title">아이엠 프로필정보 입력</h3>
                                    <div class="utils clearfix">
                                        <a href="?member=on" class="button"><?php echo $MENU['IAM_PROFILE']['TOP_BTN'];?></a>
                                    </div>
                                    <br>
                                    <div class="attr-row">
                                        <div class="attr-name">대표이미지1</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="radio" name="main_type1" value="f" checked>파일
                                                <input type="radio" name="main_type1" value="l" id = "main_type1">웹주소
                                                <input type="radio" name="main_type1" value="u" id = "main_type1">이미지주소
                                            </div>
                                            <div class="input-wrap">
                                                <input type="file" class="input" name="main_upload1" id="main_upload1" accept=".jpg,.jpeg,.png,.gif,.svc">
                                                <div style="display: flex;">
                                                    <input type="text" class="input" style="display: none" name="main_img1_link" id="main_img1_link" placeholder="웹페이지주소">
                                                    <input type="button" class="button is-grey" style="display: none" name="btn_img1" id="btn_img1" value="가져오기">
                                                </div>
                                                <img style="max-width:50%;" id = "main_img1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="attr-row">
                                        <div class="attr-name">대표이미지2</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="radio" name="main_type2" value="f" checked>파일
                                                <input type="radio" name="main_type2" value="l" id = "main_type2">웹주소
                                                <input type="radio" name="main_type2" value="u" id = "main_type2">이미지주소
                                            </div>
                                            <div class="input-wrap">
                                                <input type="file" class="input" name="main_upload2" id="main_upload2" accept=".jpg,.jpeg,.png,.gif,.svc">
                                                <div style="display: flex;">
                                                    <input type="text" class="input" style="display: none" name="main_img2_link" id="main_img2_link" placeholder="웹페이지주소">
                                                    <input type="button" class="button is-grey" style="display: none" name="btn_img2" id="btn_img2" value="가져오기">
                                                </div>
                                                <img style="max-width:50%;" id = "main_img2">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="attr-row">
                                        <div class="attr-name">대표이미지3</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="radio" name="main_type3" value="f" checked>파일
                                                <input type="radio" name="main_type3" value="l" id = "main_type3">웹주소
                                                <input type="radio" name="main_type3" value="u" id = "main_type3">이미지주소
                                            </div>
                                            <div class="input-wrap">
                                                <input type="file" class="input" name="main_upload3" id="main_upload3" accept=".jpg,.jpeg,.png,.gif,.svc">
                                                <div style="display: flex;">
                                                    <input type="text" class="input" style="display: none" name="main_img3_link" id="main_img3_link" placeholder="웹페이지주소">
                                                    <input type="button" class="button is-grey" style="display: none" name="btn_img3" id="btn_img3" value="가져오기">
                                                </div>
                                                <img style="max-width:50%;" id = "main_img3">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="attr-row">
                                        <div class="attr-name">명함로고</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="radio" name="main_type" value="f" checked>파일
                                                <input type="radio" name="main_type" value="l" id = "main_type">웹주소
                                                <input type="radio" name="main_type" value="u" id = "main_type">이미지주소
                                            </div>
                                            <div class="input-wrap">
                                                <input type="file" class="input" name="logo_file" id="logo_file" accept=".jpg,.jpeg,.png,.gif,.svc">
                                                <div style="display: flex;">
                                                    <input type="text" class="input" style="display: none" name="logo_link" id="logo_link" placeholder="웹페이지주소">
                                                    <input type="button" class="button is-grey" style="display: none" name="btn_img_logo" id="btn_img_logo" value="가져오기">
                                                </div>
                                                <img style="max-width:50%;" id = "logo_img">
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
                                                <input type="text" id="card_name" name="card_name" class="input" value="<?=$card_name?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="attr-row">
                                        <div class="attr-name">소속/직책</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text" class="input" id="card_company" name="card_company" placeholder="소속/직책을 '/'로 나누어 입력하세요" value="<?=$card_company?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="attr-row">
                                        <div class="attr-name">자기소개</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text" class="input" id="card_position" required name="card_position" placeholder="자신을 한문장으로 소개해봐요" value="<?=$card_position?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="attr-row is-phone">
                                        <div class="attr-name">휴대폰번호</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="tel" id="card_phone" name="card_phone" class="input"  style="width: 100%" value="<?=$card_phone?>">
                                            </div>
                                        </div>
                                    </div>
                                    <?if(!$_SESSION['iam_member_id']){?>
                                    <div class="attr-row is-phone">
                                        <div class="attr-name">인증번호</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text" class="input" name="rnum" id="rnum" required>
                                                <input type="button" value="인증번호 받기" class="button" onclick="chk_sms()">
                                            </div>
                                        </div>
                                    </div>
                                    <?}?>
                                    <div class="attr-row is-mail">
                                        <div class="attr-name">프로필이메일</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <!--input type="text" id="mem_email" name="mem_email" class="input"-->
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
                                        <div class="attr-name">프로필주소</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <?php
                                                if($country_code == "KR") {
                                                    // 광역시도 목록
                                                    $member_address = explode(" ", $card_addr);
                                                    $province_list = array();
                                                    $query = "SELECT province FROM gn_cities group by province";
                                                    $res = mysqli_query($self_con,$query);
                                                    while($row = mysqli_fetch_array($res)) {
                                                        $province_list[] = $row['province'];
                                                    }
                                                    $city_list = array();
                                                    if(isset($member_address[0])) {
                                                        $query = "SELECT city FROM gn_cities WHERE province = '{$member_address[0]}' group by city ";
                                                        $res = mysqli_query($self_con,$query);
                                                        while($row = mysqli_fetch_array($res)) {
                                                            $city_list[] = $row['city'];
                                                        }
                                                    }

                                                    $town_list = array();
                                                    if(isset($member_address[1])) {
                                                        $query = "SELECT town FROM gn_cities WHERE city = '{$member_address[1]}' and province = '{$member_address[0]}' group by town";
                                                        $res = mysqli_query($self_con,$query);
                                                        while($row = mysqli_fetch_array($res)) {
                                                            $town_list[] = $row['town'];
                                                        }
                                                    }
                                                    ?>
                                                    <select itemname="주소" id = "value_region_province" class="select" style="height: 28px;margin-top:5px;">
                                                        <option value="">-시/도-</option>
                                                        <?php foreach($province_list as $province) {?>
                                                            <option value="<?=$province?>" <? if($province == $member_address[0]) { echo 'selected'; }?>><?=$province?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <select id="value_region_city" itemname="주소" class="select" style="height: 28px;margin-top:5px;">
                                                        <option value="">-군/구-</option>
                                                        <? foreach($city_list as $city) {?>
                                                            <option value="<?=$city?>" <? if($city == $member_address[1]) { echo 'selected'; }?>><?=$city?></option>
                                                        <?}?>
                                                    </select>
                                                    <select id="value_region_town" itemname="주소" class="select" style="height: 28px;margin-top:5px;">
                                                        <option value="">-읍/면/동-</option>
                                                        <? foreach($town_list as $town) {?>
                                                            <option value="<?=$town?>" <? if($member_address[2] == $town) { echo 'selected'; }?>><?=$town?></option>
                                                        <?}?>
                                                    </select>
                                                    <input type="text" name="mem_addr" id="mem_addr" required  class="input" placeholder="지역통계를 위해 읍,면,동까지 입력" style="display: none" value="<?=$card_addr?>">
                                                <?}else{?>
                                                    <input type="text" name="mem_addr" id="mem_addr" required  class="input" value="<?=$card_addr?>">
                                                <?}?>
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
                                                <div class="input-row">
                                                    <input type="text" class="input" name="story_online1_text"
                                                           id="story_online1_text" style="width:40%" placeholder="사이트이름">
                                                    <input type="text" class="input" name="story_online1"
                                                           id="story_online1" style="width:50%" placeholder="사이트주소">
                                                    <input type="checkbox" id="radio_01" name="radio_01"
                                                           class="radio">
                                                </div>
                                                <div class="input-row">
                                                    <input type="text" class="input" name="story_online2_text"
                                                           id="story_online2_text" style="width:40%" placeholder="사이트이름">
                                                    <input type="text" class="input" name="story_online2"
                                                           id="story_online2" style="width:50%" placeholder="사이트주소">
                                                    <input type="checkbox" id="radio_02" name="radio_02"
                                                           class="radio">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="attr-row">
                                        <div class="attr-name">마이스토리<br>자기소개</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text" class="input" name="story_myinfo_title" id="story_myinfo_title" placeholder="자기소개타이틀"/>
                                                <textarea type="text" style="height:60px" name="story_myinfo" id="story_myinfo" class="input"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="attr-row">
                                        <div class="attr-name">마이스토리<br>기관소개</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text" class="input" name="story_company_title" id="story_company_title" placeholder="회사소개타이틀"/>
                                                <textarea type="text" style="height:60px" name="story_company" id="story_company" class="input"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="attr-row">
                                        <div class="attr-name">마이스토리<br>이력소개</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text" class="input" name="story_career_title" id="story_career_title" placeholder="이력소개타이틀"/>
                                                <textarea type="text" style="height:60px" name="story_career" id="story_career" class="input"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="attr-row">
                                        <div class="attr-value">
                                            <div class="input-wrap" style="text-align: right">
                                                <a href="javascript:add_contents_row();" id="add_btn" data-num = "1"><img src="img/star/icon-profile.png" style="right: 10px; top : 20px;" width="25" height="25" alt=""></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="attr-row">
                                        <div class="attr-name">컨텐츠1번<br>제목</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text" class="input" name="contents_title1" id="contents_title1" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="attr-row">
                                        <div class="attr-name">컨텐츠1번<br>이미지</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="file" name="contents_img1" id="contents_img1"  class="input">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="attr-row">
                                        <div class="attr-name">컨텐츠1번<br>링크</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text" name="contents_url1" id="contents_url1" class="input"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="attr-row">
                                        <div class="attr-name">컨텐츠1번<br>설명</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <textarea type="text" style="height:60px" name="contents_desc1" id="contents_desc1" class="input"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </section>

                        <div class="button-wrap">
                            <a href="javascript:history.back()" class="button is-grey" id = "btn_ignore">다음에 저장</a>
                            <a href="javascript:form_save();" class="button is-pink" id = "btn_save">저장</a>
                        </div>
                        <div id="ajax_div" style="display:none"></div>
                    </div>
                </div>
            </div>
        </div>
    </main><!-- // 컨텐츠 영역 시작 -->
</div>


<script language="javascript">
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
    function del_contents_row(index) {
        $('#iam_form').find('div[data-num='+index+']').remove();
        var addIndex =  $('#add_btn').attr('data-num');
        $('#add_btn').attr('data-num', (addIndex - 1));
        $('#iam_form').find('div[name=del_btn]').each(function(){
            var curIndex = $(this).attr("data-num");
            if(curIndex > index)
            {
                $(this).find('a').prop('href',"javascript:del_contents_row("+(curIndex-1)+");");
                $(this).attr("data-num", (curIndex - 1));
            }
        });

        $('#iam_form').find('div[name=contents_title]').each(function(){
            var curIndex = $(this).attr("data-num");
            if(curIndex > index)
            {
                $(this).attr("data-num", (curIndex - 1));
                $(this).find('.attr-name').html("컨텐츠"+(curIndex-1)+"번<br>제목");
                $(this).find('input').prop("name","contents_title"+(curIndex-1));
                $(this).find('input').prop("id","contents_title"+(curIndex-1));
            }
        });

        $('#iam_form').find('div[name=contents_img]').each(function(){
            var curIndex = $(this).attr("data-num");
            if(curIndex > index)
            {
                $(this).attr("data-num", (curIndex - 1));
                $(this).find('.attr-name').html("컨텐츠"+(curIndex-1)+"번<br>이미지");
                $(this).find('input').prop("name","contents_img"+(curIndex-1));
                $(this).find('input').prop("id","contents_img"+(curIndex-1));
            }
        });

        $('#iam_form').find('div[name=contents_link]').each(function(){
            var curIndex = $(this).attr("data-num");
            if(curIndex > index)
            {
                $(this).attr("data-num", (curIndex - 1));
                $(this).find('.attr-name').html("컨텐츠"+(curIndex-1)+"번<br>링크");
                $(this).find('input').prop("name","contents_url"+(curIndex-1));
                $(this).find('input').prop("id","contents_url"+(curIndex-1));
            }
        });

        $('#iam_form').find('div[name=contents_desc]').each(function(){
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
    function add_contents_row(){
        var index = $('#add_btn').attr('data-num');
        index ++;
        var cont = "<div class='attr-row' name = 'del_btn' data-num = " + index + ">"+
                        "<div class='attr-value'>"+
                            "<div class='input-wrap' style='text-align: right'>"+
                                "<a href='javascript:del_contents_row("+index+");'>"+
                                    "<img src='img/star/icon-bin2.png' style='right: 10px; top : 20px;' width='25' height='25'></a>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='attr-row' name = 'contents_title' data-num = " + index + ">" +
                        "<div class='attr-name'>컨텐츠"+index+"번<br>제목</div>"+
                        "<div class='attr-value'>"+
                            "<div class='input-wrap'>"+
                                "<input type='text' class='input' name='contents_title"+index+"' id='contents_title"+index+"' />"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='attr-row' name = 'contents_img' data-num = " + index + ">"+
                        "<div class='attr-name'>컨텐츠"+index+"번<br>이미지</div>"+
                        "<div class='attr-value'>"+
                            "<div class='input-wrap'>"+
                                "<input type='file' name='contents_img"+index+"' id='contents_img"+index+"'  class='input'>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='attr-row' name = 'contents_link' data-num = " + index + ">"+
                        "<div class='attr-name'>컨텐츠"+index+"번<br>링크</div>"+
                        "<div class='attr-value'>"+
                            "<div class='input-wrap'>"+
                                "<input type='text' name='contents_url"+index+"' id='contents_url"+index+"' class='input'/>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                    "<div class='attr-row' name = 'contents_desc' data-num = " + index + ">"+
                        "<div class='attr-name'>컨텐츠"+index+"번<br>설명</div>"+
                        "<div class='attr-value'>"+
                            "<div class='input-wrap'>"+
                                "<textarea type='text' style='height:60px' name='contents_desc"+index+"' id='contents_desc"+index+"' class='input'></textarea>"+
                            "</div>"+
                        "</div>"+
                    "</div>";
        $('#iam_form').append(cont);
        console.log(index);
        $('#add_btn').attr("data-num", index);
    }
    $('input[name=main_type1]').change(function(){
        $('#main_img1').attr('src', "");
        if($(this).val() == 'f')
        {
            $('#main_upload1').attr("style", "display:block");
            $('#main_upload1').attr("style", "width:100%");
            $('#main_img1_link').attr("style", "display:none");
            $('#btn_img1').attr("style", "display:none");
            $('#main_img1_link').val("");
        }
        else if($(this).val() == 'u')
        {
            $("#main_upload1").val("");
            $('#main_upload1').attr("style", "display:none");
            $('#main_img1_link').attr("style", "display:block");
            $('#main_img1_link').attr("style", "width:100%");
            $('#main_img1_link').attr("placeholder", "이미지주소");
            $('#btn_img1').attr("style", "display:none");
        }
        else{
            $("#main_upload1").val("");
            $('#main_upload1').attr("style", "display:none");
            $('#main_img1_link').attr("style", "display:block");
            $('#main_img1_link').attr("style", "width:100%");
            $('#main_img1_link').attr("placeholder", "웹페이지주소");
            $('#btn_img1').attr("style", "display:block");
        }
    });
    $('input[name=btn_img1]').click(function(){
        if($('#main_img1_link').val() == "")
            alert("웹주소를 입력해주세요.");
        else {
            $('input[name=main_type1]').attr("disabled", true);
            $('input[name=btn_img1]').attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "https://www.goodhow.com/crawler/crawler/scrape/getImage.php",
                dataType: "json",
                data: {
                    web_address: $('#main_img1_link').val()
                },
                success: function (result) {
                    $('#main_img1_link').val(result.link);
                    $('#main_img1').attr('src', result.link);
                    $('input[name=main_type1]').attr("disabled", false);
                    $('input[name=btn_img1]').attr("disabled", false);
                },
                error: function () {
                    $('input[name=main_type1]').attr("disabled", false);
                    $('input[name=btn_img1]').attr("disabled", false);
                    alert("웹주소를 크롤링할수 없습니다.");
                }
            });
        }
    });
    $("#main_img1_link").keyup(function () {
        $('#main_img1').attr('src', $(this).val());
    });
    $("#main_upload1").change(function () {
        var input = this;
        var url = $(this).val();
        if (input.files && input.files[0])
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#main_img1').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });

    $('input[name=main_type2]').change(function(){
        $('#main_img2').attr('src', "");
        if($(this).val() == 'f')
        {
            $('#main_upload2').attr("style", "display:block");
            $('#main_upload2').attr("style", "width:100%");
            $('#main_img2_link').attr("style", "display:none");
            $('#btn_img2').attr("style", "display:none");
            $('#main_img2_link').val("");
        }
        else if($(this).val() == 'u')
        {
            $("#main_upload2").val("");
            $('#main_upload2').attr("style", "display:none");
            $('#main_img2_link').attr("style", "display:block");
            $('#main_img2_link').attr("style", "width:100%");
            $('#main_img2_link').attr("placeholder", "이미지주소");
            $('#btn_img2').attr("style", "display:none");
        }
        else{
            $("#main_upload2").val("");
            $('#main_upload2').attr("style", "display:none");
            $('#main_img2_link').attr("style", "display:block");
            $('#main_img2_link').attr("style", "width:100%");
            $('#main_img2_link').attr("placeholder", "웹페이지주소");
            $('#btn_img2').attr("style", "display:block");
        }
    });
    $('input[name=btn_img2]').click(function(){
        if($('#main_img2_link').val() == "")
            alert("웹주소를 입력해주세요.");
        else {
            $('input[name=main_type2]').attr("disabled", true);
            $('input[name=btn_img2]').attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "https://www.goodhow.com/crawler/crawler/scrape/getImage.php",
                dataType: "json",
                data: {
                    web_address: $('#main_img2_link').val()
                },
                success: function (result) {
                    $('#main_img2_link').val(result.link);
                    $('#main_img2').attr('src', result.link);
                    $('input[name=main_type2]').attr("disabled", false);
                    $('input[name=btn_img2]').attr("disabled", false);
                },
                error: function () {
                    $('input[name=main_type2]').attr("disabled", false);
                    $('input[name=btn_img2]').attr("disabled", false);
                    alert("웹주소를 크롤링할수 없습니다.");
                }
            });
        }
    });
    $("#main_img2_link").keyup(function () {
        $('#main_img2').attr('src', $(this).val());
    });
    $("#main_upload2").change(function () {
        var input = this;
        var url = $(this).val();
        if (input.files && input.files[0])
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#main_img2').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });

    $('input[name=main_type3]').change(function(){
        $('#main_img3').attr('src', "");
        if($(this).val() == 'f')
        {
            $('#main_upload3').attr("style", "display:block");
            $('#main_upload3').attr("style", "width:100%");
            $('#main_img3_link').attr("style", "display:none");
            $('#btn_img3').attr("style", "display:none");
            $('#main_img3_link').val("");
        }
        else if($(this).val() == 'u')
        {
            $("#main_upload3").val("");
            $('#main_upload3').attr("style", "display:none");
            $('#main_img3_link').attr("style", "display:block");
            $('#main_img3_link').attr("style", "width:100%");
            $('#main_img3_link').attr("placeholder", "이미지주소");
            $('#btn_img1').attr("style", "display:none");
        }
        else{
            $("#main_upload3").val("");
            $('#main_upload3').attr("style", "display:none");
            $('#main_img3_link').attr("style", "display:block");
            $('#main_img3_link').attr("style", "width:100%");
            $('#main_img3_link').attr("placeholder", "웹페이지주소");
            $('#btn_img3').attr("style", "display:block");
        }
    });
    $('input[name=btn_img3]').click(function(){
        if($('#main_img3_link').val() == "")
            alert("웹주소를 입력해주세요.");
        else {
            $('input[name=main_type3]').attr("disabled", true);
            $('input[name=btn_img3]').attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "https://www.goodhow.com/crawler/crawler/scrape/getImage.php",
                dataType: "json",
                data: {
                    web_address: $('#main_img3_link').val()
                },
                success: function (result) {
                    $('#main_img3_link').val(result.link);
                    $('#main_img3').attr('src', result.link);
                    $('input[name=main_type3]').attr("disabled", false);
                    $('input[name=btn_img3]').attr("disabled", false);
                },
                error: function () {
                    $('input[name=main_type3]').attr("disabled", false);
                    $('input[name=btn_img3]').attr("disabled", false);
                    alert("웹주소를 크롤링할수 없습니다.");
                }
            });
        }
    });
    $("#main_img3_link").keyup(function () {
        $('#main_img3').attr('src', $(this).val());
    });
    $("#main_upload3").change(function () {
        var input = this;
        var url = $(this).val();
        if (input.files && input.files[0])
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#main_img3').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });

    $('input[name=main_type]').change(function(){
        $('#logo_img').attr('src', "");
        if($(this).val() == 'f')
        {
            $('#logo_file').attr("style", "display:block");
            $('#logo_file').attr("style", "width:100%");
            $('#logo_link').attr("style", "display:none");
            $('#btn_img_logo').attr("style", "display:none");
            $('#logo_link').val("");
        }
        else if($(this).val() == 'u')
        {
            $("#logo_file").val("");
            $('#logo_file').attr("style", "display:none");
            $('#logo_link').attr("style", "display:block");
            $('#logo_link').attr("style", "width:100%");
            $('#logo_link').attr("placeholder", "이미지주소");
            $('#btn_img_logo').attr("style", "display:none");
        }
        else{
            $("#logo_file").val("");
            $('#logo_file').attr("style", "display:none");
            $('#logo_link').attr("style", "display:block");
            $('#logo_link').attr("style", "width:100%");
            $('#logo_link').attr("placeholder", "웹페이지주소");
            $('#btn_img_logo').attr("style", "display:block");
        }
    });
    $('input[name=btn_img_logo]').click(function(){
        if($('#logo_link').val() == "")
            alert("웹주소를 입력해주세요.");
        else {
            $('input[name=main_type]').attr("disabled", true);
            $('input[name=btn_img_logo]').attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "https://www.goodhow.com/crawler/crawler/scrape/getImage.php",
                dataType: "json",
                data: {
                    web_address: $('#logo_link').val()
                },
                success: function (result) {
                    $('#logo_link').val(result.link);
                    $('#logo_img').attr('src', result.link);
                    $('input[name=main_type]').attr("disabled", false);
                    $('input[name=btn_img_logo]').attr("disabled", false);
                },
                error: function () {
                    $('input[name=main_type]').attr("disabled", false);
                    $('input[name=btn_img_logo]').attr("disabled", false);
                    alert("웹주소를 크롤링할수 없습니다.");
                }
            });
        }
    });
    $("#logo_link").keyup(function () {
        $('#logo_img').attr('src', $(this).val());
    });
    $("#logo_file").change(function () {
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
            $("#mem_addr").val(address);
        }
    });
    function post_contents(index, card_short_url) {
        var formData = new FormData();
        if ($('#contents_img'+ index)[0].files.length) {
            formData.append('contents_img[]', $('#contents_img'+index)[0].files[0]);
        }
        formData.append("post_type", "creat");
        formData.append("mem_id", $('#memid').val());
        formData.append("contents_type", 1);
        formData.append("contents_title", $('#contents_title'+index).val());
        formData.append("contents_url", $('#contents_url'+index).val());
        formData.append("contents_iframe", "");
        formData.append("contents_price", 0);
        formData.append("contents_sell_price", 0);
        formData.append("contents_desc", $('#contents_desc'+index).val());
        formData.append("contents_share_id", "");
        formData.append("contents_idx", 0);
        formData.append("card_short_url", card_short_url);
        formData.append("westory_card_url", card_short_url);
        formData.append("contents_footer_display", "Y");
        formData.append("contents_user_display", "Y");
        formData.append("contents_westory_display", "N");
        $.ajax({
            type: "POST",
            url: "ajax/contents.proc.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                console.log(data);
                if(index == 1)
                {
                    $('#multi_btn_cancel').attr("disabled", false);
                    $('#multi_btn_ok').attr("disabled", false);
                    console.log(data);
                    //alert('컨텐츠가 등록 되었습니다.');
                    //location.reload();
                }
                else
                    console.log(data);
                //alert(data);
            }
        });
    }
    function form_save() {
        toastr.options = {
            // "progressBar": true,
            "timeOut": 3000
        }
        if($('#memid').val() == "")
        {
            if($('input[name=id_status]').val() != 'ok')
            {
                toastr.error ("아이디중복확인 : 필수 확인입니다.", "오류");
                $('input[name=id_status]').focus();
                return false;
            }
        }
        if(!$('#card_name').val()) {
            toastr.error("프로필 이름을 입력하세요.", "오류");
            $('#card_name').focus();
            return false;
        }
        /*else if(($('input[name=card_phone]').val()) != '010' && ($('input[name=mobile_1]').val()) != '011' && ($('input[name=mobile_1]').val()) != '016' && ($('input[name=mobile_1]').val()) != '017' && ($('input[name=mobile_1]').val()) != '018' && ($('input[name=mobile_1]').val()) != '019') {
            toastr.error('전화번호 첫번째 자리는 010, 011, 016, 017, 018, 019만 가능합니다.', "오류")
            $('input[name=mobile_1]').focus();
            return false;
        }*/
        else if($('#rnum').val() == "" && $('#memid').val() == "") {
            toastr.error('휴대폰 인증번호를 입력해 주세요.', "오류")
            return false;      
        }
        else if(!$('#card_company').val()) {
            toastr.error("소속과 직책을 입력하세요", "오류");
            $('#card_company').focus();
            return false;
        }
        else if(!$('#card_position').val()) {
            toastr.error("한문장으로 자기를 소개해요.", "오류");
            $('#card_position').focus();
            return false;
        }
        else if($('#main_type1').prop("checked") && $('#main_img1_link').val() != "" && $('#main_img1').prop("src") !== $('#main_img1_link').val()) {
            toastr.error("대표이미지1를 크롤링해주세요.", "오류");
            return false;
        }
        else if($('#main_type2').prop("checked") && $('#main_img2_link').val() != "" && $('#main_img2').prop("src") !== $('#main_img2_link').val()) {
            toastr.error("대표이미지2를 크롤링해주세요.", "오류");
            return false;
        }
        else if($('#main_type3').prop("checked") && $('#main_img3_link').val() != "" && $('#main_img3').prop("src") !== $('#main_img3_link').val()) {
            toastr.error("대표이미지3를 크롤링해주세요.", "오류");
            return false;
        }
        else if($('#main_type').prop("checked") && $('#logo_link').val() != "" && $('#main_img_logo').prop("src") !== $('#logo_link').val()) {
            toastr.error("로고이미지를 크롤링해주세요.", "오류");
            return false;
        }
        else {
            var count = $('#add_btn').attr("data-num");
            for(var i = 1 ; i <= count;i++)
            {
                if($('#contents_title' + i).val() == '')//contents_img1,contents_url1,contents_desc1
                {
                    toastr.error("컨텐츠"+ i + "번 제목을 입력하세요.", "오류");
                    $('#contents_title' + i).focus();
                    return;
                }
                else if($('#contents_img' + i).val() == '')
                {
                    toastr.error("컨텐츠"+ i + "번 이미지를 첨부해 주세요.", "오류");
                    $('#contents_img' + i).focus();
                    return;
                }
            }

            $('#btn_save').attr("disabled", true);
            $('#btn_ignore').attr("disabled", true);
            var formData = new FormData();
            var phone_str = $('input[name=card_phone]').val();
            formData.append('mode', $('#mode').val());
            if($('#memid').val() != "")
                formData.append('mem_id', $('#memid').val());
            else
                formData.append('mem_id', $('#id').val());
            formData.append('card_title', $('#card_title').val());
            formData.append('card_name', $('#card_name').val());
            formData.append('card_company', $('#card_company').val());
            formData.append('card_position', $('#card_position').val());
            formData.append('card_phone',phone_str);
            formData.append('card_email', $('#email_1').val() + "@" + $('#email_2').val());
            formData.append('card_addr', $('#mem_addr').val());
            formData.append('card_keyword', $('#card_keyword').val());

            formData.append('story_title4', $('#story_title4').val());
            formData.append('story_online1_text', $('#story_online1_text').val());
            formData.append('story_online2_text', $('#story_online2_text').val());
            formData.append('story_online1', $('#story_online1').val());
            formData.append('story_online2', $('#story_online2').val());
            formData.append('story_title1',$('#story_myinfo_title').val());
            formData.append('story_title2',$('#story_company_title').val());
            formData.append('story_title3',$('#story_career_title').val());
            formData.append('story_myinfo',$('#story_myinfo').val());
            formData.append('story_company',$('#story_company').val());
            formData.append('story_career',$('#story_career').val());
            formData.append('online1_check', $('#radio_01').prop('checked') ? 'Y':'N');
            formData.append('online2_check', $('#radio_01').prop('checked') ? 'Y':'N');

            if ($('#logo_file')[0].files.length) {
                formData.append('uploadFile', $('#logo_file')[0].files[0]);
            }
            else{
                formData.append('logo_link', $('#logo_link').val());
            }
            if ($('#main_upload1')[0].files.length) {
                formData.append('uploadFile1', $('#main_upload1')[0].files[0]);
            }
            else{
                formData.append('main_img1_link', $('#main_img1_link').val());
            }

            if ($('#main_upload2')[0].files.length) {
                formData.append('uploadFile2', $('#main_upload2')[0].files[0]);
            }
            else{
                formData.append('main_img2_link', $('#main_img2_link').val());
            }

            if ($('#main_upload3')[0].files.length) {
                formData.append('uploadFile3', $('#main_upload3')[0].files[0]);
            }
            else{
                formData.append('main_img3_link', $('#main_img3_link').val());
            }
            if($('#memid').val() == "") {
                var birth_str = $('select[name=birth_1]').val() + "-" + $('select[name=birth_2]').val() + "-" + $('select[name=birth_3]').val();
                $.ajax({
                    type: "POST",
                    url: "/ajax/ajax.php",
                    data: {
                        join_id: $('#id').val(),
                        join_nick: 'join',
                        join_pwd: $('#passwd').val(),
                        join_web_pwd: $('#passwd').val(),
                        join_name: $('#card_name').val(),
                        join_email: $('#mem_email').val(),
                        join_phone: phone_str,
                        join_add1: $('#mem_addr').val(),
                        join_zy: $('#card_company').val(),
                        join_birth: birth_str,
                        join_is_message: 'Y',
                        solution_type: $('#site').val(),
                        solution_name: $('#site_name').val(),
                        rnum: $('#rnum').val(),
                        bank_name: '',
                        bank_account: '',
                        bank_owner: '',
                        recommend_id: 'kiam'
                    },
                    success: function (data) {
                        if ($.trim(data) == "1") {
                            $.ajax({
                                type: "POST",
                                url: "ajax/namecard.proc.php",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function (data) {
                                    if (data.length > 20) {
                                        alert('result:' + data);
                                    } else {
                                        for (var i = count; i >= 1; i--)
                                            post_contents(i, data);
                                        alert("명함등록이 완료 되었습니다.");
                                        location.href = "/index.php?" + data;
                                    }
                                }
                            });
                        } else {
                            alert($.trim(data));
                        }
                    },
                    error: function () {

                    }
                });
            }else{
                $.ajax({
                    type: "POST",
                    url: "ajax/namecard.proc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.length > 20) {
                            alert('result:' + data);
                        } else {
                            for (var i = count; i >= 1; i--)
                                post_contents(i, data);
                            alert("명함등록이 완료 되었습니다.");
                            location.href = "/index.php?" + data;
                        }
                    }
                });
            }
        }
    }
</script>
</body>

</html>