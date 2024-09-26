<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/ad_sms/include/_header.inc.php";

// 광역시도 목록
$province_list = array();
$query = "SELECT province FROM gn_cities group by province";
$res = mysql_query($query);
while($row = mysql_fetch_array($res)) {
    $province_list[] = $row['province'];
}

$sel_addrs = array();

?>

<script language="javascript" src="/js/rlatjd_fun.js?m=<?php echo time();?>"></script>

<style>
    td {
        vertical-align:middle !important;
        max-width:800px;
    }
    td input {
        margin-left:0px !important;
    }
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
        border: 1px solid #747373;
    }
    .section-title.active {
        background:#F8CBAD;
    }
    .section-title2 {
        border: 1px solid #3c8dbc;
        padding: 5px;
        width: 350px;
        display:inline-block;
        cursor:pointer;
    }
    td span{
        width:70% !important;
        height:30px;
        line-height:30px;
    }
    .input-small{
        width:100px !important;
        text-align:center;
    }
    .search-input {
        width:200px;
        height:30px;
        margin-right:10px;
        margin-top:10px;
    }
    .tag-container{
        padding-top: 20px;
    }
    .tag {
        height:30px;
    }
    .addr-box {
        height:240px;
        overflow:auto;
        margin:10px;
        border: 1px solid #3c8dbc;
    }
    .job-box {
        /* display:flex;
        flex-wrap:wrap; */
        height:160px;
        margin:10px;
        overflow:auto;
        border: 1px solid #3c8dbc;
    }
    .search-result {
        border: 1px solid #3c8dbc;
        height:40px;
        margin:10px;
        padding:5px;
        overflow:auto;
    }
    .ad-wrapper{
        background-color:#ecf0f5;
        height:100%;
    }
    .ad-content{
        max-width:900px;
        margin:0 auto;
        height:100%;
        background-color:#ffffff
    }
    #tutorial-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:150;text-align:center;display:none;background-color: grey;opacity: 0.7;}
    #ajax-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:9000;text-align:center;display:none;background-color: #fff;opacity: 0.8;}
    #ajax-loading img{position:absolute;top:50%;left:50%;width:120px;height:120px;margin:-60px 0 0 -60px;}
</style>

<div class="modal fade" id="regionModal" role="dialog">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">지역선택</h4>
            </div>
            <div class="modal-body" style="height:300px;">
                <div class="fetched-data"></div> 
                <div class="text-left">
                    <input type="radio" name="region" detail="province" style="display:none;">
                    <select id="value_region_province">
                        <option value="">-시/도-</option>
                        <?php foreach($province_list as $province) {?>
                        <option value="<?=$province?>"><?=$province?></option>
                        <?php } ?>
                    </select>
                    <input type="radio" name="region" detail="city" style="display:none;">
                    <select id="value_region_city">
                        <option value="">-군/구-</option>
                    </select>
                    <input type="radio" name="region" detail="town" style="display:none;">
                    <select id="value_region_town">
                        <option value="">-읍/면/동-</option>
                    </select>
                </div>
                <div class="text-left">
                    <div class="row">
                        <div class="addr-box"  id="sel_addrs">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="onAddrConfirm()">확인</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="jobModal" role="dialog">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">직업업종의 종류</h4>
            </div>
            <div class="modal-body" style="height:300px;">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" onclick="onSearchJob()" class="btn btn-sm btn-default pull-right" style="margin-right:10px;margin-top:10px"><i class="fa fa-search"></i></button>
                        <input class="pull-right search-input" placeholder="키워드를 입력하세요" type="text" id="job_search" >
                    </div>
                    
                </div>
                <div class="row">
                    <div class="search-result" id="sel_jobs">
                        
                    </div>
                </div>

                <div class="row">
                    <div class="job-box" id="job_search_results">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="onJobConfirm()">확인</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="jobTitleModal" role="dialog">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">직함의 종류</h4>
            </div>
            <div class="modal-body" style="height:300px;">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" onclick="onSearchJobTitle()" class="btn btn-sm btn-default pull-right" style="margin-right:10px;margin-top:10px"><i class="fa fa-search"></i></button>
                        <input class="pull-right search-input" placeholder="키워드를 입력하세요" type="text" id="job_title_search" >
                    </div>
                    
                </div>
                <div class="row">
                    <div class="search-result" id="sel_job_titles">
                        
                    </div>
                </div>

                <div class="row">
                    <div class="job-box" id="job_title_search_results">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="onJobTitleConfirm()">확인</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="phoneListModal" role="dialog">
    <div class="modal-dialog" style="width:700px !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">폰 목록</h4>
            </div>
            <div class="modal-body" style="height:550px;">
                <table id="phoneListTable" class="table table-bordered" cellspacing="0">
                    <thead>
                        <tr style="background-color:#ebeaea;">
                            <th style="width:10%;">
                                번호
                            </th>
                            <th>
                                폰번호
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" onclick="onPhoneListConfirm()">확인</button>
            </div>
        </div>
    </div>
