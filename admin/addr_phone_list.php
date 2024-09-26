<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/rlatjd_admin.js"></script>
<script>
    //주소록 다운
    function excel_down_(){
        $("#excel_down_form").submit();
        return false;
    }
    function goPage(pgNum) {
        location.href = '?nowPage='+pgNum+'&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>&search_key=<?=$search_key?>&search_word=<?=$search_word?>';
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
            <h1>휴대폰주소록<small>휴대폰주소록을 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">휴대폰주소록</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_phone_down.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
                <div class="col-xs-12" style="padding-bottom:20px">
                    <form method="get" name="search_form" id="search_form" class="form-inline">
                        <div class="box-tools">
                            <div class="input-group"  >
                                <div class="form-group"></div>
                                <div class="form-group">
                                    <input type="date" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST['search_start_date']?>"/> ~
                                    <input type="date"  name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST['search_end_date']?>"/>
                                </div>
                                <div class="form-group">
                                    <select name="search_key" class="form-control" >
                                        <option value="a.dest" <?if($_REQUEST['search_key'] == "a.dest") echo "selected"?>>전화번호</option>
                                        <option value="a.grp" <?if($_REQUEST['search_key'] == "a.grp") echo "selected"?>>그룹명</option>
                                        <option value="a.msg_text" <?if($_REQUEST['search_key'] == "a.msg_text") echo "selected"?>>고객이름</option>
                                        <option value="a.msg_url" <?if($_REQUEST['search_key'] == "a.msg_url") echo "selected"?>>고객폰</option>
                                    </select>
                                </div>
                                <div class="form-group"></div>
                                <div class="form-group">
                                    <input type="text" name="search_word" id="search_word" class="form-control input-sm pull-right" placeholder="아이디/휴대폰번호" value="<?=$_REQUEST['search_word']?>">
                                </div>
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
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="60px">
                                <col width="100px">
                                <col width="140px">
                                <col width="130px">
                                <col width="200px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>번호</th>
                                    <th>아이디</th>
                                    <th>소유자명</th>
                                    <th>전화번호</th>
                                    <th>그룹명</th>
                                    <th>고객이름</th>
                                    <th>전화번호</th>
                                    <th>동기화시간</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_word ? " AND ({$search_key} LIKE '%".$search_word."%') " : null;
                                if($search_start_date && $search_end_date)
                                    $searchStr .= " AND a.reservation_time >= '$search_start_date' and a.reservation_time <= '$search_end_date'";
                                if($search_status)
                                    $searchStr .= " AND end_status='$search_status'";
                                $order = $order?$order:"desc";
                                $query = "SELECT count(a.dest) FROM sm_data a ";
                                $query .= " WHERE 1=1 $searchStr";
                                $res    = mysqli_query($self_con, $query);
                                $totalRow	=  mysqli_fetch_array($res);
                                $totalCnt = $totalRow[0];
                                $query = "SELECT a.dest, a.grp, a.msg_text, a.msg_url, a.reservation_time FROM sm_data a";
                                $query .= " WHERE 1=1 $searchStr";

                                $excel_sql=$query;
                                $excel_sql=str_replace("'","`",$excel_sql);
                                $limitStr   = " LIMIT ".(($startPage-1) * $pageCnt).", ".$pageCnt;
                                $number		= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY a.seq DESC $limitStr ";
                                $i = 1;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con, $query);
                                while($row = mysqli_fetch_array($res)) {
                                    $sql="select mem_id, mem_name from Gn_Member where REPLACE(mem_phone, '-', '')='{$row['dest']}'";
                                    $sresul=mysqli_query($self_con, $sql);
                                    $srow=mysqli_fetch_array($sresul);
                                    if($srow['mem_id'] == "")
                                    {
                                        $sql="select m.mem_id, mem_name from Gn_Member m inner join Gn_MMS_Number n on m.mem_id=n.mem_id where n.sendnum='{$row['dest']}'";
                                        $sresul=mysqli_query($self_con, $sql);
                                        $srow=mysqli_fetch_array($sresul);
                                    }?>
                                    <tr>
                                        <td><?=$number--?></td>
                                        <td><?=$srow['mem_id']?></td>
                                        <td><?=$srow['mem_name']?></td>
                                        <td><?=$row['dest']?></td>
                                        <td><?=$row['grp']?></td>
                                        <td><?=$row['msg_text']?></td>
                                        <td><?=$row['msg_url']?></td>
                                        <td><?=$row['reservation_time']?></td>
                                    </tr>
                                <?
                                    $i++;
                                }
                                if($i == 1) {?>
                                    <tr>
                                        <td colspan="11" style="text-align:center;background:#fff">
                                            등록된 내용이 없습니다.
                                        </td>
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
                    <? echo drawPagingAdminNavi($totalCnt, $nowPage); ?>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div>
<!--link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script-->                
