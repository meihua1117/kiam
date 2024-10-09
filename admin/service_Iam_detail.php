<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
if($idx) {
    // 가입 회원 상세 정보
    $query = "select * from Gn_Iam_Service where idx='$idx'";
    $res = mysql_query($query);
    $data = mysql_fetch_array($res);

    $query = "select * from Gn_Iam_Name_Card where idx = $data[profile_idx]";
    $res = mysql_query($query);
    $card_data = mysql_fetch_array($res);
}else{
    $query = "select * from Gn_Iam_Service where idx='$idx'";
    $res = mysql_query($query);
    $data = mysql_fetch_array($res);
}
?>
<style>
    .box-body th {background:#ddd;}
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script> 

<link rel='stylesheet' id='jquery-ui-css'  href='//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css?ver=4.5.2' type='text/css' media='all' />
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
            <h1>아이엠분양관리<small>아이엠분양업체를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">분양업체관리</li>
            </ol>
        </section>
        <form method="post" id="dForm" name="dForm" action="/admin/ajax/service_Iam_save.php"  enctype="multipart/form-data">
            <input type="hidden" name="idx" value="<?=$data['idx']?>" />
            <input type="hidden" name="mode" value="<?=$data['idx']?"updat":"creat"?>" />
            <input type="hidden" name="profile_idx" value="<?=$data['profile_idx']?>" />
            <input type="hidden" name="self_share_card" value="<?=$data['self_share_card']?>" />
            <input type="hidden" name="dup_up_state" id="dup_up_state" value="<?=$data['dup_up_state']?>" />
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-body" style="overflow: auto !important">
                            <table id="detail1" class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="15%">
                                    <col width="35%">
                                    <col width="15%">
                                    <col width="35%">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th colspan="2" style="text-align: center"><h4>[분양상세정보]</h4></th>
                                        <th colspan="2" style="text-align: center"><h4>[아이엠디폴트정보]</h4></th>
                                    </tr>
                                    <tr>
                                        <th>분양자아이디</th>
                                        <td > <input type="text" style="width:250px;" name="mem_id" id="mem_id" value="<?=$data['mem_id']?>" >  </td>
                                        <th>분양자이름</th>
                                        <td >  <input type="text" style="width:250px;" name="mem_name" id="mem_name" value="<?=$data['mem_name']?>" >  </td>
                                    </tr>
                                    <tr>
                                        <th>업체대표이름</th>
                                        <td> <input type="text" style="width:250px;" name="owner_name" id="owner_name" value="<?=$data['owner_name']?>" > </td>
                                        <th>관리자이름</th>
                                        <td> <input type="text" style="width:250px;" name="manager_name" id="manager_name" value="<?=$data['manager_name']?>" > </td>
                                    </tr>
                                    <tr>
                                        <th>업체이름</th>
                                        <td> <input type="text" style="width:250px;" name="company_name" id="company_name" value="<?=$data['company_name']?>" >  </td>
                                        <th>사업자번호</th>
                                        <td> <input type="text" style="width:250px;" name="business_number" id="business_number" value="<?=$data['business_number']?>" >   </td>
                                    </tr>
                                    <tr>
                                        <th>통신판매번호</th>
                                        <td><input type="text" style="width:250px;" name="communications_vendors" id="communications_vendors" value="<?=$data['communications_vendors']?>" >  </td>
                                        <th>업체주소</th>
                                        <td><input type="text" style="width:250px;" name="address" id="address" value="<?=$data['address']?>" >  </td>
                                    </tr>
                                    <tr>
                                        <th>정보책임자</th>
                                        <td> <input type="text" style="width:250px;" name="privacy" id="privacy" value="<?=$data['privacy']?>" > </td>
                                        <th>팩스번호</th>
                                        <td> <input type="text" style="width:250px;" name="fax" id="fax" value="<?=$data['fax']?>" > </td>
                                    </tr>
                                    <tr>
                                        <th>대표전화번호</th>
                                        <td> <input type="text" style="width:250px;" name="owner_cell" id="owner_cell" value="<?=$data['owner_cell']?>" >  </td>
                                        <th>관리자폰번호</th>
                                        <td> <input type="text" style="width:250px;" name="manager_tel" id="manager_tel" value="<?=$data['manager_tel']?>" > </td>
                                    </tr>
                                    <tr>
                                        <th>이메일</th>
                                        <td> <input type="text" style="width:250px;" name="email" id="email" value="<?=$data['email']?>" >  </td>
                                        <th>아이엠브랜드</th>
                                        <td>  <input type="text" style="width:250px;" name="brand_name" id="brand_name" value="<?=$data['brand_name']?>" > </td>
                                    </tr>
                                    <tr>
                                        <th>메인도메인</th>
                                        <td> <input type="text" style="width:250px;" name="main_domain" id="main_domain" value="<?=$data['main_domain']?>" >  </td>
                                        <th>서브도메인</th>
                                        <td> <input type="text" style="width:250px;" name="sub_domain" id="sub_domain" value="<?=$data['sub_domain']?>" >   </td>
                                    </tr>
                                    <tr>
                                        <th>회원승인건수</th>
                                        <td> <input type="text" style="width:250px;"    name="mem_cnt" id="mem_cnt" value="<?=$data['mem_cnt']?>" >  </td>
                                        <th>멤버카드승인건</th>
                                        <td> 
                                            <input type="text" style="width:250px;" name="iamcard_cnt" id="iamcard_cnt" value="<?=$data['iamcard_cnt']?>" >
                                            <input type="hidden" name="old_card_cnt" id="old_card_cnt" value="<?=$data['iamcard_cnt']?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>분양카드승인건</th>
                                        <td> 
                                            <input type="text" style="width:250px;" name="my_card_cnt" id="my_card_cnt" value="<?=$data['my_card_cnt']?>">  
                                            <input type="hidden" name="old_my_card_cnt" id="old_my_card_cnt" value="<?=$data['my_card_cnt']?>">  
                                        </td>
                                        <th>컨텐츠전송건수</th>
                                        <td> 
                                            <input type="text" style="width:250px;" name="send_content" id="send_content" value="<?=$data['send_content']?>">  
                                            <input type="hidden" name="old_send_content" id="old_send_content" value="<?=$data['send_content']?>">  
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>월이용료</th>
                                        <td> <input type="text" style="width:250px;" name="month_price" id="month_price" value="<?=$data['month_price']?>" > </td>
                                        <th>분양비용</th>
                                        <td> <input type="text" style="width:250px;" name="share_price" id="share_price" value="<?=$data['share_price']?>" >  </td>
                                    </tr>
                                    <tr>
                                        <th>결제회원정보</th>
                                        <td>
                                            <input type="radio" value="0" name="service_type" id="service_type" <?php echo $data['service_type']==0?"checked":""?>>무료회원<br>
                                            <input type="radio" value="1" name="service_type" id="service_type" <?php echo $data['service_type']==1?"checked":""?>>유료회원<br>
                                            <input type="radio" value="2" name="service_type" id="service_type" <?php echo $data['service_type']==2?"checked":""?>>단체회원
                                            <input type="text" style="width:175px;"  name="service_price" id="service_price" value="<?=$data['service_type']==2?$data['service_price']:''?>" ><br>
                                            <input type="radio" value="3" name="service_type" id="service_type" <?php echo $data['service_type']==3?"checked":""?>>체험회원
                                            <input type="text" style="width:100px;"  name="service_exp_date" id="service_exp_date" value="<?=$data['service_type']==3?$data['service_price']:''?>" >일
                                            <input type="text" style="width:100px;"  name="service_price1" id="service_price1" value="<?=$data['service_type']==3?$data['service_price_exp']:''?>" >원<br>
                                        </td>
                                        <th>결제링크정보</th>
                                        <td><input type="text" style="width:250px;" name="pay_link" id="pay_link" value="<?=$data['pay_link']?>" >   </td>
                                    </tr>
                                    <tr>
                                        <th>사용여부</th>
                                        <td> <select name="status">
                                                <option value="Y" <?echo $data['status']=="Y"?"selected":""?>>사용</option>
                                                <option value="N" <?echo $data['status']=="N"?"selected":""?>>미사용</option>
                                            </select>
                                        </td>
                                        <th>홈페이지제목</th>
                                        <td> <input type="text" style="width:250px;" name="web_theme" id="web_theme" value="<?=$data['web_theme']?>" > </td>
                                    </tr>
                                    <tr>
                                        <th >아이엠로고</th>
                                        <td >
                                            <div style="display:flex">
                                                <input type="file" name="head_logo">
                                                <input type="button" onclick="clear_logo('<?=$data[profile_idx]?>');" value="삭제" style="">
                                            </div>
                                            <?php if($card_data['profile_logo'] != "") {?>
                                                <img src="<?=$card_data['profile_logo']?>" style="width:120px">
                                            <?php }?>
                                        </td>
                                        <th>로고링크</th>
                                        <td> <input type="text" style="width:250px;" name="home_link" id="home_link" value="<?=$data['home_link']?>" >  </td>
                                    </tr>
                                    <tr>
                                        <th>푸터로고</th>
                                        <td > 
                                            <div style="display:flex">
                                                <input type="file" name="footer_logo">
                                                <input type="button" onclick="clear_footer_logo('<?=$idx?>');" value="삭제" style="">
                                            </div>
                                            <?php if($data['footer_logo'] != "") {?><img src="<?=$data['footer_logo']?>" style="width:120px"><?php }?> 
                                        </td>
                                        <th>푸터링크</th>
                                        <td> <input type="text" style="width:250px;" name="footer_link" id="footer_link" value="<?=$data['footer_link']?>" >  </td>
                                    </tr>
                                    <tr>
                                        <th>KaKao Ink</th>
                                        <td>  <input type="text" style="width:250px;" name="kakao" id="kakao" value="<?=$data['kakao']?>" >  </td>
                                        <th>KAKAO API KEY</th>
                                        <td colspan="3"> <input type="text" name="kakao_api_key" value="<?=$data['kakao_api_key'];?>" size="50"></td>
                                    </tr>
                                    <tr>
                                        <th>등록일</th>
                                        <td > <input type="text"  name="regdate" id="regdate" value="<?=$data['regdate']?>" class="date" style="width:150px">   </td>
                                        <th>계약기간</th>
                                        <td ><input type="date"  name="contract_start_date" id="contract_start_date" value="<?=$data['contract_start_date']?>" class="date" style="width:130px">
                                            ~<input type="date"   name="contract_end_date" id="contract_end_date" value="<?=$data['contract_end_date']?>" class="date" style="width:130px">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>포인트 승인</th>
                                        <td>
                                            AI 자동생성 <input type="text" name="ai_point" id="ai_point" value="<?=$data['ai_card_point']?>" style="margin-left:20px;">P<br>
                                            오토회원 <input type="text" name="automem_point" id="automem_point" value="<?=$data['auto_member_point']?>" style="margin-left:37px;">P<br>
                                            카드전송 <input type="text" name="card_point" id="card_point" value="<?=$data['card_send_point']?>" style="margin-left:37px;">P<br>
                                            콘텐츠 전송 <input type="text" name="contents_point" id="contents_point" value="<?=$data['contents_send_point']?>" style="margin-left:19px;">P<br>
                                            오토데이트 <input type="text" name="autodata_point" id="autodata_point" value="<?=$data['autodata_point']?>" style="margin-left:23px;">P<br>
                                            콜백설정 <input type="text" name="callback_set_point" id="callback_set_point" value="<?=$data['callback_set_point']?>" style="margin-left:37px;">P<br>
                                            데일리설정 <input type="text" name="daily_set_point" id="daily_set_point" value="<?=$data['daily_set_point']?>" style="margin-left:23px;">P
                                        </td>
                                        <th>포인트 적용기간</th>
                                        <td >
                                            AI 자동생성<input type="date"  name="ai_start_date" id="ai_start_date" value="<?=$data['ai_point_start']?>" class="date" style="width:130px;margin-left:10px;">
                                            ~<input type="date"   name="ai_end_date" id="ai_end_date" value="<?=$data['ai_point_end']?>" class="date" style="width:130px"><br>
                                            오토회원<input type="date"  name="automem_start_date" id="automem_start_date" value="<?=$data['automem_point_start']?>" class="date" style="width:130px;margin-left:27px;">
                                            ~<input type="date"   name="automem_end_date" id="automem_end_date" value="<?=$data['automem_point_end']?>" class="date" style="width:130px"><br>
                                            카드전송<input type="date"  name="card_start_date" id="card_start_date" value="<?=$data['card_point_start']?>" class="date" style="width:130px;margin-left:27px;">
                                            ~<input type="date"   name="card_end_date" id="card_end_date" value="<?=$data['card_point_end']?>" class="date" style="width:130px"><br>
                                            콘텐츠전송<input type="date"  name="contents_start_date" id="contents_start_date" value="<?=$data['contents_point_start']?>" class="date" style="width:130px;margin-left:13px;">
                                            ~<input type="date"   name="contents_end_date" id="contents_end_date" value="<?=$data['contents_point_end']?>" class="date" style="width:130px"><br>
                                            오토데이트<input type="date"  name="autodata_start_date" id="autodata_start_date" value="<?=$data['autodata_point_start']?>" class="date" style="width:130px;margin-left:13px;">
                                            ~<input type="date"   name="autodata_end_date" id="autodata_end_date" value="<?=$data['autodata_point_end']?>" class="date" style="width:130px"><br>
                                            콜백설정<input type="date"  name="callback_start_date" id="callback_start_date" value="<?=$data['callback_point_start']?>" class="date" style="width:130px;margin-left:27px;">
                                            ~<input type="date"   name="callback_end_date" id="callback_end_date" value="<?=$data['callback_point_end']?>" class="date" style="width:130px"><br>
                                            데일리설정<input type="date"  name="daily_start_date" id="daily_start_date" value="<?=$data['daily_point_start']?>" class="date" style="width:130px;margin-left:13px;">
                                            ~<input type="date"   name="daily_end_date" id="daily_end_date" value="<?=$data['daily_point_end']?>" class="date" style="width:130px">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
                <div class="box-footer" style="text-align:center">
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='service_Iam_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
                </div>
            </section><!-- /.content -->
        </form>
    </div><!-- /content-wrapper -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
    
<script language="javascript">
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 245;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
function form_save() {
    var dup_up_state = $("#dup_up_state").val();
    $.ajax({
        type: "POST",
        url: "ajax/check_id_exist.php",
        dataType: "json",
        data:{
            mem_id : $('#mem_id').val()
        },
        success: function (data) {
            if (data.count == 0 && dup_up_state == 0) {
                alert("아이디를 정확히 입력하세요");
            }
            else{
                if($('#main_domain').val() == "") {
                    alert('도메인을 입력해주세요.');
                    return;
                }else if($('#sub_domain').val() == "") {
                    alert('보조도메인을 입력해주세요.');
                    return;
                }else
                    $('#dForm').submit();
            }
        },
        error: function () {
            //alert('초기화 실패');
        }
    });
}
function clear_logo(idx){
    $.ajax({
        type: "POST",
        url: "ajax/service_Iam_save.php",
        dataType: "json",
        data:{
            mode : "clear_logo",
            idx:idx
        },
        success: function (data) {
            location.reload();
        },
        error: function () {
            //alert('초기화 실패');
        }
    });
}
function clear_footer_logo(idx){
    $.ajax({
        type: "POST",
        url: "ajax/service_Iam_save.php",
        dataType: "json",
        data:{
            mode : "clear_footer_logo",
            idx:idx
        },
        success: function (data) {
            location.reload();
        },
        error: function () {
            //alert('초기화 실패');
        }
    });
}
$(document).ready(function(){
    $("#mem_id").keyup(function(){
        $.ajax({
            type: "POST",
            url: "ajax/get_member_cnt.php",
            dataType: "json",
            data:{
                mem_id : $('#mem_id').val()
                },
            success: function (data) {
                if (data.my_card_cnt > 0) {
                    /*if($('#mem_cnt').val() == '') {
                        $('#mem_cnt').prop('readonly', true);
                        $('#iamcard_cnt').prop('readonly', true);
                        $('#my_card_cnt').prop('readonly', true);
                        $('#send_content').prop('readonly', true);
                    }
                    $('#mem_cnt').val(data.mem_cnt);*/
                    $('#iamcard_cnt').val(data.iam_card_cnt);
                    $('#my_card_cnt').val(data.my_card_cnt);
                    $('#send_content').val(data.iam_share_cnt);}
                else{
                    /*$('#mem_cnt').prop('readonly',false);
                    $('#mem_cnt').val("");*/
                    $('#iamcard_cnt').prop('readonly',false);
                    $('#iamcard_cnt').val("");
                    $('#my_card_cnt').prop('readonly',false);
                    $('#my_card_cnt').val("");
                    $('#send_content').prop('readonly',false);
                    $('#send_content').val("");
                }
            },
            error: function () {
                //alert('초기화 실패');
            }
        });
    });
});

</script>      
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                