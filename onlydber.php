<!--//head -->
<?
include_once $path . "lib/rlatjd_fun.php";
if ($_SESSION['one_member_id']) {
  $sql = "select * from tjd_pay_result where buyer_id = '$_SESSION[one_member_id]' and end_date > '$date_today' and end_status in ('Y','A') order by end_date desc limit 1";
  $res_result = mysql_query($sql);
  $pay_data = mysql_fetch_array($res_result);
}
$rights = 0;
if ($pay_data['TotPrice'] < "55000") {
  $rights = 1;
} else if ($pay_data['TotPrice'] == "55000") {
  $rights = 2;
} else if ($pay_data['TotPrice'] > "55000") {
  $rights = 3;
}
$rights = 3;
$sub_domain = false;


if ($HTTP_HOST == "kiam.kr")
  $host = "www.kiam.kr";
else
  $host = $HTTP_HOST;
$query = "select * from Gn_Service where sub_domain like '%{$host}'";
$res = mysql_query($query);
$domainData = mysql_fetch_array($res);
if ($HTTP_HOST != "kiam.kr") {
  if ($domainData['idx'] != "") {
    $sub_domain = true;
  }
}
if ($domainData['status'] == "N" || $pay_data['stop_yn'] == "Y") {
  echo "<script>location='/ma.php';</script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>온리원디버</title>
  <meta name="description" content="온리원디버, DB수집프로그램, 타겟DB수집가능" />
  <meta name="keywords" content="온리원디버, DB수집프로그램, 타켓디비수집가능" />


  <!--
    <link rel="shortcut icon" href="logo.ico">
-->
  <link href='<?= $path ?>css/nanumgothic.css' rel='stylesheet' type='text/css' />
  <link href='<?= $path ?>css/main.css' rel='stylesheet' type='text/css' />
  <link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
  <link href='<?= $path ?>css/responsive.css' rel='stylesheet' type='text/css' /><!-- 2019.11 반응형 CSS -->
  <link href='<?= $path ?>css/font-awesome.min.css' rel='stylesheet' type='text/css' /><!-- 2019.11 반응형 CSS -->
  <script language="javascript" src="<?= $path ?>js/jquery-1.7.1.min.js"></script>
  <script language="javascript" src="<?= $path ?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
  <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-3E40Q09QGE');
  </script>
  <script>
    $(function() {
      $('.main_link').hover(function() {
          $('.sub_menu').hide();
          var index = $(".main_link").index(this);
          $(".main_link:eq(" + index + ")").parent().find("ul").show();
        },
        function() {
          $('.sub_menu').hide();
        }
      );
      $('.main_link').on("hover", function() {
        $('.sub_menu').hide();
        var index = $(".main_link").index(this);
        $(".main_link:eq(" + index + ")").parent().find("ul").show();
        //       $(this).closest("ul").show();
        //$(this).closest("ul").css("background","yellow");
        //console.log($(this).closest('ul'));
      });
      $('.head_right_2').on("mouseout", function() {
        //$('.sub_menu').delay(5000).hide(0);
      });
      $('.main_link').on("click", function() {
        $('.sub_menu').hide();
        var index = $(".main_link").index(this);
        $(".main_link:eq(" + index + ")").parent().find("ul").show();
        //       $(this).closest("ul").show();
        //$(this).closest("ul").css("background","yellow");
        //console.log($(this).closest('ul'));
      });
      $('.head_left, .head_right_1, .container').on("mouseover", function() {
        $('.sub_menu').hide();
      });

      $('.sub_menu').on("mouseleave", function() {
        $('.sub_menu').hide();
      });
    });
  </script>
</head>
<!--<body>
    <header class="big_h_f" id="header">
       <div class="m_div" style="background-color: #24303e;">
        	<div class="head_left">
    		
    		</div>
            <div class="head_right">
            	<div class="head_right_1">                
                                             
                </div>
            </div>
    		<p style="clear:both;"></p>
            			
            <!--<nav class="header-gnb"><!-- Menu GNB -->
<!--<div class="gnb-container">
                    <ul>
                        <li class="menu-item">
                            <a href="sub_8.php">솔루션소개</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_8.php#Introduce">셀링솔루션이란?</a></li>
                                <li class="submenu-item"><a href="sub_8.php#trait">셀링솔루션특징</a></li>
                                <li class="submenu-item"><a href="sub_8.php#technic">셀링솔루션기술</a></li>
                                <li class="submenu-item"><a href="sub_8.php#uses">셀링솔루션활용</a></li>
                                <li class="submenu-item"><a href="sub_8.php#construction">셀링솔루션구성</a></li>
                            </ul>
                        </li>
                        <li class="menu-item">
                            <a href="sub_10.php">아이엠</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_10.php#Introduce">아이엠이란</a></li>
                                <li class="submenu-item"><a href="sub_10.php#trait">아이엠특징</a></li>
                                <li class="submenu-item"><a href="sub_10.php#technic">아이엠기능</a></li>
                                <li class="submenu-item"><a href="sub_10.php#uses">아이엠활용</a></li>
                            </ul>
                        </li>
                        <li class="menu-item">
                            <a href="sub_11.php">온리원콜백</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_11.php#Introduce">온리원콜백이란</a></li>
                                <li class="submenu-item"><a href="sub_11.php#trait">온리원콜백특징</a></li>
                                <li class="submenu-item"><a href="sub_11.php#technic">온리원콜백기능</a></li>
                                <li class="submenu-item"><a href="sub_11.php#uses">온리원콜백활용</a></li>
                            </ul>
                        </li>
                        <li class="menu-item">
                            <a href="sub_2.php">온리원디버</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_2.php#Introduce">온리원디버란</a></li>
                                <li class="submenu-item"><a href="sub_2.php#trait">온리원디버특징</a></li>
                                <li class="submenu-item"><a href="sub_2.php#technic">온리원디버기능</a></li>
                                <li class="submenu-item"><a href="sub_2.php#uses">온리원디버활용</a></li>
                            </ul>
                        </li>
                        <li class="menu-item">
                            <a href="sub_9.php">메시지카피</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_9.php#Introduce">메시지카피란</a></li>
                                <li class="submenu-item"><a href="sub_9.php#trait">메시지카피특징</a></li>
                                <li class="submenu-item"><a href="sub_9.php#technic">메시지카피기술</a></li>
                                <li class="submenu-item"><a href="sub_9.php#effect">메시지카피효과</a></li>
                                <li class="submenu-item"><a href="sub_9.php#case">메시지카피사례</a></li>
                            </ul>
                        </li>   
                        <li class="menu-item">
                            <a href="sub_1.php">온리원문자</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_1.php">온리원문자란</a></li>
                                <li class="submenu-item"><a href="sub_5.php">휴대폰등록</a></li>
                                <li class="submenu-item"><a href="sub_6.php">문자발송</a></li>
                                <li class="submenu-item"><a href="sub_4_return_.php">발신내역</a></li>
                                <li class="submenu-item"><a href="sub_4.php?status=5&status2=3">수신내역</a></li>
                                <li class="submenu-item"><a href="sub_4.php?status=6">수신여부</a></li>
                            </ul>                                       
                        </li>
                        <li class="menu-item">
                            <a href="/sub_12.php">원스텝문자</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="/sub_12.php">원스텝문자란</a></li>
                                <li class="submenu-item"><a href="mypage_landing_list.php">랜딩페이지</a></li>
                                <li class="submenu-item"><a href="/mypage_link_list.php">DB신청창</a></li>
                                <li class="submenu-item"><a href="/mypage_reservation_list.php">스텝예약관리</a></li>
                                <li class="submenu-item"><a href="/daily_list.php">데일리발송</a></li>
                                <li class="submenu-item"><a href="/mypage_request_list.php">신청고객관리</a></li>
                                <li class="submenu-item"><a href="/mypage_wsend_list.php">스텝발신내역</a></li>
                                <li class="submenu-item"><a href="/mypage_send_list.php">스텝발송결과</a></li>
                            </ul>                   
                        </li>
                        <li class="menu-item">
                            <a href="/event/event.html?pcode=&sp=&landing_idx=296" target="_biank">온리원공유</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="javascript:;;" onclick="alert('준비중입니다.')">마이샵</a></li>
                                <li class="submenu-item"><a href="javascript:;;" onclick="alert('준비중입니다.')">온리원몰</a></li>
                                <li class="submenu-item"><a href="javascript:;;" onclick="alert('준비중입니다.')">온리원웹캠</a></li>
                                <li class="submenu-item"><a href="javascript:;;" onclick="alert('준비중입니다.')">온리원영상</a></li>
                                <li class="submenu-item"><a href="/event/event.html?pcode=&sp=&landing_idx=296" target="_biank">온리원강연</a></li>
                                <li class="submenu-item"><a href="javascript:;;" onclick="alert('준비중입니다.')">단축주소창</a></li>
                            </ul>                   
                        </li>
                        <li class="menu-item">
                            <a href="pay.php">결제안내</a>  
                            <ul class="submenu">
                                <li class="submenu-item"><a href="pay.php">결제안내</a></li>   
                            </ul>            
                        </li>
                    </ul>
                </div>
                
            </nav><!-- // Menu GNB -->





</div>
</header>

<script>
  $('.header-gnb').mouseover(function() {
    $(this).addClass('active');
  });
  $('.header-gnb').mouseleave(function() {
    $(this).removeClass('active');
  });
</script>

<!--head //-->

<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
<style>
  .container .vote_top .tab_box1 {
    position: absolute;
    top: 100px;
    right: px;
    overflow: hidden;
    width: 577px;
    height: 469px;
  }

  .container .vote_top .tab_box2 {
    position: absolute;
    top: 370px;
    right: 510px;
    overflow: ;
    width: 577px;
    height: 200px;
  }
</style>
<style>
  .index .midle .bg {
    position: absolute;
    overflow: hidden;
  }
</style>
<!-- 아래 이징 플러그인은 부드러운 모션 처리를 위해서 필요 -->
<script src="/js/jquery.easing.1.3.js"></script>
<script src="motionj.com.jBanner.1.2.js"></script>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script language="javascript">
  function show_login() {
    $('.ad_layer_login').lightbox_me({
      centered: true,
      onLoad: function() {}
    });
  }
</script>
<script type="text/javascript">
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

  function dbFree() {
    <?
    if ($_SESSION[one_member_id] == "") {
    ?>
      alert('로그인 이후에 이용이 가능합니다.');
      return;
    <?php } ?>

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
            alert('신청되었습니다. 디버 다운로드 후 이용해주세요.');
            return;
          }
        },
        error: function() {
          alert('저장 실패');
        }
      });
    } else {
      return false;
    }
  }
