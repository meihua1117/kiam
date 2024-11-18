<?php
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
echo "test1"."<br>";
$query = "select count(*) from Gn_Member where site_iam='$site[0]'";
$res = mysqli_query($self_con, $query);
$row = mysqli_fetch_array($res);
$mem_cnt = $row[0];
echo "test2"."<br>";
$query = "select count(*) from Gn_Member where site_iam='$site[0]' and first_regist >= '" . date("Y-m-d 00:00:00") . "'";
$res = mysqli_query($self_con, $query);
$row = mysqli_fetch_array($res);
$new_cnt = $row[0];
echo "test3"."<br>";
$query = "select count(*) from Gn_Member where site_iam='$site[0]' and ext_recm_id = '{$domainData['mem_id']}'";
$res = mysqli_query($self_con, $query);
$row = mysqli_fetch_array($res);
$recommend_cnt = $row[0];
echo "test4"."<br>";
$query = "select count(*) from Gn_Iam_Name_Card a inner join  Gn_Member b on a.mem_id = b.mem_id where a.group_id is NULL and b.site_iam='$site[0]' ";
$res = mysqli_query($self_con, $query);
$row = mysqli_fetch_array($res);
$card_cnt = $row[0];
echo "test5"."<br>";
$query = "select count(*) from Gn_Iam_Name_Card a inner join  Gn_Member b on a.mem_id = b.mem_id  where a.group_id is NULL and b.site_iam='$site[0]' and a.req_data >= '" . date("Y-m-d 00:00:00") . "'";
$res = mysqli_query($self_con, $query);
$row = mysqli_fetch_array($res);
$card_new_cnt = $row[0];
echo "test6"."<br>";
$query = "select sum(iam_share) from Gn_Iam_Name_Card a inner join  Gn_Member b on a.mem_id = b.mem_id  where a.group_id is NULL and b.site_iam='$site[0]' ";
$res = mysqli_query($self_con, $query);
$row = mysqli_fetch_array($res);
$card_share_cnt = $row[0];
echo "test7"."<br>";
$query = "select count(*) from Gn_Iam_Contents a inner join  Gn_Member b on a.mem_id = b.mem_id where b.site_iam='$site[0]' ";
$res = mysqli_query($self_con, $query);
$row = mysqli_fetch_array($res);
$contents_cnt = $row[0];
echo "test8"."<br>";
$query = "select count(*) from Gn_Iam_Contents a inner join  Gn_Member b on a.mem_id = b.mem_id  where b.site_iam='$site[0]' and a.req_data >= '" . date("Y-m-d 00:00:00") . "'";
$res = mysqli_query($self_con, $query);
$row = mysqli_fetch_array($res);
$contents_new_cnt = $row[0];
echo "test9"."<br>";
$query = "select sum(iam_share) from Gn_Iam_Contents a inner join  Gn_Member b on a.mem_id = b.mem_id  where b.site_iam='$site[0]' ";
$res = mysqli_query($self_con, $query);
$row = mysqli_fetch_array($res);
$contents_share_cnt = $row[0];
echo "test10"."<br>";

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
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
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
    <!-- jQuery 2.1.4 -->
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    <style>
        .loading_div {
            position: fixed;
            left: 50%;
            top: 50%;
            display: none;
            z-index: 1000;
        }

        .open_2_2 a:link,
        .open_2_2 a:visited,
        .open_2_2 a:active {
            text-decoration: none;
            color: #FFF;
        }

        .open_2_2 a:hover {
            text-decoration: none;
            color: #FF0;
        }

        .loading_div {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            display: none;
            z-index: 1000;
        }

        #open_recv_div li {
            list-style: none;
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
            vertical-align: middle;
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
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini"><!--script type="text/javascript" src="/jquery.lightbox_me.js"></script-->
    <div class="loading_div"><img src="/images/ajax-loader.gif"></div>
    <div class="wrapper" style="margin-left: auto;margin-right: auto;position: relative;max-width: 750px">
        <!-- Top 메뉴 -->
        <header class="main-header">
            <!-- Logo -->
            <a href="/admin/member_list.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>A</b>LT</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>아이엠관리자</b></span>
            </a>
        </header>
        <!-- Content Wrapper. Contains page content -->
        <div class="join" style="margin-left: 0px !important;background:#fff">
            <section class="content" style="font-size:12px;">
                <div class="row">
                    <div class="col-xs-6 col-sm-6  text-center">
                        <button style="width:100px;height:25px;" onclick="location='service_Iam_admin.php'"> <b>분양정보</b></button>
                        <table id="example1" class="table table-bordered table-striped" style="width:100%">
                            <colgroup>
                                <col width="50%">
                                <col width="50%">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>분양일시</th>
                                    <td><?= substr($domainData['regdate'], 0, 10); ?></td>
                                </tr>
                                <tr>
                                    <th>분양인원수</th>
                                    <td><?= $domainData['mem_cnt']; ?>명</td>
                                </tr>
                                <tr>
                                    <th>카드승인건수</th>
                                    <td><?= $domainData['iamcard_cnt']; ?>개</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-xs-6 col-sm-6 text-center">
                        <button style="width:100px;height:25px;" onclick="location='member_list.php'"> <b>회원정보</b></button>
                        <table id="example1" class="table table-bordered table-striped" style="width:100%">
                            <colgroup>
                                <col width="50%">
                                <col width="50%">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>총회원수</th>
                                    <td><?= $mem_cnt; ?> 명</td>
                                </tr>
                                <tr>
                                    <th>새가입자</th>
                                    <td><?= $new_cnt; ?> 명</td>
                                </tr>
                                <tr>
                                    <th>추천수</th>
                                    <td><?= $recommend_cnt; ?>개</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-sm-6  text-center">
                        <button style="width:100px;height:25px;" onclick="location='card_list.php'"> <b>카드정보</b></button>
                        <table id="example1" class="table table-bordered table-striped" style="width:100%">
                            <colgroup>
                                <col width="50%">
                                <col width="50%">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>총카드수</th>
                                    <td><?= number_format($card_cnt); ?></td>
                                </tr>
                                <tr>
                                    <th>새카드수</th>
                                    <td><?= number_format($card_new_cnt); ?></td>
                                </tr>
                                <tr>
                                    <th>공유총수</th>
                                    <td><?= number_format($card_share_cnt); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xs-6 col-sm-6  text-center">
                        <button style="width:100px;height:25px;" onclick="location='contents_list.php'"> <b>컨텐츠정보</b></button>
                        <table id="example1" class="table table-bordered table-striped" style="width:100%">
                            <colgroup>
                                <col width="50%">
                                <col width="50%">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>총컨텐츠</th>
                                    <td><?= number_format($contents_cnt); ?></td>
                                </tr>
                                <tr>
                                    <th>새컨텐츠</th>
                                    <td><?= number_format($contents_new_cnt); ?></td>
                                </tr>
                                <tr>
                                    <th>공유건수</th>
                                    <td><?= number_format($contents_share_cnt); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <button style="width:100%;height:25px;float:center;" onclick="location.href='/index.php'">
                        <b>아이엠 바로가기</b>
                    </button>
                </div>
                <br>
            </section>
            <!-- Main content -->
            <div class="row text-center">
                <section class="content-header">
                    <h1>아이엠 공지사항</h1>
                </section>
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped" style="width:100%">
                            <colgroup>
                                <col width="10%">
                                <col width="55%">
                                <col width="20%">
                                <col width="15%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>제목</th>
                                    <th>등록일</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
                                $startPage = $nowPage ? $nowPage : 1;
                                $pageCnt = 40;
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_key ? " AND (a.mem_id LIKE '%" . $search_key . "%' or a.mem_phone like '%" . $search_key . "%' or a.mem_name like '%" . $search_key . "%' or b.sendnum like '%" . $search_key . "%')" : null;
                                $order = $order ? $order : "desc";
                                $query = "SELECT SQL_CALC_FOUND_ROWS * FROM tjd_sellerboard WHERE 1=1 and category=10 $searchStr";
                                $res        =  mysqli_query($self_con, $query);
                                $totalCnt    =  mysqli_num_rows($res);
                                $limitStr   =  " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
                                $number        =  $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY important_yn DESC, no DESC $limitStr ";
                                $i = 1;
                                $query .= "$orderQuery";
                                $res = mysqli_query($self_con, $query);
                                while ($row = mysqli_fetch_array($res)) {
                                    if ($row['important_yn'] == 'Y')
                                        $style = "style='background:#ffd700'";
                                    else
                                        $style = "";
                                ?>
                                    <tr <?= $style ?>>
                                        <td><?= $number-- ?></td>
                                        <td><?= $row['title']; ?></td>
                                        <td><?= substr($row['date'], 0, 10) ?></td>
                                    </tr>
                                <?
                                    $i++;
                                }
                                if ($i == 1) {
                                ?>
                                    <tr>
                                        <td colspan="4" style="text-align:center;background:#fff">
                                            등록된 내용이 없습니다.
                                        </td>
                                    </tr>
                                <? } ?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="row text-center">
                <div class="col-sm-12">
                    <? echo drawPagingAdminNavi($totalCnt, $nowPage); ?>
                </div>
            </div><!-- /.row -->
        </div><!-- /content-wrapper -->
        <!-- Footer -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 1.0.0
            </div>
            <strong>Copyright &copy; 2016 Onlyone All rights reserved.
        </footer>
        <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
        <div id='ajax_div'></div>
</body>

</html>