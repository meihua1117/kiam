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
    $('input[name=diber]').on("click",function() {
       var diber = $(this).is(":checked")==true?"Y":"";
		$.ajax({
			type:"POST",
			url:"/admin/ajax/board_save.php",
			data:{mode:"diber", diber:diber,no:$(this).data("no")},
			dataType: 'html',
			success:function(data){
			},
			error: function(){
			  alert('로딩 실패');
			}
		});	       
});
});
 
function goPage(pgNum) {
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&type=<?php echo $type;?>";
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 246;
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
            분양사업자 공지사항
            <small>분양사업자 공지사항을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">분양사업자 공지사항</li>
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
              <?php if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                <?php if($_SESSION['one_member_subadmin_id'] == "" || $_SESSION['one_member_subadmin_domain'] != $HTTP_HOST) {?>
                  <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='biz_notice_write.php'"><i class="fa fa-download"></i>작성하기</button>
                <?php }}?>
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 250px;">
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="제목">
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
                  <table id="example1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="60px">
                      <col width="600px">
                      <col width="100px">
                      <col width="100px">
                      <?if($_SESSION['one_member_subadmin_id'] == "" || $_SESSION['one_member_subadmin_domain'] != $HTTP_HOST) {?>
                      <col width="50px">
                      <?}?>
                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>제목</th>
                        <th>작성일</th>
                        <th>조회수</th>
                         <?if($_SESSION['one_member_subadmin_id'] == "" || $_SESSION['one_member_subadmin_domain'] != $HTTP_HOST) {?>
                        <th>삭제</th>
                         <?}?>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 40;
                  // 검색 조건을 적용한다.
                  $searchStr .= $search_key ? " AND (a.mem_id LIKE '%".$search_key."%' or a.mem_phone like '%".$search_key."%' or a.mem_name like '%".$search_key."%' or b.sendnum like '%".$search_key."%')" : null;
                	
                	$order = $order?$order:"desc"; 		
                	$query = "SELECT SQL_CALC_FOUND_ROWS * FROM tjd_sellerboard WHERE 1=1 and category=11 $searchStr";
                	$res	    = mysqli_query($self_con,$query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                  $orderQuery .= " ORDER BY no DESC $limitStr ";            	
                	$i = 1;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con,$query);
                  while($row = mysqli_fetch_array($res)) {                       	
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td>
                        <?php if($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) {?>
                            <a onclick="onView('<?=$row['no']; ?>')" ><?php echo $row['title'];?></a>
                            <script type="text/javascript">
                                function onView(board_no){
                                        $("#modal-tjd_sellerboard_"+board_no).modal("show");
                                }
                            </script>
                              <!-- board모달 -->
                              <div class="modal fade" id="modal-tjd_sellerboard_<?=$row['no']; ?>">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                              <h3 class="modal-title" style="text-align: center;"><?php echo $row['title'];?></h3>
                                          </div>
                                          <div class="modal-body">
                                        
                                            <?= $row['content'];?>

                                            <div class="p1">
                                                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <th>연락처</th>
                                                    <td><?=$row['phone']?></td>
                                                    <th>이메일</th>
                                                    <td><?=$row['email']?></td>
                                                </tr>       
                                             
                                                <tr>
                                                    <th>답변</th>
                                                    <td><?=$row['reply']?></td>
                                                    <th></th>
                                                    <td></td>
                                                </tr>                      
                                                </table>
                                            </div>
                                          </div>
                                          <div class="modal-footer" style="text-align: center;">
                                              <a style="color:white;" class="btn btn-danger" data-dismiss="modal"> 종 료 </a>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                           <? } else {?>
                            <a href="biz_notice_write.php?no=<?php echo $row['no']?>"><?php echo $row['title'];?></a>
                        <? } ?>
                        <td>
                            <?=substr($row['date'],0,10)?>
                        </td>
                        <td><?=$row['view_cnt']?></td>

                        <?php if($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) {} else {?>
                        <td><a href="javascript:;;" onclick="board_del('<?=$row['no']?>','<?=$_REQUEST['status']?>')">삭제</a></td>
                      <? } ?>
                      </tr>
                    <?
                    $i++;
                    }
                    if($i == 1) {
                    ?>
                        <tr>
                            <td colspan="4" style="text-align:center;background:#fff">
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

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />                
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>	

<script language="javascript">
    
function changeLevel(mem_code) {
    var mem_leb = $('#mem_leb'+mem_code+" option:selected").val();
    var data = {"mode":"change","mem_code":"'+mem_code+'","mem_leb":"'+mem_leb+'"};
    $.ajax({
		type:"POST",
		url:"/admin/ajax/user_level_change.php",
		dataType:"json",
		data:{mode:"change",mem_code:mem_code,mem_leb:mem_leb},
		success:function(data){
			alert('변경이 완료되었습니다.');
		},
		error: function(){
		  alert('초기화 실패');
		}
	});	
    
//    alert(mem_code);
}

function loginGo(mem_id, mem_pw, mem_code) {
    $('#one_id').val(mem_id);
    $('#mem_pass').val(mem_pw);
    $('#mem_code').val(mem_code);
    $('#login_form').submit();
}

function board_del(ids,status)
{
	if(confirm('삭제하시겠습니까?'))
	{
		$($(".loading_div")[0]).show();		
		$.ajax({
			 type:"POST",
			 url:"/ajax/ajax_sellerboard.php",
			 dataType:"json",
			 data:{
					sboard_del_ids:ids,
					board_del_status:status
				  },
			 success:function(data){location.reload();}
			})
	}	
} 
function board_del(ids,status)
{
	if(confirm('삭제하시겠습니까?'))
	{
		$($(".loading_div")[0]).show();		
		$.ajax({
			 type:"POST",
			 url:"/ajax/ajax_session.php",
			 dataType:"html",
			 data:{
					sboard_del_ids:ids,
					board_del_status:status
				  },
			 success:function(data){location.reload();}
			})
	}	
} 
</script>