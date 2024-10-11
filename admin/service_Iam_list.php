<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
if($_REQUEST['dir'] == "desc"){
    $dir = "asc";
}else{
    $dir = "desc";
}
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    //계정 삭제
    function del_service(idx){
        var msg = confirm('정말로 삭제하시겠습니까?');
        if(msg){
            $.ajax({
                type:"POST",
                url:"/admin/ajax/service_Iam_save.php",
                data:{mode:"del",idx:idx},
                success:function(){
                    alert('삭제되었습니다.');
                    location.reload();
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
    function loginGo(sub_domain,mem_id, mem_pw, mem_code) {
        $('#one_id').val(mem_id);
        $('#mem_pass').val(mem_pw);
        $('#mem_code').val(mem_code);
        $('#sub_domain').val(sub_domain);
        $('#login_form').submit();
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
    function addMultiSelling(){
        var check_array = $("#example1").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });
        if(no_array.length == 0){
            alert("분양등록할 업체를 선택하세요.");
            return;
        }
        if(confirm('등록하시겠습니까?')) {
            $.ajax({
                type:"POST",
                url:"/admin/ajax/service_save_clone.php",
                dataType:"json",
                data:{id:no_array.toString()},
                success: function(data){
                    console.log(data);
                    alert('등록되었습니다.');
                    window.location.reload();
                },error: function (request, status, error) {
                    alert('code: ' + request.status + "\n" + 'message: ' + request.responseText + "\n" + 'error: ' + error);
                }
            })
        }
    }
    function duplicate_service(){
        var check_array = $("#example1").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });
        if(no_array.length == 0){
            alert("분양복제할 업체를 선택하세요.");
            return;
        }
        if(confirm('분양복제하시겠습니까?')) {
            $.ajax({
                type:"POST",
                url:"/admin/ajax/service_save_duplicate.php",
                dataType:"json",
                data:{id:no_array.toString()},
                success: function(data){
                    console.log(data);
                    alert('복제되었습니다.');
                    window.location.reload();
                },error: function (request, status, error) {
                    alert('code: ' + request.status + "\n" + 'message: ' + request.responseText + "\n" + 'error: ' + error);
                }
            })
        }
    }
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
                url:"/admin/ajax/delete_func.php",
                dataType:"json",
                data:{admin:1, delete_name:"iam_service_list", id:no_array.toString()},
                success: function(data){
                    if(data == 1){
                        alert('삭제 되었습니다.');
                        window.location.reload();
                    }
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
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 232;
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
    @media (max-width:768px){
        .wrapper{
            margin-top: 92px;
        }
    }
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;text-align: center}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
</style>

<div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
<div class="wrapper">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>아이엠 분양관리<small>아이엠 분양정보를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">아이엠 분양관리</li>
            </ol>
        </section>

        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
            <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />
            <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />
            <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />
            <input type="hidden" name="sub_domain" id="sub_domain" value="<?=$data['sub_domain']?>" />
        </form>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='service_Iam_detail.php';return false;"><i class="fa fa-download"></i> 등록</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow();"><i class="fa fa-download"></i> 선택삭제</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="addMultiSelling();"><i class="fa fa-download"></i> 셀링분양등록</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="duplicate_service();"><i class="fa fa-download"></i> 분양복제</button>
                </div>
            </div>
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
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body"  style="overflow: auto !important">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="60px">
                                <col width="120px">
                                <col width="200px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="200px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check" id="check_all_member" value="0">번호</th>
                                    <th>수정/삭제</th>
                                    <th>업체명</th>
                                    <th>승인회원</th>
                                    <th>분양비</th>
                                    <th>메인도메인</th>
                                    <th>서브도메인</th>
                                    <th>브랜드명</th>
                                    <th>아이디</th>
                                    <th>계약자</th>
                                    <th>연락처</th>
                                    <th><a href="?orderField=a.status&dir=<?=$dir?>" class="sort-by">상태</a></th>
                                    <th>앱메인홈</th>
                                    <th>아이엠메뉴</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;
                            // 검색 조건을 적용한다.
                            $searchStr .= $search_key ? " AND (mem_id LIKE '%".$search_key."%' or company_name like '%".$search_key."%' or mem_name like '%".$search_key."%' or sub_domain like '%".$search_key."%')" : null;
                            $order = $order?$order:"desc";
                            $query = "SELECT SQL_CALC_FOUND_ROWS * FROM Gn_Iam_Service a WHERE 1=1 $searchStr";
                            $res	    = mysqli_query($self_con,$query);
                            $totalCnt	=  mysqli_num_rows($res);
                            $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                            $orderQuery .= " ORDER BY a.regdate DESC $limitStr ";
                            $i = 1;
                            $query .= "$orderQuery";
                            $res = mysqli_query($self_con,$query);
                            while($row = mysqli_fetch_array($res)) {
                                $mem_sql = "select web_pwd,mem_code, site_iam from Gn_Member where mem_id = '$row[mem_id]'";
                                $mem_result = mysqli_query($self_con,$mem_sql);
                                $mem_row = mysqli_fetch_array($mem_result);
                            ?>
                                <tr>
                                    <td><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['idx']?>"><?=$number--?></td>
                                    <td>
                                        <a href="service_Iam_detail.php?idx=<?=$row['idx']?>">수정</a> /
                                        <a href="javascript:del_service('<?=$row['idx']?>')">삭제</a>
                                    </td>
                                    <td><?=$row['company_name']?></td>
                                    <td><?=$row['mem_cnt']?></td>
                                    <td><?=$row['share_price']?></td>
                                    <td><a href="<?=$row['main_domain']?>" target="_blank"><?=$row['main_domain']?></a></td>
                                    <td><a href="javascript:loginGo('<?=$row['sub_domain']?>','<?=$row['mem_id']?>','<?=$mem_row['web_pwd']?>', '<?=$mem_row['mem_code']?>')"><?=$row['sub_domain']?></a></td>
                                    <td><?=$row['brand_name']?></td>
                                    <td><?=$row['mem_id']?></td>
                                    <td><?=$row['owner_name']?></td>
                                    <td><?=$row['owner_cell']?></td>
                                    <td><?=$row['status']=="Y"?"서비스중":"정지중"?></td>
                                    <td><a href="app_home_menu.php?site=<?=$mem_row['site_iam']?>" target="_blank">수정</a></td>
                                    <td><a href="iam_menu.php?site=<?=$mem_row['site_iam']?>" target="_blank">수정</a></td>
                            </tr>
                                <?
                                $i++;
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
            </div><!-- /.row -->
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                    <?echo drawPagingAdminNavi($totalCnt, $nowPage);?>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <iframe name="excel_iframe" style="display:none;"></iframe>
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
</div><!-- /.wrapper -->