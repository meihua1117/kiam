<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
function goPage(pgNum) {
    location.href = '?nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&case=<?php echo $_GET['case'];?>";
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 270;
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
            회원 데일리 메시지 관리
            <small>회원 데일리 메시지를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">회원 데일리 메시지관리</li>
          </ol>
        </section>            

        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
               <div style="padding:10px">
              </div>
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='daily_msg_list_service.php';return false;"><i class="fa fa-download"></i> 분양사 데일리 메시지 관리</button>
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 350px;">
                  <input type="text" name="search_recom" id="search_recom" class="form-control input-sm pull-right" style="width:32%;" placeholder="소속">
                  <input type="text" name="search_name" id="search_name" class="form-control input-sm pull-right" style="width:32%;" placeholder="이름">
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" style="width:32%;" placeholder="타이틀/콘텐츠">
                  <div class="input-group-btn">
                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
              </div>            
              </div>            
          </div>
          
          <div class="row">
              <div class="box">
                <div class="box-body">
                  <table id="phone_table" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="70px">
                      <col width="70px">
                      <col width="70px">
                      <col width="70px">
                      <col width="200px">
                      <col width="150px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
                      <col width="120px">
                      <col width="120px">
                      <col width="120px">
                     </colgroup>
                    <thead>
                      <tr>
                        <td ><input type="checkbox" class="check" id="check_all_member" value="0">&nbsp;No</td>
                        <td >소속</td>
                        <td >아이디</td>
                        <td >이름</td>
                        <td >타이틀</td>
                        <td >발송폰번호</td>
                        <td >주소록이름</td>
                        <td >주소건수</td>
                        <td >발송일수</td>
                        <td >일발송량</td>
                        <td >발송시작일</td>
                        <td >발송마감일</td>
                        <td >등록일</td>
                        <td ><button class="btn btn-primary" onclick="deleteMultiRow();return false;"><i class="fa"></i>삭제</button></td>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                  // 검색 조건을 적용한다.
                  $searchStr .= $search_key ? " AND (a.title LIKE '".$search_key."%' or a.content like '".$search_key."%'  )" : null;
                  $searchStr .= $search_name ? " AND (b.mem_name LIKE '".$search_name."%')" : null;
                  $searchStr .= $search_recom ? " AND (b.site LIKE '".$search_recom."%')" : null;
                	$order = $order?$order:"desc";
                	$query = "
                        	SELECT 
                          count(a.mem_id)
                        	FROM Gn_daily a inner join Gn_Member b on a.mem_id=b.mem_id
                        	WHERE 1=1 
                	              $searchStr";
                	              
                	$res	    = mysqli_query($self_con, $query);
                  //$totalCnt	=  mysqli_num_rows($res);	
                  $totalRow	=  mysqli_fetch_array($res);	                	
                	$totalCnt = $totalRow[0];
                  

                  $query = "
                  SELECT 
                    a.*, b.mem_name, b.site
                  FROM Gn_daily a inner join Gn_Member b on a.mem_id=b.mem_id
                  WHERE 1=1 
                        $searchStr";
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                	
                    $orderQuery .= "
                    	ORDER BY a.gd_id DESC
                    	$limitStr
                    ";            	
                	
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con, $query);
                    while($row = mysqli_fetch_array($res)) {      				
                      $ksql="select * from Gn_MMS_Group where idx='$row[group_idx]'";
                      $kresult=mysqli_query($self_con, $ksql) or die(mysqli_error($self_con));
                      $krow = mysqli_fetch_array($kresult);

                      $sql="select count(*) cnt from Gn_daily_date where gd_id='$row[gd_id]'";
                      $sresult=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
                      $srow = mysqli_fetch_array($sresult);
                  ?>
                      <tr>
                            <td><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['gd_id']?>">&nbsp;&nbsp;<?=$number--?></td>
                            <td style="font-size:12px;"><?=$row['site']?></td>
                            <td style="font-size:12px;"><?=$row['mem_id']?></td>
                            <td style="font-size:12px;"><?=$row['mem_name']?></td>
                            <td style="font-size:12px;"><?=$row['title']?></td>
                            <td style="font-size:12px;"><?=$row['send_num']?></td>
                            <td><?=$krow['grp']?></td>
                            <td><?=$row['total_count']?></td>
                            <td><?=$srow['cnt']?></td>
                            <td><?=$row['daily_cnt']?></td>
                            <td><?=$row['start_date']?></td>
                            <td><?=$row['end_date']?></td>
                            <td><?=$row['reg_date']?></td>
                            <td><a href="edit_daily_msg.php?type=mem&gd_id=<?=$row['gd_id']?>">수정</a>/<a href="javascript:delete_daily(<?=$row['gd_id']?>)">삭제</a></td>
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
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_recv_title open_2_1"></li>
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
<script>
$(function() {
    $('.check').on("click",function(){
        if($(this).prop("id") == "check_all_member"){
            if($(this).prop("checked"))
                $('.check').prop("checked",true);
            else
                $('.check').prop("checked",false);
        }else if($(this).prop("id") == "check_one_member"){
            if(!$(this).prop("checked"))
                $('#check_all_member').prop("checked",false);
        }
    });
});
function deleteMultiRow() {
    if(confirm('삭제하시겠습니까?')) {
        var check_array = $("#phone_table").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });
        location.href = "ajax/delete_daily_msg.php?type=mem&gd_id="+no_array.toString();
    }
}
function delete_daily(gd_id){
  if(confirm('삭제하시겠습니까?')) {
    location.href = "ajax/delete_daily_msg.php?type=mem&gd_id="+gd_id;
  }
}
</script>