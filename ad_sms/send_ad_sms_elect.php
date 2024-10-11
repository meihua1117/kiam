<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/ad_sms/include/_header.inc.php";

// 광역시도 목록
$province_list = array();
$query = "SELECT province FROM gn_cities group by province";
$res = mysqli_query($self_con,$query);
while($row = mysqli_fetch_array($res)) {
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
    .section-title {
        color: #000;
        font-family: Inter;
        font-size: 18px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }
    .section-title2 {
        color: #000;
        font-family: Inter;
        font-size: 16px;
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }
    .normal-label {
        color: #000;
        font-family: Inter;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
    .second-label{
        width:100px;
        padding-left:35px;
        float:left;
        height:32px;
        line-height:32px;
    }
    .result-span{
        width:135px;
        display:inline-block;
        border:solid 1px #aaa4a4;
        border-radius:5px;
        height:32px;
        line-height:32px;
        padding-left:10px;
    }
    .btn-sel {
        width:93px;
        height:32px;
        float: right;
    }
    .btn-confirm {
        width:47px;
        height:32px;
        float: right;
    }
    .btn-send{
        float:right;
        margin-top:10px;
        border-radius: 5px;
        background: #384ACC;
        height:32px;
        width:93px;
        border:solid 1px;
        color:white
    }
    td span{
        width:70% !important;
        height:30px;
        line-height:30px;
        margin-left:30px;
    }
    .input-small{
        height:32px;
        width:40px !important;
        text-align:center;
        border-radius: 5px;
        border: 1px solid #AAA4A4;
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
    .coming-wrapper {
        width:100%;
        overflow:hidden;
        position:relative;
    }
    .coming-popup{
        background:#ccccccaa;
        position:absolute;
        width:100%;
        height:100%;
        right:0;
        top:0;
    }
    .coming-content{
        padding:20px;
        margin:50px 130px 50px 130px;
        background:#ffffff;
    }
    .ad-wrapper{
        background-color:#ecf0f5;
    }
    .ad-content{
        max-width:900px;
        margin:0 auto;
        height:100%;
        background-color:#ffffff
    }
    .btn-toggle-left {
        border-top-left-radius:3px;
        border-bottom-left-radius:3px;
        background-color:#D9D9D9;
        color:#000000;
        font-size:14px;
        height:30px;
        border:1px solid #AAA4A4;
    }
    .btn-toggle-left.active{
        background-color:#384ACC;
        color:#ffffff;
        border-color:#384ACC;
    }
    .btn-toggle-right {
        border-top-right-radius:3px;
        border-bottom-right-radius:3px;
        background-color:#D9D9D9;
        color:#000000;
        margin-left:-5px;
        font-size:14px;
        height:30px;
        border:1px solid #AAA4A4;
    }
    .btn-toggle-right.active{
        background-color:#384ACC;
        color:#ffffff;
        border-color:#384ACC;
    }
    .hint {
        color: #656464;
        font-family: Inter;
        font-size: 11px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
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
                        <option value="">-시/군/구-</option>
                    </select>
                    <input type="radio" name="region" detail="town" style="display:none;">
                    <select id="value_region_town">
                        <option value="">-읍/면/동-</option>
                    </select>
                    <button class="btn btn-sm" type="button" onclick="onSelectRegion()">선택</button>
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
                <table id="phoneListTable" class="table" cellspacing="0">
                    <thead>
                        <tr style="background-color:#ebeaea;">
                            <th style="width:5%;">
                                번호
                            </th>
                            <th style="width:200%;">
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

<div >
    <div class="row" style="max-width:700px;margin:0 auto;">
        <div class="col-md-12">
            <div style="display:inline-block;margin-left:24px;">
                <h3 class="section-title">공개DB 검색</h3>
                <div style="width: 100%;height: 4px;flex-shrink: 0;background: #000;"></div>
            </div>
            
            <div style="height:1px;width:100%;background: #E3DEDE;"></div>

            <h3 class="section-title2" style="margin-top:20px;margin-left:28px">광고개요</h4>
            
            <div style="width:100%; display:table">
                <label for="ad_title" style="display:table-cell;width:100px;padding-left:35px;" class="normal-label">제목</label>
                <input type="text" id="ad_title" style="display:table-cell; width:100%;height:32px;border:solid 1px#aaa4a4;border-radius:5px;" />
            </div>

            <div style="width:100%; display:table;margin-top:15px;">
                <label for="ad_title" style="display:table-cell;width:100px;padding-left:35px;" class="normal-label">설명</label>
                <input type="text" id="ad_title" style="display:table-cell; width:100%;height:32px;border:solid 1px#aaa4a4;border-radius:5px;" />
            </div>

            <div style="height:1px;width:100%;background: #E3DEDE;margin-top:30px;"></div>

            <div style="width:100%; margin-top:15px;" class="text-left">
                <label class="section-title2" style="width:80px;float:left;padding-top:10px;margin-left:28px;">타겟설정</label>
                <div style="width:100%;">
                    <div><span class="hint">* 위에서부터 아래로 순서대로 설정해주세요.</span></div>
                    <div><span class="hint">* 순서가 바뀌면 F5(리프레쉬)해서 다시 시작해주세요.</span></div>
                </div>
            </div>

            <div class="text-left" style="margin-top:20px;">
                <label class="normal-label second-label">지역</label>
                <span id="addr" class="result-span">총 0 명</span>
                <button class="btn btn-default btn-sel" type="button" onclick="onSelectAddr()">선택</button>
            </div>

            <div class="coming-wrapper">
                <div class="text-left" style="margin-top:10px;">
                    <label class="normal-label second-label">직업</label>
                    <span id="job" class="result-span">총 0 명</span>
                    <button class="btn btn-default btn-sel" type="button" onclick="onSelectJob()">선택</button>
                </div>

                <div class="text-left" style="margin-top:10px;">
                    <label class="normal-label second-label">연령</label>
                    <span id="age" class="result-span">총 0 명</span>
                    <button style="margin-left:5px;" class="btn btn-default btn-confirm" type="button" onclick="onAgeConfirm()">확인</button>
                    <div style="float:right;">
                        <input class="input-small" type="text" id="from_age" onchange="onChangeAge()" > 세 ~ <input class="input-small" type="text" id="to_age" onchange="onChangeAge()"> 세
                    </div>
                </div>

                <div class="text-left" style="margin-top:10px;">
                    <label class="normal-label second-label">성별</label>
                    <span id="gen" class="result-span">총 0 명</span>
                    <button style="margin-left:5px;" class="btn btn-default btn-confirm" type="button" onclick="onGenConfirm()">확인</button>
                    <div style="float:right;height:30px;">
                        <button type="button" class="btn-toggle-left" onclick="onGenClickMale()" id="btn_male">남성</button>
                        <button type="button" class="btn-toggle-right" onclick="onGenClickFemale()" id="btn_female">여성</button>
                    </div>
                </div>

                
                <div class="text-left" style="margin-top:10px;">
                    <label class="normal-label second-label">직함</label>
                    <span id="job_title" class="result-span">총 0 명</span>
                    <button class="btn btn-default btn-sel" type="button" onclick="onSelectJobTitle()">선택</button>
                </div>

                <h3 class="section-title2" style="margin-top:20px;margin-left:28px">상세설정</h3>

                <div class="text-left" style="margin-top:10px;">
                    <label class="normal-label second-label">학력</label>
                    <span id="school" class="result-span">총 0 명</span>
                    <button style="margin-left:5px;" class="btn btn-default btn-confirm" type="button" onclick="onSchoolConfirm()">확인</button>
                    <div style="float:right;height:30px;">
                        <input type="checkbox" id="doctor" onclick="onSelectSchool(this)"> 박사
                        <input type="checkbox" id="master" onclick="onSelectSchool(this)"> 석사
                        <input type="checkbox" id="bachelor" onclick="onSelectSchool(this)"> 학사
                        <input type="checkbox" id="other" onclick="onSelectSchool(this)"> 기타
                    </div>
                </div>

                <div class="text-left" style="margin-top:10px;">
                    <label class="normal-label second-label">결혼</label>
                    <span id="marrage" class="result-span">총 0 명</span>
                    <button style="margin-left:5px;" class="btn btn-default btn-confirm" type="button" onclick="onMarrageConfirm()">확인</button>
                    <div style="float:right;height:30px;">
                        <button type="button" class="btn-toggle-left" onclick="onClickMarried()" id="btn_married">기혼</button>
                        <button type="button" class="btn-toggle-right" onclick="onClickUnmarried()" id="btn_unmarried">미혼</button>
                    </div>
                </div>

                <div class="coming-popup">
                    <div class="coming-content" id="coming-content">
                        <button type="button" class="close" onclick="onCloseComingPopup()">X</button>
                        <div class="text-center"><h3 class="section-title">공개DB 검색 시스템</h3></div>
                        <div class="text-center"><h4 class="section-title">공개DB 검색 시스템은 현재 지역검색만</br>지원합니다.이후 검색항목 확장이 되는</br>대로 추가 오픈하겠습니다.</h4></div>
                    </div>
                </div>
            </div>

            <button type="button" onclick="onSearchDB()" class="btn-send">검색하기</button>

            <h3 class="section-title2" style="margin-top:50px;margin-left:28px">건수별 요금</h4>

            <div class="text-left" style="margin-top:10px;">
                <label class="normal-label second-label">디비</label>
                <span id="count" class="result-span">0 건</span>                
            </div>

            <div class="text-left" style="margin-top:10px;">
                <label class="normal-label second-label">요금</label>
                <span id="cost" class="result-span">0 원</span>
            </div>
            
            <button type="button" onclick="onClickSend()" class="btn-send">전송하기</button>
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
                    var html = '<option value="">-시/군/구-</option>';
                    for(var i = 0; i < locations.length; i++) {
                        var location = locations[i];
                        html += '<option value="' + location + '">' + location +'</option>';
                    }
                    $("#value_region_city").html(html);

                    html = '<option value="">-읍/면/동-</option>';
                    $("#value_region_town").html(html);
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

    function onSelectRegion(){
        var region = $("#value_region_province").val();
        if(region == "")
        {
            return;
        }
        if($("#value_region_city").val() != "")
        {
            region = region + " " + $("#value_region_city").val();
        } 
        if($("#value_region_town").val() != "")
        {
            region = region + " " + $("#value_region_town").val();
        }
        var html ="<div style='margin-left:5px;margin-top:3px;float:left'><span class='region_span'>" + region + "</span><button type='button' style='margin-left:5px;' onclick='onDeleteAddr(this)'> X </button></div>";
            $("#sel_addrs").append(html);
    }
    

    var phones = [];
    var search_addr='';
    

    function onSearchDB() {
        
        search_addr = sel_addrs.join(',');
       
        $("#ajax-loading").show();
        phones = [];
        $.post('/ad_sms/search_ad_db.php', {'addr':search_addr,'only_count':'0'}, function(res){
            $("#ajax-loading").hide();
            if(res.status == '1') {
                phones = res.phones;
                var cost = res.cost;
                var text = '<a href="javascript:showPhoneList()">' + number_format(phones.length) + ' 건</a>';
                document.getElementById("count").innerHTML = text;

                text = number_format(cost) + ' 원'
                document.getElementById("cost").innerHTML = text;
                var enc_phones = [];
                for(var i=0;i<res.phones.length;i++)
                {
                    var phone = res.phones[i]['phone'];
                    var enc_phone = phone.substr(0,3) + '-' + phone.substr(3,4) + '-' + '****';
                    var record = {'no':i+1, 'enc_no':enc_phone};
                    enc_phones.push(record);
                }
                setPhoneListTable(enc_phones);
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
        
        $('#regionModal').modal('hide');
        sel_addrs = [];
        if(count == 0)
        {
            document.getElementById('addr').innerHTML = '총 0 명';
            return;
        }
        
        
        var nodes = document.getElementsByClassName('region_span');
        for(var i = 0; i < nodes.length;i++)
        {
            var full_addr = nodes[i].innerHTML;
            var pos = full_addr.lastIndexOf(' ');
            // if(pos != -1)
            // {
            //     full_addr = full_addr.substring(0,pos);
            // }
            sel_addrs.push(full_addr);
        }
        
        initSearchValues();
        search_addr = sel_addrs.join(',');
        
        //onSearchDB();
        $("#ajax-loading").show();
        phones = [];
        $.post('/ad_sms/search_ad_db.php', {'addr':search_addr,'only_count':'1'}, function(res){
            $("#ajax-loading").hide();
            if(res.status == '1') {
                count = res.count;
                document.getElementById('addr').innerHTML = '총 ' + count + ' 명';
            }
        }, 'json');
    }

    function initSearchValues(){
        search_addr = '';
    }
    function onSelectJob(){
        $('#jobModal').modal('show');
        onSearchJob();
    }

    var sel_jobs = [];
    function onJobConfirm(){
        $('#jobModal').modal('hide');
    }

    function onSearchJob() {
        var keyword = $('#job_search').val();
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
        onSearchJobTitle();
    }

    function onGenClickMale(){
        if(!$('#btn_male').hasClass('active'))
        {
            $('#btn_male').addClass('active');
            $('#btn_female').removeClass('active');
        }
        else {
            $('#btn_male').removeClass('active');
        }
    }

    function onGenClickFemale(){
        if(!$('#btn_female').hasClass('active'))
        {
            $('#btn_female').addClass('active');
            $('#btn_male').removeClass('active');
        }
        else {
            $('#btn_female').removeClass('active');
        }
    }

    function onClickMarried(){
        if(!$('#btn_married').hasClass('active'))
        {
            $('#btn_married').addClass('active');
            $('#btn_unmarried').removeClass('active');
        }
        else {
            $('#btn_married').removeClass('active');
        }
    }

    function onClickUnmarried(){
        if(!$('#btn_unmarried').hasClass('active'))
        {
            $('#btn_unmarried').addClass('active');
            $('#btn_married').removeClass('active');
        }
        else {
            $('#btn_unmarried').removeClass('active');
        }
    }

    var sel_job_titles = [];
    function onJobTitleConfirm(){
        
        $('#jobTitleModal').modal('hide');
    }

    function onSearchJobTitle() {
        var keyword = $('#job_title_search').val();
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

    function onSelectGen(){
        
    }
    function onSelectSchool(){
        
    }
    function onSelectMarrage(){
        
    }
    function onChangeAge(){
        
    }

    function onGenConfirm(){
        

    }
    function onSchoolConfirm(){
        
    }
    function onAgeConfirm(){
        
    }
    function onMarrageConfirm(){
        
    }

    function onClickSend(){

    }

    function onCloseComingPopup(){
        $('#coming-content').css('display','none');
    }

</script>