<?php 
include_once "inc/header.inc.php";
if($_SESSION[iam_member_id] == "") {
    echo "<script>location='/iam/';</script>";
}
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
//$sql="select * from Gn_Member  where mem_id='".$_SESSION[iam_member_id]."'";
//$sresul_num=mysqli_query($self_con,$sql);
//$data=mysqli_fetch_array($sresul_num);
//$iam_birth_arr = explode("-",$data[mem_birth]);

?>
<style>
.desc li {
    margin-bottom: 5px;
    font-size: 12px;
    line-height: 18px;
}    
.input-wrap a {
    float: right;
    width: 65px;
    display: block;
    margin-left: 5px;
    padding: 7px 5px;
    font-size: 11px;
    color: #fff;
    line-height: 14px;
    background-color: #ccc;
    text-align: center;
}
.check-wrap .check ~ label:before {
    content: '';
    position: absolute;
    top: 3px;
    left: 0;
    width: 18px;
    height: 18px;
    background-color: #fff;
    border: 1px solid #ccc;
}
.check-wrap .check ~ label {
    position: relative;
    display: inline-block;
    padding-left: 25px;
    line-height: 24px;
}
.check-wrap .check:checked ~ label:after { content: '\f00c'; position: absolute; top: 1px; left: 2px; color: #fff; font-family: 'Fontawesome'; font-size: 13px; }
.check-wrap .check:checked ~ label:before { background-color: #ff0066; border-color: #ff0066; }
.lselect {
    float: left;
    width: 70px;
    height: 28px;
    background-color: #fff;
    border: 1px solid #ccc;
    font-size: 12px;
    line-height: 16px;
}
.sub_4_1_t7 select {
    height: 30px !important;
}
.sub_4_1_t7 input[type=text] {
    height: 30px;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
.container {
    background-color: #fff;
    -webkit-box-shadow: 0 0 0 1px rgba(0,0,0,0.05), 0 0 5px 0 rgba(0,0,0,0.1);
    padding: 0;
}
td {
    font-size: 11px !important;
    vertical-align: middle;
}
</style>
<link href='/css/main.css' rel='stylesheet' type='text/css'/>
<link href='/css/responsive.css' rel='stylesheet' type='text/css'/><!-- 2019.11 반응형 CSS -->
<main id="register" class="common-wrap" style=""><!-- 컨텐츠 영역 시작 -->
    <div class="container">
        <div class="inner-wrap">
                    <h2 class="title"></h2>
                    <div class="mypage_menu">
                        <div style="display:flex;float: right">
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_receive&modal=Y')" title = "<?=$MENU['IAM_MENU']['M7_TITLE'];?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">콘수신</p>
                                <label class="label label-sm" id = "share_recv_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_send&modal=Y')" title = "<?=$MENU['IAM_MENU']['M8_TITLE'];?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">콘전송</p>
                                <label class="label label-sm" id = "share_send_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=unread_post')" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">댓글수신</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="/iam/mypage_post_lock.php" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">댓글차단해지</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=request_list')" title = "<?='신청알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">이벤트신청</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                        </div>
                        <div style="display:flex;float: right;">
                            <?if($_SESSION[iam_member_subadmin_id] == $_SESSION[iam_member_id]){?>
                            <a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&box=send&modal=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공지전송</p>
                                <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&modal=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공지수신</p>
                                <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <?}else{?>
                            <a class="btn  btn-link" title = "<?='공지알림';?>" href="javascript:iam_mystory('cur_win=unread_notice')" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공지</p>
                                <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <?}?>
                            <?if($is_pay_version){?>
                            <a class="btn  btn-link" title = "" href="/iam/mypage_refer.php" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:#99cc00">추천</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "" href="/iam/mypage_payment.php" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">결제</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "" href="/iam/mypage_payment_item.php" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">판매</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <?}?>
                            <?if($member_iam[service_type] < 2){
                                $report_link = "/iam/mypage_report_list.php";
                            }else{
                                $report_link = "/iam/mypage_report.php";
                            }
                            ?>
                            <a class="btn  btn-link" title = "" href="<?=$report_link?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">리포트</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "" href="/?cur_win=unread_notice&req_provide=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공급사신청</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                        </div>
                    </div>
                    <br>
                    <form name="pay_form" id="pay_form" method="GET" enctype="multipart/form-data">
                        <input type="hidden" name="page" value="<?=$page?>" />
                        <input type="hidden" name="page2" value="<?=$page2?>" />
                        <div style="text-align: center;margin-top: 70px;">
                            <h2 class="title">사업자 추천정보</h2>
                        </div>
                        <br>
                        <div class="sub_4_1_t7">
                            <div style="float:left;">
                                <select name="search_type">
                                    <option value="">전체</option>
                                    <option value="22">일반회원</option>
                                    <option value="50">사업자회원</option>
                                </select>
                                <input type="date" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>"/> ~
                                <input type="date" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>"/>
                            </div>
                            <div style="float:right;">
                                <img src="/images/sub_button_703.jpg" onclick="pay_form.submit();" style="height: 30px" />
                            </div>
                            <div style="float:right;">
                                <select name="lms_select">
                                    <option value="">선택</option>
                                    <?
                                    $select_lms_arr=array("mem_name"=>"회원명","mem_id"=>"아이디");
                                    foreach($select_lms_arr as $key=>$v)
                                    {
                                        $selected=$_REQUEST[lms_select]==$key?"selected":"";
                                        ?>
                                        <option value="<?=$key?>" <?=$selected?>><?=$v?></option>
                                    <?}?>
                                </select>
                                <input type="text" name="lms_text" value="<?=$_REQUEST[lms_text]?>" />
                            </div>
                            <p style="clear:both;"></p>
                        </div>
                        <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td><label>번호</label></td>
                                <td>회원등급</td>
                                <td>이름</td>
                                <td>아이디</td>
                                <td>휴대폰번호</td>
                                <td>가입일</td>
                                <td>결제일</td>
                                <td>해지일</td>
                            </tr>
                            <?
                            $nowPage= $_REQUEST['page']?$_REQUEST['page']:1;
                            $startPage = $_REQUEST[page]?$_REQUEST[page]:1;
                            $pageCnt = 20;
                            $search_type =  $_REQUEST['search_type'];
                            $rday1 =  $_REQUEST['rday1'];
                            $rday2 =  $_REQUEST['rday2'];
                            $lms_select =  $_REQUEST['lms_select'];
                            $lms_text =  $_REQUEST['lms_text'];

                            $searchStr = "";
                            if($lms_text && $lms_select){
                                $searchStr .= " AND gm.".$lms_select." LIKE '%".$lms_text."%'";
                            }

                            if($search_type){
                                $searchStr .= " AND gm.mem_leb =".$search_type;
                            }

                            if($rday1){
                                $start_date = date($rday1);
                                $searchStr .= " AND gm.first_regist > '".$start_date."'";
                            }
                            if($rday2){
                                $end_date = date($rday2);
                                $searchStr .= " AND gm.first_regist < '".$end_date."'";
                            }
                            $order = $order?$order:"desc";
                            $query = "select * from Gn_Member gm left join tjd_pay_result p on p.buyer_id = gm.mem_id
                	                        where recommend_id = '".$_SESSION[iam_member_id]."' $searchStr";
                            $res	    = mysqli_query($self_con,$query);
                            $totalCnt	=  mysqli_num_rows($res);
                            $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                            $intRowCount=$totalCnt;
                            $intPageSize =20;
                            if($_REQUEST[page])
                            {
                                $page=(int)$_REQUEST[page];
                                $sort_no=$intRowCount-($intPageSize*$page-$intPageSize);
                            }
                            else
                            {
                                $page=1;
                                $sort_no=$intRowCount;
                            }
                            if($_REQUEST[page2])
                                $page2=(int)$_REQUEST[page2];
                            else
                                $page2=1;
                            $int=($page-1)*$intPageSize;

                            $intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
                            $orderQuery .= "
                                    	ORDER BY gm.mem_code DESC
                                    	$limitStr
                                    ";

                            $i = 1;
                            $query .= "$orderQuery";
                            $res = mysqli_query($self_con,$query);
                            while($row = mysqli_fetch_array($res)) {
                                if($row['mem_leb'] == "22")
                                    $mem_leb = "일반회원";
                                else if($row['mem_leb'] == "50")
                                    $mem_leb = "사업자회원";
                                if($row['service_type'] == 1) {
                                    $mem_level = "소비자";
                                } else if($row['service_type'] == 2) {
                                    $mem_level = "직원";
                                } else if($row['service_type'] == 3) {
                                    $mem_level = "일반 대리점";
                                } else if($row['service_type'] == 4) {
                                    $mem_level = "지사 대리점";
                                } else if($row['service_type'] == 5) {
                                    $mem_level = "총판 대리점";
                                } else {
                                    $mem_level = "일반회원";
                                }
                                $new_val = "";
                                if($row['service_type'] == 1) {
                                    $new_val = "이용자";
                                } else if($row['service_type'] == 2) {
                                    $new_val = "리셀러";
                                } else if($row['service_type'] == 3) {
                                    $new_val = "분양자";
                                } else{
                                    $new_val = "FREE";
                                }
                                ?>
                                <tr>
                                    <td><?=$number--?></td>
                                    <td ><?=$new_val ?></td>
                                    <td ><?=$row[mem_name]?></td>
                                    <td ><?=$row[mem_id]?></td>
                                    <td><?=$row[mem_phone]?></td>
                                    <td><?=$row['first_regist']?></td>
                                    <td><?=$row['date']?></td>
                                    <td><?=$row['cancel_completetime']?></td>
                                </tr>
                                <?
                                $i++;
                            }
                            if($i == 1) {
                                ?>
                                <tr>
                                    <td colspan="10">등록된 내용이 없습니다.</td>
                                </tr>
                            <?
                            }
                            ?>
                            <tr>
                                <td colspan="10">
                                    <?
                                    page_f($page,$page2,$intPageCount,"pay_form");
                                    ?>

                                </td>
                            </tr>
                        </table>
                    </form>
    </div>
    </div>
</main><!-- // 컨텐츠 영역 끝 -->
<div id="ajax_div" style="display:none"></div>
<script language="javascript">
$("document").ready(function(){
    $("#value_region_province").on('change', function(){
        var province = $(this).val();
        $("#value_region_city").html('<option value="">-시/군/구-</option>');
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('location.php', {'type':'cities', 'location':province}, function(res){
            if(res.status == '1') {
                var locations = res.locations;
                var html = '<option value="">-시/군/구-</option>';
                for(var i = 0; i < locations.length; i++) {
                    var location = locations[i];
                    html += '<option value="'+location+'">'+location+'</option>';
                }
                $("#value_region_city").html(html);
            }
        }, 'json');
    });

    $("#value_region_city").on('change', function(){
        var city = $(this).val();
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('location.php', {'type':'towns', 'location':city}, function(res){
            if(res.status == '1') {
                var locations = res.locations;
                var html = '<option value="">-읍/면/동-</option>';
                for(var i = 0; i < locations.length; i++) {
                    var location = locations[i];
                    html += '<option value="'+location+'">'+location+'</option>';
                }
                $("#value_region_town").html(html);
            }
        }, 'json');
    });

    $("#value_region_town").on('change', function(){
        if($(this).val() != "") {
            var address = $("#value_region_province").val() + " " + $("#value_region_city").val() + " " + $(this).val();
            $("#add1").val(address);
        }
    });
});
function showInfoOut() {
    $('.contents').show();
}    
$(function() {
    $(document).ajaxStop(function() {
        $("#ajax-loading").delay(10).hide(1);
    });
    $('#mem_sex_m').on("click", function() {
        $('#mem_sex_f').prop("checked", false);
    });    
    $('#mem_sex_f').on("click", function() {
        $('#mem_sex_m').prop("checked", false);
    });
});
    function save_form(frm,modify) {
        if($('#name').val() == "") {
            alert('성명을 입력해 주세요.');
            return;                                    
        }       
        
        if($('#zy').val() == "") {
            alert('소속을 입력해 주세요.');
            return;                                                
        }                                         

        if($('#add1').val() == "") {
            alert('주소를 입력해 주세요.');
            return;                                                
        }        
                
        if($('#email_1').val() == "") {
            alert('이메일을 입력해 주세요.');
            return;                                                
        }        
        if($('#email_2').val() == "") {
            alert('이메일을 입력해 주세요.');
            return;                                                
        }
        var msg=modify?"수정하시겠습니까?":"등록하시겠습니까?";
        var form = $('#edit_form')[0];
        var formData = new FormData(form);
        formData.append("profile", $("#profile")[0].files[0])
        console.log(formData);

        if(confirm(msg))
        {
            $.ajax({
                type:"POST",
                url:"/ajax/ajax.member.php",
                processData: false,
                contentType: false,
                data:formData,
                success:function(data){$("#ajax_div").html(data)}
            })
        }
    }
    function chk_sms()   {
        if($('input[name=mobile_1]').val() == "" || $('input[name=mobile_2]').val() == "" || $('input[name=mobile_3]').val() == "") {
            alert('인증받으실 전화번호를 입력해주세요.')
            return;
        }
		$.ajax({
			 type:"POST",
			 url:"/ajax/join.proc.php",
			 cache: false,
			 dataType:"json",
			 data:{
				 mode:"send_sms",
				 rphone:$('input[name=mobile_1]').val()+"-"+$('input[name=mobile_2]').val()+"-"+$('input[name=mobile_3]').val()
				 },
			 success:function(data){
			    if(data.result == "success")
			        $('#check_rnum').val("Y");
			    else
			         $('#check_rnum').val("");
			         
			    alert(data.msg);
			    }
			})    
    }    
    $(function(){
        $('#checkAll').on("change",function() {
            if($('#checkAll').prop("checked") == true) {
                $("#checkPersonal").prop("checked", true);
                $("#checkTerms").prop("checked", true);
                $("#checkReceive").prop("checked", true);
                $("#checkThirdparty").prop("checked", true);
            } else {
                $("#checkPersonal").prop("checked", false);
                $("#checkTerms").prop("checked", false);
                $("#checkReceive").prop("checked", false);
                $("#checkThirdparty").prop("checked", false);
            }
        })
    });
 
function id_check(frm,frm_str) {
	if(!frm.id.value)
	{
		frm.id.focus();
		return
	}
    var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
    if (!pattern.test(frm.id.value)) 
    {
        alert('영문 소문자와 숫자만 사용이 가능합니다.');
	 	frm.id_status.value=''
	    frm.id.value=''
	    frm.id.focus();
	    return;
    }

	 $.ajax({
		 type:"POST",
		 url:"/ajax/ajax.php",
		 data:{
			 	id_che:frm.id.value,
		 		id_che_form:frm_str
		 },
		 success:function(data){
		    $("#ajax_div").html(data);
		 }
	 });
}
function inmail(v,id)
{
    $("#"+id).val(v);
}
function searchManagerInfo() {
        var winw_pop = window.open('searchManagerInfo.php', 'popup', 'width=624, height=744, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,scrolling=yes');
        if(winw_pop == null) {
            alert('팝업 차단을 해제 해 주세요.');
        } else {
            winw_pop.focus();
        }
    
}


function change_message(form) {
	if(form.intro_message.value == "") {
		  alert('정보를 입력해주세요.');
		  form.intro_message.focus();
		  return false;
	}

	$.ajax({
		 type:"POST",
		 url:"ajax/ajax.php",
		 data:{
			 mode : "intro_message",
			 intro_message: form.intro_message.value
			 },
		 success:function(data){
		 	$("#ajax_div").html(data);
		 	alert('저장되었습니다.');
		 	}
		});
		return false;
}
function showInfo() {
    if($('#outLayer').css("display") == "none") {
        $('#outLayer').show();
    } else {
        $('#outLayer').hide();
    }
}

function copyHtml(){
    //oViewLink = $( "ViewLink" ).innerHTML;
    ////alert ( oViewLink.value );
    //window.clipboardData.setData("Text", oViewLink);
    //alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
    var trb = $.trim($('#sHtml').html());
    var IE=(document.all)?true:false;
    if (IE) {
        if(confirm("이 소스코드를 복사하시겠습니까?")) {
            window.clipboardData.setData("Text", trb);
        } 
    } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
    }

} 

//비밀번호 보안등급
function pwd_check(i)
{ 	
}
//비밀번호 재확인
function pwd_cfm_check(i)
{
	if($('#pwd_cfm').val() != $('#pwd').val())
	  {
		  	alert("두번 입력한 비밀번호가 틀립니다.");
			return;
	  }
	else
	  {
		
	  }	  
}

function pwd_change(frm,i)
{
    if($('#pwd_cfm').val() != $('#pwd').val())
    {
        alert("두번 입력한 비밀번호가 틀립니다.");
        return;
    }
	if(confirm('변경하시겠습니까?'))
	{
		$.ajax({
			 type:"POST",
			 url:"/ajax/ajax_session.php",
			 data:{
					pwd_change_old_pwd:$('#old_pwd').val(),
					pwd_change_new_pwd:$('#pwd').val(),
					pwd_change_status:i
				  },
			 success:function(data){$("#ajax_div").html(data)}
			})		
	}		
}

$("#value_region_province").on('change', function(){
        var province = $(this).val();
        $("#value_region_city").html('<option value="">-시/군/구-</option>');
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('/location.php', {'type':'cities', 'location':province}, function(res){
            if(res.status == '1') {
                var locations = res.locations;
                var html = '<option value="">-시/군/구-</option>';
                for(var i = 0; i < locations.length; i++) {
                    var location = locations[i];
                    html += '<option value="${location}">${location}</option>';
                }
                $("#value_region_city").html(html);
            }
        }, 'json');
    });

    $("#value_region_city").on('change', function(){
        var city = $(this).val();
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('/location.php', {'type':'towns', 'location':city}, function(res){
            if(res.status == '1') {
                var locations = res.locations;
                var html = '<option value="">-읍/면/동-</option>';
                for(var i = 0; i < locations.length; i++) {
                    var location = locations[i];
                    html += '<option value="${location}">${location}</option>';
                }
                $("#value_region_town").html(html);
            }
        }, 'json');
    });

    $("#value_region_town").on('change', function(){
        if($(this).val() != "") {
            var address = $("#value_region_province").val() + " " + $("#value_region_city").val() + " " + $(this).val();
            $("#mem_addr").val(address);
        }
    });
</script>
