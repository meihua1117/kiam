<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
function iam_board_del(ids){
    if(confirm('삭제하시겠습니까??')){
        $($(".loading_div")[0]).show();
        $.ajax({
            type:"POST",
            url:"/ajax/ajax_sellerboard.php",
            dataType:"html",
            data:{
                board_del_ids:ids
            },
            success:function(data){
                location.reload();
            }
        });
    }
}
/*function onView(no,title,phone,email){
    $("#title").html(title);
    $("#content").html($("#content_" + no).val());
    $("#phone").html(phone);
    $("#email").html(email);
    $("#reply").html($("#reply_" + no).val());
    $("#modal-sellerboard").modal("show");
}*/
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
            <h1>아이엠분양사 공지사항<small>아이엠분양사 공지사항을 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">아이엠 분양사 공지사항</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="display: flex">
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
                    <?if($_SESSION['iam_member_admin_id'] != "onlyonemaket"){?>
                    <div class="col-xs-12" style="padding-bottom:20px">
                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='iam_notice_write.php'"><i class="fa"></i>작성하기</button>
                    </div>
                    <?}?>
                </div>
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="60px">
                                <col width="500px">
                                <col width="100px">
                                <col width="100px">
                                <col width="50px">
                                <col width="50px">
                                <col width="100px">
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
                            $pageCnt = 40;
                            // 검색 조건을 적용한다.
                            $searchStr .= $search_key ? " AND (title '%".$search_key."%' or content like '%".$search_key."%' or id like '%".$search_key."%')" : null;
                            $order = $order?$order:"desc";
                            $query = "SELECT SQL_CALC_FOUND_ROWS * FROM tjd_sellerboard WHERE 1=1 and category=10 $searchStr";
                            $res	    = mysqli_query($self_con,$query);
                            $totalCnt	=  mysqli_num_rows($res);
                            $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number			= ($nowPage - 1) * $pageCnt + 1;
                            $orderQuery .= " ORDER BY no DESC $limitStr ";
                            $i = 1;
                            $query .= "$orderQuery";
                            $res = mysqli_query($self_con,$query);
                            while($row = mysqli_fetch_array($res)) {?>
                                <tr>
                                    <td><?=$number++?></td>
                                    <td>
                                        <a href="iam_notice_write.php?no=<?php echo $row['no']?>"><?=$row['title'];?></a>
                                    </td>
                                    <td><?=substr($row[date],0,10)?></td>
                                    <td><?=$row['view_cnt']?></td>
                                    <td><input type="checkbox" disabled="true" name="important_yn" value="Y" data-no="<?php echo $row['no']?>" <?php echo $row['important_yn']=="Y"?"checked":""?>></td>
                                    <td><input type="checkbox" disabled="true" name="pop_yn" value="Y" data-no="<?php echo $row['no']?>" <?php echo $row['pop_yn']=="Y"?"checked":""?>></td>
                                    <td><input type="text" disabled="true" name="working_date" data-no="<?php echo $row['no']?>" value="<?php echo ($row['start_date']&&$row['end_date'])?$row['start_date'].'-'.$row['end_date']:"날자가 존재하지 않습니다."?>"></td>
                                    <td><a href="javascript:;;" onclick="iam_board_del('<?=$row[no]?>')">삭제</a></td>
                                </tr>
                                <?$i++;
                            }
                            if($i == 1) {?>
                                <tr>
                                    <td colspan="4" style="text-align:center;background:#fff">등록된 내용이 없습니다.</td>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                    <?echo drawPagingAdminNavi($totalCnt, $nowPage);?>
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div>

<!-- board모달 -->
<div class="modal fade" id="modal-sellerboard" tabindex="-1" role="dialog" style="z-index: 1100;top: 20px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" style="text-align: center;" id="title"><?=$row['title'];?></h3>
            </div>
            <div class="modal-body">
                <?//=$row['content'];?>
                <div id = "content">

                </div>
                <div class="p1">
                    <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <th>연락처</th>
                            <td id="phone"><?=$row[phone]?></td>
                            <th>이메일</th>
                            <td id="email"><?=$row[email]?></td>
                        </tr>
                        <tr>
                            <th>답변</th>
                            <td id="reply"><?=$row[reply]?></td>
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
