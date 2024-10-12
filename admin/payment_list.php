<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
//extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
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
        location.href = '?nowPage='+pgNum+'&search_status=<?=$search_status?>&stop_yn=<?=$stop_yn?>'+
                        '&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>'+
                        '&search_key=<?=$search_key?>&rec_id=<?=$rec_id?>&identifier=<?=$identifier?>'+
                        '&pay_method=<?=$pay_method?>&item_type=<?=$item_type?>&site=<?=$site?>&site_iam=<?=$site_iam?>';
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
                url:"/admin/ajax/payment_delete.php",
                data:{
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
        $('.switch').on("change", function() {
            var no = $(this).find("input[type=checkbox]").val();
            var status = $(this).find("input[type=checkbox]").is(":checked")==true?"N":"Y";
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
        $('.month_count').on("change",function(){
            var obj = $(this);
            $.ajax({
                type:"POST",
                url:"ajax/payment_save.php",
                dataType : "json",
                data:{
                    type : "end_date",
                    no : $(this).data("no"),
                    month : $(this).val()
                },
                success:function(data){
                    obj.parents("tr").find("span[id=end_date]").html(data.end_date);
                }
            });
        });
        $('.onestep2yak').on("change",function(){
            var yak = $(this).val();
            $.ajax({
                type:"POST",
                url:"ajax/payment_save.php",
                dataType : "json",
                data:{
                    type : "onestep2_update",
                    no : $(this).data("no"),
                    yak : yak
                },
                success:function(data){
                    location.reload();
                }
            });
        });
    });

    function cmsexcel_submit(f)
    {
        if(!f.cms_excel.value) {
            alert('(*.xls) 파일을 업로드해주십시오.');
            return false;
        }
        
        if(!f.cms_excel.value.match(/\.(xls)$/i) && f.cms_excel.value) {
            alert('(*.xls) 파일만 등록 가능합니다.');
            return false;
        }

        if(!confirm("결제정보를 업로드 하시겠습니까?"))
            return false;
        
        f.action = "/admin/ajax/cms_excel_upload.php";
        return true;
    }
    $(function() {
        var contHeaderH = $(".main-header").height();
        var navH = $(".navbar").height();
        if(navH != contHeaderH)
            contHeaderH += navH - 50;
        $(".content-wrapper").css("margin-top",contHeaderH);
        var height = window.outerHeight - contHeaderH /*- $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 196*/;
        if(height < 375)
            height = 375;
        $(".box-body").css("height",height);
    });
</script>   
<style>
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
            <h1>솔루션결제관리<small>솔루션결제를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">솔루션결제관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <?if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                        <div style="margin-bottom:40px">
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_payment_down.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='payment_status_mng.php';return false;"><i class="fa fa-download"></i> 결제상태관리</button>
                            <!-- <form method="post" name="cms_pay_upload" id="cms_pay_upload" onsubmit="return cmsexcel_submit(this);" enctype="multipart/form-data">
                            <input type="submit" class="btn btn-primary pull-right" style="margin-right: 10px;" value="CMS결제정보업로드">
                            <input type="file" name="cms_excel" id="cms_excel" style="width:190px;float: right;margin-right:3px;">
                            </form> -->
                        </div>
                    <?}?>
                    <form method="get" name="search_form" id="search_form " class="form-inline">
                        <div class="box-tools">
                            <div class="input-group"  >
                                <div class="form-group">
                                    <select name="search_status" class="form-control input-sm " style="margin-right:5px">
                                        <option value="">결제상태</option>
                                        <option value="Y" <?if($_REQUEST['search_status'] == "Y") echo "selected"?>>결제완료</option>
                                        <option value="N" <?if($_REQUEST['search_status'] == "N") echo "selected"?>>결제대기</option>
                                        <option value="A" <?if($_REQUEST['search_status'] == "A") echo "selected"?>>후불결제</option>
                                        <option value="E" <?if($_REQUEST['search_status'] == "E") echo "selected"?>>기간만료</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="stop_yn" class="form-control input-sm " style="margin-right:5px">
                                        <option value="">이용상태</option>
                                        <option value="Y" <?if($_REQUEST['stop_yn'] == "Y") echo "selected"?>>이용정지</option>
                                        <option value="N" <?if($_REQUEST['stop_yn'] == "N") echo "selected"?>>이용승인</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="item_type" class="form-control input-sm " style="margin-right:5px">
                                        <option value="">전체</option>
                                        <option value="S" <?if($_REQUEST['item_type'] == "S") echo "selected"?>>셀링</option>
                                        <option value="I" <?if($_REQUEST['item_type'] == "I") echo "selected"?>>아이엠</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="pay_method" class="form-control input-sm " style="margin-right:5px">
                                        <option value="">결제종류</option>
                                        <option value="BANK" <?if($_REQUEST['pay_method'] == "BANK") echo "selected"?>>무통장</option>
                                        <option value="VCard" <?if($_REQUEST['pay_method'] == "VCard") echo "selected"?>>V카드</option>
                                        <option value="Card" <?if($_REQUEST['pay_method'] == "Card") echo "selected"?>>카드</option>
                                        <option value="MONTH" <?if($_REQUEST['pay_method'] == "MONTH") echo "selected"?>>통장정기</option>
                                        <option value="MONTH_Card" <?if($_REQUEST['pay_method'] == "MONTH_Card") echo "selected"?>>카드정기</option>
                                        <option value="Auto_Card" <?if($_REQUEST['pay_method'] == "Auto_Card") echo "selected"?>>Auto카드</option>
                                        <option value="Yak" <?if($_REQUEST['pay_method'] == "Yak") echo "selected"?>>약정</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="date" style="height: 30px" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST['search_start_date']?>" multiple/> ~
                                    <input type="date" style="height: 30px" name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST['search_end_date']?>"/>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="site_iam" id="site_iam" style="width:100px" class="form-control input-sm pull-right" placeholder="아이엠소속" value="<?=$site_iam?>">
                                    <input type="text" name="site" id="site" style="width:100px" class="form-control input-sm pull-right" placeholder="셀링소속" value="<?=$site?>">
                                    <input type="text" name="search_key" id="search_key"   style="width:100px" class="form-control input-sm pull-right" placeholder="이름" value="<?=$search_key?>">
                                    <input type="text" name="rec_id" id="rec_id" style="width:100px" class="form-control input-sm pull-right" placeholder="추천인" value="<?=$rec_id?>">
                                    <input type="text" name="identifier" id="identifier" style="width:100px" class="form-control input-sm pull-right" placeholder="아이디" value="<?=$identifier?>">
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
                </table>
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="200px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="60px">
                                <col width="100px">
                                <col width="140px">
                                <col width="80px">
                                <col width="250px">
                                <col width="200px">
                                <col width="200px">
                                <col width="100px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check" id="check_all_member" value="0">번호</th>
                                    <th>소속</th>
                                    <th>추천인</th>
                                    <th>아이디</th>
                                    <th>상품종류</th>
                                    <th><?=$_REQUEST['pay_type'] == "I" ? '상품구성<br>카드/전송':'상품구성'?></th>
                                    <th>이름</th>
                                    <th>전화번호</th>
                                    <th>결제종류</th>
                                    <th>금액</th>
                                    <th>약정</th>
                                    <th>기간(개월)</th>
                                    <th>이용정지</th>
                                    <th>상태</th>
                                    <th>가입일</th>
                                    <th>결제일</th>
                                    <th>종료일</th>
                                    <th><button class="btn btn-primary" onclick="deleteMultiRow();return false;"><i class="fa"></i>삭제</button></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $identifier ? " AND (buyer_id LIKE '%".$identifier."%')" : null;
                                $searchStr .= $rec_id ? " AND (recommend_id like '%".$rec_id."%') " : null;
                                $searchStr .= $search_key ? "AND ( VACT_InputName like '%".$search_key."%')" : null;
                                $searchStr .= $site ? " AND b.site = '$site' " : null;
                                $searchStr .= $site_iam ? " AND b.site_iam = '$site_iam' " : null;
                                if($search_start_date && $search_end_date)
                                    $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
                                if($search_status)
                                    $searchStr .= " AND end_status='$search_status'";
                                if($stop_yn)
                                    $searchStr .= " AND stop_yn='$stop_yn'";
                                if($pay_method){
                                    if($pay_method == "Yak")
                                        $searchStr .= " AND onestep2='ON'";
                                    else
                                        $searchStr .= " AND payMethod='$pay_method'";
                                }

                                if($rec_id || $site || $site_iam){
                                    $join_str = "INNER JOIN Gn_Member b on b.mem_id =a.buyer_id ";
                                    $sel_str = ",b.site,b.site_iam,b.recommend_id,b.mem_name,b.mem_phone,b.first_regist";
                                }
                                else{
                                    $join_str = "";
                                    $sel_str = "";
                                }
                                /*if($item_type == "S")
                                    $searchStr .= " AND (iam_pay_type ='' or iam_pay_type ='0')";
                                else if($item_type == "I")
                                    $searchStr .= " AND (iam_pay_type !='' and iam_pay_type !='0')";*/

                                $order = $order?$order:"desc";
                                $query = "SELECT SQL_CALC_FOUND_ROWS a.*$sel_str FROM tjd_pay_result a $join_str WHERE a.member_type!='포인트충전' and gwc_cont_pay=0 $searchStr";
                                $excel_sql=$query;
                                $excel_sql=str_replace("'","`",$excel_sql);
                                $res	    = mysqli_query($self_con,$query);
                                $totalCnt	=  mysqli_num_rows($res);
                                $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY a.no DESC $limitStr ";
                                $i = 1;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {
                                    if($rec_id || $site || $site_iam){
                                        $chk_str = 0;
                                        $mem_phone = $row['mem_phone'];
                                        $first_regist = $row['first_regist'];
                                    }
                                    else{
                                        $chk_str = 1;
                                        $sql_mem = "select site, site_iam, recommend_id, mem_phone, first_regist from Gn_Member where mem_id='{$row['buyer_id']}'";
                                        $res_mem = mysqli_query($self_con,$sql_mem);
                                        $row_mem = mysqli_fetch_array($res_mem);
                                        $mem_phone = $row_mem['mem_phone'];
                                        $first_regist = $row_mem['first_regist'];
                                    }
                                ?>
                                    <tr>
                                        <td><input type="checkbox" class="check" id="check_one_member" value="<?=$row['no']?>"><?=$number--?></td>
                                        <td><?=$chk_str?$row_mem['site']:$row['site']?>/<br><?=$chk_str?$row_mem['site_iam']:$row['site_iam']?></td>
                                        <td><?=$chk_str?$row_mem['recommend_id']:$row['recommend_id']?></td>
                                        <td><?=$row['buyer_id']?></td>
                                        <td><?=$row['member_type']?></td>
                                        <td>
                                            <?
                                            if($row['member_type'] != "dber")
                                                echo number_format($row['iam_card_cnt']).'/'.number_format($row['db_cnt']).'/'.number_format($row['max_cnt']);
                                            else
                                                echo number_format($row['db_cnt']).'/'.number_format($row['email_cnt']).'/'.number_format($row[shop_cnt]);
                                            ?>
                                        </td>
                                        <td><?=$row['VACT_InputName']?></td>
                                        <td><?=str_replace("-", "",$mem_phone)==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$mem_phone):$row['sendnum']?></td>
                                        <td><?=$pay_type[$row[payMethod]]?></td>
                                        <td>
                                            <input type="text" name="price" id="price<?=$i?>" value="<?=$row[TotPrice]?>" onchange="$('#price_<?=$i?>').val(this.value)" style="width:70px;">원
                                        </td>
                                        <!--td><?=number_format($row['add_phone'])?>개</td-->
                                        <td>
                                            <select name="onestep2" class="onestep2yak" data-no = "<?=$row['no']?>">
                                                <option value="ON" <?php echo $row['onestep2'] == "ON"?"selected":""?>>ON</option>
                                                <option value="OFF" <?php echo $row['onestep2'] == "OFF"?"selected":""?>>OFF</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" value = "<?=number_format($row['month_cnt'])?>" class = "month_count" style="width: 50px" data-no = "<?=$row['no']?>">
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" name="status" id="stauts_<?php echo $row['no'];?>" value="<?php echo $row['no'];?>" <?php echo $row['stop_yn']=="Y"?"checked":""?>>
                                                <span class="slider round" name="status_round" id="stauts_round_<?php echo $row['no'];?>"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="ajax/payment_save.php">
                                                <input type="hidden" name="no" value="<?=$row['no']?>" >
                                                <input type="hidden" name="price" id="price_<?=$i?>" value="<?=$row[TotPrice]?>" >
                                                <input type="hidden" name="type" id="type_<?=$i?>" value="main">
                                                <select name="end_status"  onchange="payment_save('#ssForm<?=$i?>');return false;">
                                                    <option value="N" <?php echo $row['end_status'] == "N"?"selected":""?>>결제대기</option>
                                                    <option value="Y" <?php echo $row['end_status'] == "Y"?"selected":""?>>결제완료</option>
                                                    <option value="A" <?php echo $row['end_status'] == "A"?"selected":""?>>후불결제</option>
                                                    <option value="E" <?php echo $row['end_status'] == "E"?"selected":""?>>기간만료</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td><?=$first_regist?></td>
                                        <td><?=$row['date']?></td>
                                        <td><span id="end_date"><?=$row['end_date']?></span></td>
                                        <td><button class="btn btn-primary" onclick="deleteRow('<?=$row['no']?>');return false;"><i class="fa"></i>삭제</button></td>
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
        <input type="hidden" name="excel_sql" value="<?=base64_encode($excel_sql)?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div><!--wrapper-->
<!-- Footer -->
<!-- <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                 -->
