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
</script>


<div class="midle_div">
    
  <div class="sub-10-visual main-section">
      <a name="Introduce"></a>
      <div class="inner">
        <img src="images/191220_10.png">
      </div>
    </div>
    
    <div class="sub-10-bg-01 sub-10-section">
        
        <div class="inner">
          <img src="images/191227_01.png">
        </div>
     </div>
    
    <a name="install"></a>
    <?php if($sub_domain == true) {?>
	<div class="bnt main-buttons" style="background-color: #DBA901; padding-bottom:20px">    
      <div class="wrap2" style="max-width: 849px; margin: 0 auto;">      
        <a href="https://tinyurl.com/2waxstj9"  class="button" target="_blank">아이엠 앱(APP)설치하기</a>
        <a href="https://tinyurl.com/bdds92j3"  class="button" target="_blank">아이엠 사용매뉴얼</a>
        <a href="<?=$domainData['kakao'];?>" class="button2" target="_blank">카카오상담</a>
        <a href="<?=$href?>" class="button" target="_blank">아이엠 샘플보기</a>
      </div>
    </div>
	<?php }else{?>  
 
   <a name="install"></a>
    <div class="bnt main-buttons" style="background-color: #DBA901; padding-bottom:20px;">   
      <div class="wrap2" style="max-width: 849px; margin: 0 auto;">      
        <a href="https://tinyurl.com/2waxstj9"  class="button" target="_blank">아이엠 앱(APP)설치하기</a>
        <a href="https://tinyurl.com/bdds92j3"  class="button" target="_blank">아이엠 사용매뉴얼</a>
      
        <a href="http://pf.kakao.com/_jVafC/chat" class="button2" target="_blank">카카오상담하기</a>
        <a href="<?=$href?>" class="button" target="_blank">아이엠 샘플보기</a>
      </div>
    </div>
    <? } ?>
    </div>
    <div class="sub-10-section">
    <a name="trait"></a>
      <div class="inner">
        <img src="images/200330_04.png">
      </div>
    </div>
    <div class="sub-10-section">
      <div class="inner">
        <img src="images/191227_02.png">
      </div>
    </div>
    <div class="sub-10-bg-02 sub-10-section">
      <a name="technic"></a>
      <div class="inner">
        <img src="images/191227_03.png">
      </div>
    </div>
    <div class="sub-10-section">
      <a name="uses"></a>
      <div class="inner">
        <img src="images/191227_04.png">
      </div>
    </div>
  </div>
</div>

<?
include_once "_foot.php";
?>