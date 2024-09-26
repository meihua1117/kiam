<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");
if($_REQUEST['dir'] == "desc"){
    $dir = "asc";
} 	else{
    $dir = "desc";
}
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
//계정 삭제
function deleteRow(idx){
    var msg = confirm('정말로 삭제하시겠습니까?');
    if(msg){
        $.ajax({
            type:"POST",
            url:"/admin/ajax/phone_check.php",
            data:{mode:"del",no:idx},
            dataType:"json",
            success:function(data){
                if(data.result == "success"){
                    alert('삭제되었습니다.');
                    location.reload();
                }else{
                    alert('삭제 실패');    
                }
            },
            error: function(){
                alert('삭제 실패');
            }
        });
    }else{
        return false;
    }
}
function goPage(pgNum) {
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&orderField=<?=$orderField;?>&dir=<?=$dir;?>";
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
    var check_array = $("#example1").children().find(".check");
    var no_array = [];
    var index = 0;
    check_array.each(function(){
        if($(this).prop("checked") && $(this).val() > 0)
            no_array[index++] = $(this).val();
    });

    if(no_array.length == 0){
        alert("삭제할 업체를 선택하세요.");
        return;
    }
    if(confirm('삭제하시겠습니까?')) {
        $.ajax({
            type:"POST",
            url:"/admin/ajax/phone_check.php",
            dataType:"json",
            data:{mode:"del", no:no_array.toString()},
            success: function(data){
                if(data.result == "success"){
                    alert('삭제 되었습니다.');
                    location.reload();
                }else{
                    alert('삭제 실패');
                }
            },
            error: function(){
                alert('삭제 실패');
            }
        })
    }
}
function page_view(mem_id) {
    $('.ad_layer1').lightbox_me({
        centered: true,
        onLoad: function() {
            $.ajax({
                type:"POST",
                url:"/admin/ajax/member_list_page1.php",
                data:{mem_id:mem_id},
                dataType: 'html',
                success:function(data){
                    $('#phone_list').html(data);
                },
                error: function(){
                    alert('로딩 실패');
                }
            });
        }
    });
    $('.ad_layer1').css({"overflow-y":"auto", "height":"300px"});
}
$(function(){
    $('.chkagree').change(function() {
      var id = $(this)[0].id;
      var checkable = $(this)[0].checked?"Y":"N";
      $.ajax({
          type:"POST",
          url:"/admin/ajax/phone_check.php",
          dataType:"json",
          data:{
              mode:"modify_phone_check_status",
              idx:id,
              status:checkable
          },
          success:function(data){
          },
          error: function(){
              alert('변경 실패');
          }
      });
    });
});
function change_type(obj,no){
    var val = $(obj).val();
    $.ajax({
        type:"POST",
        url:"/admin/ajax/phone_check.php",
        dataType:"json",
        data:{
            mode:"modify_phone_check_type",
            idx:no,
            type:val
        },
        success:function(data){
        },
        error: function(){
            alert('변경 실패');
        }
    });
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH/* - $(".content-header").height() - $("#toolbox").height() - 250*/;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
</script>
<style>
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd!important;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
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
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;text-align:center}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
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
            <h1>폰문자인증관리<small>폰문자인증정보를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">분양관리</li>
            </ol>
        </section>
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
                        <th>발송폰</th>
                        <th>설치일자</th>
                    </tr>
                </thead>
                <tbody id="phone_list">
                </tbody>
            </table>
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <form method="get" name="search_form" id="search_form">
                        <div class="box-tools">
                            <div class="input-group" style="width: 250px;">
                                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="검색키워드">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='phone_check_detail.php';return false;"><i class="fa fa-download"></i> 등록</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow()"><i class="fa fa-download"></i> 선택삭제</button>
                </div>
                <div class="col-xs-12" style="padding-bottom:20px">
                    <div class="row">
                        <div class="box">
                            <div class="box-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <colgroup>
                                        <col width="80px">
                                        <col width="120px">
                                        <col width="200px">
                                        <col width="200px">
                                        <col width="100px">
                                        <col width="100px">
                                        <col width="100px">
                                        <col width="100px">
                                        <col width="100px">
                                        <col width="100px">
                                        <col width="200px">
                                        <col width="200px">
                                        <col width="200px">
                                        <col width="200px">
                                        <col width="200px">
                                        <col width="200px">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="check" id="check_all_member" value="0">번호</th>
                                            <th>수정/삭제</th>
                                            <th>소속<br>셀링/IAM</th>
                                            <th>아이디<br>이름</th>
                                            <th>회사명<br>담당자</th>
                                            <th>적용<br>도메인</th>
                                            <th>가입폰</th>
                                            <th>발송폰</th>
                                            <th>기본료</th>
                                            <th>추가비</th>
                                            <th>금월합계</th>
                                            <th>발송건수</th>
                                            <th>API키</th>
                                            <th>인증방식</th>
                                            <th>사용</th>
                                            <th>등록일</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?
                                    $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                    $startPage = $nowPage?$nowPage:1;
                                    $pageCnt = 20;

                                    // 검색 조건을 적용한다.
                                    $searchStr .= $search_key ? " AND (a.mem_id LIKE '%".$search_key."%' or a.company like '%".$search_key.
                                                    "%' or a.manager like '%".$search_key."%')" : null;
                                    $order = $order?$order:"desc";
                                    $query = "SELECT SQL_CALC_FOUND_ROWS * FROM gn_check_phone a WHERE 1=1 $searchStr";
                                    $res	    = mysqli_query($self_con, $query);
                                    $totalCnt	=  mysqli_num_rows($res);
                                    $limitStr   = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                    $number		= ($nowPage - 1) * $pageCnt;
                                    $orderField = $orderField?$orderField:"a.idx";
                                    $orderQuery .= " ORDER BY a.reg_date $dir $limitStr";
                                    $i = 1;
                                    $query .= $orderQuery;
                                    $res = mysqli_query($self_con, $query);
                                    while($row = mysqli_fetch_array($res)) {
                                        $mem_sql = "select * from Gn_Member use index(mem_id) where mem_id='{$row['mem_id']}'";
                                        $mem_res = mysqli_query($self_con, $mem_sql);
                                        $mem_row = mysqli_fetch_array($mem_res);

                                        $sql = "select count(*) from Gn_MMS_Number where ( not (cnt1 = 10 and cnt2 = 20)) and  mem_id = '{$row['mem_id']}'";
                                        $res_result = mysqli_query($self_con, $sql);
                                        $num_res = mysqli_fetch_row($res_result);
                                        $row['tcnt'] = $num_res[0];
                                        $row['send_extra'] = 0;

                                        $phone_sql="select sendnum from Gn_MMS_Number where ( not (cnt1 = 10 and cnt2 = 20)) and  mem_id = '{$row['mem_id']}'";
                                        $phone_res=mysqli_query($self_con, $phone_sql) or die(mysqli_error($self_con));
                                        while($phone_row = mysqli_fetch_array($phone_res)){
                                            $sql_result_g = "select SUM(recv_num_cnt) from Gn_MMS where send_num='{$phone_row['sendnum']}' and (reg_date like '$date_month%'  or reservation like '$date_month%') and type=10 and result = 0";
											$res_result_g = mysqli_query($self_con, $sql_result_g);
                                            $row_result_g = mysqli_fetch_array($res_result_g);
                                            $row['send_extra'] += $row_result_g[0];
                                        }
                                    ?>
                                        <tr>
                                            <td><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['no']?>"><?=$i + $number?></td>
                                            <td style="text-align:center">
                                                <a href="phone_check_detail.php?no=<?=$row['no']?>">수정</a> /
                                                <a href="javascript:deleteRow('<?=$row['no']?>')">삭제</a></td>
                                            </td>
                                            <td style="text-align:center"><?=$mem_row['site']?>/<?=$mem_row['site_iam']?></td>
                                            <td style="text-align:center"><?=$mem_row['mem_id']?>/<?=$mem_row['mem_name']?></td>
                                            <td style="text-align:center"><?=$row['company']?>/<?=$row['manager']?></td>
                                            <td style="text-align:center"><?=$row['domain']?></td>
                                            <td style="text-align:center"><?=str_replace("-", "",$mem_row['mem_phone'])==$mem_row['sendnum']||$mem_row['sendnum']==""?str_replace("-", "",$mem_row['mem_phone']):$mem_row['sendnum']?></td>
                                            <td onclick="page_view('<?=$row['mem_id']?>');" style="cursor:pointer;text-align:center"><?=$row['tcnt']?></td>
                                            <td style="text-align:right"><?=$row['main_price']?>원</td>
                                            <td style="text-align:right"><?=$row['sub_price']?>원</td>
                                            <td style="text-align:right"><?=$row['send_extra'] * $row['sub_price']?>원</td>
                                            <td style="text-align:right"><?=$row["send_extra"]?></td>
                                            <td style="text-align:center"><?=$row['api_key']?></td>
                                            <td style="text-align:center">
                                                <select onchange="change_type(this,'<?=$row['no']?>')">
                                                    <option value="phone" <?=$row['check_type'] == "phone"?"selected":""?>>폰문자인증</option>
                                                    <option value="web" <?=$row['check_type'] == 'web' ?"selected":""?>>웹문자인증</option>
                                                </select>
                                            </td>
                                            <td style="text-align:center">
                                                <label class="switch">
                                                    <input type="checkbox" class="chkagree" id="<?=$row['no'];?>" <?=$row['status']=="Y"?"checked":""?>>
                                                    <span class="slider round"></span>
                                                </label>
                                            </td>
                                            <td style="text-align:right"><?=$row['reg_date']?></td>
                                            
                                        </tr>
                                    <?
                                        $i++;
                                    }
                                    if($i == 1) {?>
                                        <tr>
                                            <td colspan="14" style="text-align:center;background:#fff">
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
                        <?echo drawPagingAdminNavi($totalCnt, $nowPage);?>
                        </div>
                    </div>
                </div><!-- /.row -->
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?> 
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div><!--./wrapper-->
     