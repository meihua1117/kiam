<?
$path="./";
include_once "_head.php";
if(!$_SESSION[one_member_id])
{

?>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}
extract($_REQUEST);
$sql="select * from Gn_Member  where mem_id='".$_SESSION[one_member_id]."'";
$sresul_num=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($sresul_num);	
	
?>

<script>
function copyHtml(){
    var trb = $.trim($('#sHtml').html());
    var IE=(document.all)?true:false;
    if (IE) {
        if(confirm("이 링크를 복사하시겠습니까?")) {
            window.clipboardData.setData("Text", trb);
        } 
    } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
    }

} 
$(function(){
	$(".popbutton").click(function(){
		$('.ad_layer_info').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	})

});
</script>



<style>

.label_content {
    position:absolute;
    top:50%;
    left:50%;
    transform : translate(-50%, -50%);
}

.label_content input{display:none;}
.label_content input + label {}
.label_content input + label + div {
    display : none;
    width : 300px;
    height:300px;
    background:#f5f5f5;
    border:1px solid #eee;
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%, -50%);
}
.label_content input:checked + label + div{display:block;}
.label_content input + label + div label{
    position:absolute;
    bottom:0%;
    left:50%;
    transform:translate(-50%, -50%);
    background:#333;
    color:#fff;
    padding:20px;
}


</style>

<div class="big_sub">
    
<?php include_once "mypage_base_navi.php";?>

   <div class="m_div">
       <?php include_once "mypage_left_menu.php";?>

 <br><br><br><br><br><br><br><br><br><br>
   <div class ="label_content">
        <input type="checkbox" id="info">
        <label for="info">open</label>
        <div class="content">
       <h1>온리원셀링 <br>코치신청하기</h2>
       <h3>온리원셀링의 코치로 활동하시려면 신청하기에 클릭하세요. <br>코칭활동에 대해 자세히 보시려면 왼쪽 버튼을 눌러 확인하세요</h3>

      <button class="button" onclick="javascript:location.href='https://oog.kiam.kr/pages/page_4243.php'">코칭활동보기</button>
      <button class="button" onclick="javascript:location.href='http://kiam.kr/m/join.php'">코치신청하기</button>
      <button class="button" onclick="javascript:location.href='http://kiam.kr/m/join.php'">다음에봐요</button>      
         <label for="info">닫기</label>
    </div>
    </div>

  <!--  <div class="bnt main-buttons" 
          style="background-color: #FFFFFF2; 
                width:50%;
                text-align:center;
                padding-top :10px;
                padding-left 10px;
                padding-right :10px;
                padding-bottom :30px;">


     </div>
 
   </div> -->

   </body>