</script>
<!--<div class="big_1 breadcrumb">
  <div class="m_div">
    
    <div class="left_sub_menu">
      <a href="./">홈</a> >
      <a href="sub_1.php">온리원디버</a> >
      <a href="?status=<?= $_REQUEST[status] ?>"><?= $left_str ?></a>
    </div>
    <div class="right_sub_menu">     
      <a href="#Introduce">온리원디버란</a> ㅣ <a href="#trait">온리원디버특징</a> ㅣ <a href="#technic">온리원디버기능</a> ㅣ <a href="#uses">온리원디버활용</a> 
    </div>
    
    <p style="clear:both;"></p>
  </div>
</div>-->


<div class="container">


  <div class="sub-2-section">
    <div class="inner">
      <img src="images/200123_01.png">
    </div>

    <div class="sub-2-section">
      <div class="inner">
        <img src="images/200123_02.png">
      </div>
    </div>



    <div class="bnt main-buttons">
      <div class="wrap2" style="max-width: 849px; margin: 0 auto;">
        <div class="wrap2" style="max-width: 849px; margin: 0 auto;">
          <a href="/app/200116OnlyOneDB.exe" class="button" target="_blank">온리원디버 다운로드</a>
          <a href="https://goodhow.com/docs/191118_%EC%98%A8%EB%A6%AC%EC%9B%90%EB%94%94%EB%B2%84%20%EC%9D%B4%EC%9A%A9%20%EB%A7%A4%EB%89%B4%EC%96%BCv23.pdf" class="button" target="_blank">온리원디버 매뉴얼</a>
        </div>
      </div>

      <!--<div class="text-wrap">
      <p>
        온리원디버 프로그램은 브라우저 크롬에서 다운로드가 잘 되며,<br>
        컴퓨터 사양은 윈도우10 이상, 64비트 지원될 때 정상 작동합니다.<br>
        수집된 디비는 M/S 엑셀에 저장되기 때문에 M/S 엑셀 프로그램이 설치되어 있어야 합니다.
      </p>
    </div>
    <div class="button-wrap">
      <div class="inner-wrap clearfix">
        <div class="left">
          <div class="bnt3">
              <a href="/cliente_list.php?status=1&one_no=85" class="button" target="_blank">온리원디버 정식버전 다운로드</a>
          </div>
        </div>
        <div class="right">
          <div class="inner">
            <li class="right-text">온리원디버 정식판 이용방법</li>
            <li class="right-button"><a href="/cliente_list.php?status=1&one_no=85" target="_blank"><img src="images/sub_02_btn_26.jpg" /></a></li>
          </div>
        </div>
      </div>
    </div>-->
    </div>

    <div class="sub-2-section ">
      <!--<div class="im_title">
      <p><span class="colored">“타겟 대상별” “웹종류별”</span> 공개 <span class="colored"> 정보</span> 모두 수집!</p>
    </div>-->
      <div class="content-wrap">
        <div class="image-wrapper"><img src="images/200123_03.png"></div>

      </div>
    </div>

    <div class="sub-2-section ">
      <!--div class="im_title is-lh">
      <p class="fsz-14">
        온리원디버는 신뢰입니다.<br/>
        <span class="im_title colored">온리원디버는 합법적인 디비만 추출합니다.</span><br/>
        <span class="fsz-14">이미 온라인에 공개된 데이터를 온리원디버가 빠르게 수집합니다.</span><br/>
        <span class="fsz-14">알고리즘을 통해 타겟에 맞는 맞춤 DB로 추출하는 합법적인 프로그램입니다.</span>
      </p>
    </div-->
      <div class="content-upper">
        <div class="image-wrapper">
          <img src="images/200123_04.png">
        </div>
      </div>
      <div class="content-downer">
        <div class="image-wrapper">
          <img src="images/200123_05.png">
        </div>
      </div>
    </div>

    <div class="sub-2-section">
      <!-- <div class="im_title is-lh">
      <p class="fsz-14">
        지도, 카페, 블로그, 웹사이트,신문 등 각각 하던 <span class="fsz-16">DB수집을 한번에 해결!</span><br/>
        <span class="im_title colored">막강한 올인원 추출 마케팅 솔루션</span>
      </p>
    </div>-->
      <div class="content-wrap">
        <a name="technic"></a>
        <div class="image-wrapper">
          <img src="images/200123_06.png">
        </div>
      </div>
    </div>

    <div class="sub-2-section">
      <!--<div class="im_title is-lh">
      <p class="fsz-14">
        검색엔진의 검색키워드로 상세한 타겟팅 가능!<br/>
        <span class="im_title colored">검색키워드로 타겟팅하는 마케팅 솔루션</span>
      </p>
    </div>-->

      <div class="content-wrap">
        <div class="image-wrapper">
          <img src="images/200123_07.png">
        </div>
      </div>
    </div>

    <div class="sub-2-section inner">
      <div class="image-wrapper">
        <img src="images/200123_08.png">
      </div>
    </div>


    <div class="sub-2-section ">
      <div class="content-wrap">
        <div class="image-wrapper">
          <img src="images/200123_09.png">
        </div>

        <div class="sub-2-section">
          <div class="content-wrap">
            <div class="image-wrapper">
              <img src="images/200123_10.png">
            </div>

            <div class="sub-2-section">
              <div class="content-wrap">
                <div class="image-wrapper">
                  <img src="images/200123_12.png">
                </div>

                <div class="sub-2-section">
                  <div class="content-wrap">
                    <div class="image-wrapper">
                      <img src="images/200123_13.png">
                    </div>

                    <div class="sub-2-section sec-3">
                      <div class="im_title is-lh">
                        <p class="fsz-14">
                          온리원디버는 신뢰입니다.<br />
                          <span class="im_title colored">*온리원디버는 국내 유일한 종합 디비수집기 입니다.</span><br />
                          <span class="fsz-14">1.국내 유일! 네이버 8개의 웹종류 모두 수집 가능</span><br />
                          <span class="fsz-14">2.국내 유일! 블로그, 카페 등 글의 지문 속에서 타겟디비를 수집 가능</span><br />
                          <span class="fsz-14">2.국내 유일! 블로그, 카페 등 글의 지문 속에서 타겟디비를 수집 가능</span><br />
                          <span class="fsz-14">3.국내 유일! 이메일과 일반지역번호를 무제한 수집 가능</span><br />
                          <span class="fsz-14">4.국내 유일! 수집된 정보의 출처를 함께 수집 가능</span><br />
                          <span class="fsz-14">5.국내 유일! 네이버카페의 댓글까지 자동 수집 가능</span>
                        </p>
                      </div>
                    </div>

                    <div class="sub-2-section sec-5">
                      <div class="iframe-wrap">
                        <iframe width="900" height="500" src="https://www.youtube.com/embed/q3JtXbREnhY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

                      </div>
                    </div>
                    <!--<div class="bnt3">
        <div class="wrap2">
          <a href="/cliente_list.php?status=1&one_no=66" class="button" target="_blank">온리원디버 정식버전 다운로드</a>
        </div>
      </div>-->
                  </div>
                </div>
              </div>
              <?
              //include_once "_foot.php";
              ?>