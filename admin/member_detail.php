<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");

// 가입 회원 상세 정보
$query = "select recommend_id, mem_code, mem_id, mem_pass, web_pwd, mem_name, gwc_worker_no, gwc_worker_img,
          mem_nick, mem_post, mem_leb, service_type, iam_type, mem_add1, mem_add2,mem_zip, mem_phone, mem_email, mem_birth,
          bank_name, bank_owner, bank_account, zy, is_message, first_regist,com_add1,mem_memo,mem_phone1,
          last_modify, visited, site, site_iam, fujia_date2, login_date, phone_cnt,video_upload,
          total_pay_money, mem_type, mem_vote, special_type, iam_card_cnt,iam_share_cnt,(select count(*) from Gn_MMS_Number where 1=1 and ( not (cnt1 = 10 and cnt2 = 20)) and mem_id =Gn_Member.mem_id) tcnt
          from Gn_Member where mem_code='$mem_code'";
$res = mysql_query($query);
$data = mysql_fetch_array($res);

$group = "";
$group_sql = "select info.name from gn_group_member mem inner join gn_group_info info on info.idx = mem.group_id where mem_id='$data[mem_id]'";
$group_res = mysql_query($group_sql);
while($group_row = mysql_fetch_array($group_res)){
    if($group == "")
        $group = $group_row['name'];
    else
        $group .= ",".$group_row['name'];
}
// 기부회원 상세 정보
$query = "select idx, mem_id, sendnum, max_cnt, user_cnt, gl_cnt, month_cnt, today_cnt, over_cnt, memo,
          reg_date, up_date, max_over_cnt, memo2, device, memo3, usechk, cnt1, cnt2, format_date,
          end_status, end_date, donation_rate, daily_limit_cnt, use_order
          from Gn_MMS_Number where mem_id='{$data['mem_id']}' and sendnum='$sendnum'";
$res = mysql_query($query);
$donation_data = mysql_fetch_array($res);

//  발송한도, 발송폰수, 결제상품, 디버상품 정보..
$sql_phone = "select max_cnt, add_phone, member_type, TotPrice, end_status, stop_yn from tjd_pay_result where buyer_id='{$data['mem_id']}' and gwc_cont_pay=0 order by no desc limit 1";
$res_phone = mysql_query($sql_phone);
$row_phone = mysql_fetch_array($res_phone);

$send_phone_limit = $row_phone[0];
$send_phone_cnt = $row_phone[1];
$send_phone_data = $send_phone_limit."/".$send_phone_cnt;

$pay_prod = $row_phone[2];
$dber_price = $row_phone[3];

$end_state1 = $row_phone[4];
$stop_state1 = $row_phone[5];

if($end_state1 == "N") $end_state = "결제대기";
if($end_state1 == "Y") $end_state = "결제완료";
if($end_state1 == "A") $end_state = "후불결제";
if($end_state1 == "E") $end_state = "기간만료";

if($stop_state1 == "Y") $stop_state = "이용중지";
if($stop_state1 == "N") $stop_state = "이용승인";
$price_state = $end_state."/".$stop_state;

$sql_phone_cnt = "select count(*) as cnt from Gn_MMS_Number where mem_id='{$data['mem_id']}'";
$res_phone_cnt = mysql_query($sql_phone_cnt);
$row_phone_cnt = mysql_fetch_array($res_phone_cnt);
$phone_cnt = $row_phone_cnt[0];

$sql_login_cnt = "select count(*) as cnt from gn_hist_login where userid='{$data['mem_id']}' and success='Y'";
$res_login_cnt = mysql_query($sql_login_cnt);
$row_login_cnt = mysql_fetch_array($res_login_cnt);

$login_cnt = $row_login_cnt[0];

$recommend_link = "https://".$HTTP_HOST."/ma.php?mem_code=".$mem_code;
// =====================  유료결제건 시작 ===================== 
$sql = "select phone_cnt, add_phone from tjd_pay_result where buyer_id = '".$data['mem_id']."' and end_date > '$date_today' and end_status='Y' and gwc_cont_pay=0 order by end_date desc limit 1";
$res_result = mysql_query($sql);
$buyPhoneCnt = mysql_fetch_row($res_result);
mysql_free_result($res_result);


