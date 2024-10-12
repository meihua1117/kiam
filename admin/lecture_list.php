<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
$(function(){
    $('.switch').on("change", function() {
        var no = $(this).find("input[type=checkbox]").val();
        var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
		$.ajax({
			 type:"POST",
			 url:"/admin/ajax/lecture.proc.php",
			 data:{
				 mode:"update_status",
				 lecture_id:no,
				 status:status,
				 },
			 success:function(data){
			    //alert('신청되었습니다.');location.reload();
			 }
			})    
    });    
});
function goPage(pgNum) {
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&case=<?php echo $_GET['case'];?>";
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 266;
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
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
/*user agent stylesheet*/
input[type="checkbox" i] {
    background-color: initial;
    cursor: default;
    -webkit-appearance: checkbox;
    box-sizing: border-box;
    margin: 3px 3px 3px 4px;
    padding: initial;
    border: initial;
}
input:checked + .slider {
    background-color: #2196F3;
}
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}
input:checked + .slider {
    background-color: #2196F3;
}
.slider.round {
    border-radius: 34px;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}
input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}
.slider.round:before {
    border-radius: 50%;
}
.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
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
            강연회/교육과정관리
            <small>강연회및 교육과정 신청/승인관리</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">강연/교육관리 페이지</li>
          </ol>
        </section>
        
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
        <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />        
        <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />        
        <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />        
        </form>                

        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
               <div style="padding:10px">
              </div>
              <?php if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
              <?php }?>
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 250px;float:left;">
                    <input type="radio" name="category" value="" <?php echo $category==""?"checked":""?>>전체
                    <input type="radio" name="category" value="강연"  <?php echo $category=="강연"?"checked":""?>>강연
                    <input type="radio" name="category" value="교육"  <?php echo $category=="교육"?"checked":""?>>교육
                    <input type="radio" name="category" value="영상"  <?php echo $category=="영상"?"checked":""?>>영상                                        
                </div>
                <div class="input-group" style="width: 250px;float:left;">
                    <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="강의내역/강사/지역">
                      <div class="input-group-btn">
                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                      </div>                    
                </div>
                <div class="input-group" style="width: 250px;float:right;">    
                    <input type="radio" name="end_date" value="" <?php echo $end_date==""?"checked":""?>>전체
                    <input type="radio" name="end_date" value="Y" <?php echo $end_date=="Y"?"checked":""?>>진행완료
                    <input type="radio" name="end_date" value="N" <?php echo $end_date=="N"?"checked":""?>>진행중                  
                </div>
              </form>
              </div>            
              </div>            
          </div>
          
          <div class="row">
              <div class="box">
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="6%">
                      <col width="6%">
                      <col width="6%">
                      <col width="6%">
                      <col width="11%">
                      <col width="8%">
                      <col width="8%">
                      <col width="8%">
                      <col width="8%">
                      <col width="8%">
                      <col width="8%">
                      <col width="8%">
                      <col width="15%">
                      <col width="8%">
                      <col width="8%">
                      <col width="8%">
                     </colgroup>
                    <thead>
                      <tr>
                        <th style="width:6%;">No</th>
                        <th style="width:6%;">아이디</th>
                        <th style="width:6%;">이름</th>
                        <th style="width:6%;">분야</th>
                        <th style="width:11%;">일정/기간</th>
                        <th style="width:8%;">요일</th>
                        <th style="width:8%">시간</th>
                        <th style="width:8%">강의제목</a></th>
                        <th style="width:8%;">강사이름</th>            
                        <th style="width:8%;">지역</th>            
                        <th style="width:8%;">정원</th>            
                        <th style="width:8%;">비용</th>            
                        <th style="width:15%;">참여대상</th>            
                        <th style="width:8%;">신청확인</th>
                        <th style="width:8%;">수정/삭제</th>
                        <th style="width:8%;">노출여부</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (a.lecture_info LIKE '".$search_key."%' or a.instructor like '".$search_key."%' or a.area like '".$search_key."%' )" : null;
 
                    if($category) $searchStr .= " and category='$category'";
                    if($end_date == "N") 
                        $searchStr .= " and end_date>CURRENT_DATE()";
                    if($end_date == "Y") 
                        $searchStr .= " and end_date<CURRENT_DATE()";
                	
                	$order = $order?$order:"desc"; 		
                	
                	$query = "SELECT count(a.mem_id) FROM Gn_lecture a WHERE 1=1 $searchStr";
                	$res	    = mysqli_query($self_con,$query);
                  $totalRow	=  mysqli_fetch_array($res);	                	
                	$totalCnt = $totalRow[0];
                  
                  $query = "SELECT a.* FROM Gn_lecture a WHERE 1=1 $searchStr";
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                  $orderQuery .= " ORDER BY a.lecture_id DESC $limitStr";            	
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con,$query);
                  while($row = mysqli_fetch_array($res)) {                       	
                    $sql_num="select short_url from Gn_event where m_id='{$row['mem_id']}' and event_idx='{$row['event_idx']}' ";
                    $resul_num=mysqli_query($self_con,$sql_num);
                    $crow=mysqli_fetch_array($resul_num); 	 
                    
                    $sql_num="select mem_name from Gn_Member where mem_id='{$row['mem_id']}' ";
                    $resul_num=mysqli_query($self_con,$sql_num);
                    $mrow=mysqli_fetch_array($resul_num); 
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td style="font-size:12px;"><?=$row['mem_id']?></td>
                        <td style="font-size:12px;"><?=$mrow['mem_name']?></td>
                        <td style="font-size:12px;"><?=$row['category']?></td>
                        <td style="font-size:12px;"><?=$row[start_date]?>~<?=$row['end_date']?></td>
                        <td style="font-size:12px;"><?=$row[lecture_day]?></td>
                        <td style="font-size:12px;"><?=$row[lecture_start_time]?>~<?=$row[lecture_end_time]?></td>
                        <td style="font-size:12px;"><?=$row[lecture_info]?></td>
                        <td style="font-size:12px;"><?=$row[instructor]?></td>
                        <td style="font-size:12px;"><?=$row[area]?></td>
                        <td style="font-size:12px;"><?=$row[max_num]?>명</td>
                        <td style="font-size:12px;"><?=$row[fee]==0?"무료":$row[fee]."원"?></td>
                        <td style="font-size:12px;"><?=$row[target]?></td>
                        <td style="font-size:12px;">
                            <input type="button" value="신청하기" class="button" onclick="viewEvent('<?php echo $crow['short_url']?>')">
                        </td>
                        <td style="font-size:12px;">
                            <a href="lecture_write.php?lecture_id=<?php echo $row['lecture_id'];?>">수정</a>
                            / 
                            <a href="javascript:;;" onclick="deleteRow('<?php echo $row['lecture_id']?>');">삭제</a>
                        </td>                          
                        <td style="font-size:12px;">
                            <label class="switch">
                              <input type="checkbox" name="status" id="stauts_<?php echo $row['lecture_id'];?>" value="<?php echo $row['lecture_id'];?>" <?php echo $row['status']=="Y"?"checked":""?> >
                              <span class="slider round" name="status_round" id="stauts_round_<?php echo $row['lecture_id'];?>"></span>
                            </label>                            
                        </td>                        
                      </tr>
                    <?
                    $c++;
                    $i++;
                    }
                    if($i == 1) {
                    ?>
                        <tr>
                            <td colspan="10" style="text-align:center;background:#fff">
                    	        등록된 내용이 없습니다.
                            </td>
                        </tr>
                    <?
                    }
                    ?> 
                       
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                	<?
                    	echo drawPagingAdminNavi($totalCnt, $nowPage);
                    ?>
                </div>
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?> 
      </div><!-- /content-wrapper -->
      <iframe name="excel_iframe" style="display:none;"></iframe>	
<script language="javascript">
function deleteRow(lecture_id) {
    if(confirm('삭제하시겠습니까?')) {
        location = "ajax/lecture.proc.php?mode=del&lecture_id="+lecture_id;
    }
}
function viewEvent(str){
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
}    
</script>
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_recv_title open_2_1"></li>
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>