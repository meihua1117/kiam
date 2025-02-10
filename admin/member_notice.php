<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";

function parseDomain($url) {
    $urlInfo = parse_url($url);
    $host = $urlInfo['host'];
    $host = explode(".", $host);
    return $host[0];
}
$curYear = date("Y");

// 셀링분양 특정도메인 목록
$selling_domains = array();
$query = "SELECT sub_domain FROM Gn_Service GROUP BY sub_domain";
$res = mysqli_query($self_con,$query);
while($row = mysqli_fetch_array($res)) {
    $selling_domains[] = $row['sub_domain'];
}
// IAM분양 특정도메인 목록
$iam_selling_domains = array();
$query = "SELECT sub_domain FROM Gn_Iam_Service GROUP BY sub_domain";
$res = mysqli_query($self_con,$query);
while($row = mysqli_fetch_array($res)) {
    $iam_selling_domains[] = $row['sub_domain'];
}
/*$query = "SELECT site FROM Gn_Member GROUP BY site";
$res = mysqli_query($self_con,$query);
while($row = mysqli_fetch_array($res)) {
    $iam_selling_domains[] = $row['site'];
}*/
// 광역시도 목록
$province_list = array();
$query = "SELECT province FROM gn_cities group by province";
$res = mysqli_query($self_con,$query);
while($row = mysqli_fetch_array($res)) {
    $province_list[] = $row['province'];
}

