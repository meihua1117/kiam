<?
  $path="./";
  include_once "_head.php";
?>
<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css'/>
<style>
.container .vote_top .tab_box1 {
position: absolute;
top: 100px;
right: 510px;
overflow: hidden;
width: 577px;
height: 269px;
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
</script>

<div class="midle_div">
    
  <div class="sub-12-visual sub-12-section">
      <a name="Introduce"></a>
      <div class="inner">
        <img src="images/191229_02.png">
      </div>
    </div>
    
    <div class="sub-12-bg-01 sub-12-section">
        <a name="trait"></a>
        <div class="inner">
          <img src="images/191229_03.png">
        </div>
     
      
	<?php if($sub_domain != true) {?>
    <div class="bnt main-buttons" style="background-color: #585858; ">
      <div class="wrap2">
        <a href="https://tinyurl.com/26trbfnj" class="button" target="_blank">원스텝문자 이용매뉴얼</a><br />
        <a href="http://pf.kakao.com/_jVafC/chat" class="button2" target="_blank">카카오상담하기</a>
        <a href="https://tinyurl.com/yrnyd537" class="button" target="_blank">IAM플랫폼 통합매뉴얼</a>
      </div>
    </div>
    <? } ?>
    
    <div class="text-wrap" style="background-color: #585858; max-height:200px">
      <p>
        원스텝문자는 스텝문자발송 솔루션입니다. 본 솔루션을 통해 발송된 문자내용은 발송자가<br> 제공하는 정보로서 이를 신뢰하여 취한 조치에 대해 책임지지 않습니다.</br>

      </p>
    </div>
    </div>
    </div>
    <div class="sub-12-section">
      <div class="inner">
        <img src="images/200330_10.png">
      </div>
    </div>
    <div class="sub-12-bg-02 sub-12-section">
      <div class="inner">
        <img src="images/191229_04.png">
      </div>
    </div>
    <div class=" sub-12-section">
      <a name="technic"></a>
      <div class="inner">
        <img src="images/191229_05.png">
      </div>
    </div>
    <div class="sub-12-bg-02 sub-12-section">
      <a name="uses"></a>
      <div class="inner">
        <img src="images/191229_06.png">
      </div>
    </div>
  </div>
</div>
<?
include_once "_foot.php";
?>