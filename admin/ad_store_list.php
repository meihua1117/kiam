<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
//계정 삭제
function del_row_info(cam_id){
	var msg = confirm('정말로 삭제하시겠습니까?');
	if(msg){
    $.ajax({
      type:"POST",
      url:"/admin/ajax/ad_mo_delete.php",
      data:{cam_id:cam_id},
      success:function(){
        alert('삭제되었습니다.');
        location.reload();
      },
      error: function(){
        alert('삭제 실패');
      }
    });		
	}else{
		return false;
	}
}

//주소록 다운
function excel_down_(){
	$("#excel_down_form").submit();
	return false;
}
function goPage(pgNum) {
  location.href = '?<?=$nowPage?>&nowPage='+pgNum;
}
$(function() {
  var contHeaderH = $(".main-header").height();
  var navH = $(".navbar").height();
  if(navH != contHeaderH)
      contHeaderH += navH - 50;
  $(".content-wrapper").css("margin-top",contHeaderH);
  var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 250;
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
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
  border: 1px solid #ddd!important;
}
#open_recv_div li{list-style: none;}
input, select, textarea {
  vertical-align: middle;
  border: 1px solid #CCC;
}
/* user agent stylesheet */
.zoom {
  transition: transform .2s; /* Animation */
}

.zoom:hover {
  transform: scale(4); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
  border:1px solid #0087e0;
  box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
}

.zoom-2x {
  transition: transform .2s; /* Animation */
}

.zoom-2x:hover {
  transform: scale(2); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
  border:1px solid #0087e0;
  box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
</style>
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
            스토어 광고관리
            <small>스토어 광고를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">스토어 광고관리</li>
          </ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='ad_store_detail.php';return false;"><i class="fa fa-download"></i> 등록</button>
              <form method="get" name="search_form" id="search_form">
                <div class="box-tools">
                  <div class="input-group" style="width: 250px;">
                    <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이름/휴대폰">
                    <div class="input-group-btn">
                      <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>            
              </form>
            </div>            
          </div>
          <form method="post" id="ssForm" name="ssForm">
          <div class="row">
            <div class="box">
              <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                  <colgroup>
                    <col width="30px">
                    <col width="100px">
                    <col width="100px">
                    <col width="100px">
                    <col width="100px">
                    <col width="100px">
                    <col width="100px">
                    <col width="60px">
                    <col width="60px">
                    <col width="20px">
                    <col width="20px">
                    <col width="120px">
                  </colgroup>
                  <thead>
                    <tr>
                      <th>번호</th>
                      <th>광고제목</th>
                      <th>광고소제목</th>
                      <th>광고주</th>
                      <th>의뢰일</th>
                      <th>광고시작</th>
                      <th>광고종료</th>
                      <th>미리보기</th>
                      <th>이동주소</th>
                      <th>사용</th>
                      <th>노출</th>
                      <th>수정삭제</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                  // 검색 조건을 적용한다.
                  $searchStr .= $search_key ? " AND (title LIKE '%".$search_key."%' or client like '%".$search_key."%' )" : null;
                	$order = $order?$order:"desc"; 		
                	$query = "SELECT SQL_CALC_FOUND_ROWS * FROM Gn_Ad_Manager WHERE ad_category='store' $searchStr";
                	$res	    = mysqli_query($self_con, $query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                  $orderQuery .= " ORDER BY cam_id DESC $limitStr";            	
                	$i = 1;
                	$c=0;
                	$query .= $orderQuery;
                	$res = mysqli_query($self_con, $query);
                  while($row = mysqli_fetch_array($res)) {                       	
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td><?=$row['title']?></td>
                        <td><?=$row['sub_title']?></td>
                        <td><?=$row['client']?></td>
                        <td><?=$row['start_date']?></td>
                        <td><?=$row['send_start_date']?></td>
                        <td><?=$row['send_end_date']?></td>
                        <td>
                          <div >
                          <?
                            if($row['img_url']){
                              $thumb_img =  $row['img_url'];
                            }else{
                              $thumb_img =  $default_img;
                            }
                          ?>
                            <a href="<?=$thumb_img?>" target="_blank">
                              <img class="zoom" src="<?=$thumb_img?>" style="width:50px;">
                            </a> 
                          </div>
                        </td>
                        <td>
                          <div style="overflow-x:hidden;width:100px;">
                            <a href="<?=strip_tags($row['move_url'])?>" target="_blank"><?=$row['move_url']?></a>
                          </div>
                        </td>
                        <td><?=$row['use_yn']?></td>
                        <td><?=$row['display_order']?></td>
                        <td>
                          <a href="ad_store_detail.php?cam_id=<?=$row['cam_id']?>">수정</a> / 
                          <a href="javascript:del_row_info('<?=$row['cam_id']?>')">삭제</a></td>                        
                        </tr>
                    <?
                    $c++;
                    $i++;
                    }
                    if($i == 1) {
                    ?>
                        <tr>
                            <td colspan="12" style="text-align:center;background:#fff">
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
            </form>
            
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                	<?echo drawPagingAdminNavi($totalCnt, $nowPage);?>
                </div>
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?> 
      </div><!-- /content-wrapper -->

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />                
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>	
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
<script>
function show_recv(name,c,t,status)
{
	if(!document.getElementsByName(name)[c].value)
	return;
	open_div(open_recv_div,100,1,status);
	if(name=="show_jpg")
	$($(".open_recv")[0]).html("<img src='"+document.getElementsByName(name)[c].value+"' />");
	else if(name=="show_jpg1")
	    $($(".open_recv")[0]).html("<img src='"+document.getElementsByName(name)[c].value+"' />");
	else if(name=="show_jpg2")
	    $($(".open_recv")[0]).html("<img src='"+document.getElementsByName(name)[c].value+"' />");
	else
	$($(".open_recv")[0]).html(document.getElementsByName(name)[c].value.replace(/\n/g,"<br/>"));
	$($(".open_recv_title")[0]).html(t);	
}    
</script>