if($buyPhoneCnt == 0){	
    $buyMMSCount = 0;
}else{
    $buyMMSCount = ($buyPhoneCnt[0] -1) * 9000;
}                    	
// ===================== 유료결제건 끝 ===================== 
// =====================  총결제금액 시작 =====================
$sql = "select sum(TotPrice) totPrice, date from tjd_pay_result where buyer_id = '".$data['mem_id']."' and end_status='Y' and gwc_cont_pay=0";
$res_result = mysql_query($sql);
$totPriceRow = mysql_fetch_row($res_result);

$totPrice = $totPriceRow[0];
$pay_date = $totPriceRow[1];
// ===================== 총결제금액 끝 =====================                     	

// =====================  마지막 결제정보 시작 ===================== 
/*
$sql = "select reg_date  from tjd_pay_result where buyer_id = '".$data['mem_id']."' order by end_date desc limit 1";
$res_result = mysql_query($sql);
$totPriceRow = mysql_fetch_row($res_result);
mysql_free_result($res_result);

$totPrice = $totPriceRow[0];
*/
// ===================== 마지막 결제정보 끝 =====================                     	

// =====================  마지막 발송정보 시작 ===================== 
$sql = "select msg_text, reservation_time  from sm_data where dest = '".str_replace("-", "", $data['mem_phone'])."' order by reservation_time desc limit 1";
$res_result = mysql_query($sql);
$totPriceRow = mysql_fetch_row($res_result);
mysql_free_result($res_result);

//$totPrice = $totPriceRow[0];
// ===================== 마지막 발송정보 끝 =====================                     	


// 부가서비스 이용 여부 확인
if($data['fujia_date2'] >= date("Y-m-d H:i:s")) {
    $add_opt = "사용";
} else {
    $add_opt = "미사용";
}

// 기부회원 상세정보

?>
<style>
    .box-body th {background:#ddd;}
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
</style>
<script src="/js/rlatjd_fun.js"></script>
<script src="/js/rlatjd.js"></script>
<script>
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 347;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
//수정
function form_save(){
    var recommender = $("#recommend_id").val();
    var userid = $("#divID").text();
    if(recommender == userid){
        alert('자신의 아이디는 추천에 입력되지 않습니다.');
        return;
    }
    if($('#is_exist_recommender').val() == "N" ){
        alert('추천인을 정확히 입력해 주세요.');
        return;
    }
    if(recommender == ''){
        alert('추천인을 입력해주세요');
        return;
    }
    var formData = new FormData();

    formData.append('mem_code', $("input[name=mem_code]").val());
    formData.append('sendnum', $("input[name=sendnum]").val());
    formData.append('send_phone_data', $("input[name=send_phone_data]").val());
    formData.append('phone_cnt', $("input[name=phone_cnt]").val());
    formData.append('mem_name', $("input[name=mem_name]").val());
    formData.append('bank_name', $("input[name=bank_name]").val());
    formData.append('bank_account', $("input[name=bank_account]").val());
    formData.append('bank_owner', $("input[name=bank_owner]").val());
    formData.append('mem_phone', $("input[name=mem_phone]").val());
    formData.append('mem_phone1', $("input[name=mem_phone1]").val());
    formData.append('mem_type', $("input[name=mem_type]:checked").val());
    formData.append('mem_email', $("input[name=mem_email]").val());
    formData.append('is_message', $("input[name=is_message]:checked").val());
    formData.append('video_upload', $("input[name=video_upload]:checked").val());
    if($("input[name=vote_member]").prop('checked')){
        formData.append('vote_member', 1);
    }
    formData.append('passwd', $("input[name=passwd]").val());
    formData.append('mem_add1', $("input[name=mem_add1]").val());
    formData.append('mem_add2', $("input[name=mem_add2]").val());
    formData.append('mem_zip', $("input[name=mem_zip]").val());
    formData.append('iam_card_cnt', $("input[name=iam_card_cnt]").val());
    formData.append('web_passwd', $("input[name=web_passwd]").val());
    formData.append('com_add1', $("input[name=com_add1]").val());
    formData.append('recommend_id', $("input[name=recommend_id]").val());
    formData.append('mem_birth', $("input[name=mem_birth]").val());
    formData.append('zy', $("input[name=zy]").val());
    formData.append('site', $("input[name=site]").val());
    formData.append('site_iam', $("input[name=site_iam]").val());
    formData.append('mem_memo', $("#mem_memo").val());
    
    var special_type = "";
    var s_arr = new Array();
    $("input[name=special]:checked").each(function(){
        s_arr.push($(this).val());
    })
    formData.append('special_type', s_arr.join(","));
    var msg = confirm('저장하시겠습니까?');
    if(msg){
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_change.php",
            data: formData,
            contentType: false,
            processData: false,
            success:function(){
                alert('저장되었습니다.');
                location.reload();
            },
            error: function(){
                alert('저장 실패');
            }
        });
    }else{
        return false;
    }
}    

