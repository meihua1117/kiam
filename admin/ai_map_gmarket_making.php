<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");

$sql = "SELECT id, round_num, state_flag, iam_count FROM crawler_gm_seller_info_ad ORDER BY id DESC LIMIT 1";
$result = mysqli_query($self_con, $sql);
while($res = mysqli_fetch_array($result)){
    $id = $res['id'];
}
$round_num = (int)$id + 1;

?>

<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    function goPage(pgNum) {
        location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&type=<?php echo $type;?>";
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
    .loading_div {display:none;position: fixed;left: 50%;top: 50%;display: none;z-index: 1000;}
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
                IAM자동등록관리
                <small>아이엠을 자동으로 등록합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">아이엠자동등록관리</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:10px">
                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location.href='reg_shopping_iam.php'"><i class="fa fa-download"></i>IAM자동생성등록</button>
                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location.href='ai_map_gmarket_list.php'"><i class="fa fa-download"></i>사업자공급관리</button>
                    <form method="get" name="search_form" id="search_form">
                        <div class="box-tools">
                            <div class="input-group" style="width: 250px;">
                                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="채널/제목/메모" value="<?=$search_key?>">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" style="height: 30px;"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-striped"  id="gm_iam">
                            <colgroup>
                                <col width="60px">
                                <col width="100px">
                                <col width="200px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="200px">
                                <col width="100px">
                                <col width="100px">
                            </colgroup>
                            <thead>
                            <tr>
                            <th>NO</th>
                            <th>채널</th>
                            <th>제목</th>
                            <th>생성건수</th>
                            <th>검색조건</th>
                            <th>결과보기</th>
                            <th>메모</th>
                            <th>등록일</th>
                            <th>삭제</th>
                            </tr>
                            </thead>
                            <tbody id="iam_body">
                            <?php
                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;

                            $sql_where = '';
                            if($_REQUEST['search_key']){
                                $sql_where = " where web_type like '%".$_REQUEST['search_key']."%' or reg_title like '%".$_REQUEST['search_key']."%' or reg_memo like '%".$_REQUEST['search_key']."%' ";
                            }

                            // $query = "select * from crawler_gm_seller_info";
                            $query = "select * from crawler_shop_admin";
                            $query_cnt = $query . $sql_where;
                            // echo $query; exit;
                            $res = mysqli_query($self_con, $query_cnt);
                            $totalCnt = mysqli_num_rows($res);

                            $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number = $totalCnt - ($nowPage - 1) * $pageCnt;

                            $order = " order by reg_date desc";
                            $query1 = $query.$sql_where.$order.$limitStr;
                            // echo $query1;
                            $res1 = mysqli_query($self_con, $query1);
                            while($row=mysqli_fetch_array($res1))
                            {
                            ?>
                                <tr>
                                    <th><?=$number--?></th>
                                    <th><?=$row['web_type']?></th>
                                    <th><?=$row['reg_title']?></th>
                                    <th><?=$row['reg_cnt']?></th>
                                    <th><?=$row['reg_search_busi_type']?$row['reg_search_busi_type'].",":""?><?=$row['reg_search_busi']?$row['reg_search_busi'].",":""?><?=$row['reg_search_addr']?$row['reg_search_addr'].",":""?><?=$row['reg_search_keyword']?$row['reg_search_keyword']:""?><?=$row['iamstore_link']?$row['iamstore_link']:""?></th>
                                    <th><?=$row['res_crawler']?></th>
                                    <th><?=$row['reg_memo']?></th>
                                    <th><?=$row['reg_date']?></th>
                                    <th><a href="javascript:delete_set(<?=$row['id']?>)">삭제</a></th>
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
                    <?
                    echo drawPagingAdminNavi($totalCnt, $nowPage);
                    ?>
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>    
    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
<script language="javascript">
    let round = 0;
    $( document ).ready( function() {});

    function loginGo(mem_id, mem_pw, mem_code) {
        $('#one_id').val(mem_id);
        $('#mem_pass').val(mem_pw);
        $('#mem_code').val(mem_code);
        $('#login_form').submit();
    }

    function delete_set(id){
        if(confirm("삭제하시겠습니까?")){
            $.ajax({
                type:"POST",
                dataType:"json",
                data:{del:true, set_idx:id},
                url:"ajax/reg_ai_shop_crawler.php",
                success:function(data){
                    if(data == 1){
                        alert("삭제 되었습니다.");
                        location.reload();
                    }
                }
            });
        }
    }
</script>
  