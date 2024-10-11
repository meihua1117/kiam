<?
  $path="./";
  include_once "_head.php";
?>

<head>
<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css'/>
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
.index .midle .bg{
position: absolute;
overflow: hidden;
}
</style>
</head>

<!-- 아래 이징 플러그인은 부드러운 모션 처리를 위해서 필요 -->
<script src="/js/jquery.easing.1.3.js"></script>
<script src="motionj.com.jBanner.1.2.js"></script>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script language="javascript" >
  function show_login() {
    $('.ad_layer_login').lightbox_me({
      centered: true,
      onLoad: function() {
      }
    });
  }

</script>
<script type="text/javascript">
$(function(){
  $('.jBanner').jBanner({width:570, height:270});
});
  $(function () {
    tab('#tab',0);
});
function tab(e, num){
var num = num || 0;
var menu = $(e).children();
var con = $(e+'_con').children();
var select = $(menu).eq(num);
var i = num;
select.addClass('on');
con.eq(num).show();
menu.click(function(){
if(select!==null){
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
if($_SESSION['one_member_id'] == "")
{
?>
alert('로그인 이후에 이용이 가능합니다.');
return;
<?php } ?>

var msg = confirm('온리원 디버 무료체험 신청하시겠습니까? 300건의 무료 건수가 제공됩니다.');

if($('#password').val() != "") {
if($('#password').val() != $('#password_re').val()) {
alert('비밀번호를 확인해주세요');
return;
}
}
if(msg){
$.ajax({
type:"POST",
url:"/ajax/crawler_request.php",
dataType:"json",
success:function(result){
if(result.result == "fail") {
alert(result.msg)
} else {
alert('신청되었습니다. 디버 다운로드 후 이용해주세요.');
return;
}
},
error: function(){
alert('저장 실패');
}
});
}else{
return false;
}
}
</script>


<div class="container">
  
  <div class="sub-2-top">
   <a name="Introduce"></a>
    <div class="image-wrap">
      <img src="images/200102_02.png">
    </div>
    
     <div class="sub-2-bg-01 sub-2-section">        
        <div class="inner">
          <img src="images/200102_03.png">
        </div>
     </div>
      
 	<?php if($sub_domain == true) {?>
    <a name="install"></a>
    <div class="bnt main-buttons" style="background-color: #0B610B;padding-bottom:20px;">
      <div class="wrap2" style="max-width: 849px; margin: 0 auto;">
        <a href="/cliente_list.php?status=1&one_no=85" class="button" target="_blank">온리원디버 설치하기</a>
        <a href="https://tinyurl.com/2p8ehzsm" class="button" target="_blank" >온리원디버 이용매뉴얼</a>
      
        <a href="<?=$domainData['kakao'];?>" class="button2" target="_blank">카카오상담하기</a>
        <a href="https://tinyurl.com/yrnyd537" class="button" target="_blank">IAM플랫폼 통합매뉴얼</a>
      </div>
    </div>
    <?php }else{?>

     <a name="install"></a>
     <div class="bnt main-buttons" style="background-color: #0B610B;padding-bottom:20px;">     
      <div class="wrap2" style="max-width: 849px; margin: 0 auto;">
        <a href="/cliente_list.php?status=1&one_no=85" class="button" target="_blank">온리원디버 설치하기</a>
        <a href="https://tinyurl.com/2p8ehzsm" class="button" target="_blank" >온리원디버 이용매뉴얼</a>
      
        <a href="http://pf.kakao.com/_jVafC/chat" class="button2" target="_blank">카카오상담하기</a>
        <a href="https://tinyurl.com/yrnyd537" class="button" target="_blank">IAM플랫폼 통합매뉴얼</a>
      </div>
    </div>
    <? } ?>
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
  
<!--   <div class="sub-2-section sec-5">
    <div class="iframe-wrap">
     <iframe width="900" height="500" src="https://www.youtube.com/embed/9a7sqygX7wo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
  </div> -->
  
  <div class="sub-2-section ">
    <!--<div class="im_title">
      <p><span class="colored">“타겟 대상별” “웹종류별”</span> 공개 <span class="colored"> 정보</span> 모두 수집!</p>
    </div>-->
    <div class="content-wrap">
    <a name="trait"></a>
      <div class="image-wrapper"><img src="images/200330_06.png"></div>
      
    </div>
  </div>

  <div class="sub-2-section sec-2">
    <!--<div class="im_title">
      <p><span class="colored">“타겟 대상별” “웹종류별”</span> 공개 <span class="colored"> 정보</span> 모두 수집!</p>
    </div>-->
    <div class="content-wrap">
      <div class="image-wrapper"><img src="images/200102_04.png"></div>
      
    </div>
  </div>

  <div class="sub-2-section sec-3">
    <div class="im_title is-lh">
      <p class="fsz-14">
        온리원디버는 신뢰입니다.<br/>
        <span class="im_title colored">온리원디버는 합법적인 디비만 추출합니다.</span><br/>
        <span class="fsz-14">이미 온라인에 공개된 데이터를 온리원디버가 빠르게 수집합니다.</span><br/>
        <span class="fsz-14">알고리즘을 통해 타겟에 맞는 맞춤 DB로 추출하는 합법적인 프로그램입니다.</span>
      </p>
    </div>
    <div class="content-upper">
      <div class="image-wrapper">
        <img src="images/200102_10.png">
      </div>
    </div>
    <div class="content-downer">
      <div class="image-wrapper">
        <img src="images/200102_11.png">
      </div>
    </div>
  </div>

  <div class="sub-2-section sec-4">
    <!-- <div class="im_title is-lh">
      <p class="fsz-14">
        지도, 카페, 블로그, 웹사이트,신문 등 각각 하던 <span class="fsz-16">DB수집을 한번에 해결!</span><br/>
        <span class="im_title colored">막강한 올인원 추출 마케팅 솔루션</span>
      </p>
    </div>-->
    <div class="content-wrap">
    <a name="technic"></a>
      <div class="image-wrapper">
        <img src="images/200102_06.png">
      </div>
    </div>
  </div>

  <div class="sub-2-section sec-5">
   <!--<div class="im_title is-lh">
      <p class="fsz-14">
        검색엔진의 검색키워드로 상세한 타겟팅 가능!<br/>
        <span class="im_title colored">검색키워드로 타겟팅하는 마케팅 솔루션</span>
      </p>
    </div>-->
    
    <div class="content-wrap">
      <div class="image-wrapper">
        <img src="images/200102_07.png">
      </div>
     </div>
    </div>
     
    <div class="sub-2-section inner">
      <div class="image-wrapper">
        <img src="images/200102_08.png">
      </div>
    </div>
   
  
  

  <div class="sub-2-section sec-6">
    <div class="im_title is-green">온리원디버 활용분야</div>
    <a name="uses"></a>
    <div class="content-wrap">
      <div class="image-wrapper">
        <img src="images/200102_09.png">
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
  include_once "_foot.php";
?>