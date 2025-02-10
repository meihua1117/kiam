<?
$path="./";
include_once "_head.php";
$date = date("Y-m-d H:i:s");
// 이미 진행중인 결제가 있는지 확인
$query = "select *
from tjd_pay_result where buyer_id='{$_SESSION['one_member_id']}' and end_status='Y' and `end_date` > '$date'";
$res = mysqli_query($self_con,$query);
$sdata = mysqli_fetch_array($res);
if($sdata['no'] != "") {
//echo "<Script>alert('이미 진행중인 결제가 있습니다.');history.go(-1);</script>";
//exit;
$chk = "Y";
}
// 3 -> 건당 0.70000 부터 -0.00006
// 100 -> 건당 0.50000 부터 -0.00006
// 5000 개까지
?>
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script language="javascript" >
$(function(){
    $('.service_type').on("click", function(){
       $('.service_type').prop("checked", false);
       $(this).prop("checked", true);
    });
    
    $('.payon, .payoff').on("click", function(){
        if($(this).hasClass("payon")) {
            $(this).removeClass("payon").addClass("payoff");
            $(this).val("OFF");
        } else  {
            $(this).removeClass("payoff").addClass("payon");
            $(this).val("ON");            
        }
    });
})
	function show_login() {
		$('.ad_layer_login').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	}

    
function dbFree() {
        <?
        if($_SESSION['one_member_id'] == "")
        {
        ?>    
        alert('로그인 이후에 이용이 가능합니다.');
        return;
        <?php } ?>
    
    	var msg = confirm('온리원 디버 무료체험 신청하시겠습니까? 300건의 무료 건수가 제공됩니다.');
    	
    	if($('#password').val() != "") {
    	    if($('#password').val() != $('#password_re').val()) {
    	        alert('비밀번호를 확인해주세요');
    	        return;
    	    }
    	}

    	if(msg){
    			$.ajax({
    				type:"POST",
    				url:"/ajax/crawler_request.php",
    				dataType:"json",
    				success:function(result){
    				    if(result.result == "fail") {
    				        alert(result.msg)
    				    } else {
    				        alert('신청되었습니다. 디버 다운로드 후 이용해주세요.');
    				        return;
    				    }
    				},
    				error: function(){
    				  alert('저장 실패');
    				}
    			});		

    	}else{
    		return false;
    	}    
}
/*
    $('#member_type').val('퍼널문자');
    $('#teacher_only').show();
    $('#money_type5').prop("checked", true);
    $('#total_amount').text('55,000');
    $('#add_phone').val(3);
    $('#add_phone').prop("readonly", true);
    $('#price').val('55000');

    $('#goodname').val('퍼널문자');
    $('#month_cnt').val('1');    
    $('.payment_1').hide();
    $('.payment_2').show();       
    $('#payment_type_month').prop("checked", true);    
    
    1,1000,2000,ON,OFF
    5,5000,10000,ON,ON
    10,15000,30000,ON,ON
*/
function price_calc(index) {
    //console.log($('input[name=service_type]:checked').val());
    var cursor = $('input[name=service_type]:checked').data("num");
    index = index+1;
    var price = 0;
    var add_price = 0;
    var db_price = 0;
    var db_email = 0;
    
    // 폰 추가 가격
    var p_price = 2000;
    // 디비 개당 가격
    var p_db = 10;
    // 이메일 개당 가격
    var p_email = 1;
    
    if(index>=4)
        phone_price = (($('#phone_num'+index).val()*1)-1)*p_price;
    else if(index==1)
        phone_price = ($('#phone_num'+index).data("min_amount")*1)+(($('#phone_num'+index).val()*1)-1)*p_price;
    else if(index==2)
        phone_price = ($('#phone_num'+index).data("min_amount")*1)+(($('#phone_num'+index).val()*1)-5)*p_price;
    else if(index==3)
        phone_price = ($('#phone_num'+index).data("min_amount")*1)+(($('#phone_num'+index).val()*1)-10)*p_price;        

    if(index == 1 || index == 4) {
        if($('#db_num'+index).val()*1 > 1000) {
            db_price = ($('#db_num'+index).val()*1 -1000) * p_db;
        }
        if($('#db_email_num'+index).val()*1 > 2000) {
            db_email = ($('#db_email_num'+index).val()*1 -2000) * p_email;
        }
    } else if(index == 2 || index == 5) {
        
        if($('#db_num'+index).val()*1 > 5000) {
            db_price = ($('#db_num'+index).val()*1 -5000) * p_db;
        }
        if($('#db_email_num'+index).val()*1 > 10000) {
            db_email = ($('#db_email_num'+index).val()*1 -10000) * p_email;
        }
    } else if(index == 3 || index == 6) {
        if($('#db_num'+index).val()*1 > 15000) {
            db_price = ($('#db_num'+index).val()*1 -15000) * p_db;
        }
        if($('#db_email_num'+index).val()*1 > 30000) {
            db_email = ($('#db_email_num'+index).val()*1 -30000) * p_email;
        }
    }

    if($('#onestep'+index+'_2').hasClass('payon') == true) {
        if(index == 1 ) {
            add_price = (10000*1);
        }
        if(index == 4) {
            add_price = (120000*1);
        }       
        //console.log(index); 
    }    
    
    //price = (phone_price*1) + (db_price*1) + (db_email*1) + (add_price*1);
    
    price = (phone_price*1) + (db_price*1) + (db_email*1);
    if(index >= 4 && index <= 5) price = price * 12;
    if(index >= 6) price = price * 36;
    price += (add_price*1);
    price = (price*1)+(price * 0.1);
    //console.log(index+"^"+$('#phone_num'+index).val()+"^"+$('#db_num'+index).val()+"^"+$('#db_email_num'+index).val())
    if(index == 4) {
        
        if($('#phone_num'+index).val() == 1 && $('#db_num'+index).val() == 1000 && $('#db_email_num'+index).val() == 2000)
            price = 110000;
        else
            price = price + 110000;
            price += (add_price*1);
    } else if(index == 5) {
        if($('#phone_num'+index).val() == 5 && $('#db_num'+index).val() == 5000 && $('#db_email_num'+index).val() == 10000)
            price = 550000;
        else
            price = price + 550000;
            
            price += (add_price*1);
    } else if(index == 6) {
        if($('#phone_num'+index).val() == 10 && $('#db_num'+index).val() == 15000 && $('#db_email_num'+index).val() == 30000)
            price = 3000000;
        else
            price = price + 3000000;
            price += (add_price*1);
    }
    $('#payment'+index).html(comma(price));
}
$(function(){
    activeTable();
    
    $('input[name=month_type]').on("change",function(){

        activeTable();

        if($('input[name=month_type]:checked').val() == "M") {
            $('#month_table').show();
            $('#year_table').hide();
            $('.payment_1').hide();
            $('.payment_2').show();
            //$('input[name=service_type]:eq(0)').prop("checked",true);
            price_calc(0);
            $('#payment_type_month').prop("checked", true);
        } else {
            $('#month_table').hide();
            $('#year_table').show();
            $('.payment_1').show();
            $('.payment_2').hide();
            $('input[name=service_type]:eq(0)').prop("checked",false);
            $('input[name=service_type]:eq(1)').prop("checked",false);
            $('input[name=service_type]:eq(2)').prop("checked",false);
            //$('input[name=service_type]:eq(3)').prop("checked",true);
            price_calc(3);
            $('#payment_type_cash').prop("checked", true);
        }
    });
    
    $('input[name=payment_type]').on("change", function() {
        if($(this).data("monthly") == "Y") {
            $('#month_plan').prop("checked", true);
        } else {
            $('#month_plan').prop("checked", false);
        }
    });

    $('[name="service_type"]').on('change',function(){
        activeTable();
    });

    function activeTable(){
        $('[name="service_type"]').each(function(){
            if( $(this).prop('checked') == true ){
                $(this).parents('.view_table').addClass('active');
            } else {
                $(this).parents('.view_table').removeClass('active');
            }
        });
    }    
    
    $('input[name=month_type]').on("change",function(){
         if($('input[name=month_type]:checked').val() == "M") {
            $('#month_table').show();
            $('#year_table').hide();
            $('.payment_1').hide();
            $('.payment_2').show();
            //$('input[name=service_type]:eq(0)').prop("checked",true);
            price_calc(0);
            $('#payment_type_month').prop("checked", true);
         } else {
            $('#month_table').hide();
            $('#year_table').show();            
            $('.payment_1').show();
            $('.payment_2').hide();            
            $('input[name=service_type]:eq(0)').prop("checked",false);
            $('input[name=service_type]:eq(1)').prop("checked",false);
            $('input[name=service_type]:eq(2)').prop("checked",false);
            //$('input[name=service_type]:eq(3)').prop("checked",true);
            price_calc(3);
            $('#payment_type_cash').prop("checked", true);
         }
    });
    $('#onestep1_2').on("click",function(){
        price_calc(0);
    });
    $('#onestep4_2').on("click",function(){
        price_calc(3);
    });    
    $('.phone_plus').on("click",function() {
        var index = ($(this).data("num")*1)-1;
        var cursor = index;
        if(index >= 3) cursor = index - 3;
//        var obj = $(this).parents("tr").find("input[name=phone_num]:eq("+cursor+")");
        var obj = $(this).parents("tr").find("input[name=phone_num]");
        console.log(obj);
        obj.val((obj.val()*1)+1);
        $('#send_cnt'+$(this).data("num")).html(((obj.val()*1))*9000);
        price_calc(index);
    });
    
    $('.phone_minus').on("click",function() {
        var index = ($(this).data("num")*1)-1;
        var cursor = index;
        if(index >= 3) cursor = index - 3;
//        var obj = $(this).parents("tr").find("input[name=phone_num]:eq("+cursor+")");
        var obj = $(this).parents("tr").find("input[name=phone_num]");
        if(obj.data("min_cnt") >= (obj.val()*1)) {
        } else
            obj.val((obj.val()*1)-1);
        $('#send_cnt'+$(this).data("num")).html(((obj.val()*1))*9000);
        price_calc(index);
    });    
    
    $('.db_plus').on("click",function() {
        var index = ($(this).data("num")*1)-1;
        var cursor = index;
        if(index >= 3) cursor = index - 3;
//        var obj = $(this).parents("tr").find("input[name=db_num]:eq("+cursor+")");
        var obj = $(this).parents("tr").find("input[name=db_num]");
        obj.val((obj.val()*1)+500);
        price_calc(index);
    });
    
    $('.db_minus').on("click",function() {
        var index = ($(this).data("num")*1)-1;
        var cursor = index;
        if(index >= 3) cursor = index - 3;
//        var obj = $(this).parents("tr").find("input[name=db_num]:eq("+cursor+")");
        var obj = $(this).parents("tr").find("input[name=db_num]");
        console.log(obj.data("min_cnt"));
        console.log(obj.val());
        if(obj.data("min_cnt") >= (obj.val()*1)) {
        } else
            obj.val((obj.val()*1)-500);
        price_calc(index);
    });    
    
    $('.email_plus').on("click",function() {
        var index = ($(this).data("num")*1)-1;
        var cursor = index;
        if(index >= 3) cursor = index - 3;
        //var obj = $(this).parents("tr").find("input[name=db_email_num]:eq("+cursor+")");
        var obj = $(this).parents("tr").find("input[name=db_email_num]");
        obj.val((obj.val()*1)+5000);
        price_calc(index);
    });
    
    $('.email_minus').on("click",function() {
        var index = ($(this).data("num")*1)-1;
        var cursor = index;
        if(index >= 3) cursor = index - 3;
//        var obj = $(this).parents("tr").find("input[name=db_email_num]:eq("+cursor+")");
        var obj = $(this).parents("tr").find("input[name=db_email_num]");
        if(obj.data("min_cnt") >= (obj.val()*1)) {
        } else
            obj.val((obj.val()*1)-5000);
        price_calc(index);
    });    
            
    
    $('input[name=service_type]').on("click",function() {
        //console.log($('input[name=service_type]:checked').val());
    });
    
    
})
var send_cnt = 9000;

