<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today = date("Y-m") . "-01 00:00:00";
$search_year = $search_year ? $search_year : date("Y");
$search_month = $search_month ? sprintf("%02d", $search_month) : sprintf("%02d", date("m"));
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
        location.href = '?nowPage=' + pgNum + '&search_status=<?= $search_status ?>&yutong=<?= $yutong ?>' +
            '&search_year=<?= $search_year ?>&search_month=<?= $search_month ?>' +
            '&search_key=<?= $search_key ?>&prod_name=<?= $prod_name ?>&identifier=<?= $identifier ?>' +
            '&order_info=<?= $order_info ?>&provide=<?= $provide ?>&site=<?= $site ?>&site_iam=<?= $site_iam ?>&seller_id=<?= $seller_id ?>';
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

    function deleteMultiRow() {
        if (confirm('삭제하시겠습니까?')) {
            var check_array = $("#example1").children().find(".check");
            var no_array = [];
            var index = 0;
            check_array.each(function() {
                if ($(this).prop("checked") && $(this).val() > 0)
                    no_array[index++] = $(this).val();
            });
            $.ajax({
                type: "POST",
                url: "/admin/ajax/gwc_order_save.php",
                data: {
                    type: "delete_list",
                    no: no_array.toString()
                },
                success: function(data) {
                    alert('정확히 삭제되었습니다.');
                    location.reload();
                }
            });
        }
    }
    $(function() {
        $('.check').on("click", function() {
            if ($(this).prop("id") == "check_all_member") {
                if ($(this).prop("checked"))
                    $('.check').prop("checked", true);
                else
                    $('.check').prop("checked", false);
            } else if ($(this).prop("id") == "check_one_member") {
                if (!$(this).prop("checked"))
                    $('#check_all_member').prop("checked", false);
            }
        });
        $('.month_count').on("change", function() {
            var obj = $(this);
            $.ajax({
                type: "POST",
                url: "ajax/payment_save.php",
                dataType: "json",
                data: {
                    type: "end_date",
                    no: $(this).data("no"),
                    month: $(this).val()
                },
                success: function(data) {
                    obj.parents("tr").find("span[id=end_date]").html(data.end_date);
                }
            });
        });
        $('.onestep2yak').on("change", function() {
            var yak = $(this).val();
            $.ajax({
                type: "POST",
                url: "ajax/payment_save.php",
                dataType: "json",
                data: {
                    type: "onestep2_update",
                    no: $(this).data("no"),
                    yak: yak
                },
                success: function(data) {
                    location.reload();
                }
            });
        });
        $('select[name=yutong]').on('change', function() {
            console.log($(this).val());
            if ($(this).val() == 1) {
                $("#provider_sel").attr('style', 'display:inline-block');
            } else {
                $("#provider_sel").attr('style', 'display:none');
            }
        })
    });



    function save_delivery(id) {
        var delivery = $("select[name=delivery_type_" + id + "]").val();
        var delivery_no = $("input[name=delivery_no_" + id + "]").val();
        $.ajax({
            type: "POST",
            url: "ajax/gwc_order_save.php",
            dataType: "json",
            data: {
                type: "delivery_save",
                delivery: delivery,
                delivery_no: delivery_no,
                id: id
            },
            success: function(data) {
                location.reload();
            }
        });
    }

    function show_delivery_link(delivery_id) {
        if (delivery_id == '') {
            alert('배송회사를 선택해 주세요.');
            return;
        }
        $.ajax({
            type: "POST",
            url: "ajax/gwc_order_save.php",
            dataType: "json",
            data: {
                type: "get_delivery_link",
                delivery_id: delivery_id
            },
            success: function(data) {
                window.open(data.link, '_blank');
            }
        });
    }

    function show_order_page(link) {
        window.open(link, '_blank');
    }
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

    input[type="checkbox" i] {
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

    .zoom {
        transition: transform .2s;
        /* Animation */
    }

    .zoom:hover {
        transform: scale(4);
        /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        border: 1px solid #0087e0;
        box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
    }

    .zoom-2x {
        transition: transform .2s;
        /* Animation */
    }

    .zoom-2x:hover {
        transform: scale(2);
        /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        border: 1px solid #0087e0;
        box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
    }
</style>
<!-- Top 메뉴 -->
<? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header_menu.inc.php"; ?>
<div class="wrapper" style="display: flex;overflow: initial">
    <!-- Left 메뉴 -->
    <? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_left_menu.inc.php"; ?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>굿마켓 정산상세내역<small>굿마켓회원의 정산상세내역을 볼수 있습니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">굿마켓 정산상세내역</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <? if ($_SESSION['one_member_admin_id'] != "onlyonemaket") { ?>
                        <div>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_payment_down.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
                            <!-- <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow();return false;"> 선택삭제</button> -->
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='gwc_payment_balance_list.php';return false;"> 굿마켓정산관리</button>
                        </div>
                    <? } ?>
                    <form method="get" name="search_form" id="search_form " class="form-inline">
                        <div class="box-tools">
                            <div class="input-group">
                                <div class="form-group">
                                    <input type="hidden" name="seller_id" value='<?= $seller_id ?>'>
                                    <!-- <input type="date" style="height: 30px" name="search_start_date" placeholder="" id="search_start_date" value="<?= $_REQUEST['search_start_date'] ?>" multiple/> ~
                                    <input type="date" style="height: 30px" name="search_end_date" placeholder="" id="search_end_date" value="<?= $_REQUEST['search_end_date'] ?>"/> -->
                                    <select name="search_year" class="form-inline" style="height: 30px;">
                                        <? for ($i = $search_year - 4; $i <= $search_year; $i++) { ?>
                                            <option value="<?= $i ?>" <?php echo $i == $search_year ? "selected" : "" ?>><?= $i ?></option>
                                        <? } ?>
                                    </select>
                                    <select name="search_month" class="form-inline" style="height: 30px;">
                                        <? for ($i = 1; $i < 13; $i++) { ?>
                                            <option value="<?= $i ?>" <?php echo sprintf("%02d", $i) == $search_month ? "selected" : "" ?>><?= $i ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="identifier" id="identifier" style="width:100px;margin-right: 5px" class="form-control input-sm pull-right" placeholder="아이디" value="<?= $identifier ?>">
                                    <input type="text" name="search_key" id="search_key" style="width:100px;margin-right: 5px" class="form-control input-sm pull-right" placeholder="이름" value="<?= $search_key ?>">
                                    <input type="text" name="item_name" id="item_name" style="width:100px;margin-right: 5px;margin-left: 5px" class="form-control input-sm pull-right" placeholder="상품명" value="<?= $item_name ?>">
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
                                <col width="100px">
                                <col width="120px">
                                <col width="150px">
                                <col width="120px">
                                <col width="130px">
                                <col width="130px">
                                <col width="130px">
                                <col width="120px">
                                <col width="120px">
                                <col width="120px">
                                <col width="130px">
                                <col width="120px">
                                <col width="120px">
                                <col width="150px">
                                <col width="150px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>번호</th>
                                    <th>주문일시<br>주문번호</th>
                                    <th>주문상품</th>
                                    <th>셀링<br>IAM소속</th>
                                    <th>판매자명<br>판매자ID</th>
                                    <th>구매자명<br>구매자ID</th>
                                    <th>최저가<br>공급가</th>
                                    <th>상품<br>건수</th>
                                    <th>판매금액<br>배송비</th>
                                    <th>이용포인트<br>카드결제</th>
                                    <th>총판매금<br>이벤트당첨금</th>
                                    <th>판매P</th>
                                    <th>추천P</th>
                                    <th>센터P</th>
                                    <th>총수당P</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
                                $startPage = $nowPage ? $nowPage : 1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $identifier ? " AND (mem_id LIKE '%" . $identifier . "%')" : null;
                                $searchStr .= $search_key ? "AND ( order_mem_name like '%" . $search_key . "%')" : null;
                                $searchStr .= $site ? " AND b.site = '$site' " : null;
                                $searchStr .= $site_iam ? " AND b.site_iam = '$site_iam' " : null;
                                if ($search_year && $search_month) {
                                    $start = $search_year . "-" . $search_month . "-01";
                                    $end = $search_year . "-" . sprintf("%02d", ($search_month * 1 + 1)) . "-01";
                                    $searchStr .= " AND reg_date >= '$start' and reg_date < '$end'";
                                }

                                if ($site || $site_iam) {
                                    $join_str = "INNER JOIN Gn_Member b on b.mem_id =a.buyer_id ";
                                    $sel_str = ",b.recommend_id";
                                } else {
                                    $join_str = "";
                                    $sel_str = "";
                                }

                                $query = "select * from Gn_Gwc_Order where (seller_id='{$seller_id}' and page_type=0 and pay_status='Y' " . $searchStr . ") or (seller_id in (select mem_id from Gn_Member where recommend_id='{$seller_id}' and mem_id in (select seller_id from Gn_Gwc_Order where page_type=0 and pay_status='Y')) and page_type=0 and pay_status='Y')";
                                $excel_sql = $query;
                                $excel_sql = str_replace("'", "`", $excel_sql);
                                $res        = mysqli_query($self_con, $query);
                                $totalCnt    =  mysqli_num_rows($res);
                                $limitStr       = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
                                $number            = $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= $limitStr;
                                $i = 1;
                                $query .= $orderQuery;
                                // echo $query;
                                $res = mysqli_query($self_con, $query);
                                while ($row = mysqli_fetch_array($res)) {
                                    $show_link = "http://kiam.kr/iam/gwc_order_pay.php?contents_idx=" . $row['contents_idx'] . "&contents_cnt=" . $row['contents_cnt'] . "&contents_price=" . $row['contents_price'] . "&contents_salary=" . $row['salary_price'] . "&seller_id=" . $row['seller_id'] . "&order_option=" . $row['order_option'] . "&admin=Y&mem_id=" . $row['mem_id'] . "&use_point_val=" . $row['use_point'] . "&pay_method=" . $row['payMethod'];

                                    $sql_seller = "select mem_name, mem_id from Gn_Member where mem_id='{$row['seller_id']}'";
                                    $res_seller = mysqli_query($self_con, $sql_seller);
                                    $row_seller = mysqli_fetch_array($res_seller);
                                    $seller_data = $row_seller[0] . "/\n" . $row_seller[1];

                                    $sql_mem = "select site, site_iam, service_type, gwc_leb, gwc_center_per, gwc_service_per, mem_cash, recommend_id from Gn_Member where mem_id='{$row['seller_id']}'";
                                    $res_mem = mysqli_query($self_con, $sql_mem);
                                    $row_mem = mysqli_fetch_array($res_mem);
                                    $site_data = $row_mem[0] . "/\n" . $row_mem[1];

                                    if ($row_mem['service_type'] == 2 || $row_mem['service_type'] == 3) {
                                        if ($row_mem['gwc_leb'] == 3) {
                                            $mem_type = 3;
                                        } else {
                                            $mem_type = 2;
                                        }
                                        if (!$row_mem['gwc_service_per']) {
                                            if ($row_mem['service_type'] == 3) {
                                                $row_mem['gwc_service_per'] = 1;
                                            } else if ($row_mem['service_type'] == 2) {
                                                $row_mem['gwc_service_per'] = 4;
                                            }
                                        }
                                        if (!$row_mem['gwc_center_per']) {
                                            $row_mem['gwc_center_per'] = 5;
                                        }
                                    } else {
                                        $mem_type = 1;
                                        $sql_per = "select gwc_center_per, gwc_service_per from Gn_Member where mem_id='{$row_mem['recommend_id']}'";
                                        $res_per = mysqli_query($self_con, $sql_per);
                                        $row_per = mysqli_fetch_array($res_per);

                                        if (!$row_per['gwc_service_per']) {
                                            $row_mem['gwc_service_per'] = 1;
                                        } else {
                                            $row_mem['gwc_service_per'] = $row_per['gwc_service_per'];
                                        }
                                        if (!$row_per['gwc_center_per']) {
                                            $row_mem['gwc_center_per'] = 5;
                                        } else {
                                            $row_mem['gwc_center_per'] = $row_per['gwc_center_per'];
                                        }
                                    }

                                    $sql_title = "select member_type from tjd_pay_result where no='{$row['tjd_idx']}'";
                                    $res_title = mysqli_query($self_con, $sql_title);
                                    $row_title = mysqli_fetch_array($res_title);

                                    $sql_cont_data = "select idx, contents_sell_price, send_provide_price, send_salary_price, contents_img, contents_title from Gn_Iam_Contents_Gwc where idx='{$row['contents_idx']}'";
                                    $res_cont_data = mysqli_query($self_con, $sql_cont_data);
                                    $row_cont_data = mysqli_fetch_array($res_cont_data);

                                    if (strpos($row_cont_data['contents_img'], ",") !== false) {
                                        $img_link1 = explode(",", $row_cont_data['contents_img']);
                                        $img_link = trim($img_link1[0]);
                                    } else {
                                        $img_link = $row_cont_data['contents_img'];
                                    }

                                    $price_data2 = number_format($row['use_point']) . "/\n" . number_format($row['contents_price'] * 1 + $row[salary_price] * 1 - $row['use_point'] * 1);

                                    $price_data1 = number_format($row['contents_price'] * 1 + $row[salary_price] * 1) . "/\n0";

                                    if (!$row['use_point']) {
                                        $min_val = ceil(($row['contents_price'] * 1 / $row['contents_cnt'] * 1) * 0.03);
                                    } else {
                                        $min_val = 0;
                                    }
                                    $sell_money += ceil(((($row['contents_price'] * 1 / $row['contents_cnt'] * 1) - $row['contents_provide_price'] * 1) * 0.9 - $min_val) * $row['contents_cnt'] * 1);
                                    // $sell_money = ceil((($row['contents_price'] * 1 / $row['contents_cnt'] * 1) - $row['contents_provide_price'] * 1) * 0.9 * $row['contents_cnt'] * 1);
                                    $recom_money = ceil($sell_money * ($row_mem['gwc_service_per'] * 1 / 100));
                                    $center_money = ceil($sell_money * ($row_mem['gwc_center_per'] * 1 / 100));

                                    if ($mem_type == 3) {
                                        $last_money = number_format($sell_money);
                                    } else if ($mem_type == 2) {
                                        $last_money = number_format($recom_money);
                                    } else {
                                        $last_money = number_format($recom_money * 1 + $center_money * 1);
                                    }
                                ?>
                                    <tr>
                                        <td><?= $number-- ?></td>
                                        <td><?= $row['reg_date'] ?><br><?= $row['pay_order_no'] ?></td>
                                        <td><img class="zoom" src="<?= $img_link ?>" style="width:50px;" onclick="show_order_page('<?= $show_link ?>')"><br><?= $row_title[0] ?></td>
                                        <td><?= $site_data ?></td>
                                        <td><?= $seller_data ?></td>
                                        <td><?= $row['order_mem_name'] ?><br><?= $row['mem_id'] ?></td>
                                        <td><?= number_format(($row['contents_price'] * 1 / $row['contents_cnt'] * 1)) ?><br><?= number_format($row['contents_provide_price']) ?></td>
                                        <td><?= $row['contents_cnt'] ?></td>
                                        <td><?= number_format($row['contents_price']) ?><br><?= number_format($row[salary_price]) ?></td>
                                        <td><?= $price_data2 ?></td>
                                        <td><?= $price_data1 ?></td>
                                        <td style="<?= $mem_type == 3 ? 'color:blue;' : 'color:green;' ?>"><?= number_format($sell_money) ?></td>
                                        <td><span <?= $mem_type == 3 ? 'hidden' : '' ?>><?= number_format($recom_money) ?></span></td>
                                        <td><span <?= $mem_type == 3 || $mem_type == 2 ? 'hidden' : '' ?>><?= number_format($center_money) ?></span></td>
                                        <td style="color:red;"><?= $last_money ?></td>
                                    </tr>
                                <? $i++;
                                }
                                if ($i == 1) { ?>
                                    <tr>
                                        <td colspan="15" style="text-align:center;background:#fff">등록된 내용이 없습니다.</td>
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
    </div><!-- /.content-wrapper -->
    <form id="excel_down_form" name="excel_down_form" target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="excel_sql" value="<?= base64_encode($excel_sql) ?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div><!--wrapper-->
<!-- Footer -->
<!-- <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                 -->
<? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_footer.inc.php"; ?>