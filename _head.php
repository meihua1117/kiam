<script>
    function newpop1(str) {
        window.open(str, "notice_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=600,height=350");
    }

    function Gesi_getCookie(name) {
        var nameOfCookie = name + '=';
        var x = 0;
        while (x <= document.cookie.length) {
            var y = (x + nameOfCookie.length);
            if (document.cookie.substring(x, y) == nameOfCookie) {
                if ((endOfCookie = document.cookie.indexOf(';', y)) == -1)
                    endOfCookie = document.cookie.length;
                return unescape(document.cookie.substring(y, endOfCookie));
            }
            x = document.cookie.indexOf(' ', x) + 1;
            if (x == 0)
                break;
        }
        return '';
    }
</script>
<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
$date_today = date("Y-m-d");
if ($_SESSION['one_member_id']) {
    $sql = "select * from tjd_pay_result where buyer_id='{$_SESSION['one_member_id']}' and end_status in ('Y','A')  and gwc_cont_pay=0 and 
        ((member_type like '%standard%' or member_type like '%professional%' or member_type like '%enterprise%') or 
        (((iam_pay_type = '' or iam_pay_type = '0' or iam_pay_type = '전문가') and member_type != '포인트충전')) or member_type='베스트상품') and payMethod <> 'POINT' order by end_date desc";
    /*$sql = "select * from tjd_pay_result where buyer_id='{$_SESSION['one_member_id']}' and gwc_cont_pay=0 and 
        ((member_type like '%standard%' or member_type like '%professional%' or member_type like '%enterprise%') or 
        (((iam_pay_type = '' or iam_pay_type = '0' or iam_pay_type = '전문가') and member_type != '포인트충전')) or member_type='베스트상품') and payMethod <> 'POINT' order by end_date desc";*/
    $res_result = mysqli_query($self_con,$sql);
    $pay_data = mysqli_fetch_array($res_result);
}
$mem_type = $member_1['mem_type'];
$site = $member_1['site_iam'];
$iam_type = $member_1['iam_type'];
if ($site) {
    if ($site == "kiam")
        $href = "http://www.kiam.kr/";
    else
        $href = "http://" . $site . ".kiam.kr/";
} else {
    $href = "/";
}
//$use_domain = false;
$sub_domain = false;
if ($HTTP_HOST == "kiam.kr")
    $host = "www.kiam.kr";
else
    $host = $HTTP_HOST;
$query = "select * from Gn_Service where sub_domain like '%{$host}%'";
$res = mysqli_query($self_con,$query);
$domainData = mysqli_fetch_array($res);
if ($HTTP_HOST != "kiam.kr") {
    if ($domainData['idx'] != "") {
        $sub_domain = true;
        if ($_SERVER['REQUEST_URI'] == '/' && $domainData['main_default_yn'] == "L") {
            header('Location: ' . $domainData['main_url']);
        }
        $curdate = strtotime(date('Y-m-d', time()));
        $startdate = strtotime($domainData['contract_start_date']);
        $enddate = strtotime($domainData['contract_end_date']);
        /*if ($domainData['status'] == 'Y' && $curdate >= $startdate &&  $curdate <= $enddate) {
            $use_domain = true;
        }*/
    }
} /*else {
    $use_domain = true;
}*/
if ($domainData['status'] == "N") { ?>
    <script>
        if (Gesi_getCookie('Memo1') != 'done')
            newpop1('payment_pop.php?index=' + '<?= $pay_data['orderNumber'] ?>');
    </script>
<? }/* else if ($pay_data['stop_yn'] == "Y" || $pay_data['end_status'] == "N") { ?>
    <script>
        if (Gesi_getCookie('Memo1') != 'done')
            newpop1('payment_pop.php?index=' + '<?= $pay_data['orderNumber'] ?>' + '&type=user');
    </script>
<?
}
*/

