<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
//주소록 다운
function excel_down_(){
	$("#excel_down_form").submit();
	return false;
}
  function goPage(pgNum) {
   location.href = '?<?=$nowPage?>&nowPage='+pgNum+'&search_leb=<?=$search_leb?>&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>&search_key=<?=$search_key?>';
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
function deleteMultiRow() {
    if(confirm('삭제하시겠습니까?')) {
        var check_array = $("#example1").children().find(".check_no");
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
            data:{admin:1, delete_name:"payment_per_list", id:no_array.toString()},
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
            data:{admin:1, delete_name:"payment_per_list", id:id},
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
</script>   
<style>
#open_recv_div li{list-style: none;}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
user agent stylesheet
input[type="checkbox" i] {
    background-color: initial;
    cursor: default;
    -webkit-appearance: checkbox;
    box-sizing: border-box;
    margin: 3px 3px 3px 4px;
    padding: initial;
    border: initial;
}
input:checked + .slider {
    background-color: #2196F3;
}
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}
input:checked + .slider {
    background-color: #2196F3;
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
input:checked + .slider:before {
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
            <h1>상품 배당 비율관리<small>상품 배당비율을 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">상품비율관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <form method="get" name="search_form" id="search_form" class="form-inline">
                        <div class="box-tools">
                            <div class="input-group">
                                <div class="form-group">
                                    <select name="search_leb" class="form-control" >
                                        <option value="">전체</option>
                                        <option value="50">사업자</option>
                                        <option value="22">이용자</option>
                                    </select>
                                </div>
                                <div class="form-group">  
                                    <input type="date" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST[search_start_date]?>"/> ~
                                    <input type="date"  name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST[search_end_date]?>"/>                  
                                </div>
                                <div class="form-group">
                                    <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="이름/아이디/금액">
                                </div>
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
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
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="180px">
                                <col width="100px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check_no" id="check_all_member" value="0">번호</th>
                                    <th>소속</th>
                                    <th>아이디</th>
                                    <th>이용상품</th>
                                    <th>이름</th>
                                    <th>결제종류</th>
                                    <th>금액</th>
                                    <th>추천인</th>
                                    <th>추천/대리점배당</th>
                                    <th><button class="btn btn-primary" onclick="deleteMultiRow();return false;"><i class="fa"></i>삭제</button></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_key ? " AND (a.buyer_id LIKE '%".$search_key."%' or b.mem_phone like '%".$search_key."%' or b.mem_name like '%".$search_key."%' or b.site like '%".$search_key."%' or a.TotPrice='$search_key') " : null;
                                if($search_start_date && $search_end_date) {
                                    $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
                                } 
                                if($search_leb ) {
                                    $searchStr .= " AND mem_leb='$search_leb'";
                                }
                                $searchStr .= " AND end_status='Y'";
                                $order = $order?$order:"desc"; 		
                                $query = "SELECT SQL_CALC_FOUND_ROWS *, a.share_per FROM tjd_pay_result a 
                                            INNER JOIN Gn_Member b on b.mem_id =a.buyer_id
                                            WHERE 1=1 $searchStr";
                                $res	    = mysql_query($query);
                                $totalCnt	=  mysql_num_rows($res);	
                                $limitStr   = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number		= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                                $orderQuery .= " ORDER BY a.no DESC $limitStr ";
                                $i = 1;
                                $query .= "$orderQuery";
                                $res = mysql_query($query);
                                while($row = mysql_fetch_array($res)) {
                            ?>
                                <tr>
                                    <td><input type="checkbox" class="check_no" id="check_one_member" name="" value="<?=$row['no']?>"><?=$number--?></td>
                                    <td><?=$row['site']?></td>
                                    <td><?=$row['mem_id']?></td>
                                    <td><?=$row['member_type']?></td>
                                    <td><?=$row['mem_name']?></td>
                                    <td><?=$pay_type[$row[payMethod]]?></td>
                                    <td><?=number_format($row[TotPrice])?>원</td>
                                    <td><?=$row['recommend_id']?></td>
                                    <td>
                                        <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="ajax/payment_per_save.php">
                                        <input type="hidden" name="no" value="<?php echo $row['no']?>" >
                                        <input type="text" name="share_per" id="share_per<?=$i?>" value="<?=$row[share_per]?>"  style="width:70px;">
                                        <input type="text" name="branch_share_per" id="branch_share_per<?=$i?>" value="<?=$row[branch_share_per]?>"  style="width:70px;">
                                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="payment_save('#ssForm<?=$i?>');return false;"><i class="fa fa-download"></i> 변경</button>
                                        </form>
                                    </td>
                                    <td><a href="javascript:delete_payment(<?=$row['no']?>)">삭제</a></td>
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
            </div><!-- /.row -->
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                	<?
                    	echo drawPagingAdminNavi($totalCnt, $nowPage);
                    ?>
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
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
        location = "ajax/payment_delete.php?no="+no;
    }
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
    $('.check_no').on("click",function(){
        if($(this).prop("id") == "check_all_member"){
            if($(this).prop("checked"))
                $('.check_no').prop("checked",true);
            else
                $('.check_no').prop("checked",false);
        }else if($(this).prop("id") == "check_one_member"){
            if(!$(this).prop("checked"))
                $('#check_all_member').prop("checked",false);
        }
    });
});
</script>
      <!-- Footer -->
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
