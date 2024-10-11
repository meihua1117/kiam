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
<script>
//주소록 다운
function excel_down_(){
	$("#excel_down_form").submit();
	return false;
}

function goPage(pgNum) {
   location.href = '?nowPage='+pgNum+'&search_status=<?=$search_status?>&stop_yn=<?=$stop_yn?>&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>&search_key=<?=$search_key?>&search_reco=<?=$search_reco?>&search_site=<?=$search_site?>&search_price=<?=$search_price?>&search_year=<?=$search_year?>&search_month=<?=$search_month?>';
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
#open_recv_div li{list-style: none;}
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
            <h1>솔루션카드정기결제관리<small>솔루션 카드 정기 결제를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">솔루션카드정기결제관리</li>
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
                    if($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) {}
                    else {}
                }?>
                    <form method="get" name="search_form" id="search_form " class="form-inline">
                        <div class="box-tools">
                            <div class="input-group"  >
                                <select name="search_year" class="form-inline" style="padding: 5px;">
                                    <option value=""  <?php echo isset($_GET[search_year])?"":"selected"?>>전체</option>
                                    <?for($i=$search_year-4;$i<=$search_year;$i++){?>
                                        <option value="<?=$i?>"  <?php echo $i==$_GET[search_year]?"selected":""?>><?=$i?></option>
                                    <?}?>
                                </select>
                                <select name="search_month" class="form-inline" style="padding: 5px;">
                                    <option value=""  <?php echo isset($_GET[search_month])?"":"selected"?>>전체</option>
                                    <?for($i=1;$i<13;$i++){?>
                                        <option value="<?=$i?>" <?php echo sprintf("%02d",$i)==$_GET[search_month]?"selected":""?>><?=$i?></option>
                                    <?}?>
                                </select>
                                <div class="form-group">
                                    <input type="text" style="margin-left:5px;width: 100px;" name="search_price" id="search_price" class="form-control input-sm pull-right" placeholder="결제금액" value="<?=$search_price?>">
                                    <!-- <input type="text" style="margin-left:5px;width: 100px;" name="search_iam" id="search_iam" class="form-control input-sm pull-right" placeholder="IAM소속"> -->
                                    <input type="text" style="margin-left:5px;width: 100px;" name="search_site" id="search_site" class="form-control input-sm pull-right" placeholder="셀링소속" value="<?=$search_site?>">
                                    <input type="text" style="margin-left:5px;width: 100px;" name="search_reco" id="search_reco" class="form-control input-sm pull-right" placeholder="추천인" value="<?=$search_reco?>">
                                    <input type="text" style="margin-left:5px;width: 100px;" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="이름/아이디" value="<?=$search_key?>">
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
                    <tbody id="phone_list">
                    </tbody>
                </table>
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
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>번호</th>
                                    <th>아이디</th>
                                    <th>이름</th>
                                    <th>이용상품</th>
                                    <th>금액</th>
                                    <th>결제상태</th>
                                    <th>메세지</th>
                                    <th>결제일</th>
                                    <th>수동신청</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_key ? " AND (b.buyer_id LIKE '%".$search_key."%' or b.VACT_InputName like '%".$search_key."%' ) " : null;
                                $searchStr .= $search_reco ? " AND b.share_id = '".$search_reco."'" : null;
                                $searchStr .= $search_site ? " AND b.branch_share_id = '".$search_site."'" : null;
                                // $searchStr .= $search_iam ? " AND c.site_iam = '".$search_iam."'" : null;
                                $searchStr .= $search_price ? " AND b.TotPrice = '".$search_price."'" : null;
                                if(isset($_GET[search_year]) && $_GET[search_year] && isset($_GET[search_month]) && $_GET[search_month])
                                $searchStr .=" and a.regdate like '%$search_year-$search_month%' ";
                                if($search_start_date && $search_end_date) {
                                    $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
                                }
                                if($search_leb ) {
                                    $searchStr .= " AND mem_leb='$search_leb'";
                                }
                                if($search_status) {
                                    $searchStr .= " AND end_status='$search_status'";
                                }
                                if($stop_yn) {
                                    $searchStr .= " AND stop_yn='$stop_yn'";
                                }
                                $order = $order?$order:"desc";
                                $query = "SELECT SQL_CALC_FOUND_ROWS * FROM tjd_pay_result_month a INNER JOIN tjd_pay_result b on b.orderNumber =a.pay_idx and b.orderNumber != ''
                                          WHERE 1=1 $searchStr";
                                $excel_sql=$query;
                                $excel_sql=str_replace("'","`",$excel_sql);
                                $res	    = mysqli_query($self_con,$query);
                                $totalCnt	=  mysqli_num_rows($res);
                                $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY a.idx DESC $limitStr ";
                                $i = 1;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {?>
                                    <tr>
                                        <td><?=$number--?></td>
                                        <td><?=$row['buyer_id']?></td>
                                        <td><?=$row['VACT_InputName']?></td>
                                        <td><?=$row['member_type']?></td>
                                        <td><?=number_format($row['TotPrice'])?></td>
                                        <td><?=$row['pay_yn']?></td>
                                        <td><?=$row['msg']?></td>
                                        <td><?=$row['date']?><BR><?=$row['regdate']?></td>
                                        <td>
                                            <?if($row['pay_yn'] == "N") {?>
                                                <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="/allat/mp/allat_approval_extra_manual.php?ORDER_NO=<?=$row['order_number']?>" target="_blank">
                                                    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;" ><i class="fa fa-download"></i> 신청</button>
                                                </form>
                                            <?}?>
                                        </td>
                                    </tr>
                                    <?
                                    $i++;
                                }
                                if($i == 1) {?>
                                    <tr>
                                        <td colspan="11" style="text-align:center;background:#fff">
                                            등록된 내용이 없습니다.
                                        </td>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
                <div class="row">
                    <div class="col-sm-5">
                        <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                    </div>
                    <div class="col-sm-7">
                        <?echo drawPagingAdminNavi($totalCnt, $nowPage);?>
                    </div>
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div>
<script language="javascript">
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
function loginGo(mem_id, mem_pw, mem_code) {
    $('#one_id').val(mem_id);
    $('#mem_pass').val(mem_pw);
    $('#mem_code').val(mem_code);
    $('#login_form').submit();
}
$(function(){
    $('.switch').on("change", function() {
        var no = $(this).find("input[type=checkbox]").val();
        var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
		$.ajax({
			 type:"POST",
			 url:"/admin/ajax/payment_stop.php",
			 data:{
				 no:no
				 },
			 success:function(data){
			    //alert('신청되었습니다.');location.reload();
			 }
			})    
    });    
});
</script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                
