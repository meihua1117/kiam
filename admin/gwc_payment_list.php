<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today = date("Y-m") . "-01 00:00:00";
set_gwc_delivery_state();
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/rlatjd_admin.js"></script>
<script type="text/javascript" src="./js/gwc_payment_list.js"></script>
<link rel="stylesheet" href="./css/gwc_payment_list.css">
<script>
    $(document).ready(function() {
        var send_mms = '<?= $send_mms ?>';
        var tjd_no = '<?= $tjd_no ?>';
        if (send_mms == 'Y') {
            $.ajax({
                type: "POST",
                url: "/makeData_item_point.php",
                data: {
                    payMethod: "order_change",
                    payMethod1: "order_complete_admin",
                    tjd_no: tjd_no
                },
                dataType: 'json',
                success: function(data) {
                    // alert("문자발송되었습니다.");
                    location = '/admin/gwc_payment_list.php';
                },
            });
        }
    })

    function goPage(pgNum) {
        location.href = '?nowPage=' + pgNum + '&search_status=<?= $search_status ?>&yutong=<?= $yutong ?>' +
            '&search_start_date=<?= $search_start_date ?>&search_end_date=<?= $search_end_date ?>' +
            '&search_key=<?= $search_key ?>&prod_name=<?= $prod_name ?>&identifier=<?= $identifier ?>' +
            '&order_info=<?= $order_info ?>&provide=<?= $provide ?>&site=<?= $site ?>&site_iam=<?= $site_iam ?>';
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
            <h1>굿마켓 결제정보 관리<small>굿마켓 상품결제정보를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">굿마켓 결제관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <? if ($_SESSION['one_member_admin_id'] != "onlyonemaket") { ?>
                        <div style="margin-bottom:40px">
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_gwc_payment_down.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
                            <? if ($_SESSION['one_member_id'] != 'sungmheo') { ?>
                                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow();return false;"> 선택삭제</button>
                                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='gwc_payment_balance_list.php';return false;"> 굿마켓정산관리</button>
                            <? } ?>
                        </div>
                    <? } ?>
                    <form method="get" name="search_form" id="search_form " class="form-inline">
                        <div class="box-tools">
                            <div class="input-group">
                                <div class="form-group">
                                    <input type="date" style="height: 30px" name="search_start_date" placeholder="" id="search_start_date" value="<?= $_REQUEST['search_start_date'] ?>" multiple /> ~
                                    <input type="date" style="height: 30px" name="search_end_date" placeholder="" id="search_end_date" value="<?= $_REQUEST['search_end_date'] ?>" />
                                </div>
                                <div class="form-group">
                                    <select name="yutong" class="form-control input-sm " style="margin-right:5px">
                                        <option value="">유통사</option>
                                        <option value="1" <? if ($_REQUEST['yutong'] == "1") echo "selected" ?>>온리원</option>
                                        <option value="2" <? if ($_REQUEST['yutong'] == "2") echo "selected" ?>>웹빙몰</option>
                                    </select>
                                </div>
                                <div class="form-group" id="provider_sel" style="<?= $_REQUEST['yutong'] == "1" ? '' : 'display:none' ?>">
                                    <select name="provide" class="form-control input-sm " style="margin-right:5px">
                                        <option value="">공급사</option>
                                        <?
                                        $sql_gongupsa = "select gwc_provider_name from Gn_Member where gwc_provider_name!='' GROUP BY gwc_provider_name";
                                        $res_gongupsa = mysqli_query($self_con, $sql_gongupsa);
                                        while ($row_gongupsa = mysqli_fetch_array($res_gongupsa)) {
                                        ?>
                                            <option value="<?= $row_gongupsa[0] ?>" <? if ($_REQUEST['provide'] == $row_gongupsa[0]) echo "selected" ?>><?= $row_gongupsa[0] ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="search_status" class="form-control input-sm " style="margin-right:5px">
                                        <option value="">결제상태</option>
                                        <option value="Y" <? if ($_REQUEST['search_status'] == "Y") echo "selected" ?>>결제완료</option>
                                        <option value="N" <? if ($_REQUEST['search_status'] == "N") echo "selected" ?>>결제대기</option>
                                        <option value="A" <? if ($_REQUEST['search_status'] == "A") echo "selected" ?>>후불결제</option>
                                        <option value="E" <? if ($_REQUEST['search_status'] == "E") echo "selected" ?>>기간만료</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="order_info" class="form-control input-sm " style="margin-right:5px">
                                        <option value="">주문정보</option>
                                        <option value="od_no" <? if ($_REQUEST['order_info'] == "od_no") echo "selected" ?>>주문번호</option>
                                        <!-- <option value="seller_name" <? if ($_REQUEST['order_info'] == "seller_name") echo "selected" ?>>판매자명</option> -->
                                        <option value="seller_id" <? if ($_REQUEST['order_info'] == "seller_id") echo "selected" ?>>판매자ID</option>
                                        <!-- <option value="order_name" <? if ($_REQUEST['order_info'] == "order_name") echo "selected" ?>>주문자명</option> -->
                                        <!-- <option value="buyer_name" <? if ($_REQUEST['order_info'] == "buyer_name") echo "selected" ?>>구매자명</option> -->
                                        <!-- <option value="buyer_id" <? if ($_REQUEST['order_info'] == "buyer_id") echo "selected" ?>>구매자ID</option> -->
                                        <!-- <option value="recom_name" <? if ($_REQUEST['order_info'] == "recom_name") echo "selected" ?>>추천인명</option>
                                        <option value="recom_id" <? if ($_REQUEST['order_info'] == "recom_id") echo "selected" ?>>추천인ID</option> -->
                                    </select>
                                    <input type="text" name="order_key" id="order_key" style="width:100px" class="form-control input-sm pull-right" placeholder="" value="<?= $order_key ?>">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="search_key" id="search_key" style="width:100px" class="form-control input-sm pull-right" placeholder="이름" value="<?= $search_key ?>">
                                    <input type="text" name="identifier" id="identifier" style="width:100px" class="form-control input-sm pull-right" placeholder="아이디" value="<?= $identifier ?>">
                                    <input type="text" name="site_iam" id="site_iam" style="width:100px" class="form-control input-sm pull-right" placeholder="아이엠소속" value="<?= $site_iam ?>">
                                    <input type="text" name="site" id="site" style="width:100px" class="form-control input-sm pull-right" placeholder="셀링소속" value="<?= $site ?>">
                                    <input type="text" name="prod_name" id="prod_name" style="width:100px" class="form-control input-sm pull-right" placeholder="상품명" value="<?= $prod_name ?>">
                                </div>
                                <div class="input-group-btn">
                                    <button style="margin-left:5px" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="ad_layer1" style="display:none" style="overflow-y:auto !important;height:150px !important;">
                <table id="phone_table" class="table table-bordered table-striped" style="background:#fff !important">
                    <colgroup>
                        <col width="60px">
                        <col width="100px">
                        <col width="180px">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>번호</th>
                            <th>기부폰</th>
                            <th>설치일자</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="50"><input type="checkbox" class="check" id="check_all_member" value="0"><br>번호</th>
                                    <th width="80">주문일시<br>주문번호</th>
                                    <th width="150">주문상품</th>
                                    <th width="60">상품코드</th>
                                    <th width="80">공급사<br>유통사</th>
                                    <th width="80">구매자명<br>구매자ID</th>
                                    <th width="80">추천인명<br>추천인ID</th>
                                    <th width="80">판매자명<br>판매자ID</th>
                                    <th width="80">최저가<br>공급가</th>
                                    <th width="150">옵션정보</th>
                                    <th width="80">상품건수</th>
                                    <th width="80">구매금액<br>배송비</th>
                                    <th width="60">이용<br>포인트</th>
                                    <th width="60">무통장<br>입금</th>
                                    <th width="80">카드결제</th>
                                    <th width="80">총결제액</th>
                                    <th width="80">결제상태</th>
                                    <th width="80">입금상태</th>
                                    <th width="80">당월건수<br>누적건수</th>
                                    <th width="80">금월금액<br>누적금액</th>
                                    <th width="80">배송자<br>배송자ID</th>
                                    <th width="60">배송정보</th>
                                    <th width="80">운송장번호</th>
                                    <th>주문변경</th>
                                    <!-- <th><button class="btn btn-primary" onclick="deleteMultiRow();return false;"><i class="fa"></i>삭제</button></th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
                                $startPage = $nowPage ? $nowPage : 1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $identifier ? " AND (buyer_id LIKE '%" . $identifier . "%')" : null;
                                $searchStr .= $rec_id ? " AND (recommend_id like '%" . $rec_id . "%') " : null;
                                $searchStr .= $search_key ? "AND ( VACT_InputName like '%" . $search_key . "%')" : null;
                                $searchStr .= $site ? " AND b.site = '$site' " : null;
                                $searchStr .= $site_iam ? " AND b.site_iam = '$site_iam' " : null;
                                $searchStr .= $yutong ? " AND a.yutong_name = '$yutong' " : null;
                                $searchStr .= $provide ? " AND a.provider_name = '$provide' " : null;
                                $searchStr .= $prod_name ? " AND a.member_type = '$prod_name' " : null;
                                if ($order_info) {
                                    switch ($order_info) {
                                        case 'od_no':
                                            $searchStr .= $order_key ? " AND a.idx = '$order_key' " : null;
                                            break;
                                        case 'seller_id':
                                            $searchStr .= $order_key ? " AND a.seller_id = '$order_key' " : null;
                                            break;
                                        case 'buyer_id':
                                            $searchStr .= $order_key ? " AND a.buyer_id = '$order_key' " : null;
                                            break;
                                    }
                                }
                                if ($search_start_date && $search_end_date)
                                    $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
                                if ($search_status)
                                    $searchStr .= " AND end_status='$search_status'";
                                if ($stop_yn)
                                    $searchStr .= " AND stop_yn='$stop_yn'";

                                /*if($item_type == "S")
                                    $searchStr .= " AND (iam_pay_type ='' or iam_pay_type ='0')";
                                else if($item_type == "I")
                                    $searchStr .= " AND (iam_pay_type !='' and iam_pay_type !='0')";*/
                                if ($rec_id || $site || $site_iam) {
                                    $join_str = "INNER JOIN Gn_Member b on b.mem_id =a.buyer_id ";
                                    $sel_str = ",b.recommend_id";
                                } else {
                                    $join_str = "";
                                    $sel_str = "";
                                }

                                $order = $order ? $order : "desc";
                                $query = "SELECT SQL_CALC_FOUND_ROWS a.*$sel_str FROM tjd_pay_result a $join_str WHERE a.gwc_cont_pay=1 $searchStr";
                                $excel_sql = $query;
                                $excel_sql = str_replace("'", "`", $excel_sql);
                                $res        = mysqli_query($self_con, $query);
                                $totalCnt    =  mysqli_num_rows($res);
                                $limitStr       = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
                                $number            = $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY a.date DESC $limitStr ";
                                $i = 1;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con, $query);
                                while ($row = mysqli_fetch_array($res)) {
                                    if ($rec_id || $site || $site_iam) {
                                        $chk_str = 0;
                                        $recommend_id = $row['recommend_id'];
                                    } else {
                                        $chk_str = 1;
                                        $sql_mem = "select recommend_id from Gn_Member where mem_id='{$row['buyer_id']}'";
                                        $res_mem = mysqli_query($self_con, $sql_mem);
                                        $row_mem = mysqli_fetch_array($res_mem);
                                        $recommend_id = $row_mem['recommend_id'];
                                    }

                                    $sql_order = "select * from Gn_Gwc_Order where tjd_idx='{$row['no']}'";
                                    $res_order = mysqli_query($self_con, $sql_order);
                                    $row_order = mysqli_fetch_array($res_order);

                                    $show_link = "http://kiam.kr/iam/gwc_order_pay.php?contents_idx=" . $row_order['contents_idx'] . "&contents_cnt=" . $row_order['contents_cnt'] . "&contents_price=" . $row_order['contents_price'] . "&contents_salary=" . $row_order['salary_price'] . "&seller_id=" . $row['seller_id'] . "&order_option=" . $row_order['order_option'] . "&admin=Y&mem_id=" . $row_order['mem_id'] . "&use_point_val=" . $row_order['use_point'] . "&pay_method=" . $row_order['payMethod'];

                                    if ($row['yutong_name'] == 1) {
                                        $sql_gong = "select mem_name from Gn_Member where mem_id='iamstore'";
                                        $res_gong = mysqli_query($self_con, $sql_gong);
                                        $row_gong = mysqli_fetch_array($res_gong);
                                        $gong_data = $row_gong[0] . "<br> iamstore";
                                        $yt_name = "웹빙몰";
                                    } else {
                                        $sql_gong = "select mem_id, mem_name from Gn_Member where gwc_provider_name='{$row['provider_name']}'";
                                        $res_gong = mysqli_query($self_con, $sql_gong);
                                        $row_gong = mysqli_fetch_array($res_gong);
                                        $gong_data = $row_gong['mem_name'] . "<br>" . $row_gong['mem_id'];

                                        $yt_name = $row['provider_name'] . "<br>온리원";
                                    }
                                    $sql_seller = "select mem_name from Gn_Member where mem_id='{$row['seller_id']}'";
                                    $res_seller = mysqli_query($self_con, $sql_seller);
                                    $row_seller = mysqli_fetch_array($res_seller);
                                    $seller_data = $row_seller[0] . "<br>" . $row['seller_id'];

                                    $sql_recommend = "select mem_name from Gn_Member where mem_id='{$recommend_id}'";
                                    $res_recommend = mysqli_query($self_con, $sql_recommend);
                                    $row_recommend = mysqli_fetch_array($res_recommend);
                                    $recommend_data = $row_recommend[0] . "<br>" . $recommend_id;

                                    $sql_cont_data = "select idx, contents_sell_price, send_provide_price, send_salary_price, contents_img, product_code from Gn_Iam_Contents_Gwc where idx='{$row_order['contents_idx']}'";
                                    $res_cont_data = mysqli_query($self_con, $sql_cont_data);
                                    $row_cont_data = mysqli_fetch_array($res_cont_data);
                                    $price_data = ($row_order['contents_price'] * 1 / $row_order['contents_cnt'] * 1) . "<br>" . $row_order['contents_provide_price'];

                                    $price_data1 = ($row_order['contents_price']) . "<br>" . $row_order['salary_price'];

                                    if (strpos($row_cont_data['contents_img'], ",") !== false) {
                                        $img_link1 = explode(",", $row_cont_data['contents_img']);
                                        $img_link = trim($img_link1[0]);
                                    } else {
                                        $img_link = $row_cont_data['contents_img'];
                                    }

                                    $sql_order_point = "select use_point from Gn_Gwc_Order where tjd_idx='{$row['no']}'";
                                    $res_order_point = mysqli_query($self_con, $sql_order_point);
                                    $row_order_point = mysqli_fetch_array($res_order_point);

                                    $price_data2 = $row_order_point[0] . "/\n" . ($row['TotPrice'] * 1 - $row_order_point[0] * 1);

                                    if ($row['payMethod'] == "BANK" || $row['payMethod'] == "BANKPOINT") {
                                        $card_price = 0;
                                        $bank_price = $row['TotPrice'] * 1 - $row_order_point[0] * 1;
                                    } else {
                                        $bank_price = 0;
                                        $card_price = $row['TotPrice'] * 1 - $row_order_point[0] * 1;
                                    }

                                    $sql_item_cnt_month = "select SUM(contents_cnt) as month_cnt from Gn_Gwc_Order where contents_idx='{$row_order['contents_idx']}' and reg_date > '{$date_today}' and page_type=0";
                                    $res_item_cnt_month = mysqli_query($self_con, $sql_item_cnt_month);
                                    $row_item_cnt_month = mysqli_fetch_array($res_item_cnt_month);

                                    $sql_item_cnt_all = "select SUM(contents_cnt) as all_cnt from Gn_Gwc_Order where contents_idx='{$row_order['contents_idx']}' and page_type=0";
                                    $res_item_cnt_all = mysqli_query($self_con, $sql_item_cnt_all);
                                    $row_item_cnt_all = mysqli_fetch_array($res_item_cnt_all);

                                    $month_cnt = $row_item_cnt_month[0] ? $row_item_cnt_month[0] : "0";
                                    $cnt_data = $month_cnt . "/\n" . $row_item_cnt_all[0];

                                    $sql_price_month = "select SUM(TotPrice) as month_price from tjd_pay_result where gwc_cont_pay=1 and date > '{$date_today}' and buyer_id='{$row['buyer_id']}' and end_status='Y'";
                                    $res_price_month = mysqli_query($self_con, $sql_price_month);
                                    $row_price_month = mysqli_fetch_array($res_price_month);

                                    $sql_price_all = "select SUM(TotPrice) as all_price from tjd_pay_result where gwc_cont_pay=1 and buyer_id='{$row['buyer_id']}' and end_status='Y'";
                                    $res_price_all = mysqli_query($self_con, $sql_price_all);
                                    $row_price_all = mysqli_fetch_array($res_price_all);

                                    $month_money = $row_price_month[0] ? $row_price_month[0] : "0";
                                    $money_data = $month_money . "<br>" . $row_price_all[0];

                                    $prod_state = '주문';
                                    if ($row_order['prod_state'] == '1') {
                                        $prod_state = "취소";
                                    } else if ($row_order['prod_state'] == '2') {
                                        $prod_state = "반품";
                                    } else if ($row_order['prod_state'] == '3') {
                                        $prod_state = "교환";
                                    }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" class="check" id="check_one_member" value="<?= $row['no'] ?>"><br><?= $number-- ?></td>
                                        <td><?= $row['date'] ?><br><?= $row['idx'] ?></td>
                                        <td><img class="zoom" src="<?= $img_link ?>" style="width:50px;" onclick="show_order_page('<?= $show_link ?>')"><br><?= $row['member_type'] ?></td>
                                        <td><?= $row_cont_data['product_code'] ?></td>
                                        <td><?= $yt_name ?></td>
                                        <td style="cursor: pointer" onclick="onShowOrderDetailModal('<?= $row_order['order_mem_phone'] ?>','<?= $row_order['order_mem_email'] ?>','<?= $row_order['receive_mem_add'] ?>')">
                                            <?= $row['VACT_InputName'] . "<br>" . $row['buyer_id'] ?>
                                        </td>
                                        <td><?= $recommend_data ?></td>
                                        <td><?= $seller_data ?></td>
                                        <td><?= $price_data ?></td>
                                        <td><?
                                            $order_option = json_decode($row_order['gwc_order_option_content'], true);
                                            foreach ($order_option as $value) {
                                                echo $value['name'] . ' : ' . $value['number'] . '개, (' . $value['opt_price'] . ' 원)' . '<br>';
                                            }
                                            ?></td>
                                        <td><?= $row_order['contents_cnt'] ?></td>
                                        <td><?= $price_data1 ?></td>
                                        <td><?= $row_order_point[0] ?></td>
                                        <td><?= $bank_price ?></td>
                                        <td><?= $card_price ?></td>
                                        <td><?= $row['TotPrice'] ?></td>
                                        <td>
                                            <form method="post" name="ssForm<?= $i ?>" id="ssForm<?= $i ?>" action="ajax/gwc_payment_save.php">
                                                <input type="hidden" name="no" value="<?= $row['no'] ?>">
                                                <input type="hidden" name="mem_id" value="<?= $_SESSION['one_member_id'] ?>">
                                                <input type="hidden" name="price" id="price_<?= $i ?>" value="<?= $row['TotPrice'] ?>">
                                                <input type="hidden" name="type" id="type_<?= $i ?>" value="main">
                                                <select name="end_status" onchange="payment_save('#ssForm<?= $i ?>');return false;">
                                                    <option value="N" <?php echo $row['end_status'] == "N" ? "selected" : "" ?>>결제대기</option>
                                                    <option value="Y" <?php echo $row['end_status'] == "Y" ? "selected" : "" ?>>결제완료</option>
                                                    <option value="A" <?php echo $row['end_status'] == "A" ? "selected" : "" ?>>후불결제</option>
                                                    <option value="E" <?php echo $row['end_status'] == "E" ? "selected" : "" ?>>기간만료</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="post" name="releaseForm<?= $i ?>" id="releaseForm<?= $i ?>" action="ajax/gwc_payment_save.php">
                                                <input type="hidden" name="no" value="<?= $row_order['id'] ?>">
                                                <input type="hidden" name="mem_id" value="<?= $_SESSION['one_member_id'] ?>">
                                                <input type="hidden" name="type" id="type_<?= $i ?>" value="release">
                                                <select name="prod_release_state" onchange="payment_save('#releaseForm<?= $i ?>');return false;">
                                                    <option value="0" <?php echo $row_order['prod_release_state'] == 0 ? "selected" : "" ?>>입금대기</option>
                                                    <option value="1" <?php echo $row_order['prod_release_state'] == 1 ? "selected" : "" ?>>입금완료</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td><?= $cnt_data ?></td>
                                        <td><?= $money_data ?></td>
                                        <td onclick="onShowDeliverDetailModal('','','','')" style="cursor: pointer"></td>
                                        <td>
                                            <select name="delivery_type_<?= $row_order['id'] ?>" style="font-size:11px;">
                                                <option value="">배송회사</option>
                                                <?
                                                $sql_delivery = "select * from delivery_list";
                                                $res_delivery = mysqli_query($self_con, $sql_delivery);
                                                while ($row_delivery = mysqli_fetch_array($res_delivery)) {
                                                ?>
                                                    <option value="<?= $row_delivery['id'] ?>" title="<?= $row_delivery['delivery_name'] ?>" <?= $row_delivery['id'] == $row_order['delivery'] ? 'selected' : '' ?>><?= cut_str($row_delivery['delivery_name'], 5) ?></option>
                                                <? } ?>
                                            </select>
                                            <select name="delivery_state_<?= $row_order['id'] ?>" style="font-size:11px;">
                                                <option value="<?= $row_order['delivery_state'] ?>" <?= $row_order['delivery_state'] == 1 ? 'selected' : '' ?>>상품준비중</option>
                                                <option value="<?= $row_order['delivery_state'] ?>" <?= $row_order['delivery_state'] == 2 ? 'selected' : '' ?>>배송중</option>
                                                <option value="<?= $row_order['delivery_state'] ?>" <?= $row_order['delivery_state'] == 3 ? 'selected' : '' ?>>배송완료</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="delivery_no_<?= $row_order['id'] ?>" value="<?= $row_order['delivery_no'] ?>" style="width:80px;font-size: 11px;">
                                            <button onclick="show_delivery_link('<?= $row_order['delivery'] ?>');return false;" style="font-size: 11px;color: white;background-color: black;padding: 0px 5px;">배송조회</button>
                                            <button onclick="save_delivery('<?= $row_order['id'] ?>');return false;" style="font-size: 11px;color: white;background-color: black;padding: 0px 5px;">저장</button>
                                        </td>
                                        <td><a href="javascript:show_detail_prod('<?= $row_order['state_detail'] ?>')"><?= $prod_state ?></a></td>
                                    </tr>
                                <? $i++;
                                }
                                if ($i == 1) { ?>
                                    <tr>
                                        <td colspan="11" style="text-align:center;background:#fff">등록된 내용이 없습니다.</td>
                                    </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.row -->
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
    <div id="show_paper_comment" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:500px">
            <!-- Modal content-->
            <div class="modal-content" style="border-radius:5px;">
                <div class="modal-header" style="background: #5bd540;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">주문변경이유</div>
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -33px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="text-align: left;">
                        <p id="state_detail" style="font-size: 17px;">

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="orderDetailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content" style="border-radius:5px;">
                <div class="orderDetailModal_header">
                    <p class="">상세정보</p>
                    <div class="orderDetailModal_close" data-dismiss="modal">
                        &times;
                    </div>
                </div>
                <div class="orderDetailModal_body">
                    <div class="d-flex">
                        <p class="orderDetailModal_body_title">폰번호</p>
                        <p class="order_phone"></p>
                    </div>
                    <div class="d-flex">
                        <p class="orderDetailModal_body_title">우편번호</p>
                        <p class="order_email"></p>
                    </div>
                    <div class="d-flex">
                        <p class="orderDetailModal_body_title">배송지</p>
                        <p class="order_address"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="deliverDetailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="orderDetailModal_header">
                    <p class="">상세정보</p>
                    <div class="orderDetailModal_close" data-dismiss="modal">
                        &times;
                    </div>
                </div>
                <div class="orderDetailModal_body">
                    <div class="d-flex">
                        <p class="orderDetailModal_body_title">폰번호</p>
                        <p class="deliver_phone"></p>
                    </div>
                    <div class="d-flex">
                        <p class="orderDetailModal_body_title">우편번호</p>
                        <p class="deliver_email"></p>
                    </div>
                    <div class="d-flex">
                        <p class="orderDetailModal_body_title">주소</p>
                        <p class="deliver_address"></p>
                    </div>
                    <div class="d-flex">
                        <p class="orderDetailModal_body_title">계좌</p>
                        <p class="deliver_account"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="excel_down_form" name="excel_down_form" target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
    <? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_footer.inc.php"; ?>
</div><!--wrapper-->
<!-- Footer -->
<!-- <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                 -->