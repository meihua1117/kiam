<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
//extract($_GET);
// 오늘날짜
// $date_today=date('Ymd');
$sql_last = "select sync_date from Gn_Iam_Contents_Gwc order by sync_date desc limit 1";
$res_last = mysqli_query($self_con,$sql_last);
$row_last = mysqli_fetch_array($res_last);
$date_today=$row_last[0];
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/rlatjd_admin.js"></script>
<script>
    function goPage(pgNum) {
        location.href = '?nowPage='+pgNum;
    }

    function sync_arkda(){
        $.ajax({
            type:"POST",
            url:"ajax/sync_arkda.php",
            data:{sync:"Y"},
            dataType:"json",
            success:function(data){
                if(data.result != "1"){
                    alert('현재 동기화중에 있습니다. 잠시 기다려 주세요.');
                    return;
                }
                else{
                    alert('동기화가 시작되었습니다.');
                    $.ajax({
                        type:"POST",
                        url:"https://www.goodhow.com/crawler/crawler/index_arkda.php",
                        data:{sync_data_arkda:"Y", sync_date:"<?=date('Ymd');?>"},
                        dataType:"json",
                        success:function(data){
                            
                        }
                    })
                }
            },
            error: function(){
                alert('오류 발생.');
                return;
            }
        });
    }
    $(function() {
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
            <h1>굿마켓 상품 동기화결과<small>굿마켓 상품 동기화결과를 확인합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">굿마켓 상품 동기화결과</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <div>
                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="sync_arkda();return false;"><i class="fa fa-download"></i> 아크팜스동기화</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="200px">
                                <col width="200px">
                                <col width="200px">
                                <col width="200px">
                                <col width="200px">
                                <col width="200px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>공급업체</th>
                                    <th>현재상품수</th>
                                    <th>이전상품수</th>
                                    <th>추가감소수</th>
                                    <th>동기화일자</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;

                                $query = "select * from Gn_Gwc_Sync_State";
                                $res	    = mysqli_query($self_con,$query);
                                $totalCnt	=  mysqli_num_rows($res);
                                $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY id DESC $limitStr ";
                                $i = 1;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {
                                    if($row[type] == 1){
                                        $org = "웹빙몰";
                                    }
                                    else{
                                        $org = "아크팜스";
                                    }
                                    $cur_cnt = $row[add_cnt] * 1 + $row[update_cnt] * 1;

                                    if($row[add_cnt]){
                                        $add_min_val = $row[add_cnt];
                                    }
                                    else{
                                        $date_com = date("Y-m-d")." 00:00:00";
                                        $sql_last = "select * from Gn_Gwc_Sync_State where reg_date<'{$date_com}' and type='{$row[type]}' order by id desc limit 1";
                                        $res_last = mysqli_query($self_con,$sql_last);
                                        $row_last = mysqli_fetch_array($res_last);
                                        $cur_last = $row_last[add_cnt] * 1 + $row_last[update_cnt] * 1;

                                        $add_min_val = $cur_cnt * 1 - $cur_last * 1;
                                    }
                                ?>
                                <tr>
                                    <td><?=$number--?></td>
                                    <td><?=$org?></td>
                                    <td><?=$cur_cnt?></td>
                                    <td><?=$row[update_cnt]?></td>
                                    <td><?=$add_min_val?></td>
                                    <td><?=$row['reg_date']?></td>
                                </tr>
                                <?$i++;
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
            </div><!-- /.col -->
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                    <?=drawPagingAdminNavi($totalCnt, $nowPage);?>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <iframe name="excel_iframe" style="display:none;"></iframe>
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
</div><!--wrapper-->
