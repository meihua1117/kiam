<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
if($idx) {
    // 가입 회원 상세 정보
    $query = "select * from crawler_shop_admin where id='$idx'";
    $res = mysqli_query($self_con, $query);
    $data = mysqli_fetch_array($res);
}
$sync_gwc = get_search_key('gwc_prod_sync_status');
$sql_sync = "select up_data from Gn_Iam_Contents_Gwc where mem_id='iamstore' and card_idx in (934329,934722,935615,935757,936099,937019,937056,937141,937226,937314,937339,937416,937427,937435,937473,974442,1002328,1029774,1034733,1034893,1036309,1036310,1036445,1037573,1037592,1037708,1037798,1047845,1047846,1047943) and sync_date is not null order by up_data desc limit 1";
$res_sync = mysqli_query($self_con, $sql_sync);
$row_sync = mysqli_fetch_array($res_sync);
?>
<style>
    .box-body th {
        background: #ddd;
    }
    .ai_reg_input{
        width:80%;
    }
    .ai_reg_input1{
        width:19.6%;
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
    .auto_switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .auto_switch {
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
    #ajax-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:9000;text-align:center;display:none;background-color: #fff;opacity: 0.8;}
    #ajax-loading img{position:absolute;top:50%;left:50%;width:120px;height:120px;margin:-60px 0 0 -60px;}
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script>
<div class="wrapper">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                IAM자동등록을 위한 정보입력하기
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">아이엠자동등록관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <input type="hidden" name="crawl_status" id="crawl_status" value="<?=$data['crawler_status']?$data['crawler_status']:"0"?>">
                        <table id="detail1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="5%">
                                <col width="10%">
                                <col width="70%">
                            </colgroup>
                            <tbody>
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>항목</th>
                                        <th>수집키워드 및 설명</th>
                                    </tr>
                                </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>채널항목</td>
                                    <td>
                                        <input type="radio" name="web_type" id="mapid" style="vertical-align: middle;" <?=$data['web_type']=='지도'?"checked":"";?>>
                                        <label for="mapid" value="map" style="font-size:17px;">지도</label>
                                        <input type="radio" name="web_type" id="gmarketid" style="vertical-align: middle;" <?=$data['web_type']=='G쇼핑'?"checked":"";?>>
                                        <label for="gmarketid" value="gmarket" style="font-size:17px;">G쇼핑</label>
                                        <input type="radio" name="web_type" id="nshopid" style="vertical-align: middle;" <?=$data['web_type']=='N쇼핑'?"checked":"";?>>
                                        <label for="nshopid" value="nshop" style="font-size:17px;">N쇼핑</label>
                                        <input type="radio" name="web_type" id="iamshopid" style="vertical-align: middle;" <?=$data['web_type']=='도매몰'?"checked":"";?>>
                                        <label for="iamshopid" value="iamshop" style="font-size:17px;">도매몰</label>
                                        <label class="auto_switch" id="sync_state" style="margin-right:20%;float:right;display:none;">
                                            <input type="checkbox" name="sync_status" id="sync_status" onchange="show_date_input()" <?=$sync_gwc == "Y"?"checked":"";?>>
                                            <span class="slider round" name="auto_status_round" id="auto_status_round"></span>
                                        </label>
                                        <label for="iamshopid" value="iamshop" id="sync_title" style="font-size:15px;margin-top: 7px;float:right;display:none;">도매몰동기화(<?=$row_sync[0]?>)</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>생성제목</td>
                                    <td>
                                        <input type="text" name="reg_title" id="reg_title" class="ai_reg_input" placeholder="자동생성을 위한 제목을 입력합니다." value="<?=$data['reg_title']?$data['reg_title']:"";?>">
                                    </td>
                                </tr>
                                <?if($_GET['store'] != "Y"){?>
                                <tr id="search_str">
                                    <td>3</td>
                                    <td>검색조건</td>
                                    <td>
                                        <input type="text" name="reg_search_busi_type" id="reg_search_busi_type" class="ai_reg_input1" placeholder="업종"  value="<?=$data['reg_search_busi_type']?$data['reg_search_busi_type']:"";?>">
                                        <input type="text" name="reg_search_busi" id="reg_search_busi" class="ai_reg_input1" placeholder="상호"  value="<?=$data['reg_search_busi']?$data['reg_search_busi']:"";?>">
                                        <input type="text" name="reg_search_addr" id="reg_search_addr" class="ai_reg_input1" placeholder="주소"  value="<?=$data['reg_search_addr']?$data['reg_search_addr']:"";?>">
                                        <input type="text" name="reg_search_keyword" id="reg_search_keyword" class="ai_reg_input1" placeholder="검색어"  value="<?=$data['reg_search_keyword']?$data['reg_search_keyword']:"";?>">
                                    </td>
                                </tr>
                                <!--tr>
                                    <td colspan="2"></td>
                                    <td>
                                        <input type="text" name="reg_search_region" id="reg_search_region" class="ai_reg_input" placeholder="여러지역을 수집할때 입력하세요."  value="">
                                    </td>
                                </tr-->
                                <tr id="make_cnt">
                                    <td>4</td>
                                    <td>생성건수</td>
                                    <td>
                                        <input type="text" name="reg_cnt" id="reg_cnt" class="ai_reg_input" placeholder="생성건수를 입력합니다."  value="<?=$data['reg_cnt']?$data['reg_cnt']:"";?>">
                                    </td>
                                </tr>
                                <tr id="contents_cnt">
                                    <td>5</td>
                                    <td>콘텐츠갯수</td>
                                    <td>
                                        <input type="text" name="reg_con_cnt" id="reg_con_cnt" class="ai_reg_input" placeholder="콘텐츠 갯수를 입력합니다."  value="<?=$data['reg_con_cnt']?$data['reg_con_cnt']:"";?>">
                                    </td>
                                </tr>
                                <tr id="setting_id">
                                    <td>6</td>
                                    <td>ID설정</td>
                                    <td>
                                        <input type="text" name="reg_id" id="reg_id" class="ai_reg_input" placeholder="지도는 웹주소 고유번호, N쇼핑 G쇼핑은 웹주소 고유계정으로 합니다." readonly>
                                    </td>
                                </tr>
                                <tr id="setting_pwd">
                                    <td>7</td>
                                    <td>PW설정</td>
                                    <td>
                                        <input type="text" name="reg_pwd" id="reg_pwd" class="ai_reg_input" placeholder="지도, N쇼핑 G쇼핑의 비번은 모두 전화번호 뒷 4자리로 합니다." readonly>
                                    </td>
                                </tr>
                                <tr id="setting_auto">
                                    <td>8</td>
                                    <td>오토데이트</td>
                                    <td>
                                        <label class="auto_switch" style="margin-right:10px;">
                                            <input type="checkbox" name="auto_status" id="auto_status" onchange="show_date_input()" <?=$data['reg_auto_data']?"checked":"";?>>
                                            <span class="slider round" name="auto_status_round" id="auto_status_round"></span>
                                        </label>
                                        <input type="text" name="reg_auto_day" id="reg_auto_day" style="margin-left:10px;width:20%;" placeholder="날자를 입력합니다." <?=$data['reg_auto_data']?"":"hidden";?> value="<?=$data['reg_auto_day']?$data['reg_auto_day']:"";?>">
                                        <input type="text" name="reg_auto_time" id="reg_auto_time" style="margin-left:10px;width:20%;" placeholder="시간을 입력합니다." <?=$data['reg_auto_data']?"":"hidden";?> value="<?=$data['reg_auto_time']?$data['reg_auto_time']:"";?>">
                                    </td>
                                </tr>
                                <?}?>
                                <tr id="iam_store_link" <?=$_GET['store'] != "Y"?"hidden":""?>>
                                    <td>3</td>
                                    <td>상품주소</td>
                                    <td>
                                        <input type="text" name="iamstore_link" id="iamstore_link" class="ai_reg_input" placeholder="아이엠스토어 상품 상세페이지 주소를 입력합니다." value="<?=$data['iamstore_link']?$data['iamstore_link']:"";?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td id="memo_no"><?=$_GET['store'] != "Y"?"9":"4"?></td>
                                    <td>메모남기기</td>
                                    <td>
                                        <input type="text" name="reg_memo" id="reg_memo" class="ai_reg_input" placeholder="IAM 자동생성에 필요한 정보를 메모장에 남긴다." value="<?=$data['reg_memo']?$data['reg_memo']:"";?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td id="memo_regdate"><?=$_GET['store'] != "Y"?"10":"5"?></td>
                                    <td>등록일시</td>
                                    <td>
                                        <input type="text" name="reg_date" id="reg_date" value='<?=$data['reg_date']?$data['reg_date']:date('Y-m-d H:i:s')?>'>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
                <div class="box-footer" style="text-align:center">
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='ai_map_gmarket_making.php';return false;"><i class="fa fa-list"></i> 취소</button>
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                    <button class="btn btn-primary" id="start_crawl_btn" style="margin-right: 5px;" onclick="start_crawler();return false;" <?=$_REQUEST['idx']?"":"disabled";?>><i class="fa fa-save"></i> 생성실행</button>
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
<!-- Footer -->
<div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
<script language="javascript">
    $(function() {
        var contHeaderH = $(".main-header").height();
        var navH = $(".navbar").height();
        if(navH != contHeaderH)
            contHeaderH += navH - 50;
        $(".content-wrapper").css("margin-top",contHeaderH);
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 252;
        if(height < 375)
            height = 375;
        $(".box-body").css("height",height);
    });
    $(document).ajaxStart(function() {
        $("#ajax-loading").show();
    })
    .ajaxStop(function() {
        $("#ajax-loading").delay(10).hide(1); 
    });
    $(function(){
        $('#sync_status').change(function() {
            var sample_click = $(this)[0].checked;
            if(sample_click){
                sample_click = "Y";
            }else{
                sample_click = "N";
            }
            $.ajax({
                type:"POST",
                url:"/admin/ajax/gwc_order_save.php",
                data:{
                    type:"gwc_sync_state",
                    sample_click:sample_click,
                    },
                success:function(data){
                    location.reload();
                },
                error: function(){
                    alert('실패');
                }
            });
        });
    })

    $("input[name=web_type]").on('change', function(){
        if($(this).prop('checked') && $(this).attr('id') == 'iamshopid'){
            $("#search_str").hide();
            $("#make_cnt").hide();
            $("#contents_cnt").hide();
            $("#setting_id").hide();
            $("#setting_auto").hide();
            $("#setting_pwd").hide();
            $("#iam_store_link").show();
            $("#memo_no").html('4');
            $("#memo_regdate").html('5');
            $("#sync_state").show();
            $("#sync_title").show();
        }
        else{
            $("#search_str").show();
            $("#make_cnt").show();
            $("#contents_cnt").show();
            $("#setting_id").show();
            $("#setting_auto").show();
            $("#setting_pwd").show();
            $("#iam_store_link").hide();
            $("#memo_no").html('9');
            $("#memo_regdate").html('10');
            $("#sync_state").hide();
            $("#sync_title").hide();
        }
    })

    function form_save() {
        var store = "Y";
        if ($('#reg_title').val() == "") {
            alert('생성제목을 입력해주세요.');
            return;
        }
        if(!$("#iamshopid").prop('checked')){
            if ($('#reg_cnt').val() == "") {
                alert('생성건수를 입력해주세요.');
                return;
            }
            if ($('#reg_con_cnt').val() == "") {
                alert('콘텐츠갯수를 입력해주세요.');
                return;
            }
            var store = "N";
        }

        var web_type1 = $('input[name=web_type]:checked').attr('id');
        switch(web_type1){
            case 'mapid':
                var web_type = '지도';
                break;
            case 'gmarketid':
                var web_type = 'G쇼핑';
                break;
            case 'nshopid':
                var web_type = 'N쇼핑';
                break;
            case 'iamshopid':
                var web_type = '도매몰';
                break;
            default:
                alert("채널을 선택해 주세요.");
                return;
                break;
        }
        var reg_title = $('#reg_title').val();
        var reg_search_busi_type = $('#reg_search_busi_type').val();
        var reg_search_busi = $('#reg_search_busi').val();
        var reg_search_addr = $('#reg_search_addr').val();
        var reg_search_keyword = $('#reg_search_keyword').val();
        var reg_cnt = $('#reg_cnt').val();
        var reg_con_cnt = $('#reg_con_cnt').val();
        var reg_memo = $('#reg_memo').val();
        var reg_date = $('#reg_date').val();
        var auto_data = $("#auto_status").prop("checked");
        var iamstore_link = $('#iamstore_link').val();

        if(auto_data == true){
            var auto = 1;
        }
        else{
            var auto = 0;
        }

        $.ajax({
            type:"POST",
            dataType:"json",
            data:{web_type:web_type, reg_title:reg_title, reg_search_busi_type:reg_search_busi_type, reg_search_busi:reg_search_busi, reg_search_addr:reg_search_addr, reg_search_keyword:reg_search_keyword, reg_cnt:reg_cnt, reg_con_cnt:reg_con_cnt, reg_memo:reg_memo, reg_date:reg_date, auto:auto, iamstore_link:iamstore_link},
            url:"ajax/reg_ai_shop_crawler.php",
            success:function(data){
                if(data.status == 1){
                    alert("저장 되었습니다.");
                    location.href="reg_shopping_iam.php?idx="+data.set_id+"&store="+store;
                }
            }
        });
    }

    function show_date_input(){
        var auto_data = $("#auto_status").prop("checked");
        if(auto_data == true){
            $("#reg_auto_day").show();
            $("#reg_auto_time").show();
        }
        else{
            $("#reg_auto_day").hide();
            $("#reg_auto_time").hide();
        }
    }

    function start_crawler(){
        if($("#crawl_status").val() == 1){
            alert("이미 실행했습니다. 추가로 실행할가요?");
            return;
        }
        if(!confirm("생성실행을 하시겠습니까?")){
            return;
        }
        start_crawler_1();
        // setTimeout(go_making, 4000);
    }

    function go_making(){
        location.href="ai_map_gmarket_making.php";
    }

    function start_crawler_1(){
        $("#start_crawl_btn").attr('disabled', true);
        var web_type1 = $('input[name=web_type]:checked').attr('id');
        switch(web_type1){
            case 'mapid':
                var web_type = 'map';
                break;
            case 'gmarketid':
                var web_type = 'gshopping';
                break;
            case 'nshopid':
                var web_type = 'nshopping';
                break;
            case 'iamshopid':
                var web_type = 'iamstore';
                break;
            default:
                console.log("select type!");
                break;
        }

        if(web_type == "map"){
            url = "https://www.goodhow.com/crawler/Crawler_map/index_1.php";
            // url = "http://localhost:8086/Crawler_map/index_map.php";
        }
        else if(web_type == "gshopping"){
            url = "https://www.goodhow.com/crawler/crawler/index_1.php";
            // url = "http://localhost:8086/crawler_gmarket/index_gmarket.php";
        }
        else if(web_type == "nshopping"){
            url = "https://www.goodhow.com/crawler/crawler/index_navershop_1.php";
            // url = "http://localhost:8086/crawler_gmarket/index_gmarket.php";
        }
        else if(web_type == "iamstore"){
            url = "https://www.goodhow.com/crawler/crawler/index_iamshop.php";
            // url = "http://localhost:8086/crawler_gmarket/index_test.php";
        }

        var contents_cnt = $('#reg_con_cnt').val();
        var account_cnt = $('#reg_cnt').val();
        var reg_search_busi_type = $('#reg_search_busi_type').val();
        var reg_search_busi = $('#reg_search_busi').val();
        var reg_search_addr = $('#reg_search_addr').val();
        var reg_search_keyword = $('#reg_search_keyword').val();
        var set_idx = '<?=$_REQUEST['idx']?>';
        var iamstore_link = $('#iamstore_link').val();

        $.ajax({
            type:"POST",
            dataType:"json",
            data:{
                contents_cnt:contents_cnt, 
                account_cnt:account_cnt, 
                reg_search_busi_type:reg_search_busi_type,
                reg_search_busi:reg_search_busi, 
                reg_search_addr:reg_search_addr, 
                reg_search_keyword:reg_search_keyword, 
                admin_shopping:true, 
                set_idx:set_idx, 
                iamstore_link:iamstore_link
            },
            url:url,
            success: function(data){
                if(web_type == "iamstore"){
                    console.log(data);
                }
                else{
                    alert("생성완료 되었습니다.");
                }
                // location.href="ai_map_gmarket_list.php";
                // console.log(data);
            }
        });

        if(web_type == "iamstore" && iamstore_link == ''){
            console.log("OK!!");
            var interval_gwc = setInterval(function() {
                $.ajax({
                    type:"POST",
                    url:"/admin/iamshop_crawling_status.php",
                    dataType:"json",
                    data:{get:true, set_idx:set_idx},
                    success:function (data){
                        if(data.status == 0){
                            if(data.restart == 0){
                                $("#start_crawl_btn").attr('disabled', false);
                                clearInterval(interval_gwc);
                            }
                            else{
                                start_crawler_1();
                            }
                        }
                        else if(data.status == 3){
                            alert("자동 생성중 오류가 발생 하였습니다.");
                            clearInterval(interval_gwc);
                            location.reload();
                        }
                    }
                });
            }, 7000);
        }
        else{
            console.log("NO!!");
        }
    }
</script>
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>