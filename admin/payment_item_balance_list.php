<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$search_year = $search_year?$search_year:date("Y");
$search_month = $search_month?sprintf("%02d",$search_month):sprintf("%02d",date("m"));
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/rlatjd_admin.js"></script>
<script>
    function go_item_list(){
        location.href = "payment_item_list.php";
    }
    //주소록 다운
    function excel_down_(){
        $("#excel_down_form").submit();
        return false;
    }
    function goPage(pgNum) {
        location.href = '?nowPage='+pgNum+'&search_status=<?=$search_status?>&stop_yn=<?=$stop_yn?>&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>&search_key=<?=$search_key?>';
    }
    function payment_save(fm) {
        if(confirm('상태를 변경하시겠습니까?')) {
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
        if(confirm('삭제하시겠습니까?')) {
            $.ajax({
            type:"POST",
            url:"/admin/ajax/payment_delete.php",
            data:{
                no:no
            },
            success:function(data){
                alert('정확히 삭제되었습니다.');
                location.reload();
            }
        })
        }
    }
    $(function() {
        var contHeaderH = $(".main-header").height();
        var navH = $(".navbar").height();
        if(navH != contHeaderH)
            contHeaderH += navH - 50;
        $(".content-wrapper").css("margin-top",contHeaderH);
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 196;
        if(height < 375)
            height = 375;
        $(".box-body").css("height",height);
    });
</script>
<style>
    input, select, textarea {
        vertical-align: middle;
        border: 1px solid #CCC;
    }
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
    .wrapper{height:100%;overflow:auto;}
    .content-wrapper{min-height : 80% !important;}
    .box-body{overflow:auto;padding:0px !important}
</style>
<div class="wrapper">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>판매자정산관리<small>판매자 정산을 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">판매자정산관리</li>
            </ol>
        </section>
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
            <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />
            <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />
            <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />
        </form>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='make_balance_item.php?search_year=<?php echo $search_year;?>&search_month=<?php echo $search_month;?>'"><i class="fa"></i> 정산생성</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="go_item_list();return false;"><i class="fa"></i> 상품결제배당</button>
                    <form method="get" name="search_form" id="search_form " class="form-inline">
                        <div class="box-tools">
                            <div class="input-group"  >
                                <div class="form-group">
                                    <input type="text" style="height: 30px" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST[search_start_date]?>"/> ~
                                    <input type="text" style="height: 30px" name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST[search_end_date]?>"/>
                                </div>
                                <div class="form-group">
                                    <input type="text" style="margin-left:5px" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="이름/아이디/상품명">
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
                                <col width="200px">
                                <col width="200px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="200px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>번호</th>
                                    <th>판매자<br>아이디</th>
                                    <th>판매자<br>이름</th>
                                    <th>전화번호</th>
                                    <th>상품명</th>
                                    <th>결제일</th>
                                    <th>정산일</th>
                                    <th>결제액</th>
                                    <th>정산액</th>
                                    <th>지급액</th>
                                    <th>지급여부</th>
                                    <th>지급일</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_key ? " AND (b.mem_id LIKE '%".$search_key."%' or b.mem_name like '%".$search_key."%' or c.item_name like '%".$search_key."%') " : null;
                                if($search_start_date && $search_end_date)
                                    $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
                                $order = $order?$order:"desc";
                                $query = "SELECT SQL_CALC_FOUND_ROWS b.mem_id, b.mem_name, b.mem_phone, c.price ,c.balance_date,c.item_name,c.pay_date,c.regdate,c.share_per,c.bid,c.balance_confirm_date,c.balance_yn
                                            FROM Gn_Item_Pay_Result_Balance c
                                            INNER JOIN Gn_Member b
                                            on b.mem_id = c.seller_id
                                          WHERE c.gwc_cont_pay=0 $searchStr";
                                $res	    = mysql_query($query);
                                $totalCnt	=  mysql_num_rows($res);
                                $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY c.bid DESC $limitStr ";
                                $i = 1;
                                $query .= "$orderQuery";
                                $res = mysql_query($query);
                                while($row = mysql_fetch_array($res)) {?>
                                    <tr>
                                        <td><?=$number--?></td>
                                        <td><?=$row['mem_id']?></td>
                                        <td><?=$row['mem_name']?></td>
                                        <td>
                                            <?=str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row['mem_phone']):$row['sendnum']?>
                                        </td>
                                        <td><?=$row['item_name']?></td>
                                        <td><?=$row['pay_date']?></td>
                                        <td><?=$row['regdate']?></td>
                                        <td><?=$row['price']?> 원</td>
                                        <td><?=$row['price'] * $row['share_per']/100?> 원</td>
                                        <td><?=$row['balance_yn'] == "Y"?$row['price'] * $row['share_per']/100:0?> 원</td>
                                        <td>
                                            <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="ajax/payment_item_balance_save.php">
                                                <input type="hidden" name="bid" value="<?php echo $row['bid']?>" >
                                                <input type="hidden" name="balance_date" value="<?php echo $search_year."".$search_month?>" >
                                                <input type="hidden" name="search_year" value="<?php echo $search_year?>" >
                                                <input type="hidden" name="search_month" value="<?php echo $search_month?>" >
                                                <select name="end_status">
                                                    <option value="N" <?php echo $row['balance_yn'] == "N"?"selected":""?>>지급대기</option>
                                                    <option value="Y" <?php echo $row['balance_yn'] == "Y"?"selected":""?>>지급완료</option>
                                                </select>
                                                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="payment_save('#ssForm<?=$i?>');return false;"><i class="fa fa-download"></i> 변경</button>
                                            </form>
                                        </td>
                                        <td><?=$row['balance_confirm_date']?></td>
                                    </tr>
                                    <?
                                    $i++;
                                }
                                $excel_sql=$query;
                                $excel_sql=str_replace("'","`",$excel_sql);
                                if($i == 1) {?>
                                    <tr>
                                        <td colspan="11" style="text-align:center;background:#fff">등록된 내용이 없습니다.</td>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                    <?=drawPagingAdminNavi($totalCnt, $nowPage);?>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div><!--wrapper-->
<!--link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script-->                
