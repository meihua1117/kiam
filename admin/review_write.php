<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$sql="select * from Gn_review  where review_id='".$review_id."'";
$sresul_num=mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($sresul_num);	


$sql="select * from Gn_lecture  where lecture_id='".$row['lecture_id']."'";
$sresul_num=mysqli_query($self_con,$sql);
$srow=mysqli_fetch_array($sresul_num);	
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
$(function(){
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 196;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
			});		
</script>   
<style>
.loading_div {
    display:none;
    position: fixed;
    left: 50%;
    top: 50%;
    display: none;
    z-index: 1000;
}
#open_recv_div li{list-style: none;}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
</style>
    <div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
    <div class="wrapper">
      <!-- Top 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>      
      
      <!-- Left 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>      
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            실시간 리뷰 관리
            <small>리뷰를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">리뷰관리 페이지</li>
          </ol>
        </section>
        
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
        <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />        
        <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />        
        <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />        
        </form>                

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="box">
                    <form name="sform" id="sform" action="ajax/review.proc.php" method="post" class="my_pay">
                        <input type="hidden" name="mode" value="<?php echo $review_id?"review_update":"review_save";?>" />
                        <input type="hidden" name="review_id" value="<?php echo $row['review_id'];?>" />
                        <div id="writeForm" class="box-body">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="200px" />
                                    <col width="*" />
                                </colgroup>                
                                <tr>
                                    <th>평가</th>
                                    <td style="text-align: left;height: 40px;border-bottom: 1px solid #CCC;border-top: 1px solid #CCC;background:#fff">
                                    <input type="radio" name="score" value="5" <?php echo $row['score'] == "5"||$row['score'] == ""?"checked":""?>>★★★★★
                                    <input type="radio" name="score" value="4" <?php echo $row['score'] == "4"?"checked":""?>>★★★★
                                    <input type="radio" name="score" value="3" <?php echo $row['score'] == "3"?"checked":""?>>★★★
                                    <input type="radio" name="score" value="2" <?php echo $row['score'] == "2"?"checked":""?>>★★
                                    <input type="radio" name="score" value="1" <?php echo $row['score'] == "1"?"checked":""?>>★
                                    </td>
                                </tr>
                                <tr>
                                    <th>강의선택</th>
                                    <td style="text-align: left;padding-left:10px;">
                                        <input type="hidden" name="lecture_id" id="lecture_id" value="<?php echo $row['lecture_id']?>">
                                        <input type="text" name="lecture_info" id="lecture_info" readonly value="<?php echo $srow['lecture_info'];?>">
                                        <input type="button" value="강의선택" class="button" id="searchBtn">
                                    </td>
                                </tr>           
                                <tr>
                                    <th>리뷰내용</th>
                                    <td style="text-align: left;padding-left:10px;">
                                        <textarea name="content" id="content" style="width:600px;height:250px"><?php echo $row['content']?></textarea>
                                    </td>
                                </tr>           
                                <tr>
                                    <th>자기소개</th>
                                    <td style="text-align: left;padding-left:10px;"><input type="text" name="profile" id="profile" value="<?php echo $row['profile']?>"></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align:right;">
                                        <input type="button" value="취소" class="button btn btn-primary"  id="cancleBtn">
                                        <input type="button" value="저장" class="button btn btn-primary" id="saveBtn">                        
                                    </td>
                                </tr>            
                            </table>
                        </div>         
                    </form>  
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?> 
    <iframe name="excel_iframe" style="display:none;"></iframe>	
<script language="javascript">
$(function() {
    $('#cancleBtn').on("click", function() {
        location = "review_list.php";
    });
    
    $('#saveBtn').on("click", function() {
        $('#sform').submit();
    });    
})
</script>
          
<link rel='stylesheet' id='jquery-ui-css'  href='//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css?ver=4.5.2' type='text/css' media='all' />
<script src="http://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.min.js" integrity="sha256-55Jz3pBCF8z9jBO1qQ7cIf0L+neuPTD1u7Ytzrp2dqo=" crossorigin="anonymous"></script>
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_recv_title open_2_1"></li>
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
<script>
function newpop(){
    var win = window.open("/mypage_lecture_list_pop.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
}    
$(function() {
    $('#searchBtn').on("click", function() {
        newpop();
    });
})   
</script>