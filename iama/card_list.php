<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$mem_id= $_GET['mem_id'];
if($_GET['total_cnt'] != '')
    $totalCnt = $_GET['total_cnt'] * 1;
if($HTTP_HOST != "kiam.kr") {
    $query = "select * from Gn_Iam_Service where sub_domain = 'http://".$HTTP_HOST."'";
    $res = mysql_query($query);
    $domainData = mysql_fetch_array($res);

    if($domainData['mem_id'] != $_SESSION[iam_member_id]) {
        echo "<script>location='/';</script>";
        exit;
    }
    $parse = parse_url($domainData['sub_domain']);
    $site = explode(".", $parse['host']);
}
else{
    $query = "select * from Gn_Iam_Service where sub_domain = 'http://www.kiam.kr'";
    $res = mysql_query($query);
    $domainData = mysql_fetch_array($res);

    if($domainData['mem_id'] != $_SESSION[iam_member_id]) {
        echo "<script>location='/';</script>";
        exit;
    }
    $site = explode(".", "kiam.kr");
}
    
    $query = "select * from Gn_Member where mem_id='$mem_id' ";
    $cres = mysql_query($query);
    $user = mysql_fetch_array($cres);

    $query = "select count(idx) from Gn_Iam_Name_Card where group_id is NULL and mem_id='$mem_id' ";
    $cres = mysql_query($query);
    $crow = mysql_fetch_array($cres);
    $card_cnt = $crow[0];

    $query = "select count(idx) from Gn_Iam_Contents use index(idx) where mem_id='$mem_id' ";
    $cres = mysql_query($query);
    $crow = mysql_fetch_array($cres);
    $contents_cnt = $crow[0];

    $query = "select count(idx) from Gn_Iam_Friends where mem_id='$mem_id' ";
    $cres = mysql_query($query);
    $crow = mysql_fetch_array($cres);
    $friends_cnt = $crow[0];

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>온리원셀링솔루션 관리자</title>
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
        <!-- <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script> -->
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
            #open_recv_div li{list-style: none;}
            .table-bordered>thead>tr>th{border: 1px solid #ddd!important;}
            .table-bordered>tfoot>tr>th{border: 1px solid #ddd!important;}
            .table-bordered>thead>tr>td{border: 1px solid #ddd!important;}
            .table-bordered>tbody>tr>td{border: 1px solid #ddd!important;}
            .table-bordered>tfoot>tr>td{border: 1px solid #ddd!important;}
            .table-bordered>tbody>tr>th{border: 1px solid #ddd!important;}
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
    <body class="hold-transition skin-blue sidebar-mini">
        <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
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
                <?php if($mem_id != "") {?>
                <div class="row text-center">
                    <section class="content-header">
                        <h1><?=$user[mem_name];?> 회원</h1>
                    </section>
                </div><!-- row end-->
                <?php }?>

                <div class="row text-center">
                    <div class="box">
                        <div class="box-title">
                            <?php if($mem_id != "") {?>
                            <table id="example1" class="table table-bordered table-striped" style="width:100%;font-size:14px;">
                                <colgroup>
                                    <col width="15%">
                                    <col width="25%">
                                    <col width="18%">
                                    <col width="18%">
                                    <col width="24%">
                                </colgroup>
                                <thead >
                                    <tr>
                                        <th>이름</th>
                                        <td class="text-center" ><?=$user['mem_name'];?></td>
                                        <th>직책</th>
                                        <td class="text-center"><?=$user['zy'];?></td>
                                        <td class="text-center" style="vertical-align:top;"  rowspan="3">
                                            <div style="height:40px;background:#ddd;padding:10px;font-size:12px">
                                                <a href="card_list.php?mem_id=<?=$user['mem_id']?>">카드보기</a><BR>
                                            </div>
                                            <br>
                                            <div style="height:40px;background:#ddd;padding:10px;font-size:12px">
                                                <a href="contents_list.php?mem_id=<?=$user['mem_id']?>">컨텐츠보기</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>이메일</th>
                                        <td class="text-center"><?=$user['mem_email'];?></td>
                                        <th>카드수</th>
                                        <td class="text-center"><?=number_format($card_cnt);?></td> </tr>
                                    <tr>
                                        <th>컨텐츠수</th>
                                        <td class="text-center"><?=number_format($contents_cnt);?></td>
                                        <th>프렌즈수</th>
                                        <td class="text-center"><?=number_format($friends_cnt);?></td>
                                    </tr>
                                </thead>
                            </table>
                            <?php }?>
                        </div>
                        <div class="row text-center">
                            <section class="content-header">
                                <h1>카드 정보 보기</h1>
                            </section>
                        </div>
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped" style="width:100%;font-size:12px;">
                                <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>카드번호</th>
                                    <th>아이디</th>
                                    <th>카드제목</th>
                                    <th>성명</th>
                                    <th>소속</th>
                                    <th>직책</th>
                                    <th>자기소개</th>
                                    <th>폰번호</th>
                                    <th>주소</th>
                                    <th>이메일</th>
                                    <th>카드링크</th>
                                    <th>카드제작일</th>
                                    <th>조회수</th>
                                    <th>이미지보기
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?
                                    //디폴트 아바타
                                    $sql = "select main_img1 from Gn_Iam_Info where mem_id = 'obmms02'";
                                    $result=mysql_query($sql) or die(mysql_error());
                                    $row=mysql_fetch_array($result);
                                    $default_img =  $row['main_img1'];

                                    $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                    $startPage = $nowPage?$nowPage:1;
                                    $pageCnt = 20;

                                    // 검색 조건을 적용한다.
                                    $searchStr .= $search_key ? " AND (ca.mem_id LIKE '%".$search_key."%' or ca.card_name like '%".$search_key."%' or ca.card_company like '%".$search_key."%' )" : null;

                                    //프렌즈를  card_idx별로 그룹화
                                    $friends_sql = "select f.friends_card_idx as card_idx, count(f.idx) as f_count from Gn_Iam_Friends as f group by f.friends_card_idx";
                                    //컨텐츠를 card_short_url로 그룹화
                                    $contents_sql = "select c.card_idx as card_idx, count(c.idx) as c_count from Gn_Iam_Contents as c group by c.card_idx";
                                    
                                    $mem_array = array();
                                    $mem_query = "select mem_id from Gn_Member use index(mem_id) where site_iam='$site[0]'";
                                    $mem_res = mysql_query($mem_query);
                                    while($mem_row = mysql_fetch_array($mem_res)){
                                        array_push($mem_array, "'".$mem_row['mem_id']."'");
                                    }
                                    
                                    /*$query = "SELECT ca.idx,ca.mem_id,ca.card_company,ca.card_title,ca.card_email,ca.card_addr,ca.card_position,ca.card_name,
                                                ca.card_phone,ca.iam_click,ca.iam_share,ca.req_data,ca.main_img1,ca.card_short_url
                                              FROM Gn_Iam_Name_Card as ca inner join Gn_Member d on d.mem_id = ca.mem_id";*/
                                    $mem_array = array();
                                    $mem_query = "select mem_id from Gn_Member use index(mem_id) where site_iam='$site[0]'";
                                    $mem_res = mysql_query($mem_query);
                                    while($mem_row = mysql_fetch_array($mem_res)){
                                        array_push($mem_array, "'".$mem_row['mem_id']."'");
                                    }
                                    if($mem_id)
                                        $mem_str = "'$mem_id'";
                                    else
                                        $mem_str = implode(",",$mem_array);

                                    $query = "SELECT ca.idx,ca.mem_id,ca.card_company,ca.card_title,ca.card_email,ca.card_addr,ca.card_position,ca.card_name,
                                            ca.card_phone,ca.iam_click,ca.iam_share,ca.req_data,ca.main_img1,ca.card_short_url
                                            FROM Gn_Iam_Name_Card as ca ";
                                    $query = $query." WHERE ca.group_id is NULL and  ca.mem_id in ($mem_str) $searchStr";
                                    /*if($mem_id){
                                        $query .= " d.mem_id='$mem_id'";
                                        $query = $query." and d.site_iam='$site[0]' $searchStr";
                                    }else{
                                        $query = $query." d.site_iam='$site[0]' $searchStr";
                                    }*/
                                    $res = mysql_query($query);
                                    if($_GET['total_cnt'] == '')
                                        $totalCnt	=  mysql_num_rows($res);
                                    $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                    $number	= $totalCnt - ($nowPage - 1) * $pageCnt;
                                    if(!$orderField){
                                        $orderField = "req_data";
                                    }
                                    $orderQuery .= " ORDER BY $orderField desc $limitStr";
                                    $i = 1;
                                    $c=0;
                                    $query .= "$orderQuery";
                                    // echo $query;
                                    $res = mysql_query($query);
                                    while($row = mysql_fetch_array($res)) {
                                        $friends_sql = "select count(idx) from Gn_Iam_Friends where friends_card_idx = $row[idx]";
                                        $friends_res =mysql_query($friends_sql);
                                        $friends_row = mysql_fetch_array($friends_res);
                                        
                                        $mem_query = "select mem_name,site_iam from Gn_Member use index(mem_id) where mem_id='$row[mem_id]'";
                                        $mem_res = mysql_query($mem_query);
                                        $mem_row = mysql_fetch_array($mem_res);
                                        $row['mem_name'] = $mem_row['mem_name'];
                                        $row['site_iam'] = $mem_row['site_iam'];

                                        if($row[main_img1]){
                                            $thumb_img =  $row[main_img1];
                                        }else{
                                            $thumb_img =  $default_img;
                                        }
                                    ?>
                                    <tr >
                                        <td><?=$number--?></td>
                                        <td><?=$row['idx']?></td>
                                        <td><?=$row['mem_id']?></td>
                                        <td><?=$row['card_title']?></td>
                                        <td><?=$row['mem_name']?></td>
                                        <td><?=$row['site_iam']?></td>
                                        <td><?=$row['card_company']?></td>
                                        <td><?=$row['card_position']?></td>
                                        <td><?=$row['card_phone']?></td>
                                        <td><?=$row['card_addr']?></td>
                                        <td><?=$row['card_email']?></td>
                                        <td><a href="/?<?=strip_tags($row['card_short_url']).$row['mem_code']?>" target="_blank"><?=$row['card_short_url']?></a></td>
                                        <td><?=$row['req_data']?></td>
                                        <td><?=$row['iam_click']?></td>
                                        <td><a href="<?=$thumb_img?>" target="_blank"><img class="zoom" src="<?=$thumb_img?>" style="width:50px;"></a></td>
                                    </tr>
                                        <?
                                        $c++;
                                        $i++;
                                    }
                                    if($i == 1) {
                                    ?>
                                        <tr>
                                            <td colspan="10" style="text-align:center;background:#fff">등록된 내용이 없습니다.</td>
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
            </div><!-- ./wrapper -->
        </div>
    </body>
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
</html>