<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
//extract($_GET);
// 오늘날짜
$date_today=date("Y-m")."-01 00:00:00";
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/rlatjd_admin.js"></script>
<script>
    //주소록 다운
    function excel_down_(){
        $("#excel_down_form").submit();
        return false;
    }
    function goPage(pgNum) {
        location.href = '?nowPage='+pgNum+'&search_status=<?=$search_status?>'+
                        '&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>'+
                        '&search_key=<?=$search_key?>&prod_name=<?=$prod_name?>&identifier=<?=$identifier?>';
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
                url:"/admin/ajax/gwc_order_save.php",
                data:{
                    type:"delete_change_list",
                    no:no_array.toString()
                },
                success:function(data){
                    alert('정확히 삭제되었습니다.');
                    location.reload();
                }
            });
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

    function save_delivery(id){
        var delivery = $("select[name=delivery_type_"+id+"]").val();
        var delivery_no = $("input[name=delivery_no_"+id+"]").val();
        $.ajax({
            type:"POST",
            url:"ajax/gwc_order_save.php",
            dataType : "json",
            data:{
                type : "delivery_save",
                delivery : delivery,
                delivery_no : delivery_no,
                id : id
            },
            success:function(data){
                location.reload();
            }
        });
    }

    function show_delivery_link(delivery_id){
        if(delivery_id == ''){
            alert('배송회사를 선택해 주세요.');
            return;
        }
        $.ajax({
            type:"POST",
            url:"ajax/gwc_order_save.php",
            dataType : "json",
            data:{
                type : "get_delivery_link",
                delivery_id : delivery_id
            },
            success:function(data){
                window.open(data.link, '_blank');
            }
        });
    }

    function show_order_page(link){
        window.open(link, '_blank');
    }

    function show_detail_prod(str){
        $("#state_detail").text(str);
        $("#show_paper_comment").modal('show');
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
            <h1>굿마켓 취소/교환/반품관리<small>굿마켓 상품 취소/교환/반품관리를 합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">굿마켓 취소/교환/반품관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <?if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                        <div>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_payment_down.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow();return false;"> 선택삭제</button>
                        </div>
                    <?}?>
                    <form method="get" name="search_form" id="search_form " class="form-inline">
                        <div class="box-tools">
                            <div class="input-group"  >
                                <div class="form-group">
                                    <input type="date" style="height: 30px" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST[search_start_date]?>" multiple/> ~
                                    <input type="date" style="height: 30px" name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST[search_end_date]?>"/>
                                </div>
                                <div class="form-group">
                                    <select name="search_status" class="form-control input-sm " style="margin-right:5px">
                                        <option value="">주문변경</option>
                                        <option value="1" <?if($_REQUEST['search_status'] == "1") echo "selected"?>>취소</option>
                                        <option value="2" <?if($_REQUEST['search_status'] == "2") echo "selected"?>>교환</option>
                                        <option value="3" <?if($_REQUEST['search_status'] == "3") echo "selected"?>>환불</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="search_key" id="search_key"   style="width:100px" class="form-control input-sm pull-right" placeholder="이름" value="<?=$search_key?>">
                                    <input type="text" name="identifier" id="identifier" style="width:100px" class="form-control input-sm pull-right" placeholder="아이디" value="<?=$identifier?>">
                                    <input type="text" name="prod_name" id="prod_name" style="width:100px" class="form-control input-sm pull-right" placeholder="상품명" value="<?=$prod_name?>">
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
                                <col width="100px">
                                <col width="130px">
                                <col width="130px">
                                <col width="130px">
                                <col width="100px">
                                <col width="100px">
                                <col width="60px">
                                <col width="100px">
                                <col width="150px">
                                <col width="150px">
                                <col width="150px">
                                <col width="200px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check" id="check_all_member" value="0">번호</th>
                                    <th>주문<br>변경</th>
                                    <th>접수일자<br>접수번호</th>
                                    <th>주문<br>변경이유</th>
                                    <th>결제<br>상태</th>
                                    <th>진행<br>상태</th>
                                    <th>처리<br>일시</th>
                                    <th>카드취소<br>영수증</th>
                                    <th>주문일시<br>주문번호</th>
                                    <th>주문<br>상품</th>
                                    <th>판매자명<br>판매자ID</th>
                                    <th>구매자명<br>구매자ID</th>
                                    <th>구매금액<br>배송비</th>
                                    <th>이용포인트<br>카드결제</th>
                                    <th>총결제액</th>
                                    <th>배송회사</th>
                                    <th>운송장번호</th>
                                    <th>반품입고</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $identifier ? " AND (mem_id LIKE '%".$identifier."%')" : null;
                                $searchStr .= $search_key ? "AND ( order_mem_name like '%".$search_key."%')" : null;
                                $searchStr .= $prod_name ? " AND b.contents_title = '$prod_name' " : null;

                                if($search_start_date && $search_end_date)
                                    $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
                                if($search_status)
                                    $searchStr .= " AND prod_status='$search_status'";

                                if($prod_name){
                                    $join_str = "INNER JOIN Gn_Iam_Contents_Gwc b on b.idx =a.contents_idx ";
                                }
                                else{
                                    $join_str = "";
                                }

                                $order = $order?$order:"desc";
                                $query = "SELECT SQL_CALC_FOUND_ROWS a.*$sel_str FROM Gn_Gwc_Order a $join_str WHERE a.prod_state!=0 $searchStr";
                                $excel_sql=$query;
                                $excel_sql=str_replace("'","`",$excel_sql);
                                $res	    = mysqli_query($self_con,$query);
                                $totalCnt	=  mysqli_num_rows($res);
                                $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY a.id DESC $limitStr ";
                                $i = 1;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {
                                    $show_link = "http://kiam.kr/iam/gwc_order_pay.php?contents_idx=".$row['contents_idx']."&contents_cnt=".$row['contents_cnt']."&contents_price=".$row['contents_price']."&contents_salary=".$row['salary_price']."&seller_id=".$row['seller_id']."&order_option=".$row['order_option']."&admin=Y&mem_id=".$row['mem_id']."&use_point_val=".$row['use_point']."&pay_method=".$row['payMethod'];

                                    $sql_tjd = "select * from tjd_pay_result where no='{$row[tjd_idx]}'";
                                    $res_tjd = mysqli_query($self_con,$sql_tjd);
                                    $row_tjd = mysqli_fetch_array($res_tjd);

                                    if($row_tjd['yutong_name'] == 1){
                                        $yt_name = "웹빙몰";
                                    }
                                    else{
                                        $yt_name = $row_tjd['provider_name']."/\n온리원";
                                    }

                                    $sql_buyer = "select mem_cash from Gn_Member where mem_id='{$row['mem_id']}'";
                                    $res_buyer = mysqli_query($self_con,$sql_buyer);
                                    $row_buyer = mysqli_fetch_array($res_buyer);

                                    $sql_seller = "select mem_name, mem_cash from Gn_Member where mem_id='{$row[seller_id]}'";
                                    $res_seller = mysqli_query($self_con,$sql_seller);
                                    $row_seller = mysqli_fetch_array($res_seller);
                                    $seller_data = $row_seller[0]."/\n".$row[seller_id];

                                    $sql_cont_data = "select idx, contents_sell_price, send_provide_price, send_salary_price, contents_img, contents_title from Gn_Iam_Contents_Gwc where idx='$row[contents_idx]'";
                                    $res_cont_data = mysqli_query($self_con,$sql_cont_data);
                                    $row_cont_data = mysqli_fetch_array($res_cont_data);

                                    $price_data1 = ($row['contents_price'])."/\n".$row['salary_price'];

                                    if(strpos($row_cont_data['contents_img'], ",") !== false){
                                        $img_link1 = explode(",", $row_cont_data['contents_img']);
                                        $img_link = trim($img_link1[0]);
                                    }
                                    else{
                                        $img_link = $row_cont_data['contents_img'];
                                    }

                                    $price_data2 = $row['use_point']."/\n".($row_tjd[TotPrice] * 1 - $row['use_point'] * 1);

                                    $sql_price_all = "select SUM(TotPrice) as all_price from tjd_pay_result where gwc_cont_pay=1 and buyer_id='{$row['buyer_id']}'";
                                    $res_price_all = mysqli_query($self_con,$sql_price_all);
                                    $row_price_all = mysqli_fetch_array($res_price_all);

                                    $month_money = $row_price_month[0]?$row_price_month[0]:"0";
                                    $money_data = $month_money."/\n".$row_price_all[0];

                                    $prod_state = '주문';
                                    if($row[prod_state] == '1'){
                                        $prod_state = "취소";
                                    }
                                    else if($row[prod_state] == '2'){
                                        $prod_state = "환불";
                                    }
                                    else if($row[prod_state] == '3'){
                                        $prod_state = "교환";
                                    }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" class="check" id="check_one_member" value="<?=$row['id']?>"><?=$number--?></td>
                                        <td><?=$prod_state?></td>
                                        <td><?=$row['prod_req_date']?>/<br><?=substr($row['prod_req_no'], -7)?></td>
                                        <td><a href="javascript:show_detail_prod('<?=$row[state_detail]?>')"><?=cut_str($row[state_detail], 10)?></a></td>
                                        <td>
                                            <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="ajax/gwc_payment_save.php">
                                                <input type="hidden" name="no" value="<?=$row_tjd['no']?>" >
                                                <input type="hidden" name="price" id="price_<?=$i?>" value="<?=$row_tjd[TotPrice]?>" >
                                                <input type="hidden" name="type" id="type_<?=$i?>" value="main">
                                                <select name="end_status"  onchange="payment_save('#ssForm<?=$i?>');return false;" style="font-size:11px;">
                                                    <option value="N" <?php echo $row_tjd['end_status'] == "N"?"selected":""?>>결제대기</option>
                                                    <option value="Y" <?php echo $row_tjd['end_status'] == "Y"?"selected":""?>>결제완료</option>
                                                    <option value="A" <?php echo $row_tjd['end_status'] == "A"?"selected":""?>>후불결제</option>
                                                    <option value="E" <?php echo $row_tjd['end_status'] == "E"?"selected":""?>>기간만료</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <form method="post" name="changeForm<?=$i?>" id="changeForm<?=$i?>" action="ajax/gwc_payment_save.php">
                                                <input type="hidden" name="no" value="<?=$row['id']?>" >
                                                <input type="hidden" name="use_point" id="use_point_<?=$i?>" value="<?=$row['use_point']?>" >
                                                <input type="hidden" name="type" id="type_<?=$i?>" value="prod_state_change">
                                                <select name="prod_req_state"  onchange="payment_save('#changeForm<?=$i?>');return false;" style="font-size:11px;">
                                                    <option value="0" <?php echo $row['prod_req_state'] == "0"?"selected":""?> title="진행상태">진행상태</option>
                                                    <option value="1" <?php echo $row['prod_req_state'] == "1"?"selected":""?> title="취소완료">취소완료</option>
                                                    <option value="2" <?php echo $row['prod_req_state'] == "2"?"selected":""?> title="카드사환불예정">환불예정</option>
                                                    <option value="3" <?php echo $row['prod_req_state'] == "3"?"selected":""?> title="교환예정">교환예정</option>
                                                    <option value="4" <?php echo $row['prod_req_state'] == "4"?"selected":""?> title="교환완료">교환완료</option>
                                                    <option value="5" <?php echo $row['prod_req_state'] == "5"?"selected":""?> title="환불완료">환불완료</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td><?=$row[change_prod_req_date]?></td>
                                        <td>
                                            <form method="post" name="receiptForm<?=$i?>" id="receiptForm<?=$i?>" action="ajax/gwc_payment_save.php" enctype="multipart/form-data">
                                                <input type="hidden" name="no" value="<?=$row['id']?>" >
                                                <input type="hidden" name="type" id="type_<?=$i?>" value="reciptimg">
                                                <input type="file" name="receipt_file" id="receipt_file" style="width: 100px;">
                                                <?if($row[prod_req_img]){?>
                                                <img class="zoom" src="http://kiam.kr/<?=$row[prod_req_img]?>" style="width:50px;">
                                                <?}?>
                                                <button onclick="window.receiptForm<?=$i?>.submit();return false;" style="font-size: 11px;color: white;background-color: black;padding: 0px 5px;">저장</button>
                                            </form>
                                        </td>
                                        <td><?=$row['reg_date']?>/<br><?=substr($row['pay_order_no'], -7)?></td>
                                        <td><img class="zoom" src="<?=$img_link?>" style="width:50px;" onclick="show_order_page('<?=$show_link?>')"><br><?=$row_cont_data['contents_title']?></td>
                                        <td><?=$seller_data?></td>
                                        <td><?=$row['order_mem_name']."/\n".$row['mem_id']?></td>
                                        <td><?=$price_data1?></td>
                                        <td><?=$price_data2?></td>
                                        <td><?=$row_tjd[TotPrice]?></td>
                                        <td>
                                            <select name="delivery_type_<?=$row['id']?>" style="font-size:11px;">
                                                <option value="">배송회사</option>
                                                <?
                                                $sql_delivery = "select * from delivery_list";
                                                $res_delivery = mysqli_query($self_con,$sql_delivery);
                                                while($row_delivery = mysqli_fetch_array($res_delivery)){
                                                ?>
                                                <option value="<?=$row_delivery['id']?>" title="<?=$row_delivery[delivery_name]?>" <?=$row_delivery['id']==$row[delivery]?'selected':''?>><?=cut_str($row_delivery[delivery_name], 5)?></option>
                                                <?}?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="delivery_no_<?=$row['id']?>" value="<?=$row[delivery_no]?>" style="width:80px;font-size: 11px;">
                                            <button onclick="show_delivery_link('<?=$row[delivery]?>');return false;" style="font-size: 11px;color: white;background-color: black;padding: 0px 5px;">배송<br>조회</button>
                                            <button onclick="save_delivery('<?=$row['id']?>');return false;" style="font-size: 11px;color: white;background-color: black;padding: 0px 5px;">저장</button>
                                        </td>
                                        <td>
                                            <form method="post" name="storageForm<?=$i?>" id="storageForm<?=$i?>" action="ajax/gwc_payment_save.php">
                                                <input type="hidden" name="no" value="<?=$row['id']?>" >
                                                <input type="hidden" name="type" id="type_<?=$i?>" value="prod_storage_change">
                                                <select name="storage_state" onchange="payment_save('#storageForm<?=$i?>');return false;" style="font-size:11px;">
                                                    <option value="0" <?=!$row[prod_storage]?"selected":""?>>입고대기</option>
                                                    <option value="1" <?=$row[prod_storage]?"selected":""?>>입고완료</option>
                                                </select>
                                            </form>
                                        </td>
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
    <div id="show_paper_comment" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:500px">
            <!-- Modal content-->
            <div class="modal-content"  style="border-radius:5px;">
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
    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="excel_sql" value="<?=base64_encode($excel_sql)?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
</div><!--wrapper-->
