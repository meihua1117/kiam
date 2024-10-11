<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if($HTTP_HOST != "kiam.kr") {
    $query = "select * from Gn_Iam_Service where sub_domain = 'http://".$HTTP_HOST."'";
    $res = mysqli_query($self_con,$query);
    $domainData = mysqli_fetch_array($res);
    
    if($domainData['mem_id'] != $_SESSION['iam_member_id']) {
        echo "<script>location='/';</script>";
        exit;
    }

    $parse = parse_url($domainData['sub_domain']);
    $site = explode(".", $parse['host']);
}else{
    $query = "select * from Gn_Iam_Service where sub_domain = 'http://www.kiam.kr'";
    $res = mysqli_query($self_con,$query);
    $domainData = mysqli_fetch_array($res);
    
    if($domainData['mem_id'] != $_SESSION['iam_member_id']) {
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
        <!-- jQuery 2.1.4 -->
        <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    </head>
    <body class="hold-transition skin-blue sidebar-mini"><script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
function show_memo(memo, mem_id){
    $("#mem_memo").val(memo);
    $("#mem_id_memo").val(mem_id);
    $("#mem_memo_modal").modal("show");
}
function cancel_modal(){
    $("#mem_memo_modal").modal("hide");
}
function reg_memo(){
    var memo = $("#mem_memo").val();
    var mem_id = $("#mem_id_memo").val();
    $.ajax({
        type:"POST",
        url:"/admin/ajax/user_change.php",
        data:{update_memo:true,memo:memo,mem_id:mem_id},
        dataType:'json',
        success:function(){
            alert('등록되었습니다.');
            location.reload();
        },
        error: function(){
            alert('삭제 실패');
        }
    });	
}

function goPage(pgNum) {
    location.href = '?1&nowPage='+pgNum+"&search_key=";
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - $("#list_paginate").height() - 186;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
</script>   
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


    .loading_div {
        display:none;
        position: fixed;
        left: 50%;
        top: 50%;
        display: none;
        z-index: 1000;
    }
    #open_recv_div li{list-style: none;}
    .table-bordered>thead>tr>th, 
    .table-bordered>tbody>tr>th, 
    .table-bordered>tfoot>tr>th, 
    .table-bordered>thead>tr>td, 
    .table-bordered>tbody>tr>td,
    .table-bordered>tfoot>tr>td {
        border: 1px solid #ddd!important;
    }
    #open_recv_div li{list-style: none;}
    .text-center{margin:0px !important;overflow:hidden !important;}
</style>
        <div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
        <div class="wrapper">
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
                        <h1><?=$user['mem_name'];?> 회원</h1>
                    </section>                    
                </div><!-- row end-->
            <?php }?>
            <br> 
                <div class="row text-center" style="">
                    <section class="content-header">
                        <h1>회원 정보보기</h1>
                    </section>
                </div>
                <div class="box" >
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped" style="font-size:12px;">
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
                                    <th>폰번호</th>
                                    <th>이메일</th>
                                    <th>생년월일</th>
                                    <th>소속/직책</th>
                                    <th>주소</th>
                                    <th>도메인</th>
                                    <th>카드링크</th>
                                    <th>추천ID</th>
                                    <th>가입일시</th>
                                    <th>최근로그인일시</th>
                                    <th>메모</th>
                                </tr>
                            </thead>
                            <tbody>
                    <?
                    //디폴트 아바타
                    $sql = "select main_img1 from Gn_Iam_Info where mem_id = 'obmms02'";
                    $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                    $row=mysqli_fetch_array($result);
                    $default_img =  $row['main_img1'];
                    
                    $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                    $startPage = $nowPage?$nowPage:1;
                    $pageCnt = 10;

                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (a.mem_id LIKE '%".$search_key."%' or a.mem_phone like '%".$search_key."%' or a.mem_name like '%".$search_key."%' )" : null;

                    $order = $order?$order:"desc";

                    $query = "SELECT count(a.mem_code) FROM Gn_Member a WHERE 1=1 and a.site_iam='$site[0]' $searchStr";

                    $res	    = mysqli_query($self_con,$query);
                    $row = mysqli_fetch_array($res);
                    $totalCnt	=  $row[0];

                    $query = "SELECT SQL_CALC_FOUND_ROWS a.* FROM Gn_Member a WHERE 1=1 and a.site_iam='$site[0]' $searchStr";
                    $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                    $number			= $totalCnt - ($nowPage - 1) * $pageCnt;

                    $orderQuery .= " ORDER BY mem_code DESC $limitStr";
                    $i = 1;
                    $query .= $orderQuery;
                    $res = mysqli_query($self_con,$query);
                    while($row = mysqli_fetch_array($res)) {
                        $query = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$row['mem_id']}' order by req_data asc";
                        $cres = mysqli_query($self_con,$query);
                        $crow = mysqli_fetch_array($cres);
                        $card_url = $crow[0];
                        if($row['mem_memo'] != ''){
                            $style = "color:#99cc00";
                        }
                        else{
                            $style = "color:black";
                        }
                        ?>
                        <tr>
                            <td><?=$number--?></td>
                            <td><?=$row['mem_id']?></td>
                            <td><?=$row['mem_name']?></td>
                            <td><?=$row['mem_phone']?></td>
                            <td><?=$row['mem_email']?></td>
                            <td><?=$row['mem_birth']?></td>
                            <td><?=$row['zy']?></td>
                            <td><?=$row['mem_add1']?></td>
                            <td><?=$row['site_iam']?></td>
                            <td><a href="card_list.php?mem_id=<?=$row['mem_id']?>"><?=$card_url?></a></td>
                            <td><?=$row['recommend_id']?></td>
                            <td><?=$row['first_regist']?></td>
                            <td><?=$row['login_date']?></td>
                            <td><a href="javascript:show_memo('<?=$row['mem_memo']?>', '<?=$row['mem_id']?>');" style="<?=$style?>">메모</a></td>
                        </tr>

                        <?
                        $i++;
                    }
                    if($i == 1) {
                        ?>
                        <tr>
                            <td colspan="2" style="text-align:center;background:#fff">
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
                echo drawPagingAdminNavi($totalCnt, $nowPage, $pageCnt);
                ?>
            </div>
        </div>
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
        <div id="mem_memo_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
            <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
                <!-- Modal content-->
                <div class="modal-content" style="width: 350px;">
                    <div>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="opacity:1;">
                            <img src="/iam/img/menu/icon_close_white.png" style="width:24px" data-dismiss="modal" aria-hidden="true">
                        </button>
                    </div>
                    <div class = "modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                        <label style="padding:15px 0px">메모 등록하기</label>
                    </div>
                    <div class="modal-body">
                        <div class="container" style="text-align: center;width:100%;">
                            <textarea name="mem_memo" id="mem_memo" style="width:100%;height:200px;"></textarea>
                            <input type="hidden" name="mem_id_memo" id="mem_id_memo">
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <button type="button" class="btn btn-primary" style="background-color:#50d050" onclick="cancel_modal()">취소</button>
                        <button type="button" class="btn btn-primary" style="background-color:#50d050" onclick="reg_memo()">등록</button>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- ./wrapper -->
  </body>
</html>      