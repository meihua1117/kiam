<?
$path="./";
include_once "_head.php";
extract($_REQUEST);
if(!$_SESSION[one_member_id]){
?>
<script language="javascript">
location.replace('/');
</script>
<?
exit;
}
$sql="select * from Gn_landing  where landing_idx='".$landing_idx."'";
$sresul_num=mysql_query($sql);
$row=mysql_fetch_array($sresul_num);	
//clean code 22-01-19
/*$sql = "select * from Gn_Member where mem_id = '$_SESSION[one_member_id]' and site != ''";
$res_result = mysql_query($sql);
$member_1 = mysql_fetch_array($res_result);*/
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
</script>
<style>
.pop_right {
    position: relative;
    right: 2px;
    display: inline;
    margin-bottom: 6px;
    width: 5px;
}    
.w200 {width:200px}
.list_table1 tr:first-child td{border-top:1px solid #CCC;}
.list_table1 tr:first-child th{border-top:1px solid #CCC;}
.list_table1 td{height:40px;border-bottom:1px solid #CCC;}
.list_table1 th{height:40px;border-bottom:1px solid #CCC;}
.list_table1 input[type=text]{width:600px;height:30px;}

</style>
<div class="big_div">
<div class="big_sub">
    
<?php include "mypage_step_navi.php";?>

   <div class="m_div">
       <?php include "mypage_left_menu.php";?>
       <div class="m_body">

        <form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="mode" value="<?php echo $landing_idx?"land_update":"land_save";?>" />
        <input type="hidden" name="landing_idx" value="<?php echo $landing_idx;?>" />
        <div class="a1" style="margin-top:50px; margin-bottom:15px">
        <li style="float:left;">		
			<div class="popup_holder popup_text">랜딩페이지 만들기
				<div class="popupbox" style="display:none; height: 56px;width: 274px;left: 155px;top: -37px;">자신의 이벤트 상품이나 서비스를 소개하거나 상세페이지로 보여줄수 있도록 제작하는 기능입니다. <br><br>
				  <a class = "detail_view" style="color: blue;" href="https://url.kr/dcpqin" target="_blank">[자세히 보기]</a>
				</div>
            </div>	
        </li>
        
        <!--<li style="float:right; font-size:13px;">
              <a href="http://kiam.kr/movie/landing_making.mp4" target="_blank" >
              <button type="button" style="background:#D8D8D8;">랜딩만들기영상보기</button>
              </a> </li>
        
	
        <li style="float:right;"></li>
        <p style="clear:both"></p> -->
        </div>
        <div>
            <div class="p1">
                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th class="w200">랜딩페이지 제목</th>
                    <td><input type="text" name="title" placeholder="" id="title" value="<?=$row[title]?>"/> </td>
                </tr>    
                <tr>
                    <th class="w200">랜딩페이지 설명</th>
                    <td><input type="text" name="description" placeholder="" id="description" value="<?=$row[description]?>"/> </td>
                </tr>                  
                <tr>
                    <td colspan="2">
						<script language="javascript" src="/naver_editor/js/HuskyEZCreator.js" charset="utf-8"></script>
                        <textarea name="ir1" id="ir1" rows="10" cols="100" style="width:100%; height:400px; min-width:645px; display:none;"><?=$row[content]?></textarea>
                        <script language="javascript" src="/naver_editor/js/naver_editor.js" charset="utf-8"></script>                        
                    </td>
                </tr>                      
         <!--       <tr>
                    <th class="w200">동영상URL</th>
                    <td>
                        <input type="text" name="movie_url" placeholder="여기에 유투브 주소를 넣으세요" id="movie_url" value="<?=$row[movie_url]?>"/> 
                     </td>
                </tr>     -->               
                <tr>
                    <th class="w200">첨부파일</th>
                    <td><input type="file" name="file" placeholder="" id="file" /> </td>
                </tr>
           <!--     <tr>
                    <th class="w200">신청시 문자알림</th>
                    <td>
                        <input type="radio" name="alarm_sms_yn" id="alarm_y" value="Y" <?php echo $row['alarm_sms_yn']=="Y"?"checked":""?>>사용
                        <input type="radio" name="alarm_sms_yn" id="alarm_n" value="N" <?php echo $row['alarm_sms_yn']=="N"||$row['alarm_sms_yn']==""?"checked":""?>>사용 안함
                    </td>
                </tr>                
                <tr>
                    <th class="w200">댓글 기능 사용</th>
                    <td>
                        <input type="radio" name="reply_yn" id="reply_y" value="Y" <?php echo $row['reply_yn']=="Y"?"checked":""?>>사용
                        <input type="radio" name="reply_yn" id="reply_n" value="N" <?php echo $row['reply_yn']=="N"||$row['reply_yn']==""?"checked":""?>>사용 안함
                    </td>
                </tr>       -->                       
                <tr>
                    <th class="w200">신청창 자동 삽입</th>
                    <td>
                        <?
                        $sql = "select event_title from Gn_event where pcode='$row[pcode]'";
                        $eres = mysql_query($sql);
                        $erow = mysql_fetch_array($eres);
                        ?>
                        <input type="radio" name="request_yn" id="request_y" value="Y" <?php echo $row['request_yn']=="Y"?"checked":""?>>사용
                        <input type="radio" name="request_yn" id="request_n" value="N" <?php echo $row['request_yn']=="N"||$row['request_yn']==""?"checked":""?>>사용 안함
                        <input type="text" name="event_title" placeholder="" id="event_title" value="<?=$erow[event_title]?>" readonly style="width:100px"/>
                        <input type="hidden" name="pcode" id="pcode" value="<?=$row[pcode]?>" />
                         <input type="button" value="신청창키워드조회" class="button " id="searchBtn">
                    </td>
                </tr>                                  
                <?php if($_SESSION[one_member_admin_id] != "" || $member_1[mem_leb] == 21 || $member_1[mem_leb] == 60) {?>
                <tr>
                    <th class="w200">강연신청창 자동삽입</th>
                    <td>
                        <input type="radio" name="lecture_yn" id="lecture_y" value="Y" <?php echo $row['lecture_yn']=="Y"?"checked":""?>>사용
                        <input type="radio" name="lecture_yn" id="lecture_n" value="N" <?php echo $row['lecture_yn']=="N"||$row['lecture_yn']==""?"checked":""?>>사용 안함
                    </td>
                </tr>      


              <tr>
<!--                    <th class="w200">링크자동연결기능</th>
                    <td>
                        <input type="text" name="" placeholder="" id="" value=""/> 
                     </td>
                </tr>   -->

 
                
                <tr>
                    <th class="w200">강연신청창 푸터</th>
                    <td colspan="2">
                        <textarea name="ir2" id="ir2" rows="10" cols="100" style="width:100%; height:400px; min-width:645px; display:none;"><?=$row[footer_content]?></textarea>
                        <script>
                        // 추가 글꼴 목록
                        //var aAdditionalFontSet = [["MS UI Gothic", "MS UI Gothic"], ["Comic Sans MS", "Comic Sans MS"],["TEST","TEST"]];

                        nhn.husky.EZCreator.createInIFrame({
                        	oAppRef: oEditors,
                        	elPlaceHolder: "ir2",
                        	sSkinURI: "/naver_editor/SmartEditor2Skin.html",	
                        	htParams : {
                        		bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
                        		bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
                        		bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
                        		//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
                        		fOnBeforeUnload : function(){
                        			//alert("완료!");
                        		}
                        	}, //boolean
                        	fOnAppLoad : function(){
                        		//예제 코드
                        		//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
                        	},
                        	fCreator: "createSEditor2"
                        });

                        function pasteHTML() {
                        	var sHTML = "<span style='color:#FF0000;'>이미지도 같은 방식으로 삽입합니다.<\/span>";
                        	oEditors.getById["ir2"].exec("PASTE_HTML", [sHTML]);
                        }

                        function showHTML() {
                        	var sHTML = oEditors.getById["ir2"].getIR();
                        	alert(sHTML);
                        }
                        	
                        function submitContents(elClickedObj) {
                        	oEditors.getById["ir2"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
                        	
                        	// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("ir1").value를 이용해서 처리하면 됩니다.
                        	
                        	try {
                        		elClickedObj.form.submit();
                        	} catch(e) {}
                        }

                        function setDefaultFont() {
                        	var sDefaultFont = '궁서';
                        	var nFontSize = 24;
                        	oEditors.getById["ir2"].setDefaultFont(sDefaultFont, nFontSize);
                        }
                            
                            
                        </script>
                    </td>
                </tr>                                           
                <?php }else {?>         
                <tr style="display:none">
                    <th class="w200">강연신청창 푸터</th>                
                    <input type="hidden" name="lecture_yn" value="<?php echo $row['lecture_yn'];?>">
                    <textarea name="ir2" id="ir2" rows="10" cols="100" style="width:100%; height:400px; min-width:645px; display:none;"><?=$row[footer_content]?></textarea>
                    </td>
                </tr>                                                        
                <?}?>
                </table>
            </div>
            <div class="p1" style="text-align:center;margin-top:20px;">
            <input type="button" value="취소" class="button"  id="cancleBtn">
            <input type="button" value="저장" class="button" id="saveBtn">
            </div>
        </div>        
        </form>
    </div>     
  </div>
</div> 
</div> 
<script>
function newpop(){
    var win = window.open("mypage_pop_event_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}    
$(function() {
    $('#searchBtn').on("click", function() {
        newpop();
    });
})    
$(function() {
    $('#cancleBtn').on("click", function() {
        location = "mypage_landing_list.php";
    });
    
    $('#saveBtn').on("click", function() {
        if($('#title').val() == "") {
            alert('제목을 입력해주세요.');
            return;
        }
        
        if($('#description').val() == "") {
            alert('설명을 입력해주세요.');
            return;
        }   

	    oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
	    <?php if($_SESSION[one_member_admin_id] != "") {?>
	    oEditors.getById["ir2"].exec("UPDATE_CONTENTS_FIELD", []);
    	<?}?>
	
	
        $('#sform').submit();
    });    
})

</script>      

<?
include_once "_foot.php";
?>