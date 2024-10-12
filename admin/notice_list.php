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
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 267;
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
                    <h1>공지사항<small>공지사항을 관리합니다.</small></h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">공지사항</li>
                    </ol>
                </section>
                <!-- Main content -->
                <section class="content">
                    <div class="row" id="toolbox">
                        <div class="col-xs-12" style="padding-bottom:20px">
                        <?if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='notice_write.php'"><i class="fa fa-download"></i> 작성하기</button>
                        <? }?>
                            <form method="get" name="search_form" id="search_form">
                                <div class="box-tools">
                                    <div class="input-group" style="width: 250px;">
                                        <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="제목">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                                        <col width="50px">
                                        <col width="50px">
                                     </colgroup>
                                    <thead>
                                        <tr>
                                            <th>번호</th>
                                            <th>제목</th>
                                            <th>작성일</th>
                                            <th>조회수</th>
                                            <th>중요</th>
                                            <th>팝업</th>
                                            <th>기간</th>
                                            <th>삭제</th>
                                        </tr>
                                    </thead>
                                <tbody>
                                <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_key ? " AND (title LIKE '%".$search_key."%')" : null;
                                $order = $order?$order:"desc";
                                $query = "SELECT SQL_CALC_FOUND_ROWS * FROM tjd_board WHERE 1=1 and category=1 $searchStr";
                	            $res	    =  mysqli_query($self_con,$query);
                	            $totalCnt	=  mysqli_num_rows($res);
                                $limitStr   =  " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number		=  $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY no DESC $limitStr ";
                                $i = 1;
                                $query .= "$orderQuery";
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {
                                ?>
                                    <tr>
                                        <td><?=$number--?></td>
                                        <td><a href="notice_write.php?no=<?php echo $row['no']?>"><?php echo $row['title'];?></a></td>
                                        <td><?=substr($row['date'],0,10)?></td>
                                        <td><?=$row['view_cnt']?></td>
                                        <td><input type="checkbox" disabled="true" name="important_yn" value="Y" data-no="<?php echo $row['no']?>" <?php echo $row['important_yn']=="Y"?"checked":""?>></td>
                                        <td><input type="checkbox" disabled="true" name="pop_yn" value="Y" data-no="<?php echo $row['no']?>" <?php echo $row['pop_yn']=="Y"?"checked":""?>></td>
                                        <td><input type="text" disabled="true" name="working_date" data-no="<?php echo $row['no']?>" value="<?php echo ($row['start_date']&&$row['end_date'])?$row['start_date'].'-'.$row['end_date']:"날자가 존재하지 않습니다."?>"></td>
                                        <td><a href="javascript:;;" onclick="board_del('<?=$row['no']?>','<?=$_REQUEST['status']?>')">삭제</a></td>
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
                                <?}?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                        </div>
                        <div class="col-sm-7">
                            <?echo drawPagingAdminNavi($totalCnt, $nowPage);?>
                        </div>
                    </div>
                </section><!-- /.content -->
            </div><!-- /content-wrapper -->
            <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
                <input type="hidden" name="grp_id" value="" />
                <input type="hidden" name="box_text" value="" />
                <input type="hidden" name="one_member_id" id="one_member_id" value="" />
                <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
            </form>
            <iframe name="excel_iframe" style="display:none;"></iframe>
<script language="javascript">
function board_del(ids,status){
	if(confirm('삭제하시겠습니까?')){
		$($(".loading_div")[0]).show();		
		$.ajax({
			 type:"POST",
			 url:"/ajax/ajax_session.php",
			 dataType:"json",
			 data:{
					board_del_ids:ids,
					board_del_status:status
				  },
			 success:function(data){location.reload();}
        });
	}	
} 
</script>
<!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      