$sql = "select * from Gn_Ad_Manager where ad_position = 'H' and use_yn ='Y'";
$res_result = mysqli_query($self_con,$sql);
$ad_data = mysqli_fetch_array($res_result);
if ($domainData['status'] == "N") {
    $join_link = "ma.php";
} else if ($_REQUEST['mem_code']) {
    $sql_recom_id = "select mem_id from Gn_Member where mem_code='{$_REQUEST['mem_code']}'";
    $res_recom_id = mysqli_query($self_con,$sql_recom_id);
    $row_recom_id = mysqli_fetch_array($res_recom_id);
    if ($HTTP_HOST != "kiam.kr") {
        $join_link = get_join_link("http://" . $HTTP_HOST, $row_recom_id['mem_id'],"");
    } else {
        $join_link = get_join_link("http://www.kiam.kr", $row_recom_id['mem_id'],"");
    }
    //$join_link = "join.php?recom_id=" . $row_recom_id['mem_id'];
} else {
    if ($HTTP_HOST != "kiam.kr") {
        $join_link = get_join_link("http://" . $HTTP_HOST, "","");
    } else {
        $join_link = get_join_link("http://www.kiam.kr", "","");
    }
    //$join_link = "join.php";
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <? if ($sub_domain == true) {
        if ($domainData['site_name']) { ?>
            <title><?= $domainData['site_name']; ?></title>
            <meta name="description" content="" <?php echo $domainData['site_name']; ?>"" />
            <meta name="keywords" content="<?php echo $domainData['keywords']; ?>" />
            <meta name="naver-site-verification" content="<?php echo $domainData['naver-site-verification']; ?>" />
        <? }
    } else { ?>
        <title>온리원플랫폼 - 홈피형 명함 SNS플랫폼입니다</title>
        <meta name="description" content="온리원문자, 온리원셀링,PC-스마트폰 연동문자, 대량문자, 장문/포토 모두0.7원, 수신거부/수신동의/없는번호/번호변경 자동관리, 수신처관리" />
        <meta name="keywords" content="온리원문자, 온리원셀링,PC-스마트폰 연동문자, 대량문자, 장문/포토 모두0.7원, 수신거부/수신동의/없는번호/번호변경 자동관리, 수신처관리" />
    <? } ?>
    <!--<link href='<?= $path ?>css/nanumgothic.css' rel='stylesheet' type='text/css'/>-->
    <link href='<?= $path ?>css/main.css' rel='stylesheet' type='text/css' />
    <link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
    <link href='<?= $path ?>css/responsive.css' rel='stylesheet' type='text/css' /><!-- 2019.11 반응형 CSS -->
    <link href='<?= $path ?>css/font-awesome.min.css' rel='stylesheet' type='text/css' /><!-- 2019.11 반응형 CSS -->
    <script language="javascript" src="<?= $path ?>js/jquery-2.1.0.js"></script>
    <script language="javascript" src="<?= $path ?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
    <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
    <script type="text/javascript" src="/jquery.cookie.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <!--<script async src="https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE"></script>-->
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-3E40Q09QGE');


        $(function() {
            $('.sub_menu').hide();
            $('.main_link').on("hover", function() {
                $('.sub_menu').hide();
                var index = $(".main_link").index(this);
                $(".main_link:eq(" + index + ")").parent().find("ul").show();
            });
            $('.head_right_2').on("mouseout", function() {
                //$('.sub_menu').delay(5000).hide(0);
            });
            $('.main_link').on("click", function() {
                $('.sub_menu').hide();
                var index = $(".main_link").index(this);
                $(".main_link:eq(" + index + ")").parent().find("ul").show();
            });
            $('.head_left, .head_right_1, .container').on("mouseover", function() {
                $('.sub_menu').hide();
            });
            $('.sub_menu').on("mouseleave", function() {
                $('.sub_menu').hide();
            });
            $('.b_exit').on("click", function() {
                $('.ad_header').hide();
            });
        });

        function parent_alert(msg) {
            alert(msg);
        }
    </script>
</head>

<body>
    <? if ($HTTP_HOST !== "dbstar.kiam.kr" && $HTTP_HOST !== "mts.kiam.kr" && $HTTP_HOST !== "kookmin.kiam.kr" && $HTTP_HOST !== "election.kiam.kr" && $HTTP_HOST !== "onemunja.kiam.kr") { ?>
        <!--
    <div class="ad_header">
        <? $i = 0;
        $ad_cnt = mysqli_num_rows($res_result);
        do { ?>
            <a href="<? echo $ad_data['move_url'] ?>">
                <img class="ad_img_banner" id="banner_<? echo $i ?>" src='<? echo $ad_data['img_url'] ?>'>
            </a>
            <a href="<? echo $ad_data['move_url'] ?>">
                <img class="ad_img_banner" id="banner_<? echo $i + $ad_cnt ?>" src='<? echo $ad_data['img_url'] ?>'>
            </a>
            <? $i++;
        } while ($ad_data = mysqli_fetch_array($res_result)); ?>
        <div id="b_exit" class="b_exit"><img src="/images/bt_close.gif"></div>
    </div>
    -->
        <header style="background: #D8D8D8;">
            <div class="h_div" style="background-color: #D8D8D8;height: 35px;">
                <div class="d_head_right_1" style="overflow-x:auto; white-space: nowrap; margin-left:20px;line-height:25px;color:#535353;">
                    <a href="/event/event.html?pcode=KYUFA8kKuJ&sp=KYUFA8kKuJ&landing_idx=4054" target="_blank">전국강연회</a>
                    |
                    <a href="https://www.youtube.com/channel/UCw5r6QTl6JTbzIQlUhIiOjA" target="_blank">온리원TV</a>
                    |
                    <a href="https://oog.kiam.kr/pages/page_4243.php" target="_blank">코칭과정</a>
                    |
                    <a href="https://tinyurl.com/23r5z8wr" target="_blank">사업권소개</a>
                    |
                    <a href="https://tinyurl.com/y7czh8j4" target="_blank">사업자지원</a>
                    |
                    <a href="https://tinyurl.com/yeykfzee" target="_blank">설명회운영</a>&nbsp;
                </div>
                <p style="clear:both;"></p>
            </div>
        </header>
    <? } ?>
    <header class="big_h_f" id="header">
        <div class="h_div" style="background-color: #24303e;">
            <div class="head_left" style="width: 225px;">
                <? if (strpos($HTTP_HOST, "onefestival") !== false) { ?>
                    <a href=""><img src="images/onnury_logo.jpg" /></a>
                <? } else { ?>
                    <?php if ($sub_domain == true) { ?>
                        <?
                        if ($domainData['logo'] && $HTTP_HOST != 'kiam.kr') { ?>
                            <a href=""><img src="<?= $domainData['logo'] ?>" style="width:252px;height:70px" /></a>
                        <? } ?>
                    <?php } else { ?>
                        <a href=""><img src="images/only_m_lo_03-1.png" style="width:252px;height:70px" /></a>
                    <?php } ?>
                <? } ?>
            </div>
            <div class="head_right">
                <div class="head_right_1">
                    <form name="login_form" action="<?= $domainData['status'] == "N" ? '' : 'ajax/login.php' ?>" method="post" target="login_iframe" onsubmit="return login_check(login_form)">
                        <?
                        $server_name = $_SERVER['SERVER_NAME'];
                        if ($server_name == 'mcube.kiam.kr')
                            echo '<a href="https://knowhowseller.notion.site/200-f1ec38dc49a640498f1d3faf338df072" target="_blank">이용매뉴얼</a> |';
                        if ($sub_domain == true) {
                            if ($HTTP_HOST != "dbstar.kiam.kr") { ?>
                                <? if ($is_pay_version) { ?><a href="https://tinyurl.com/hb2pp6n2" target="_blank">이용매뉴얼(필독)</a> |
                                    <a href="/cliente_list.php?status=1">공지사항</a> |<? } ?>
                                    <a href=<?= $domainData['kakao'] ?> target="_blank">카톡상담</a> |
                                <? }
                        } else { ?>
                                <? if ($is_pay_version) { ?><a href="https://tinyurl.com/hb2pp6n2" target="_blank">이용매뉴얼</a> |
                                    <a href="/cliente_list.php?status=1">공지사항</a> |<? } ?>
                                    <a href="https://pf.kakao.com/_jVafC/chat" target="_blank">카톡상담</a> |
                                <? } ?>
                                <? if (!$_SESSION['one_member_id']) { ?>
                                    <a href="<?= $join_link ?>">회원가입</a> |
                                    <a href="<?= $domainData['status'] == "N"?'ma.php' : 'id_pw.php' ?>">아이디/비밀번호찾기</a>&nbsp;
                                    <iframe name="login_iframe" style="display:none;"></iframe>
                                    <input type="text" name="one_id" itemname='아이디' placeholder="아이디" required style="width:100px;" />
                                    <input type="password" name="one_pwd" itemname='비밀번호' placeholder="비밀번호" style="width:100px;" required />
                                    <input type="image" src="images/main_top_button_03.jpg" />
                                <? } else { ?>
                                    <? if ($is_pay_version) { ?><a href="mypage.php">마이페이지</a>&nbsp;|<? } ?>
                                        <!--| <a href="mypage_link_list.php">원스텝문자</a>&nbsp;-->
                                        <?
                                        if ($_SESSION['one_member_id'] == "db") {
                                        ?>
                                            <a href="/admin/crawler_member_list_v.php">관리자</a>&nbsp;
                                        <?php } ?>
                                        <?
                                        if ($_SESSION['one_member_id'] == "sungmheo") {
                                        ?>
                                            <a href="/admin/gwc_payment_list.php">관리자</a>&nbsp;
                                        <?php } ?>
                                        <?
                                        if (($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) || $_SESSION['one_member_admin_id'] != "") {
                                        ?>
                                            <? if ($_SESSION['one_member_admin_id'] == "emi0542" || $_SESSION['one_member_admin_id'] == "gwunki") { ?>
                                                <a href="/admin/member_list_con.php">관리자</a>
                                            <? } else { ?>
                                                <a href="/admin/member_list.php">관리자</a>&nbsp;
                                            <? } ?>
                                        <?php } ?>
                                        <? if ($_SESSION['one_member_id'] == "obmms01") { ?>
                                            <a href="permit_number.php">승인처리</a>&nbsp;
                                        <? } ?>
                                        <? if ($_SESSION['one_member_id'] == "lecturem") { ?>
                                            <a href="/admin/lecture_list.php">관리자</a>&nbsp;
                                        <? } ?>
                                        <span style="background-color:#43515e;padding:2px 20px 2px 5px;"><?= $member_1['mem_name'] ?> 님 환영합니다.</span>
                                        <a href="javascript:void(0)" onclick="logout()"><img src="images/main_top_button_logout_03.jpg" /></a>
                                    <? } ?>
                    </form>
                </div>
            </div>
            <p style="clear:both;"></p>
            <? if (strpos($HTTP_HOST, "1111.kiam.kr") !== false) { ?>
                <style>
                    .header-gnb {
                        position: relative;
                        padding-bottom: 20px;
                        left: 25%;
                    }

                    .header-gnb:after {
                        content: '';
                        position: absolute;
                        top: 38px;
                        left: 50%;
                        width: 100vw;
                        height: 0px;
                        background-color: #fff;
                        opacity: 0;
                        visibility: hidden;
                        z-index: 1;
                        -webkit-box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        -webkit-transform: translateX(-50%);
                        -ms-transform: translateX(-50%);
                        -o-transform: translateX(-50%);
                        transform: translateX(-50%);
                    }
                </style>
                <nav class="header-gnb"><!-- Menu GNB -->
                    <div class="gnb-container" style="text-align:center;">
                        <ul>
                            <li class="menu-item">
                                <a href="sub_5.php">휴대폰등록</a>
                            <li class="menu-item">
                                <a href="sub_6.php">문자발송</a>
                            </li>
                            <li class="menu-item">
                                <a href="sub_4_return_.php">발신내역</a>
                            </li>
                            <li class="menu-item">
                                <a href="sub_4.php?status=5&status2=3">수신내역</a>
                            </li>
                            <li class="menu-item">
                                <a href="sub_4.php?status=6">수신여부</a>
                            </li>
                            <? if ($is_pay_version) { ?>
                                <li class="menu-item">
                                    <a href="pay.php">결제안내</a>
                                </li>
                            <? } ?>
                        </ul>
                    </div>
                </nav><!-- // Menu GNB -->
            <? } else if ($HTTP_HOST == "dbstar.kiam.kr") { ?>
                <style>
                    .header-gnb {
                        position: relative;
                        padding-bottom: 20px;
                        left: 25%;
                    }

                    .header-gnb:after {
                        content: '';
                        position: absolute;
                        top: 38px;
                        left: 50%;
                        width: 100vw;
                        height: 0px;
                        background-color: #fff;
                        opacity: 0;
                        visibility: hidden;
                        z-index: 1;
                        -webkit-box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        -webkit-transform: translateX(-50%);
                        -ms-transform: translateX(-50%);
                        -o-transform: translateX(-50%);
                        transform: translateX(-50%);
                    }
                </style>
                <nav class="header-gnb"><!-- Menu GNB -->
                    <div class="gnb-container" style="text-align:center;font-size:100px;">
                        <ul>
                            <li class="menu-item">
                                <a href="/app/onlyonedber.msi">디버설치</a>
                            </li>
                            <li class="menu-item">
                                <a href="/mypage_link_list.php">고객신청관리</a>
                            </li>
                            <li class="menu-item">
                                <a href="/mypage_landing_list.php">랜딩페이지</a>
                            </li>
                            <li class="menu-item">
                                <a href="/sub_6_dbstar.php">포토문자</a>
                            </li>
                            <!--li class="menu-item" id="msms">
                                <a href="/sub_16.php">웹문자</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="/sub_16.php">웹문자소개</a></li>
                                    <li class="submenu-item"><a href="/link.php" target="_target">웹문자접속</a></li>
                                </ul>
                            </li-->
                            <li class="menu-item">
                                <a href="http://www.smsallline.com/home/login" target="_balnk">국제문자</a>
                            </li>
                        </ul>
                    </div>
                </nav><!-- // Menu GNB -->
            <? } else if ($HTTP_HOST == "mts.kiam.kr") { ?>
                <style>
                    .header-gnb {
                        position: relative;
                        padding-bottom: 20px;
                        left: 5%;
                    }

                    .header-gnb:after {
                        content: '';
                        position: absolute;
                        top: 38px;
                        left: 50%;
                        width: 100vw;
                        height: 0px;
                        background-color: #fff;
                        opacity: 0;
                        visibility: hidden;
                        z-index: 1;
                        -webkit-box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        -webkit-transform: translateX(-50%);
                        -ms-transform: translateX(-50%);
                        -o-transform: translateX(-50%);
                        transform: translateX(-50%);
                    }
                </style>
                <nav class="header-gnb"><!-- Menu GNB -->
                    <div class="gnb-container" style="text-align:center;font-size:100px;">
                        <ul>
                            <li class="menu-item" id="msub8">
                                <a href="sub_8.php">솔루션시작</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="sub_8.php">셀링솔루션소개</a></li>
                                    <li class="submenu-item"><a href="https://tinyurl.com/hb2pp6n2" target="_blank">매뉴얼따라하기</a></li>
                                </ul>
                            </li>
                            <li class="menu-item" id="msub10">
                                <a href="sub_10.php">아이엠</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="sub_10.php">아이엠소개</a></li>
                                    <li class="submenu-item"><a href="/?cur_win=best_sample" target="_blank">아이엠샘플</a></li>
                                    <li class="submenu-item"><a href="<?= $href ?>" target="_blank">아이엠접속</a></li>
                                </ul>
                            </li>
                            <li class="menu-item" id="msub2">
                                <a href="sub_2.php">디비수집</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="sub_2.php">디버알아보기</a></li>
                                    <li class="submenu-item"><a href="/cliente_list.php?status=1&one_no=85">디버설치하기</a></li>
                                    <li class="submenu-item"><a href="https://tinyurl.com/2p8ehzsm" target="_blank">디비수집하기</a></li>
                                </ul>
                            </li>
                            <li class="menu-item" id="msub15">
                                <a href="sub_11.php">콜백문자</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="sub_11.php">콜백알아보기</a></li>
                                    <li class="submenu-item"><a href="https://tinyurl.com/3ucs2vbt" target="_biank">콜백설치하기</a></li>
                                    <li class="submenu-item"><a href="https://tinyurl.com/3teh9ez5" target="_blank">콜백이용하기</a></li>
                                </ul>
                            </li>
                            <li class="menu-item" id="msub1">
                                <a href="sub_1.php">폰문자</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="sub_1.php">폰문자소개</a></li>
                                    <li class="submenu-item"><a href="sub_5.php">휴대폰등록</a></li>
                                    <li class="submenu-item"><a href="sub_9.php#Introduce">메시지카피</a></li>
                                    <li class="submenu-item"><a href="sub_6.php">폰문자발송</a></li>
                                    <li class="submenu-item"><a href="daily_list.php">데일리발송</a></li>
                                    <li class="submenu-item"><a href="sub_4_return_.php">발신내역보기</a></li>
                                    <li class="submenu-item"><a href="sub_4.php?status=5&status2=3">수신내역보기</a></li>
                                    <li class="submenu-item"><a href="sub_4.php?status=6">수신여부보기</a></li>
                                </ul>
                            </li>
                            <li class="menu-item" id="msub12">
                                <a href="/sub_12.php">스텝문자</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="/sub_12.php">원스텝소개</a></li>
                                    <?php if ($pay_data['onestep1'] != "ON" && $iam_type != 2) { ?>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">랜딩페이지</a></li>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">고객신청창</a></li>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">스텝예약관리</a></li>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">신청고객관리</a></li>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">기존고객관리</a></li>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">발송내역관리</a></li>
                                    <?php } else { ?>
                                        <li class="submenu-item"><a href="mypage_landing_list.php">랜딩페이지</a></li>
                                        <li class="submenu-item"><a href="/mypage_link_list.php">고객신청창</a></li>
                                        <li class="submenu-item"><a href="/mypage_reservation_list.php">스텝예약관리</a></li>
                                        <li class="submenu-item"><a href="/mypage_request_list.php">신청고객관리</a></li>
                                        <li class="submenu-item"><a href="/mypage_oldrequest_list.php">기존고객관리</a></li>
                                        <li class="submenu-item"><a href="/mypage_wsend_list.php">발송내역관리</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <!--li class="menu-item" id="msms">
                                <a href="/sub_16.php">웹문자</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="/sub_16.php">웹문자소개</a></li>
                                    <li class="submenu-item"><a href="/link.php" target="_blank">웹문자접속</a></li>
                                </ul>
                            </li-->
                            <li class="menu-item" id="msms">
                                <a href="http://mk5.kr" target="_balnk">웹문자</a>
                            </li>
                            <!--li class="menu-item">
                                <a href="http://www.smsallline.com/home/login" target="_balnk">국제문자</a>
                            </!--li>
                            </li-->
                        </ul>
                    </div>
                </nav><!-- // Menu GNB -->
            <? } else if ($HTTP_HOST == "election.kiam.kr") { ?>
                <style>
                    .header-gnb {
                        position: relative;
                        padding-bottom: 20px;
                        left: 25%;
                    }

                    .header-gnb:after {
                        content: '';
                        position: absolute;
                        top: 38px;
                        left: 50%;
                        width: 100vw;
                        height: 0px;
                        background-color: #fff;
                        opacity: 0;
                        visibility: hidden;
                        z-index: 1;
                        -webkit-box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        -webkit-transform: translateX(-50%);
                        -ms-transform: translateX(-50%);
                        -o-transform: translateX(-50%);
                        transform: translateX(-50%);
                    }
                </style>
                <nav class="header-gnb"><!-- Menu GNB -->
                    <div class="gnb-container" style="text-align:center;font-size:100px;">
                        <ul>
                            <li class="menu-item">
                                <a href="sub_10.php">아이엠소개</a>
                            </li>
                            <li class="menu-item">
                                <a href="/app/onlyonedber.msi">디버설치</a>
                            </li>
                            <li class="menu-item">
                                <a href="/mypage_link_list.php">고객신청관리</a>
                            </li>
                            <li class="menu-item">
                                <a href="/mypage_landing_list.php">랜딩페이지</a>
                            </li>
                            <li class="menu-item">
                                <a href="/sub_6.php">포토문자발송</a>
                            </li>
                        </ul>
                    </div>
                </nav><!-- // Menu GNB -->
            <? } else if ($HTTP_HOST == "iam.kiam.kr") { ?>
                <style>
                    .header-gnb {
                        position: relative;
                        padding-bottom: 20px;
                        left: 25%;
                    }

                    .header-gnb:after {
                        content: '';
                        position: absolute;
                        top: 38px;
                        left: 50%;
                        width: 100vw;
                        height: 0px;
                        background-color: #fff;
                        opacity: 0;
                        visibility: hidden;
                        z-index: 1;
                        -webkit-box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        -webkit-transform: translateX(-50%);
                        -ms-transform: translateX(-50%);
                        -o-transform: translateX(-50%);
                        transform: translateX(-50%);
                    }
                </style>
                <nav class="header-gnb"><!-- Menu GNB -->
                    <div class="gnb-container" style="text-align:center;font-size:100px;">
                        <ul>
                            <li class="menu-item">
                                <a href="sub_10.php">아이엠소개</a>
                            </li>
                            <li class="menu-item">
                                <a href="/?cur_win=best_sample" target="_blank">아이엠샘플</a>
                            </li>
                            <li class="menu-item">
                                <a href="https://tinyurl.com/3ucs2vbt" target="_blank">아이엠설치</a>
                            </li>

                            <li class="menu-item">
                                <a href="<?= $href ?>">아이엠접속</a>
                            </li>
                            <li class="menu-item">
                                <a href="https://tinyurl.com/557nca2b" target="_blank">아이엠매뉴얼</a>
                            </li>
                        </ul>
                    </div>
                </nav><!-- // Menu GNB -->
            <? } else if ($_SERVER['HTTP_HOST'] == "db.kiam.kr") { ?>
                <style>
                    .header-gnb {
                        position: relative;
                        padding-bottom: 20px;
                        left: 25%;
                    }

                    .header-gnb:after {
                        content: '';
                        position: absolute;
                        top: 38px;
                        left: 50%;
                        width: 100vw;
                        height: 0px;
                        background-color: #fff;
                        opacity: 0;
                        visibility: hidden;
                        z-index: 1;
                        -webkit-box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        -webkit-transform: translateX(-50%);
                        -ms-transform: translateX(-50%);
                        -o-transform: translateX(-50%);
                        transform: translateX(-50%);
                    }
                </style>
                <nav class="header-gnb"><!-- Menu GNB -->
                    <div class="gnb-container" style="text-align:center;font-size:100px;">
                        <ul>
                            <li class="menu-item">
                                <a href="sub_2.php">디버소개</a>
                            </li>
                            <li class="menu-item">
                                <a href="https://url.kr/1ZK4Aq" target="_blank">디비수집영상</a>
                            </li>
                            <li class="menu-item">
                                <a href="https://url.kr/dkLIEv">디버설치안내</a>
                            </li>
                            <li class="menu-item">
                                <a href="https://tinyurl.com/3puyvnpd" target="_blank">매뉴얼보기</a>
                            </li>
                        </ul>
                    </div>
                </nav><!-- // Menu GNB -->
            <? } else if ($_SERVER['HTTP_HOST'] == "psms.kiam.kr") { ?>
                <style>
                    .header-gnb {
                        position: relative;
                        padding-bottom: 20px;
                        left: 25%;
                    }

                    .header-gnb:after {
                        content: '';
                        position: absolute;
                        top: 38px;
                        left: 50%;
                        width: 100vw;
                        height: 0px;
                        background-color: #fff;
                        opacity: 0;
                        visibility: hidden;
                        z-index: 1;
                        -webkit-box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        box-shadow: 0 5px 5px rgba(0, 0, 0, 0.15);
                        -webkit-transform: translateX(-50%);
                        -ms-transform: translateX(-50%);
                        -o-transform: translateX(-50%);
                        transform: translateX(-50%);
                    }
                </style>
                <nav class="header-gnb"><!-- Menu GNB -->
                    <div class="gnb-container" style="margin-left:-240px; text-align:center;font-size:100px;">
                        <ul>
                            <li class="menu-item">
                                <a href="sub_1.php">폰문자소개</a>
                            </li>
                            <li class="menu-item">
                                <a href="sub_5.php">휴대폰등록</a>
                            </li>
                            <li class="menu-item">
                                <a href="sub_6.php">폰문자발송</a>
                            </li>
                            <li class="menu-item">
                                <a href="sub_11.php">콜백문자발송</a>
                            </li>
                            <li class="menu-item">
                                <a href="daily_list.php">원스텝발송</a>
                            </li>
                            <li class="menu-item">
                                <a href="sub_4_return_.php">발신내역보기</a>
                            </li>
                            <li class="menu-item">
                                <a href="sub_4.php?status=5&status2=3">수신내역보기</a>
                            </li>
                            <li class="menu-item">
                                <a href="sub_4.php?status=6">수신여부보기</a>
                            </li>
                            <li class="menu-item">
                                <a href="https://url.kr/fJaxTN6" target="_blank">매뉴얼보기</a>
                            </li>
                        </ul>
                    </div>
                </nav><!-- // Menu GNB -->
            <? } else { ?>
                <nav class="header-gnb"><!-- Menu GNB -->
                    <div class="gnb-container" style="text-align:center;font-size:100px;">
                        <ul>
                            <li class="menu-item" id="msub8">
                                <a href="sub_8.php">솔루션시작</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="sub_8.php">셀링솔루션소개</a></li>
                                    <li class="submenu-item"><a href="https://tinyurl.com/hb2pp6n2" target="_blank">매뉴얼따라하기</a></li>
                                </ul>
                            </li>
                            <li class="menu-item" id="msub10">
                                <a href="sub_10.php">아이엠</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="sub_10.php">아이엠소개</a></li>
                                    <li class="submenu-item"><a href="/?cur_win=best_sample" target="_blank">아이엠샘플</a></li>
                                    <li class="submenu-item"><a href="<?= $href ?>" target="_blank">아이엠접속</a></li>
                                </ul>
                            </li>

                            <li class="menu-item" id="msub2">
                                <a href="sub_2.php">디비수집</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="sub_2.php">디버알아보기</a></li>
                                    <li class="submenu-item"><a href="/cliente_list.php?status=1&one_no=85">디버설치하기</a></li>
                                    <li class="submenu-item"><a href="https://tinyurl.com/2p8ehzsm" target="_blank">디비수집하기</a></li>
                                </ul>
                            </li>
                            <li class="menu-item" id="msub15">
                                <a href="sub_11.php">콜백문자</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="sub_11.php">콜백알아보기</a></li>
                                    <li class="submenu-item"><a href="https://tinyurl.com/3ucs2vbt" target="_biank">콜백설치하기</a></li>
                                    <li class="submenu-item"><a href="https://tinyurl.com/3teh9ez5" target="_blank">콜백이용하기</a></li>
                                </ul>
                            </li>
                            <li class="menu-item" id="msub1">
                                <a href="sub_1.php">폰문자</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="sub_1.php">폰문자소개</a></li>
                                    <li class="submenu-item"><a href="sub_5.php">휴대폰등록</a></li>
                                    <li class="submenu-item"><a href="sub_9.php#Introduce">메시지카피</a></li>
                                    <li class="submenu-item"><a href="sub_6.php">폰문자발송</a></li>
                                    <li class="submenu-item"><a href="daily_list.php">데일리발송</a></li>
                                    <li class="submenu-item"><a href="sub_4_return_.php">발신내역보기</a></li>
                                    <li class="submenu-item"><a href="sub_4.php?status=5&status2=3">수신내역보기</a></li>
                                    <li class="submenu-item"><a href="sub_4.php?status=6">수신여부보기</a></li>
                                </ul>
                            </li>
                            <li class="menu-item" id="msub12">
                                <a href="/sub_12.php">스텝문자</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="/sub_12.php">원스텝소개</a></li>
                                    <?php if ($pay_data['onestep1'] != "ON" && $iam_type != 2) { ?>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">랜딩페이지</a></li>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">고객신청창</a></li>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">스텝예약관리</a></li>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">신청고객관리</a></li>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">기존고객관리</a></li>
                                        <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">발송내역관리</a></li>
                                    <?php } else { ?>
                                        <li class="submenu-item"><a href="mypage_landing_list.php">랜딩페이지</a></li>
                                        <li class="submenu-item"><a href="/mypage_link_list.php">고객신청창</a></li>
                                        <li class="submenu-item"><a href="/mypage_reservation_list.php">스텝예약관리</a></li>
                                        <li class="submenu-item"><a href="/mypage_request_list.php">신청고객관리</a></li>
                                        <li class="submenu-item"><a href="/mypage_oldrequest_list.php">기존고객관리</a></li>
                                        <li class="submenu-item"><a href="/mypage_wsend_list.php">발송내역관리</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <!--li class="menu-item" id="msms">
                                <a href="/sub_16.php">웹문자</a>
                                <ul class="submenu">
                                    <li class="submenu-item"><a href="/sub_16.php">웹문자소개</a></li>
                                    <li class="submenu-item"><a href="/link.php" target="_blank">웹문자접속</a></li>
                                </ul>
                            </li-->
                            <li class="menu-item" id="msms">
                                <a href="http://mk5.kr" target="_balnk">웹문자</a>
                            </li>
                            <!--li class="menu-item">
                                <a href="http://www.smsallline.com/home/login" target="_balnk">국제문자</a>
                            </!--li>
                            </li-->
                            <? if ($is_pay_version) { ?>
                                <li class="menu-item" id="mpay">
                                    <a href="pay.php">결제안내</a>
                                </li>
                            <? } ?>
                        </ul>
                    </div>
                </nav><!-- // Menu GNB -->
            <?php } ?>
            <a href="#" id="menuToggle"><!-- 모바일 메뉴 토글 버튼 -->
                <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="bars" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-bars fa-w-14 fa-7x">
                    <path fill="currentColor" d="M436 124H12c-6.627 0-12-5.373-12-12V80c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12z" class=""></path>
                </svg>
            </a><!-- // 모바일 메뉴 토글 버튼 -->
        </div>
    </header>
    <div class="big_1 head_breadcrumb" style="display:none;width:100%;position:absolute;">
        <div class="m_head_div" id="sub1" style="position:relative;">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="sub_1.php">온리원문자</a> >
                <a href="?status=<?= $_REQUEST['status'] ?>"><?= $left_str ?></a>
            </div>
            <div style="position:absolute;left:450px;">
                <a href="sub_1.php">폰문자소개</a> ㅣ
                <a href="sub_5.php">휴대폰등록</a> ㅣ
                <a href="sub_6.php">문자발송</a> ㅣ
                <? if ($mem_type == "V") { ?>
                    <a href="sub_6_elc.php">선거문자</a> ㅣ
                <? } ?>
                <a href="sub_4_return_.php">발신내역</a> ㅣ
                <a href="sub_4.php?status=5&status2=3">수신내역</a> ㅣ
                <a href="sub_4.php?status=6">수신여부</a>
            </div>
            <p style="clear:both;"></p>
        </div>
        <div class="m_head_div" id="sub10" style="position:relative;">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="sub_10.php">아이엠</a>
            </div>
            <div style="position:absolute;left:200px;">
                <a href="sub_10.php">아이엠소개</a> ㅣ
                <a href="/?cur_win=best_sample" target="_blank">아이엠샘플</a> ㅣ
                <a href="https://tinyurl.com/3ucs2vbt" target="_blank">아이엠설치</a> ㅣ
                <a href="<?= $href ?>" target="_blank">아이엠접속</a> ㅣ
                <a href="https://tinyurl.com/557nca2b" target="_blank">아이엠매뉴얼</a> |
                <?
                $sql_chk = "select count(a.mem_code) as cnt from Gn_Member a inner join Gn_Iam_Service b on a.mem_id=b.mem_id where a.service_type>=2 and a.mem_id='{$_SESSION['one_member_id']}'";
                echo $sql_chk."<br>";
                $res_chk = mysqli_query($self_con,$sql_chk);
                echo json_encode($res_chk)."<br>";
                $row_chk = mysqli_fetch_array($res_chk);
                echo json_encode($row_chk)."<br>";
                if ($row_chk[0] || $_SESSION['one_member_id'] == 'obmms02') {
                ?>
                    <a href="calliya.php">콜이야</a>
                <? } ?>
            </div>
            <p style="clear:both;"></p>
        </div>
        <div class="m_head_div" id="sub2" style="position:relative;">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="sub_2.php">온리원디버</a> >
                <a href="?status=<?= $_REQUEST['status'] ?>"><?= $left_str ?></a>
            </div>
            <div style="position:absolute;left:320px;">
                <a href="sub_2.php">디버알아보기</a> |
                <a href="/cliente_list.php?status=1&one_no=85">디버설치하기</a> |
                <a href="https://tinyurl.com/2p8ehzsm" target="_blank">디버수집하기</a>
            </div>
            <p style="clear:both;"></p>
        </div>
        <div class="m_head_div" id="sub15" style="position:relative;">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="sub_11.php">온리원콜백</a> >
                <a href="?status=<?= $_REQUEST['status'] ?>"><?= $left_str ?></a>
            </div>
            <div style="position:absolute;left:430px;">
                <a href="sub_11.php">콜백알아보기</a> ㅣ <a href="https://tinyurl.com/3ucs2vbt" target="_biank">콜백설치하기</a>ㅣ <a href="https://tinyurl.com/3teh9ez5" target="_blank">콜백이용하기</a>
            </div>
            <p style="clear:both;"></p>
        </div>
        <div class="m_head_div" id="sub12">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="/sub_12.php">스텝발송</a>
            </div>
            <div class="right_sub_menu">&nbsp;
                <a href="/sub_12.php">스텝문자소개</a> |
                <? if ($pay_data['onestep1'] != "ON" && $iam_type != 2) { ?>
                    <a onclick="alert('결제 후 사용가능합니다.');">랜딩관리</a> |
                    <a onclick="alert('결제 후 사용가능합니다.');">신청관리</a> |
                    <a onclick="alert('결제 후 사용가능합니다.');">고객관리</a> |
                    <a onclick="alert('결제 후 사용가능합니다.');">예약관리</a> |
                    <a onclick="alert('결제 후 사용가능합니다.');">데일리발송</a>
                    <!-- <a onclick="alert('결제 후 사용가능합니다.');">기존고객관리</a> |
                    <a onclick="alert('결제 후 사용가능합니다.');">발송예정내역</a> |
                    <a onclick="alert('결제 후 사용가능합니다.');">발송결과내역</a> -->

                <? } else { ?>
                    <a href="mypage_landing_list.php">랜딩관리</a> |
                    <a href="/mypage_link_list.php">신청관리</a> |
                    <a href="/mypage_request_list.php">고객관리</a> |
                    <a href="/mypage_reservation_list.php">예약관리</a> |
                    <a href="/daily_list.php">데일리발송</a>
                    <!-- <a href="/mypage_oldrequest_list.php">기존고객관리</a> |
                    <a href="/mypage_wsend_list.php">발송예정내역</a> |
                    <a href="/mypage_send_list.php">발송결과내역</a> -->
                <? } ?>
            </div>
            <p style="clear:both;"></p>
        </div>
        <div class="m_head_div" id="sub13">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="sub_13.php">온리원쇼핑</a>
            </div>
            <div class="right_sub_menu">
                <a href="http://onlyonemall.net/">온리원쇼핑몰</a> |
                <a href="sub_13.php">국내제휴쇼핑</a> ㅣ
                <a href="/?cCT6LD1no7">해외제휴쇼핑</a> |
                <a href="http://kims3925.onlyonemall.net/">바자회쇼핑몰</a>
            </div>
            <p style="clear:both;"></p>
        </div>
        <!--
        <div class="m_head_div" id="sub11" style="position:relative;">
			<div class="left_sub_menu">
			    <a href="./">홈</a> >
			    <a href="sub_11.php">온리원콜백</a>
			</div>
			<div style="position:absolute;left:430px;">
			    <a href="sub_11.php">콜백알아보기</a> ㅣ <a href="https://tinyurl.com/3ucs2vbt" target="_biank">콜백설치하기</a> ㅣ <a href="https://tinyurl.com/4j8ez8x3" target="_blank">콜백이용하기</a>
			</div>
			<p style="clear:both;"></p>
        </div> -->
        <div class="m_head_div" id="sub8" style="position:relative;">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="sub_8.php">솔루션소개</a> >
                <a href="?status=<?= $_REQUEST['status'] ?>"><?= $left_str ?></a>
            </div>
            <div style="position:absolute;left:140px;">
                <a href="sub_8.php#Introduce">셀링솔루션소개</a> ㅣ
                <a href="https://tinyurl.com/hb2pp6n2" target="_blank">매뉴얼따라하기</a>
            </div>
            <p style="clear:both;"></p>
        </div>
        <div class="m_head_div" id="sms" style="position:relative;">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="http://mk5.kr" target="_balnk">웹문자</a> >
            </div>
            <!--div style="position:absolute;left:770px;">
                <a href="/sub_16.php">웹문자소개</a> ㅣ <a href="/link.php" target="_blank">웹문자접속</a>
            </div-->
            <p style="clear:both;"></p>
        </div>
        <div class="m_head_div" id="isms" style="position:relative;">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="http://www.smsallline.com/home/login">국제문자</a> >
            </div>
            <p style="clear:both;"></p>
        </div>
        <div class="m_head_div" id="head_pay" style="position:relative;">
            <? if ($is_pay_version) { ?>
                <div class="left_sub_menu">
                    <a href="./">홈</a> >
                    <a href="pay.php">결제안내</a>
                </div>
            <? } ?>
            <p style="clear:both;"></p>
        </div>
    </div>
    <script>
        //배너 처리부분
        var i = 0;
        while (1) {
            if (document.getElementById("banner_" + i)) {
                document.getElementById("banner_" + i).style.left = 1000 * i + "px";
                document.getElementById("banner_" + i).style.width = 1000 + "px";
            } else
                break;
            i++;
        }

        function pop_hide() {
            $(".popupbox").hide();
            //clearTimeout(tid);
            $('.big_1').show();
            $('.m_head_div').hide();
        }
        $('#mpay').mouseover(function() {
            pop_hide();
            $('#head_pay').show();
        });

        $('#msub12').mouseover(function() {
            pop_hide();
            $('#sub12').show();
        });
        $('#msub13').mouseover(function() {
            pop_hide();
            $('#sub13').show();
        });
        $('#msub15').mouseover(function() {
            pop_hide();
            $('#sub15').show();
        });
        $('#msub1').mouseover(function() {
            pop_hide();
            $('#sub1').show();
        });
        $('#msub10').mouseover(function() {
            pop_hide();
            $('#sub10').show();
        });
        $('#msub8').mouseover(function() {
            pop_hide();
            $('#sub8').show();
        });
        $('#msub2').mouseover(function() {
            pop_hide();
            $('#sub2').show();
        });
        $('#msms').mouseover(function() {
            pop_hide();
            $('#sms').show();
        });
        $('#misms').mouseover(function() {
            pop_hide();
            $('#isms').show();
        });
        $('.big_1').mouseleave(function() {
            $('.big_1').hide();
            $('.top_menu').show();
        });
        var inter = 0;
        var top_banner_speed = 30;
        setInterval(function() {
            var i = 0;
            var image_size = 1000;
            while (1) {
                if (document.getElementById("banner_" + i)) {
                    var temp = image_size * i - inter;
                    document.getElementById("banner_" + i).style.left = temp + "px";
                } else
                    break;
                i++;
            }
            inter += 2;
            if (inter >= image_size * (i / 2))
                inter = 0;
        }, top_banner_speed);
    </script>