// 검색키워드 목록
$keyword_list = array();
?>
<style>
    td {
        vertical-align:middle !important;
        max-width:800px;
    }
    td input {
        margin-left:10px !important;
    }
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
        border: 1px solid #747373;
    }
    .section-title.active {
        background:#F8CBAD;
    }
    .section-title, .section-title2 {
        border: 1px solid #3c8dbc;
        padding: 5px;
        width: 320px;
        display:inline-block;
        cursor:pointer;
    }
    a {
        color:black;
    }
    .img_view {
        height: 140px;
        border: 1px solid #CCC;
        margin-bottom: 2px;
    }
    textarea {
        height:200px;
    }
    .img_view img {
        width: 100%;
        height: 140px;
    }
    .div_240 {
        width: 240px;
        margin:10px;
        margin-top: 20px;
        display:inline-block;
        vertical-align:top;
    }
    .b1 {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 5px;
        color: #000;
    }
    .div_240 input[type=text],select {
        height: 30px;
    }
    .open_1 {
        position: absolute;
        z-index: 10;
        background-color: #FFF;
        display: none;
        border: 1px solid #000;
    }
    .open_2 {
        padding-left: 5px;
        height: 30px;
        cursor: move;
    }
    .open_2_1 {
        float: left;
        line-height: 30px;
        font-size: 16px;
        font-weight: bold;
        list-style: none;
        font-family: "KoPub Dotum";
    }
    .open_2_2 {
        float: right;
        list-style: none;
        font-family: "KoPub Dotum";
    }
    .open_3 {
        padding: 10px;
    }
    .keyword-item {
        list-style:none;
        float:left;
    }
    .loading_div {
        display:none;
        position: fixed;
        left: 50%;
        top: 50%;
        z-index: 1000;
    }
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
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
          <h1>
            회원문자공지
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">회원문자공지</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <button class="btn btn-primary pull-right" onclick="gotoCheckedMemberList()">수발신목록</button>
                </div>
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <p><h3 class="section-title2">셀프 폰문자 발송대상 선택</h3></p>
                        <table id="example1" class="table table-bordered">
                            <tbody class="text-center">
                                <tr>
                                    <td><input type="checkbox" class="check" id="check_phone"></td>
                                    <td>폰번호</td>
                                    <td class="text-left">
                                        <input type="radio" name="phone" detail="number" style="display:none;">
                                        <input type="text" name="phone" id="value_phone_number" placeholder="01012345678,01012345678" style="width:90%;">
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" class="check" id="check_member" checked></td>
                                    <td>앱설치</td>
                                    <td class="text-left">
                                        <!-- <input type="checkbox" name="member" detail="all" >전체 -->
                                        <input type="checkbox" name="member" detail="owner" id="owner_app">소유/앱
                                        <input type="checkbox" name="member" detail="add">추가폰
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="13"><input type="checkbox" id="check_search" class="check"></td>
                                    <td>회원레벨</td>
                                    <td class="text-left">
                                        <input type="checkbox" name="level" detail="all">전체
                                        <input type="checkbox" name="level" detail="normal">일반
                                        <input type="checkbox" name="level" detail="work">사업
                                        <input type="checkbox" name="level" detail="speaker">강사
                                        <input type="checkbox" name="level" detail="painter">홍보
                                    </td>
                                </tr>
                                <tr>
                                    <td rowspan="2">소속도메인</td>
                                    <td class="text-left">
                                        <input type="checkbox" name="selling_level" detail="seller_domain" id="radio_selling_level_seller_domain">셀링&nbsp;&nbsp;
                                        <select id="value_selling_level_seller_domain">
                                            <option value="obmms">본부</option>
                                            <?php foreach($selling_domains as $domain) {?>
                                            <option value="<?=parseDomain($domain)?>"><?=$domain?></option>
                                            <?php }?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-left">
                                        <input type="checkbox" name="iam_level" detail="seller_domain" id="radio_iam_level_seller_domain">아이엠
                                        <select id="value_iam_level_seller_domain">
                                            <option value="obmms">본부</option>
                                            <?php foreach($iam_selling_domains as $domain) {?>
                                            <option value="<?=parseDomain($domain)?>"><?=$domain?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>지역구분</td>
                                    <td class="text-left">
                                        <input type="checkbox" name="select_region" id="select_region">
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
                                    </td>
                                </tr>
                                <tr>
                                    <td>연령구분</td>
                                    <td class="text-left">
                                        <input type="checkbox" name="age" detail="all">전체
                                        <input type="checkbox" name="age" detail="special" p1="10">10-20대
                                        <input type="checkbox" name="age" detail="special" p1="20">20-30대
                                        <input type="checkbox" name="age" detail="special" p1="30">30-40대
                                        <input type="checkbox" name="age" detail="special" p1="40">40-50대
                                        <input type="checkbox" name="age" detail="special" p1="50">50-60대
                                        <input type="checkbox" name="age" detail="special" p1="60">60이상
                                        <input type="text" id="from_age_special" style="display:none;">
                                        <input type="text" id="to_age_special" style="display:none;">
                                    </td>
                                </tr>
                                <tr>                                    
                                    <td>상품구분</td>
                                    <td class="text-left">
                                        <input type="checkbox" name="product" detail="all">전체
                                        <input type="checkbox" name="product" detail="standard">스탠다드
                                        <input type="checkbox" name="product" detail="professional">프로패셔널
                                        <input type="checkbox" name="product" detail="enterprise">엔터프라이즈
                                        <input type="checkbox" name="product" detail="year-professinal">약정
                                        <input type="checkbox" name="product" detail="dber">디버별도
                                    </td>
                                </tr>
                                <tr>
                                    <td >사업구분</td>
                                    <td class="text-left">
                                        <input type="checkbox" name="selling" detail="all">전체
                                        <input type="checkbox" name="selling" detail="normal">이용자
                                        <input type="checkbox" name="selling" detail="reseller">리셀러
                                        <input type="checkbox" name="selling" detail="seller">분양자
                                    </td>
                                </tr>
                                <tr>
                                    <td>추천건수</td>
                                    <td class="text-left">
                                        <input type="checkbox" name="recommend" detail="count" from="" to="" id="recommend_manual_count">
                                        <input type="text" onchange="onChangeRecommend(this, 'from')" placeholder="000">-<input type="text" placeholder="0000" onchange="onChangeRecommend(this, 'to')">건
                                        <input type="text" style="display:none;" id="from_recommend_count">
                                        <input type="text" style="display:none;" id="to_recommend_count">
                                    </td>
                                </tr>
                                <tr style="display: none;">
                                    <td rowspan="2">IAM등급</td>
                                    <td class="text-left">
                                        <input type="radio" name="iam_level" detail="all">사업자전체
                                        <input type="radio" name="iam_level" detail="reseller">리셀러
                                        <input type="radio" name="iam_level" detail="seller_start">분양자(진행)
                                        <input type="radio" name="iam_level" detail="seller_stop">분양자(정지)
                                    </td>
                                </tr>
                                <tr style="display: none;">
                                    <td class="text-left">
                                        <input type="radio" name="iam_level" detail="free">FREE
                                        <input type="radio" name="iam_level" detail="pay">BASIC
                                        <input type="radio" name="iam_level" detail="person">전문가
                                        <input type="radio" name="iam_level" detail="work">회사용
                                        <input type="radio" name="iam_level" detail="company">단체용
                                    </td>
                                </tr>

                                <tr>
                                    <td>검색키워드</td>
                                    <td class="text-left">
                                        <input type="radio" detail="all" name="select_keyword" onclick="onSelectKeyword(this)">전체
                                        <input type="radio" detail="special" id="radio_search_keyword" name="select_keyword" onclick="onSelectKeyword(this)">검색
                                        <input type="text" placeholder="검색어를 입력하세요." id="search_keyword">
                                        <div class="row">
                                            <input type="text" id="value_keyword_special" style="display:none;">
                                            <input type="text" id="value_keyword_all" style="display:none;">
                                            <ul class="col-md-12" style="width:700px;" id="keywords_wrapper">
                                            <?php foreach($keyword_list as $keyword) {?>
                                                <li class="keyword-item">
                                                <input class="keyword_check" type="checkbox" name="keyword" style="padding:10px;" data="<?=$keyword?>" onclick="onClickKeyword(this)"><label class="label_keyword"><?=$keyword?></label>
                                                </li>
                                            <?php } ?>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12" style="margin-top:10px;">
                                <div style="float: right;padding:10px;border:1px solid #3c8dbc"><span id="total_count">0</span>명</div>
                                <div style="float: right;padding:10px;border:1px solid #3c8dbc;border-right: 0px;">발송건수</div>
                            </div>
                        </div>
                        <form name="sub_4_form" id="sub_4_form" action="" method="post" enctype="multipart/form-data">
                            <input type="checkbox" name="ssh_check" onclick="deny_msg_click(this,1);type_check();numchk('4')" style="display:none;">
                            <input type="checkbox" name="ssh_check" onclick="deny_msg_click(this,1);type_check();numchk('4')" style="display:none;">
                            <input type="checkbox" name="ssh_check" onclick="deny_msg_click(this,1);type_check();numchk('4')" style="display:none;">
                            <input type="checkbox" name="ssh_check" onclick="deny_msg_click(this,1);type_check();numchk('4')" style="display:none;">
                            <input type="checkbox" name="deny_wushi[]" checked="" onclick="numchk('0');type_check()" style="display:none;">
                            <input type="checkbox" name="deny_wushi[]" checked="" onclick="numchk('0');type_check()" style="display:none;">
                            <input type="checkbox" name="deny_wushi[]" checked="" onclick="numchk('0');type_check()" style="display:none;">
                            <div class="text-center"><h3 class="section-title active" target="Text"><i class="fa fa-circle-o"></i>셀프 폰문자 메시지 발송</h3></div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="div_240">
                                        <div class="b2" style="float:left">이미지 미리보기</div> 
                                        <div style="float:right">
                                            <button id="show1" onclick="showImage('');return false;">1</button>
                                            <button id="show2" onclick="showImage('1');return false;">2</button>
                                            <button id="show3" onclick="showImage('2');return false;">3</button>
                                        </div>
                                        <div id="preview_wrapper" class="img_view" style="display:inline-block;width:100%;">  
                                            <div id="preview_fake" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);" > 
                                                <img id="preview" onload="onPreviewLoad(this)"/>  
                                            </div>
                                        </div>
                                        <img id="preview_size_fake" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);visibility:hidden; height:0;"/>
                                        <div class="b2">형식 : jpg, gif, png / 크기 : 640X480이하</div>
                                        <div class="div_2px">
                                        <input type="file" name="upimage" onChange="uploadImage(this, 'upimage_str', '')"  />
                                        <div><input type="hidden" name="upimage_str" id="upimage_str"/></div>
                                        <input type="file" name="upimage1" onChange="uploadImage(this, 'upimage_str1', '1')"/>
                                        <div><input type="hidden" name="upimage_str1" id="upimage_str1"/></div>
                                        <input type="file" name="upimage2" onChange="uploadImage(this, 'upimage_str2', '2')" /></div>
                                        <div><input type="hidden" name="upimage_str2" id="upimage_str2"/></div>
                                        <div class="a3" >
                                            <div class="b1">예약서비스</div>
                                            <div>
                                                <input type="date"  name="rday" onfocus="type_check();" onblur="check_date('<?=date("Ymd")?>')" placeholder="예약발송(일)" id="rday" value="" style="width:150px;" />
                                                <br>
                                                <select name="htime" style="width:50px;">
                                                    <?
                                                    for($i=9; $i<22; $i++)
                                                    {
                                                        $iv=$i<10?"0".$i:$i;
                                                        $selected = "";
                                                        ?>
                                                        <option value="<?=$iv?>" <?=$selected?>><?=$iv?></option>                                            
                                                        <?
                                                    }
                                                    ?>
                                                </select>
                                                <select name="mtime" style="width:50px;">
                                                    <?
                                                    for($i=0; $i<31; $i+=30)
                                                    {
                                                        $iv=$i==0?"00":$i;
                                                        $selected = "";
                                                        ?>
                                                        <option value="<?=$iv?>" <?=$selected?>><?=$iv?></option>                                            
                                                        <?
                                                    }
                                                    ?>                                    
                                                </select>
                                            </div>
                                            <div style="margin-top: 10px;"><input type="text" style="width: 150px;" name="self_memo" id="self_memo" placeholder="발신처"/></div>                                    
                                        </div>
                                    <div>
                                </div>                        
                            </div>
                            <div class="div_240">
                                <div class="b1">문자입력</div>
                                <div>
                                    <input type="text" name="title" id="title" itemname="제목" required="" placeholder="제목" style="width: 100%; background-color: rgb(200, 237, 252);" value="">
                                </div>
                                <div>
                                    <textarea name="txt" itemname="내용" id="txt" required="" placeholder="내용" onkeydown="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);" onkeyup="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();" onfocus="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();" style="background-color: rgb(200, 237, 252);width:100%;"></textarea>
                                    <input type="hidden" name="onebook_status" value="N" />
				                    <input type="hidden" name="onebook_url" value="" />
                                </div>
                                <div>
                                    <a href="javascript:saveMessage()">문자저장하기</a>
                                    <div style="float:right;">
                                        <span style="color:red" class="wenzi_cnt">0</span> byte 
                                    </div>
                                </div>
                                <div>
                                    <a href="javascript:void(0)" onclick="window.open('/msg_serch.php?status=1&amp;status2=1','msg_serch','top=0,left=0,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')">
                                문자불러오기</a>				                                        	
                                </div>
                                <div>
                                    <a href="javascript:void(0)" onclick="ml_preview('txt','0','미리보기')">문자미리보기</a>
                                    <label style="float:right;"><input type="radio" name="save_mms" value="Y">문자발송후 저장하기</label>
                                </div>
                                <div id="btnSendText" name="btnSendText"><a href="javascript:void(0)" onclick="send_message(sub_4_form, 1)"><img src="/images/sub_button_68.jpg"></a></div>
                            </div>
                        </form>
                        <div class="text-center"><h3 class="section-title" target="Step"><i class="fa fa-circle-o"></i>퍼널예약메시지 설정</h3></div>
                        <table class="table table-bordered">
                            <tbody class="text-center">
                                <tr>
                                    <td>[예약메시지]</td>
                                    <td><input type="text" readonly style="width:100%;height:30px;margin-left:0px !important;" id="step_title" name="step_title"></td>
                                    <input type="hidden" id="step_idx" name="step_idx" />
                                    <td><button class="btn btn-default" type="button" onclick="onStepReservation()">퍼널예약관리 조회</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <div style="margin-top: 10px;"><input type="text" style="width: 150px;" name="self_step_memo" id="self_step_memo" placeholder="발신처"/></div>
                        <div id="btnSendStep" style="display:none;float:right;" name="btnSendStep"><a href="javascript:void(0)" onclick="send_message(sub_4_form, 2)"><img src="/images/sub_button_68.jpg"></a></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
