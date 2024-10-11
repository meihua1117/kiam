<?
$path="./";
include_once "_head_open.php";
if(!$_SESSION[one_member_id])
{

?>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}
	$sql="select * from Gn_Member  where mem_id='".$_SESSION[one_member_id]."'";
	$sresul_num=mysqli_query($self_con,$sql);
	$data=mysqli_fetch_array($sresul_num);	
	
 
?>
<script>
function copyHtml(){
    //oViewLink = $( "ViewLink" ).innerHTML;
    ////alert ( oViewLink.value );
    //window.clipboardData.setData("Text", oViewLink);
    //alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
    var trb = $.trim($('#sHtml').html());
    var IE=(document.all)?true:false;
    if (IE) {
        if(confirm("이 소스코드를 복사하시겠습니까?")) {
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

function copyHtml(url){
    //oViewLink = $( "ViewLink" ).innerHTML;
    ////alert ( oViewLink.value );
    //window.clipboardData.setData("Text", oViewLink);
    //alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
    var IE=(document.all)?true:false;
    if (IE) {
        if(confirm("이 소스코드를 복사하시겠습니까?")) {
            window.clipboardData.setData("Text", url);
        } 
    } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", url);
    }

}
</script>
<script language="javascript" src="./js/rlatjd_fun.js?m=1574414201"></script>
<script language="javascript" src="./js/rlatjd.js?m=1574414201"></script>

<!DOCTYPE html>
<html lang="ko">
<head>


<style>
.pop_right {
    position: relative;
    right: 2px;
    display: inline;
    margin-bottom: 6px;
    width: 5px;
}        
      table {
        width: 100%;; 
        border: 3px solid #333333;
      }
      th { 
        border: 1px 
        solid black; 
      }  
      td {
        padding: 5px;
        border: 1px solid #333333;
      }
      .a {
        border-collapse: separate;
      }
      .b {
        border-collapse: collapse;
      }

      

</style>
</head>



  <body>
	<div class="bnt main-buttons" 
          style="background-color: #FFFFFF2; 
                width:60%;
                padding-top :10px;
                padding-left 10px;
                padding-right :10px;
                padding-bottom :10px;">
 
 
      <h2>셀링솔루션 코칭신청해요</h2>
      
    <table class="b">
      <tbody>
        <tr><th>계약기간</th> <th>계약시간</th> <th>코칭비용</th><th>선택</th></tr>
	
        <tr><td>3일</td><td>2시간</td><td>5만원</td><td><input type="radio" name="ages" value="teenage"></td></tr>
		<tr><td>7일</td><td>4시간</td><td>9만원</td><td><input type="radio" name="ages" value="teenage"></td></tr>
		<tr><td>11일</td><td>6시간</td><td>13만원</td><td><input type="radio" name="ages" value="teenage"></td></tr>
		<tr><td>15일</td><td>8시간</td><td>17만원</td><td><input type="radio" name="ages" value="teenage"></td></tr>
		<tr><td>19일</td><td>10시간</td><td>21만원</td><td><input type="radio" name="ages" value="teenage"></td></tr>
		<tr><td>25일</td><td>12시간</td><td>25만원</td><td><input type="radio" name="ages" value="teenage"></td></tr>
		<tr><td>30일</td><td>14시간</td><td>28만원</td><td><input type="radio" name="ages" value="teenage"></td></tr>
		<tr><td>60일</td><td>30시간</td><td>50만원</td><td><input type="radio" name="ages" value="teenage"></td></tr>
	  </tbody>
            
  </table>
            <div class="p1" style="text-align:center;margin-top:20px;">
            <input type="button" value="취소" class="button"  id="cancleBtn">
            <input type="button" value="저장" class="button" id="saveBtn">
            </div>
</body>
</html>