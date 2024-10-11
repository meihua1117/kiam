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
$(function(){
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
<div class="big_1 breadcrumb">
    <div class="m_div">
        
        <div class="left_sub_menu">
            <a href="./">홈</a> >
            <a href="sub_9.php">메시지카피</a> >
            <a href="?status=<?=$_REQUEST['status']?>"><?=$left_str?></a>
        </div>
        <div class="right_sub_menu">
            <a href="sub_9.php#Introduce">메시지카피소개</a> ㅣ <a href="sub_9.php#education">메시지카피교육</a> ㅣ<a href="sub_9.php#trait">메시지카피특징</a> ㅣ <a href="sub_9.php#effect">메시지카피효과</a> ㅣ <a href="sub_9.php#case">메시지카피사례</a>
        </div>
        
        <p style="clear:both;"></p>
    </div>
</div>


<div class="midle_div">
    
   <div class="sub-9-bg-01 sub-9-section">
      <a name="Introduce"></a>
      <div class="inner">
        <img src="images/200107_02.png">
      </div>
    </div>
    
    <div class="sub-9-bg-02 sub-9-section ">
        <a name="education"></a>
        <div class="inner">
          <img src="images/200107_03.png">
        </div>
     </div>
      
	<?php if($sub_domain == true) {?>
    <div class="bnt main-buttons" style="background-color: #FA5882;padding-bottom:20px;">
      <div class="wrap2" style="max-width: 849px; margin: 0 auto;" >
        <a href="https://youtu.be/0KytdCrg05A" class="button" target="_blank"">메시지카피기술 영상교육</a>     
        <a href="cliente_list.php?status=2" class="button" target="_blank">메시지카피기술 코칭신청</a>
        <a href="<?=$domainData['kakao'];?>" class="button2" target="_blank">카카오상담하기</a>
        <a href="https://kiam.kr/cliente_list.php?status=1" class="button" target="_blank">FAQ</a>
      </div>
    </div>
    <?php }else{?>

    
    <div class="bnt main-buttons" style="background-color: #FA5882; padding-bottom:20px;">
      <div class="wrap2" style="max-width: 849px; margin: 0 auto;">
        <!--a href="javascript:;;" onclick="alert('준비중입니다.')" class="button" style="max-width: 4000px; min-width: 180px;">메시지카피라이팅 교육안내</a--> 
        <a href="https://youtu.be/0KytdCrg05A" class="button" target="_blank"">메시지카피기술 <br />영상교육</a>     
        <a href="cliente_list.php?status=2" class="button" target="_blank">메시지카피기술 <br /> 코칭신청</a>
        <a href="http://pf.kakao.com/_jVafC/chat" class="button2" target="_blank">카카오상담하기</a>
        <a href="https://kiam.kr/cliente_list.php?status=1" class="button" target="_blank">FAQ</a>
      </div>
    </div>
    <? } ?>
   
    </div>
    
  <!--  <div class="sub-2-section sec-5">
    <div class="iframe-wrap">
      <iframe width="900" height="500" src="https://www.youtube.com/embed/3mYwvPX2JkY" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>     
    </div>
  </div> -->
    
    <div class="sub-9-section">
        <!--<div class="im_title" style="b ">
            <p>메시지카피 특징</p>
        </div>-->
        <a name="technic"></a>
        <div class="content-wrap">
            <img src="images/200330_07.png">
        </div>
    </div>
    
    
    <div class="sub-9-bg-03 sub-9-section">
        <!--<div class="im_title" style="b ">
            <p>메시지카피 특징</p>
        </div>-->
        <a name="technic"></a>
        <div class="content-wrap">
            <img src="images/200107_04.png">
        </div>
    </div>
    
    <div class=" sub-9-section">
        <!--<div class="im_title" style="b ">
            <p>메시지카피 특징</p>
        </div>-->
        <a name="technic"></a>
        <div class="content-wrap">
            <img src="images/200330_08.png">
        </div>
    </div>
    
     <div class="sub-9-section outer ">
        <a name="effect"></a>
        <div class="im_title" style="b ">
            <p><span class="colored">고객의 마음을 잡는 메시지카피라이팅</span><br/>
                고객과 소통하는 메시지! </p>
        </div>
     </div>
     
    <div class="sub-9-section">
       <!-- <div class="im_title">
            <p>
                <span class="colored">
                    고객맞춤 메시지카피 솔루션
                <br/>
                메시지 카피
            </p>
        </div>-->
        <a name="case"></a>
        <div class="content-wrap">
            <img src="images/200107_05.png">
        </div>
    </div>
    
    
    <!--<div class="sub-9-section">
        <div class="im_title">
            <p>
                <span class="colored">
                    1차 회신율 55% 달성
                </span><br/>
                메시지카피 예시
            </p>
        </div>
        <div class="content-wrap">
            <img src="images/190910_sub_9_03.png">
        </div>
    </div>-->

</div>

<?
    include_once "_foot.php";
?>