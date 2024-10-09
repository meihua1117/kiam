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
  <div class="sub-13-visual main-section">
      <a name="Introduce"></a>
      <div class="inner">
        <img src="images/200324_03.png">
      </div>
    </div>
    
    <div class="sub-13-bg-01 sub-13-section">
        <a name="trait"></a>
        <div class="inner">
          <img src="images/200324_04.png">
        </div>    
    </div>
    
    <?php if($sub_domain == true) {?>
    <div class="bnt main-buttons" style="background-color:  #086A87; padding-bottom:20px;">
      <div class="wrap2">
        
        <a href="<?=$domainData['kakao'];?>" class="button2" target="_blank">카카오상담하기</a>
        <a href="/m/agree.php" class="button" target="_blank">마이샵 이용하기</a>
      </div>
    </div>
    <?php }else{?>
    
    <div class="bnt main-buttons" style="background-color:  #086A87; padding-bottom:20px;">
      <div class="wrap2">
       
        <a href="http://pf.kakao.com/_jVafC/chat" class="button2" target="_blank">카카오상담하기</a>
        <a href="/m/agree.php" class="button" target="_blank">마이샵 이용하기</a>
      </div>
    </div>
    <? } ?>

    
   <div class="sub-13-section">
      <div class="inner">
        <img src="images/200330_05.png">
      </div>
    </div>

    

    <!--<div class="im_title is-main">
      <p>
        <span style="color:#FF6; font-size:25px;">고객맞춤 메시지카피 솔루션</span><br/>
        메시지 카피
      </p>
    </div>-->
    <div class="sub-13-section">
      <a name="technic"></a>
      <div class="inner">
        <img src="images/200324_06.png">
      </div>
    </div>

    <!--<div class="im_title is-main">
      <p>
        <span style="color:#FF6; font-size:25px;">모두 0.7원 문자 솔루션</span><br/>
        온리원문자
      </p>
    </div>-->
    <div class="sub-13-section">
      <a name="uses"></a>
      <div class="inner">
        <img src="images/200324_07.png">
      </div>
    </div>
    <div class="sub-13-section">
      <a name="uses"></a>
      <div class="inner">
        <img src="images/200324_08.png">
      </div>
    </div>
    <div class="sub-13-section">
      <a name="uses"></a>
      <div class="inner">
        <img src="images/200324_09.png">
      </div>
    </div>
    
     <div class="bnt main-buttons" >
      <div class="wrap2" style="max-width: 849px; margin: 0 auto;">
        <a href="/m/agree.php" class="button" target="_blank">마이샵 이용하기</a>       
      </div>
    </div>
    
  </div>
  
  </div>


<?
include_once "_foot.php";
?>