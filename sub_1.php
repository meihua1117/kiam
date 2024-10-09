<?
    $path="./";
    include_once "_head.php";
?>
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

<div class="container">
    <?/*php if($sub_domain == true && $domainData['main_default_yn'] != "Y") {?>
    <div class="midle_div">
        <div align="center">
            <img src="<?=$domainData['main_image']?>" />
        </div>
    </div>
    <?php }else {*/?>
            <div class="midle_div">
                
                <div class="sub-1-section sec-1">
                    <div class="content-wrap">
                        <div class="image-wrapper">
                            <img src="images/200107_07.png">
                        </div>
                    </div>
                </div>

                <div class="sub-1-section sec-2">
                    <div class="content-wrap">
                        <div class="image-wrapper">
                            <img src="images/200107_08.png">
                        </div>
                    </div>   
                                 
                    <?php if($sub_domain == true) {?>        
                    <!--<div class="bnt main-buttons" style="background-color: #0B3861; padding-bottom:20px;">
                      <div class="wrap2" style="max-width: 849px; margin: 0 auto;">
                        <a href="/cliente_list.php?status=1&one_no=64"  class="button" target="_blank">온리원문자(앱) 다운로드</a>
                        <a href="https://oog.kiam.kr/pages/page_3208.php"  class="button" target="_blank">온리원문자 이용매뉴얼</a>      
                        <a href="<?=$domainData['kakao'];?>" class="button2" target="_blank">카카오상담하기</a>
                        <a href="https://oog.kiam.kr/pages/page_3214.php" class="button" target="_blank">자주하는 질문</a>
                      </div> 
                    </div>-->                         
                  <?php }else{?>
                   
                   <div class="bnt main-buttons" style="background-color: #0B3861; padding-bottom:20px;">
                      <div class="wrap2" style="max-width: 849px; margin: 0 auto;">
                        <a href="/cliente_list.php?status=1&one_no=64"  class="button" target="_blank">온리원문자(앱) 다운로드</a>
                        <a href="https://tinyurl.com/3dr4afcp"  class="button" target="_blank">온리원문자 이용매뉴얼</a>      
                        <a href="http://pf.kakao.com/_jVafC/chat" class="button2" target="_blank">카카오상담하기</a>
                        <a href="https://tinyurl.com/yrnyd537" class="button" target="_blank">IAM플랫폼 통합매뉴얼</a>
                      </div> 
                    </div>      
                   <?} ?>
                    <div class="text-wrap" style="background-color: #0B3861;">
                      <p>
                        온리원문자는 문자발송 솔루션입니다. 본 솔루션을 통해 발송된 문자내용은 발송자가 제공하는 정보로서 이를 신뢰하여 취한 조치에 대해 책임지지 않습니다.

                      </p>
                     </div>
                                        
   				</div>
                
                 <div class="sub-1-section sec-4">
                    <div class="content-wrap">
                        <div class="image-wrapper">
                            <img src="images/200330_09.png">
                        </div>
                    </div>
                </div>
                  
                <div class="sub-1-section sec-3">
                     <!--<div class="im_title">
                        “Web문자” vs “온리원문자” 가격 비교 해볼까요?
                    </div>-->
                    <div class="content-wrap">
                        <div class="image-wrapper">
                            <img src="images/200107_09.png">
                        </div>
                    </div>
                </div>
                
                <div class="sub-1-section sec-4">
                    <div class="content-wrap">
                        <div class="image-wrapper">
                            <img src="images/200107_10.png">
                        </div>
                    </div>
                </div>
                    
                <div class="sub-1-section sec-5">
                    <div class="content-wrap">
                        <div class="image-wrapper">
                            <img src="images/200107_11.png">
                        </div>
                    </div>
                </div>

                <div class="sub-1-section sec-6">
                    <div class="im_title" style="line-height:25px;"><span style="font-size:20px;">온리원문자 자신 있습니다.</span><br>기능비교로 자신있습니다!</div>
                    <div class="content-wrap">
                        <div class="image-wrapper">
                            <img src="images/190320_main_05.png">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>                
    <?php //}?>
</div>
<?
    include_once "_foot.php";
?>