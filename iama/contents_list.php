<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$mem_id= $_GET['mem_id'];
if($_GET['total_cnt'] != '')
    $totalCnt = $_GET['total_cnt'] * 1;
if($HTTP_HOST != "kiam.kr") {
    $query = "select * from Gn_Iam_Service where sub_domain = 'http://".$HTTP_HOST."'";
    $res = mysqli_query($self_con,$query);
    $domainData = mysqli_fetch_array($res);

    if($domainData['mem_id'] != $_SESSION[iam_member_id]) {
        echo "<script>location='/';</script>";
        exit;
    }
    $parse = parse_url($domainData['sub_domain']);
    $site = explode(".", $parse['host']);
}
else{
    $query = "select * from Gn_Iam_Service where sub_domain = 'http://www.kiam.kr'";
    $res = mysqli_query($self_con,$query);
    $domainData = mysqli_fetch_array($res);

    if($domainData['mem_id'] != $_SESSION[iam_member_id]) {
        echo "<script>location='/';</script>";
        exit;
    }
    $site = explode(".", "kiam.kr");
}
    
if($mem_id != "") {
    $query = "select * from Gn_Member where mem_id='$mem_id' ";
    $cres = mysqli_query($self_con,$query);
    $user = mysqli_fetch_array($cres);

    $query = "select count(*) from Gn_Iam_Name_Card  where group_id is NULL and mem_id='$mem_id' ";
    $cres = mysqli_query($self_con,$query);
    $crow = mysqli_fetch_array($cres);
    $card_cnt = $crow[0];

    $query = "select count(*) from Gn_Iam_Contents where group_id is NULL and mem_id='$mem_id' ";
    $cres = mysqli_query($self_con,$query);
    $crow = mysqli_fetch_array($cres);
    $contents_cnt = $crow[0];

    $query = "select count(*) from Gn_Iam_Friends  where mem_id='$mem_id' ";
    $cres = mysqli_query($self_con,$query);
    $crow = mysqli_fetch_array($cres);
    $friends_cnt = $crow[0];
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
        <style>
            .loading_div{position:fixed;left:50%;top:50%;display:none;z-index:1000;}
            .open_1{position:absolute;z-index:10;background-color:#FFF;display:none;border:1px solid #000}
            .open_2{padding-left:5px;height:30px;cursor:move;}
            .open_2_1{float:left;line-height:30px;font-size:16px;font-weight:bold;}
            .open_2_2{float:right;}
            .open_2_2 a:link,
            .open_2_2 a:visited,
            .open_2_2 a:active{text-decoration:none; color:#FFF; }
            .open_2_2 a:hover{text-decoration:none;color:#FF0;}
            .open_3{padding:10px;}
            .loading_div {display:none;position: fixed;left: 50%;top: 50%;display: none;z-index: 1000;}
            #open_recv_div li{list-style: none;}
                .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
                border: 1px solid #ddd!important;
            }
            #open_recv_div li{list-style: none;}
            input, select, textarea {vertical-align: middle;border: 1px solid #CCC;}
            /* user agent stylesheet */
            .zoom {transition: transform .2s; /* Animation */}
            .zoom:hover {
                transform: scale(4); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
                border:1px solid #0087e0;
                box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
            }
            .zoom-2x {transition: transform .2s; /* Animation */}
            .zoom-2x:hover {
                transform: scale(2); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
                border:1px solid #0087e0;
                box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
            }
            .text-center{margin:0px !important;overflow:hidden !important;}
        </style>
        <!-- jQuery 2.1.4 -->
        <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini"><script type="text/javascript" src="/jquery.lightbox_me.js"></script>
        <div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
        <div class="wrapper">
            <!-- Top 메뉴 -->
            <? include "header.php";?>
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper"  style="margin-left: 0px !important;background:#fff">
                <!-- Content Header (Page header) -->
                <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
                    <input type="hidden" name="one_id"   id="one_id"   value="" />
                    <input type="hidden" name="mem_pass" id="mem_pass" value="" />
                    <input type="hidden" name="mem_code" id="mem_code" value="" />
                </form>
                <!-- Main content -->
                <?if($mem_id != "") {?>
                    <div class="row text-center">
                        <section class="content-header">
                            <h1>
                                <?echo $user[mem_name];?> 회원
                            </h1>
                        </section>
                    </div>
                <?php }?>

                <div class="row text-center">
                    <div class="box">
                        <div class="box-title">
                        <?if($mem_id != "") {?>
                            <table id="example1" class="table table-bordered table-striped" style="width:100%;font-size:12px;">
                                <colgroup>
                                    <col width="15%">
                                    <col width="25%">
                                    <col width="18%">
                                    <col width="18%">
                                    <col width="24%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>이름</th>
                                        <td class="text-center" ><?echo $user['mem_name'];?></td>
                                        <th>직책</th><td class="text-center"><?echo $user['zy'];?></td>
                                        <td class="text-center" style="vertical-align:top;"  rowspan="3">
                                        <div style="height:40px;background:#ddd;padding:10px">
                                            <a href="card_list.php?mem_id=<?php echo $user['mem_id']?>">카드보기</a><BR>
                                        </div>
                                        <br>
                                        <div style="height:40px;background:#ddd;padding:10px">
                                            <a href="contents_list.php?mem_id=<?php echo $user['mem_id']?>">컨텐츠보기</a></td>
                                        </div>
                                    </tr>
                                    <tr>
                                        <th>이메일</th>
                                        <td class="text-center"><?php echo $user['mem_email'];?></td>
                                        <th>카드수</th>
                                        <td class="text-center"><?php echo number_format($card_cnt);?></td>
                                    </tr>
                                    <tr>
                                        <th>컨텐츠수</th>
                                        <td class="text-center"><?php echo number_format($contents_cnt);?></td>
                                        <th>프렌즈수</th>
                                        <td class="text-center"><?php echo number_format($friends_cnt);?></td>
                                    </tr>
                                </thead>
                            </table>
                        <?}?>
                        </div>
                        <div class="row text-center">
                            <section class="content-header">
                                <h1>컨텐츠 정보 보기</h1>
                            </section>
                        </div>
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped" style="width:100%;font-size:12px;">
                                <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>번호</th>
                                    <th>아이디</th>
                                    <th>게시자</th>
                                    <th>콘제목</th>
                                    <th>콘주소</th>
                                    <th>콘링크</th>
                                    <th>이미지보기</th>
                                    <th>등록일</th>
                                    <th>조회수</th>
                                    <th>공유수</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?
                                    $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                    $startPage = $nowPage?$nowPage:1;
                                    $pageCnt = 20;
                                    // 검색 조건을 적용한다.
                                    $searchStr .= $search_key ? " AND (contents.mem_id LIKE '%".$search_key."%' or contents.contents_title like '%".$search_key."%' )" : null;
                                    $order = $order?$order:"desc";
                                    
                                    $mem_array = array();
                                    $mem_query = "select mem_id from Gn_Member use index(mem_id) where site_iam='$site[0]'";
                                    $mem_res = mysqli_query($self_con,$mem_query);
                                    while($mem_row = mysqli_fetch_array($mem_res)){
                                        array_push($mem_array, "'".$mem_row['mem_id']."'");
                                    }
                                    
                                    if($mem_id)
                                        $mem_str = "'$mem_id'";
                                    else
                                        $mem_str = implode(",",$mem_array);
                                    $query = "SELECT  count(contents.idx) as cnt
                                            FROM Gn_Iam_Contents contents use index(idx)
                                            WHERE mem_id in ($mem_str) $searchStr";
                                    $res	    = mysqli_query($self_con,$query);
                                    $totalRow	=  mysqli_fetch_array($res);
                                    $totalCnt	=  $totalRow[0];
                                    
                                    $query = "SELECT  contents.idx, contents.mem_id, contents.contents_title, contents.contents_temp, contents.contents_like,contents.contents_url,contents.req_data,contents.up_data,contents.contents_share_count,contents.contents_img
                                              FROM Gn_Iam_Contents contents use index(idx) 
                                              WHERE mem_id in ($mem_str) $searchStr";
                                    
                                    $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                    $number = $totalCnt - ($nowPage - 1) * $pageCnt;
                                    if(!$orderField)
                                        $orderField = "req_data";

                                    $orderQuery .= " ORDER BY $orderField desc $limitStr";
                                    $i = 1;
                                    $c = 0;
                                    $query .= $orderQuery;
                                    $res = mysqli_query($self_con,$query);
                                    while($row = mysqli_fetch_array($res)) {
                                        $mem_query = "select mem_name from Gn_Member use index(mem_id) where mem_id='$row[mem_id]'";
                                        $mem_res = mysqli_query($self_con,$mem_query);
                                        $mem_row = mysqli_fetch_array($mem_res);
                                        $row['mem_name'] = $mem_row['mem_name'];
                                ?>
                                    <tr>
                                        <td style="text-align:center"> <?=$number--?></td>
                                        <td><?=$row['idx']?></td>
                                        <td><?=$row['mem_id']?></td>
                                        <td><?=$row['mem_name']?></td>
                                        <td><?=$row['contents_title']?></td>
                                        <td><a href="<?=$row['contents_url']?>" target="_blank"><?=$row['contents_url']?></a></td>
                                        <td><a href="/iam/contents.php?contents_idx=<?=$row[idx]?>" target="_blank">콘링크</a></td>
                                        <td><a href="<?=$row[contents_img]?>" target="_blank"><img class="zoom" src="<?=$row[contents_img]?>" style="width:50px;"></a></td>
                                        <td><?=$row['req_data']?></td>
                                        <td><?=$row['contents_share_count']?></td>
                                        <td><?=$row['contents_share_count']?></td>
                                    </tr>
                                        <?
                                        $c++;
                                        $i++;
                                    }
                                    if($i == 1) {
                                    ?>
                                        <tr>
                                            <td colspan="10" style="text-align:center;background:#fff">
                                                등록된 내용이 없습니다.
                                            </td>
                                        </tr>
                                    <?}?>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <?echo drawPagingAdminNavi($totalCnt, $nowPage);?>
                        </div>
                    </div>
                </div><!-- /.row -->
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
            </div><!-- ./contens wrapper -->
        </div><!-- ./wrapper -->
    </body>
</html>
<script>
    function goPage(pgNum) {
        location.href = '?1&total_cnt='+'<?=$totalCnt?>'+'&nowPage='+pgNum+"&search_key=";
    }
    $(function() {
        var contHeaderH = $(".main-header").height();
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $(".main-footer").height() - $("#list_paginate").height() - 186;
        if(height < 375)
            height = 375;
        $(".box-body").css("height",height);
    });
</script>