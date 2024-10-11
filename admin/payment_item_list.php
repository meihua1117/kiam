<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/rlatjd_admin.js"></script>
<script>
    function go_balance_list(){
        location.href = "payment_item_balance_list.php";
    }
    //주소록 다운
    function excel_down_(){
        $("#excel_down_form").submit();
        return false;
    }
    function goPage(pgNum) {
        location.href = '?nowPage='+pgNum+'&search_status=<?=$search_status?>&stop_yn=<?=$stop_yn?>&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>&search_key=<?=$search_key?>&settle_type=<?=$settle_type?>';
    }
    function payment_save(fm) {
        if(confirm('상태를 변경하시겠습니까?')) {
            $(fm).submit();
            return false;
        }
    }
    function payment_percent_save(fm){
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
    $(function(){
        $('.check').on("click",function(){
            if($(this).prop("id") == "check_all_member"){
                if($(this).prop("checked"))
                    $('.check').prop("checked",true);
                else
                    $('.check').prop("checked",false);
            }else if($(this).prop("id") == "check_one_member"){
                if(!$(this).prop("checked"))
                    $('#check_all_member').prop("checked",false);
            }
        });
    });
    function deleteMultiRow() {
        if(confirm('삭제하시겠습니까?')) {
            var check_array = $("#example1").children().find(".check");
            var no_array = [];
            var index = 0;
            check_array.each(function(){
                if($(this).prop("checked") && $(this).val() > 0)
                    no_array[index++] = $(this).val();
            });

            $.ajax({
            type:"POST",
            url:"/admin/ajax/delete_func.php",
            dataType:"json",
            data:{delete_name:"payment_item", id:no_array.toString()},
            success: function(data){
                console.log(data);
                if(data == 1){
                    alert('삭제 되었습니다.');
                    window.location.reload();
                }
            }
        })
        }
    }
    function delete_payment(id){
        if(confirm("삭제하시겠습니까?")){
            $.ajax({
                type:"POST",
                url:"/admin/ajax/delete_func.php",
                dataType:"json",
                data:{delete_name:"payment_item", id:id},
                success: function(data){
                    console.log(data);
                    if(data == 1){
                        alert('삭제 되었습니다.');
                        window.location.reload();
                    }
                }
            })
        }
        else{
            return;
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
            <h1>상품결제배당관리<small>상품결제 및 배당을 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">상품결제배당관리</li>
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
                    <?
                    if($_SESSION['one_member_admin_id'] != "onlyonemaket"){
                        //if($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) {}
                        //else
                        {?>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_item_payment_down.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
                        <?}
                    }?>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="go_balance_list();return false;"><i class="fa"></i> 판매자정산관리</button>
                    <form method="get" name="search_form" id="search_form " class="form-inline">
                        <div class="box-tools">
                            <div class="input-group"  >
                                <div class="form-group">
                                    <input type="text" style="height: 30px" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST[search_start_date]?>"/> ~
                                    <input type="text" style="height: 30px" name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST[search_end_date]?>"/>
                                </div>
                                <div class="form-group">
                                    <input type="text" style="margin-left:5px" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/상품명">
                                </div>
                                <div class="form-group">
                                    <select name="settle_type" style="height: 30px;">
                                        <option value="A" <?php echo $_REQUEST['settle_type'] == "A"?"selected":""?>>전체</option>
                                        <option value="P" <?php echo $_REQUEST['settle_type'] == "P"?"selected":""?>>포인트결제</option>
                                        <option value="C" <?php echo $_REQUEST['settle_type'] == "C"?"selected":""?>>카드결제</option>
                                    </select>
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
                                <col width="60px">
                                <col width="60px">
                                <col width="100px">
                                <col width="100px">
                                <col width="80px">
                                <col width="140px">
                                <col width="160px">
                                <col width="160px">
                                <col width="160px">
                                <col width="50px">
                                <col width="50px">
                                <col width="50px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check" id="check_all_member" value="0"><br>번호</th>
                                    <th>소속</th>
                                    <th>추천인</th>
                                    <th>아이디</th>
                                    <th>상품명</th>
                                    <th>금액</th>
                                    <th>건수</th>
                                    <th>이름</th>
                                    <th>전화번호</th>
                                    <th>결제종류</th>
                                    <th>상태</th>
                                    <th>결제일</th>
                                    <th>구매<br>확인</th>
                                    <th>판매<br>확인</th>
                                    <th>배당<br>비율</th>
                                    <th>포인트<br>비율</th>
                                    <th><button class="btn btn-primary" onclick="deleteMultiRow();return false;"><i class="fa"></i>삭제</button></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_key ? " AND (a.buyer_id LIKE '%".$search_key."%' or a.item_name like '%".$search_key."%') " : null;
                                if($search_start_date && $search_end_date)
                                    $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
                                if($settle_type == "P")
                                    $searchStr .= " AND (a.point_val = 1)";
                                else if($settle_type == "C")
                                    $searchStr .= " AND a.point_val = 0";
                                $order = $order?$order:"desc";
                                $query = "SELECT SQL_CALC_FOUND_ROWS * FROM Gn_Item_Pay_Result a WHERE (a.point_val=0 or (a.point_val=1 and a.site is not null and a.type='servicebuy')) and gwc_cont_pay=0 $searchStr";
                                $excel_sql=$query;
                                $excel_sql=str_replace("'","`",$excel_sql);
                                $res	    = mysqli_query($self_con,$query);
                                $totalCnt	=  mysqli_num_rows($res);
                                $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY a.no DESC $limitStr ";
                                $i = 1;
                                $query .= "$orderQuery";
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {
                                    $sql_mem_data = "select mem_id, site, recommend_id, mem_name, mem_phone,site_iam from Gn_Member where mem_id='{$row[buyer_id]}'";
                                    $res_mem_data = mysqli_query($self_con,$sql_mem_data);
                                    $row_mem_data = mysqli_fetch_array($res_mem_data);
                                    if($row['pay_method'] == "CARD" || $row['pay_method'] == "BANK"){
                                        $pay_method = $pay_type[$row['pay_method']];
                                    }
                                    else{
                                        $pay_method = "포인트결제";
                                    }
                                ?>
                                    <tr align="center">
                                        <td><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['no']?>"><br><?=$number--?></td>
                                        <td><?=$row_mem_data['site'].'<br>'.$row_mem_data['site_iam']?></td>
                                        <td><?=$row_mem_data['recommend_id']?></td>
                                        <td><?=$row_mem_data['mem_id']?></td>
                                        <td><?=$row['item_name']?></td>
                                        <td><?=$row['item_price']?></td>
                                        <td><?=$row['contents_cnt']?></td>
                                        <td><?=$row_mem_data['mem_name']?></td>
                                        <td><?=str_replace("-", "",$row_mem_data['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row_mem_data['mem_phone']):$row['sendnum']?></td>
                                        <td><?=$pay_method?></td>
                                        <td>
                                            <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="ajax/item_payment_save.php">
                                                <input type="hidden" name="no" value="<?php echo $row['no']?>" >
                                                <input type="hidden" name="price" id="price_<?=$i?>" value="<?=($row['item_price'])?>" >
                                                <!--select name="payment_day">
                                                    <option value="" >결제일</option>
                                                    <option value="5" <?php echo $row['payment_day'] == "5"?"selected":""?>>5</option>
                                                    <option value="10" <?php echo $row['payment_day'] == "10"?"selected":""?>>10</option>
                                                    <option value="15" <?php echo $row['payment_day'] == "15"?"selected":""?>>15</option>
                                                    <option value="20" <?php echo $row['payment_day'] == "20"?"selected":""?>>20</option>
                                                    <option value="25" <?php echo $row['payment_day'] == "25"?"selected":""?>>25</option>
                                                    <option value="30" <?php echo $row['payment_day'] == "30"?"selected":""?>>30</option>
                                                </select-->
                                                <select name="pay_status">
                                                    <option value="N" <?php echo $row['pay_status'] == "N"?"selected":""?>>결제대기</option>
                                                    <option value="Y" <?php echo $row['pay_status'] == "Y"?"selected":""?>>결제완료</option>
                                                    <option value="A" <?php echo $row['pay_status'] == "A"?"selected":""?>>후불결제</option>
                                                    <option value="E" <?php echo $row['pay_status'] == "E"?"selected":""?>>기간만료</option>
                                                </select>
                                                <button class="btn btn-primary" onclick="payment_save('#ssForm<?=$i?>');return false;"><i class="fa"></i> 변경</button>
                                            </form>
                                        </td>
                                        <td><?=$row['pay_date']?> </td>
                                        <td><?=$row['apply_buyer_date']?> </td>
                                        <td><?=$row['apply_seller_date']?> </td>
                                        <td>
                                            <form method="post" name="percentForm<?=$i?>" id="percentForm<?=$i?>" action="ajax/item_payment_percent_save.php">
                                                <input type="hidden" name="no" value="<?php echo $row['no']?>" >
                                                <input type = "number" name = "pay_percent" value = "<?=$row['pay_percent']?>" style="width:100%">
                                                <button class="btn btn-primary" onclick="payment_percent_save('#percentForm<?=$i?>');return false;"><i class="fa"></i> 변경</button>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="post" name="percentForm_point<?=$i?>" id="percentForm_point<?=$i?>" action="ajax/item_payment_percent_save.php">
                                                <input type="hidden" name="no" value="<?php echo $row['no']?>" >
                                                <input type = "text" name = "point_percent" value = "<?=$row['point_percent']?>" style="width:100%">
                                                <button class="btn btn-primary" onclick="payment_percent_save('#percentForm_point<?=$i?>');return false;"><i class="fa"></i> 변경</button>
                                            </form>
                                        </td>
                                        <td><a href="javascript:delete_payment(<?=$row['no']?>)">삭제</a></td>
                                    </tr>
                                <?$i++;
                                }
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
<!--<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>               --> 