</div>

<div id="open_recv_div" class="open_1" style="top: 1464px; left: 551.5px;">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_recv_title open_2_1">미리보기</li>
    	<li class="open_2_2"><a href="javascript:void(0)" onclick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg"></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;"></div>
</div>
<script>
    var curYear = '<?=date("Y")?>';
    var data = {};
    var searchMemberParam = [];
    var searchAllParam = [];
    var xhr;
    $(function() {
        var contHeaderH = $(".main-header").height();
        var navH = $(".navbar").height();
        if(navH != contHeaderH)
            contHeaderH += navH - 50;
        $(".content-wrapper").css("margin-top",contHeaderH);
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 200;
        if(height < 375)
            height = 375;
        $(".box-body").css("height",height);
    });
    function showImage(cursor) {
        $('#preview').attr("src","../"+$("[name='upimage_str"+cursor+"']").val());
    }

    function removeParam(key, detail, p1="") {
        if(key == "select_region")
        {
            var index = 0;
            while(true)
            {
                if(index >= searchAllParam.length)
                    break;
                if(searchAllParam[index].key == "region")
                {
                    searchAllParam.splice(index, 1);
                    continue;
                }
                index++;
            }        
        }
        else
        {
            for(var i = 0; i < searchAllParam.length; i++) {
                if(searchAllParam[i].key == key && searchAllParam[i].details.indexOf(detail) > -1 && searchAllParam[i].p1 == p1) {
                    
                    searchAllParam.splice(i, 1);
                    break;
                }
            }
        }

    }

    function UncheckMember(key, detail)
    {
        for(var i = 0; i < searchMemberParam.length; i++) {
            if(searchMemberParam[i].key == key && searchMemberParam[i].details.indexOf(detail) > -1) {
                searchMemberParam.splice(i, 1);
                break;
            }
        }      
    }
    function checkParam(key, detail, p1="") {
        var beExist = false;
        if(key == 'member') {
            if(searchMemberParam.length > 0) {
                // searchMemberParam[0].key = key;
                // searchMemberParam[0].details = [detail];
                var param = {};
                param.key = key;
                param.details = [detail];
                param.value = $("#value_" + key + "_" + detail).val();
                param.from = $("#from_" + key + "_" + detail).val();
                param.to = $("#to_" + key + "_" + detail).val();
                param.p1 = "";
                searchMemberParam.push(param);
            }else {
                var param = {};
                param.key = key;
                param.details = [detail];
                param.value = $("#value_" + key + "_" + detail).val();
                param.from = $("#from_" + key + "_" + detail).val();
                param.to = $("#to_" + key + "_" + detail).val();
                param.p1 = "";
                searchMemberParam.push(param);
            }
        }else {

            if(key == "region" || key == "keyword" || key == "recommend" || detail == "seller_domain")
            {
                for(var i = 0; i < searchAllParam.length; i++) 
                {
                    if(searchAllParam[i].key == key && searchAllParam[i].details.indexOf(detail) > -1) {
                        searchAllParam[i].key = key;
                        searchAllParam[i].details = [detail];
                        searchAllParam[i].p1 = p1;
                        if(key == "keyword") {
                            var value = "";
                            $(".keyword_check").each(function(){
                                if($(this).prop('checked')) {
                                    if(value == "") {
                                        value = $(this).attr('data');
                                    }else {
                                        value += "," + $(this).attr('data');
                                    }
                                }
                            });
                            $("#value_keyword_special").val(value);
                            $("#value_keyword_all").val(value);
                        }
                        searchAllParam[i].value = $("#value_" + key + "_" + detail).val();
                        searchAllParam[i].from = $("#from_" + key + "_" + detail).val();
                        searchAllParam[i].to = $("#to_" + key + "_" + detail).val();
                        return;
                    }
                }
            }

            var param = {};
            param.key = key;
            param.details = [detail];
            param.p1 = p1;
            if(key == "keyword") {
                var value = "";
                $(".keyword_check").each(function(){
                    if($(this).prop('checked')) {
                        if(value == "") {
                            value = $(this).attr('data');
                        }else {
                            value += "," + $(this).attr('data');
                        }
                    }
                });
                $("#value_keyword_special").val(value);
                $("#value_keyword_all").val(value);
            }
            param.value = $("#value_" + key + "_" + detail).val();
            param.from = $("#from_" + key + "_" + detail).val();
            param.to = $("#to_" + key + "_" + detail).val();
            searchAllParam.push(param);
            
        }
    }

    function ajaxSearch() {
        var searchParam = [];
        if($("#check_phone").prop('checked')) {
            var param = {};
            param.key = "phone";
            param.details = ['number'];
            param.value = $("#value_phone_number").val();
            param.from = "";
            param.to = "";
            param.p1 = "";
            searchParam.push(param);
            return;
        }
        if($("#check_member").prop('checked')) {
            searchParam = searchMemberParam;
        }
        if($("#check_search").prop('checked')) {
            searchParam = searchParam.concat(searchAllParam);
        }
        if(searchParam.length == 0) {
            return;
        }
        var _param = JSON.stringify(searchParam);
        $.post("/admin/ajax/member_notice_count.php", {'searchParam':_param}, function(res){
            if(res.status == '1') {
                $("#total_count").html(res.count);
            }
        }, 'json');
    }

    function getMembersCount(key, detail, param="") {
        checkParam(key, detail, param);
        ajaxSearch();
    }

    function onChangeRecommend(obj, attr) {
        $("#recommend_manual_count").attr(attr, $(obj).val());
        $("#" + attr + "_recommend_count").val($(obj).val());
        $("#recommend_manual_count").prop('checked', true);
        $("#recommend_manual_count").addClass('checked');
        getMembersCount("recommend", "count");
    }

    $("document").ready(function(){
        $("input[type='checkbox']").on('click', function(){
            if($(this).attr('id') == 'check_phone' || $(this).attr('id') == 'check_member' || $(this).attr('id') == 'check_search')
                return;
            if($(this).attr('name') == 'select_keyword') {
                return;
            }
            if($(this).prop('checked') == false /*&& $(this).attr('name') != 'member'*/) {
                if($(this).attr('name')  == "member")
                {
                    UncheckMember($(this).attr('name'), $(this).attr('detail'));
                }
                else{
                    //var key = $(this).attr('name');
                    var p1 = ($(this).attr('p1') !== void 0)?$(this).attr('p1'):'';
                    removeParam($(this).attr('name'), $(this).attr('detail'), p1);
                    //$(this).prop('checked', false);
                    //$(this).removeClass("checked");
                }
                ajaxSearch();
            }else {
                var key = $(this).attr('name');
                var detail = $(this).attr('detail');
                var p1 = ($(this).attr('p1') !== void 0)?$(this).attr('p1'):'';
                if(key != 'member')
                {
                    $("#check_search").prop('checked', true);
                }
                if(key == 'age' && detail == 'special') {
                    $("#to_" + key + "_" + detail).val(curYear - p1);
                    if(p1 == 60) {
                        $("#from_" + key + "_" + detail).val(curYear - p1 - 100);
                    }else {
                        $("#from_" + key + "_" + detail).val(curYear - p1 - 10);
                    }
                }
                if(key == 'recommend' && detail == 'count') {
                    var from = $(this).attr('from');
                    var to = $(this).attr('to');
                    $("#from_" + key + "_" + detail).val(from);
                    $("#to_" + key + "_" + detail).val(to);
                }

                if(key == "keyword" || key == "select_keyword") {
                    return;
                }
                if(key == "select_region")
                {
                    checkParam("region", "province");
                    checkParam("region", "city");
                    checkParam("region", "town");
                    ajaxSearch();
                    return;
                }
                //$(this).addClass('checked');
                getMembersCount(key, detail, p1);
            }
        });

        $("#check_phone").on('click', function(){

            if($(this).prop('checked')) {
                $("#check_member").prop('checked', false);
                $("#check_search").prop('checked', false);
                var count = ($("#value_phone_number").val() == "")?0:$("#value_phone_number").val().split(",").length;
                $("#total_count").html(count);
            }
            ajaxSearch();
        });

        $("#check_member").on('click', function(){
            if($(this).prop('checked')) {
                $("#check_phone").prop('checked', false);
            }
            ajaxSearch();
        });

        $("#check_search").on('click', function(){
            if($(this).prop('checked')) {
                $("#check_phone").prop('checked', false);
            }
            ajaxSearch();
        });

        $("#value_recommend_id").on('focus', function(){
            $("#radio_input_recommend").prop('checked', true);
        });
        $("#value_recommend_id").on('change', function(){
            var key = "recommend";
            var detail = "id";
            getMembersCount(key, detail);
        });
        $("#value_phone_number").on('change', function(){
            var count = ($("#value_phone_number").val() == "")?0:$("#value_phone_number").val().split(",").length;
            $("#total_count").html(count);
            ajaxSearch();
        });

        $("#value_iam_level_seller_domain").on('change', function(){
            $("#check_search").prop('checked', true);
            $("#radio_iam_level_seller_domain").prop('checked', true);
            getMembersCount("iam_level", "seller_domain");
        });

        $("#value_selling_level_seller_domain").on('change', function(){
            $("#check_search").prop('checked', true);
            $("#radio_selling_level_seller_domain").prop('checked', true);
            getMembersCount("selling_level", "seller_domain");
        });

        $("#search_keyword").on('focus', function(){
            $("#radio_search_keyword").prop('checked', true);
        });

        $("#search_keyword").on('change', function(){
            var value = $("#search_keyword").val();
            $.post('ajax/ajax_keyword.php', {'type':'special', 'value':value}, function(res){
            if(res.status == '1') {
                var keywords = res.keywords;
                var html = "";
                for(var i = 0; i < keywords.length; i++) {
                    var keyword = keywords[i];
                    html += '<li class="keyword-item"><input class="keyword_check" type="checkbox" name="keyword" style="padding:10px;" data="' + keyword + '" onclick="onClickKeyword(this)"><label class="label_keyword">' + keyword + '</label></li>'
                }
                $("#keyword").html("0");
                $("#keywords_wrapper").html(html);
            }
        }, 'json');
        });

        $("#value_region_province").on('change', function(){
            var province = $(this).val();
            $.post('location.php', {'type':'cities', 'location':province}, function(res){
                if(res.status == '1') {
                    var locations = res.locations;
                    var html = '<option value="">시군구</option>';
                    for(var i = 0; i < locations.length; i++) {
                        var location = locations[i];
                        html += '<option value="' + location + '">' + location +'</option>';
                    }
                    $("#value_region_city").html(html);
                    $("#select_region").prop('checked', true);
                    removeParam("region", "city");
                    removeParam("region", "town");
                    getMembersCount("region", "province");

                }
            }, 'json');
        });

        $("#value_region_city").on('change', function(){
            var city = $(this).val();
            $.post('location.php', {'type':'towns', 'location':city}, function(res){
                if(res.status == '1') {
                    var locations = res.locations;
                    var html = '<option value="">-읍/면/동-</option>';
                    for(var i = 0; i < locations.length; i++) {
                        var location = locations[i];
                        html += '<option value="' + location + '">' + location +'</option>';
                    }
                    $("#value_region_town").html(html);
                    $("#select_region").prop('checked', true);
                    removeParam("region", "town");
                    getMembersCount("region", "city");

                }
            }, 'json');
        });

        $("#value_region_town").on('change', function(){
            $("#select_region").prop('checked', true);
            getMembersCount("region", "town");
        });

        $(".section-title").on('click', function(){
            $(".section-title").removeClass("active");
            $(this).addClass("active");
            var btnID = "btnSend" + $(this).attr("target");
            $("#btnSendText").css('display', 'none');
            $("#btnSendStep").css('display', 'none');
            $("#" + btnID).css('display', 'block');
        });

        $("#owner_app").trigger('click');
    });

    function formatDate(date) {
        var date = new Date(date);
        var month = date.getMonth() * 1 + 1;
        return date.getFullYear() + "-" + month + "-" + date.getDate();
    }

    var totalCount = 0;
    function send_message(frm, mode) {
        var param = {};
        var searchParam = [];
        totalCount = $("#total_count").text() * 1;
        if(totalCount == 0) {
            alert("발송대상이 비였습니다.");
            return;
        }
        if($("#check_phone").prop('checked')) {
            var param = {};
            param.key = "phone";
            param.details = ['number'];
            param.value = $("#value_phone_number").val();
            param.from = "";
            param.to = "";
            param.p1 = "";
            searchParam.push(param);
        }
        if($("#check_member").prop('checked')) {
            searchParam = searchMemberParam;
        }
        if($("#check_search").prop('checked')) {
            searchParam = searchParam.concat(searchAllParam);
        }
        if(searchParam.length == 0) {
            alert("발송대상을 선택해주세요.");
            return;
        }

        if(mode == 1)
        {
            if($("#self_memo").val() == "")
            {
                alert("발신처를 입력해 주세요.");
                $("#self_memo").focus();
                return;
            }

            if($("#title").val() == "")
            {
                alert("제목을 입력해 주세요.");
                $("#title").focus();
                return;
            }

            if($("#txt").val() == "")
            {
                alert("내용을 입력해 주세요.");
                $("#txt").focus();
                return;
            }
        }else
        {
            if($("#self_step_memo").val() == "")
            {
                alert("발신처를 입력해 주세요.");
                $("#self_step_memo").focus();
                return;
            }

            if($("#step_key").val() == "")
            {
                alert("퍼널예약을 선택해 주세요.");
                $("#step_key").focus();
                return;
            }         
        }



        param.send_list = JSON.stringify(searchParam);
        param.step_idx = frm.step_idx.value;
        param.title = frm.title.value;
        param.text = txt_s=frm.txt.value+document.getElementsByName('onebook_url')[0].value;
        param.rday = frm.rday.value;
        param.htime = frm.htime.value;
        param.mtime = frm.mtime.value;
        param.send_img = frm.upimage_str.value;
        param.send_img1 = frm.upimage_str1.value;
        param.send_img2 = frm.upimage_str2.value;
        if(mode == 1)
            param.self_memo = frm.self_memo.value;
        else
            param.self_memo = frm.self_step_memo.value;
        param.save_mms_s = "";
        param.totalCount = totalCount;
        if(frm.save_mms.checked)
            param.save_mms_s="ok";
        
        if(confirm('요청하신 '+ totalCount + '건의 대상에 대해 메시지를 발송할까요?')) {
            $($(".loading_div")[0]).show();
	        $($(".loading_div")[0]).css('z-index',10000);
            $.post('send_notice.php', param, function(res){
                if(res.status == '1') {
                    $($(".loading_div")[0]).hide();
                    alert(res.msg);
                    // window.location.reload();
                }else {
                    $($(".loading_div")[0]).hide();
                    alert(res.msg);
                }
            }, 'json');
        }
    }
    function ml_preview(name,c,t)
    {
        open_div(open_recv_div,100,1);
        var content="";
        var contents= "";
        content=document.getElementsByName(name)[c].value+document.getElementsByName('onebook_url')[c].value;
        
        if($('[name=upimage_str]').val() != "") {
            contents += "<br><img src='"+ "../" + $('[name=upimage_str]').val()+"' style='width:150px;height:150x;'>";
        }
        if($('[name=upimage_str1]').val() != "") {
            contents += "<br><img src='"+ "../" + $('[name=upimage_str1]').val()+"' style='width:150px;height:150x;'>";
            
        }
        if($('[name=upimage_str2]').val() != "") {
            contents += "<br><img src='"+ "../" + $('[name=upimage_str2]').val()+"' style='width:150px;height:150x;'>";
            
        }		
        
        $($(".open_recv")[0]).html(content.replace(/\n/g,"<br/>")+contents);
        $($(".open_recv_title")[0]).html(t);	
    }

    function open_div(show_div,mus_top,mus_left,status)
    {
        if(!status)
        {
        var cbs = document.getElementsByTagName("div");
        for (var i = 0; i < cbs.length; i ++)
        {
            var cb = cbs[i];
            if (cb.id.indexOf("open")!=-1)
                {
                $("#"+cb.id).fadeOut(250)  
                }
        }
        }
        $(show_div).fadeIn(250);	   
        if(mus_top && mus_left)
        {	
        $(show_div).css("top",$(window).scrollTop()+mus_top);
        $(show_div).css("left",($("body").get(0).offsetWidth/2)-($(show_div).get(0).offsetWidth/2)+mus_left);
        }	  
    }
    
    function saveMessage() {
        if(confirm('작성중인 문자를 저장하시겠습니까?')) {
            frm = document.sub_4_form;
            
            var save_mms_s="";
            save_mms_s="ok";        

            var txt_s="";
            txt_s=frm.txt.value+document.getElementsByName('onebook_url')[0].value;
                    
                
            $.ajax({
                type:"POST",
                url:"ajax/ajax_session.php",
                data:{
                        send_save_mms:save_mms_s,
                        send_onebook_status:frm.onebook_status.value,
                        send_img:frm.upimage_str.value,
                        send_img1:frm.upimage_str1.value,
                        send_img2:frm.upimage_str2.value,
                        send_title:frm.title.value,
                        send_txt:txt_s
                    },
                success:function(data){
                    var arrData = data.split('|');
                    var msg = "";
                    if($.trim(data == "true"))
                        alert('저장되었습니다.');
                    else {
                        alert('저장중 문제가 발생하였습니다.');
                    }
                }
                })		        
        }
    }  
    function check_date(date) {
        if($('#rday').val() != "") {
            if($('#rday').val().replace("-", "").replace("-", "") < date) {
                alert('현재보다 이전시간을 선택하였습니다.\n발송불가하니 재설정하시기 바랍니다');
                $('#rday').val('');
            } 
        }    
    }

    function gotoCheckedMemberList() {
        //window.open('/self_list.php');
        window.open('self_send_list.php', "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");

    }

    function onStepReservation(){
        window.open('step_reservation_select.php', "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }

    function onClickKeyword(obj) {
        $(".keyword_check").each(function(){
            if($(this).prop('checked')) {
                $(this).next().css('color', 'red');
            }else {
                $(this).next().css('color', '#333');
            }
        });
        getMembersCount("keyword", "special");
    }


    function onSelectKeyword(obj) {
        if($(obj).hasClass('checked') == true) {

            var p1 = ($(this).attr('p1') !== void 0)?$(this).attr('p1'):'';
            removeParam($(this).attr('name'), $(this).attr('detail'),  p1);
            $(obj).prop('checked', false);
            $(obj).removeClass("checked");
            $("#keywords_wrapper").html("");
            ajaxSearch();
        }else {
            var data = {};
            if($(obj).attr('detail') == 'all') {
                data.type = "all";
                data.value = "";
            }else {
                data.type = "special";
                data.value = $("#search_keyword").val();
            }
            $(obj).addClass("checked");
            $("#check_search").prop('checked', true);
            $.post('ajax/ajax_keyword.php', data, function(res){
                if(res.status == '1') {
                    var keywords = res.keywords;
                    var html = "";
                    for(var i = 0; i < keywords.length; i++) {
                        var keyword = keywords[i];
                        html += '<li class="keyword-item"><input type="checkbox" class="keyword_check" name="keyword" style="padding:10px;" data="' + keyword + '" onclick="onClickKeyword(this)"><label class="label_keyword">' + keyword + '</label></li>'
                    }
                    $("#keyword").html("0");
                    $("#keywords_wrapper").html(html);
                }
            }, 'json');
        }
    }

    function uploadImage(obj, str, k) {
        var formData = new FormData(sub_4_form);
        // formData.append('upimage', obj.files[0]);
        onUploadImgChange(obj);
        sub_4_form.action='../ajax_up_image.php?i=0&k='+ k + '&target=' + str;
        sub_4_form.target='excel_iframe';

        $.ajax({
            url: $("#sub_4_form").attr('action'),
            type: 'post',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (resp) {
                if (resp.status == '1') {
                    $("#" + str).val(resp.value);
                } else {
                    alert(resp.value);
                }
            },
            error: function(data)
            {
                alert('오류 발생');
            }
        });
    }
</script>
