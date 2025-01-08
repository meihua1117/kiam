<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
if ($HTTP_HOST != "kiam.kr") {
    $query = "select * from Gn_Iam_Service where sub_domain = 'http://" . $HTTP_HOST . "'";
    $res = mysqli_query($self_con, $query);
    $domainData = mysqli_fetch_array($res);
    if ($domainData['mem_id'] != $_SESSION['iam_member_id']) {
        echo "<script>location='/';</script>";
        exit;
    }
    $parse = parse_url($domainData['sub_domain']);
    $site = explode(".", $parse['host']);
} else {
    $query = "select * from Gn_Iam_Service where sub_domain = 'http://www.kiam.kr'";
    $res = mysqli_query($self_con, $query);
    $domainData = mysqli_fetch_array($res);
    if ($domainData['mem_id'] != $_SESSION['iam_member_id']) {
        echo "<script>location='/';</script>";
        exit;
    }
    $site = explode(".", "kiam.kr");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>아이엠카드 관리자</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin/dist/css/AdminLTE.min_iam.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
            folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/admin/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/admin/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/admin/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/admin/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/admin/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <!-- jQuery 2.1.4 -->
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
    <script>
        function save_reg_cust() {
            var action_mode = $("#reg_cust_mode").val();
            if (action_mode == "creat") {
                var mode = "creat";
                var msg = "저장 하시겠습니까?";
                var idx = 0;
            } else {
                var mode = "edit";
                var msg = "수정 하시겠습니까?";
                var idx = $("#edit_reg_idx").val();
                var list_idx = $("#list_reg_idx").val();
            }

            var mem_id = '<?= $_SESSION['iam_member_id'] ?>';
            var name = $("#reg_name").val();
            var phone1 = $("#reg_phone1").val();
            var phone2 = $("#reg_phone2").val();
            var email = $("#reg_email").val();
            var birthday = $("#reg_birthday").val();
            var work_type = $("#reg_work_type").val();
            var company_name = $("#reg_company_name").val();
            var job = $("#reg_job").val();
            var company_addr = $("#reg_company_addr").val();
            var home_addr = $("#reg_home_addr").val();
            var link = $("#reg_link").val();
            var memo = $("#reg_memo").val();

            if (confirm(msg)) {
                $.ajax({
                    type: "POST",
                    url: "/admin/ajax/manage_customers.php",
                    data: {
                        mode: mode,
                        type: action_mode,
                        idx: idx,
                        mem_id: mem_id,
                        name: name,
                        phone1: phone1,
                        phone2: phone2,
                        email: email,
                        birthday: birthday,
                        work_type: work_type,
                        company_name: company_name,
                        job: job,
                        company_addr: company_addr,
                        home_addr: home_addr,
                        link: link,
                        memo: memo,
                        list_idx: list_idx
                    },
                    success: function() {
                        if (action_mode == "creat") {
                            alert('저장되었습니다.');
                        } else {
                            alert('수정되었습니다.');
                        }
                        location.reload();
                    },
                    error: function() {
                        alert('등록목록 수정 실패');
                    }
                });
            }
        }

        function save_req_cust() {
            var action_mode = $("#req_cust_mode").val();
            var idx = $("#edit_req_idx").val();
            var mode = "edit";
            var msg = "수정 하시겠습니까?";
            var list_idx = $("#list_req_idx").val();

            var mem_id = '<?= $_SESSION['iam_member_id'] ?>';
            var name = $("#req_name").val();
            var phone1 = $("#req_phone1").val();
            var phone2 = $("#req_phone2").val();
            var email = $("#req_email").val();
            var birthday = $("#req_birthday").val();
            var work_type = $("#req_work_type").val();
            var company_name = $("#req_company_name").val();
            var job = $("#req_job").val();
            var company_addr = $("#req_company_addr").val();
            var home_addr = $("#req_home_addr").val();
            var link = $("#req_link").val();
            var memo = $("#req_memo").val();

            if (confirm(msg)) {
                $.ajax({
                    type: "POST",
                    url: "/admin/ajax/manage_customers.php",
                    data: {
                        mode: mode,
                        type: action_mode,
                        idx: idx,
                        mem_id: mem_id,
                        name: name,
                        phone1: phone1,
                        phone2: phone2,
                        email: email,
                        birthday: birthday,
                        work_type: work_type,
                        company_name: company_name,
                        job: job,
                        company_addr: company_addr,
                        home_addr: home_addr,
                        link: link,
                        memo: memo,
                        list_idx: list_idx
                    },
                    success: function(data) {
                        alert('수정되었습니다.');
                        location.reload();
                    },
                    error: function() {
                        alert('신청목록 수정 실패');
                    }
                });
            }
        }

        function save_get_cust() {
            var action_mode = $("#get_cust_mode").val();
            var idx = $("#edit_get_idx").val();
            var mode = "edit";
            var msg = "수정 하시겠습니까?";
            var list_idx = $("#list_get_idx").val();

            var mem_id = '<?= $_SESSION['iam_member_id'] ?>';
            var name = $("#get_name").val();
            var phone1 = $("#get_phone1").val();
            var phone2 = $("#get_phone2").val();
            var email = $("#get_email").val();
            var birthday = $("#get_birthday").val();
            var work_type = $("#get_work_type").val();
            var company_name = $("#get_company_name").val();
            var job = $("#get_job").val();
            var company_addr = $("#get_company_addr").val();
            var home_addr = $("#get_home_addr").val();
            var link = $("#get_link").val();
            var memo = $("#get_memo").val();

            if (confirm(msg)) {
                $.ajax({
                    type: "POST",
                    url: "/admin/ajax/manage_customers.php",
                    data: {
                        mode: mode,
                        type: action_mode,
                        idx: idx,
                        mem_id: mem_id,
                        name: name,
                        phone1: phone1,
                        phone2: phone2,
                        email: email,
                        birthday: birthday,
                        work_type: work_type,
                        company_name: company_name,
                        job: job,
                        company_addr: company_addr,
                        home_addr: home_addr,
                        link: link,
                        memo: memo,
                        list_idx: list_idx
                    },
                    success: function(data) {
                        alert('수정되었습니다.');
                        location.reload();
                    },
                    error: function() {
                        alert('수집목록 수정 실패');
                    }
                });
            }
        }

        function save_paper_cust() {
            var action_mode = $("#paper_cust_mode").val();
            var idx = $("#edit_paper_idx").val();
            var mode = "edit";
            var msg = "수정 하시겠습니까?";
            var list_idx = $("#list_paper_idx").val();

            var mem_id = '<?= $_SESSION['iam_member_id'] ?>';
            var name = $("#paper_name").val();
            var job = $("#paper_job").val();
            var addr = $("#paper_addr").val();
            var org_name = $("#paper_org").val();
            var phone1 = $("#paper_phone1").val();
            var phone2 = $("#paper_phone2").val();
            var mobile = $("#paper_mobile").val();
            var fax = $("#paper_fax").val();
            var email1 = $("#paper_email1").val();
            var email2 = $("#paper_email2").val();
            var memo = $("#paper_memo").val();

            if (confirm(msg)) {
                $.ajax({
                    type: "POST",
                    url: "/admin/ajax/manage_customers.php",
                    data: {
                        mode: mode,
                        type: action_mode,
                        idx: idx,
                        mem_id: mem_id,
                        name: name,
                        job: job,
                        addr: addr,
                        org_name: org_name,
                        phone1: phone1,
                        phone2: phone2,
                        mobile: mobile,
                        fax: fax,
                        email1: email1,
                        email2: email2,
                        memo: memo
                    },
                    success: function(data) {
                        alert('수정되었습니다.');
                        location.reload();
                    },
                    error: function() {
                        alert('명함고객 수정 실패');
                    }
                });
            }
        }

        function show_memo(memo) {
            $("#mem_memo").html(memo);
            $("#mem_memo_modal").modal("show");
        }

        function show_edit_input(val, type, list_id = 0) {
            if (type == "creat") {
                $("#reg_cust_mode").val("creat");
                $("#edit_table_reg").show();
            } else {
                if (type == "reg_cust_edit" || type == "member_reg_edit") {
                    $("#reg_cust_mode").val(type);
                    $("#edit_reg_idx").val(val);
                    $("#list_reg_idx").val(list_id);
                } else if (type == "req_cust_edit" || type == "member_req_edit") {
                    $("#req_cust_mode").val(type);
                    $("#edit_req_idx").val(val);
                    $("#list_req_idx").val(list_id);
                } else if (type == "get_cust_edit" || type == "member_get_edit") {
                    $("#get_cust_mode").val(type);
                    $("#edit_get_idx").val(val);
                    $("#list_get_idx").val(list_id);
                } else if (type == "paper_edit") {
                    $("#paper_cust_mode").val(type);
                    $("#edit_paper_idx").val(val);
                    $("#list_paper_idx").val(list_id);
                }

                $.ajax({
                    type: "POST",
                    url: "/admin/ajax/manage_customers.php",
                    dataType: 'json',
                    data: {
                        mode: "edit_table",
                        type: type,
                        idx: val
                    },
                    success: function(data) {
                        if (type == "reg_cust_edit" || type == "member_reg_edit") {
                            $("#reg_name").val(data.reg_name);
                            $("#reg_phone1").val(data.reg_phone1);
                            $("#reg_phone2").val(data.reg_phone2);
                            $("#reg_email").val(data.reg_email);
                            $("#reg_birthday").val(data.reg_birthday);
                            $("#reg_work_type").val(data.reg_work_type);
                            $("#reg_company_name").val(data.reg_company_name);
                            $("#reg_job").val(data.reg_job);
                            $("#reg_company_addr").val(data.reg_company_addr);
                            $("#reg_home_addr").val(data.reg_home_addr);
                            $("#reg_link").val(data.reg_link);
                            $("#reg_memo").val(data.reg_memo);
                            $("#edit_table_reg").show();
                        } else if (type == "req_cust_edit" || type == "member_req_edit") {
                            $("#req_name").val(data.req_name);
                            $("#req_phone1").val(data.req_phone1);
                            $("#req_phone2").val(data.req_phone2);
                            $("#req_email").val(data.req_email);
                            $("#req_birthday").val(data.req_birthday);
                            $("#req_work_type").val(data.req_work_type);
                            $("#req_company_name").val(data.req_company_name);
                            $("#req_job").val(data.req_job);
                            $("#req_company_addr").val(data.req_company_addr);
                            $("#req_home_addr").val(data.req_home_addr);
                            $("#req_link").val(data.req_link);
                            $("#req_memo").val(data.req_memo);
                            $("#edit_table_req").show();
                        } else if (type == "get_cust_edit" || type == "member_get_edit") {
                            $("#get_name").val(data.get_name);
                            $("#get_phone1").val(data.get_phone1);
                            $("#get_phone2").val(data.get_phone2);
                            $("#get_email").val(data.get_email);
                            $("#get_birthday").val(data.get_birthday);
                            $("#get_work_type").val(data.get_work_type);
                            $("#get_company_name").val(data.get_company_name);
                            $("#get_job").val(data.get_job);
                            $("#get_company_addr").val(data.get_company_addr);
                            $("#get_home_addr").val(data.get_home_addr);
                            $("#get_link").val(data.get_link);
                            $("#get_memo").val(data.get_memo);
                            $("#edit_table_get").show();
                        } else if (type == "get_cust_edit" || type == "member_get_edit") {
                            $("#get_name").val(data.get_name);
                            $("#get_phone1").val(data.get_phone1);
                            $("#get_phone2").val(data.get_phone2);
                            $("#get_email").val(data.get_email);
                            $("#get_birthday").val(data.get_birthday);
                            $("#get_work_type").val(data.get_work_type);
                            $("#get_company_name").val(data.get_company_name);
                            $("#get_job").val(data.get_job);
                            $("#get_company_addr").val(data.get_company_addr);
                            $("#get_home_addr").val(data.get_home_addr);
                            $("#get_link").val(data.get_link);
                            $("#get_memo").val(data.get_memo);
                            $("#edit_table_get").show();
                        } else if (type == "paper_edit") {
                            $("#paper_name").val(data.name);
                            $("#paper_job").val(data.job);
                            $("#paper_org").val(data.org_name);
                            $("#paper_addr").val(data.address);
                            $("#paper_phone1").val(data.phone1);
                            $("#paper_phone2").val(data.phone2);
                            $("#paper_mobile").val(data.mobile);
                            $("#paper_fax").val(data.fax);
                            $("#paper_email1").val(data.email1);
                            $("#paper_email2").val(data.email2);
                            $("#paper_memo").val(data._memo);
                            $("#edit_table_paper").show();
                        }
                    },
                    error: function() {
                        alert('가져오기 실패');
                    }
                });
            }
        }

        function move_reg_list(val, type) {
            var msg = "이동 하시겠습니까?";

            if (confirm(msg)) {
                $.ajax({
                    type: "POST",
                    url: "/admin/ajax/manage_customers.php",
                    data: {
                        mode: 'move',
                        type: type,
                        idx: val
                    },
                    success: function(data) {
                        alert('이동되었습니다.');
                        location.reload();
                    },
                    error: function() {
                        alert('이동 실패');
                    }
                });
            }
        }

        function cancel_reg_cust(type) {
            if (type == "reg_cust") {
                $("#edit_table_reg").hide();
            } else if (type == "req_cust") {
                $("#edit_table_req").hide();
            } else if (type == "get_cust") {
                $("#edit_table_get").hide();
            } else if (type == "paper_cust") {
                $("#edit_table_paper").hide();
            }
            location.reload();
        }

        function show_tab(val) {
            var pgNum = 1;
            location.href = '?&nowPage_' + val + '=' + pgNum + "&search_key=<?= $search_key ?>&sel_tab=" + val;
        }
        $(function() {
            var contHeaderH = $(".main-header").height();
            var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - $("#list_paginate").height() - 186;
            if (height < 375)
                height = 375;
            $(".box-body").css("height", height);
            $(document).ajaxStart(function() {
                    $("#ajax-loading").show();
                })
                .ajaxStop(function() {
                    $("#ajax-loading").delay(10).hide(1);
                });
        });
    </script>
    <style>
        .loading_div {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            display: none;
            z-index: 1000;
        }

        .edit_input {
            width: 70px;
        }

        .text-center {
            margin: 0px !important;
            overflow: hidden !important;
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

        input,
        select,
        textarea {
            vertical-align: middle;
            border: 1px solid #CCC;
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
    </style>
    <div class="loading_div"><img src="/images/ajax-loader.gif"></div>
    <div class="wrapper">
        <? include "header.php"; ?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="margin-left: 0px !important;background:#fff">
            <!-- Content Header (Page header) -->
            <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
                <input type="hidden" name="one_id" id="one_id" value="" />
                <input type="hidden" name="mem_pass" id="mem_pass" value="" />
                <input type="hidden" name="mem_code" id="mem_code" value="" />
            </form>

            <!-- Main content -->
            <? if ($mem_id != "") { ?>
                <div class="row text-center">
                    <section class="content-header">
                        <h1><?= $user['mem_name']; ?> 회원</h1>
                    </section>
                </div><!-- row end-->
            <? } ?>
            <br>
            <?
            $btn_hide = "hidden";
            $hide1 = $hide2 = $hide3 = $hide4 = "hidden";
            $color1 = $color2 = $color3 = $color4 = "background-color:#a19d9d;";
            if ($_REQUEST['sel_tab'] == "reg_cust" || $_REQUEST['sel_tab'] == '') {
                $hide1 = "";
                $btn_hide = "";
                $color1 = "background-color:#3c8dbc;";
            }

            if ($_REQUEST['sel_tab'] == "req_cust") {
                $hide2 = "";
                $color2 = "background-color:#3c8dbc;";
            }

            if ($_REQUEST['sel_tab'] == "get_cust") {
                $hide3 = "";
                $color3 = "background-color:#3c8dbc;";
            }

            if ($_REQUEST['sel_tab'] == "paper_cust") {
                $hide4 = "";
                $color4 = "background-color:#3c8dbc;";
            }
            ?>
            <div class="row text-center">
                <section class="content-header">
                    <h1>고객 정보보기</h1>
                    <input type="button" id="create_reg_cust" style="position: absolute;right: 20px;font-size: 11px;top: 20px;background-color: #99cc00;color: white;" value="고객등록하기" onclick="show_edit_input('', 'creat');" <?= $btn_hide ?>>
                </section>
            </div>

            <div style="border-top: 3px solid #d2d6de;">
                <input type="button" id="reg_btn" style="font-size: 11px;color: white;<?= $color1 ?>" value="등록고객" onclick="show_tab('reg_cust');">
                <input type="button" id="get_btn" style="font-size: 11px;color: white;<?= $color3 ?>" value="수집고객" onclick="show_tab('get_cust');">
                <input type="button" id="req_btn" style="font-size: 11px;color: white;<?= $color2 ?>" value="신청고객" onclick="show_tab('req_cust');">
                <input type="button" id="req_btn" style="font-size: 11px;color: white;<?= $color4 ?>" value="명함고객" onclick="show_tab('paper_cust');">
            </div>
            <? if ($_REQUEST['sel_tab'] == "reg_cust" || $_REQUEST['sel_tab'] == '') { ?>
                <div id="reg_cu_list" <?= $hide1 ?>>
                    <div class="box" style="border-top:none;">
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped" style="width:100%;font-size:12px;">
                                <colgroup>
                                    <col width="60px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="60px">
                                    <col width="100px">
                                    <col width="70px">
                                    <col width="70px">
                                    <col width="50px">
                                    <col width="120px">
                                    <col width="120px">
                                    <col width="120px">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>아이디</th>
                                        <th>성명</th>
                                        <th>휴대폰</th>
                                        <th>일반폰</th>
                                        <th>이메일</th>
                                        <th>생년월일</th>
                                        <th>업종</th>
                                        <th>업체명</th>
                                        <th>직책</th>
                                        <th>업체주소</th>
                                        <th>자택주소</th>
                                        <th>링크</th>
                                        <th>등록일시</th>
                                        <th>메모</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="edit_table_reg" hidden>
                                        <td></td>
                                        <td></td>
                                        <td><input type="text" name="reg_name" id="reg_name" class="edit_input" placeholder="성명"></td>
                                        <td><input type="text" name="reg_phone1" id="reg_phone1" class="edit_input" placeholder="휴대폰"></td>
                                        <td><input type="text" name="reg_phone2" id="reg_phone2" class="edit_input" placeholder="일반폰"></td>
                                        <td><input type="text" name="reg_email" id="reg_email" class="edit_input" placeholder="이메일"></td>
                                        <td><input type="text" name="reg_birthday" id="reg_birthday" class="edit_input" placeholder="생년월일"></td>
                                        <td><input type="text" name="reg_work_type" id="reg_work_type" class="edit_input" placeholder="업종"></td>
                                        <td><input type="text" name="reg_company_name" id="reg_company_name" class="edit_input" placeholder="업체명"></td>
                                        <td><input type="text" name="reg_job" id="reg_job" class="edit_input" placeholder="직책"></td>
                                        <td><input type="text" name="reg_company_addr" id="reg_company_addr" class="edit_input" placeholder="업체주소"></td>
                                        <td><input type="text" name="reg_home_addr" id="reg_home_addr" class="edit_input" placeholder="자택주소"></td>
                                        <td><input type="text" name="reg_link" id="reg_link" class="edit_input" placeholder="링크"></td>
                                        <td></td>
                                        <td style="text-align:center">
                                            <input type="text" name="reg_memo" id="reg_memo" class="edit_input" placeholder="메모"><br>
                                            <input type="hidden" name="reg_cust_mode" id="reg_cust_mode">
                                            <input type="hidden" name="edit_reg_idx" id="edit_reg_idx">
                                            <input type="hidden" name="list_reg_idx" id="list_reg_idx">
                                            <input type="button" style="font-size: 11px;background-color: #99cc00;color: white;" value="저장" onclick="save_reg_cust()">
                                            <input type="button" style="font-size: 11px;background-color: #99cc00;color: white;" value="취소" onclick="cancel_reg_cust('reg_cust')">
                                            </th>
                                    </tr>
                                    <?
                                    $nowPage1 = $_REQUEST['nowPage_reg_cust'] ? $_REQUEST['nowPage_reg_cust'] : 1;
                                    $startPage1 = $nowPage1 ? $nowPage1 : 1;
                                    $pageCnt1 = 10;

                                    // 검색 조건을 적용한다.
                                    $searchStr1 .= $search_key ? " AND (a.company_name LIKE '%" . $search_key . "%' or a.phone1 like '%" . $search_key . "%' or a.name like '%" . $search_key . "%' or a.work_type like '%" . $search_key . "%' or a.company_name like '%" . $search_key . "%' or a.job like '%" . $search_key . "%' or a.company_addr like '%" . $search_key . "%' or a.home_addr like '%" . $search_key . "%' )" : null;
                                    $order1 = $order1 ? $order1 : "desc";
                                    $query1 = "SELECT count(a.id) FROM gn_reg_customer a WHERE 1=1 and mem_id='{$_SESSION['iam_member_id']}' $searchStr1";
                                    $res1        = mysqli_query($self_con, $query1);
                                    $row1    =  mysqli_fetch_array($res1);
                                    $totalCnt1 = $row1[0];

                                    $limitStr1  = " LIMIT " . (($startPage1 - 1) * $pageCnt1) . ", " . $pageCnt1;
                                    $number1    = $totalCnt1 - ($nowPage1 - 1) * $pageCnt1;
                                    $query1 = "SELECT SQL_CALC_FOUND_ROWS a.* FROM gn_reg_customer a WHERE 1=1 and mem_id='{$_SESSION['iam_member_id']}' $searchStr1";
                                    $orderQuery1 .= " ORDER BY reg_date DESC $limitStr1";

                                    $i = 1;
                                    $query1 .= $orderQuery1;
                                    $res = mysqli_query($self_con, $query1);
                                    while ($row = mysqli_fetch_array($res)) {
                                        $sql_mem_reg = "select * from Gn_Member where mem_name='{$row['name']}' and mem_phone='{$row['phone1']}' and is_leave='N' limit 1";
                                        $res_mem_reg = mysqli_query($self_con, $sql_mem_reg);
                                        $row_mem_reg = mysqli_fetch_array($res_mem_reg);
                                        if ($row_mem_reg['mem_code'] != '') {
                                            if ($row_mem_reg['mem_memo'] != '') {
                                                $style1 = "color:#99cc00";
                                            } else {
                                                $style1 = "color:black";
                                            }

                                            $mem_name = $row_mem_reg['mem_name'] ? $row_mem_reg['mem_name'] : '성명';
                                            $mem_phone = $row_mem_reg['mem_phone'] ? $row_mem_reg['mem_phone'] : '휴대폰';
                                            $mem_phone1 = $row_mem_reg['mem_phone1'] ? $row_mem_reg['mem_phone1'] : '일반폰';
                                            $mem_email = $row_mem_reg['mem_email'] ? $row_mem_reg['mem_email'] : '이메일';
                                            $mem_birth = $row_mem_reg['mem_birth'] ? $row_mem_reg['mem_birth'] : '생년월일';
                                            $com_type = $row_mem_reg['com_type'] ? $row_mem_reg['com_type'] : '업종';
                                            $zy = $row_mem_reg['zy'] ? $row_mem_reg['zy'] : '업체명';
                                            $mem_leb = $row_mem_reg['mem_job'] ? $row_mem_reg['mem_job'] : '직책';
                                            $com_add1 = $row_mem_reg['com_add1'] ? $row_mem_reg['com_add1'] : '업체주소';
                                            $mem_add1 = $row_mem_reg['mem_add1'] ? $row_mem_reg['mem_add1'] : '자택주소';

                                            $query = "select card_short_url from Gn_Iam_Name_Card where group_id = 0 and mem_id = '{$row_mem_reg['mem_id']}' order by req_data asc";
                                            $cres = mysqli_query($self_con, $query);
                                            $crow = mysqli_fetch_array($cres);
                                            $card_url = $crow[0]; ?>
                                            <tr>
                                                <td style="text-align:center;"> <?= $number1-- ?> <br> <a href="javascript:show_edit_input('<?= $row['id'] ?>', 'member_reg_edit', '<?= $row_mem_reg['mem_code'] ?>')"><img src="/iam/img/Picture_iama1.png"></a></td>
                                                <td><?= $row_mem_reg['mem_id'] ?></td>
                                                <td><?= $mem_name ?></td>
                                                <td><?= $mem_phone ?></td>
                                                <td><?= $mem_phone1 ?></td>
                                                <td><?= $mem_email ?></td>
                                                <td><?= $mem_birth ?></td>
                                                <td><?= $com_type ?></td>
                                                <td><?= $zy ?></td>
                                                <td><?= $mem_leb ?></td>
                                                <td><?= $com_add1 ?></td>
                                                <td><?= $mem_add1 ?></td>
                                                <td><a href="card_list.php?mem_id=<?= $row_mem_reg['mem_id'] ?>"><?= $card_url ?></a></td>
                                                <td><?= $row_mem_reg['first_regist'] ?></td>
                                                <td><a href="javascript:show_memo('<?= $row_mem_reg['mem_memo'] ?>');" style="<?= $style1 ?>">메모</a></td>
                                            </tr>
                                        <? } else {
                                            if ($row['memo'] != '') {
                                                $style1 = "color:#99cc00";
                                            } else {
                                                $style1 = "color:black";
                                            }
                                            $name = $row['name'] ? $row['name'] : '성명';
                                            $phone1 = $row['phone1'] ? $row['phone1'] : '휴대폰';
                                            $phone2 = $row['phone2'] ? $row['phone2'] : '일반폰';
                                            $email = $row['email'] ? $row['email'] : '이메일';
                                            $birthday = $row['birthday'] ? $row['birthday'] : '생년월일';
                                            $work_type = $row['work_type'] ? $row['work_type'] : '업종';
                                            $company_name = $row['company_name'] ? $row['company_name'] : '업체명';
                                            $job = $row['job'] ? $row['job'] : '직책';
                                            $company_addr = $row['company_addr'] ? $row['company_addr'] : '업체주소';
                                            $home_addr = $row['home_addr'] ? $row['home_addr'] : '자택주소';
                                            $link = $row['link'] ? $row['link'] : '링크';
                                            // $memo = $row['memo']?$row['memo']:'메모';
                                        ?>
                                            <tr>
                                                <td style="text-align:center;"> <?= $number1-- ?> <br> <a href="javascript:show_edit_input('<?= $row['id'] ?>', 'reg_cust_edit')"><img src="/iam/img/Picture_iama1.png" style="width:20px;"></a></td>
                                                <td></td>
                                                <td><?= $name ?></td>
                                                <td><?= $phone1 ?></td>
                                                <td><?= $phone2 ?></td>
                                                <td><?= $email ?></td>
                                                <td><?= $birthday ?></td>
                                                <td><?= $work_type ?></td>
                                                <td><?= $company_name ?></td>
                                                <td><?= $job ?></td>
                                                <td><?= $company_addr ?></td>
                                                <td><?= $home_addr ?></td>
                                                <td><a href="<?= $row['link'] ?>"><?= $link ?></a></td>
                                                <td><?= $row['reg_date'] ?></td>
                                                <td><a href="javascript:show_memo('<?= $row['memo'] ?>');" style="<?= $style1 ?>">메모</a></td>
                                            </tr>
                                        <? }
                                        $i++;
                                    }
                                    if ($i == 1) { ?>
                                        <tr>
                                            <td colspan="15" style="text-align:center;background:#fff">
                                                등록된 내용이 없습니다.
                                            </td>
                                        </tr>
                                    <? } ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <? echo drawPagingAdminNavi_iama($totalCnt1, $nowPage1, $pageCnt1, "10", "goPage", "reg_cust"); ?>
                        </div>
                    </div>
                </div>
            <?  } elseif ($_REQUEST['sel_tab'] == "req_cust") { ?>
                <div id="req_cu_list" <?= $hide2 ?>>
                    <div class="box" style="border-top:none;">
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped" style="width:100%;font-size:12px;">
                                <colgroup>
                                    <col width="60px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="60px">
                                    <col width="100px">
                                    <col width="70px">
                                    <col width="70px">
                                    <col width="50px">
                                    <col width="120px">
                                    <col width="120px">
                                    <col width="120px">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>아이디</th>
                                        <th>성명</th>
                                        <th>휴대폰</th>
                                        <th>일반폰</th>
                                        <th>이메일</th>
                                        <th>생년월일</th>
                                        <th>업종</th>
                                        <th>업체명</th>
                                        <th>직책</th>
                                        <th>업체주소</th>
                                        <th>자택주소</th>
                                        <th>링크</th>
                                        <th>등록일시</th>
                                        <th>메모</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="edit_table_req" hidden>
                                        <th>

                                        </th>
                                        <th>
                                            아이디 | <input type="text" name="req_name" id="req_name" class="edit_input" placeholder="성명"> | <input type="text" name="req_phone1" id="req_phone1" class="edit_input" placeholder="휴대폰"> | <input type="text" name="req_phone2" id="req_phone2" class="edit_input" placeholder="일반폰"> | <input type="text" name="req_email" id="req_email" class="edit_input" placeholder="이메일"> | <input type="text" name="req_birthday" id="req_birthday" class="edit_input" placeholder="생년월일"> | <input type="text" name="req_work_type" id="req_work_type" class="edit_input" placeholder="업종"> | <input type="text" name="req_company_name" id="req_company_name" class="edit_input" placeholder="업체명"> | <input type="text" name="req_job" id="req_job" class="edit_input" placeholder="직책"> | <input type="text" name="req_company_addr" id="req_company_addr" class="edit_input" placeholder="업체주소"> | <input type="text" name="req_home_addr" id="req_home_addr" class="edit_input" placeholder="자택주소"> | <input type="text" name="req_link" id="req_link" class="edit_input" placeholder="링크"> | 등록일시 | <input type="text" name="req_memo" id="req_memo" class="edit_input" placeholder="메모">
                                            <input type="hidden" name="req_cust_mode" id="req_cust_mode">
                                            <input type="hidden" name="edit_req_idx" id="edit_req_idx">
                                            <input type="hidden" name="list_req_idx" id="list_req_idx">
                                            <input type="button" style="font-size: 11px;background-color: #99cc00;color: white;" value="저장" onclick="save_req_cust()">
                                            <input type="button" style="font-size: 11px;background-color: #99cc00;color: white;" value="취소" onclick="cancel_reg_cust('req_cust')">
                                        </th>
                                    </tr>
                                    <?
                                    $nowPage2 = $_REQUEST['nowPage_req_cust'] ? $_REQUEST['nowPage_req_cust'] : 1;
                                    $startPage2 = $nowPage2 ? $nowPage2 : 1;
                                    $pageCnt2 = 10;

                                    // 검색 조건을 적용한다.
                                    $searchStr2 .= $search_key ? " AND (a.company_name LIKE '%" . $search_key . "%' or a.mobile like '%" . $search_key . "%' or a.name like '%" . $search_key . "%' or a.work_type like '%" . $search_key . "%' or a.mobile1 like '%" . $search_key . "%' or a.job like '%" . $search_key . "%' or a.addr like '%" . $search_key . "%' or a.addr1 like '%" . $search_key . "%' )" : null;

                                    $order2 = $order2 ? $order2 : "desc";

                                    $query2 = "SELECT SQL_CALC_FOUND_ROWS a.*, b.short_url FROM Gn_event_request a inner join Gn_event b on a.event_idx=b.event_idx WHERE 1=1 and a.m_id='{$_SESSION['iam_member_id']}' $searchStr2";
                                    $res2        = mysqli_query($self_con, $query2);
                                    $totalCnt2    =  mysqli_num_rows($res2);

                                    $limitStr2       = " LIMIT " . (($startPage2 - 1) * $pageCnt2) . ", " . $pageCnt2;
                                    $number2            = $totalCnt2 - ($nowPage2 - 1) * $pageCnt2;

                                    $orderQuery2 .= " ORDER BY regdate DESC $limitStr2";
                                    $i = 1;
                                    $query2 .= $orderQuery2;
                                    echo $query;
                                    $res = mysqli_query($self_con, $query2);
                                    while ($row = mysqli_fetch_array($res)) {
                                        $sql_mem_reg = "select * from Gn_Member where mem_name='{$row['name']}' and mem_phone='{$row['mobile']}' and is_leave='N' limit 1";
                                        $res_mem_reg = mysqli_query($self_con, $sql_mem_reg);
                                        $row_mem_reg = mysqli_fetch_array($res_mem_reg);
                                        if ($row_mem_reg['mem_code'] != '') {
                                            if ($row_mem_reg['mem_memo'] != '') {
                                                $style2 = "color:#99cc00";
                                            } else {
                                                $style2 = "color:black";
                                            }

                                            $mem_name = $row_mem_reg['mem_name'] ? $row_mem_reg['mem_name'] : '성명';
                                            $mem_phone = $row_mem_reg['mem_phone'] ? $row_mem_reg['mem_phone'] : '휴대폰';
                                            $mem_phone1 = $row_mem_reg['mem_phone1'] ? $row_mem_reg['mem_phone1'] : '일반폰';
                                            $mem_email = $row_mem_reg['mem_email'] ? $row_mem_reg['mem_email'] : '이메일';
                                            $mem_birth = $row_mem_reg['mem_birth'] ? $row_mem_reg['mem_birth'] : '생년월일';
                                            $com_type = $row_mem_reg['com_type'] ? $row_mem_reg['com_type'] : '업종';
                                            $zy = $row_mem_reg['zy'] ? $row_mem_reg['zy'] : '업체명';
                                            $mem_leb = $row_mem_reg['mem_job'] ? $row_mem_reg['mem_job'] : '직책';
                                            $com_add1 = $row_mem_reg['com_add1'] ? $row_mem_reg['com_add1'] : '업체주소';
                                            $mem_add1 = $row_mem_reg['mem_add1'] ? $row_mem_reg['mem_add1'] : '자택주소';

                                            $query = "select card_short_url from Gn_Iam_Name_Card where group_id = 0 and mem_id = '{$row_mem_reg['mem_id']}' order by req_data asc";
                                            $cres = mysqli_query($self_con, $query);
                                            $crow = mysqli_fetch_array($cres);
                                            $card_url = $crow[0];
                                    ?>
                                            <tr>
                                                <td style="text-align:center;"> <?= $number2-- ?> <br> <a href="javascript:show_edit_input('<?= $row['request_idx'] ?>', 'member_req_edit', '<?= $row_mem_reg['mem_code'] ?>')"><img src="/iam/img/Picture_iama1.png" style="width:20px;"></a><a href="javascript:move_reg_list('<?= $row['request_idx'] ?>', 'req_cust')" style="margin-left:10px;"><img src="/iam/img/Picture_iama2.png" style="width:20px;"></a></td>
                                                <td><?= $row_mem_reg['mem_id'] ?></td>
                                                <td><?= $mem_name ?></td>
                                                <td><?= $mem_phone ?></td>
                                                <td><?= $mem_phone1 ?></td>
                                                <td><?= $mem_email ?></td>
                                                <td><?= $mem_birth ?></td>
                                                <td><?= $com_type ?></td>
                                                <td><?= $zy ?></td>
                                                <td><?= $mem_leb ?></td>
                                                <td><?= $com_add1 ?></td>
                                                <td><?= $mem_add1 ?></td>
                                                <td><a href="card_list.php?mem_id=<?= $row_mem_reg['mem_id'] ?>"><?= $card_url ?></a></td>
                                                <td><?= $row_mem_reg['first_regist'] ?></td>
                                                <td><a href="javascript:show_memo('<?= $row_mem_reg['mem_memo'] ?>');" style="<?= $style2 ?>">메모</a></td>
                                            </tr>
                                        <? } else {
                                            if ($row['memo'] != '') {
                                                $style2 = "color:#99cc00";
                                            } else {
                                                $style2 = "color:black";
                                            }
                                            $name = $row['name'] ? $row['name'] : '성명';
                                            $mobile = $row['mobile'] ? $row['mobile'] : '휴대폰';
                                            $mobile1 = $row['mobile1'] ? $row['mobile1'] : '일반폰';
                                            $email = $row['email'] ? $row['email'] : '이메일';
                                            $birthday = $row['birthday'] ? $row['birthday'] : '생년월일';
                                            $work_type = $row['work_type'] ? $row['work_type'] : '업종';
                                            $company_name = $row['company_name'] ? $row['company_name'] : '업체명';
                                            $job = $row['job'] ? $row['job'] : '직책';
                                            $addr = $row['addr'] ? $row['addr'] : '업체주소';
                                            $addr1 = $row['addr1'] ? $row['addr1'] : '자택주소';
                                            $short_url = $row['short_url'] ? $row['short_url'] : '링크';
                                            // $memo = $row['memo']?$row['memo']:'메모';
                                        ?>
                                            <tr>
                                                <td style="text-align:center;"> <?= $number2-- ?> <br> <a href="javascript:show_edit_input('<?= $row['request_idx'] ?>', 'req_cust_edit')"><img src="/iam/img/Picture_iama1.png" style="width:20px;"></a><a href="javascript:move_reg_list('<?= $row['request_idx'] ?>', 'req_cust')" style="margin-left:10px;"><img src="/iam/img/Picture_iama2.png" style="width:20px;"></a></td>
                                                <td></td>
                                                <td><?= $name ?></td>
                                                <td><?= $mobile ?></td>
                                                <td><?= $mobile1 ?></td>
                                                <td><?= $email ?></td>
                                                <td><?= $birthday ?></td>
                                                <td><?= $work_type ?></td>
                                                <td><?= $company_name ?></td>
                                                <td><?= $job ?></td>
                                                <td><?= $addr ?></td>
                                                <td><?= $addr1 ?></td>
                                                <td><a href="<?= $row['short_url'] ?>"><?= $short_url ?></a></td>
                                                <td><?= $row['regdate'] ?></td>
                                                <td><a href="javascript:show_memo('<?= $row['memo'] ?>');" style="<?= $style2 ?>">메모</a></td>
                                            </tr>
                                        <? }
                                        $i++;
                                    }
                                    if ($i == 1) {
                                        ?>
                                        <tr>
                                            <td colspan="15" style="text-align:center;background:#fff">
                                                등록된 내용이 없습니다.
                                            </td>
                                        </tr>
                                    <?
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <? echo drawPagingAdminNavi_iama($totalCnt2, $nowPage2, $pageCnt2, "10", "goPage", "req_cust"); ?>
                        </div>
                    </div>
                </div>
            <? } elseif ($_REQUEST['sel_tab'] == "get_cust") { ?>
                <div id="get_cu_list" <?= $hide3 ?>>
                    <div class="box" style="border-top:none;">
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped" style="width:100%;font-size:12px;">
                                <colgroup>
                                    <col width="60px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="60px">
                                    <col width="100px">
                                    <col width="70px">
                                    <col width="70px">
                                    <col width="50px">
                                    <col width="120px">
                                    <col width="120px">
                                    <col width="120px">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>아이디</th>
                                        <th>성명</th>
                                        <th>휴대폰</th>
                                        <th>일반폰</th>
                                        <th>이메일</th>
                                        <th>생년월일</th>
                                        <th>업종</th>
                                        <th>업체명</th>
                                        <th>직책</th>
                                        <th>업체주소</th>
                                        <th>자택주소</th>
                                        <th>링크</th>
                                        <th>등록일시</th>
                                        <th>메모</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="edit_table_get" hidden>
                                        <th>

                                        </th>
                                        <th>
                                            아이디 | <input type="text" name="get_name" id="get_name" class="edit_input" placeholder="성명"> | <input type="text" name="get_phone1" id="get_phone1" class="edit_input" placeholder="휴대폰"> | <input type="text" name="get_phone2" id="get_phone2" class="edit_input" placeholder="일반폰"> | <input type="text" name="get_email" id="get_email" class="edit_input" placeholder="이메일"> | <input type="text" name="get_birthday" id="get_birthday" class="edit_input" placeholder="생년월일"> | <input type="text" name="get_work_type" id="get_work_type" class="edit_input" placeholder="업종"> | <input type="text" name="get_company_name" id="get_company_name" class="edit_input" placeholder="업체명"> | <input type="text" name="get_job" id="get_job" class="edit_input" placeholder="직책"> | <input type="text" name="get_company_addr" id="get_company_addr" class="edit_input" placeholder="업체주소"> | <input type="text" name="get_home_addr" id="get_home_addr" class="edit_input" placeholder="자택주소"> | <input type="text" name="get_link" id="get_link" class="edit_input" placeholder="링크"> | 등록일시 | <input type="text" name="get_memo" id="get_memo" class="edit_input" placeholder="메모">
                                            <input type="hidden" name="get_cust_mode" id="get_cust_mode">
                                            <input type="hidden" name="edit_get_idx" id="edit_get_idx">
                                            <input type="hidden" name="list_get_idx" id="list_get_idx">
                                            <input type="button" style="font-size: 11px;background-color: #99cc00;color: white;" value="저장" onclick="save_get_cust()">
                                            <input type="button" style="font-size: 11px;background-color: #99cc00;color: white;" value="취소" onclick="cancel_reg_cust('get_cust')">
                                        </th>
                                    </tr>
                                    <?
                                    $nowPage3 = $_REQUEST['nowPage_get_cust'] ? $_REQUEST['nowPage_get_cust'] : 1;
                                    $startPage3 = $nowPage3 ? $nowPage3 : 1;
                                    $pageCnt3 = 10;

                                    // 검색 조건을 적용한다.
                                    $searchStr3 .= $search_key ? " AND (a.company_name LIKE '%" . $search_key . "%' or a.cell like '%" . $search_key . "%' or a.ceo like '%" . $search_key . "%' or a.company_type like '%" . $search_key . "%' or a.data_type like '%" . $search_key . "%' or a.address like '%" . $search_key . "%' or a.address1 like '%" . $search_key . "%' )" : null;

                                    $order3 = $order3 ? $order3 : "desc";

                                    $query3 = "SELECT count(seq) cnt FROM crawler_data_supply a WHERE 1=1 and user_id='{$_SESSION['iam_member_id']}' $searchStr3";
                                    $res3        = mysqli_query($self_con, $query3);
                                    $totalRow3    =  mysqli_fetch_array($res3);
                                    $totalCnt3 = $totalRow3[0];

                                    $query3 = "SELECT SQL_CALC_FOUND_ROWS * FROM crawler_data_supply a WHERE 1=1 and user_id='{$_SESSION['iam_member_id']}' $searchStr3";
                                    $limitStr3       = " LIMIT " . (($startPage3 - 1) * $pageCnt3) . ", " . $pageCnt3;
                                    $number3            = $totalCnt3 - ($nowPage3 - 1) * $pageCnt3;

                                    $orderQuery3 .= " ORDER BY regdate DESC $limitStr3 ";

                                    $i = 1;
                                    $query3 .= $orderQuery3;
                                    $res = mysqli_query($self_con, $query3);
                                    while ($row = mysqli_fetch_array($res)) {
                                        $sql_mem_reg = "select * from Gn_Member where mem_name='{$row['ceo']}' and mem_phone='{$row['cell']}' and is_leave='N' limit 1";
                                        $res_mem_reg = mysqli_query($self_con, $sql_mem_reg);
                                        $row_mem_reg = mysqli_fetch_array($res_mem_reg);
                                        if ($row_mem_reg['mem_code'] != '') {
                                            if ($row_mem_reg['mem_memo'] != '') {
                                                $style3 = "color:#99cc00";
                                            } else {
                                                $style3 = "color:black";
                                            }

                                            $mem_name = $row_mem_reg['mem_name'] ? $row_mem_reg['mem_name'] : '성명';
                                            $mem_phone = $row_mem_reg['mem_phone'] ? $row_mem_reg['mem_phone'] : '휴대폰';
                                            $mem_phone1 = $row_mem_reg['mem_phone1'] ? $row_mem_reg['mem_phone1'] : '일반폰';
                                            $mem_email = $row_mem_reg['mem_email'] ? $row_mem_reg['mem_email'] : '이메일';
                                            $mem_birth = $row_mem_reg['mem_birth'] ? $row_mem_reg['mem_birth'] : '생년월일';
                                            $com_type = $row_mem_reg['com_type'] ? $row_mem_reg['com_type'] : '업종';
                                            $zy = $row_mem_reg['zy'] ? $row_mem_reg['zy'] : '업체명';
                                            $mem_leb = $row_mem_reg['mem_job'] ? $row_mem_reg['mem_job'] : '직책';
                                            $com_add1 = $row_mem_reg['com_add1'] ? $row_mem_reg['com_add1'] : '업체주소';
                                            $mem_add1 = $row_mem_reg['mem_add1'] ? $row_mem_reg['mem_add1'] : '자택주소';
                                            // $mem_memo = $row_mem_reg['mem_memo']?$row_mem_reg['mem_memo']:'메모';

                                            $query = "select card_short_url from Gn_Iam_Name_Card where group_id = 0 and mem_id = '{$row_mem_reg['mem_id']}' order by req_data asc";
                                            $cres = mysqli_query($self_con, $query);
                                            $crow = mysqli_fetch_array($cres);
                                            $card_url = $crow[0];
                                    ?>
                                            <tr>
                                                <td style="text-align:center;"> <?= $number3-- ?> <br> <a href="javascript:show_edit_input('<?= $row['seq'] ?>', 'member_get_edit', '<?= $row_mem_reg['mem_code'] ?>')"><img src="/iam/img/Picture_iama1.png" style="width:20px;"></a><a href="javascript:move_reg_list('<?= $row['seq'] ?>', 'get_cust')" style="margin-left:10px;"><img src="/iam/img/Picture_iama2.png" style="width:20px;"></a></td>
                                                <td><?= $row_mem_reg['mem_id'] ?></td>
                                                <td><?= $mem_name ?></td>
                                                <td><?= $mem_phone ?></td>
                                                <td><?= $mem_phone1 ?></td>
                                                <td><?= $mem_email ?></td>
                                                <td><?= $mem_birth ?></td>
                                                <td><?= $com_type ?></td>
                                                <td><?= $zy ?></td>
                                                <td><?= $mem_leb ?></td>
                                                <td><?= $com_add1 ?></td>
                                                <td><?= $mem_add1 ?></td>
                                                <td><a href="card_list.php?mem_id=<?= $row_mem_reg['mem_id'] ?>"><?= $card_url ?></a></td>
                                                <td><?= $row_mem_reg['first_regist'] ?></td>
                                                <td><a href="javascript:show_memo('<?= $row_mem_reg['mem_memo'] ?>');" style="<?= $style3 ?>">메모</a></td>
                                            </tr>
                                        <? } else {
                                            if ($row['memo'] != '') {
                                                $style3 = "color:#99cc00";
                                            } else {
                                                $style3 = "color:black";
                                            }
                                            $name = $row['ceo'] ? $row['ceo'] : '성명';
                                            $mobile = $row['cell'] ? $row['cell'] : '휴대폰';
                                            $mobile1 = $row['cell1'] ? $row['cell1'] : '일반폰';
                                            $email = $row['email'] ? $row['email'] : '이메일';
                                            $birthday = $row['birthday'] ? $row['birthday'] : '생년월일';
                                            $work_type = $row['company_type'] ? $row['company_type'] : '업종';
                                            $company_name = $row['company_name'] ? $row['company_name'] : '업체명';
                                            $job = $row['data_type'] ? $row['data_type'] : '직책';
                                            $addr = $row['address'] ? $row['address'] : '업체주소';
                                            $addr1 = $row['address1'] ? $row['address1'] : '자택주소';
                                            $short_url = $row['url'] ? $row['url'] : '링크';
                                        ?>
                                            <tr>
                                                <td style="text-align:center;"> <?= $number3-- ?> <br> <a href="javascript:show_edit_input('<?= $row['seq'] ?>', 'get_cust_edit')"><img src="/iam/img/Picture_iama1.png" style="width:20px;"></a><a href="javascript:move_reg_list('<?= $row['seq'] ?>', 'get_cust')" style="margin-left:10px;"><img src="/iam/img/Picture_iama2.png" style="width:20px;"></a></td>
                                                <td></td>
                                                <td><?= $name ?></td>
                                                <td><?= $mobile ?></td>
                                                <td><?= $mobile1 ?></td>
                                                <td><?= $email ?></td>
                                                <td><?= $birthday ?></td>
                                                <td><?= $work_type ?></td>
                                                <td><?= $company_name ?></td>
                                                <td><?= $job ?></td>
                                                <td><?= $addr ?></td>
                                                <td><?= $addr1 ?></td>
                                                <td><a href="<?= $row['url'] ?>"><?= $short_url ?></a></td>
                                                <td><?= $row['regdate'] ?></td>
                                                <td><a href="javascript:show_memo('<?= $row['memo'] ?>');" style="<?= $style3 ?>">메모</a></td>
                                            </tr>
                                        <? }
                                        $i++;
                                    }
                                    if ($i == 1) {
                                        ?>
                                        <tr>
                                            <td colspan="15" style="text-align:center;background:#fff">
                                                등록된 내용이 없습니다.
                                            </td>
                                        </tr>
                                    <?
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <?
                            echo drawPagingAdminNavi_iama($totalCnt3, $nowPage3, $pageCnt3, "10", "goPage", "get_cust");
                            ?>
                        </div>
                    </div>
                </div>
            <? } elseif ($_REQUEST['sel_tab'] == "paper_cust") { ?>
                <div id="paper_cu_list" <?= $hide4 ?>>
                    <div class="box" style="border-top:none;">
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped" style="width:100%;font-size:12px;">
                                <colgroup>
                                    <col width="60px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="60px">
                                    <col width="100px">
                                    <col width="70px">
                                    <col width="70px">
                                    <col width="50px">
                                    <col width="120px">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>성명</th>
                                        <th>직책</th>
                                        <th>기관명</th>
                                        <th>주소</th>
                                        <th>전화1</th>
                                        <th>전화2</th>
                                        <th>휴대폰</th>
                                        <th>팩스폰</th>
                                        <th>이멜1</th>
                                        <th>이멜2</th>
                                        <th>메모</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="edit_table_paper" hidden>
                                        <th>
                                            <input type="button" style="font-size: 11px;background-color: #99cc00;color: white;" value="저장" onclick="save_paper_cust()">
                                            <input type="button" style="font-size: 11px;background-color: #99cc00;color: white;" value="취소" onclick="cancel_reg_cust('paper_cust')">
                                        </th>
                                        <th><input type="text" name="paper_name" id="paper_name" class="edit_input" placeholder="성명"></th>
                                        <th><input type="text" name="paper_job" id="paper_job" class="edit_input" placeholder="직책"></th>
                                        <th><input type="text" name="paper_org" id="paper_org" class="edit_input" placeholder="기관명"></th>
                                        <th><input type="text" name="paper_addr" id="paper_addr" class="edit_input" placeholder="주소"></th>
                                        <th><input type="text" name="paper_phone1" id="paper_phone1" class="edit_input" placeholder="전화1"></th>
                                        <th><input type="text" name="paper_phone2" id="paper_phone2" class="edit_input" placeholder="전화2"></th>
                                        <th><input type="text" name="paper_mobile" id="paper_mobile" class="edit_input" placeholder="휴대폰"></th>
                                        <th><input type="text" name="paper_fax" id="paper_fax" class="edit_input" placeholder="팩스폰"></th>
                                        <th><input type="text" name="paper_email1" id="paper_email1" class="edit_input" placeholder="이멜1"></th>
                                        <th><input type="text" name="paper_email2" id="paper_email2" class="edit_input" placeholder="이멜2"></th>
                                        <th>
                                            <input type="text" name="paper_memo" id="paper_memo" class="edit_input" placeholder="메모">
                                            <input type="hidden" name="paper_cust_mode" id="paper_cust_mode">
                                            <input type="hidden" name="edit_paper_idx" id="edit_paper_idx">
                                            <input type="hidden" name="list_paper_idx" id="list_paper_idx">
                                        </th>
                                    </tr>
                                    <?
                                    $nowPage4 = $_REQUEST['nowPage_paper_cust'] ? $_REQUEST['nowPage_paper_cust'] : 1;
                                    $startPage4 = $nowPage4 ? $nowPage4 : 1;
                                    $pageCnt4 = 10;

                                    // 검색 조건을 적용한다.
                                    $searchStr4 .= $search_key ? " AND (card.mem_id LIKE '%" . $search_key . "%' or card.name like '%" . $search_key . "%' or card.comment like '%" . $search_key . "%' or card.address like '%" . $search_key . "%' )" : null;

                                    $order4 = $order4 ? $order4 : "desc";

                                    $query4 = "SELECT count(seq) FROM Gn_MMS_Receive_Iam iam INNER JOIN Gn_Member_card card  ON iam.paper_seq=card.seq WHERE card.mem_id = '{$_SESSION['iam_member_id']}' $searchStr4";
                                    $res4        = mysqli_query($self_con, $query4);
                                    $totalRow4    =  mysqli_fetch_array($res4);
                                    $totalCnt4 = $totalRow4[0];

                                    $query4 = "SELECT iam.idx,iam.display_top,iam.name AS iam_name,card.* FROM Gn_MMS_Receive_Iam iam INNER JOIN Gn_Member_card card  ON iam.paper_seq=card.seq WHERE card.mem_id = '{$_SESSION['iam_member_id']}' $searchStr4";
                                    $limitStr4       = " LIMIT " . (($startPage4 - 1) * $pageCnt4) . ", " . $pageCnt4;
                                    $number4            = $totalCnt4 - ($nowPage4 - 1) * $pageCnt4;
                                    $orderQuery4 .= " ORDER BY iam.display_top DESC $limitStr4 ";

                                    $i = 1;
                                    $query4 .= $orderQuery4;
                                    $res = mysqli_query($self_con, $query4);
                                    while ($row = mysqli_fetch_array($res)) {
                                        if ($row['memo'] != '') {
                                            $style3 = "color:#99cc00";
                                        } else {
                                            $style3 = "color:black";
                                        }
                                    ?>
                                        <tr>
                                            <td style="text-align:center;"> <?= $number4-- ?> <br> <a href="javascript:show_edit_input('<?= $row['seq'] ?>', 'paper_edit')"><img src="/iam/img/Picture_iama1.png" style="width:20px;"></a></td>
                                            <td><?= $row['name'] == '' ? $row['iam_name'] : $row['name'] ?></td>
                                            <td><?= $row['job'] ?></td>
                                            <td><?= $row['org_name'] ?></td>
                                            <td><?= $row['address'] ?></td>
                                            <td><?= $row['phone1'] ?></td>
                                            <td><?= $row['phone2'] ?></td>
                                            <td><?= $row['mobile'] ?></td>
                                            <td><?= $row['fax'] ?></td>
                                            <td><?= $row['email1'] ?></td>
                                            <td><?= $row['email2'] ?></td>
                                            <td><a href="javascript:show_memo('<?= $row['memo'] ?>');" style="<?= $style3 ?>">메모</a></td>
                                        </tr>
                                    <?
                                        $i++;
                                    }
                                    if ($i == 1) {
                                    ?>
                                        <tr>
                                            <td colspan="12" style="text-align:center;background:#fff">
                                                등록된 내용이 없습니다.
                                            </td>
                                        </tr>
                                    <?
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <? echo drawPagingAdminNavi_iama($totalCnt4, $nowPage4, $pageCnt4, "10", "goPage", "paper_cust"); ?>
                        </div>
                    </div>
                </div>
            <? } ?>
            <!-- Footer -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 1.0.0
                </div>
                <strong>Copyright &copy; 2016 Onlyone All rights reserved.
            </footer>
            <div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
            <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
            <div id='ajax_div'></div>
        </div><!-- ./content-wrapper -->
        <div id="mem_memo_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
            <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
                <!-- Modal content-->
                <div class="modal-content" style="width: 350px;">
                    <div>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="opacity:1;">
                            <img src="/iam/img/menu/icon_close_white.png" style="width:24px" data-dismiss="modal" aria-hidden="true">
                        </button>
                    </div>
                    <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                        <label style="padding:15px 0px">메모</label>
                    </div>
                    <div class="modal-body">
                        <div class="container" style="text-align: center;width:100%;">
                            <p name="mem_memo" id="mem_memo" style="width:100%;"></p>
                            <!-- <input type="hidden" name="mem_id_memo" id="mem_id_memo"> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- ./wrapper -->
</body>

</html>