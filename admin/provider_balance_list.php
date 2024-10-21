<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today = date("Y-m-d");
$search_year = $search_year ? $search_year : date("Y");
$search_month = $search_month ? sprintf("%02d", $search_month) : sprintf("%02d", date("m"));
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/rlatjd_admin.js"></script>
<script>
    function go_item_list() {
        location.href = "payment_item_list.php";
    }
    //주소록 다운
    function excel_down_() {
        $("#excel_down_form").submit();
        return false;
    }

    function goPage(pgNum) {
        location.href = '?nowPage=' + pgNum + '&search_status=<?= $search_status ?>&stop_yn=<?= $stop_yn ?>&search_start_date=<?= $search_start_date ?>&search_end_date=<?= $search_end_date ?>&search_key=<?= $search_key ?>';
    }

    function payment_save(fm) {
        if (confirm('상태를 변경하시겠습니까?')) {
            $(fm).submit();
            return false;
        }
    }

    function addcomma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }

    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }

    function deleteRow(no) {
        if (confirm('삭제하시겠습니까?')) {
            $.ajax({
                type: "POST",
                url: "/admin/ajax/payment_delete.php",
                data: {
                    no: no
                },
                success: function(data) {
                    alert('정확히 삭제되었습니다.');
                    location.reload();
                }
            })
        }
    }

    function change_per(type, mem_id) {
        var service_val = center_val = 0;
        if (type == 'service') {
            service_val = $("#service_per").val();
        } else {
            center_val = $("#center_per").val();
        }
        $.ajax({
            type: "POST",
            url: "/admin/ajax/user_gwclevel_change.php",
            data: {
                mode: 'change_per',
                type: type,
                seller_id: mem_id,
                service_val: service_val,
                center_val: center_val
            },
            success: function(data) {
                alert('변경되었습니다.');
                location.reload();
            }
        })
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
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    input,
    select,
    textarea {
        vertical-align: middle;
        border: 1px solid #CCC;
    }

    user agent stylesheet input[type="checkbox" i] {
        background-color: initial;
        cursor: default;
        -webkit-appearance: checkbox;
        box-sizing: border-box;
        margin: 3px 3px 3px 4px;
        padding: initial;
        border: initial;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    .slider.round {
        border-radius: 34px;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    .slider.round:before {
        border-radius: 50%;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .per_change {
        background-color: #3c8dbc;
        color: white;
        padding: 5px;
        float: right;
        margin-top: 5px;
    }

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
            <h1>굿마켓 공급사 정산관리<small>굿마켓 공급사의 정산정보를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">굿마켓 공급사 정산관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <div>
                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location.href='card_gwc_contents_list_provider.php'">공급사상품등록관리</button>
                    </div>
                    <form method="get" name="search_form" id="search_form " class="form-inline">
                        <div class="box-tools">
                            <div class="input-group">
                                <div class="form-group">
                                    <input type="date" style="height: 30px" name="search_start_date" placeholder="" id="search_start_date" value="<?= $_REQUEST['search_start_date'] ?>" /> ~
                                    <input type="date" style="height: 30px" name="search_end_date" placeholder="" id="search_end_date" value="<?= $_REQUEST['search_end_date'] ?>" />
                                </div>
                                <div class="form-group">
                                    <input type="text" style="margin-left:5px" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디">
                                </div>
                                <div class="input-group-btn">
                                    <button style="margin-left:5px" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
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
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>번호</th>
                                    <th>상세정보</th>
                                    <th>회원등급(%)</th>
                                    <th>센터여부(%)</th>
                                    <th>셀링/IAM소속</th>
                                    <th>이름</th>
                                    <th>아이디</th>
                                    <th>판매P</th>
                                    <th>추천P</th>
                                    <th>센터P</th>
                                    <th>총수당P</th>
                                    <th>누적P</th>
                                    <th>누적지급액</th>
                                    <th>잔액P</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
                                $startPage = $nowPage ? $nowPage : 1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_key ? " AND (g.seller_id LIKE '%" . $search_key . "%') " : null;
                                if ($search_start_date && $search_end_date)
                                    $searchStr .= " AND g.reg_date >= '$search_start_date' and g.reg_date <= '$search_end_date'";
                                $order = $order ? $order : "desc";
                                //$query = "select g.* from Gn_Gwc_Order g WHERE g.cash_prod_pay=0 and g.seller_id!='' $searchStr group by g.seller_id";
                                $query = "SELECT ggo.* FROM Gn_Gwc_Order ggo 
                                          JOIN (SELECT seller_id, MAX(id) AS max_id FROM Gn_Gwc_Order WHERE cash_prod_pay = 0  AND seller_id <> '' $searchStr GROUP BY seller_id) AS mggo ON ggo.seller_id = mggo.seller_id AND ggo.id = mggo.max_id ";
                                echo $query;
                                $res        = mysqli_query($self_con, $query);
                                $totalCnt    =  mysqli_num_rows($res);
                                $limitStr       = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
                                $number            = $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY ggo.id DESC $limitStr ";
                                $i = 1;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con, $query);
                                while ($row = mysqli_fetch_array($res)) {
                                    $sql_mem_data = "select mem_name, service_type, site, site_iam, gwc_leb, gwc_center_per, gwc_service_per, mem_cash from Gn_Member where mem_id='{$row['seller_id']}'";
                                    $res_mem_data = mysqli_query($self_con, $sql_mem_data);
                                    $row_mem_data = mysqli_fetch_array($res_mem_data);

                                    if (!$row_mem_data['gwc_service_per']) {
                                        if ($row_mem_data['service_type'] == 2) {
                                            $row_mem_data['gwc_service_per'] = 4;
                                        } else if ($row_mem_data['service_type'] == 3) {
                                            $row_mem_data['gwc_service_per'] = 1;
                                        }
                                    }
                                    if (!$row_mem_data['gwc_center_per']) {
                                        $row_mem_data['gwc_center_per'] = 5;
                                    }

                                    $service = $gwc_leb = "";
                                    if ($row_mem_data['service_type'] == 2) {
                                        $service = "리셀러";
                                    } else if ($row_mem_data['service_type'] == 3) {
                                        $service = "분양사";
                                    }

                                    if ($row_mem_data['gwc_leb'] == 3) {
                                        $gwc_leb = "센터";
                                    }

                                    $sell_money = $recom_money = $center_money = 0;

                                    $sql_sell_data = "select * from Gn_Gwc_Order where cash_prod_pay=0 and seller_id='{$row['seller_id']}' and pay_status='Y' and page_type=0";
                                    $res_sell_data = mysqli_query($self_con, $sql_sell_data);

                                    while ($row_sell_data = mysqli_fetch_array($res_sell_data)) {
                                        $sell_money += ceil((($row_sell_data['contents_price'] * 1 / $row_sell_data['contents_cnt'] * 1) - $row_sell_data['contents_provide_price'] * 1) * 0.9 * $row_sell_data['contents_cnt'] * 1);
                                    }

                                    if ($row_mem_data['service_type'] == 3 || $row_mem_data['service_type'] == 2) {
                                        $sql_mem_under = "select mem_id from Gn_Member where recommend_id='{$row['seller_id']}' and mem_id in (select seller_id from Gn_Gwc_Order where cash_prod_pay=0 and page_type=0 and pay_status='Y' and seller_id!='{$row['seller_id']}')";
                                        $res_mem_under = mysqli_query($self_con, $sql_mem_under);
                                        while ($row_mem_under = mysqli_fetch_array($res_mem_under)) {
                                            $sql_recom_data = "select * from Gn_Gwc_Order where cash_prod_pay=0 and seller_id='{$row_mem_under['seller_id']}' and pay_status='Y' and page_type=0";
                                            $res_recom_data = mysqli_query($self_con, $sql_recom_data);

                                            while ($row_recom_data = mysqli_fetch_array($res_recom_data)) {
                                                $recom_money += ceil((($row_recom_data['contents_price'] * 1 / $row_recom_data['contents_cnt'] * 1) - $row_recom_data['contents_provide_price'] * 1) * 0.9 * $row_recom_data['contents_cnt'] * 1);
                                            }
                                        }
                                        if ($row_mem_data['gwc_leb'] == 3) {
                                            $center_money = $recom_money * ($row_mem_data['gwc_center_per'] * 1 / 100);
                                        }
                                        $recom_money = $recom_money * ($row_mem_data['gwc_service_per'] * 1 / 100);
                                    }

                                    $all_money = $sell_money + ceil($recom_money) + ceil($center_money);
                                ?>
                                    <tr>
                                        <td><?= $number-- ?></td>
                                        <td><a href="gwc_payment_detail_list.php?seller_id=<?= $row['seller_id'] ?>" target="_blank">상세보기</a></td>
                                        <td><?= $service ?><input type="number" name="service_per" id="service_per" min='0' style="width: 50px;margin-left:5px;" value="<?= $row_mem_data['gwc_service_per'] ? $row_mem_data['gwc_service_per'] : '1' ?>" <?= $service ? '' : 'hidden' ?>><br><a class="per_change" href="javascript:change_per('service', '<?= $row['seller_id'] ?>')" <?= $service ? '' : 'hidden' ?>>변경</a></td>
                                        <td><?= $gwc_leb ?><input type="number" name="center_per" id="center_per" min='0' style="width: 50px;margin-left:5px;" value="<?= $row_mem_data['gwc_center_per'] ? $row_mem_data['gwc_center_per'] : '5' ?>" <?= $gwc_leb ? '' : 'hidden' ?>><br><a class="per_change" href="javascript:change_per('center', '<?= $row['seller_id'] ?>')" <?= $gwc_leb ? '' : 'hidden' ?>>변경</a></td>
                                        <td><?= $row_mem_data['site'] . "/" . $row_mem_data['site_iam'] ?></td>
                                        <td><?= $row_mem_data['mem_name'] ?></td>
                                        <td><?= $row['seller_id'] ?></td>
                                        <td><?= number_format($sell_money) ?></td>
                                        <td><?= number_format(ceil($recom_money)) ?></td>
                                        <td><?= number_format(ceil($center_money)) ?></td>
                                        <td><?= number_format($all_money) ?></td>
                                        <td><?= number_format($row_mem_data['mem_cash']) ?></td>
                                        <td>0</td>
                                        <td><?= number_format($row_mem_data['mem_cash']) ?></td>
                                    </tr>
                                <?
                                    $i++;
                                }
                                $excel_sql = $query;
                                $excel_sql = str_replace("'", "`", $excel_sql);
                                if ($i == 1) { ?>
                                    <tr>
                                        <td colspan="11" style="text-align:center;background:#fff">등록된 내용이 없습니다.</td>
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
                    <?= drawPagingAdminNavi($totalCnt, $nowPage); ?>
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
</div><!--wrapper-->
<!--link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script-->