<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
if($_REQUEST['dir'] == "desc"){
    $dir = "asc";
}else{
    $dir = "desc";
}
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    //계정 삭제
    function del_service(idx){
        var msg = confirm('정말로 삭제하시겠습니까?');
        if(msg){
            $.ajax({
                type:"POST",
                url:"/admin/ajax/gn_video_ajax.php",
                data:{mode:"del",no:idx},
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
    function goPage(pgNum) {
        location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&orderField=<?=$orderField;?>&dir=<?=$dir;?>";
    }
    $(function() {
        var contHeaderH = $(".main-header").height();
        var navH = $(".navbar").height();
        if(navH != contHeaderH)
            contHeaderH += navH - 50;
        $(".content-wrapper").css("margin-top",contHeaderH);
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 280;
        if(height < 375)
            height = 375;
        $(".box-body").css("height",height);
    });
</script>
<style>
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
        border: 1px solid #ddd!important;
    }
    input, select, textarea {
        vertical-align: middle;
        border: 1px solid #CCC;
    }
    thead tr th{
        text-align: center;position: sticky; top: 0; background: #ebeaea;
    }
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
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
            <h1>셀링 동영상 업로드 관리<small>셀링 동영상 업로드 정보를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">셀링 동영상 업로드 관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <form method="get" name="search_form" id="search_form">
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='selling_video_write.php';return false;"><i class="fa fa-download"></i> 등록</button>
                    <button class="btn btn-default pull-right" style="margin-right: 5px;" onclick="location='selling_video_list.php';return false;"><i class="fa"></i> 셀링동영상</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='selling_alert_list.php';return false;"><i class="fa"></i> 셀링알림</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='iam_video_list.php';return false;"><i class="fa"></i> 아이엠동영상</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='iam_alert_list.php';return false;"><i class="fa"></i> 아이엠알림</button>
                    <div class="input-group" style="width: 250px;">
                        <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="검색키워드">
                        <div class="input-group-btn">
                            <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row" style="margin-top:10px">
                <div class="box">
                    <div class="box-body" style="overflow: auto !important">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="50px">
                                <col width="200px">
                                <col width="200px">
                                <col width="30px">
                                <col width="30px">
                                <col width="80px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>번호</th>
                                    <th>동영상제목</th>
                                    <th>링크주소</th>
                                    <th>사용</th>
                                    <th>노출</th>
                                    <th>수정/삭제</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;
                            // 검색 조건을 적용한다.
                            $searchStr .= $search_key ? " AND (title LIKE '%".$search_key."%' or link like '%".$search_key."%')" : null;
                            $order = $order?$order:"desc";
                            $query = "SELECT SQL_CALC_FOUND_ROWS * FROM gn_video WHERE `type`='selling' $searchStr";
                            $res	    = mysqli_query($self_con,$query);
                            $totalCnt	=  mysqli_num_rows($res);
                            $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number			= ($nowPage - 1) * $pageCnt + 1;
                            $orderQuery .= " ORDER BY no $limitStr ";
                            $i = 1;
                            $query .= "$orderQuery";
                            $res = mysqli_query($self_con,$query);
                            while($row = mysqli_fetch_array($res)) {?>
                                <tr>
                                    <td><?=$number++?></td>
                                    <td><?=$row['title']?></td>
                                    <td><?=$row['link']?></td>
                                    <td style="text-align: right"><?=$row['use_status'] == 1?"Y":"N"?></td>
                                    <td style="text-align: right"><?=$row['display']?></td>
                                    <td>
                                        <a href="selling_video_write.php?no=<?=$row['no']?>">수정</a> /
                                        <a href="javascript:del_service('<?=$row['no']?>')">삭제</a>
                                    </td>
                            </tr>
                                <?
                                $i++;
                            }
                            if($i == 1) {?>
                                <tr>
                                    <td colspan="11" style="text-align:center;background:#fff">등록된 내용이 없습니다.</td>
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
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <iframe name="excel_iframe" style="display:none;"></iframe>
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
</div><!-- /.wrapper -->