function check_recommender()
{
    if($('#recommend_id').val() != '')
    {
        $.ajax({
            type:"POST",
            url:"/ajax/join.proc.php",
            cache: false,
            dataType:"json",
            data:{
                mode:"check_recommender",
                rphone: $('#recommend_id').val()
            },
            success:function(data){
                if(data.result == "success") {
                    $('#is_exist_recommender').val("Y");
                } else{
                    $('#is_exist_recommender').val("N");
                    alert('아이디가 없습니다.');
                }
            }
        });
    }
}
//계정 삭제
function passwd_reset(mem_code, opt){
    var msg = confirm('정말로 초기화하시겠습니까?');
    if(msg){
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_passwd.php",
            data:{mem_code:mem_code,opt:opt},
            success:function(){
            alert('초기화되었습니다.');
                location.reload();
            },
            error: function(){
                alert('초기화 실패');
            }
        });
    }else{
        return false;
    }
}
function gotoLogin() {
    $.ajax({
        type:"POST",
        url:"/admin/ajax/login_user.php",
        data:$('form[name=login_form]').serialize(),
        success:function(){
            location = "/";
        },
        error: function(){
            alert('초기화 실패');
        }
    });
    return false;
}
function show_pop(name){
    $("#group_names").text(name);
    $("#group_name").modal("show");
}
</script>
<div class="wrapper">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <?if($_SESSION['one_member_admin_id'] != ""){?>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>회원관리<small>회원을 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">회원관리</li>
            </ol>
        </section>
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post">
            <input type="hidden" name="one_id"   value="<?=$data['mem_id']?>" />
            <input type="hidden" name="mem_pass" value="<?=$data['web_pwd']?>" />
            <input type="hidden" name="mem_code" value="<?=$data['mem_code']?>" />
        </form>
        <form method="post" id="dForm" name="dForm">
            <input type="hidden" name="mem_code" value="<?=$data['mem_code']?>" />
            <input type="hidden" name="sendnum" value="<?=$_GET['sendnum']?>" />
            <!-- Main content -->
            <section class="content">
            <?if(str_replace("-", "",$data['mem_phone']) == $_GET['sendnum'] || $_GET['sendnum'] == "") {?>
                <div class="row">
                    <div class="col-xs-12" style="padding-bottom:20px">
                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="gotoLogin()"><i class="fa fa-user"></i> 회원페이지 로그인</button>
                        회원정보 수정
                    </div>
                </div>
                <div class="row">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">가입회원상세정보</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="detail1" class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="10%">
                                    <col width="20%">
                                    <col width="10%">
                                    <col width="20%">
                                    <col width="10%">
                                    <col width="24%">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>아이디</th>
                                        <td> <div id='divID'><?=$data['mem_id']?></div></td>
                                        <th>회원가입채널</th>
                                        <td>
                                            <!-- <input type="radio" name="mem_type" value="V"  <?=$data['mem_type']=='V'?"checked":""?>>선거용 / -->
                                            <input type="radio" name="mem_type" value="D"  <?=$data['mem_type']=='D' || $data['mem_type']=='V'?"checked":""?>>일반용 /
                                            <input type="radio" name="mem_type" value="A"  <?=$data['mem_type']=='A'?"checked":""?>>오토회원
                                        </td>

                                        <th>회원가입일 / 정보수정일</th>
                                        <td><?=$data['first_regist'].' / '.$data['last_modify']?></td>
                                    </tr>
                                    <tr>

                                        <th>회원구분</th>
                                        <td>
                                            <?
                                            if($data['mem_leb'] == "22") $level = "유저";
                                            else if($data['mem_leb'] == "50") $level = "코치";
                                            else if($data['mem_leb'] == "21") $level = "강사";
                                            else if($data['mem_leb'] == "60") $level = "홍보";

                                            if($data['service_type'] == "0") $selling = "FREE";
                                            else if($data['service_type'] == "1") $selling = "이용자";
                                            else if($data['service_type'] == "2") $selling = "리셀러";
                                            else if($data['service_type'] == "3") $selling = "분양자";

                                            $special_type = "";
                                            if($data['special_type'] != ''){
                                                $special_arr = explode(",",$data['special_type']);
                                                for($si = 0; $si < count($special_arr); $si++){
                                                    if($special_arr[$si] == 1) $special_type .= "판매자";
                                                    if($special_arr[$si] == 2) $special_type .= "전문가";
                                                    if($special_arr[$si] == 3) $special_type .= "구인자";
                                                    if($special_arr[$si] == 4) $special_type .= "구직자";
                                                    if($special_arr[$si] == 5) $special_type .= "리포터";
                                                    if($special_arr[$si] == 6) $special_type .= "아티스트";
                                                    if($si != count($special_arr) - 1) $special_type .= ",";
                                                }
                                            }
                                            ?>
                                            <?=$level."/".$selling."/".$special_type;?><br><br>
                                            스페셜등급 :
                                            <input type="checkbox" name="special" id="seller" value='1' <?=strstr($data['special_type'], "1")?"checked":""?>>판매자
                                            <input type="checkbox" name="special" id="artist" value='6' <?=strstr($data['special_type'], "6")?"checked":""?>>아티스트<br>
                                            <input type="checkbox" name="special" id="expert" value='2' <?=strstr($data['special_type'], "2")?"checked":""?>>전문가
                                            <input type="checkbox" name="special" id="guinja" value='3' <?=strstr($data['special_type'], "3")?"checked":""?>>구인자
                                            <input type="checkbox" name="special" id="gujikja" value='4' <?=strstr($data['special_type'], "4")?"checked":""?>>구직자
                                            <input type="checkbox" name="special" id="reporter" value='5' <?=strstr($data['special_type'], "5")?"checked":""?>>리포터
                                        </td>
                                        <th>은행계좌</th>
                                        <td>
                                            <table>
                                                <colgroup>
                                                    <col width="60">
                                                    <col>
                                                </colgroup>
                                                <tbody>
                                                <tr>
                                                    <td>은행</td><td><input type="text" style="width:150px;" name="bank_name" id="bank_name" value="<?=$data['bank_name']?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>계좌</td><td><input type="text" style="width:150px;" name="bank_account" id="bank_account" value="<?=$data['bank_account']?>"></td>
                                                </tr>
                                                <tr>
                                                    <td>예금주</td><td><input type="text" style="width:150px;" name="bank_owner" id="bank_owner" value="<?=$data['bank_owner']?>"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <th>IAM그룹</th>
                                        <td><input type="text" style="width:100%;" name="group_iam" id="group_iam" value="<?=$group?>" onclick="show_pop('<?=$group?>')" readonly></td>
                                        <!-- <th>부가서비스</th>
                                        <td>
                                            <input type="radio" name="add_opt" value="Y" <?=$add_opt=="사용"?"checked":""?>>사용 /
                                            <input type="radio" name="add_opt" value="N" <?=$add_opt=="미사용"?"checked":""?>>미사용
                                            <BR>서비스종료일 : <input type="text" name="fujia_date2" id="fujia_date2" value="<?=$data['fujia_date2']?>" />
                                        </td> -->
                                    </tr>
                                    <tr>
                                        <th>성명</th>
                                        <td><input type="text" name="mem_name" value="<?=$data['mem_name']?>"></td>
                                        <!-- <th>총결제금</th>
                                        <td>
                                            <?=number_format($totPrice)?><BR><?=$data['total_pay_money']?>
                                            <input type="text" style="width:100px;" name="total_pay_money" value="0" >
                                        </td> -->
                                        <th>디버별도상품</th>
                                        <td><input type="text" style="width:150px;" name="dber_price" value="<?=$dber_price?>" disabled></td>
                                        <th>카드갯수한도</th>
                                        <td><input type="text" style="width:100px;" name="iam_card_cnt" id="iam_card_cnt" value="<?=$data['iam_card_cnt']?>"></td>
                                    </tr>
                                    <tr>
                                        <th>폰번호</th>
                                        <td>
                                            <!-- <input type="text" style="width:100px;" name="mem_phone" value="<?=$data['mem_phone']?>" > -->
                                            <table>
                                                <colgroup>
                                                    <col width="80">
                                                    <col>
                                                </colgroup>
                                                <tbody>
                                                    <tr>
                                                        <td>스마트폰</td><td><input type="text" style="width:100px;" name="mem_phone" id="mem_phone" value="<?=$data['mem_phone']?>"></td>
                                                    </tr>
                                                    <tr>
                                                    <td>일반폰</td><td><input type="text" style="width:100px;" name="mem_phone1" id="mem_phone1" value="<?=$data['mem_phone1']?>"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <th>결제상품</th>
                                        <td>
                                            <input type="text" style="width:150px;" name="pay_prod" value="<?=$pay_prod?>" disabled><br>
                                            <?=$price_state?>
                                        </td>
                                        <th>수신동의일시</th>
                                        <td>
                                            <input type="radio" name="is_message" value="Y" <?=$data['is_message']=='Y'?"checked":""?>>받음 /
                                            <input type="radio" name="is_message" value="N" <?=$data['is_message']!='Y'?"checked":""?>>받지않음<br>
                                            <input type="text" name="is_message_date" value="<?=$data['first_regist']?>" disabled>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>이메일</th>
                                        <td><input type="text" name="mem_email" value="<?=$data['mem_email']?>"></td>
                                        <th>발송한도/발송폰수</th>
                                        <td>
                                            <input type="text" style="width:100px;" name="send_phone_data" value="<?=$send_phone_data?>" >
                                            <input type="text" style="width:100px;" name="phone_cnt" value="<?=$phone_cnt?>" disabled>
                                        </td>
                                        <th>최근접속일(총접속횟수)</th>
                                        <td><?=$data['login_date']?>(<?=$login_cnt?> 회)</td>
                                        <!-- <th>마지막 결제정보</th>
                                        <td><?=$pay_date?></td> -->
                                    </tr>
                                    <tr>
                                        <th>앱비밀번호</th>
                                        <td>
                                            <input type="password" style="width:100px;" name="passwd" id="passwd">
                                            <button class="btn btn-primary" style="margin-right: 5px;" onclick="void($('#passwd').val('111111'));return false;">초기화</button>
                                        </td>
                                        <th>자택주소</th>
                                        <td>
                                            <input type="text" name="mem_zip" value="<?=$data['mem_zip']?>">
                                            <input type="text" name="mem_add1" value="<?=$data['mem_add1']?>">
                                            <input type="text" name="mem_add2" value="<?=$data['mem_add2']?>">
                                        </td>
                                        <!-- <th>카드갯수한도</th>
                                        <td><?=number_format($data['tcnt'])?> 개</td> -->
                                        <th>동영상업로드</th>
                                        <td>
                                            <input type="radio" name="video_upload" value="1" <?=$data['video_upload']==1?"checked":""?>>가능 /
                                            <input type="radio" name="video_upload" value="0" <?=$data['video_upload']==0?"checked":""?>>불가능<br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>웹비밀번호</th>
                                        <td>
                                            <input type="password" style="width:100px;"  name="web_passwd" id="web_passwd">
                                            <button class="btn btn-primary" style="margin-right: 5px;"  onclick="void($('#web_passwd').val('111111'));return false;">초기화</button>
                                        </td>
                                        <th>회사주소</th>
                                        <td ><input type="text" name="com_add1" value="<?=$data['com_add1']?>"></td>
                                        <th>선거문자승인</th>
                                        <td>
                                            <input type="checkbox" name="vote_member" id="vote_member" <?=$data['mem_vote']=='1'?"checked":""?>>선거회원
                                            <input type="number" name="send_mms_cnt" value="2000" style="width: 80px;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>생년월일</th>
                                        <td><input type="text" name="mem_birth" value="<?=$data['mem_birth']?>"></td>
                                        <th>소속/직책</th>
                                        <td><input type="text" name="zy" value="<?=$data['zy']?>"></td>
                                        <th>추천인아이디</th>
                                        <td>
                                            <input type="text" style="width:100px;" name="recommend_id" id="recommend_id" onblur="check_recommender()" value="<?php echo $data['recommend_id']?>">
                                            <input type="hidden" id="is_exist_recommender" name="is_exist_recommender" required>
                                        </td>

                                    </tr>
                                    <tr>
                                        <th>셀링소속</th>
                                        <td><input type="text" style="width:100px;" name="site" id="site" value="<?=$data['site']?>"></td>
                                        <th>IAM소속</th>
                                        <td><input type="text" style="width:100px;" name="site_iam" id="site_iam" value="<?=$data['site_iam']?>"></td>
                                        <th>추천링크</th>
                                        <td>
                                            <input type="text" style="width:100%;" name="recommend_link" id="recommend_link" value="<?php echo $recommend_link?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>사업자등록번호</th>
                                        <td><input type="text" name="gwc_worker_no" id="gwc_worker_no" value="<?=$data['gwc_worker_no']?>"></td>
                                        <th>사업자등록증</th>
                                        <td><input type="file" style="width:200px;" name="gwc_worker_img" id="gwc_worker_img" value="<?=$data['gwc_worker_img']?>"></td>
                                        <th>관심키워드</th>
                                        <td><textarea style="width:100%;height:100px;" name="keyword" id="keyword"><?=$data['keyword']?></textarea></td>
                                    </tr>
                                    <tr>
                                        <th>회원관련메모</th>
                                        <td colspan="5"><textarea style="width:100%;height:100px;" name="mem_memo" id="mem_memo"><?=$data['mem_memo']?></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.row -->
            <?} else {?>
                <div class="row">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">기부회원 상세정보</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="detail2" class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="16%">
                                    <col width="16%">
                                    <col width="16%">
                                    <col width="16%">
                                    <col width="16%">
                                    <col width="20%">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>기부폰 번호</th>
                                        <td><?=$donation_data['sendnum']?></td>
                                        <th>앱설치일자</th>
                                        <td><?=$donation_data['reg_date']?></td>
                                        <th>가입자 아이디</th>
                                        <td><?=$data['mem_id']?></td>
                                    </tr>
                                    <tr>
                                        <th>최근발송건</th>
                                        <td><?=$donation_data['reservation_time']?></td>
                                        <th>기부비율</th>
                                        <td>
                                            <input type="text" style="width:100px;" name="donation_rate" value="<?=$donation_data['donation_rate']?>"> %
                                            <div><span style="font-size:10px">※ 설치 기록이 없으면 비율이 변경되지 않음.</span></div>
                                        </td>
                                        <th>가입자 이름</th>
                                        <td><?=$data['mem_name']?></td>
                                    </tr>
                                    <tr>
                                        <th>최근발송일</th>
                                        <td><?=$donation_data['reservation_time']?></td>
                                        <th>기부문자</th>
                                        <td>
                                            <?=number_format($donation_data['daily_limit_cnt'] * ($donation_data['donation_rate'] * 0.01))?> / <?=number_format($donation_data['daily_limit_cnt'])?>
                                        </td>
                                        <th>가입자 전화번호</th>
                                        <td><?=$data['mem_phone']?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.row -->
            <?}?>
                <div class="box-footer" style="text-align:center">
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='member_list.php';return false;"><i class="fa fa-list"></i> 회원목록</button>
                </div>
            </section><!-- /.content -->
        </form>
    </div><!-- /content-wrapper -->
    <?} else {
        echo "<script>location.href='/admin/smember_detail.php?mem_code=$mem_code&send_num=$send_num';</script>";        
    }?>
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>   
</div><!-- /.wrapper -->
<div id="group_name" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 20%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header" style="border:none;border: none;background-color: #99cc00;height: 45px;">
                <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;cursor:pointer;">X</a>
            </div>
            <div class="modal-body">
                <div class="center_text" id="group_names" style="text-align:center;">
                    
                </div>
            </div>
        </div>
    </div>
</div>
   