</div>

<div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
<div class="ad-wrapper">
    <div class="row ad-content">
        <div class="col-md-12" style="padding:30px;">
            <div class="text-center"><h2 class="section-title2">공개디비  광고 발송하기</h2></div>
            
            <h3 class="section-title" style="margin-top:20px;">광고개요</h3>
            

            <table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody class="text-center">
                    <tr>
                        <td style="width:5%;">광고제목</td>
                        <td style="width:20%;"><input style="width:100%;height:30px;" type="text" id="ad_title"></td>
                    </tr>
                    <tr>
                        <td style="width:5%;">세부설명</td>
                        <td style="width:20%;"><input style="width:100%;height:30px;" type="text" id="ad_desc"></td>
                    </tr>
                </tbody>
            </table>

            <h3 class="section-title" style="margin-top:20px;">타겟설정</h3>

            <table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody class="text-center">
                    <tr>
                        <td style="width:5%;">지역선택</td>
                        <td style="width:20%;">
                            <span id="addr">총 0 개 지역 선택</span>
                            <button style="width:20%;height:30px;float: right;" class="btn btn-default" type="button" onclick="onSelectAddr()">선택</button>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:5%;">직업업종</td>
                        <td style="width:20%;">
                            <span id="job">총 0 개 업종 선택</span>
                            <button style="width:20%;height:30px;float: right;" class="btn btn-default" type="button" onclick="onSelectJob()">선택</button>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:5%;">연령선택</td>
                        <td class="text-left" style="width:20%;">
                            <input class="input-small" type="text" id="from_age"> 세 ~ <input class="input-small" type="text" id="to_age"> 세
                        </td>
                    </tr>
                    <tr>
                        <td style="width:5%;">성별선택</td>
                        <td class="text-left">
                            <input type="radio" id="gen_male" name="select_keyword" onclick="onSelectGen(this)">남 
                            <input type="radio" id="gen_female" name="select_keyword" onclick="onSelectGen(this)">여
                        </td>
                    </tr>
                    <tr>
                        <td style="width:5%;">직함선택</td>
                        <td style="width:20%;">
                            <span id="job_title">총 0 개 선택</span>
                            <button style="width:20%;height:30px;float: right;" class="btn btn-default" type="button" onclick="onSelectJobTitle()">선택</button>
                        </td>
                    </tr>
                            
                </tbody>
            </table>

            <h3 class="section-title" style="margin-top:20px;">상세설정</h3>

            <table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody class="text-center">
                    <tr>
                        <td style="width:5%;">학력선택</td>
                        <td class="text-left" style="width:20%;">
                            <input type="radio" id="doctor" name="select_school" onclick="onSelectSchool(this)"> 박사
                            <input type="radio" id="master" name="select_school" onclick="onSelectSchool(this)"> 석사
                            <input type="radio" id="bachelor" name="select_school" onclick="onSelectSchool(this)"> 학사
                            <input type="radio" id="other" name="select_school" onclick="onSelectSchool(this)"> 기타
                        </td>
                    </tr>
                    <tr>
                        <td style="width:5%;">결혼여부</td>
                        <td class="text-left" style="width:20%;">
                            <input type="radio" id="married" name="select_marrage" onclick="onSelectSchool(this)"> 기혼
                            <input type="radio" id="unmarried" name="select_marrage" onclick="onSelectSchool(this)"> 미혼
                        </td>
                    </tr>
                            
                </tbody>
            </table>

            <h3 class="section-title" style="margin-top:20px;">선택건수</h3>

            <table class="table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody class="text-center">
                    <tr>
                        <td style="width:5%;">디비/요금</td>
                        <td style="width:20%;">
                            <span id="cost">0건 / 0원</span>
                        </td>
                    </tr>   
                </tbody>
            </table>

            
            <div class="row">
                <div class="col-md-12">
                    <button type="button" onclick="onSearchDB()" class="btn btn-success btn-sm" style="float:right;">검색</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="tutorial-loading"></div>
<?
include_once "_foot.php";
?>

