<!DOCTYPE html>
<html lang="ko">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 

 
</head>

<style>
*{
  margin:0; padding:0;
  font-size:15px; 
  line-height:1.3;
}
ul{list-style:none;}

.tabmenu{ 
  max-width:1000px; 
  margin: 0 auto; 
  position:relative;     
}
.tabmenu ul li{
    list-style: none;
    color: white;
    width:33%; 
    background-color: #2d2d2d;
    float: left;
    line-height: 30px;
    font-weight : bold;
    vertical-align: middle;
    text-align: center;
  }
.tabmenu label{
  display:block;
  width:100%; 
  height:40px;
  line-height:20px;
  padding: 5px 0px;
  border:1px solid #CCC;  
}
.tabmenu input{display:none;}
.topMenu .menuLink:hover {
  color: red;
  background-color: #4d4d4d;
}

.tabCon{
  display:none; 
  text-align:left; 
  padding: 10px;
  position:absolute; 
  left:0; top:40px; 
  box-sizing: border-box; 
  margin-top:80px;
  width:100%; 
}
.tabmenu input:checked ~ label{
  background:#ccc;
}
.tabmenu input:checked ~ .tabCon{
  display:block;
}
.box-body th {background:#ddd;}


</style>

<div class="tabmenu">
  <ul>
    <li class="btnCon" id="tab1"> <input name="tabmenu" id="tabmenu1" type="radio" checked="">
      <label for="tabmenu1">대표 샘플<br>아이엠 보기</label>
      <div class="tabCon"><p style="text-align: center;">
        <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(70, 70, 70);">[추천 IAM을 롤링으로 살펴보세요]</span><br><br><iframe src="http://obmms.net//iam/?ZAhvccPxi4" style="width: 400px; height: 1200px; border-width:1px; border-style: solid; border-color: gray;"></iframe><br><br><span style="color: rgb(0, 0, 0); font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt;"><span style="font-weight: normal;">더 많은 아이엠 샘플을 보시려면 </span><br>[상단샘플더보기]</span><span style="color: rgb(0, 0, 0); font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; font-weight: normal;">를 클릭하세요.</span></p></div> </li>

    <li class="btnCon" id="tab2"><input name="tabmenu" id="tabmenu2" type="radio">
      <label for="tabmenu2">샘플 아이엠<br>더보기</label>
      <div class="tabCon"><p style="text-align: left;" align="left">&nbsp;</p><div style="text-align: center;" align="center"><span style="font-size: 14pt; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; color: rgb(0, 0, 0);">[멋진 IAM을 모아둔 샘플페이지 보기]</span><br><br><iframe src="http://obmms.net/iam/index_sample.php" style="width: 400px; height: 1200px; border-style: solid; border-color: gray;"></iframe><br><br><span id="husky_bookmark_end_1609083909802"></span><span style="font-weight: normal;"><span style="color: rgb(99, 99, 99); font-size: 12pt; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif;">이렇게 멋있는 아이엠을 더 알아보고 싶다면?</span><br></span><span style="color: rgb(0, 0, 0); font-size: 12pt; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif;"><span style="color: rgb(99, 99, 99);"><span style="font-weight: normal;">상단 우측&nbsp;</span><span style="font-size: 14pt; color: rgb(99, 99, 99);">[아이엠 알아보기]</span><span style="font-weight: normal;">를 클릭하세요.</span></span><br><br><span style="font-weight: normal; color: rgb(99, 99, 99);">나의 아이엠을 만들고 싶다면?</span><br><span style="font-weight: normal; color: rgb(99, 99, 99);">상단 왼쪽 </span><span style="font-size: 14pt; color: rgb(99, 99, 99);">[내 아이엠 만들기]</span><span style="font-weight: normal; color: rgb(99, 99, 99);">를 클릭하세요.</span></span><br><br></div>
      <br><br><span id="husky_bookmark_end_1609083779553"></span><span id="husky_bookmark_end_1609083816784"></span><br>
      <p>&nbsp;</p></div> </li>    
    <li class="btnCon" id="tab3"><input name="tabmenu" id="tabmenu3" type="radio">
      <label for="tabmenu3">아이엠<br>알아보기</label>
      <div class="tabCon">
      
      <div style="text-align: center;" align="center"><span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 18pt; color: rgb(0, 0, 0);">[IAM에 대해서 더 알고 싶다면 여기를 보세요!]</span><br><br><br></div>

      <div style="text-align: center;" align="center"><img src="upload/160915181048863_201226_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0_%25EB%2594%2594%25EC%259E%2590%25EC%259D%25B81.png" title="201226_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0_%25EB%2594%2594%25EC%259E%2590%25EC%259D%25B81.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915182718253_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C2.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C2.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915184573336_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C3.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C3.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915202010043_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C4.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C4.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915204061610_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C5.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C5.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915206066500_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C6.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C6.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915207861835_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C7.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C7.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915209690850_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C8.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C8.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915211748725_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C9.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C9.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915217526180_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C11.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C11.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915219610176_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C12.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C12.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915222874717_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C14.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C14.png" width="400px" height=""><br style="clear:both;"><img src="upload/160915224418912_201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C15.png" title="201228_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%25EC%2586%258C%25EA%25B0%259C15.png" width="400px" height=""><br style="clear:both;"><br style="clear:both;"><span style="font-size: 24pt; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; background-color: rgb(119, 176, 43); color: rgb(255, 255, 255);">IAM 소개서 다운로드 받기<br><span style="font-size: 14pt; background-color: rgb(109, 48, 207); color: rgb(255, 255, 255);"><br><span style="font-size: 12pt; background-color: rgb(119, 176, 43); color: rgb(255, 255, 255);">이제 내 아이엠을 만들고 싶지 않나요?</span><br></span><span style="font-size: 12pt; background-color: rgb(119, 176, 43); color: rgb(255, 255, 255);">상단 왼쪽 <span style="font-size: 14pt; background-color: rgb(119, 176, 43); color: rgb(255, 255, 255);">[내 IAM만들기]</span>에서 만들어볼까요?</span><br><br></span></div><div style="text-align: center;" align="center"><br><br><br><br></div>
      
      <div style="text-align: center;" align="center">&nbsp;</div><p>&nbsp;</p>
      </div> 
    </li>
        
    <li class="btnCon" id="tab4"><input name="tabmenu" id="tabmenu4" type="radio">
      <label for="tabmenu4">내 아이엠<br>만들기</label>
     
      <div class="tabCon">
        <div> 
        <p style="text-align: center; font-size: 24px;" align="center"><span style="color: rgb(0, 0, 0); font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt;"><span style="font-size: 18pt; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif;">[내 아이엠 자동생성 서비스]</span></span></p>
        </div>
        
        <div><div style="text-align: center;"><br></div>
        <div style="text-align: center;" align="center"><img src="upload/160908355173454_%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%2B%25EB%25A7%258C%25EB%2593%25A4%25EA%25B8%25B0%2B%25EC%2586%258C%25EA%25B0%259C%2B%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" title="%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%2B%25EB%25A7%258C%25EB%2593%25A4%25EA%25B8%25B0%2B%25EC%2586%258C%25EA%25B0%259C%2B%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" width="600" height=""></div><br><span style="color: rgb(0, 158, 37);">
        
        </span><p style="text-align: center; font-size: 20px;" align="center">&nbsp;</p><div style="text-align: center;" align="center"><span style="color: rgb(0, 158, 37); font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt;">[자동으로 만든 네이버샵 아이엠 샘플보기]</span></div><span style="color: rgb(0, 158, 37); font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt;"><div style="text-align: center;" align="center"><span style="font-size: 14pt;">&nbsp;</span></div></span><p style="text-align: center;">&nbsp;</p>
        </div>
        
        <div style="text-align: center;">
                
        <iframe src="http://obmms.net//iam/index.php?mem_code=12209card_mode=card_title" style="width: 400px; height: 1200px; border-width: 1px; border-style: solid; border-color: gray;"></iframe></div>
        
        <br>

        <div>
        <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 18pt; color: rgb(0, 0, 0);">■ 이제 나의 아이엠을 만들어볼까요?</span><br>

                  <span style="color: rgb(0, 0, 0); font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif;"><br>운영자님의 스토어 주소를 아래에 입력하시면 프로그램이 운영자님의 샵 아이엠을 자동으로 생성하게 됩니다.
                   <br></span><span style="color: rgb(0, 0, 0);">[예시]</span><span style="color: rgb(0, 0, 0);">&nbsp;</span><span style="color: rgb(0, 0, 0);">https://smartstore.naver.com/misohealth</span><br><br>

        
        <p style="font-size:18px;"><span style="color: rgb(0, 0, 0); font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif;">&nbsp;스토어주소  <button type="button" style="background:white;width:250px;height:22px;"></button><button type="button" style="margin-left:12px;background:#BDBDBD;width:40px;height:22px;">확인</button><br><br><br> </span></p>


        <div style="margin-bottom:25px;border:1px solid #000;padding:10px;line-height:25px;">
        <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);">운영자님의 아이엠이 자동생성되었습니다.</span><br>
                  <span style="color: rgb(0, 0, 0); font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif;">&nbsp;<br>&nbsp;1. 해당정보는 운영자님의 스토어에 있는 판매자 정보와 상품페이지 정보가 임시계정으로 시스템에 등록되었습니다. <br>&nbsp;2. 자동으로 만들어진 운영자님의 아이엠은 편집/삭제 코너에서 편집, 홍보, 삭제할 수 있습니다.  <br>&nbsp;3. 운영자님의 아이엠을 편집, 이용, 삭제하시려면 상단의 [내 아이엠 편집하기]를 클릭해주세요.<br><br>
                   </span>
        </div>
      </div>
         
     </div></li>

   <li class="btnCon" id="tab5"><input name="tabmenu" id="tabmenu5" type="radio">
      <label for="tabmenu5">내 아이엠<br>편집하기</label>
   <div class="tabCon">
      <div> <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 18pt; color: rgb(0, 0, 0);">■ 내 아이엠 편집, 이용, 삭제 안내</span></div><p>
      
      <span style="font-weight: normal; font-size: 12pt;"><span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);"><br>&nbsp;▷자동으로 생성된 아이엠이 마음에 드시나요?</span><br>

      <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">&nbsp;▷자신의 아이엠을 더 멋지게 만드려면 아래 정보와 절차를 따라 편집, 이용해주세요.</span></span><br><br>
      <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);">
      
      1. 내 아이엠 임시계정 받기</span><br><span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0); font-weight: normal;"><br>&nbsp;▷아이엠을 편집하려면 휴대폰으로 임시계정을 받으세요.<br>&nbsp;▷임시계정에서 아이디는 수정이 되지 않습니다. <br>&nbsp;▷비번은 마이페이지에서 수정하시면 됩니다.</span><br><br>
      
      <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);">&nbsp;[임시계정받기] 

      </span><br>
      
      <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);">
      <button type="button" style="font-weight: normal; margin-left: 12px; background: rgb(189, 189, 189); width: 80px; height: 22px;">휴대폰</button> 


      <span style="background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial; height: 22px;"><button type="button" style="margin-left:12px;background:white;width:180px;height:22px;"></button></span> 

      <button type="button" style="margin-left:12px;background:#BDBDBD;width:50px;height:22px;">확인</button> <br><br>

      
      <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);">
      2. 내 아이엠 편집하러 가기<br></span><span id="husky_bookmark_end_1608923038615" style="font-weight: normal;"></span><span style="font-weight: normal; font-size: 12pt;"><br>1) 로그인 : 임시계정으로 로그인하고 마이페이지에서 비번을 변경하세요.<br><br></span>&nbsp;<img src="upload/160919228772017_%25ED%259A%258C%25EC%259B%2590%25EC%25A0%2595%25EB%25B3%25B4%25EC%2588%2598%25EC%25A0%2595.PNG" title="%25ED%259A%258C%25EC%259B%2590%25EC%25A0%2595%25EB%25B3%25B4%25EC%2588%2598%25EC%25A0%2595.PNG" width="400" height=""><br style="clear:both;"><br><span style="font-weight: normal; font-size: 12pt;">2) 살펴보기 : 포토박스, 프로필, 콘텐츠를 하나씩 살펴보세요.<br></span><br><span style="font-weight: normal; font-size: 12pt;">3) 포토 변경 : 대표이미지 3장 중 바꾸고 싶은 이미지를 우측 상단 편집기를 이용하여 변경하세요.<br><br></span><span style="font-weight: normal;"><img src="upload/160907863831586_%25ED%258F%25AC%25ED%2586%25A0%25EB%25B0%2595%25EC%258A%25A4%25EB%25B3%2580%25EA%25B2%25BD%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" title="%25ED%258F%25AC%25ED%2586%25A0%25EB%25B0%2595%25EC%258A%25A4%25EB%25B3%2580%25EA%25B2%25BD%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" width="400" height=""></span><br style="clear:both;"><br><span style="font-weight: normal; font-size: 12pt;">4) 프로필 변경 : 프로필 편집아이콘을 클릭하여 해당 항목을 변경 혹은 입력하세요.<br><br></span><span style="font-weight: normal;"><img src="upload/160907866387288_%25ED%2594%2584%25EB%25A1%259C%25ED%2595%2584%25EB%25B3%2580%25EA%25B2%25BD%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" title="%25ED%2594%2584%25EB%25A1%259C%25ED%2595%2584%25EB%25B3%2580%25EA%25B2%25BD%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" width="400" height=""></span><br style="clear:both;"><br><br><span style="font-weight: normal; font-size: 12pt;">5) 콘텐츠 변경 : 콘텐츠 편집아이콘을 클릭하여 해당 정보를 변경 혹은 입력하세요.<br><br><img src="upload/160907869153273_%25EC%25BD%2598%25ED%2585%2590%25EC%25B8%25A0%25EB%25B0%2595%25EC%258A%25A4%25EB%25B3%2580%25EA%25B2%25BD%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" title="%25EC%25BD%2598%25ED%2585%2590%25EC%25B8%25A0%25EB%25B0%2595%25EC%258A%25A4%25EB%25B3%2580%25EA%25B2%25BD%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" width="400" height=""><br style="clear:both;"></span><br><br>
      
      <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);">3. 내 아이엠 이용하기</span><span style="font-weight: normal;"><span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);"><span style="font-size: 12pt;"><br>※아이엠을 이용하시기 위해서는 위에서 받은 임시계정으로 로그인해야 합니다.<br></span><br><span style="font-size: 12pt;">1) 내 명함 지인에게 보내기 : 아래 공유아이콘중 필요한 채널을 클릭하여 공유해보세요.<br><br>&nbsp;<img src="upload/160907896037506_%25EA%25B3%25B5%25EC%259C%25A0%25EC%2595%2584%25EC%259D%25B4%25EC%25BD%2598%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" title="%25EA%25B3%25B5%25EC%259C%25A0%25EC%2595%2584%25EC%259D%25B4%25EC%25BD%2598%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" width="400" height=""><br style="clear:both;"><br>2) 상단의 [10만 회원에게 홍보하기]를 클릭하면 자세히 볼수 있습니다.<br><br><img src="upload/160907918172826_%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%2B%25EC%2583%2581%25EB%258B%25A8%25EB%25A9%2594%25EB%2589%25B4%2B%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" title="%25EC%259E%2590%25EB%258F%2599%25EC%2595%2584%25EC%259D%25B4%25EC%2597%25A0%2B%25EC%2583%2581%25EB%258B%25A8%25EB%25A9%2594%25EB%2589%25B4%2B%25EC%259D%25B4%25EB%25AF%25B8%25EC%25A7%2580.PNG" width="400" height=""></span><br></span></span><br>
     
      <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);">4. 내 아이엠을 폰의 홈에 등록하기<br></span><span style="font-size: 12pt;"><br><span style="font-weight: normal;">1)</span></span><span style="font-weight: normal;"><span style="font-size: 12pt;">&nbsp;휴대폰의 상단 삼점을 클릭하고 하단에 홈화면추가를 클릭하세요.</span></span><span style="font-weight: normal;"><span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);"><span style="font-size: 12pt;"><br><br><img src="upload/160907984399563_%25ED%2599%2588%25ED%2599%2594%25EB%25A9%25B4%2B%25EC%25A0%2580%25EC%259E%25A5%25ED%2595%2598%25EA%25B8%25B0%2B%25EC%2595%2584%25EC%259D%25B4%25EC%25BD%2598.PNG" title="%25ED%2599%2588%25ED%2599%2594%25EB%25A9%25B4%2B%25EC%25A0%2580%25EC%259E%25A5%25ED%2595%2598%25EA%25B8%25B0%2B%25EC%2595%2584%25EC%259D%25B4%25EC%25BD%2598.PNG" width="400" height=""><br style="clear:both;"></span></span></span><span style="font-size: 12pt;"><br><span style="font-weight: normal;">2) 카톡에서 IAM 링크를 열었을 경우, [다른 브라우저로 열기]를 눌러 브라우저를 바꾼 후에 위 1)항 처럼 해주세요.<br><br></span></span><img src="upload/160919378522265_%25EC%25B9%25B4%25ED%2586%25A1%25EC%2597%2590%25EC%2584%259C%2B%25EB%25B8%258C%25EB%259D%25BC%25EC%259A%25B0%25EC%25A0%2580%2B%25EB%25B3%2580%25EA%25B2%25BD%25ED%2595%2598%25EC%2597%25AC%2B%25ED%2599%2594%25EB%25A9%25B4%25EC%2597%2590%2B%25EC%25A0%2580%25EC%259E%25A5.PNG" title="%25EC%25B9%25B4%25ED%2586%25A1%25EC%2597%2590%25EC%2584%259C%2B%25EB%25B8%258C%25EB%259D%25BC%25EC%259A%25B0%25EC%25A0%2580%2B%25EB%25B3%2580%25EA%25B2%25BD%25ED%2595%2598%25EC%2597%25AC%2B%25ED%2599%2594%25EB%25A9%25B4%25EC%2597%2590%2B%25EC%25A0%2580%25EC%259E%25A5.PNG" width="400" height=""><br style="clear:both;"><br><br>
  
        <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);">5. 내 아이엠 삭제하기</span><br>

         <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);"><span style="font-size: 12pt;"><span style="font-weight: normal;"><br><span style="font-size: 12pt;">1) 내 아이엠을 삭제하려면 아래 삭제 버튼을 클릭하세요.<br></span><br></span></span><span style="font-weight: normal;"><span style="font-size: 12pt;">&nbsp;</span><img src="upload/160908019767854_%25EB%25A7%2588%25EC%259D%25B4%25ED%258E%2598%25EC%259D%25B4%25EC%25A7%2580%2B%25EC%2582%25AD%25EC%25A0%259C%25ED%2595%2598%25EA%25B8%25B0.PNG" title="%25EB%25A7%2588%25EC%259D%25B4%25ED%258E%2598%25EC%259D%25B4%25EC%25A7%2580%2B%25EC%2582%25AD%25EC%25A0%259C%25ED%2595%2598%25EA%25B8%25B0.PNG" width="400" height=""><br style="clear:both;"><br><span style="font-size: 12pt;">2) 다음에 삭제하려면 주소, 아이디, 비번을 어딘가에 잘 저장해두세요.&nbsp;<br></span></span><br><br>감사합니다.<br><br><span style="font-size: 12pt; color: rgb(125, 125, 125);">내 아이엠으로 홍보를 하고 싶다면?</span><br><span style="font-size: 12pt; color: rgb(125, 125, 125);">상단 우측에 <span style="font-size: 14pt; color: rgb(0, 0, 0);">[깜짝놀랄 홍보하기</span><span style="color: rgb(0, 0, 0);">]</span>를 클릭하세요.</span><br></span>&nbsp;</span></p></div>
        </li>


   <li class="btnCon" id="tab6"><input name="tabmenu" id="tabmenu6" type="radio">
      <label for="tabmenu6">깜짝놀랄<br>홍보하기</label>
   <div class="tabCon">
      <div> <span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 18pt; color: rgb(0, 0, 0);"><span style="font-size: 14pt;"><span style="font-size: 18pt;">■ 깜짝 놀라게 될 홍보를 해볼까요?</span><br><br><span style="font-size: 12pt;">아이엠을 만들었으면 1)아이엠에 가입된 모든 회원들과 2)자신의 지인, 그리고 3)익명의 디비들에게까지 깜짝 놀랄 홍보의 기회가 열려있습니다. 이제 하나씩 그 기능을 이용해볼까요?</span></span><br><br><span style="font-size: 14pt;">1. 프렌즈에서 홍보하기</span></span></div><br>

      <span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">▷아래 아이엠 이미지 우측에 프렌즈 숫자가 여러분에게 공개한 아이엠 정보입니다.</span><br><span style="font-weight: normal; font-size: 12pt;">

      </span><span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">▷프렌즈에서 여러분이 찾고 싶은 키워드를 입력하면 원하는 아이엠을 검색할수 있습니다.</span><br><span style="font-weight: normal; font-size: 12pt;">

      </span><span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">▷프렌즈에서 검색하여 홍보하는 것은 이미 아이엠에 공개한 분들이기때문에 스팸이 되지 않습니다.</span><br><br>

      
      <div style="font-weight: normal; text-align: left;"><img src="http://obmms.net/iam/img/frenz_num.PNG" width="400px"><br><br></div>

      <div style=""><span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);">2. 위콘텐츠에서 홍보하기</span></div><br>
      
      <span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">▷위콘텐츠는 10만명의 회원들이 자신의 아이엠에 올린 정보를 함께 공유하는 곳입니다.</span><br><span style="font-weight: normal; font-size: 12pt;">

      </span><span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">▷운영자님이 아이엠에 올린 콘텐츠는 모두 위콘텐츠에 공개되어 모든 회원들이 함께 보게 됩니다.</span><br><span style="font-weight: normal; font-size: 12pt;">

      </span><span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">▷위콘텐츠에 노출하고 싶지 않을때는 콘텐츠 편집창에서 위콘텐츠 노출 중지를 클릭하면 됩니다.</span><br><br>


      <div style="font-weight: normal; text-align: left;"><img src="http://obmms.net/iam/img/wecontent.PNG" width="400px"></div><br>

      <div style=""><span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);">3. 데일리발송으로 홍보하기</span></div><br>
      
      <span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">▷데일리발송은 운영자님의 휴대폰 주소록에 있는 지인들에게 매일 지정한 인원에게 홍보하는 기능입니다.</span><br><span style="font-weight: normal; font-size: 12pt;">

      </span><span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">▷본 기능은 자신의 폰에 있는 문자기능을 이용하므로 요금이 사용되지 않습니다.</span><br><span style="font-weight: normal; font-size: 12pt;">

      </span><span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">▷이를 사용하기 위해서는 IAM 온리원앱을 설치해야 합니다. <br></span><br>


      <div style="font-weight: normal; text-align: left;"><img src="http://obmms.net/iam/img/dailysend.PNG" width="400px"></div><br>

      <div style=""><span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);">4. 공유링크로 홍보하기</span></div><br>
      
      <span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">▷공유기능에는 카톡, 문자, 데일리, 복사, 페북, 이메일로 홍보할수 있습니다.</span><br><span style="font-weight: normal; font-size: 12pt;">

      </span><span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 12pt; color: rgb(0, 0, 0);">▷데일리발송은 앱설치를 해야 발송할수 있으나 문자는 앱설치없이 발송합니다.</span><br><span style="font-weight: normal; font-size: 12pt;">

      </span><span style="font-weight: normal; font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 14pt; color: rgb(0, 0, 0);"><span style="font-size: 12pt;">▷휴대폰의 무료문자로 대량 발송을 하려면 앱을 설치해야 합니다. <br></span> </span><br>


      <div style="text-align: left;"><span style="font-weight: normal;"><img src="http://obmms.net/iam/img/share_link.PNG" width="400px"></span><br><br><span style="font-family: 나눔고딕코딩, NanumGothicCoding, sans-serif; font-size: 18pt; color: rgb(0, 0, 0);">감사합니다.</span></div><br>


    </div></li>
   </ul>
 </div>
 