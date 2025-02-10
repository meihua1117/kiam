<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
$mid = date("YmdHis") . rand(10, 99);
if ($_SESSION['iam_member_id']) {
    $Gn_point = $member_iam['mem_point'];
    $Gn_cash_point = $member_iam['mem_cash'];
    $member_type = $member_iam['service_type'];
    $point_auto = get_search_key('auto_member_join');
}
$site = $HTTP_HOST;
if ($HTTP_HOST == "kiam.kr")
    $site = "www." . $HTTP_HOST;
$query = "select * from Gn_Iam_Service where sub_domain = 'http://" . $site . "'";
$res = mysqli_query($self_con, $query);
$domainData = mysqli_fetch_assoc($res);
if ($domainData['mem_id'] != $_SESSION['iam_member_id']) {
    echo "<script>location='/';</script>";
    exit;
}
$parse = parse_url($domainData['sub_domain']);
$site = explode(".", $parse);
//added by amigo middle log 로그부분이므로 삭제하지 말것!!!!
$logs = new Logs("iamalog.txt", false);
//end
$logs->add_log("start");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>아이엠카드 관리자</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/ionicons.min.css">
    <link rel="stylesheet" href="/admin/dist/css/AdminLTE.min_iam.css">
    <link rel="stylesheet" href="/admin/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/admin/plugins/iCheck/flat/blue.css">
    <link rel="stylesheet" href="/admin/plugins/morris/morris.css">
    <link rel="stylesheet" href="/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="/admin/plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="/admin/plugins/daterangepicker/daterangepicker-bs3.css">
    <link rel="stylesheet" href="/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <style>
        .loading_div {
            position: fixed;
            left: 50%;
            top: 50%;
            display: none;
            z-index: 1000;
        }

        .table-bordered>thead>tr>th,
        .table-bordered>tbody>tr>th,
        .table-bordered>tfoot>tr>th,
        .table-bordered>thead>tr>td,
        .table-bordered>tbody>tr>td,
        .table-bordered>tfoot>tr>td {
            border: 1px solid #ddd !important;
        }

        #open_recv_div li {
            list-style: none;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        input,
        select,
        textarea {
            vertical-align: top;
            border: 1px solid #CCC;
        }

        /* user agent stylesheet */
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

        .switch_callback_copy {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch_daily_copy {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
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

        .disagree {
            background: #ffd5d5 !important;
        }

        th a.sort-by {
            padding-right: 18px;
            position: relative;
        }

        a.sort-by:before,
        a.sort-by:after {
            border: 4px solid transparent;
            content: "";
            display: block;
            height: 0;
            right: 5px;
            top: 50%;
            position: absolute;
            width: 0;
        }

        a.sort-by:before {
            border-bottom-color: #666;
            margin-top: -9px;
        }

        a.sort-by:after {
            border-top-color: #666;
            margin-top: 1px;
        }

        .zoom {
            transition: transform .2s;
        }

        .zoom:hover {
            transform: scale(4);
            border: 1px solid #0087e0;
            box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
        }

        .zoom-2x {
            transition: transform .2s;
        }

        .zoom-2x:hover {
            transform: scale(2);
            border: 1px solid #0087e0;
            box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
        }

        #edit_modal_content {
            width: 875px;
        }

        .button_edit {
            border-radius: 5px;
            width: 15%;
            height: 40px;
            font-size: 15px;
            margin-top: 10px;
        }

        @media only screen and (max-width: 420px) {
            #edit_modal_content {
                width: 375px;
            }

            .btn_back {
                width: 25%;
            }
        }

        #ajax-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9000;
            text-align: center;
            display: none;
            background-color: #fff;
            opacity: 0.8;
        }

        #ajax-loading img {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 120px;
            height: 120px;
            margin: -60px 0 0 -60px;
        }

        .locked {
            color: red;
        }

        #point_show_share {
            background-color: #ffffff;
            border: 1px solid #dddddd;
            width: 30%;
            text-align: center;
            font-size: 15px;
        }

        .people_iam {
            font-size: 15px;
            background-color: #ffffff;
            border: 1px solid #dddddd;
        }

        .people_iam:hover {
            background-color: #e1d6d6;
        }

        .people_iam.settlement_btn {
            width: 30%;
        }

        .auto_iam {
            padding: 10px;
            border-radius: 7px;
            font-size: 15px;
            background-color: #99cc00;
            font-weight: 500;
            color: white;
        }

        .auto_iam:hover {
            color: black;
        }

        .auto_iam.settlement_btn {
            width: 35%;
        }

        .meminfo_name {
            margin-left: 27.5%;
            margin-bottom: 5px;
            display: flex;
        }

        .meminfo {
            margin-left: 25%;
            margin-bottom: 5px;
            display: flex;
        }

        .settle_way {
            font-size: 17px;
            color: #000000;
            margin-left: 7px;
            border: 1px solid #ccc;
            border-radius: 3px;
            cursor: pointer;
        }

        .detail_callmsg {
            width: 300px;
        }

        .copy_req_btn {
            width: 85%;
        }

        .urlcopy {
            width: 45%;
            border-radius: 2px;
            font-size: 15px;
            font-weight: bold;
        }

        .reqevent {
            width: 45%;
            border-radius: 2px;
            font-size: 15px;
            font-weight: bold;
        }

        @media only screen and (max-width: 420px) {
            .get_type_name {
                column-count: 3;
                text-align: center;
            }

            .dojang {
                margin-left: 0px;
            }

            .meminfo_name {
                margin-left: 15.5%;
                margin-bottom: 5px;
                display: flex;
            }

            .meminfo {
                margin-left: 11%;
                margin-bottom: 5px;
                display: flex;
            }

            .agreement-field {
                width: 95%;
                margin-left: 9%;
            }

            .detail_callmsg {
                width: 200px;
            }

            .copy_req_btn {
                width: auto;
                text-align: center;
            }
        }

        .iam_table {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 3px;
            text-align: center;
            cursor: pointer;
        }

        .agreement-field {
            width: 85%;
            margin-left: 9%;
        }

        .agreement-field .title {
            position: relative;
            padding: 10px 20px;
            background-color: #e5e5e5;
            border-radius: 15px;
            font-size: 16px;
            font-weight: 500;
            line-height: 20px;
        }

        .agreement-field .notice {
            padding: 10px;
            font-size: 12px;
            word-break: keep-all;
        }

        .step_switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .step_switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .auto_switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .auto_switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .auto_switch_copy input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .auto_switch_copy {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .reserv_btn {
            border: 1px solid;
            padding: 5px;
            color: white;
            background-color: #82c736;
        }

        .btn-active,
        .btn-active:hover,
        .btn-active:focus {
            background: #99cc00;
            color: white;
        }

        .chat_btn {
            color: white;
            border-radius: 7px;
            background-color: red;
            font-size: 12px;
            padding: 3px 15px;
            /* float: right; */
            margin-right: 17px;
            border: none;
            position: absolute;
            top: 85px;
            right: 7px;
            z-index: 10000;
        }

        #answer_side,
        #answer_side1,
        #answer_side2 {
            width: 90%;
            height: 400px;
            background-color: white;
            margin-right: auto;
            margin-left: auto;
            border-radius: 10px;
            margin-top: 12px;
            padding: 35px 30px 10px 30px;
            overflow: auto;
            text-align: left;
            position: relative;
        }

        .search_keyword {
            position: relative;
            width: 88%;
            margin: 0 auto;
            margin-top: 10px;
        }

        .search_keyword textarea {
            width: 99%;
            height: 58px;
            padding: 17px 60px 0 25px;
            border-width: 0;
            border-radius: 15px;
            border: 2px solid transparent;
            background-color: #fff;
            outline-width: 0;
            box-shadow: 0 5px 10px -5px rgb(0 0 0 / 30%);
            -webkit-transition: border-color 1000ms ease-out;
            transition: border-color 1000ms ease-out;
        }

        .send_ask {
            position: absolute;
            top: 0;
            right: 0;
            width: 58px;
            height: 100%;
            left: 90%;
            background-color: white;
            border-radius: 20px;
            border: none;
        }

        #gpt_req_list_title {
            float: left;
            padding: 7px;
            margin-left: 40px;
            background-color: #f18484;
            border-radius: 10px;
        }

        .history {
            position: absolute;
            top: 5px;
            left: 80px;
        }

        .gpt_act {
            position: relative;
            height: 35px;
        }

        .newpane,
        .newpane:hover {
            background-color: black;
            color: white;
            padding: 4px;
            border-radius: 10px;
            position: absolute;
            top: 5px;
            right: 80px;
        }

        @media only screen and (max-width: 720px) {
            .send_ask {
                position: absolute;
                top: 0;
                right: 0;
                width: 50px;
                height: 100%;
                left: 90%;
                background-color: white;
                border-radius: 20px;
                border: none;
            }
        }

        @media only screen and (max-width: 600px) {
            .send_ask {
                position: absolute;
                top: 0;
                right: 0;
                width: 50px;
                height: 100%;
                left: 88%;
                background-color: white;
                border-radius: 20px;
                border: none;
            }

            .chat_btn {
                color: white;
                border-radius: 7px;
                background-color: red;
                font-size: 12px;
                padding: 3px 15px;
                border: none;
                position: absolute;
                top: 85px;
                right: 7px;
                z-index: 10000;
            }
        }

        @media only screen and (max-width: 450px) {
            .send_ask {
                position: absolute;
                top: 0;
                right: 0;
                width: 50px;
                height: 100%;
                left: 85%;
                background-color: white;
                border-radius: 20px;
                border: none;
            }

            .history {
                position: absolute;
                top: 5px;
                left: 35px;
            }

            .newpane {
                background-color: black;
                color: white;
                padding: 4px;
                border-radius: 10px;
                position: absolute;
                top: 5px;
                right: 40px;
            }

            .chat_btn {
                color: white;
                border-radius: 7px;
                background-color: red;
                font-size: 12px;
                padding: 3px 15px;
                border: none;
                position: absolute;
                top: 85px;
                right: 7px;
                z-index: 10000;
            }
        }

        .chat_answer {
            word-break: break-all;
            word-wrap: break-word;
            white-space: pre-wrap;
        }

        .article-title {
            border-bottom: 1px solid lightgrey;
            margin-bottom: 15px;
            list-style-type: none;
        }

        .article-content {
            display: grid;
            margin-bottom: 15px;
            list-style-type: none;
        }

        .hided {
            display: none;
        }

        .copy_msg {
            position: absolute;
            right: 10px;
            top: 10px;
        }

        th.readonly {
            color: #003087;

        }

        th {
            width: 120px;
            background: #f3f3f3;
        }

        th.category {
            background: white;
        }

        input:focus {
            outline: none;
        }
        input {
            border: none;
        }
        tr.category{
            border-bottom:2px solid #AAA4A4;
        }
        tr.cont{
            border-bottom:3px double #AAA4A4;
        }
        tr.end{
            border-bottom:2px solid #AAA4A4;
        }
    </style>
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <link rel='stylesheet' href='../plugin/toastr/css/toastr.css' />
    <script src='../plugin/toastr/js/toastr.min.js'></script>
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <script src="/iam/js/layer.min.js" type="application/javascript"></script>
    <script src="/iam/js/chat.js"></script>
    <script>
        $(function() {
            $(document).ajaxStart(function() {
                    console.log("loading");
                    $("#ajax-loading").show();
                })
                .ajaxStop(function() {
                    $("#ajax-loading").delay(10).hide(1);
                });
            <? if ($_GET['mode'] == "auto") { ?>
                $("#auto_list_modal").modal("show");
            <? } ?>
            <? if ($_GET['mode'] == "callback") { ?>
                // $("#callback_list_modal").modal("show");
            <? } ?>
            <? if ($_GET['mode'] == "daily") { ?>
                $("#daily_list_modal").modal("show");
            <? } ?>
        })
    </script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
    <script language="javascript">
        function form_save() {
            if ($('#domain').val() == "") {
                alert('도메인을 입력해주세요.');
                return;
            }
            $('#dForm').submit();
        }
    </script>
    <style>

    </style>
    <div class="loading_div"><img src="/images/ajax-loader.gif"></div>
    <div class="wrapper">
        <!-- Top 메뉴 -->
        <? include "header.php"; ?>
        <form method="post" id="dForm" name="dForm" action="/admin/ajax/service_Iam_save.php" enctype="multipart/form-data" style="background: white;">
            <input type="hidden" name="idx" value="<?= $domainData['idx'] ?>" />
            <input type="hidden" name="mode" value="<?= $domainData['idx'] ? "updat" : "creat" ?>" />
            <input type="hidden" name="profile_idx" value="<?= $domainData['profile_idx'] ?>" />
            <input type="hidden" name="service_type" value="<?= $domainData['service_type'] ?>" />
            <input type="hidden" name="service_exp_date" value="<?= $domainData['service_price'] ?>" />
            <input type="hidden" name="service_price" value="<?= $domainData['service_price'] ?>" />
            <input type="hidden" name="service_price1" value="<?= $domainData['service_price_exp'] ?>" />
            <input type="hidden" name="ai_point" value="<?= $domainData['ai_card_point'] ?>" />
            <input type="hidden" name="automem_point" value="<?= $domainData['auto_member_point'] ?>" />
            <input type="hidden" name="card_point" value="<?= $domainData['card_send_point'] ?>" />
            <input type="hidden" name="contents_point" value="<?= $domainData['contents_send_point'] ?>" />
            <input type="hidden" name="autodata_point" value="<?= $domainData['autodata_point'] ?>" />
            <input type="hidden" name="ai_start_date" value="<?= $domainData['ai_point_start'] ?>" />
            <input type="hidden" name="ai_end_date" value="<?= $domainData['ai_point_end'] ?>" />
            <input type="hidden" name="automem_start_date" value="<?= $domainData['automem_point_start'] ?>" />
            <input type="hidden" name="automem_end_date" value="<?= $domainData['automem_point_end'] ?>" />
            <input type="hidden" name="card_start_date" value="<?= $domainData['card_point_start'] ?>" />
            <input type="hidden" name="card_end_date" value="<?= $domainData['card_point_end'] ?>" />
            <input type="hidden" name="contents_start_date" value="<?= $domainData['contents_point_start'] ?>" />
            <input type="hidden" name="contents_end_date" value="<?= $domainData['contents_point_end'] ?>" />
            <input type="hidden" name="autodata_start_date" value="<?= $domainData['autodata_point_start'] ?>" />
            <input type="hidden" name="autodata_end_date" value="<?= $domainData['autodata_point_end'] ?>" />
            <input type="hidden" name="callback_set_point" value="<?= $domainData['callback_set_point'] ?>" />
            <input type="hidden" name="callback_start_date" value="<?= $domainData['callback_point_start'] ?>" />
            <input type="hidden" name="callback_end_date" value="<?= $domainData['callback_point_end'] ?>" />
            <input type="hidden" name="daily_set_point" value="<?= $domainData['daily_set_point'] ?>" />
            <input type="hidden" name="daily_start_date" value="<?= $domainData['daily_point_start'] ?>" />
            <input type="hidden" name="daily_end_date" value="<?= $domainData['daily_point_end'] ?>" />
            <input type="hidden" name="from_page" id="from_page" value="iama" />
            <div class="row" style="margin: 0px;">
                <div class="box" style="margin-bottom: 0px;border-radius:0px">
                    <div class="box-body" style="border-radius:0px">
                        <table id="detail1" class="table">
                            <tbody>
                                <tr class="category">
                                    <th class="category" colspan="2" style="width:110px;text-align:left !important;">
                                        <p style="font-size:16px;font-weight:bold;margin-bottom:0px;">&#x2802 홈페이지 설정</P>
                                    </th>
                                </tr>
                                <tr class="cont">
                                    <th style="width:180px;">홈페이지제목</th>
                                    <td>
                                        <input type="text" style="width:97%;" name="web_theme" id="web_theme" value="<?= $domainData['web_theme'] ?>">
                                    </td>
                                </tr>
                                <tr class="cont">
                                    <th>홈 로고 등록<br>(이미지)</th>
                                    <td>
                                        <div style="display:flex">
                                            <input type="radio" name="head_logo_type" value="I" <?= ($domainData['head_logo_type'] == "I" ? "checked" : "") ?>>
                                            <input type="file" name="head_logo" accept=".jpg,.jpeg,.png,.gif,.svc" style="margin-left: 10px;">
                                            <input type="button" onclick="clear_head_logo('<?= $domainData['idx'] ?>');" value="삭제">
                                        </div>
                                        <? if ($domainData['head_logo'] != "") { ?>
                                            <img src="<?= $domainData['head_logo'] ?>" style="width:120px">
                                        <? } ?>
                                    </td>
                                </tr>
                                <tr class="cont">
                                    <th>홈 로고 등록<br>(텍스트)</th>
                                    <td>
                                        <div style="display:flex">
                                            <input type="radio" name="head_logo_type" value="T" <?= ($domainData['head_logo_type'] == "T" ? "checked" : "") ?>>
                                            <input type="text" style="width:97%;" name="head_logo_text" id="head_logo_text" value="<?= $domainData['head_logo_text'] ?>" style="margin-left: 10px;">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="cont">
                                    <th>홈아이콘 링크</th>
                                    <td>
                                        <input type="text" style="width:97%;" name="home_link" id="home_link" value="<?= $domainData['home_link'] ?>">
                                    </td>
                                </tr>
                                <tr class="cont">
                                    <th>푸터 로고 등록<br>(이미지)</th>
                                    <td>
                                        <div style="display:flex">
                                            <input type="radio" name="footer_logo_type" value="I" <?= ($domainData['footer_logo_type'] == "I" ? "checked" : "") ?>>
                                            <input type="file" name="footer_logo" accept=".jpg,.jpeg,.png,.gif,.svc" style="margin-left: 10px;">
                                            <input type="button" onclick="clear_footer_logo('<?= $domainData['idx'] ?>');" value="삭제">
                                        </div>
                                        <? if ($domainData['footer_logo'] != "") { ?>
                                            <img src="<?= $domainData['footer_logo'] ?>" style="width:120px">
                                        <? } ?>
                                    </td>
                                </tr>
                                <tr class="cont">
                                    <th>푸터 로고 등록<br>(텍스트)</th>
                                    <td>
                                        <div style="display:flex">
                                            <input type="radio" name="footer_logo_type" value="T" <?= ($domainData['footer_logo_type'] == "T" ? "checked" : "") ?>>
                                            <input type="text" style="width:97%;" name="footer_logo_text" id="footer_logo_text" value="<?= $domainData['footer_logo_text'] ?>" style="margin-left: 10px;">
                                        </div>
                                    </td>
                                </tr>
                                <tr class="cont">
                                    <th>푸터 링크</th>
                                    <td>
                                        <input type="text" style="width:97%;" name="footer_link" id="footer_link" value="<?= $domainData['footer_link'] ?>">
                                    </td>
                                </tr>
                                <tr class="end">
                                    <th>KaKao Iink</th>
                                    <td>
                                        <input type="text" style="width:97%;" name="kakao" id="kakao" value="<?= $domainData['kakao'] ?>">
                                    </td>
                                </tr>
                                <? if ($member_type >= 2) { ?>
                                    <tr class="category">
                                        <th class="category" colspan="2" style="width:110px;text-align:left !important;">
                                            <p style="font-size:16px;font-weight:bold;margin-bottom:0px;">&#x2802 셀프회원 공유카드선택</P>
                                            <p style="margin-bottom:0px;font-weight:400;">가입회원들에게 1번 카드는 디폴트로 공유되며, 다른 1개 카드를 추가로 선택해야 합니다. 총 4개까지 공유가능합니다.</p>
                                        </th>
                                    </tr>
                                    <tr class="end">
                                        <td colspan="2">
                                            <input type="text" id="self_share_card" name="self_share_card" value="<?= $domainData['self_share_card'] ?>" hidden>
                                            <div id="cardsel_self" onclick="limit_selcard_self();" style="line-height: 2em;">
                                                <?
                                                $sql5_self = "select card_short_url,phone_display, card_title from Gn_Iam_Name_Card where group_id = 0 and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                                                $result5_self = mysqli_query($self_con, $sql5_self);
                                                $i = 0;

                                                $share_card = $domainData['self_share_card'];
                                                if ($share_card != "") {
                                                    $card_arr = explode(",", $share_card);
                                                } else {
                                                    $card_arr = "";
                                                }
                                                while ($row5_self = mysqli_fetch_array($result5_self)) {
                                                    if ($i == 0)
                                                        $hidden = "hidden";
                                                    else
                                                        $hidden = "";

                                                    if (in_array($i + 1, $card_arr))
                                                        $checked = "checked";
                                                    else
                                                        $checked = "";

                                                    if ($row5_self['phone_display'] == "N")
                                                        $click = "onclick='locked_card_click();'";
                                                    else
                                                        $click = "";
                                                ?>
                                                    <input type="checkbox" id="multi_westory_card_url_self_<?= $i + 1 ?>" name="multi_westory_card_url_self" class="we_story_radio_self" style="margin-top:7px" <?= $checked ?> value="<?= $i + 1 ?>" <?= $click ?> <?= $hidden ?>>
                                                    <span <?= $row5_self['phone_display'] == "N" ? "class='locked' title='비공개카드'" : "title='" . $row5_self['card_title'] . "'"; ?> <?= $hidden ?>>
                                                        <?= $i + 1 ?>번(<?= $row5_self['card_title'] ?>)
                                                    </span>
                                                <?
                                                    $i++;
                                                }
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="category">
                                        <th class="category" colspan="2" style="width:110px;text-align:left !important;">
                                            <p style="font-size:16px;font-weight:bold;margin-bottom:0px;">&#x2802 자동회원 공유카드선택</P>
                                            <p style="margin-bottom:0px;font-weight:400;">가입회원들에게 1번 카드는 디폴트로 공유되며, 추가로 1번 카드 외 3개까지 공유가능합니다.</p>
                                        </th>
                                    </tr>
                                    <tr class="end">
                                        <td colspan="2">
                                            <input type="text" id="sel_card" hidden>
                                            <div id="cardsel" onclick="limit_selcard()" style="line-height: 2em;">
                                                <?
                                                $sql5 = "select card_short_url,phone_display, card_title from Gn_Iam_Name_Card where group_id = 0 and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                                                $result5 = mysqli_query($self_con, $sql5);
                                                $i = 0;
                                                while ($row5 = mysqli_fetch_array($result5)) {
                                                    if ($i == 0)
                                                        $hidden = "hidden";
                                                    else
                                                        $hidden = "";

                                                    if ($row5['phone_display'] == "N")
                                                        $click = "onclick='locked_card_click();'";
                                                    else
                                                        $click = "";
                                                ?>
                                                    <input type="checkbox" id="multi_westory_card_url_<?= $i + 1 ?>" name="multi_westory_card_url" class="we_story_radio" style="margin-top:7px;" value="<?= $i + 1 ?>" <?= $click ?> <?= $hidden ?>>
                                                    <span <?= $row5['phone_display'] == "N" ? "class='locked' title='비공개카드'" : "title='" . $row5['card_title'] . "'"; ?> <?= $hidden ?>>
                                                        <?= $i + 1 ?>번(<?= $row5['card_title'] ?>)
                                                    </span>
                                                <?
                                                    $i++;
                                                }
                                                ?>
                                                <br><br>
                                                <a class="btn" style="border: 1px solid #ead0d0;" onclick="state_check()">회원가입 메시지만들기</a>
                                                <a class="btn" onclick="$('#auto_list_modal').modal('show');" style="border: 1px solid #ead0d0;">리스트보기</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="category">
                                        <th class="category" colspan="2" style="width:110px;text-align:left !important;">
                                            <p style="font-size:16px;font-weight:bold;margin-bottom:0px;">&#x2802 공유콜백 설정하기</P>
                                            <p style="margin-bottom:0px;font-weight:400;">회원(직원)과 공유하기 위한 콜백메시지를 설정합니다.</p>
                                        </th>
                                    </tr>
                                    <tr class="end">
                                        <td colspan="2">
                                            <a class="btn" onclick="$('#callback_msg_modal').modal('show');" style="border: 1px solid #ead0d0;">콜백 메시지만들기</a>
                                            <a class="btn" onclick="callback_list()" style="border: 1px solid #ead0d0;">리스트보기</a>
                                            <select id="callback_times" class="btn" style="border: 1px solid #ead0d0;">
                                                <option value="a0" <?= substr($member_iam['callback_times'], 1) == 0 ? "selected" : "" ?>>1회만</option>
                                                <option value="a1" <?= substr($member_iam['callback_times'], 1) == 1 ? "selected" : "" ?>>일 1회</option>
                                                <option value="a2" <?= substr($member_iam['callback_times'], 1) == 2 ? "selected" : "" ?>>주 1회</option>
                                                <option value="a3" <?= substr($member_iam['callback_times'], 1) == 3 ? "selected" : "" ?>>월 1회</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <!-- <tr>
                                        <th style="width:180px;">콜백동기화설정</th>
                                        <td>
                                            <a class="btn" data-toggle="modal" data-target="#callback_sync_modal" style="border: 1px solid #ead0d0;">동기화 폰 리스트 및 설정</a>
                                        </td>
                                    </tr> -->
                                    <tr class="category">
                                        <th class="category" colspan="2" style="width:110px;text-align:left !important;">
                                            <p style="font-size:16px;font-weight:bold;margin-bottom:0px;">&#x2802 디비데일리 설정하기</P>
                                            <p style="margin-bottom:0px;font-weight:400;">회원(직원)과 공유하기 위한 콜백메시지를 설정합니다.</p>
                                        </th>
                                    </tr>
                                    <tr class="end">
                                        <td colspan="2">
                                            <a class="btn" onclick="$('#daily_msg_modal').modal('show');" style="border: 1px solid #ead0d0;">데일리메시지만들기</a>
                                            <a class="btn" onclick="$('#daily_list_modal').modal('show');" style="border: 1px solid #ead0d0;">리스트보기</a>
                                        </td>
                                    </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.row -->
            <div class="box-footer" style="text-align:center;padding:0px;margin-top:20px">
                <button class="btn btn-active" style="width:100%;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
            </div>
        </form>
        <!-- Footer -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; 2016 Onlyone All rights reserved.
        </footer>
        <div class="control-sidebar-bg"></div>
        <div id='ajax_div'></div>
    </div><!-- ./wrapper -->
    <? $logs->add_log("before_modal"); ?>
    <!-- ./오토회원가입 설정 모달 -->
    <div id="auto_memiam_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" data-dismiss="modal">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                    <label style="padding:15px 0px">자동으로 회원 IAM 만들기</label>
                </div>
                <div class="modal-header" style="padding:0px">
                    <div class="container" style="margin-top: 30px;">
                        <p style="margin:0px 10px;font-size:15px;font-weight:700;padding:10px 0px;text-align:center;border:1px solid #dddddd">회원이 직접 이름과 핸드폰 번호만 입력하면 자동으로 회원가입이 되고, 아이엠카드까지 생성됩니다. 본 서비스는 유료이므로 결제 후에 이용할 수 있습니다.</p>
                    </div>
                    <div class="container" style="margin-top: 20px;text-align: center">
                        <a href="https://www.youtube.com/playlist?list=PLP7cr8I5HQ8iLO-oGYvCOFygjKYnjYO28" target="_blank" style="font-size:15px; color:#99cc00;">자동으로 회원 IAM 만들기 메시지 샘플</a>
                    </div>
                    <div class="container" style="margin-top:10px;">
                        <p style="margin:0px 10px;font-size:15px;font-weight:700;">포인트충전</p>
                    </div>
                    <div class="container" style="margin-top: 5px;">
                        <div style="margin-top:10px;display: flex;justify-content: space-around;font-size:18px;font-weight:700;">
                            <? if ($is_pay_version) { ?>
                                <button class="people_iam settlement_btn" style="padding:20px 0px" href="#" onclick="point_chung()">포인트로<br>충전하기</button>
                            <? } ?>
                            <p id="point_show_share" style="padding:20px 0px">보유포인트<br><a style="color:red"><?= number_format($Gn_point); ?>P</a></p>
                            <p class="people_iam settlement_btn sharepoint" data="webpage" style="padding:20px 0px;text-align:center; cursor:pointer;">포인트<br>쉐어하기</p>
                        </div>
                        <!--div id="settle_way" style="margin-left:14px;margin-bottom:20px;display: flex;display:none;">
                            <a class="settle_way" href="pay_point.php" target = "_blank">카드결제</a>
                            <a class="settle_way" onclick="mutongjang()">무통장결제</a>
                        </div-->
                    </div>
                    <div class="container" style="margin: 20px 0px;">
                        <p style="font-size:15px;border: 1px solid #dddddd;margin:0px 10px;padding:0px 10px">
                            <br>
                            [결제전 확인사항]<br>
                            1. 다른 아이디로 잔여 포인트를 전송할수 있습니다.<br>
                            2. 자동회원가입 1명당 <?= number_format($point_auto) ?> 포인트 결제를 하셔야 합니다.<br>
                            3. 실제 회원가입이 됐을 때 포인트 차감이 됩니다.<br>
                            4. 발송자가 제공하는 정보로서 이를 신뢰하여 취한 조치에 대해 본사는 책임지지 않습니다.<br><br>
                        </p>
                    </div>
                    <div class="modal-footer" style="padding:0px;display:flex">
                        <button type="button" class="btn btn-default" style="width:50%;border-radius:0px;padding:15px 0px;" data-dismiss="modal">취소하기</button>
                        <button type="button" class="btn btn-active" style="width:50%;border-radius:0px;margin-left:0px;padding:15px 0px;" onclick="settlement('make')">자동 메시지 만들기</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./데일리메시지 설정 모달 -->
    <div id="daily_msg_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                    <label style="padding:15px 0px">데일리 메시지 설정하기</label>
                </div>
                <div class="modal-header">
                    <div class="container" style="margin-top: 30px;">
                        <p style="margin:0px 10px;font-size:15px;font-weight:700;padding:10px 0px;text-align:center;border:1px solid #dddddd">데일리 메시지 발송을 동의한 회원의 데일리 메시지를 설정할 수 있습니다.</p>
                    </div>
                    <div class="container" style="margin-top: 5px;">
                        <div style="margin-top:10px;display: flex;justify-content: space-around;font-size:18px;font-weight:700;">
                            <? if ($is_pay_version) { ?>
                                <button class="people_iam settlement_btn" href="#" onclick="point_chung()">포인트로<br>충전하기</button>
                            <? } ?>
                            <p style="padding:20px 0px" id="point_show_share">보유포인트<br><a style="color:red"><?= number_format($Gn_point); ?>P</a></p>
                            <p class="people_iam settlement_btn sharepoint" data="webpage" style="padding:20px 0px;text-align:center; cursor:pointer;">포인트<br>쉐어하기</p>
                        </div>
                        <!--div id="settle_way" style="margin-left:14px;margin-bottom:20px;display: flex;display:none;">
                            <a class="settle_way" href="pay_point.php" target = "_blank">카드결제</a>
                            <a class="settle_way" onclick="mutongjang()">무통장결제</a>
                        </div-->
                    </div>
                    <div class="container" style="margin: 20px 0px;">
                        <p style="font-size:15px;border: 1px solid #dddddd;margin:0px 10px;padding:0px 10px">
                            <br>
                            [결제전 확인사항]<br>
                            1. 데일리 메시지 신청자 한명당 500포인트 차감<br>
                            2. 데일리 메시지 세팅을 동의한 회원의 폰 디비로 데일리 문자가 발송됩니다.
                            <br><br>
                        </p>
                    </div>
                </div>
                <div class="modal-footer" style="padding:0px;display:flex">
                    <button type="button" class="btn btn-default" style="width:50%;border-radius:0px;padding:15px 0px;" data-dismiss="modal">취소하기</button>
                    <button type="button" class="btn btn-active" style="width:50%;border-radius:0px;margin-left:0px;padding:15px 0px;" onclick="settlement('daily')">데일리 메시지 설정하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ./데일리메시지 페이지 모달 -->
    <div id="dailymsg_making_modal" class="modal fade" role="dialog" style="overflow: auto;">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <form method="post" id="dForm_daily" name="dForm_daily" action="/admin/ajax/daily_msg_save.php" enctype="multipart/form-data">
                    <input type="hidden" name="mode" value="reg_daily" />
                    <div class="modal-header">
                        <input type="text" name="msgtitle_daily_intro" id="msgtitle_daily_intro" placeholder="안내글 제목을 입력하세요." style="margin-left: 17%;font-size: 20px;width: 70%;text-align:center;">
                        <div class="container" style="margin-top: 20px;width: 100%;">
                            <textarea style="font-size:15px;text-align:left;font-weight:500;width:100%; height:152px;" name="msgdesc_daily_intro" id="msgdesc_daily_intro" placeholder="안내글 내용"></textarea>
                        </div>
                        <div class="container" style="margin-top: 20px;width: 100%;">
                            <span style="font-size: 17px;">데일리메시지 상세정보</span>
                            <table class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="30%">
                                    <col width="60%">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>제목</th>
                                        <td>
                                            <input type="text" name="msgtitle_daily" id="msgtitle_daily">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>내용</th>
                                        <td>
                                            <textarea class="detail_callmsg" name="msgdesc_daily" id="msgdesc_daily" style="height:100px" placeholder="링크연결주소는 하단 박스에 입력해 주세요. 한 개 주소만 연결 가능합니다."></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>링크주소</th>
                                        <td>
                                            <input class="detail_callmsg" type="text" name="iam_link_daily" id="iam_link_daily">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>이미지</th>
                                        <td>
                                            <input type="file" name="upimage" style="width:75%;" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>발송량</th>
                                        <td>
                                            <input type="number" name="daily_cnt" id="daily_cnt" value="50" style="width:30%;">&nbsp;100건이하로 권장
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="w200">[발송시간선택]</th>
                                        <td>
                                            <select name="htime" style="width:50px;">
                                                <?
                                                for ($i = 9; $i < 22; $i++) {
                                                    $iv = $i < 10 ? "0" . $i : $i;
                                                    $selected = $row['htime'] == $iv ? "selected" : "";
                                                ?>
                                                    <option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                            <select name="mtime" style="width:50px;">
                                                <?
                                                for ($i = 0; $i < 31; $i += 30) {
                                                    $iv = $i == 0 ? "00" : $i;
                                                    $selected = $row['mtime'] == $iv ? "selected" : "";
                                                ?>
                                                    <option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div style="width:85%;">
                            <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:42%;font-size:15px; background-color:#82aae0;font-weight: bold;font-size: 18px;" onclick="goback('making_daily')">취소하기</button>
                            <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:42%;font-size:15px;background-color:#82aae0;font-weight: bold;font-size: 18px;" onclick="form_save_daily();">등록하기</button>
                            <input type="hidden" name="daily_msg_surl" id="daily_msg_surl" val="">
                        </div>
                </form>
                <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:73%;float:left;margin-left:12%;font-size:15px;background-color:#82aae0;margin-top:5px;font-weight: bold;font-size: 18px;" onclick="copy()">메시지 단축주소 복사하기</button>
            </div>
        </div>
    </div>
    </div>
    <!-- ./콜백메시지만들기 설정 모달 -->
    <div id="callback_msg_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                    <label style="padding:15px 0px">콜백 메시지 설정하기</label>
                </div>
                <div class="modal-header">
                    <div class="container" style="margin-top: 30px;">
                        <p style="margin:0px 10px;font-size:15px;font-weight:700;padding:10px 0px;text-align:center;border:1px solid #dddddd">콜백 메시지 세팅을 동의한 회원의 콜백 메시지함에 들어갈 메시지를 설정할 수 있습니다.</p>
                    </div>
                    <div class="container" style="margin-top:10px;">
                        <p style="margin:0px 10px;font-size:15px;font-weight:700;">포인트충전</p>
                    </div>
                    <div class="container" style="margin-top: 20px;">
                        <div style="margin-top:10px;display: flex;justify-content: space-around;font-size:18px;font-weight:700;">
                            <? if ($is_pay_version) { ?>
                                <button class="people_iam settlement_btn" href="#" onclick="point_chung()">포인트로<br>충전하기</button>
                            <? } ?>
                            <p style="padding:20px 0px" id="point_show_share">보유포인트<br><a style="color:red"><?= number_format($Gn_point); ?>P</a></p>
                            <p class="people_iam settlement_btn sharepoint" data="webpage" style="padding:20px 0px;text-align:center; cursor:pointer;">포인트<br>쉐어하기</p>
                        </div>
                        <!--div id="settle_way" style="margin-left:14px;margin-bottom:20px;display: flex;display:none;">
                            <a class="settle_way" href="pay_point.php" target = "_blank">카드결제</a>
                            <a class="settle_way" onclick="mutongjang()">무통장결제</a>
                        </div-->
                        <div style="margin:30px 0px;display: flex;justify-content: space-around;">
                            <button type="button" class="btn btn-default btn-submit" style="background:white;border-radius: 20px;width:30%;font-size:15px;font-weight:700;" onclick="callback_list('contentssend')">콜백리스트보기</button>
                            <button type="button" class="btn btn-default btn-submit" style="background:white;border-radius: 20px;width:30%;font-size:15px;font-weight:700;" onclick="location.href='/iam/mypage_payment.php'">포인트내역보기</button>
                        </div>
                    </div>
                    <div class="container" style="margin-top: 20px;border: 1px solid black;">
                        <p style="font-size:16px;color:#6e6c6c">
                            [결제전 확인사항]<br>
                            1. 콜백 메시지 신청자 한명당 300포인트 차감<br>
                            2. 콜백 메시지 세팅을 동의한 회원의 콜백 메시지함에 샘플 메시지로 등록되어 디폴트로 선택됩니다.<br>
                            3. 회원이 콜백 메시지 세팅을 동의했어도 추후 선택 메시지를 변경할 수 있습니다.<br>
                        </p>
                    </div>
                </div>
                <div class="modal-footer" style="padding:0px;display:flex">
                    <button type="button" class="btn btn-default" style="width:50%;border-radius:0px;padding:15px 0px;" data-dismiss="modal">취소하기</button>
                    <button type="button" class="btn btn-active" style="width:50%;border-radius:0px;margin-left:0px;padding:15px 0px;" onclick="settlement('callmake')">콜백 메시지 설정하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ./콜백메시지 페이지 모달 -->
    <div id="callback_making_modal" class="modal fade" role="dialog" style="overflow: auto;">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <button onclick="show_chat('callback', '<?= $member_iam['gpt_chat_api_key'] ?>')" class="chat_btn" style="top: 35px;">+ AI와 대화하기</button>
                <form method="post" id="dForm_call" name="dForm_call" action="/admin/ajax/mms_callback_save.php" enctype="multipart/form-data">
                    <input type="hidden" name="mode" value="reg_msg" />
                    <div class="modal-header">
                        <textarea type="text" name="msgtitle_call" id="msgtitle_call" placeholder="제목을 입력하세요." style="margin-left: 17%;font-size: 20px;width: 55%;text-align:center;"></textarea>
                        <div class="container" style="margin-top: 20px;width: 100%;">
                            <textarea style="font-size:15px;text-align:left;font-weight:500;width:100%; height:152px;" name="msgdesc_call" id="msgdesc_call" placeholder="메시지내용"></textarea>
                        </div>
                        <div class="container" style="margin-top: 20px;width: 100%;">
                            <span style="font-size: 17px;">콜백메시지상세정보</span>
                            <table class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="30%">
                                    <col width="60%">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>메시지 타이틀</th>
                                        <td>
                                            <textarea class="detail_callmsg" type="text" name="call_title" id="call_title"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>메시지 콘텐츠</th>
                                        <td>
                                            <textarea class="detail_callmsg" name="call_content" id="call_content" style="height:100px"></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>이미지</th>
                                        <td>
                                            <input class="detail_callmsg" type="file" name="call_img" id="call_img">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>링크주소</th>
                                        <td>
                                            <input class="detail_callmsg" type="text" name="iam_link" id="iam_link">
                                            <br>&nbsp;이 주소가 설정되지 않으면 이용자의 IAM주소를 가져옵니다.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div style="width:85%;">
                            <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:42%;font-size:15px; background-color:#82aae0;font-weight: bold;font-size: 18px;" onclick="goback('making_callback')">취소하기</button>
                            <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:42%;font-size:15px;background-color:#82aae0;font-weight: bold;font-size: 18px;" onclick="form_save_call();return false;">등록하기</button>
                            <input type="hidden" name="call_msg_surl" id="call_msg_surl" val="">
                        </div>
                </form>
                <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:73%;float:left;margin-left:12%;font-size:15px;background-color:#82aae0;margin-top:5px;font-weight: bold;font-size: 18px;" onclick="copy()">메시지 단축주소 복사하기</button>
            </div>
        </div>
    </div>
    </div>
    <!-- ./오토회원가입 페이지 모달 -->
    <div id="auto_making_modal" class="modal fade" role="dialog" style="overflow: auto;">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content" style="margin: 0px 10px">
                <button onclick="show_chat('auto', '<?= $member_iam['gpt_chat_api_key'] ?>')" class="chat_btn">+ AI와 대화하기</button>
                <form method="post" id="dForm_auto" name="dForm_auto" action="/admin/ajax/daily_msg_save.php" enctype="multipart/form-data">
                    <div>
                        <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                            <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" data-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-header" style="text-align: center;background:#99cc00">
                        <h4 style="text-align: center;color:white;font-weight:500">회원가입 메시지 만들기</h4>
                    </div>
                    <div class="modal-body" style="margin-top:0px">
                        <label style="font-size:15px;font-weight:700">제목입력</label>
                        <input type="text" id="msgtitle" placeholder="제목을 입력하세요." style="font-size: 15px;width: 100%;margin-top:10px;padding:5px 10px">
                        <label style="font-size:15px;font-weight:700;margin-top:20px">내용입력</label>
                        <textarea style="font-size:15px;text-align:left;font-weight:500;width:100%; height:152px;margin-top:10px;padding:5px 10px" id="msgdesc" placeholder="메시지 내용을 입력해주세요."></textarea>
                        <div class="container" style="margin-top: 10px;width: 100%;text-align:center;border:1px solid #ddd;padding:5px 0px">
                            <input type="file" name="autojoin_img" id="autojoin_img" style="display:none">
                            <a onClick="$('#autojoin_img').click();">+이미지 추가</a>
                        </div>
                        <div style="width:100%;text-align:center;margin-top:20px">
                            <a class="auto_iam settlement_btn" href="http://kiam.kr/?cur_win=best_sample" target="_blank">Best IAM 보기</a>
                        </div>
                        <div style="margin-top: 40px;width: 100%;display:flex;">
                            <div>
                                <label style="font-size:15px;font-weight:700;margin-top:5px">버튼 텍스트</label>
                            </div>
                            <div style="margin-left:5px">
                                <input type="text" style="font-size:15px;padding:5px 10px;width:60%" class="dojang" placeholder="버튼 텍스트 입력">
                                <img src="/iam/img/menu/icon_share_black.png" style="height:34px;margin-left: -4px;border:1px solid #ddd;background:#f5f5f5" id="setlink">
                            </div>
                        </div>
                        <div style="width: 100%;display:none;">
                            <label style="font-size:15px;font-weight:700;margin-top:5px;color:white">버튼 텍스트</label>
                            <input type="text" name="myiamlink" id="myiamlink" placeholder="링크주소 입력" style="margin-left:5px;font-size:14px;margin-top: 10px;">
                        </div>
                        <div style="margin-top: 10px;width: 100%;display:flex;">
                            <div>
                                <label style="font-size:15px;font-weight:700;margin-top:5px">발송폰선택</label>
                            </div>
                            <div style="margin-left:9px">
                                <select id="send_num" name="send_num" style="font-size: 15px;padding: 5px 5px">
                                    <?

                                    $mem_sql = "select mem_phone from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
                                    $mem_res = mysqli_query($self_con, $mem_sql);
                                    $data = mysqli_fetch_array($mem_res);
                                    $mem_phone = str_replace("-", "", $data['mem_phone']);
                                    ?>
                                    <option value="<?= $mem_phone ?>"><?php echo $mem_phone; ?></option>
                                    <?
                                    $query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['iam_member_id']}' order by sort_no asc, user_cnt desc , idx desc";
                                    $resul = mysqli_query($self_con, $query);
                                    while ($korow = mysqli_fetch_array($resul)) {
                                        $send_phone = str_replace("-", "", $korow['sendnum']);
                                        if ($send_phone != $mem_phone) {
                                    ?>
                                            <option value="<?= $send_phone ?>"> <?php echo $send_phone ?></option>
                                    <?
                                        }
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <div style="margin-top: 10px;width: 100%;display:flex;">
                            <div>
                                <label style="font-size:15px;font-weight:700;margin-top:5px">예약메시지</label>
                            </div>
                            <div style="margin-left:8px;display:flex">
                                <input type="hidden" id="step_idx" name="step_idx" value="">
                                <input type="text" id="reservation_title" name="reservation_title" value="" disabled style="font-size:15px;padding:5px 10px;width:35%">
                                <input type="hidden" id="event_pcode_event" name="event_pcode_" value="">
                                <input type="button" value="퍼널예약관리 조회" class="button " id="searchEventBtn" onclick="newMessageEvent()">
                            </div>
                        </div>
                        <div style="display:flex;justify-content: space-evenly;margin-top:20px">
                            <button type="button" class="btn btn-default btn-submit urlcopy" style="width: 35%;" onclick="window.open('https://tinyurl.com/yc5dw5ek')">
                                퍼널예약<br>세팅방법 보기
                            </button>
                            <button id="auto_making_preview" type="button" class="btn btn-default btn-submit urlcopy" style="width: 35%;" onclick="automaking_preview();" disabled>
                                유저화면<br>미리보기
                            </button>
                        </div>
                        <div style="display:flex;justify-content: space-between;margin-top:20px">
                            <button id="auto_making_copy" type="button" class="btn btn-default btn-submit urlcopy" onclick="copy()" disabled>메시지 단축주소<img src="/iam/img/menu/icon_copy.png"></button>
                            <button type="button" class="btn btn-default btn-submit reqevent">이벤트 신청주소<img src="/iam/img/menu/icon_share_black.png" id="seteventlink"></button>
                        </div>
                        <div style="width:50%;float:right;display:none">
                            <input type="text" name="event_link" id="event_link" placeholder="이벤트 신청주소 입력" style="width:90%;margin-top:10px;float: right;">
                        </div>
                    </div>
                </form>
                <!--div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
                    <p style="margin-left: auto; margin-right: auto; font-size: 12px; margin-top: 20px;">
                        * 퍼널예약관리조회 : 미리 설정한 퍼널문자세트를 선택<br>오토회원가입한 회원에게 자동으로 퍼널문자가 발송<br>
                        * 이벤트신청주소 : 미리 설정한 고객신청창 주소를 입력<br>아이디 중복확인이 되면 이벤트신청하기 탭으로 변환
                    </p>
                </div-->
                <div class="modal-footer" style="padding:0px;margin-top:30px">
                    <input type="hidden" name="reg_msg_surl" id="reg_msg_surl" value="">
                    <div style="display:flex;justify-content: space-between;width:100%">
                        <button type="button" style="padding:10px 0px;border:1px solid #ddd;width:50%;font-size:16px;font-weight: bold;" onclick="goback('making')">취소하기</button>
                        <button type="button" style="padding:10px 0px;width:50%;font-size:16px;font-weight: bold;background:#99cc00;color:white" onclick="reg_msg()">등록하기</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./포인트 쉐어 모달 -->
    <div id="sharepoint_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="text-align: center;">포인트 쉐어하기</h2>
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="iam_table" style="width: 22.8%;border-bottom-color: white;">쉐어ID</td>
                                    <td class="iam_table" style="border-bottom-color: white;"><input type="text" id="share_id" placeholder="아이디를 입력하세요." style="width: 100%;"></td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="width:100%">
                            <tbody>
                                <tr>
                                    <td class="iam_table" style="width: 22.8%;border-bottom-color: white;">쉐어 캐시포인트</td>
                                    <td class="iam_table" style="border-bottom-color: white;"><input type="number" id="share_cash" style="width: 100%;border: none;"></td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="width:100%">
                            <tbody>
                                <tr>
                                    <td class="iam_table" style="width: 22.8%;">쉐어 씨드포인트</td>
                                    <td class="iam_table"><input type="number" id="share_point" style="width: 100%;border: none;"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="container" style="margin-top: 20px;border: 1px solid black;">
                        <p style="font-size:16px;color:#6e6c6c">
                            1. 자신의 계정에 있는 포인트를 다른 ID로 쉐어하는 기능입니다.<br>
                            2. 쉐어하려는 ID와 건수를 입력하면 포인트는 자동으로 입력됩니다.<br>
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:49%;font-size:15px;" onclick="goback('share')">취소</button>
                    <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:49%;font-size:15px;" onclick="start_sharing()" id="start_share">쉐어하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ./결제 모달 -->
    <div id="settlement_mutongjang_modal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="text-align: center;">무통장 입금안내</h2>
                    <div class="container" style="margin-top: 20px;">
                        <p style="font-size:20px;text-align:left;">인물정보에 의한 IAM 생성기능을 이용하기 위해 <span id="buy_point"></span>원을 무통장으로 결제 하였습니다. 3일 이내에 아래의 계좌로 입금하시면 해당기능을 이용할수 있습니다. 감사합니다.<br><br>입금계좌 : SC제일은행 354-20-403048<br>계좌이름 : 온리원셀링</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-submit" data-dismiss="modal" style="border-radius: 5px;width:42%;font-size:15px;" onclick="javascript:location.reload()">확인</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ./결제내역 모달 -->
    <div id="settlement_contents_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h2 style="text-align: center;">IAM 만들기 결제/이용내역</h2>
                    <div class="container" style="display:inline-block;margin-top: 20px;">
                        <input type="date" placeholder="시작일" id="search_start_date" value="<?= $_REQUEST['search_start_date'] ?>" style="border: 1px solid black;width:130px;"><span style="margin-left: 3px;">~</span>
                        <input type="date" placeholder="종료일" id="search_end_date" value="<?= $_REQUEST['search_end_date'] ?>" style="border: 1px solid black;width:130px;">
                        <select title="" id="use_buy_type" data-plugin-selectTwo onchange="" style="width:90px;margin: 0 0 0 5px;">
                            <option value="">전체</option>
                            <option value="use">이용</option>
                            <option value="buy">입금</option>
                        </select>
                        <!-- <input type="text" placeholder="아이디" name="search_ID" id="search_ID" style="margin-top: 20px;border: 1px solid black;width:120px;"> -->
                        <button onclick="search_people('search')"><i class="fa fa-search"></i></button>
                    </div>
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <!-- <div style="border: 1px solid black;">인물주소입력<input type="text" placeholder="네이버 인물검색 웹주소입력"></div>
                        <div ></div>
                        <div ></div> -->
                        <table style="width:100%">
                            <thead>
                                <th class="iam_table" style="width:20%;">일시</th>
                                <th class="iam_table" style="width:22%;">아이디</th>
                                <th class="iam_table" style="width:9%;">유형</th>
                                <th class="iam_table">활동</th>
                                <th class="iam_table">금액</th>
                                <th class="iam_table">잔량</th>
                            </thead>
                            <tbody id="contents_side">

                            </tbody>
                        </table>
                    </div>
                    <div class="container" style="text-align:center;">
                        <button style="border-radius: 5px;width:20%;font-size:15px;margin-top: 10px;" onclick="goback('more')">뒤로가기</button>
                        <button style="border-radius: 5px;width:20%;font-size:15px;margin-top: 10px;" onclick="search_people('more')">더보기</button>
                    </div>
                    <div class="container" style="margin-top: 20px;border: 1px solid black;">
                        <p style="font-size:16px;color:#6e6c6c">
                            [주의사항]<br>
                            1. 잔여액을 출금요청시 구매할때 할인 받은 금액을 이용건수에서 차감하고 지불합니다.<br>
                            2. 아이엠을 자동으로 생성한 이후에는 해당 생성분에 대해서는 환불이 되지 않으므로 생성전에 웹정보를 상세히 확인하기바랍니다.<br>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?
    $logs->add_log("middle_modal_log");
    ?>
    <!-- ./오토회원 설정리스트 모달 -->
    <div id="auto_list_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                    <label style="padding:15px 0px">회원가입 메시지 리스트</label>
                </div>
                <div class="modal-body">
                    <div class="container" style="display:inline-block;margin-top: 20px;">
                        <input type="date" placeholder="시작일" id="search_start_date1" value="<?= $_REQUEST['search_start_date'] ?>" style="border: 1px solid black;width:130px;"><span style="margin-left: 3px;">~</span>
                        <input type="date" placeholder="종료일" id="search_end_date1" value="<?= $_REQUEST['search_end_date'] ?>" style="border: 1px solid black;width:130px;">
                        <button onclick="search_auto()"><i class="fa fa-search"></i></button>
                        <a class="btn" onclick="show_msg_make()" style="border: 1px solid #ead0d0;float: right;">회원가입 메시지만들기</a>
                    </div>
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <table style="width:100%">
                            <thead>
                                <th class="iam_table" style="width:5%;">No</th>
                                <th class="iam_table" style="width:22%;">제목</th>
                                <th class="iam_table" style="width:15%;">보기/링크</th>
                                <th class="iam_table" style="width:15%;">이미지</th>
                                <th class="iam_table">조회/가입</th>
                                <th class="iam_table">등록일</th>
                                <th class="iam_table">수정/삭제</th>
                                <th class="iam_table">이용</th>
                            </thead>
                            <tbody id="contents_side1">
                                <?php
                                $sql = "select * from Gn_event where m_id='{$_SESSION['iam_member_id']}' and event_name_kor='단체회원자동가입및아이엠카드생성' order by regdate desc";
                                $res = mysqli_query($self_con, $sql);
                                $i = 0;
                                while ($row = mysqli_fetch_array($res)) {
                                    $pop_url = '/event/automember.php?pcode=' . $row['pcode'] . '&eventidx=' . $row['event_idx'];
                                    $id_sql = "select count(event_id) as cnt from Gn_Member where event_id={$row['event_idx']} and mem_type='A'";
                                    $res_id = mysqli_query($self_con, $id_sql);
                                    $row_id = mysqli_fetch_array($res_id);
                                    if ($row_id['cnt'] != null) {
                                        $cnt_join = $row_id['cnt'];
                                    } else {
                                        $cnt_join = 0;
                                    }
                                    $i++;
                                    $sql_service = "select auto_join_event_idx from Gn_Iam_Service where mem_id='{$row['m_id']}'";
                                    $res_service = mysqli_query($self_con, $sql_service);
                                    $row_service = mysqli_fetch_array($res_service);
                                    if ($row["event_idx"] == $row_service['auto_join_event_idx']) {
                                        $checked_auto = "checked";
                                    } else {
                                        $checked_auto = "";
                                    }
                                ?>
                                    <tr>
                                        <td class="iam_table"><?= $i ?></td>
                                        <td class="iam_table"><?= $row['event_title'] ?></td>
                                        <td class="iam_table"><a onclick="newpop('<?= $pop_url ?>')">미리보기</a>/<a onclick="copy('<?= $row['short_url'] ?>')">링크복사</a></td>
                                        <td class="iam_table"><? if ($row['object'] != "") { ?><img class="zoom" src="<?= $row['object'] ?>" style="width:90%;"><? } ?></td>
                                        <td class="iam_table"><?= $row['read_cnt'] ?>/<?= $cnt_join ?></td>
                                        <td class="iam_table"><?= $row['regdate'] ?></td>
                                        <td class="iam_table"><a onclick="edit_ev(<?= $row['event_idx'] ?>)">수정</a>/<a onclick="delete_ev(<?= $row['event_idx'] ?>)">삭제</a></td>
                                        <? if ($_SESSION['iam_member_id'] == "iam1") { ?>
                                            <td class="iam_table">
                                                <label class="auto_switch_copy">
                                                    <input type="checkbox" name="auto_status" id="auto_stauts_<?php echo $row['event_idx']; ?>" value="<?php echo $row['event_idx']; ?>" <?= $checked_auto ?>>
                                                    <span class="slider round" name="auto_status_round" id="auto_stauts_round_<?php echo $row['event_idx']; ?>"></span>
                                                </label>
                                                <input type="hidden" name="auto_service_id" id="auto_service_id" value="<?= $row['m_id'] ?>">
                                            </td>
                                        <? } else { ?>
                                            <td class="iam_table">
                                                <label class="auto_switch">
                                                    <input type="checkbox" name="auto_status" id="auto_stauts_<?php echo $row['event_idx']; ?>" value="<?php echo $row['event_idx']; ?>" <?= $checked_auto ?>>
                                                    <span class="slider round" name="auto_status_round" id="auto_stauts_round_<?php echo $row['event_idx']; ?>"></span>
                                                </label>
                                                <input type="hidden" name="auto_service_id" id="auto_service_id" value="<?= $row['m_id'] ?>">
                                            </td>
                                        <? } ?>
                                    </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?
    $logs->add_log("autoreg_modal_log");
    ?>
    <!-- ./오토회원 설정리스트 수정 모달 -->
    <div id="auto_list_edit_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content" id="edit_modal_content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                    <label style="padding:15px 0px">회원가입 메시지</label>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <table style="width:100%">
                            <form method="post" id="dForm_edit" name="dForm_edit" action="/ajax/edit_event.php" enctype="multipart/form-data">
                                <input type="hidden" name="save" value="save">
                                <tbody id="edit_event">
                                    <tr>
                                        <input type="hidden" id="event_idx" name="event_idx" value="">
                                        <th class="iam_table" style="width:20%;">아이디</th>
                                        <td class="iam_table"><?= $_SESSION['iam_member_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">이벤트타이틀</th>
                                        <td class="iam_table">
                                            <textarea style="width:100%;height: 50px;" name="event_title" id="event_title" value=""></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">이벤트메시지</th>
                                        <td class="iam_table"><textarea style="width:100%;height: 100px;" name="event_desc" id="event_desc" value=""></textarea></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">카드링크</th>
                                        <td class="iam_table">
                                            <input type="text" id="card_short_url" name="card_short_url" hidden>
                                            <div id="cardsel1" onclick="limit_selcard1()" style="margin-top:15px;">
                                                <?
                                                $sql5 = "select card_short_url,phone_display, card_title from Gn_Iam_Name_Card where group_id = 0 and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                                                $result5 = mysqli_query($self_con, $sql5);
                                                $i = 0;
                                                while ($row5 = mysqli_fetch_array($result5)) {
                                                    if ($i == 0) {
                                                        $hidden = "hidden";
                                                    } else {
                                                        $hidden = "";
                                                    }
                                                ?>
                                                    <input type="checkbox" id="multi_westory_card_url1_<?= $i + 1 ?>" name="multi_westory_card_url1" class="we_story_radio1" value="<?= $i + 1 ?>" <? if (
                                                                                                                                                                                                        $row5['phone_display'] == "N"
                                                                                                                                                                                                    ) {
                                                                                                                                                                                                        echo "onclick='locked_card_click();'";
                                                                                                                                                                                                    } ?> <?= $hidden ?>>
                                                    <span <? if ($row5['phone_display'] == "N") {
                                                                echo "class='locked' title='비공개카드'";
                                                            } ?> <?= $hidden ?>>
                                                        <?= $i + 1 ?>번(<?= $row5['card_title'] ?>)
                                                    </span>
                                                <?
                                                    $i++;
                                                }
                                                ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- <input type="text" style="width:100%;" name="card_short_url" id="card_short_url" value="" > -->
                                    <tr>
                                        <th class="iam_table" style="width:20%;">버튼타이틀</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="btn_title" id="btn_title" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">버튼링크</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="btn_link" id="btn_link" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">단축주소</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="short_url" id="short_url" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">이벤트신청주소</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="event_req_link" id="event_req_link" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">조회수</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="read_cnt" id="read_cnt" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">이미지</th>
                                        <td class="iam_table"><input type="file" name="autojoin_img" style="width:200px;"><span id="autojoin_img_event"></span></td>
                                    </tr>
                                    <tr id="step_info_tr">
                                        <th class="iam_table" style="width:20%;">퍼널문자정보</th>
                                        <td class="iam_table">
                                            <input type="text" style="width:45%;" name="step_title" id="step_title" value="" disabled>
                                            <input type="text" style="width:10%;" name="step_cnt" id="step_cnt" value="" disabled>
                                            <input type="text" style="width:30%;" name="step_phone" id="step_phone" value="" disabled>
                                            <br><br>
                                            <p id="step_info" style="display:inline-block;"></p>
                                            적용상황
                                            <label class="step_switch">
                                                <input type="checkbox" name="step_allow_state" id="step_allow_state" value="">
                                                <span class="slider round" name="step_status_round" id="step_status_round"></span>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">등록일시</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="regdate1" id="regdate1" value=""></td>
                                    </tr>
                                </tbody>
                        </table>
                        </form>
                    </div>
                </div>
                <div class="modal-footer" style="padding:0px;display:flex">
                    <button type="button" class="btn btn-default" style="width:50%;border-radius:0px;padding:15px 0px;" onclick="goback('auto_list')">뒤로가기</button>
                    <button type="button" class="btn btn-active" style="width:50%;border-radius:0px;margin-left:0px;padding:15px 0px;" onclick="save_edit_ev()">저장</button>
                </div>
            </div>
        </div>
    </div>
    <?
    $logs->add_log("autoedit_modal_log");
    ?>
    <!-- ./콜백메시지 설정리스트 모달 -->
    <div id="callback_list_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                    <label style="padding:15px 0px">콜백 메시지 리스트</label>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <table style="width:100%">
                            <thead>
                                <th class="iam_table" style="width:5%;">No</th>
                                <th class="iam_table" style="width:10%;">타이틀</th>
                                <th class="iam_table" style="width:10%;">메시지</th>
                                <th class="iam_table" style="width:10%;">이미지</th>
                                <th class="iam_table">보기/링크</th>
                                <th class="iam_table">조회수</th>
                                <th class="iam_table">유지/해지</th>
                                <th class="iam_table">등록일</th>
                                <!--th class="iam_table">발송횟수</th-->
                                <th class="iam_table">노출여부</th>
                                <th class="iam_table">수정/삭제</th>
                                <? if ($_SESSION['iam_member_id'] == "iam1") { ?>
                                    <th class="iam_table">샘플</th>
                                <? } ?>
                            </thead>
                            <tbody id="callback_msg_set_list">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?
    $logs->add_log("callback_modal_log");
    ?>
    <!-- ./콜백메시지 설정리스트 수정 모달 -->
    <div id="callback_list_edit_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content" id="edit_callback_modal_content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                    <label style="padding:15px 0px">콜백 메시지</label>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <table style="width:100%">
                            <form method="post" id="dForm_call_edit" name="dForm_call_edit" action="/ajax/edit_event_callback.php" enctype="multipart/form-data">
                                <tbody id="edit_event">
                                    <tr>
                                        <input type="hidden" name="save" value="save">
                                        <input type="hidden" id="call_event_idx" name="call_event_idx" value="">
                                        <th class="iam_table" style="width:85px;">아이디</th>
                                        <td class="iam_table"><?= $_SESSION['iam_member_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:85px;">요청메시지 제목</th>
                                        <td class="iam_table">
                                            <textarea style="width:100%;height: 50px;" name="event_title_call" id="event_title_call" value=""></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:85px;">요청메시지 내용</th>
                                        <td class="iam_table">
                                            <textarea style="width:100%;height: 50px;" name="event_desc_call" id="event_desc_call" value=""></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:85px;">콜백 타이틀</th>
                                        <td class="iam_table">
                                            <textarea style="width:100%;height: 50px;" name="call_event_title" id="call_event_title" value=""></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:85px;">콜백 메시지</th>
                                        <td class="iam_table"><textarea style="width:100%;height: 100px;" name="call_event_desc" id="call_event_desc" value=""></textarea></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:85px;">링크주소</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="call_iam_link" id="call_iam_link" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:85px;">이미지</th>
                                        <td class="iam_table"><input type="file" name="call_event_img" style="width:200px;"><span id="call_img_event"></span> </td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:85px;">메시지단축주소</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="call_short_url" id="call_short_url" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:85px;">조회수</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="call_read_cnt" id="call_read_cnt" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:85px;">등록일시</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="call_regdate1" id="call_regdate1" value=""></td>
                                    </tr>
                            </form>
                            <tr>
                                <th class="iam_table" style="width:85px;">발송하기</th>
                                <td class="iam_table"><button style="float:left;padding: 5px;" onclick="app_set_list('self', 'callback')">셀프폰 발송하기</button><button style="float:left;padding: 5px;margin-left:10px;" onclick="app_set_list('push', 'callback')">푸시형 전송하기</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="container" id="app_sets_member_call" style="margin-top: 20px;text-align: center;display:none;">
                        <h2 style="text-align: center;" id="send_type_title_call"></h2>
                        <input type="hidden" name="send_type_call" id="send_type_call">
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="hide_spec">
                                    <td class="bold" id="remain_count" data-num="" style="width:70px;">전송하기<br>
                                        <textarea name="app_set_mbs_count_call" id="app_set_mbs_count_call" style="width:100%; height:50px;color: red;font-size: 12px" readonly="" data-num="0" placeholder="0건"></textarea>
                                    </td>
                                    <td colspan="2">
                                        <div style="display:flex">
                                            <!-- <div style="border: solid 1px #b5b5b5;width:75%; height:auto;" name="contents_share_id" id="contents_share_id"></div> -->
                                            <textarea name="app_set_mbs_id_call" id="app_set_mbs_id_call" style="border: solid 1px #b5b5b5;width:100%; height:100px;" data-num="0" placeholder="전송할 친구 아이디를 입력하세요.<컴마로 구분>"></textarea>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="display:flex;width:100%">
                            <button type="button" class="btn btn-default" style="width:50%;border-radius:0px;padding:15px 0px;" onclick="cancel_app_list('callback')">취소</button>
                            <button type="button" class="btn btn-active" style="width:50%;border-radius:0px;margin-left:0px;padding:15px 0px;" onclick="send_msg_applist('callback')">전송하기</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding:0px;display:flex">
                    <button type="button" class="btn btn-default" style="width:50%;border-radius:0px;padding:15px 0px;" onclick="goback('callback_list')">뒤로가기</button>
                    <button type="button" class="btn btn-active" style="width:50%;border-radius:0px;margin-left:0px;padding:15px 0px;" onclick="save_call_edit_ev()">저장</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ./데일리 설정리스트 모달 -->
    <div id="daily_list_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                    <label style="padding:15px 0px">데일리 메시지 리스트</label>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <table style="width:100%">
                            <thead>
                                <th class="iam_table" style="width:5%;">번호</th>
                                <th class="iam_table" style="width:15%;">타이틀</th>
                                <th class="iam_table" style="width:15%;">메시지</th>
                                <th class="iam_table" style="width:15%;">이미지</th>
                                <th class="iam_table" style="width:15%;">미리보기/링크복사</th>
                                <th class="iam_table" style="width:15%;">조회수/신청수</th>
                                <th class="iam_table">등록일</th>
                                <th class="iam_table">수정/삭제</th>
                                <? if ($_SESSION['iam_member_id'] == "iam1") { ?>
                                    <th class="iam_table">샘플</th>
                                <? } ?>
                            </thead>
                            <tbody id="contents_side1">
                                <?php
                                $sql = "select * from Gn_event where m_id='{$_SESSION['iam_member_id']}' and event_name_kor='데일리문자세트자동생성' order by regdate desc";
                                $res = mysqli_query($self_con, $sql);
                                $i = 0;

                                $sql_service_set_idx = "select daily_msg_event_idx from Gn_Iam_Service where mem_id='iam1'";
                                $res_set_idx = mysqli_query($self_con, $sql_service_set_idx);
                                $row_set_idx = mysqli_fetch_array($res_set_idx);

                                while ($row = mysqli_fetch_array($res)) {
                                    $pop_url = '/event/dailymsg.php?pcode=' . $row['pcode'] . '&eventidx=' . $row['event_idx'];
                                    $i++;
                                    $sql_req_mem = "select count(*) as cnt from Gn_daily where event_idx={$row['event_idx']}";
                                    $res_req_mem = mysqli_query($self_con, $sql_req_mem);
                                    $row_req_mem = mysqli_fetch_array($res_req_mem);

                                    if ($row_set_idx['daily_msg_event_idx'] == $row['event_idx']) {
                                        $checked_dup = "checked";
                                    } else {
                                        $checked_dup = "";
                                    }
                                ?>
                                    <tr>
                                        <td class="iam_table"><?= $i ?></td>
                                        <td class="iam_table"><a href="javascript:show_more('<?= $row['event_info'] ?>')"><?= cut_str($row['event_title'], 5) ?></a></td>
                                        <td class="iam_table"><a href="javascript:show_more('<?= str_replace("\n", "<br>", $row['event_req_link']) ?>')"><?= cut_str($row['event_req_link'], 10) ?></a></td>
                                        <td class="iam_table"><? if ($row['object'] != "") { ?><img class="zoom" src="http://www.kiam.kr/<?= $row['object'] ?>" style="width:90%;"><? } ?></td>
                                        <td class="iam_table"><a onclick="newpop('<?= $pop_url ?>')">미리보기</a>/<a onclick="copy('<?= $row['short_url'] ?>')">링크복사</a></td>
                                        <td class="iam_table"><a href="javascript:show_req_mem('<?= $row['event_idx'] ?>')"><?= $row['read_cnt'] ?>/<?= $row_req_mem['cnt'] ?></a></td>
                                        <td class="iam_table"><?= $row['regdate'] ?></td>
                                        <td class="iam_table"><a onclick="edit_ev_daily(<?= $row['event_idx'] ?>)">수정</a>/<a onclick="delete_ev_daily(<?= $row['event_idx'] ?>)">삭제</a></td>
                                        <? if ($_SESSION['iam_member_id'] == "iam1") { ?>
                                            <td class="iam_table">
                                                <label class="switch_daily_copy">
                                                    <input type="checkbox" name="status" id="daily_stauts_<?php echo $row['event_idx']; ?>" value="<?php echo $row['event_idx']; ?>" <?= $checked_dup ?>>
                                                    <span class="slider round" name="status_round" id="stauts_round_<?php echo $row['event_idx']; ?>"></span>
                                                </label>
                                            </td>
                                        <? } ?>
                                    </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./데일리 신청회원리스트 모달 -->
    <div id="daily_reqmem_list_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background-color:rgb(130,199,54); color:white;">
                    <h2 style="text-align: center;">디비 데일리 신청자</h2>
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -33px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <table style="width:100%">
                            <thead>
                                <th class="iam_table">번호</th>
                                <th class="iam_table">이름</th>
                                <th class="iam_table">아이디</th>
                                <th class="iam_table">상세보기/삭제</th>
                            </thead>
                            <tbody id="daily_reqmem_table">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./데일리 설정리스트 수정 모달 -->
    <div id="daily_list_edit_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content" id="edit_modal_content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                    <label style="padding:15px 0px">데일리 메시지</label>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <table style="width:100%">
                            <form method="post" id="dForm_daily_edit" name="dForm_daily_edit" action="/ajax/edit_event_daily.php" enctype="multipart/form-data">
                                <tbody id="edit_event">
                                    <tr>
                                        <input type="hidden" id="daily_event_idx" name="daily_event_idx" value="">
                                        <input type="hidden" name="save" value="save">
                                        <th class="iam_table" style="width:20%;">아이디</th>
                                        <td class="iam_table"><?= $_SESSION['iam_member_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">이벤트타이틀</th>
                                        <td class="iam_table">
                                            <textarea style="width:100%;height: 50px;" name="daily_event_title_intro" id="daily_event_title_intro" value=""></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">이벤트메시지</th>
                                        <td class="iam_table"><textarea style="width:100%;height: 100px;" name="daily_event_desc_intro" id="daily_event_desc_intro" value=""></textarea></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">제목</th>
                                        <td class="iam_table">
                                            <textarea style="width:100%;height: 50px;" name="daily_event_title" id="daily_event_title" value=""></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">내용</th>
                                        <td class="iam_table"><textarea style="width:100%;height: 100px;" name="daily_event_desc" id="daily_event_desc" value=""></textarea></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">링크주소</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="daily_req_link" id="daily_req_link" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">조회수</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="daily_read_cnt" id="daily_read_cnt" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">이미지</th>
                                        <td class="iam_table"><input type="file" name="daily_img" style="width:93px;"><span id="daily_img_event"></span></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">일발송량</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="daily_send_cnt" id="daily_send_cnt" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">발송시간</th>
                                        <td class="iam_table">
                                            <select name="htime" style="width:50px;">
                                                <?
                                                for ($i = 9; $i < 22; $i++) {
                                                    $iv = $i < 10 ? "0" . $i : $i;
                                                ?>
                                                    <option value="<?= $iv ?>"><?= $iv ?></option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                            <select name="mtime" style="width:50px;">
                                                <?
                                                for ($i = 0; $i < 31; $i += 30) {
                                                    $iv = $i == 0 ? "00" : $i;
                                                ?>
                                                    <option value="<?= $iv ?>"><?= $iv ?></option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">등록일시</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="daily_regdate1" id="daily_regdate1" value=""></td>
                                    </tr>
                                    <tr>
                                        <th class="iam_table" style="width:20%;">단축주소</th>
                                        <td class="iam_table"><input type="text" style="width:100%;" name="daily_short_url" id="daily_short_url" value=""></td>
                                    </tr>
                            </form>
                            <tr>
                                <th class="iam_table" style="width:20%;">발송하기</th>
                                <td class="iam_table"><button style="float:left;padding: 5px;" onclick="app_set_list('self', 'daily')">셀프폰 발송하기</button><button style="float:left;padding: 5px;margin-left:10px;" onclick="app_set_list('push', 'daily')">푸시형 전송하기</button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="container" id="app_sets_member_daily" style="margin-top: 20px;text-align: center;display:none;">
                        <h2 style="text-align: center;" id="send_type_title_daily"></h2>
                        <input type="hidden" name="send_type_daily" id="send_type_daily">
                        <table class="table table-bordered">
                            <tbody>
                                <tr class="hide_spec">
                                    <td class="bold" id="remain_count" data-num="" style="width:70px;">전송하기<br>
                                        <textarea name="app_set_mbs_count_daily" id="app_set_mbs_count_daily" style="width:100%; height:50px;color: red;font-size: 12px" readonly="" data-num="0" placeholder="0건"></textarea>
                                    </td>
                                    <td colspan="2">
                                        <div style="display:flex">
                                            <!-- <div style="border: solid 1px #b5b5b5;width:75%; height:auto;" name="contents_share_id" id="contents_share_id"></div> -->
                                            <textarea name="app_set_mbs_id_daily" id="app_set_mbs_id_daily" style="border: solid 1px #b5b5b5;width:100%; height:100px;" data-num="0" placeholder="전송할 친구 아이디를 입력하세요.<컴마로 구분>"></textarea>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="display:flex">
                            <button type="button" class="btn btn-default" style="width:50%;border-radius:0px;padding:15px 0px;" onclick="cancel_app_list('daily')">취소</button>
                            <button type="button" class="btn btn-active" style="width:50%;border-radius:0px;margin-left:0px;padding:15px 0px;" onclick="send_msg_applist('daily')">전송하기</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding:0px;display:flex">
                    <button type="button" class="btn btn-default" style="width:50%;border-radius:0px;padding:15px 0px;" onclick="goback('daily_list')">뒤로가기</button>
                    <button type="button" class="btn btn-active" style="width:50%;border-radius:0px;margin-left:0px;padding:15px 0px;" onclick="save_daily_edit_ev()">저장</button>
                </div>
            </div>
        </div>
    </div>
    <div id="daily_reqmem_detail_list_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background-color:rgb(130,199,54); color:white;">
                    <!-- <h2 style="text-align: center;">디비 데일리 신청자</h2> -->
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -10px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <table style="width:100%">
                            <thead>
                                <th class="iam_table">발송폰번호</th>
                                <th class="iam_table">주소건수</th>
                                <th class="iam_table">발송일수</th>
                                <th class="iam_table">일발송량</th>
                                <th class="iam_table">발송시작일</th>
                                <th class="iam_table">발송마감일</th>
                                <th class="iam_table">등록일</th>
                            </thead>
                            <tbody id="daily_reqmem_detail_table">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="mutong_settlement" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border:none;background-color: rgb(130,199,54)">
                    <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">포인트 충전
                    </div>
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -33px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;box-shadow: none;">
                        <p style="font-size:16px;color:#6e6c6c">
                            회원님의 보유 포인트는 <?= $Gn_point ?> P 입니다.<br>
                            포인트 부족시 충전하시기 바랍니다.
                        </p>
                    </div>
                    <div class="container" style="margin-top: 20px;box-shadow: none;">
                        <p style="font-size:16px;color:#6e6c6c">
                            결제금액:<input type="number" id="money_point" style="border: 1px solid;">
                        </p>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:right;">
                    <a class="btn btn-default btn-submit" style="border-radius: 5px;width:49%;font-size:15px;background-color: #ff0066;color:white;" onclick="card_settle()" target="_blank">카드 결제</a>
                    <a class="btn btn-default btn-submit" style="border-radius: 5px;width:49%;font-size:15px;background-color: #ff0066;color:white;" onclick="bank_settle()" target="_blank">무통장 결제</a>
                </div>
            </div>
        </div>
    </div>
    <div id="mutong_settle" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border:none;background-color: rgb(130,199,54)">
                    <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">무통장 결제 안내
                    </div>
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -33px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;box-shadow: none;">
                        <p style="font-size:16px;color:#6e6c6c">
                            [입금 계좌 안내]<br>
                            스텐다드차타드은행 617-20-109431<br>
                            온리원연구소(구,SC제일은행)
                        </p>
                    </div>
                </div>
                <div class="modal-footer" style="text-align:center;">
                    <a class="btn btn-default btn-submit" style="border-radius: 5px;width:49%;font-size:15px;background-color: #ff0066;color:white;" href="https://pf.kakao.com/_jVafC/chat" target="_blank">입금 후 카톡창에 남기기</a>
                </div>
            </div>
        </div>
    </div>
    <div id="show_detail_more" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border:none;background-color: rgb(130,199,54)">
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -12px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;box-shadow: none;">
                        <p style="font-size:16px;color:#6e6c6c" id="contents_detail">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="show_select_member" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border:none;background-color: rgb(146 148 144)">
                    <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">콜백 설정 유지
                    </div>
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -12px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;box-shadow: none;">
                        <p style="font-size:16px;color:#6e6c6c;text-align:center;" id="mem_selected">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="show_unselect_member" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border:none;background-color: rgb(146 148 144)">
                    <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">콜백 설정 해지
                    </div>
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -12px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;box-shadow: none;">
                        <p style="font-size:16px;color:#6e6c6c;text-align:center;" id="mem_unselecte">
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?
    $logs->add_log("daily_modal_log");
    ?>
    <!--GPT chat modal-->
    <div id="gpt_chat_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 30px auto;width: 100%;max-width:700px">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background: #5bd540;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <div class="login_bold" id="gwc_con_name_modal" style="margin-bottom: 0px;color: #ffffff;font-size: 17px;text-align: center">콘텐츠 창작AI 알지(ALJI)</div>
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -27px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body" style="background-color:#e5e3e3;">
                    <div class="container" style="text-align: center;">
                        <input type="hidden" id="insert_modal_name" value="">
                        <p><img src="/iam/img/arji_intro_title.png" style="width: 22px;margin-right: 3px;">"알지(ALJI)" 인공지능에게 무엇이든 물어보세요.<br>구체적으로 질문할수록 "알지 AI" 답변이 정교해집니다.</p>
                        <p id="gpt_req_list_title" hidden>질문답변목록</p>
                        <ul id="answer_side" hidden>
                            <a class="copy_msg" href="javascript:copy_msg()"><img src="/iam/img/gpt_res_copy.png" style="height:20px;"></a>
                        </ul>
                        <ul id="answer_side1">
                            <?
                            $gpt_qu = get_search_key('gpt_question_example');
                            $gpt_an = get_search_key('gpt_answer_example');
                            $gpt_qu_arr = explode("||", $gpt_qu);
                            $gpt_an_arr = explode("||", $gpt_an);
                            for ($i = 0; $i < count($gpt_qu_arr); $i++) {
                            ?>
                                <li class="article-title" id="q<?= $i ?>" onclick="show('<?= $i ?>')"><img src="/iam/img/chat_Q.png" style="width:30px;margin-right: 10px;"><span class="chat_title"><?= htmlspecialchars_decode($gpt_qu_arr[$i]) ?></span><i id="down<?= $i ?>" class="fa fa-angle-down" style="font-size: 20px;font-weight: bold;margin-left: 10px;"></i><i id="up<?= $i ?>" class="fa fa-angle-up" style="font-size: 20px;font-weight: bold;margin-left: 10px;display:none;"></i></li>
                                <li class="article-content hided" id="a<?= $i ?>"><img src="/iam/img/chat_A.png" style="width:30px;"><span class="chat_answer" style="margin-left: 35px;"><?= htmlspecialchars_decode($gpt_an_arr[$i]) ?></span></li>
                            <? } ?>
                        </ul>
                        <ul id="answer_side2" hidden>
                        </ul>
                        <div class="gpt_act">
                            <a class="history" href="javascript:show_req_history();"><img src="/iam/img/gpt_req_list.png" style="height: 25px;"></a>
                            <a class="newpane" href="javascript:show_new_chat();"><span style="font-size: 5px;">NEW</a>
                        </div>
                        <div class="search_keyword">
                            <input type="hidden" name="key" id="key" value="<?= $member_iam['gpt_chat_api_key'] ?>">
                            <textarea class="search_input" autocomplete="off" name="question" id="question" value="" title="질문을 입력하세요" placeholder="알지AI에게 질문해보세요" onclick="check_login('<?= $_SESSION['iam_member_id'] ?>')"></textarea>
                            <button type="button" onclick="send_post('<?= $_SESSION['iam_member_id'] ?>')" class="send_ask"><img src="/iam/img/send_ask.png" alt="전송"></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align: center;background-color: #e5e3e3;padding:7px;">
                    <button type="button" style="width:50%;background:#99cc00;color:white;padding:10px 0px;border: none;" onclick="send_chat()">보내기</button>
                </div>
            </div>
        </div>
    </div>
    <div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>

    <?
    $logs->add_log("end_modal");
    ?>
    <script>
        var short_url = "";
        var ele = 0;
        var contextarray = [];

        function show_chat(type, api) {
            $("#gpt_chat_modal").modal('show');
            $("#insert_modal_name").val(type);
        }
        $(document).ready(function() {
            var textarea = document.getElementById("question");
            var limit = 110; //height limit
            var api_state = '<?= $member_iam['gpt_chat_api_key'] ?>';

            textarea.oninput = function() {
                textarea.style.height = "";
                textarea.style.height = Math.min(textarea.scrollHeight, limit) + "px";
            };

            $("#question").on('keydown', function(event) {
                if (api_state == '') {
                    alert("회원정보에서 본인의 API 키를 입력해주세요.");
                    location.href = "mypage.php";
                }
                if (event.keyCode == 13) {
                    if (event.shiftKey) {
                        $("#kw-target").html($("#kw-target").html() + "\n");
                        event.stopPropagation();
                    } else {
                        send_post('<?= $_SESSION['iam_member_id'] ?>');
                    }
                }
            });
        });

        function check_login(id) {
            if (id == '') {
                $("#intro_modal").modal('show');
            } else {
                return;
            }
        }

        function show_new_chat() {
            $("#answer_side").hide();
            $("#gpt_req_list_title").hide();
            $("#answer_side1").show();
            $("#answer_side2").hide();
        }

        function show(val) {
            if ($('li[id=a' + val + ']').hasClass('hided')) {
                $('li[id=a' + val + ']').removeClass('hided');
                $('i[id=down' + val + ']').css('display', 'none');
                $('i[id=up' + val + ']').css('display', 'inline-block');
            } else {
                $('li[id=a' + val + ']').addClass('hided');
                $('i[id=down' + val + ']').css('display', 'inline-block');
                $('i[id=up' + val + ']').css('display', 'none');
            }
        }

        function show_req_history() {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/manage_gpt_chat.php",
                data: {
                    mem_id: "<?= $_SESSION['iam_member_id'] ?>",
                    method: 'show_req_list'
                },
                dataType: 'html',
                success: function(data) {
                    // console.log(data);
                    $("#answer_side").hide();
                    $("#answer_side1").hide();
                    $("#gpt_req_list_title").show();
                    $("#answer_side2").html(data);
                    $("#answer_side2").show();
                }
            });
        }

        function copy_msg() {
            var value = $("#answer_side").text().trim();
            console.log(value.trim());
            // return;
            var aux1 = document.createElement("input");
            // 지정된 요소의 값을 할당 한다.
            aux1.setAttribute("value", value);
            // bdy에 추가한다.
            document.body.appendChild(aux1);
            // 지정된 내용을 강조한다.
            aux1.select();
            // 텍스트를 카피 하는 변수를 생성
            document.execCommand("copy");
            // body 로 부터 다시 반환 한다.
            document.body.removeChild(aux1);
            alert("복사되었습니다. 원하는 곳에 붙여 넣으세요.");
        }

        function del_list(id) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/manage_gpt_chat.php",
                data: {
                    method: 'del_req_list',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.result == "1") {
                        alert('삭제 되었습니다.');
                        show_req_history();
                    } else {
                        alert('삭제실패 되었습니다.');
                    }
                }
            });
        }

        function articlewrapper(question, answer, str) {
            $("#answer_side").html('<li class="article-title" id="q' + answer + '"><img src="/iam/img/chat_Q.png" style="width:30px;margin-right: 10px;"><span class="chat_title"></span></li>');
            let str_ = ''
            let i = 0
            let timer = setInterval(() => {
                if (str_.length < question.length) {
                    str_ += question[i++]
                    $("#q" + answer).children('span').text(str_ + '_') //인쇄할 때 커서 추가
                } else {
                    clearInterval(timer)
                    $("#q" + answer).children('span').text(str_) //인쇄할 때 커서 추가
                }
            }, 5)
            $("#answer_side").append('<li class="article-content" id="' + answer + '"><img src="/iam/img/chat_A.png" style="width:30px;"><span class="chat_answer" style="margin-left: 35px;"></span></li>');
            if (str == null || str == "") {
                str = "서버가 응답하는 데 시간이 걸리면 나중에 다시 시도할 수 있습니다.";
            }
            let str2_ = ''
            let i2 = 0
            let timer2 = setInterval(() => {
                if (str2_.length < str.length) {
                    str2_ += str[i2++]
                    $("#" + answer).children('span').text(str2_ + '_') //인쇄할 때 커서 추가
                } else {
                    clearInterval(timer2)
                    $("#" + answer).children('span').text(str2_) //인쇄할 때 커서 추가

                }

                $('#answer_side').animate({
                    scrollTop: 10000,
                }, 10);
            }, 25)
        }

        function clear_head_logo(idx) {
            $.ajax({
                type: "POST",
                url: "/admin/ajax/service_Iam_save.php",
                dataType: "json",
                data: {
                    mode: "clear_head_logo",
                    idx: idx
                },
                success: function(data) {
                    location.reload();
                },
                error: function() {
                    //alert('초기화 실패');
                }
            });
        }

        function clear_footer_logo(idx) {
            $.ajax({
                type: "POST",
                url: "/admin/ajax/service_Iam_save.php",
                dataType: "json",
                data: {
                    mode: "clear_footer_logo",
                    idx: idx
                },
                success: function(data) {
                    location.reload();
                },
                error: function() {
                    //alert('초기화 실패');
                }
            });
        }

        function randomString(len) {
            len = len || 32;
            var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678'; /****혼란스러운 문자는 기본적으로 제거됩니다oOLl,9gq,Vv,Uu,I1****/
            var maxPos = $chars.length;
            var pwd = '';
            for (i = 0; i < len; i++) {
                pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
            }
            return pwd;
        }

        function send_chat() {
            var title = $("#answer_side span.chat_title").text();
            var detail = $("#answer_side span.chat_answer").text();
            var modal_name = $("#insert_modal_name").val();
            if (title == "") {
                alert('질문해주세요.');
                return;
            }
            if (modal_name == "auto") {
                $("#msgtitle").val(title);
                $("#msgdesc").val(detail);
            } else {
                $("#msgtitle_call").val(title);
                $("#msgdesc_call").val(detail);
            }
            $("#gpt_chat_modal").modal('hide');
        } //gpt chat

        function locked_card_click() {
            toastr.options = {
                "timeOut": 10000
            }
            toastr.error("선택한 아이엠카드는 비공개이므로 위스토리에 나타나지 않습니다. <br><br> 노출을 원하시면  자물쇠를 공개상태로 바꾸시기 바랍니다.", "비공개 카드 선택");
        }

        $("#setlink").on('click', function() {
            $("#myiamlink").parents("div").first().css('display', "flex");
        });

        $("#seteventlink").on('click', function() {
            $("#event_link").parents("div").first().show();
        });
        $("#callback_times").on('change', function() {
            var mem_code = '<?= $member_iam['mem_code'] ?>';
            var callback_type = $(this).val();
            $.ajax({
                type: "POST",
                url: "/admin/ajax/user_service_type_change.php",
                dataType: "json",
                data: {
                    mode: "updat",
                    mem_code: mem_code,
                    service_type: 'callback_times',
                    service_value: callback_type
                },
                success: function(data) {
                    if (data.status) {
                        alert('변경이 완료되었습니다.');
                    } else {
                        alert('변경 실패');
                    }
                },
                error: function() {
                    alert('변경 실패');
                }
            });
        });

        function point_chung() {
            $('#auto_memiam_modal').modal('hide');
            $('#callback_msg_modal').modal('hide');
            $('#mutong_settlement').modal('show');
        }

        function callback_list() {
            $('#callback_msg_modal').modal('hide');
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_callback.php",
                data: {
                    get_callback_list: "Y",
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                },
                dataType: "html",
                success: function(data) {
                    $("#callback_msg_set_list").html(data);
                    $('#callback_list_modal').modal('show');
                }
            });
        }
        /*function set_callback_time(callback_idx,obj){
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_callback.php",
                data: {
                    update_callback_time: $(obj).val(),
                    callback_idx: callback_idx
                },
                dataType: "html",
                success: function(data) {
                    alert("콜백횟수가 수정되었습니다.")
                }
            });
        }*/
        function card_settle() {
            item_price = $("#money_point").val();
            location.href = "/iam/pay_point.php?item_price=" + item_price;
        }

        function bank_settle() {
            item_price = $("#money_point").val();
            $.ajax({
                type: "POST",
                url: "/makeData_item.php",
                data: {
                    payMethod: "BANK",
                    member_type: "포인트충전",
                    allat_amt: item_price,
                    pay_percent: 90,
                    allat_order_no: <?= $mid ?>,
                    point_val: 1
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                }
            });
            $("#mutong_settlement").modal('hide');
            $("#mutong_settle").modal('show');
        }

        function limit_selcard() {
            var sel_card = new Array();
            var cnt;
            $('input[class=we_story_radio]:checked').each(function() {
                var idVal = $(this).attr("value");
                cnt = sel_card.push(idVal);
                /*if(cnt > 4){
                    alert('최대 4개까지 선택할수 있습니다.');
                    $('input[id=multi_westory_card_url_'+idVal+']').prop("checked", false);
                    return;
                }*/
                $("#sel_card").val(sel_card.join(","));
            });
        }

        function limit_selcard_self() {
            var sel_card = new Array();
            var cnt;
            $('input[class=we_story_radio_self]:checked').each(function() {
                var idVal = $(this).attr("value");
                cnt = sel_card.push(idVal);
                /*if(cnt > 4){
                    alert('최대 4개까지 선택할수 있습니다.');
                    $('input[id=multi_westory_card_url_self_'+idVal+']').prop("checked", false);
                    return;
                }*/
                $("#self_share_card").val(sel_card.join(","));
            });
        }

        function limit_selcard1() {
            var sel_card1 = new Array();
            var cnt1;
            $('input[class=we_story_radio1]:checked').each(function() {
                var idVal1 = $(this).attr("value");
                cnt1 = sel_card1.push(idVal1);
                /*if(cnt1 > 4){
                    alert('최대 4개까지 선택할수 있습니다.');
                    $('input[id=multi_westory_card_url1_'+idVal1+']').prop("checked", false);
                    return;
                }*/
                $("#card_short_url").val(sel_card1.join(","));
            });
        }

        function state_check() {
            $("#auto_memiam_modal").modal('show');
        }

        function reg_msg() {
            var msg_title = $("#msgtitle").val();
            var msg_desc = $("#msgdesc").val();
            var sel_card = $("#sel_card").val();
            var myiam_link = $("#myiamlink").val();
            var button_txt = $(".dojang").val();
            var event_link = $("#event_link").val();
            var mem_id = '<?= $_SESSION['iam_member_id'] ?>';

            //added by amigo
            var reserv_id = $("#step_idx").val();
            var reserv_title = $("#reservation_title").val();
            var send_phonenum = $("#send_num").val();
            if ((msg_title == "") || (msg_desc == "")) {
                alert("제목/메시지 내용을 입력해 주세요.");
                return;
            }
            var formData = new FormData($("#dForm_auto")[0]);
            formData.append("autojoin_img", $("#autojoin_img")[0].files[0]);
            formData.append("msg_title", msg_title);
            formData.append("msg_desc", msg_desc);
            formData.append("sel_card", sel_card);
            formData.append("mem_id", mem_id);
            formData.append("myiam_link", myiam_link);
            formData.append("button_txt", button_txt);
            formData.append("event_link", event_link);
            formData.append("reserv_id", reserv_id);
            formData.append("reserv_title", reserv_title);
            formData.append("send_phonenum", send_phonenum);
            $.ajax({
                method: 'post',
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                url: "reg_automem.php",
                data: formData,
                success: function(data) {
                    arr = data.split("{");
                    data = "{" + arr[1];
                    res = JSON.parse(data);
                    short_url = res.shorturl;
                    $("#reg_msg_surl").val(res.shorturl);
                    $("#auto_making_preview").prop("disabled", false);
                    $("#auto_making_copy").prop("disabled", false);
                    alert('등록되었습니다.');
                }
            })
        }

        function form_save_call() {
            if ($('#call_title').val() == "") {
                alert('메시지 타이틀을 입력해주세요.');
                return;
            }
            if ($('#call_content').val() == "") {
                alert('메시지 콘텐츠를 입력해주세요.');
                return;
            }
            if ($('#msgtitle_call').val() == "" || $('#msgdesc_call').val() == "") {
                alert("제목/메시지 내용을 입력해 주세요.");
                return;
            }

            var formData = new FormData($("#dForm_call")[0]);

            $.ajax({
                method: 'post',
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                url: "/admin/ajax/mms_callback_save.php",
                data: formData,
                success: function(data) {
                    arr = data.split("{");
                    data = "{" + arr[1];
                    res = JSON.parse(data);
                    short_url = res.shorturl;
                    $("#call_msg_surl").val(res.shorturl);
                    alert('등록되었습니다.');
                }
            });
        }

        function form_save_daily() {
            if ($('#msgtitle_daily').val() == "" || $('#msgdesc_daily').val() == "") {
                alert("제목/메시지 내용을 입력해 주세요.");
                return;
            }
            if ($('#daily_cnt').val() == "") {
                alert("일발송량을 입력해 주세요.");
                return;
            }
            if ($('#daily_cnt').val() > 100) {
                alert('일발송량의 최대수는 100입니다.');
                return;
            }

            var formData = new FormData($("#dForm_daily")[0]);

            $.ajax({
                method: 'post',
                processData: false,
                contentType: false,
                cache: false,
                enctype: 'multipart/form-data',
                url: "daily_msg_save.php",
                data: formData,
                // dataType:"json",
                success: function(data) {
                    arr = data.split("{");
                    data = "{" + arr[1];
                    res = JSON.parse(data);
                    short_url = res.shorturl;
                    $("#daily_msg_surl").val(res.shorturl);
                    alert('등록되었습니다.');
                }
            });
        }

        function set_callback_state(obj) {
            var id = $(obj).find("input[type=checkbox]").val();
            var status = $(obj).find("input[type=checkbox]").is(":checked") == true ? "1" : "0";
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_callback.php",
                data: {
                    update_state: true,
                    id: id,
                    status: status
                },
                success: function(data) {
                    alert('신청되었습니다.');
                }
            })
        }
        $(function() {
            $('.auto_switch').on("change", function() {
                var id = $(this).find("input[type=checkbox]").val();
                $(this).closest('#contents_side1').find("input[id!=auto_stauts_" + id + "]").prop("checked", false);
                var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "1" : "0";
                var auto_service_id = $("#auto_service_id").val();
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event.php",
                    data: {
                        update_join_state: true,
                        id: id,
                        status: status,
                        mem_id: auto_service_id
                    },
                    success: function(data) {
                        alert('신청되었습니다.');
                    },
                    error: function(data) {
                        alert(data);
                    }
                })
            });
            $('.auto_switch_copy').on("change", function() {
                var id = $(this).find("input[type=checkbox]").val();
                $(this).closest('#contents_side1').find("input[id!=auto_stauts_" + id + "]").prop("checked", false);
                var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "1" : "0";
                var auto_service_id = $("#auto_service_id").val();
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event.php",
                    data: {
                        duplicate_msg: true,
                        id: id,
                        status: status,
                        mem_id: auto_service_id
                    },
                    success: function(data) {
                        alert('신청되었습니다.');
                    }
                })
            });
            $('.switch_callback_copy').on("change", function() {
                var id = $(this).find("input[type=checkbox]").val();
                $(this).closest('#contents_side1').find("input[id!=auto_stauts_" + id + "]").prop("checked", false);
                var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "1" : "0";
                var auto_service_id = $("#auto_service_id").val();
                var mms_id = $(this).find("input[name=mms_id]").val();
                $.ajax({
                    type: "POST",
                    url: "/admin/ajax/mms_callback_save.php",
                    data: {
                        mode: "duplicate_msg",
                        id: id,
                        status: status,
                        mem_id: auto_service_id,
                        mms_id: mms_id
                    },
                    success: function(data) {
                        alert('신청되었습니다.');
                        location.reload();
                    }
                })
            });
            $('.switch_daily_copy').on("change", function() {
                var id = $(this).find("input[type=checkbox]").val();
                $(this).closest('#contents_side1').find("input[id!=daily_stauts_" + id + "]").prop("checked", false);
                var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "1" : "0";
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event_daily.php",
                    data: {
                        mode: "duplicate_msg",
                        id: id,
                        status: status
                    },
                    success: function(data) {
                        alert('신청되었습니다.');
                        location.reload();
                    }
                })
            });
            $('.step_switch').on("change", function() {
                var id = $(this).find("input[type=checkbox]").val();
                var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "1" : "0";
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event.php",
                    data: {
                        update_state: true,
                        id: id,
                        status: status
                    },
                    success: function(data) {
                        alert('신청되었습니다.');
                    }
                })
            });
        });

        function newMessageEvent() { // test 메시지조회
            var win = window.open("../mypage_pop_message_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        }

        function copyurl() {
            console.log(short_url);
            url = short_url;
            var IE = (document.all) ? true : false;
            if (IE) {
                if (confirm("이 소스코드를 복사하시겠습니까?")) {
                    window.clipboardData.setData("Text", url);
                }
            } else {
                temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", url);
            }
        }

        function goback(val) {
            if (val == "settlment") {
                $('#settlement_modal').modal('hide');
                $('#auto_memiam_modal').modal('show');
            } else if (val == "making") {
                $('#auto_making_modal').modal('hide');
                $('#auto_memiam_modal').modal('show');
            } else if (val == "more") {
                $('#settlement_contents_modal').modal('hide');
                $('#auto_memiam_modal').modal('show');
            } else if (val == "share") {
                $('#sharepoint_modal').modal('hide');
                $('#auto_memiam_modal').modal('show');
            } else if (val == "auto_list") {
                $("#auto_list_edit_modal").modal('hide');
                $("#auto_list_modal").modal('show');
            } else if (val == "making_callback") {
                $("#callback_making_modal").modal('hide');
                $("#callback_msg_modal").modal('show');
            } else if (val == "callback_list") {
                $("#callback_list_edit_modal").modal('hide');
            } else if (val == "making_daily") {
                $('#daily_msg_modal').modal('show');
                $('#dailymsg_making_modal').modal('hide');
            } else if (val == "daily_list") {
                $('#daily_list_modal').modal('show');
                $('#daily_list_edit_modal').modal('hide');
            }
        }

        function show_req_mem(idx) {
            $("#daily_reqmem_list_modal").modal("show");
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_daily.php",
                dataType: "html",
                data: {
                    show_req_mem_admin: true,
                    event_idx: idx
                },
                success: function(data) {
                    $("#daily_reqmem_table").html(data);
                }
            })
        }

        function show_detail_daily(gd_id, event_idx, mem_id) {
            $("#daily_reqmem_detail_list_modal").modal("show");
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_daily.php",
                dataType: "html",
                data: {
                    show_req_mem_detail: true,
                    event_idx: event_idx,
                    gd_id: gd_id,
                    mem_id: mem_id
                },
                success: function(data) {
                    $("#daily_reqmem_detail_table").html(data);
                }
            })
        }

        function del_req_data(idx, mem_id) {
            console.log(idx, mem_id);
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_daily.php",
                dataType: "json",
                data: {
                    del_req_mem: true,
                    event_idx: idx,
                    mem_id: mem_id
                },
                success: function(data) {
                    alert('삭제되었습니다.');
                    location.reload();
                }
            })
        }

        function settlement(val, frm, auto_up) {
            if (val == 'set') {
                <?php if ($_SESSION['iam_member_id'] == "") { ?>
                    window.location = '/join.php';
                <? } else { ?>
                    $('#auto_memiam_modal').modal('hide');
                    $('#settlement_modal').modal('show');
                <? } ?>
            } else if (val == 'finish') {
                var memberType = "";
                var con_cnt = 0;
                var item_type = $('input[name=make_iam]:checked').val();
                var settlment_type = $('input[name=pay_type]:checked').val();
                var type_card = false;
                var type_bank = false;

                $("input[name=make_iam]:checked").each(function() {
                    var idVal = $(this).attr("id");
                    ele = $("label[for='" + idVal + "']").attr('value');
                });
                if (ele == 10000) {
                    memberType = '회원IAM 10건 만들기';
                    con_cnt = 10;
                } else if (ele == 47000) {
                    memberType = '회원IAM 50건 만들기';
                    con_cnt = 50;
                } else if (ele == 85000) {
                    memberType = '회원IAM 100건 만들기';
                    con_cnt = 100;
                } else if (ele == 140000) {
                    memberType = '회원IAM 200건 만들기';
                    con_cnt = 200;
                } else if (ele == 250000) {
                    memberType = '회원IAM 500건 만들기';
                    con_cnt = 500;
                }

                type_card = $("#card_type").prop('checked');
                type_bank = $("#bank_type").prop('checked');
                if (item_type == "" || item_type == undefined) {
                    alert('상품을 선택 하세요.');
                    return false;
                }
                if (settlment_type == "" || settlment_type == undefined) {
                    alert('결제종류를 선택 하세요.');
                    return false;
                }

                $('#month_cnt').val('12');
                $('#price').val(ele);
                $('#total_amount').text(ele);
                $('#onestep1').val("ON");
                $('#onestep2').val("OFF");
                console.log(ele);

                $('#member_type').val(memberType);
                $('#add_phone').val(con_cnt);
                $('#db_cnt').val(9000);
                if (!frm.mid.value) {
                    alert('결제종류를 선택해주세요.');
                    return false;
                }
                if (confirm('결제시작하시겠습니까?')) {
                    if (type_bank == true) {
                        $.ajax({
                            type: "POST",
                            url: "/pay_cash_people.php",
                            dataType: "json",
                            data: $('#pay_form').serialize(),
                            success: function(data) {
                                console.log("mutongjang!!!!!!");
                                if (data == 1) {
                                    iam_item('<?= $_SESSION['iam_member_id'] ?>', 'buy', 'bank');
                                }
                            }
                        });
                        return;
                    } else if (type_card == true) {
                        location.href = "/iam/pay_spoc.php?itemname=" + memberType + "&totprice=" + ele;
                    }
                }
            } else if (val == 'make') {
                <?php if ($_SESSION['iam_member_id'] == "") { ?>
                    window.location = '/iam/login.php';
                <? } else if ($Gn_point < $point_auto) { ?>
                    alert("포인트 잔액이 없습니다. 결제하신후에 사용하세요.");
                <? } else { ?>
                    $('#auto_memiam_modal').modal('hide');
                    $('#auto_making_modal').modal('show');
                <? } ?>
            } else if (val == 'callmake') {
                <?php if ($_SESSION['iam_member_id'] == "") { ?>
                    window.location = '/iam/login.php';
                <? } else if ($Gn_point < 300) { ?>
                    alert("포인트 잔액이 없습니다. 결제하신후에 사용하세요.");
                <? } else { ?>
                    $('#callback_msg_modal').modal('hide');
                    $('#callback_making_modal').modal('show');
                <? } ?>
            } else if (val == "daily") {
                $('#daily_msg_modal').modal('hide');
                $('#dailymsg_making_modal').modal('show');
            }
        }

        $(".sharepoint").on('click', function() {
            $('#auto_memiam_modal').modal('hide');
            $('#callback_msg_modal').modal('hide');
            $('#sharepoint_modal').modal('show');
        });

        //포인트 쉐어하기
        var current_point = 0;

        function start_sharing() {
            <?php
            if ($_SESSION['iam_member_id']) {
            ?>
                current_point = <?= $Gn_point ?>;
                current_cash = <?= $Gn_cash_point ?>;
            <? } ?>
            send_id = '<?= $_SESSION['iam_member_id']; ?>';
            receive_id = $("#share_id").val();
            var share_point = $("#share_point").val();
            var share_cash = $("#share_cash").val();
            if (share_point == '') share_point = 0;
            if (share_cash == '') share_cash = 0;
            if (send_id == '' || receive_id == '' || (share_point == 0 && share_cash == 0)) {
                alert("아이디와 포인트를 입력하세요.");
                return;
            }
            if (current_point < share_point) {
                alert("현재 보유씨드포인트는 <?= $Gn_point ?> P 입니다.");
                return;
            }
            if (current_cash < share_cash) {
                alert("현재 보유캐시포인트는 <?= $Gn_cash_point ?> P 입니다.");
                return;
            }
            $.ajax({
                type: "POST",
                url: "/iam/share_point.php",
                dataType: "json",
                data: {
                    send_id: send_id,
                    receive_id: receive_id,
                    share_point: share_point,
                    share_cash: share_cash
                },
                success: function(data) {
                    console.log(data);
                    if (data == 0) {
                        alert("잘못된 회원 아이디 입니다.");
                    } else if (data == 1) {
                        if (share_point && !share_cash)
                            alert(send_id + "의 계정에서 씨드포인트 " + share_point + "P 가 " + receive_id + " 계정으로 이전되었습니다.");
                        if (!share_point && share_cash)
                            alert(send_id + "의 계정에서 캐시포인트 " + share_cash + "P 가 " + receive_id + " 계정으로 이전되었습니다.");
                        if (share_point && share_cash)
                            alert(send_id + "의 계정에서 씨드포인트 " + share_point + "P, 캐시포인트 " + share_cash + "P 가 " + receive_id + " 계정으로 이전되었습니다.");
                        location.reload();
                    }
                }
            })
        }

        function iam_item(memid, action, type) {
            if (action == 'use') {
                console.log("use123123");
                $.ajax({
                    type: "POST",
                    url: "/iam/iam_item_mng.php",
                    dataType: "json",
                    data: {
                        use: true,
                        memid: memid
                    },
                    success: function(data) {}
                })
            } else {
                $.ajax({
                    type: "POST",
                    url: "/iam/iam_item_mng.php",
                    dataType: "json",
                    data: {
                        buy: true,
                        memid: memid
                    },
                    success: function(data) {
                        $('#settlement_modal').modal('hide');
                        if (type == "bank") {
                            $("#buy_point").text(ele);
                            $('#settlement_mutongjang_modal').modal('show');
                        } else {
                            $.ajax({
                                type: "POST",
                                url: "get_item_status.php",
                                dataType: "json",
                                data: {
                                    get: true,
                                    memid: '<?= $_SESSION['iam_member_id']; ?>',
                                    mem_type: '오토회원'
                                },
                                success: function(data1) {
                                    console.log(data1.name, data1.point);
                                    if (data1.payMethod == "CARD") {
                                        point = data1.point + '/' + data1.count + '건';
                                        $("#finish_name").text(data1.name);
                                        $("#finish_point").val(point);
                                        $('#settlement_finish_modal').modal('show');
                                    } else {

                                    }
                                }
                            });
                        }

                    }
                })
            }
        }

        function show_contents() {
            $('#auto_memiam_modal').modal('hide');
            $('#settlement_contents_modal').modal('show');
            search_people('search');
        }

        function search_people(val) {
            start = $("#search_start_date").val();
            end = $("#search_end_date").val();
            type = $("#use_buy_type").val();
            item_type = '오토회원';
            see = 'false';
            if (val == 'more') see = 'see_more';
            $.ajax({
                type: "POST",
                url: "/ajax/use_contents.php",
                dataType: "html",
                data: {
                    start: start,
                    end: end,
                    type: type,
                    item_type: item_type,
                    ID: '<?= $_SESSION['iam_member_id']; ?>',
                    more: see
                },
                success: function(data) {
                    $("#contents_side").html(data);
                }
            })
        }

        function show_msg_make() {
            $('#auto_list_modal').modal('hide');
            $('#auto_memiam_modal').modal('show');
        }

        function edit_ev(id) {
            $('#auto_list_modal').modal('hide');

            start = '';
            end = '';
            item_type = '';
            see = '';
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event.php",
                dataType: "json",
                data: {
                    edit_ev: true,
                    start: start,
                    end: end,
                    item_type: item_type,
                    ID: '<?= $_SESSION['iam_member_id']; ?>',
                    more: see,
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    $("#event_idx").val(data.id);
                    $("#event_title").val(data.event_title);
                    $("#event_desc").val(data.event_desc);
                    $("#btn_title").val(data.btn_title);
                    $("#btn_link").val(data.btn_link);
                    $("#short_url").val(data.short_url);
                    $("#event_req_link").val(data.event_link);
                    $("#read_cnt").val(data.read_cnt);
                    $("#autojoin_img_event").html(data.autojoin_img);
                    $("#regdate1").val(data.regdate);

                    if (data.reserv_sms_id != undefined) {
                        $("#step_title").val(data.step_title);
                        $("#step_phone").val(data.send_num);
                        if (data.step == null) {
                            $("#step_cnt").val("0회");
                        } else {
                            $("#step_cnt").val(data.step + "회");
                        }
                        $("#step_allow_state").val(data.step_idx);

                        if (data.step_allow_state == 1) {
                            $("#step_allow_state").prop("checked", true);
                        } else {
                            $("#step_allow_state").prop("checked", false);
                        }

                        var href = '<a class="reserv_btn" href="javascript:view_step_list(' + data.id + ', `update`)">리스트변경</a> <a class="reserv_btn" href="' + data.domain + '/mypage_reservation_create.php?sms_idx=' + data.reserv_sms_id + '" target="_blank" id="edit_step_info">내용수정</a>';
                        $("#step_info").html(href);
                    } else {
                        $("#step_title").val('');
                        $("#step_phone").val('');
                        $("#step_cnt").val('');
                        $("#step_allow_state").val('');
                        $("#step_allow_state").prop("checked", false);
                        var href = '<a class="reserv_btn" href="javascript:view_step_list(' + data.id + ', `insert`)">퍼널문자조회</a>';
                        $("#step_info").html(href);
                    }

                    $('input[class=we_story_radio1]').prop("checked", false);
                    card_short_url = data.card_short_url;
                    var pos = card_short_url.search(",");
                    if (pos == -1) {
                        $('input[id=multi_westory_card_url1_' + card_short_url + ']').prop("checked", true);
                    } else {
                        var arr = card_short_url.split(",");
                        for (var k = 0; k < arr.length; k++) {
                            $('input[id=multi_westory_card_url1_' + arr[k] + ']').prop("checked", true);
                        }
                    }
                    $('#auto_list_edit_modal').modal('show');
                }
            });

        }

        function view_step_list(idx, type) {
            var win = window.open("../mypage_pop_message_list_for_edit_autolist.php?event_idx=" + idx + "&type=" + type, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        }

        function save_edit_ev() {
            $("#dForm_edit").submit();
        }

        function delete_ev(id) {
            if (confirm("삭제하시겠습니까?")) {
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event.php",
                    dataType: "json",
                    data: {
                        del: true,
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        if (data == 1) {
                            alert('삭제 되었습니다.');
                            window.location.reload();
                        }
                    }
                })
            } else {
                return;
            }
        }

        function delete_ev_callback(id) {
            if (confirm("삭제하시겠습니까?")) {
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event_callback.php",
                    dataType: "json",
                    data: {
                        del: true,
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        if (data == 1) {
                            alert('삭제 되었습니다.');
                            window.location.reload();
                        }
                    }
                })
            } else {
                return;
            }
        }

        function delete_ev_daily(id) {
            if (confirm("삭제하시겠습니까?")) {
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event_daily.php",
                    dataType: "json",
                    data: {
                        del: true,
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        if (data == 1) {
                            alert('삭제 되었습니다.');
                            window.location.reload();
                        }
                    }
                })
            } else {
                return;
            }
        }

        function edit_ev_callback(id) {
            $('#callback_list_modal').modal('hide');

            start = '';
            end = '';
            item_type = '';
            see = '';
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_callback.php",
                dataType: "json",
                data: {
                    edit_ev: true,
                    start: start,
                    end: end,
                    item_type: item_type,
                    ID: '<?= $_SESSION['iam_member_id']; ?>',
                    more: see,
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    $("#call_event_idx").val(data.id);
                    $("#call_event_title").val(data.event_info);
                    $("#call_event_desc").val(data.event_sms_desc);
                    $("#call_img_event").html(data.img);
                    $("#call_iam_link").val(data.iam_link);
                    $("#call_short_url").val(data.short_url);
                    $("#call_read_cnt").val(data.read_cnt);
                    $("#call_regdate1").val(data.regdate);
                    $("#event_desc_call").val(data.event_desc_call);
                    $("#event_title_call").val(data.event_title_call);
                    if (data.allow_state == 1) {
                        $("#allow").prop("checked", true);
                        $("#notallow").prop("checked", false);
                    } else {
                        $("#allow").prop("checked", false);
                        $("#notallow").prop("checked", true);
                    }

                    $('#callback_list_edit_modal').modal('show');
                }
            });

        }

        function edit_ev_daily(id) {
            $('#daily_list_modal').modal('hide');

            start = '';
            end = '';
            item_type = '';
            see = '';
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_daily.php",
                dataType: "json",
                data: {
                    edit_ev: true,
                    start: start,
                    end: end,
                    item_type: item_type,
                    ID: '<?= $_SESSION['iam_member_id']; ?>',
                    more: see,
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    $("#daily_event_idx").val(data.id);
                    $("#daily_event_title_intro").val(data.event_title_daily_intro);
                    $("#daily_event_desc_intro").val(data.event_desc_daily_intro);
                    $("#daily_event_title").val(data.msg_title_daily);
                    $("#daily_event_desc").val(data.msg_desc_daily);
                    $("#daily_img_event").html(data.img);
                    $("#daily_short_url").val(data.short_url);
                    $("#daily_read_cnt").val(data.read_cnt);
                    $("#daily_regdate1").val(data.regdate);
                    $("#daily_send_cnt").val(data.send_cnt);
                    $("#daily_req_link").val(data.daily_req_link);
                    $("select[name=htime]").val(data.htime);
                    $("select[name=mtime]").val(data.mtime);

                    $('#daily_list_edit_modal').modal('show');
                }
            });

        }

        function save_call_edit_ev() {
            $("#dForm_call_edit").submit();
        }

        function save_daily_edit_ev() {
            $("#dForm_daily_edit").submit();
        }

        function show_more(str) {
            $("#contents_detail").html(str);
            $("#show_detail_more").modal("show");
        }

        function search_auto() {
            start = $("#search_start_date1").val();
            end = $("#search_end_date1").val();

            $.ajax({
                type: "POST",
                url: "/ajax/edit_event.php",
                dataType: "html",
                data: {
                    search: true,
                    start: start,
                    end: end,
                    ID: '<?= $_SESSION['iam_member_id']; ?>'
                },
                success: function(data) {
                    console.log(data);
                    // return;
                    $("#contents_side1").html(data);
                }
            })
        }

        function newpop(str) {
            window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        }

        function copy(shortlink) {
            if (shortlink == undefined) {
                if ($("#reg_msg_surl").val() != "") {
                    shortlink = $("#reg_msg_surl").val();
                }
                if ($("#call_msg_surl").val() != "") {
                    shortlink = $("#call_msg_surl").val();
                }
                if ($("#daily_msg_surl").val() != "") {
                    shortlink = $("#daily_msg_surl").val();
                }
            }
            // 글을 쓸 수 있는 란을 만든다.
            var aux = document.createElement("input");
            // 지정된 요소의 값을 할당 한다.
            aux.setAttribute("value", shortlink);
            // bdy에 추가한다.
            document.body.appendChild(aux);
            // 지정된 내용을 강조한다.
            aux.select();
            // 텍스트를 카피 하는 변수를 생성
            document.execCommand("copy");
            // body 로 부터 다시 반환 한다.
            document.body.removeChild(aux);
            alert("URL이 복사되었습니다. 원하는 곳에 붙여 넣으세요.");
        }

        function show_mem_info(val, str) {
            if (val == "select") {
                $("#mem_selected").text(str);
                $("#show_select_member").modal("show");
            } else {
                $("#mem_unselecte").text(str);
                $("#show_unselect_member").modal("show");
            }
        }

        function app_set_list(val, type) {
            if (type == "callback") {
                $("#send_type_call").val(val);
                if (val == "self") {
                    $("#send_type_title_call").html("셀프폰으로 발송하기");
                } else {
                    $("#send_type_title_call").html("푸시형으로 전송하기");
                }
                $("#app_sets_member_call").show();
            } else {
                $("#send_type_daily").val(val);
                if (val == "self") {
                    $("#send_type_title_daily").html("셀프폰으로 발송하기");
                } else {
                    $("#send_type_title_daily").html("푸시형으로 전송하기");
                }
                $("#app_sets_member_daily").show();
            }
            var win = window.open("/iam/set_apps_list.php?type=" + type, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=900");
        }

        function cancel_app_list(type) {
            if (type == "callback") {
                $("#app_sets_member_call").hide();
            } else {
                $("#app_sets_member_daily").hide();
            }
        }

        function send_msg_applist(type) {
            if (type == "callback") {
                var val = $("#send_type_call").val();
                var member_ids = $("#app_set_mbs_id_call").val();
                var message = $("#event_desc_call").val();
                var title = $("#event_title_call").val();
                var notice_link = $("#call_short_url").val();
            } else {
                var val = $("#send_type_daily").val();
                var member_ids = $("#app_set_mbs_id_daily").val();
                var message = $("#daily_event_desc_intro").val();
                var title = $("#daily_event_title_intro").val();
                var notice_link = $("#daily_short_url").val();
            }

            if (val == "self") {
                $.ajax({
                    type: "POST",
                    url: "/iam/card_con_send.php",
                    data: {
                        settle_type: "self_phone_send",
                        payMethod: member_ids,
                        message: message,
                        title: title,
                        notice_link: notice_link
                    },
                    dataType: 'json',
                    success: function(data) {
                        alert("전송되었습니다.");
                        location.reload();
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "/iam/card_con_send.php",
                    data: {
                        settle_type: "notice_send",
                        payMethod: member_ids,
                        pay_percent: 90,
                        allat_order_no: <?= $mid ?>,
                        point_val: 3,
                        message: message,
                        title: title,
                        notice_link: notice_link
                    },
                    dataType: 'json',
                    success: function(data) {
                        alert("전송되었습니다.");
                        location.reload();
                    }
                });
            }
        }

        $("#app_set_mbs_id_call").keyup(function() {
            point = $(this).val();
            var arr = point.split(",");
            cnt = arr.length;
            if (point.indexOf(",") == -1 && point == "") {
                cnt = 0;
            }
            $("#app_set_mbs_count_call").val(cnt + "건");
        });

        $("#app_set_mbs_id_daily").keyup(function() {
            point = $(this).val();
            var arr = point.split(",");
            cnt = arr.length;
            if (point.indexOf(",") == -1 && point == "") {
                cnt = 0;
            }
            $("#app_set_mbs_count_daily").val(cnt + "건");
        });

        function automaking_preview() {
            window.open($("#reg_msg_surl").val());
        }
    </script>
</body>
<?
$logs->add_log("End");
$logs->write_to_file();
?>

</html>