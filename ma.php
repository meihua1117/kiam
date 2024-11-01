<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once "_head.php";
header("Pragma:no-cache");
if (isset($_REQUEST['mem_code']) && $_REQUEST['mem_code']) {
    setcookie("mem_code", $_REQUEST['mem_code'], time() + 3600);
    $_COOKIE['mem_code'] = $_REQUEST['mem_code'];
}
?>
<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
<style>
    .index .midle .bg {
        position: absolute;
        overflow: hidden;
    }

    .daily_tmp_popup {
        font-size: 15px;
        background-color: white;
        color: black;
        text-align: left;
        position: absolute;
        z-index: 200;
        top: 100px;
        left: 50%;
        transform: translate(-50%, 0px);
    }

    .daily_tmp_title {
        text-align: center;
        background-color: rgb(130, 199, 54);
        padding: 10px;
        font-size: 20px;
        color: white;
    }

    .daily_tmp_desc {
        padding: 15px;
    }

    .daily_tmp_button {
        text-align: center;
        padding: 10px;
    }

    @media only screen and (max-width: 450px) {
        .daily_tmp_popup {
            width: 80%;
        }
    }
</style>
<script src="/js/jquery-2.1.0.js"></script>
<!-- 아래 이징 플러그인은 부드러운 모션 처리를 위해서 필요 -->
<script src="/js/jquery.easing.1.3.js"></script>
<script src="motionj.com.jBanner.1.2.js"></script>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script language="javascript">
    jQuery(document).ready(
        function() {
            for (var i = 0; i < 3; i++) {
                var id;
                if ($('#alertid' + i).length)
                    id = $('#alertid' + i)[0].value;
                if ($('#alertid' + i).length && document.cookie.search('Memo' + id) == -1)
                    showIframeModal('/notice_pop.php?id=' + id);
            }
        }
    );

    function show_login() {
        $('.ad_layer_login').lightbox_me({
            centered: true,
            onLoad: function() {}
        });
    }

    function showIframeModal(url) {
        window.open(url, "", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=800,height=400");
    }
    $(function() {
        $('.jBanner').jBanner({
            width: 570,
            height: 270
        });
    });
    $(function() {
        tab('#tab', 0);
    });

    function tab(e, num) {
        var num = num || 0;
        var menu = $(e).children();
        var con = $(e + '_con').children();
        var select = $(menu).eq(num);
        var i = num;
        select.addClass('on');
        con.eq(num).show();
        menu.click(function() {
            if (select !== null) {
                select.removeClass("on");
                con.eq(i).hide();
            }
            select = $(this);
            i = $(this).index();
            select.addClass('on');
            con.eq(i).show();
        });
    }
</script>
<?/*if($use_domain == false){
      echo "<Script>alert('".$HTTP_HOST."도메인은 독립적으로 사용할 권한이 없습니다.해당 도메인은 www.kiam.kr에 소속되어 있으므로 확인을 누르시면 상위 도메인으로 이동합니다.');location.href = 'https://www.kiam.kr/ma.php';</script>";
      exit;
  }*/
?>
<div class="container">
    <?
    $query = "SELECT NO FROM tjd_board WHERE pop_yn='Y' ORDER BY DATE DESC LIMIT 0, 3";
    $res = mysqli_query($self_con,$query);
    $totalCnt    =  mysqli_num_rows($res);

    for ($i = 0; $i < $totalCnt; $i++) {
        $alert_ids    =  mysqli_fetch_array($res); ?>
        <input type="text" id="alertid<?= $i ?>" value="<?= $alert_ids[0] ?>" hidden>
    <? }
    if ($sub_domain == true && $domainData['main_default_yn'] == "I") { ?>
        <div class="midle_div">
            <div style="text-align:center">
                <img src="<?= $domainData['main_image'] ?>" style="width : 100%">
            </div>
        </div>
        <? if ($HTTP_HOST == "moodoga.kiam.kr") { ?>
            <link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
            <script src="/js/jquery-2.1.0.js"></script>
            <style>
                .head_left img {
                    width: 166px !important;
                    height: 72px !important;
                }

                .container .vote_top .tab_box {
                    position: absolute;
                    top: 300px;
                    right: 510px;
                    overflow: hidden;
                    width: 577px;
                    height: 269px;
                }
            </style>
            <!-- 아래 이징 플러그인은 부드러운 모션 처리를 위해서 필요 -->
            <script src="/js/jquery.easing.1.3.js"></script>
            <script src="motionj.com.jBanner.1.2.js"></script>
            <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
            <script type="text/javascript">
                $(function() {
                    $('.jBanner').jBanner({
                        width: 570,
                        height: 270
                    });
                });
            </script>
            <div class="container">
                <div class="">
                    <img src="/images/view_visual_1_moo.jpg" usemap="#Map" border="0" />
                    <map name="Map" id="Map">
                        <area shape="rect" coords="597,505,991,622" href="join.php" target="_blank" />
                    </map>
                    <div class="tab_box">
                        <div class="">
                            <ul>
                                <li><a href="#"><img src="" alt="" /></a></li>
                                <li><a href="#"><img src="" alt="" /></a></li>
                                <li><a href="#"><img src="" alt="" /></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="vote_middle">
                    <div class="md_con">
                        <div class="md_con1">
                            <div>
                                <img src="/images/vote_middle_1.jpg" alt="문자 발송무제한" />
                            </div>
                            <div>
                                <dl>
                                    <dt><img class="T_btn" src="/images/vote_middle_btn_11.jpg" /><span class="T_font">문자 발송수 무제한</span></dt>
                                    <dd class="D_font">스마트폰 무제한 요금제를<br />사용하여 무제한 발송 가능</dd>
                                </dl>
                                <p class="dashed"><img src="/images/vote_dashed.jpg" /></p>
                                <dl>
                                    <dt><img class="T_btn" src="/images/vote_middle_btn_11.jpg" /><span class="T_font">장문/포토 무제한</span></dt>
                                    <dd class="D_font">장문, 단문, 포토까지<br />무제한 발송 가능</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="md_con2">
                            <div>
                                <img src="/images/vote_middle_2.jpg" alt="수신거부자동화무제한" />
                            </div>
                            <div>
                                <dl>
                                    <dt><img class="T_btn" src="/images/vote_middle_btn_11.jpg" /><span class="T_font">수신거부 자동화</span></dt>
                                    <dd class="D_font">080 대신 수신거부 자동등록<br />및 재발송 중지 기능</dd>
                                </dl>
                                <p class="dashed"><img src="/images/vote_dashed.jpg" /></p>
                                <dl>
                                    <dt><img class="T_btn" src="/images/vote_middle_btn_11.jpg" /><span class="T_font">수신불가 자동화</span></dt>
                                    <dd class="D_font">수신불가 번호, 없는 번호,<br />번호변경 건 확인 가능</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="md_con3">
                            <div>
                                <img src="/images/vote_middle_3.jpg" alt="그룹별발송무제한" />
                            </div>
                            <div>
                                <dl>
                                    <dt><img class="T_btn" src="/images/vote_middle_btn_11.jpg" /><span class="T_font">그룹별 발송기능</span></dt>
                                    <dd class="D_font">개인번호 입력, 엑셀파일<br />업로드하여 그룹별 발송 가능</dd>
                                </dl>
                                <p class="dashed"><img src="/images/vote_dashed.jpg" /></p>
                                <dl>
                                    <dt><img class="T_btn" src="/images/vote_middle_btn_11.jpg" /><span class="T_font">시간절약 기능</span></dt>
                                    <dd class="D_font">그룹에게 최단기간으로<br />발송 가능</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="md_con4">
                            <div>
                                <img src="/images/vote_middle_4.jpg" alt="통계내역 종합확인" />
                            </div>
                            <div>
                                <dl>
                                    <dt><img class="T_btn" src="/images/vote_middle_btn_11.jpg" /><span class="T_font">수신내역 확인</span></dt>
                                    <dd class="D_font">수신불가, 없는번호,<br />번호변경 확인 가능</dd>
                                </dl>
                                <p class="dashed"><img src="/images/vote_dashed.jpg" /></p>
                                <dl>
                                    <dt><img class="T_btn" src="/images/vote_middle_btn_11.jpg" /><span class="T_font">발신내역 확인</span></dt>
                                    <dd class="D_font">발신내역, 예약내역<br />확인 및 통계 가능</dd>
                                </dl>
                                <p class="dashed"><img src="/images/vote_dashed.jpg" /></p>
                                <dl>
                                    <dt><img class="T_btn" src="/images/vote_middle_btn_11.jpg" /><span class="T_font">발송통계 확인</span></dt>
                                    <dd class="D_font">오늘발송건, 이달발송건,<br />이달 수신처 통계 가능</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="midle_div">
                    <div align="center"><img src="images/190320_main_03.png" width="900" /></div><br />
                    <div align="center" height="510px" style="background:url(/images/m_bg_m.JPG) no-repeat center / cover; height:510px;repeat-x;repeat-y;line-height:510px;">
                        <div style="display: block; padding-top:20px;padding-bottom:20px; "><img src="images/190508_main01.png" width="900" /></div>
                    </div>
                    <div class="index con_title" style="background:url(/images/m_bg_bar.JPG) ; repeat-x ;width:1000px; font-color:#EFF2FB;">문자은행 기능비교</div> <br />
                    <div align="center"><img src="images/190508_main02.png" width="900" /></div><br />
                </div>
            </div>
        <? }
    } else { ?>
        <div class="main-visual main-section">
            <div class="inner">
                <img src="images/191220_01.png">
            </div>
        </div>
        <? if ($sub_domain == true) { ?>
            <div class="bnt main-buttons">
                <div class="wrap2">
                    <a href="https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms" class="button" target="_blank">IAM앱설치</a>
                    <a href="<?php echo $domainData['kakao']; ?>" class="button2" target="_blank">카카오상담</a>
                    <a href="/join.php" class="button" target="_blank">회원가입하기</a>
                    <a href="/event/event.html?pcode=&sp=&landing_idx=747" class="button" target="_blank">처음오셨어요?</a>
                    <a href="https://oog.kiam.kr/pages/page_3208.php" class="button" target="_blank">이용매뉴얼</a>
                    <a href="/event/event.html?pcode=onlyselling_coaching111&sp=onlyselling_coaching" class="button" target="_blank">코칭신청하기</a>
                </div>
            </div>
        <? } else { ?>
            <div class="bnt main-buttons">
                <div class="wrap2">
                    <a href="https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms" class="button" target="_blank">IAM앱설치</a>
                    <a href="http://pf.kakao.com/_jVafC/chat" class="button2" target="_blank">카카오상담</a>
                    <a href="/join.php" class="button" target="_blank">회원가입하기</a>
                    <a href="/event/event.html?pcode=&sp=&landing_idx=747" class="button" target="_blank">처음오셨어요?</a>
                    <a href="https://oog.kiam.kr/pages/page_3208.php" class="button" target="_blank">이용매뉴얼</a>
                    <a href="/event/event.html?pcode=onlyselling_coaching111&sp=onlyselling_coaching" class="button" target="_blank">코칭신청하기</a>
                </div>
            </div>
        <? } ?>
        <div class="main-section">
            <div class="im_title is-main">
                <div class="inner">
                    <img src="images/191220_11.png">
                </div>
            </div>
            <div class="inner">
                <img src="images/191220_02.png">
            </div>
        </div>
</div>
<div class="main-bg-01 main-section">
    <div class="inner">
        <img src="images/191220_03.png">
    </div>
</div>
<div class="main-bg-04 main-section">
    <div class="inner">
        <img src="images/200410_01.png">
    </div>
</div>
<div class="main-section">
    <div class="inner">
        <img src="images/191220_04.png">
    </div>
</div>
<div class="main-bg-02 main-section">
    <div class="inner">
        <img src="images/191220_05.png">
    </div>
</div>

<div class="main-section">
    <div class="inner">
        <img src="images/191220_06.png">
    </div>
</div>
<div class="main-section">
    <div class="inner">
        <img src="images/191220_07.png">
    </div>
</div>
<div class="main-bg-03 main-section">
    <div class="inner">
        <img src="images/191220_08.png">
    </div>
</div>
<div class="main-section">
    <div class="inner">
        <img src="images/191220_09.png">
    </div>
</div>
<? }
    include_once "_foot.php";
?>
<span class="daily_tmp_popup" id="daily_tmp_popup" style="display:none;">
    <p class="daily_tmp_title" style="font-size: 14px;">
        경기중소기업CEO연합회 회원사의 회원가입 및 로그인 안내
        <a href="javascript:hide_daily_popup()" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -5px;cursor:pointer;">X</a>
    </p>
    <br>
    <p class="daily_tmp_desc">
        환영합니다.<br>
        본 플랫폼은 CEO연합회 회원사를 위한 자동화 마케팅 및 고객관리 플랫폼입니다.<br>
        본 플랫폼을 이용하실 분은 아래와 같은 로그인 지침을 확인하시기바랍니다.<br><br>
        1. 연합회 회원사는 gscf.kr에서 회원가입한 경우 아래처럼 로그인해주세요<br>
        - http://gscf.kr 홈피의 아이디에 'ceo_'를 앞에 붙이고 임시비번 123456으로 로그인하여 비번 변경을 하시면 됩니다.<br>
        - 본 플랫폼을 이용하시기 위해서는 gscf.kr에 접속하여 먼저 회원가입해주세요.<br>
        - 휴대폰에서 아래 주소를 클릭하여 앱설치를 하시기바랍니다.<br>
        <a href='https://me2.do/xVB4OHoO' target='_blank'>https://me2.do/xVB4OHoO</a><br><br>
        2. 본 플랫폼을 활용하시기 위해서는 아래 이용안내 영상을 보시고활용해주세요.<br>
        <a href='https://me2.do/IDFIAJMO' target='_blank'>https://me2.do/IDFIAJMO</a><br><br>
        3. 본 플랫폼 이용중 문의 사항은 아래 카톡상담창으로 남겨주세요<br>
        <a href='https://pf.kakao.com/_jVafC/chat' target='_blank'>https://pf.kakao.com/_jVafC/chat</a><br><br>
        감사합니다.
    </p>
    <div class="daily_tmp_button" style="float:right">
        <div style="float: left;">더 이상 보지 않기</div>
        <input type="checkbox" onclick="deny_tmp_popup(this)" style="float:left;">
    </div>
</span>
<script>
    function newpop(str) {
        //window.open(str, "notice_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=600,height=350");
    }
    if (Gesi_getCookie('Memo') != 'done')
        newpop('notice_pop.php');

    function getCookie(name) {
        var Found = false
        var start, end
        var i = 0
        while (i <= document.cookie.length) {
            start = i
            end = start + name.length
            if (document.cookie.substring(start, end) == name) {
                Found = true
                break
            }
            i++
        }
        if (Found == true) {
            start = end + 1
            end = document.cookie.indexOf(";", start)
            if (end < start)
                end = document.cookie.length
            return document.cookie.substring(start, end)
        }
        return ""
    }

    function setCookie(name, value, expiredays) {
        var todayDate = new Date();
        todayDate.setDate(todayDate.getDate() + expiredays);
        document.cookie = name + "=" + escape(value) + ";path=/;expires=" + todayDate.toGMTString() + ";"
    }

    function dbFree() {
        var msg = confirm('온리원 디버 무료체험 신청하시겠습니까? 300건의 무료 건수가 제공됩니다.');
        if ($('#password').val() != "") {
            if ($('#password').val() != $('#password_re').val()) {
                alert('비밀번호를 확인해주세요');
                return;
            }
        }
        if (msg) {
            $.ajax({
                type: "POST",
                url: "/ajax/crawler_request.php",
                dataType: "json",
                success: function(result) {
                    if (result.result == "fail") {
                        alert(result.msg)
                    } else {
                        alert('온리원디버 체험판 신청 완료되었습니다. 디버 체험판을 다운로드 후 이용해주세요.');
                        return;
                    }
                },
                error: function() {
                    alert('로그인 후 신청해주세요.');
                }
            });
        } else {
            return false;
        }
    }
    if(window.location.host  == "gscfceo.kiam.kr" && getCookie("gscfceo") != "off"){
        $("#daily_tmp_popup").show();
    }

    function hide_daily_popup() {
        $("#daily_tmp_popup").hide();
    }

    function deny_tmp_popup(object) {
        if ($(object).prop("checked")) {
            setCookie("gscfceo","off",365);
            hide_daily_popup();
        }
    }
</script>