<script>

    $("document").ready(function(){
        $("#value_region_province").on('change', function(){
            var province = $(this).val();
            if(province == '세종특별자치시'){
                var html ="<div style='margin-left:5px;margin-top:3px;float:left'><span class='region_span'>" + $("#value_region_province").val() + "</span><button type='button' style='margin-left:5px;' onclick='onDeleteAddr(this)'> X </button></div>";
                $("#sel_addrs").append(html);
            }
            $.post('/admin/location.php', {'type':'cities', 'location':province}, function(res){
                if(res.status == '1') {
                    var locations = res.locations;
                    var html = '<option value="">시군구</option>';
                    for(var i = 0; i < locations.length; i++) {
                        var location = locations[i];
                        html += '<option value="' + location + '">' + location +'</option>';
                    }
                    $("#value_region_city").html(html);
                    $("#select_region").prop('checked', true);

                }
            }, 'json');
        });

        $("#value_region_city").on('change', function(){
            var city = $(this).val();
            $.post('/admin/location.php', {'type':'towns', 'location':city}, function(res){
                if(res.status == '1') {
                    var locations = res.locations;
                    var html = '<option value="">-읍/면/동-</option>';
                    for(var i = 0; i < locations.length; i++) {
                        var location = locations[i];
                        html += '<option value="' + location + '">' + location +'</option>';
                    }
                    $("#value_region_town").html(html);
                    $("#select_region").prop('checked', true);

                }
            }, 'json');
        });

        $("#value_region_town").on('change', function(){
            $("#select_region").prop('checked', true);
            
            // var html ="<div style='margin-left:5px;margin-top:3px;float:left'><span class='region_span'>" + $("#value_region_province").val() + " " + $("#value_region_city").val() + "</span><button type='button' style='margin-left:5px;' onclick='onDeleteAddr(this)'> X </button></div>";

            var html ="<div style='margin-left:5px;margin-top:3px;float:left'><span class='region_span'>" + $("#value_region_province").val() + " " + $("#value_region_city").val() + " " + $("#value_region_town").val() + "</span><button type='button' style='margin-left:5px;' onclick='onDeleteAddr(this)'> X </button></div>";
            $("#sel_addrs").append(html);
        });

        $('#job_search').keypress(function(e){
            if(e.which == 13){
                onSearchJob();
            }
        });
        $('#job_title_search').keypress(function(e){
            if(e.which == 13){
                onSearchJobTitle();
            }
        });
    });


    var checked_key = "";
    var checked_title = "";
    $(".check_step").on('click', function(){
        if($(this).prop('checked')) {
            $(".check_step").prop('checked', false);
            $(this).prop('checked', true);
            checked_key = $(this).attr('key');
            checked_title = $(this).attr('title');
        }else {
            checked_key = "";
            checked_title = "";
        }
    });
    

    var phones = [];
    function onSearchDB() {
        var from_age = $('#from_age').val();
        var to_age = $('#to_age').val();
        var gen = '';
        if($('#gen_male').prop('checked')){
            gen = '남자';
        }
        if($('#gen_female').prop('checked')){
            gen = '여자';
        }

        var school = '';
        if($('#doctor').prop('checked')){
            school = '박사';
        }
        if($('#master').prop('checked')){
            school = '석사';
        }
        if($('#bachelor').prop('checked')){
            school = '학사';
        }
        if($('#other').prop('checked')){
            //school = '기타';
            school = '';
        }

        var marrage = '';
        if($('#married').prop('checked')){
            marrage = '기혼';
        }
        if($('#unmarried').prop('checked')){
            marrage = '미혼';
        }

        $("#ajax-loading").show();
        phones = [];
        $.post('/ad_sms/search_ad_db.php', {'addr':sel_addrs.join(','),'com_type':sel_jobs.join(','),'rank':sel_job_titles.join(','),'from_age':from_age,'to_age':to_age,'gen':gen,'school':school,'marrage':marrage}, function(res){
            $("#ajax-loading").hide();
            if(res.status == '1') {
                phones = res.phones;
                var cost = res.cost;
                var text = '<a href="javascript:showPhoneList()">' + number_format(phones.length) + '건</a>  / ' + number_format(cost) + '원';
                document.getElementById("cost").innerHTML = text;

                setPhoneListTable(phones);
            }
        }, 'json');

    }

    function onSelectAddr(){
        $('#regionModal').modal('show');
    }

    function onDeleteAddr(obj){
        obj.parentNode.remove();
    }

    var sel_addrs = [];
    function onAddrConfirm(){
        var addrNode = document.getElementById('sel_addrs');
        var count = addrNode.childElementCount;
        document.getElementById('addr').innerHTML = '총 ' + count + ' 개 지역 선택';

        sel_addrs = [];
        if(count > 0)
        {
            var nodes = document.getElementsByClassName('region_span');
            for(var i = 0; i < nodes.length;i++)
            {
                var full_addr = nodes[i].innerHTML;
                var pos = full_addr.lastIndexOf(' ');
                full_addr = full_addr.substring(0,pos);
                sel_addrs.push(full_addr);
            }
        }
        
        $('#regionModal').modal('hide');
    }

    function onSelectJob(){
        $('#jobModal').modal('show');
    }

    var sel_jobs = [];
    function onJobConfirm(){
        var addrNode = document.getElementById('sel_jobs');
        var count = addrNode.childElementCount;
        document.getElementById('job').innerHTML = '총 ' + count + ' 개 업종 선택';

        sel_jobs = [];
        if(count > 0)
        {
            var nodes = document.getElementsByClassName('job_span');
            for(var i = 0; i < nodes.length;i++)
            {
                sel_jobs.push(nodes[i].innerHTML);
            }
        }
        $('#jobModal').modal('hide');
    }

    function onSearchJob() {
        var keyword = $('#job_search').val();
        if(keyword == '')
        {
            return;
        }
        $.post('/ad_sms/search_job.php', {'keyword':keyword}, function(res){
                if(res.status == '1') {
                    var jobs = res.jobs;
                    var html = '';
                    for(var i = 0; i < jobs.length; i++) {
                        var job = jobs[i];
                        html += '<div style="margin-left:3px;float:left"><button class="btn btn-sm btn-default" onclick="onClickJob(this)">' + job + '</button></div>'
                    }
                    $("#job_search_results").html(html);
                }
            }, 'json');

    }

    function onClickJob(job) {
        var html = '<div style="margin-left:3px;float:left"><span class="job_span">' + job.innerHTML + '</span><button type="button" style="margin-left:3px;" onclick="onDeleteAddr(this)"> X </button></div>';
        $("#sel_jobs").append(html);
    }

    function onSelectJobTitle(){
        $('#jobTitleModal').modal('show');
    }

    var sel_job_titles = [];
    function onJobTitleConfirm(){
        var addrNode = document.getElementById('sel_job_titles');
        var count = addrNode.childElementCount;
        document.getElementById('job_title').innerHTML = '총 ' + count + ' 개 선택';

        sel_job_titles = [];
        if(count > 0)
        {
            var nodes = document.getElementsByClassName('job_title_span');
            for(var i = 0; i < nodes.length;i++)
            {
                sel_job_titles.push(nodes[i].innerHTML);
            }
        }
        
        $('#jobTitleModal').modal('hide');
    }

    function onSearchJobTitle() {
        var keyword = $('#job_title_search').val();
        if(keyword == '')
        {
            return;
        }
        $.post('/ad_sms/search_job_title.php', {'keyword':keyword}, function(res){
                if(res.status == '1') {
                    var jobs = res.jobs;
                    var html = '';
                    for(var i = 0; i < jobs.length; i++) {
                        var job = jobs[i];
                        html += '<div style="margin-left:3px;float:left"><button class="btn btn-sm btn-default" onclick="onClickJobTitle(this)">' + job + '</button></div>'
                    }
                    $("#job_title_search_results").html(html);
                }
            }, 'json');

    }

    function onClickJobTitle(job) {
        var html = '<div style="margin-left:3px;float:left"><span class="job_title_span">' + job.innerHTML + '</span><button type="button" style="margin-left:3px;" onclick="onDeleteAddr(this)"> X </button></div>';
        $("#sel_job_titles").append(html);
    }

    function showPhoneList(){
        $('#phoneListModal').modal('show');
    }

    function setPhoneListTable(phoneList){
        $('#phoneListTable').dataTable().fnClearTable();
        $('#phoneListTable').dataTable().fnDestroy();
        var table = $('#phoneListTable').DataTable({
                "lengthChange": false,
                data: phoneList,
                columns: [
                    { "data": "no", "defaultContent": "" },
                    { "data": "enc_no", "defaultContent": "" }
                ], 
                //"paging": false,  
                "searching":false,
                scrollCollapse: true,
                // "jQueryUI": true,  
                //"stripeClasses": ['success', 'info'],  //
                "columnDefs": [
                {
                    "targets": [0],
                    "visible": true,
                    "searchable": true,
                }
                ],
                
                language: {
                    "sProcessing": "처리중...",
                    "sLengthMenu": " _MENU_ ",
                    "sZeroRecords": "검색결과가 없습니다.",
                    "sInfo": " _START_ ~ _END_ ，총 _TOTAL_ 건",
                    "sInfoEmpty": " 0 ~ 0 ，총 0 건",
                    "sInfoFiltered": "( _MAX_ )",
                    "sInfoPostFix": "",
                    "sSearch": "검색:",
                    "sUrl": "",
                    "sEmptyTable": "검색결과가 없습니다",
                    "sLoadingRecords": "로딩중...",
                    "sInfoThousands": ",",
                    "oPaginate": {
                        "sFirst": "처음",
                        "sPrevious": "이전",
                        "sNext": "다음",
                        "sLast": "마지막"
                    }
                },
            })
    }

    function onPhoneListConfirm(){
        $('#phoneListModal').modal('hide');
    }
</script>