</script>
<style>
.payon {padding:5px;width:60px;background:#67D1FF;color:#fff;}
.payoff {padding:5px;width:60px;background:#efefef;}
</style>
<div class="ad_layer_login">
    <div class="layer_in">
        <span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>
        <div class="pop_title">
            이용안내
        </div>
        <div class="info_text">
            <p>온리원문자를 찾아주셔서 감사드립니다!<br/>
            온리원문자 및 온리원디버는 로그인후 사용이 가능합니다.</p>
            <div class="bnt2">
                <div class="wrap">
                    <a href="/join.php" class="button" target="_blank" >회원 가입 하기</a>
                    <a href="javascript:login_form.one_id.focus();$('.lb_overlay').hide();$('.ad_layer_login').hide();" class="button" target="_blank">로그인 하기</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="big_main pay-wrap">
    <div class="big_1 breadcrumb">
        <div class="m_div">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="pay.php">결제안내</a>
            </div>
            <!--<div class="right_sub_menu">
                <a href="sub_5.php">휴대폰등록</a> ㅣ <a href="sub_6.php">문자발송</a> ㅣ <a href="sub_4_return_.php">발신내역</a> ㅣ <a href="sub_4.php?status=5&status2=3">수신내역</a> ㅣ <a href="sub_4.php?status=6">수신여부</a>
            </div>-->
            <p style="clear:both;"></p>
        </div>
    </div>
    <div class="m_div">
        <div><img src="images/sub_02_visual_03.jpg" /></div>
        <div class="pay">
            <form name="pay_form" id="pay_form" method="post" action="" target="">
                <input type="hidden" name="member_type" id="member_type" value="월간타입">
                <input type="hidden" name="db_cnt" id="db_cnt" value="1000">
                <input type="hidden" name="email_cnt" id="email_cnt" value="2000">
                <input type="hidden" name="onestep1" id="onestep1" value="ON">
                <input type="hidden" name="onestep2" id="onestep2" value="OFF">
                <input type="hidden" name="add_phone" id="add_phone_post" value="">
                
                <div class="pay-header">
                    <div class="radio-item">
                        <input type="radio" id="monthTypeM" name="month_type" value="M" checked>
                        <label for="monthTypeM">월간결제</label>
                    </div>
                    <div class="radio-item">
                        <input type="radio" id="monthTypeY" name="month_type" value="Y">
                        <label for="monthTypeY">연간결제</label>
                    </div>
                </div>
                <div class="table-wrap">
                    <div id="month_table" class="table-type">
                        <div class="row">
                            <div class="col-4" <?php if($_SERVER['SERVER_NAME'] == "jungle.kiam.kr") { echo "style='display:none'";}?>>
                                <table class="view_table" border="0" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th class="colored" colspan="3">
                                                <input type="checkbox" name="service_type" class="service_type" value="M_PE"  data-num="1" >Personal
                                                <span class="popbutton11 pop_view">?</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3">
                                               1.상품구성변경 : 아래 ↑↓/ON을 눌러서 상품구성을 변경할수 있습니다.<br>
                                               2.이용폰수: 발송건수 내에서 무제한 휴대폰 연결이 가능합니다.



                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">온리원 문자</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">기준폰수</td>
                                            <td><input type="text" name="phone_num" value="1" id="phone_num1" data-min_cnt="1" data-min_amount="10000"></td>
                                            <td>
                                                <input type="button" value="+" class="phone_plus" id="plus_btn_phone1" data-num="1"/>
                                                <input type="button" value="-" class="phone_minus" id="minus_btn_phone1" data-num="1"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">발송건수</td>
                                            <td colspan="2"><span id="send_cnt1">9,000</span>건</td>
                                            
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">온리원디버</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">휴대폰 디비</td>
                                            <td>
                                                <input type="text" name="db_num" value="1000" id="db_num1"  data-min_cnt="1000">건
                                            </td>
                                            <td>
                                                <input type="button" value="+" class="db_plus" id="plus_btn_db1"  data-num="1"/>
                                                <input type="button" value="-" class="db_minus" id="minus_btn_db1"  data-num="1" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">타겟용 이메일 디비</td>
                                            <td class="fw-bold">무제한</td>
                                            <td><input type="button" value="ON" class="payon" id="db_target1" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">검색자 이메일 디비</td>
                                            <td><input type="text" name="db_email_num" value="2000" id="db_email_num1"  data-min_cnt="2000">건</td>
                                            <td>
                                                <input type="button" value="+" class="email_plus" id="plus_btn_email1" data-num="1"/>
                                                <input type="button" value="-" class="email_minus" id="minus_btn_email1"  data-num="1" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">원퍼널문자</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" colspan="2">
                                                이벤트신청관리기능<br>
                                                이벤트관리기능
                                            </td>
                                            <td><input type="button" value="ON" class="payon" id="onestep1_1" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" colspan="2">
                                                이벤트랜딩작성기능<br>
                                                퍼널예약발송기능<br>
                                                퍼널예약관리기능
                                            </td>
                                            <td><input type="button" value="OFF" class="payoff" id="onestep1_2"  /></td>
                                        </tr>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3"><span id="payment1" class="payment"  data-min_cnt="11000">11,000</span>원</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div  <?php if($_SERVER['SERVER_NAME'] == "jungle.kiam.kr") { echo "class='col-6'";}else{echo "class='col-4'";}?>>
                                <table class="view_table" border="0" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th class="colored" colspan="3">
                                                <input type="checkbox" name="service_type" class="service_type"  data-num="2" value="M_BU">Business
                                                <span class="popbutton12 pop_view">?</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3">
                                                1.상품구성변경 : 아래 ↑↓/ON을 눌러서 상품구성을 변경할수 있습니다.<br>
                                                2.이용폰수: 발송건수 내에서 무제한 휴대폰 연결이 가능합니다.
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">온리원 문자</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">기준폰수</td>
                                            <td><input type="text" name="phone_num" value="5" id="phone_num2" data-min_cnt="5"  data-min_amount="50000"></td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_phone2" class="phone_plus" data-num="2"  />
                                                <input type="button" value="-" id="minus_btn_phone2" class="phone_minus" data-num="2" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">발송건수</td>
                                            <td colspan="2"><span id="send_cnt2">45,000</span>건</td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">온리원디버</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">휴대폰 디비</td>
                                            <td><input type="text" name="db_num" value="5000" id="db_num2"  data-min_cnt="5000">건</td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_db2" class="db_plus"  data-num="2"/>
                                                <input type="button" value="-" id="minus_btn_db2" class="db_minus"  data-num="2" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">타겟용 이메일 디비</td>
                                            <td class="fw-bold">무제한</td>
                                            <td><input type="button" value="ON" class="payon" id="db_target2" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">검색자 이메일 디비</td>
                                            <td><input type="text" name="db_email_num" value="10000" id="db_email_num2"  data-min_cnt="10000">건
                                            </td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_email2" class="email_plus" data-num="2"/>
                                                <input type="button" value="-" id="minus_btn_email2"  class="email_minus"  data-num="2" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">원퍼널문자</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" colspan="2">
                                                이벤트신청관리기능<br>
                                                이벤트관리기능
                                            </td>
                                            <td><input type="button" value="ON" class="payon" id="onestep2_1" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" colspan="2">
                                                이벤트랜딩작성기능<br>
                                                퍼널예약발송기능<br>
                                                퍼널예약관리기능
                                            </td>
                                            <td><input type="button" value="ON" class="payon" id="onestep2_2"  /></td>
                                        </tr>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3"><span id="payment2" class="payment"  data-min_cnt="55000">55,000</span>원</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div  <?php if($_SERVER['SERVER_NAME'] == "jungle.kiam.kr") { echo "class='col-6'";}else{echo "class='col-4'";}?>>
                                <table class="view_table" border="0" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th class="colored" colspan="3">
                                                <input type="checkbox" name="service_type" class="service_type"  data-num="3" value="M_PR">Premium
                                                <span class="popbutton13 pop_view">?</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3">
                                                     1.상품구성변경 : 아래 ↑↓/ON을 눌러서 상품구성을 변경할수 있습니다.<br>
													 2.이용폰수: 발송건수 내에서 무제한 휴대폰 연결이 가능합니다.
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">온리원 문자</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">기준폰수</td>
                                            <td><input type="text" name="phone_num" value="10" id="phone_num3"  data-min_cnt="10"  data-min_amount="90000"></td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_phone3"  class="phone_plus"  data-num="3" />
                                                <input type="button" value="-" id="minus_btn_phone3"  class="phone_minus"  data-num="3"  />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">발송건수</td>
                                            <td colspan="2"><span id="send_cnt3">90,000</span>건</td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">온리원디버</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">휴대폰 디비</td>
                                            <td><input type="text" name="db_num" value="15000" id="db_num3"  data-min_cnt="15000">건</td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_db3" class="db_plus" data-num="3"/>
                                                <input type="button" value="-" id="minus_btn_db3"  class="db_minus" data-num="3"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">타겟용 이메일 디비</td>
                                            <td class="fw-bold">무제한</td>
                                            <td><input type="button" value="ON" class="payon" id="db_target3" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">검색자 이메일 디비</td>
                                            <td><input type="text" name="db_email_num" value="30000" id="db_email_num3"  data-min_cnt="30000">건</td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_email3" class="email_plus"  data-num="3" />
                                                <input type="button" value="-" id="minus_btn_email3"  class="email_minus" data-num="3"  />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">원퍼널문자</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" colspan="2">
                                                이벤트신청관리기능<br>
                                                이벤트관리기능
                                            </td>
                                            <td><input type="button" value="ON" class="payon" id="onestep3_1" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" colspan="2">
                                                이벤트랜딩작성기능<br>
                                                퍼널예약발송기능<br>
                                                퍼널예약관리기능
                                            </td>
                                            <td><input type="button" value="ON" class="payon" id="onestep3_2"  /></td>
                                        </tr>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3"><span id="payment3" class="payment">99,000</span>원</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="year_table" class="table-type" style="display: none;">
                        <div class="row">
                            <div class="col-4" <?php if($_SERVER['SERVER_NAME'] == "jungle.kiam.kr") { echo "style='display:none'";}?>>
                                <table class="view_table" border="0" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th class="colored" colspan="3">
                                                <input type="checkbox" name="service_type" class="service_type"  data-num="4" value="Y_PE">Personal(1년)
                                                <span class="popbutton11 pop_view">?</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td colspan="3">
                                            1.상품구성변경 : 아래 ↑↓/ON을 눌러서 상품구성을 변경할수 있습니다.<br>
                                            2.이용폰수: 발송건수 내에서 무제한 휴대폰 연결이 가능합니다.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colored" colspan="3">온리원 문자</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">기준폰수</td>
                                        <td><input type="text" name="phone_num" value="1" id="phone_num4"  data-min_cnt="1" data-min_amount="100000"></td>
                                        <td>
                                            <input type="button" value="+" id="plus_btn_phone4" class="phone_plus"  data-num="4"  />
                                            <input type="button" value="-" id="minus_btn_phone4" class="phone_minus"  data-num="4"   />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">발송건수</td>
                                        <td colspan="2"><span id="send_cnt4">9,000</span>건</td>
                                    </tr>
                                    <tr>
                                        <td class="colored" colspan="3">온리원디버</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">휴대폰 디비</td>
                                        <td><input type="text" name="db_num" value="1000" id="db_num4"  data-min_cnt="1000">건</td>
                                        <td>
                                            <input type="button" value="+" id="plus_btn_db4" class="db_plus" data-num="4"/>
                                            <input type="button" value="-" id="minus_btn_db4" class="db_minus" data-num="4"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">타겟용 이메일 디비</td>
                                        <td class="fw-bold">무제한</td>
                                        <td><input type="button" value="ON" class="payon" id="db_target4" /></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">검색자 이메일 디비</td>
                                        <td><input type="text" name="db_email_num" value="2000" id="db_email_num4"  data-min_cnt="2000">건</td>
                                        <td>
                                            <input type="button" value="+" id="plus_btn_email4"  class="email_plus"  data-num="4" />
                                            <input type="button" value="-" id="minus_btn_email4"  class="email_minus"  data-num="4" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="colored" colspan="3">원퍼널문자</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold" colspan="2">
                                            이벤트신청관리기능<br>
                                            이벤트관리기능
                                        </td>
                                        <td><input type="button" value="ON" class="payon" id="onestep4_1" /></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold" colspan="2">
                                            이벤트랜딩작성기능<br>
                                            퍼널예약발송기능<br>
                                            퍼널예약관리기능
                                        </td>
                                        <td><input type="button" value="OFF" class="payoff" id="onestep4_2"  /></td>
                                    </tr>
                                    <tr>
                                        <td class="colored fw-bold" colspan="3"><span id="payment4" class="payment"  data-min_cnt="110000">110,000</span>원</td>
                                    </tr>
                                </table>
                            </div>
                            <div  <?php if($_SERVER['SERVER_NAME'] == "jungle.kiam.kr") { echo "class='col-6'";}else{echo "class='col-4'";}?>>
                                <table class="view_table" border="0" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th class="colored" colspan="3">
                                                <input type="checkbox" name="service_type" class="service_type"  data-num="5" value="Y_BU">Business(1년)
                                                <span class="popbutton12 pop_view">?</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3">
                                                1.상품구성변경 : 아래 ↑↓/ON을 눌러서 상품구성을 변경할수 있습니다.<br>
                                               2.이용폰수: 발송건수 내에서 무제한 휴대폰 연결이 가능합니다.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">온리원 문자</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">기준폰수</td>
                                            <td><input type="text" name="phone_num" value="5" id="phone_num5"  data-min_cnt="5" data-min_amount="500000"></td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_phone5"  class="phone_plus" data-num="5" />
                                                <input type="button" value="-" id="minus_btn_phone5"  class="phone_minus" data-num="5" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">발송건수</td>
                                            <td colspan="2"><span id="send_cnt5">45,000</span>건</td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">온리원디버</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">휴대폰 디비</td>
                                            <td><input type="text" name="db_num" value="5000" id="db_num5"  data-min_cnt="5000">건</td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_db5"  class="db_plus"  data-num="5"/>
                                                <input type="button" value="-" id="minus_btn_db5" class="db_minus"  data-num="5" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">타겟용 이메일 디비</td>
                                            <td class="fw-bold">무제한</td>
                                            <td><input type="button" value="ON" class="payon" id="db_target5" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">검색자 이메일 디비</td>
                                            <td><input type="text" name="db_email_num" value="10000" id="db_email_num5" data-min_cnt="10000" data-min_amount="3000000">건</td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_email1"  class="email_plus" data-num="5"/>
                                                <input type="button" value="-" id="minus_btn_email1"  class="email_minus" data-num="5"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">원퍼널문자</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" colspan="2">
                                                이벤트신청관리기능<br>
                                                이벤트관리기능
                                            </td>
                                            <td><input type="button" value="ON" class="payon" id="onestep5_1" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" colspan="2">
                                                이벤트랜딩작성기능<br>
                                                퍼널예약발송기능<br>
                                                퍼널예약관리기능
                                            </td>
                                            <td><input type="button" value="ON" class="payon" id="onestep5_2"  /></td>
                                        </tr>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3"><span id="payment5" class="payment">550,000</span>원</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div  <?php if($_SERVER['SERVER_NAME'] == "jungle.kiam.kr") { echo "class='col-6'";}else{echo "class='col-4'";}?>>
                                <table class="view_table" border="0" cellspacing="0" cellpadding="0">
                                    <thead>
                                        <tr>
                                            <th class="colored" colspan="3">
                                                <input type="checkbox" name="service_type" class="service_type"  data-num="6" value="Y_PR">Premium(3년)
                                                <span class="popbutton13 pop_view">?</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3">
                                                1.상품구성변경 : 아래 ↑↓/ON을 눌러서 상품구성을 변경할수 있습니다.<br>
                                                2.이용폰수: 발송건수 내에서 무제한 휴대폰 연결이 가능합니다.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">온리원 문자</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">기준폰수</td>
                                            <td><input type="text" name="phone_num" value="10" id="phone_num6"  data-min_cnt="10"></td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_phone6" class="phone_plus"  data-num="6"/>
                                                <input type="button" value="-" id="minus_btn_phone6" class="phone_minus"   data-num="6"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">발송건수</td>
                                            <td colspan="2"><span id="send_cnt6">90,000</span>건</td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">온리원디버</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">휴대폰 디비</td>
                                            <td><input type="text" name="db_num" value="15000" id="db_num6"  data-min_cnt="15000">건</td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_db6"  class="db_plus"  data-num="6"/>
                                                <input type="button" value="-" id="minus_btn_db6"  class="db_minus"   data-num="6"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">타겟용 이메일 디비</td>
                                            <td class="fw-bold">무제한</td>
                                            <td><input type="button" value="ON" class="payon" id="db_target6" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">검색자 이메일 디비</td>
                                            <td><input type="text" name="db_email_num" value="30000" id="db_email_num6"  data-min_cnt="30000">건</td>
                                            <td>
                                                <input type="button" value="+" id="plus_btn_email6" class="email_plus"  data-num="6" />
                                                <input type="button" value="-" id="minus_btn_email6" class="email_minus"  data-num="6"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored" colspan="3">원퍼널문자</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" colspan="2">
                                                이벤트신청관리기능<br>
                                                이벤트관리기능
                                            </td>
                                            <td><input type="button" value="ON" class="payon" id="onestep6_1" /></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" colspan="2">
                                                이벤트랜딩작성기능<br>
                                                퍼널예약발송기능<br>
                                                퍼널예약관리기능
                                            </td>
                                            <td><input type="button" value="ON" class="payon" id="onestep6_2"  /></td>
                                        </tr>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3"><span id="payment6" class="payment">3,000,000</span>원
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="a3">
                    <div class="a3_1 payment_1">
                        <span id="payment_type_card_span">
                        <input type="checkbox" name="month_plan" id="month_plan" value="Y" style="display:none" >
                        <input type="radio" name="payment_type" id="payment_type_card_1" value="card" data-monthly="Y" > 카드 일시불</span>
                        <input type="radio" name="payment_type" id="payment_type_card" value="card" data-monthly="N" > 카드 할부</span>
                        <input type="radio" name="payment_type" id="payment_type_cash" value="bank" > 무통장입금
                    </div>
                    <div class="a3_1 payment_2" style="display:none">
                        <input type="radio" name="payment_type" id="payment_type_month" value="month" checked> <a href="http://xn--2q1bv58amcq4w.kr/ars?userid=onlyone19" target="_blank">정기결제</a>
                    </div>
                </div>
						  
							    	 <div class="utils clearfix"> 
									        <a href="http://kiam.kr/iam/?DOSEhGYXOC" target="_blank" class="button"><font color="black">아이엠 샘플보기</font></a>
								     </div>

                
				<div class="a6" style="background-color:#fed700;display:none">
                    <span id="total_amount_cash_branch" style="display:none">
                        계약내용 참조
                    </span>
                    <span id="total_amount_cash">
                        결제금액: <span class="add_money_span" id="total_amount">0</span> 원<input type="hidden" name="price" id="price" />
                    </span>
                </div>
                <input type="hidden" name="gopaymethod" />
                <input type="hidden" name="goodname" id="goodname" />
                <input type="hidden" name="pay_type" id="pay_type" />
                <input type="hidden" name="max_cnt" />
                <input type="hidden" name="month_cnt" id="month_cnt" />
                <input type="hidden" name="fujia_status" id="fujia_status" />
                <input type="hidden" name="mid" value="obmms20151" />
                <?
                if(!$_SESSION['one_member_id'])
                {
                ?>

                
                <div class="a8"><a href="javascript:void(0)" onclick="alert('로그인후 이용이 가능합니다.');"><img src="images/sub_02_btn_23.jpg" /></a></div>
                <?
                }else {
                ?>
                <div class="a8"><a href="javascript:void(0)" onclick="pay_go(document.pay_form)"><img src="images/sub_02_btn_23.jpg" /></a></div>
                <?}?>
                <div style="margin-bottom:25px;border:1px solid #000;padding:10px;line-height:25px;">
                    <div class="a3_1"> 정기결제 유의사항</div>
                    <p>1. 부가세 포함 안내  : 정기결제금액은 부가세가 포함된 가격입니다.<br/>
					   2. 정기결제출금일자 : 정기결제 출금일은 결제신청을 한 날로부터 7일 후로 신청됩니다.<br/>
					   3. 정기결제신청완료 : 정기결제 신청 후 휴대폰으로 ARS전화를 받아야 신청이 완료됩니다.<br/>
					   4. 정기결제해지위치 : 정기결제는 고객님의 마이페이지에서 가능합니다.<br/>
					   5. 정기결제해지처리 : 고객 계정 오픈 후 첫달 결제일 전에 해지해도 첫 달 결제후 해지 처리되며, 정기 결제일이 지난 후 해지하면 해당 월의 사용기간이 끝날 때 해지 됩니다.<br/>
					   6. 정기결제변경처리 : 결제타입이나 수량변경을 하려면 기존 결제를 해지하고 다시 신청해주세요.<br/></p>

                </div>
                <div class="notice clearfix">
                    <div class="row">
                        <div class="col-6">
                            <div class="notice-box is-grey">
                                <div class="upper">카드 결제는 익스플로러에서만 가능합니다</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="notice-box is-green">
                                <div class="upper"> 무통장 입금 계좌</div>
                                <div class="downer">
                                    <!--<div class="a3_2_1">-패키지 가격</div>
                                    <div>패키지 가격은 정가의 약 65%를 할인한 가격입니다</div>
                                    <div>(통상 3만원 내외)</div>-->
                                    <div class="title">-스텐다드차타드은행 617-20-109431 온리원연구소</div>
                                    <div>(구,SC제일은행)</div>
                                    <!--<div class="a3_2_1">-정기결제 해지</div>
                                    <div>마감기간내에 정기결제를 해지한 경우에 사전에 할인받은 금액을</div>
                                    <div>납부해야 합니다.</div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
        <div class="pay bottom-notice">
            <div style="clear:both">
                <div class="a1" style="text-align:center;height:50px;line-height:27px;margin-top:20px;">
                    <p style="font-size:24px;font-weight:none">온리원셀링솔루션</p>
                    <p style="font-size:16px;font-weight:none">유의사항 및 사용방법 안내</p>
                </div>

                <div class="row">
                    <div class="col-6">
                        <table class="view_table" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width:100%;font-weight:bold;font-size:15px" colspan="2">
                                    온리원 문자
                                </td>
                            </tr>
                            <tr>
                                <td style="width:100%;padding:10px;" colspan="2">
                                    <a href="/cliente_list.php?status=1&one_no=64" target="_blank">온리원 문자 APP 다운로드</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:50%;padding:10px;" >
                                    <a href="/cliente_list.php?status=1&one_no=42" target="_blank">온리원 문자 매뉴얼 보기</a>
                                </td>
                                <td style="width:50%;padding:10px;" >
                                    <a href="/cliente_list.php?status=1&one_no=77" target="_blank">온리원 문자 사용법 영상보기</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:100%;text-align:left;padding:10px;height:171px;line-height:25px;vertical-align:top" colspan="2">
                                    <p style="font-weight:bold;font-size:14px;">유의사항</p>
                                    <p>1. 안드로이드폰 전용  : 2014년 5월 이후부터 출시된 안드로이드폰이며 아이폰(X)은 사용할 수 없습니다.<br/> 
									   2. 통신요금 확인 필수 : 휴대폰 통신사 통신요금제의 문자 발송 수 확인 후 가입하시기 바랍니다.<br/>
									   3. 휴대폰 사용량 안내 : 결제한 휴대폰 개수보다 적게 사용해도 환불은 되지 않습니다.<br/>
                                       4. 문자 발송 번호 : 문자 발송 번호는 휴대폰 번호입니다. 투넘버나 임의 번호 사용이 불가능합니다. <br/>
									   4. 온리원문자는 문자발송 솔루션입니다.  온리원셀링에서 발송된 문자내용은 발송자가 제공하는 정보로서 이를 신뢰하여 취한 조치에 대해 책임지지 않습니다.<br/></p> 

                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">                        
                        <table class="view_table" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width:100%;font-weight:bold;font-size:15px" colspan="2">
                                    온리원 디버
                                </td>
                            </tr>
                            <tr>
                                <td style="width:100%;padding:10px;" colspan="2">
                                     <a href="/cliente_list.php?status=1&one_no=85"  target="_blank">온리원 디버 다운로드</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:50%;padding:10px;" >
                                    <a href="/cliente_list.php?status=1&one_no=85"  target="_blank"> 온리원 디버 매뉴얼 보기</a>
                                </td>
                                <td style="width:50%;padding:10px;" >
                                    <a href="/cliente_list.php?status=1&one_no=85"  target="_blank"> 온리원 디버 사용법 영상보기</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:100%;text-align:left;padding:10px;height:171px;line-height:25px;vertical-align:top" colspan="2">
                                    <p style="font-weight:bold;font-size:14px;">유의사항</p>
                                    <p>1. 디버 로그인 : 온리원셀링 ID, 온리원셀링 PW로 로그인하면 됩니다.<br/>
									   2. 디버설치 사양 : 온리원디버는 브라우저 크롬에서 다운로드가 잘 되며, 컴퓨터 사양은 윈도우10 이상, 64비트 지원될 때 정상 작동합니다.<br/>
									  3. M/S 엑셀 설치 : 수집된 디비는 M/S 엑셀에 저장됩니다.<br/>
4. 익스플로어 설치 : 디버는 검색시 익스창을 통해 검색합니다. <br/>
<br/>
[디버 미작동시 확인사항]<br/>
1. 디버가 최신 버전인지 확인합니다(공지사항)<br/>
2. 인터넷 속도 및 핫스팟 데이타를 확인합니다.<br/>
3. 익스 네이버 지도 검색이 되는지 확인합니다.<br/>
(가끔 익스 네이버 지도 자체가 안될 때가 있음)<br/>
4. 컴퓨터가 낮은 단계 CPU/저전력모델인지 확인합니다. </p>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <table class="view_table" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width:100%;font-weight:bold;font-size:15px" colspan="2">
                                    메시지카피
                                </td>
                            </tr>
                            
                            <tr>
                                <td style="width:50%;padding:10px;" >
                                    메시지카피 사례 보기
                                </td>
                                <td style="width:50%;padding:10px;" >
                                    메시지카피 교육 영상
                                </td>
                            </tr>
                            <tr>
                                <td style="width:100%;text-align:left;padding:10px;height:171px;line-height:25px;vertical-align:top" colspan="2">
                                    <p style="font-weight:bold;font-size:14px;">유의사항</p>
                                    <p>메시지카피 오픈 : 메시지카피솔루션은 곧 오픈예정입니다. 다양하고 좋은 메시지카피 내용으로 찾아뵙겠습니다.(^^)</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-6">                        
                        <table class="view_table" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width:100%;font-weight:bold;font-size:15px" colspan="2">
                                    원퍼널문자
                                </td>
                            </tr>
                            
                            <tr>
                                <td style="width:50%;padding:10px;" >
                                    <a href="/cliente_list.php?status=1&one_no=118"  target="_blank">원퍼널문자 매뉴얼 보기</a>
                                </td>
                                <td style="width:50%;padding:10px;" >
                                    <a href="/cliente_list.php?status=1&one_no=118"  target="_blank"> 원퍼널문자 사용법 영상보기</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:100%;text-align:left;padding:10px;height:171px;line-height:25px;vertical-align:top" colspan="2">
                                    <p style="font-weight:bold;font-size:14px;">유의사항</p>
                                    <p>1. 사용가능폰 : 원퍼널문자는 현재 온리원문자 소유폰에서만 발송이 가능합니다. 추가 휴대폰 연동은 곧 오픈예정입니다.<br/>
                                       2. 신청경로명 : 신청경로와 영문이벤트명의 중복이 있을 시 오류가 생깁니다. 이벤트명 뒤에 신청경로를 연결하여 사용하시기 바랍니다.
</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <?/*
            <form name="pay_form" id="pay_form" method="post" action="" target="">
                <input type="hidden" name="member_type" id="member_type" value="월간타입">
                
                <div class="r1">
                    <div class="a1">
                        <li style="float:left;">온리원문자결제</li>
                        <li style="float:right;"></li>
                        <p style="clear:both"></p>
                    </div>
                    <div class="a2">
                        <table class="view_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width:15.6%">상품명</td>
                                <td style="width:16.6%">결제형태</td>
                                <td style="width:13.6%">기준폰수</td>
                                <td style="width:16.6%">문자발송<br/>(월)</td>
                                <td style="width:17.6%">디버제공<br/>(월)</td>
                                <td style="width:19.6%">요금</td>
                            </tr>
                            <?php if($_SERVER['SERVER_NAME'] == "center.kiam.kr") {?>
                            <tr >
                                <td><input type="checkbox" name="month_yn" id="month_yn_" value="Y" <?php if($_SERVER['SERVER_NAME'] == "center.kiam.kr") echo "checked";?>>개인형</td>
                                <td>일반/정기</td>
                                <td>
                                    <input type="text" style="width:55px;" name="add_phone_" id="add_phone_" value="1" onfocus="pay_total_v('add_phone_')" onkeyup="pay_total_v('add_phone_')" readonly/> 개
                                    <input type="button" value="+" id="plus_btn_"/>
                                    <input type="button" value="-" id="minus_btn_" />
                                </td>
                                <td>
                                    <span class="total_count_" id="total_count_">9,000</span>건
                                </td>
                                <td><!--월<span class='db_cnt' id="db_cnt1">500</span>개-->미제공</td>
                                <td><span class='add_money_span_' id="add_money_total_">11,000</span>원</td>
                            </tr>
                            <tr >
                                <td><input type="checkbox" name="month_yn" id="month_yn" value="Y">업무형</td>
                                <td>정기결제(월)) </td>
                                <td>
                                    <input type="text" style="width:55px;" name="add_phone" id="add_phone" value="3" onfocus="pay_total_month('add_phone')" onkeyup="pay_total_month('add_phone')" readonly/> 개
                                    <input type="button" value="+" id="plus_btn"/>
                                    <input type="button" value="-" id="minus_btn" />
                                </td>
                                
                                <td>
                                    <span id="total_count_1">27,000</span>건
                                </td>
                                <td><!--월<span class='db_cnt' id="db_cnt2">1,500</span>개-->미제공</td>
                                <td><span class='add_money_span' id="add_money_total_1">18,700</span>원
                                <input type="hidden" name="add_money" id="add_money_1" value="0" /></td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="payment_yn" id="payment_yn_5" value="BJ" >제휴형</td>
                                <td>정기결제<BR>(본사)</td>
                                <td>1개</td>
                                <td>9,000건</td>
                                <td>1,500개</td>
                                <td>30,000원</td>
                            </tr>
                            <!--
                            <tr>
                                <td><input type="checkbox" name="teacher_yn" id="teacher_yn" value="Y"  <?php if($_SERVER['SERVER_NAME'] == "center.kiam.kr") echo "checked";?>>사업형</td>
                                <td>일반결제 (년)</td>
                                <td>5개</td>
                                <td>45,000건</td>
                                <td>월<span class='db_cnt' id="db_cnt3">2,500</span>개</td>
                                <td>550,000원</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="month_yn_g" id="month_yn1" value="A" class="b_group" data-phone="100">분양형</td>
                                <td>분양결제</td>
                                <td>
                                    무제한
                                    <div style="display:none">
                                        <input type="text" style="width:55px;" name="add_phone__" id="add_phone__" value="100" onfocus="pay_total_month('add_phone__')" onkeyup="pay_total_month('add_phone__')" /> 개
                                        <input type="button" value="+" id="plus_btn__"/>
                                        <input type="button" value="-" id="minus_btn__" />
                                    </div>
                                </td>
                                
                                <td>
                                    폰당 <span id="total_count_2">9,000</span>건
                                </td>
                                <td>폰당 월<span class='db_cnt' id="db_cnt4">500</span>개</td>
                                <td>
                                    <span class='add_money_span' id="add_money_total_2">2,200,000</span>원
                                    <input type="hidden" name="add_money" id="add_money_2" value="0" />
                                </td>
                            </tr>
                            -->
                            <?php }else{?>
                            
                            <tr style="display:none">
                                <td><input type="checkbox" name="payment_yn" id="payment_yn_1" value="BA">예약형</td>
                                <td>2개월</td>
                                <td>5개</td>
                                <td>45,000건</td>
                                <td>2,500개</td>
                                <td>100,000원</td>
                            </tr>
                            <tr >
                                <td><input type="checkbox" name="month_yn" id="month_yn_" value="Y" >기본문자</td>
                                <td>1개월<BR>정기결제</td>
                                <td>
                                    1개
                                    <div style="display:none">
                                        <input type="text" style="width:55px;" name="add_phone_" id="add_phone_" value="1" onfocus="pay_total_v('add_phone_')" onkeyup="pay_total_v('add_phone_')" readonly/> 개
                                        <input type="button" value="+" id="plus_btn_"/>
                                        <input type="button" value="-" id="minus_btn_" />
                                    </div>
                                </td>
                                <td>
                                    <span class="total_count_" id="total_count_">9,000</span>건
                                </td>
                                <td><!--월<span class='db_cnt' id="db_cnt1">500</span>개-->월500건</td>
                                <td><span class='add_money_span_' id="add_money_total_">11,000</span>원</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="payment_yn" id="payment_yn_2" value="ST"  <?php if($_SERVER['SERVER_NAME'] != "center.kiam.kr") echo "checked";?>   >퍼널<br />문자</td>
                                <td>1개월<BR>정기결제<BR>(1년 약정)</td>
                                <td>3개</td>
                                <td>27,000건</td>
                                <td>2,500개</td>
                                <td>55,000원</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="payment_yn" id="payment_yn_4" value="BY"  >사업<br />문자</td>
                                <td>1년</td>
                                <td>5개</td>
                                <td>45,000건</td>
                                <td>2,500개</td>
                                <td>550,000원</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="payment_yn" id="payment_yn_3" value="BU"  >사업<br />문자</td>
                                <td>1개월<BR>정기결제<BR>(1년 약정)</td>
                                <td>5개</td>
                                <td>45,000건</td>
                                <td>2,500개</td>
                                <td>55,000원</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="payment_yn" id="payment_yn_3" value="BS"  >종합<br />문자</td>
                                <td>3년<BR>퍼널형포함</td>
                                <td>10개</td>
                                <td>90,000건</td>
                                <td>10,000개</td>
                                <td>2,500,000원</td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="payment_yn" id="payment_yn_3" value="BC"  >종합<br />컨설팅</td>
                                <td>3년<BR>퍼널형포함</td>
                                <td>10개</td>
                                <td>90,000건</td>
                                <td>10,000개</td>
                                <td>5,000,000원<br>컨설팅지원</td>
                            </tr>
                            <?php } ?>
                            
                            <!--<tr>
                                <td>부가서비스</td>
                                <td><input type="checkbox" name="add_opt" id="add_opt" value="Y">수신동의기능 <span class="popbutton1 pop_view">?</span></td>
                                <td colspan="3">
                                    <p>직접동의 : 수신동의 질문기능</p>
                                    <p>간접동의 : 참여과정 동의기능</p>
                                </td>
                                <td><span class='add_money_span__' id="add_money_total__">15,400</span>원</td>
                            </tr>-->
                        </table>
                    </div>
                    <div style="margin-bottom:25px">
                        <p>*부가세 포함된 가격입니다.</p>
                        
                        <p>*정기결제: 첫 달은 약정일 이후에 인출이 될 수 있으며, 둘째 달부터 매월 약정일에 인출이 됩니다. 해지는 마이페이지에서 가능합니다.</p>
                        <p>*연간결제: 연결제 상품은 1년 단위로 결제됩니다.                     </p>
                        <p style="color:#C00">*디버사용: 온리원디버 제공 상품을 결제하신 경우 카카오상담 또는 고객센터로 문의해 주세요. 담당자가 계정안내해 드립니다.
                        </p>
                        
                        
                    </div>
                    
                    <div class="a3">
                        <div class="a3_1">유의사항</div>
                        <div class="a3_2_1" style="color:#539210;">온리원문자 발송시 유의해주세요.</div><br/>
                        
                        <div class="a3_2">
                            <div>- 안드로폰 전용 발송:  본 서비스는 안드론폰만 가능함 (아이폰X)<br/>
                                - 결제후 휴대폰 추가 : 결제후 휴대폰을 추가하려면 기존 결제를 소진 또는 취소후 재결제해야 추가 가능함<br/>
                                - 휴대폰 사용량 안내 : 결제한 휴대폰 개수보다 적게 사용해도 환불은 되지 않음
                            </div>
                        </div>
                    </div>
                    <div class="a4">
                        <div class="a4_1">
                            <div class="a4_1_1">사용법영상</div>
                            <div class="a4_1_1">사용매뉴얼</div>
                            <div class="a4_1_1">앱다운로드</div>                        </div>
                            <div class="a4_2">
                                <div class="a4_2_1">
                                    <li style="float:left;line-height:45px; font-weight: ;font-size: 12px;">문자 사용법 영상보기 </li>
                                    <li style="float:right;margin-top:9px;"><a href="/cliente_list.php?status=1&one_no=77" target="_blank"><img src="images/sub_02_btn_26.jpg" /></a></li>
                                    <p style="clear:both;"></p>
                                </div>
                                <div class="a4_2_1">
                                    <li style="float:left;line-height:45px; font-weight: ;font-size: 12px;">문자 매뉴얼 다운로드</li>
                                    <li style="float:right;margin-top:9px;"><a href="/cliente_list.php?status=1&one_no=42" target="_blank"><img src="images/sub_02_btn_20.jpg" /></a></li>
                                    <p style="clear:both;"></p>
                                </div>
                                <div class="a4_2_1">
                                    <li style="float:left;line-height:45px; font-weight:; font-size: 12px;">문자APP 다운로드 </li>
                                    <li style="float:right;margin-top:9px;"><a href="/cliente_list.php?status=1&one_no=64" target="_blank"><img src="images/sub_02_btn_20.jpg" /></a></li>
                                    <p style="clear:both;"></p>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="r2">
                        <div class="a1">
                            <li style="float:left;">온리원디버결제 <span class="popbutton2 pop_view">?</span></li>
                            <li style="float:right;"></li>
                            <p style="clear:both"></p>
                        </div>
                        <div class="a2" style="margin-bottom: 5px;">
                            <table class="view_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width:20%">구분</td>
                                    <td style="width:20%">PC설치</td>
                                    <td style="width:20%">사용기간</td>
                                    <td style="width:20%">수집건수</td>
                                    <td style="width:20%">이용요금</td>
                                </tr>
                                <tr style="display:none">
                                    <td><input type="checkbox" name="term" id="term1" value="3"> 라이트</td>
                                    <td>1대</td>
                                    <td>1개월</td>
                                    <td style="text-align:left">
                                        <input type="text" style="width:55px;" name="add_db" id="add_db" value="10000" onfocus="pay_total_v('add_db')" onkeyup="pay_total_v('add_db')" readonly/> 건
                                        <input type="button" value="+" id="plus_add_db"/>
                                        <input type="button" value="-" id="minus_add_db" />
                                    </td>
                                    <td><span class='add_money_span' id="add_money_total_term1">110,000</span>원</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="term" id="term2" value="6"> 스탠다드</td>
                                    <td>1대</td>
                                    <td>6개월</td>
                                    <td class="fw-bold">무제한</td>
                                    <td><span class='add_money_span' id="add_money_total_term2">5,500,000</span>원</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" name="term" id="term3" value="12"> 프리미엄</td>
                                    
                                    <td>1대</td>
                                    <td>12개월</td>
                                    <td class="fw-bold">무제한</td>
                                    <td><span class='add_money_span' id="add_money_total_term3">10,000,000</span>원</td>
                                </tr>
                                <!--<tr style="display:none">
                                    <td>부가서비스</td>
                                    <td><input type="hidden" name="show_fujia" value="이미결제됨 <br><br> 사용만료시간 :<?=$member_1['fujia_date2']?>"/>
                                    <label <?=$fujia_pay?"onmouseover=\"show_recv('show_fujia','0','부가서비스')\"":""?>><input type="checkbox" name="add_service" onclick="pay_total_v('add_service')" <?=$fujia_pay?"disabled":""?> />사용시 선택</label>
                                </td>
                                <td>
                                    <?
                                    foreach($fujia_type as $key=>$v)
                                    {
                                    $br=$key==count($fujia_type)-1?"":"<br>";
                                    echo $v.$br;
                                    }
                                    ?>
                                </td>
                                <td>통합20,000원</td>
                                <td><span class="add_money_span">0</span>원<input type="hidden" name="add_money" value="0" /></td>
                            </tr>-->
                        </table>
                    </div>
                    
                    
                    <div class="bnt3" >
                        <div class="wrap">
                            <a href="/cliente_list.php?status=1&one_no=85" class="button"  target="_blank" style="margin: 10px 0px 20px 0px;" >온리원디버 정식판 다운로드</a>
                            
                        </div>
                    </div>
                    <p style="clear:both;"></p>
                    <div class="a5" style="display:none">
                        <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="background:#5f9ea0">선택</td>
                                <td style="background:#5f9ea0">기간</td>
                                <td style="background:#5f9ea0">요금할인 예시</td>
                            </tr>
                            <tr>
                                <td style="width:10%;"><label><input type="radio" name="money_type" id="money_type1"" value="1" onclick="pay_total_v()" checked /></label></td>
                                <td style="width:30%;"><label id="money_type1">1개월</label></td>
                                <td style="width:60%;">9,900원</td>
                            </tr>
                            <tr>
                                <td style="width:10%;"><label><input type="radio" name="money_type" id="money_type2" value="3" onclick="pay_total_v()"  /></label></td>
                                <td style="width:30%;"><label id="money_type2">3개월</label></td>
                                <td style="width:60%;"><strike>29,700원</strike>(5%할인)<BR>28,215원</td>
                            </tr>
                            <tr>
                                <td style="width:10%;"><label><input type="radio" name="money_type" id="money_type3" value="6" onclick="pay_total_v()"  /></label></td>
                                <td style="width:30%;"><label id="money_type2">6개월</label></td>
                                <td style="width:60%;"><strike>59,400원</strike>(8%할인)<BR>54,648원</td>
                            </tr>
                            <tr>
                                <td style="width:10%;"><label><input type="radio" name="money_type" id="money_type4" value="12" onclick="pay_total_v()"  /></label></td>
                                <td style="width:30%;"><label id="money_type2">12개월</label></td>
                                <td style="width:60%;"><strike>118,800원</strike>(10%할인)<BR>106,920원</td>
                            </tr>
                            <tr id="teacher_only" id="teacher_only"  style="display:none">
                                <td style="width:10%;"><label><input type="radio" name="money_type" id="money_type5" value="12" onclick="pay_total_v()"  /></label></td>
                                <td style="width:30%;"><label id="money_type2">36개월</label></td>
                                <td style="width:60%;">550,000원</td>
                            </tr>
                            <!--
                            <tr>
                                <td rowspan="3">자동결제</td>
                                <td><label><input type="radio" name="money_type" value="3" <?=$is_chrome?> onclick="pay_total_v()" />3개월 기간</label></td>
                            </tr>
                            <tr>
                                <td style="background-color:#FFF"><label><input type="radio" name="money_type" value="6" <?=$is_chrome?>  onclick="pay_total_v()"/>6개월 기간</label></td>
                            </tr>
                            <tr>
                                <td style="background-color:#FFF"rmfjrp><label><input type="radio" name="money_type" value="12" <?=$is_chrome?> onclick="pay_total_v()" />12개월 기간</label></td>
                            </tr>
                            -->
                        </table>
                    </div>
                    <div class="a3">
                        <div class="a3_1 payment_1">
                            <span id="payment_type_card_span">
                                <input type="checkbox" name="month_plan" value="Y" >일시불
                            <input type="radio" name="payment_type" id="payment_type_card" value="card" checked> 카드</span>
                            <input type="radio" name="payment_type" id="payment_type_cash" value="bank" > 무통장입금
                        </div>
                        <div class="a3_1 payment_2" style="display:none">
                            <input type="radio" name="payment_type" id="payment_type_month" value="month"> <a href="http://xn--2q1bv58amcq4w.kr/ars?userid=onlyone19" target="_blank">정기결제</a>
                        </div>
                    </div>
                    <div class="a3">
                        <div class="a3_1">결제는 익스플로러에서만 가능합니다</div>
                    </div>
                    <div class="a6" style="background-color:#fed700;">
                        <span id="total_amount_cash_branch" style="display:none">
                            계약내용 참조
                        </span>
                        <span id="total_amount_cash">
                            결제금액: <span class="add_money_span" id="total_amount">0</span> 원<input type="hidden" name="price" id="price" />
                        </span>
                    </div>
                    <input type="hidden" name="gopaymethod" />
                    <input type="hidden" name="goodname" id="goodname" />
                    <input type="hidden" name="pay_type" id="pay_type" />
                    <input type="hidden" name="max_cnt" />
                    <input type="hidden" name="month_cnt" id="month_cnt" />
                    <input type="hidden" name="fujia_status" id="fujia_status" />
                    <input type="hidden" name="mid" value="obmms20151" />
                    <?
                    if(!$_SESSION['one_member_id'])
                    {
                    ?>
                    <div class="a8"><a href="javascript:void(0)" onclick="alert('로그인후 이용이 가능합니다.');"><img src="images/sub_02_btn_23.jpg" /></a></div>
                    <?
                    }else {
                    ?>
                    <div class="a8"><a href="javascript:void(0)" onclick="pay_go(document.pay_form)"><img src="images/sub_02_btn_23.jpg" /></a></div>
                    <?}?>
                    <div class="a3" style="background-color:#539210; color:#FFF;">
                        <div class="a3_1"> 무통장 입금 계좌</div>
                        <div class="a3_2">
                            <!--<div class="a3_2_1">-패키지 가격</div>
                            <div>패키지 가격은 정가의 약 65%를 할인한 가격입니다</div>
                            <div>(통상 3만원 내외)</div>-->
                            <div class="a3_2_1">-스텐다드차타드은행 617-20-109431 온리원연구소</div>
                            <div>(구,SC제일은행)</div>
                            <!--<div class="a3_2_1">-정기결제 해지</div>
                            <div>마감기간내에 정기결제를 해지한 경우에 사전에 할인받은 금액을</div>
                            <div>납부해야 합니다.</div>-->
                        </div>
                    </div>
                    
                    <p style="clear:both;"></p>
                    
                    <div class="a4">
                        <div class="a4_1">
                            <div class="a4_1_1">디버 소개영상</div>
                            <div class="a4_1_1">디버 다운로드</div>
                        </div>
                        <div class="a4_2">
                            <div class="a4_2_1">
                                <li style="float:left;line-height:45px; font-weight: ;  font-size: 12px;">디버 소개 영상보기 </li>
                                <li style="float:right;margin-top:9px;"><a href="/cliente_list.php?status=1&one_no=76" target="_blank"><img src="images/sub_02_btn_26.jpg" /></a></li>
                                <p style="clear:both;"></p>
                            </div>
                            <div class="a4_2_1">
                                <li style="float:left;line-height:45px;font-weight: ;   font-size: 12px;">디버 정규판 설치</li>
                                <li style="float:right;margin-top:9px;"><a href="/cliente_list.php?status=1&one_no=85" target="_blank"><img src="images/sub_02_btn_20.jpg" /></a></li>
                                <p style="clear:both;"></p>                             </div>
                            </div>
                        </div>
                    </div>
                    <p style="clear:both;"></p>
                </form>
                */?>
            </div>
            
            <iframe name="pay_iframe" style="width:100%;height:500px;display:none" ></iframe>
            <!--<iframe name="pay_iframe" style="display:none;"></iframe>-->
        </div>
        

        <div class="ad_layer1">
            <div class="layer_in">
                <span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>
                <div class="pop_title">
                    치환문자 기능 사용안내
                </div>
                <div class="info_box">
                    <!--
                    <table class="info_box_table" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <th>이름</th>
                                <td>홍길동</td>
                                <th>앱 설치일</th>
                                <td>15.10.25</td>
                            </tr>
                            <tr>
                                <th>폰번호</th>
                                <td>010-1111-1111</td>
                                <th>앱 버전</th>
                                <td>V70</td>
                            </tr>
                            <tr>
                                <th>통신사</th>
                                <td>KT</td>
                                <th>마지막 사용일</th>
                                <td>15.12.23</td>
                            </tr>
                            <tr>
                                <th>기종</th>
                                <td>삼성0000</td>
                                <th>동기화 DB</th>
                                <td>1148</td>
                            </tr>
                            <tr class="last_tr">
                                <th>메모</th>
                                <td></td>
                                <th>엑셀다운</th>
                                <td>파일표시</td>
                            </tr>
                        </tbody>
                    </table>
                    -->
                </div>
                <div class="info_text">
                    <p>
                        
                    </p>
                </div>
            </div>
            
            
            
            
            <div class="ad_layer2">
                <div class="layer_in">
                    <span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>
                    <div class="pop_title">
                        온리원 디버
                    </div>
                    <div class="info_text">
                        <p>■ 수집위치 : 블로그, 카페, 동영상, 지도, 웹사이트, 포스트(개별 카페 주소별로 수집 가능)</p>
                        <p>■ 수집정보 : 웹종류, 페이지제목, 폰번호, 이메일, 대표자, 상호, 업종, 검색 페이지주소(해당 정보중 웹페이지에 없는 정보는 수집불가)</p>
                        <p>■pc변경 : 온리원디버는 1PC에서 작동하므로 PC변경을 원하시면 본사 담당자에게 요청하세요.</p>
                    </div>
                </div>
            </div>
</div>
       <?
       mysqli_close($self_con);
       include_once "_foot.php";
       ?>
            <script>
            var msg = "";
            msg += "*사전고지안내\n\n";
            msg += "-정기결제 미납 시 사용 중지 되오니 통장관리를 잘 부탁드립니다. \n";
            msg += "-온리원셀링솔루션의 리셀러 수당은 신청자에 한해 지급됩니다.\n";
            msg += "-문자 기본제공이 아닌 휴대폰 요금제 사용에 따른 통신요금 추가 발생에 대해서는 책임지지 않습니다. \n";
            //alert(msg);
            <?php if($chk == "Y") {?>
            function pay_go(frm) {
            /*
            if($('#month_yn').is(":checked") == true || $('#month_yn').is(":checked") == true) {
            }  else {
            alert('결제 유형을 선택해주세요.');
            return;
            }
            */
            if($('#term1').is(":checked") == false && $('#term2').is(":checked") == false && $('#term3').is(":checked") == false) {
            alert('이미 결제된 내역이 있습니다. 관리자에게 문의해주세요.');
            return;
            }
            npay_go(frm);
            }
            <?}else{?>
            //결제고고
            function pay_go(frm)
            {
            
            var price_type = $('input[name=service_type]:checked').val();
            
            if(price_type == "") {
            alert('결제종류를 선택해주세요.');
            return false;
            }
            
            var price_num = $('input[name=service_type]:checked').data("num");
            
            // console.log(price_type+"^"+price_num);
            
            
            if(price_type == "M_PE") {
            $('#member_type').val('Personal-월간결제');
            $('#goodname').val('Personal-월간결제');
            $('#month_cnt').val('12');
            } else if(price_type == "M_BU") {
            $('#member_type').val('Business-월간결제');
            $('#goodname').val('Business-월간결제');
            $('#month_cnt').val('12');
            } else if(price_type == "M_PR") {
            $('#member_type').val('Premium-월간결제');
            $('#goodname').val('Premium-월간결제');
            $('#month_cnt').val('12');
            } else if(price_type == "Y_PE") {
            $('#member_type').val('Personal-년간결제');
            $('#goodname').val('Personal-년간결제');
            $('#month_cnt').val('12');
            } else if(price_type == "Y_BU") {
            $('#member_type').val('Business-년간결제');
            $('#goodname').val('Business-년간결제');
            $('#month_cnt').val('12');
            } else if(price_type == "Y_PR") {
            $('#member_type').val('Premium -년간결제');
            $('#goodname').val('Premium -년간결제');
            $('#month_cnt').val('36');
            }
            
            $('#total_amount').text($('#payment'+price_num).html().replace(",",""));
            $('#add_phone').val($('#phone_num'+price_num).val());
            $('#db_cnt').val($('#db_num'+price_num).val());
            $('#email_cnt').val($('#db_email_num'+price_num).val());
            $('#price').val($('#payment'+price_num).html().replace(",","").replace(",",""));
            //console.log($('#payment'+price_num).html().replace(",","").replace(",",""));
            //return;
            $('#onestep1').val($('#onestep'+price_num+"_1").val());
            $('#onestep2').val($('#onestep'+price_num+"_2").val());
            
            $('#add_phone_post').val($('#phone_num'+price_num).val());;
            
            if(!frm.mid.value)
            {
                alert('결제종류를 선택해주세요.');
                document.getElementsByName('money_type')[0].focus();
                return false;
            }
            
            if(!document.getElementsByName('price')[0].value || parseInt(document.getElementsByName('price')[0].value)<=0)
            {
                alert('결제하실 금액이 존재하지 않습니다.');
                return;
            }
            
            
                if(confirm('결제시작하시겠습니까?'))
                {
                    if($('input[name=payment_type]:checked').val()=="bank") {
                        //frm.target='pay_iframe';
                        frm.target='_self';
                        frm.action='pay_cash.php';
                        frm.submit();
                    } else if($('input[name=payment_type]:checked').val()=="month") {
                        //frm.target='pay_iframe';
                        frm.target='_self';
                        frm.action='pay_month.php';
                        frm.submit();
                    } else {
                            if(frm.mid.value=="obmms20151")
                            {
                                frm.target='pay_iframe';
                                frm.action='pay_start.php';
                                frm.submit();
                            }
                            else if(frm.mid.value=="obmms20152")
                            {
                                frm.target='pay_iframe';
                                frm.action='pay_auto_start.php';
                                frm.submit();
                            }
                    }
                }
            }
            <?php }?>
            $(function() {
            $(".popbutton1").click(function(){
            $('.ad_layer1').lightbox_me({
            centered: true,
            onLoad: function() {
            }
            });
            });
            
            $(".popbutton2").click(function(){
            $('.ad_layer2').lightbox_me({
            centered: true,
            onLoad: function() {
            }
            });
            });
            
            });
            var level3 = 0.70000;
            var level100 = 0.50000;
            var per = 0.00006;
            var sms_cnt = 9000;
            var per_price = 0;
            var set_price = 0;
            
            $(function() {
            
            <?php
            if($_SERVER['SERVER_NAME'] == "center.kiam.kr") {?>
            pay_total_v('add_phone_');
            <?}?>
            $('.payment_1').hide();
            $('.payment_2').show();
            /*
            $('#member_type').val('퍼널문자');
            $('#teacher_only').show();
            $('#money_type5').prop("checked", true);
            $('#total_amount').text('55,000');
            $('#add_phone').val(3);
            $('#add_phone').prop("readonly", true);
            $('#price').val('55000');
            $('#goodname').val('퍼널문자');
            $('#month_cnt').val('1');
            $('.payment_1').hide();
            $('.payment_2').show();
            $('#payment_type_month').prop("checked", true);
            */
            });
            </script>