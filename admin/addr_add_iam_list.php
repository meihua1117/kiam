<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today = date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/rlatjd_admin.js"></script>
<script>
    //주소록 다운
    function excel_down_() {
        $("#excel_down_form").submit();
        return false;
    }

    function goPage(pgNum) {
        location.href = '?nowPage=' + pgNum + '&search_leb=<?= $search_leb ?>&search_start_date=<?= $search_start_date ?>&search_end_date=<?= $search_end_date ?>&search_key=<?= $search_key ?>&search_word=<?= $search_word ?>';
    }
    $(function() {
        var contHeaderH = $(".main-header").height();
        var navH = $(".navbar").height();
        if (navH != contHeaderH)
            contHeaderH += navH - 50;
        $(".content-wrapper").css("margin-top", contHeaderH);
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - $("#list_paginate").height() - 196;
        if (height < 375)
            height = 375;
        $(".box-body").css("height", height);
    });
</script>
<style>
    thead tr th {
        position: sticky;
        top: 0;
        background: #ebeaea;
        z-index: 10;
    }

    .wrapper {
        height: 100%;
        overflow: auto;
    }

    .content-wrapper {
        min-height: 80% !important;
    }

    .box-body {
        overflow: auto;
        padding: 0px !important
    }
</style>
<div class="wrapper">
    <!-- Top 메뉴 -->
    <? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header_menu.inc.php"; ?>
    <!-- Left 메뉴 -->
    <? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_left_menu.inc.php"; ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>주소록추가관리<small>주소록을 추가하는 리스트입니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">주소록추가관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_dber_down.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
                <div class="col-xs-12" style="padding-bottom:20px">
                    <form method="get" name="search_form" id="search_form" class="form-inline">
                        <div class="box-tools">
                            <div class="input-group">
                                <div class="form-group"></div>
                                <div class="form-group">
                                    <input type="date" name="search_start_date" placeholder="" id="search_start_date" value="<?= $_REQUEST['search_start_date'] ?>" /> ~
                                    <input type="date" name="search_end_date" placeholder="" id="search_end_date" value="<?= $_REQUEST['search_end_date'] ?>" />
                                </div>
                                <div class="form-group">
                                    <select name="search_leb" class="form-control">
                                        <option value="">전체</option>
                                        <option value="50" <? if ($_REQUEST['search_leb'] == "50") echo "selected" ?>>사업자</option>
                                        <option value="22" <? if ($_REQUEST['search_leb'] == "22") echo "selected" ?>>이용자</option>
                                    </select>
                                    <select name="search_key" class="form-control">
                                        <option value="a.mem_id" <? if ($_REQUEST['search_key'] == "a.mem_id") echo "selected" ?>>아이디</option>
                                        <option value="a.grp_2" <? if ($_REQUEST['search_key'] == "a.grp_2") echo "selected" ?>>소그룹명</option>
                                        <option value="a.name" <? if ($_REQUEST['search_key'] == "a.name") echo "selected" ?>>고객이름</option>
                                        <option value="a.recv_num" <? if ($_REQUEST['search_key'] == "a.recv_num") echo "selected" ?>>고객폰</option>
                                    </select>
                                </div>
                                <div class="form-group"></div>
                                <div class="form-group">
                                    <input type="text" name="search_word" id="search_word" class="form-control input-sm pull-right" placeholder="아이디/휴대폰번호" value="<?= $_REQUEST['search_word'] ?>">
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
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                                <col width="33px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>source</th>
                                    <th>name</th>
                                    <th>subname</th>
                                    <th>phone</th>
                                    <th>email</th>
                                    <th>page_title</th>
                                    <th>tag</th>
                                    <th>info</th>
                                    <th>url</th>
                                    <th>com_name</th>
                                    <th>com_type</th>
                                    <th>rank</th>
                                    <th>gen</th>
                                    <th>age</th>
                                    <th>school</th>
                                    <th>marrige</th>
                                    <th>regdate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
                                $startPage = $nowPage ? $nowPage : 1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                if (strpos($search_word, ' ') !== false) {
                                    $word_arr = explode(' ', $search_word);
                                    $search_word1 = trim($word_arr[0]);
                                    $search_word2 = trim($word_arr[1]);
                                    $searchStr .= $search_key ? " AND ({$search_key} LIKE '%" . $search_word1 . "%' and {$search_key} LIKE '%" . $search_word2 . "%') " : null;
                                } else {
                                    $searchStr .= $search_key ? " AND ({$search_key} LIKE '%" . $search_word . "%') " : null;
                                }
                                if ($search_start_date && $search_end_date)
                                    $searchStr .= " AND a.reservation_time >= '{$search_start_date}' and a.reservation_time <= '{$search_end_date}'";
                                if ($search_leb)
                                    $searchStr .= " AND mem_leb='{$search_leb}'";
                                if ($search_status)
                                    $searchStr .= " AND end_status='{$search_status}'";
                                $order = $order ? $order : "desc";
                                $query = "SELECT count(a.phone) FROM sm_data_one a WHERE 1=1 $searchSt";
                                $res    = mysqli_query($self_con, $query);
                                $totalRow    =  mysqli_fetch_array($res);
                                $totalCnt = $totalRow[0];
                                $query = "SELECT a.no, a.source, a.name, a.subname, a.phone, a.email, a.tag, a.info, a.url, a.rank, a.addr, a.gen, a.age, a.school, a.marrige FROM sm_data_one a WHERE 1=1 $searchStr";
                                $excel_sql = $query;
                                $excel_sql = str_replace("'", "`", $excel_sql);
                                $limitStr   = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
                                $number        = $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY a.no DESC $limitStr ";
                                $i = 1;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con, $query);
                                while ($row = mysqli_fetch_array($res)) {
                                    $sql = "select mem_name, mem_phone from Gn_Member where mem_id='{$row['mem_id']}'";
                                    $sresul = mysqli_query($self_con, $sql);
                                    $srow = mysqli_fetch_array($sresul); ?>
                                    <tr>
                                        <td><?= $number-- ?></td>
                                        <td><?= $row['source'] ?></td>
                                        <td><?= $srow['name'] ?></td>
                                        <td><?= $srow['subname'] ?></td>
                                        <td><?= $row['phone'] ?> </td>
                                        <td><?= $row['email'] ?></td>
                                        <td><?= $row['tag'] ?></td>
                                        <td><?= $row['info'] ?></td>
                                        <td><?= $srow['url'] ?></td>
                                        <td><!--<?= $srow['com_name']?>--></td>
                                        <td><!--<?= $row['com_type'] ?>--> </td>
                                        <td><?= $row['rank'] ?></td>
                                        <td><?= $row['addr'] ?></td>
                                        <td><?= $srow['gen'] ?></td>
                                        <td><?= $srow['age'] ?></td>
                                        <td><?= $row['school'] ?> </td>
                                        <td><?= $row['marrige'] ?></td>
                                        <td><!--<?= $row['reservation_time'] ?>--></td>
                                    </tr>
                                <?
                                    $i++;
                                }
                                if ($i == 1) { ?>
                                    <tr>
                                        <td colspan="11" style="text-align:center;background:#fff">
                                            등록된 내용이 없습니다.
                                        </td>
                                    </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?= $totalCnt ?> 건</div>
                </div>
                <div class="col-sm-7">
                    <? echo drawPagingAdminNavi($totalCnt, $nowPage); ?>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_footer.inc.php"; ?>
    <form id="excel_down_form" name="excel_down_form" target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div>
<!--link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script-->