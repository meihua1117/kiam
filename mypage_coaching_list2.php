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


<style>
.pop_right {
    position: relative;
    right: 2px;
    display: inline;
    margin-bottom: 6px;
    width: 5px;
}   

.desc li {
    margin-bottom: 5px;
    font-size: 12px;
    line-height: 18px;
}    
.input-wrap a {
    float: right;
    width: 65px;
    display: block;
    margin-left: 5px;
    padding: 7px 5px;
    font-size: 11px;
    color: #fff;
    line-height: 14px;
    background-color: #ccc;
    text-align: center;
}
.check-wrap .check ~ label:before {
    content: '';
    position: absolute;
    top: 3px;
    left: 0;
    width: 18px;
    height: 18px;
    background-color: #fff;
    border: 1px solid #ccc;
}
.check-wrap .check ~ label {
    position: relative;
    display: inline-block;
    padding-left: 25px;
    line-height: 24px;
}
.check-wrap .check:checked ~ label:after { content: '\f00c'; position: absolute; top: 1px; left: 2px; color: #fff; font-family: 'Fontawesome'; font-size: 13px; }
.check-wrap .check:checked ~ label:before { background-color: #ff0066; border-color: #ff0066; }
</style>

<div class="big_sub">
    
<?php include_once "mypage_base_navi.php";?>

   <div class="m_div">
       <?php include_once "mypage_left_menu.php";?>
       <div class="m_body">

        <form name="pay_form" action="" method="post" class="my_pay">

            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        


	    <div class="bnt main-buttons" style="background-color:  #086A87; padding-bottom:10px;">
   	   <div class="wrap2"><br>
	        <a href="mypage_coaching_list_pop.php" class="button2" target="_blank">코치신청하기<br>가르치고싶어요!</a>
 	       <a href="mypage_coaching_apply.php" class="button2" target="_blank">코티신청하기<br>배우고싶어요!</a>
   	   </div>
  	  </div>  <br><br>

        <div class="a1" style="margin-top:15px; margin-bottom:15px">
        <li style="float:left;">셀링코치 코칭정보리스트 </li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>




        <div>
            <div class="p1">
                
             <!--   <input type="radio" name="category" value="" checked>전체-->
            <!--    <input type="radio" name="category" value="강연" <?php echo $_REQUEST['category'] =="강연"?"checked":""?>>강연-->
            <!--    <input type="radio" name="category" value="교육" <?php echo $_REQUEST['category'] =="교육"?"checked":""?>>교육-->
            <!--    <input type="radio" name="category" value="영상" <?php echo $_REQUEST['category'] =="영상"?"checked":""?>>영상 -->
                <input type="text" name="search_text" placeholder="" id="search_text" value="<?=$_REQUEST[search_text]?>"/> 
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="/images/sub_mypage_11.jpg" /></a>                                            
              <div style="float:right">
                    <input type="radio" name="end_date" value="">전체
                    <input type="radio" name="end_date" value="Y" <?php echo $_REQUEST['end_date']=="Y"?"checked":""?>>진행완료
                    <input type="radio" name="end_date" value="N" <?php echo $_REQUEST['end_date']=="N"||$_REQUEST['end_date']==""?"checked":""?>>진행중
                    <input type="button" value="코칭정보입력" class="button" onclick="location='mypage_coaching_write.php'">
                </div>
            </div>



            <div>

            <table class="write_table" width="100%"  cellspacing="0" cellpadding="0">
        <tr>
        <td>수강생선택</td>
        <td>
        <li><input type="text" name="id" itemname='아이디'  placeholder="아이디를 선택하세요"/></li>
        <li><input type="text" name="id" itemname='이름'  placeholder="이름을 선택하세요"/></li>
		</td>
        </tr>
        <td>코칭일시</td>
            <td >
               <li><input type="text"  name="contract_start_date" id="contract_start_date" value="<?=$data['contract_start_date']?>" class="date" style="width:80px"> </li>
                            ~ 
               <li><input type="text"   name="contract_end_date" id="contract_end_date" value="<?=$data['contract_end_date']?>" class="date" style="width:80px">  </li>
                        </td>       
                        <tr>
        <td>코칭제목</td>
        <td>
        <li><input type="text" name="id" itemname='아이디'  placeholder="코칭제목을 기록하세요"/></li>
		</td>
        </tr>
        <tr>
        <td>코칭내용</td>
        <td>
        <li><input type="text" name="id" itemname='아이디'  placeholder="코칭내용을 기록하세요"/></li>
		</td>
        </tr>
        <tr>
        <td>파일첨부</td>
        <td>
        <li><input type="text" name="id" itemname='아이디'  placeholder="코칭자료를 파일로 올리세요"/></li>
		</td>
        </tr>
        <tr>
        <td>코치평가</td>
        <td>
        <li><input type="text" name="id" itemname='아이디'  placeholder="1-5까지 평가해주세요"/></li>
		</td>
        </tr>
        <tr>
        <td>과제안내</td>
        <td>
        <li><input type="text" name="id" itemname='아이디'  placeholder="다음회차 과제를 입력하세요"/></li>
		</td>
        </tr>
        <tr>
        <td>코치의견</td>
        <td>
        <li><input type="text" name="id" itemname='아이디'  placeholder="수업에 대한 의견을 기록하세요"/></li>
		</td>
        </tr>

        </table>
        
        </form>
 
   </div> 
</div> 
<?
include_once "_foot.php";
?>