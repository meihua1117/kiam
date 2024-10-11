<?
extract($_GET);
?>
<script>
    if("<?=$platform?>" != "mobile" && navigator.userAgent.toLocaleLowerCase().search("android") > -1) {
        location.href = "?platform=mobile";
    }
</script>
<?
include_once "_head.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$date = date("Y-m-d H:i:s");
$sql="select * from Gn_Member where mem_id='{$_SESSION['one_member_id']}' ";
$resul=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($resul);
// 이미 진행중인 결제가 있는지 확인
$mid = date("YmdHis").rand(10,99);
/*$query = "select * from tjd_pay_result where buyer_id='{$_SESSION['one_member_id']}' and end_status='Y' and `end_date` > '$date'";
$res = mysqli_query($self_con,$query);
$sdata = mysqli_fetch_array($res);
if($sdata['no'] != "") {
//echo "<Script>alert('이미 진행중인 결제가 있습니다.');history.go(-1);</script>";
//exit;
$chk = "Y";
}*/
?>
<!DOCTYPE html>
<html lang="ko">

<style type="text/css">
    ul li a{font-family: 'notokr', sans-serif;font-size: 14px;}
    input {height: 25px;vertical-align: middle;border: 1px solid #CCC;font-family: "Arial" !important;font-weight: normal;font-size: 15px;line-height: normal;}
    #ajax-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:9000;text-align:center;display:none;background-color: #fff;opacity: 0.8;}
    #ajax-loading img{position:absolute;top:50%;left:50%;width:120px;height:120px;margin:-60px 0 0 -60px;}
    .payon {padding:5px;width:60px;background:#67D1FF;color:#fff;}
    .payoff {padding:5px;width:60px;background:#efefef;}
    .nav-tabs>.pay_tab.active>a, .nav-tabs>.pay_tab.active>a:focus, .nav-tabs>.pay_tab.active>a:hover {
        color: #000!important;
        background-color: rgb(146, 208, 80)!important;
        border-radius: 0px!important;
    }
</style>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<script src="/iam/js/jquery-3.1.1.min.js"></script>
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<?
if($platform == "mobile"){
    echo "<script type='text/javascript' charset='euc-kr' src='https://tx.allatpay.com/common/AllatPayM.js'></script>";
}else{
    echo "<script language=JavaScript charset='euc-kr' src='https://tx.allatpay.com/common/NonAllatPayREPlus.js'></script>";
    echo "<script language=JavaScript charset='euc-kr' src='https://tx.allatpay.com/common/NonAllatPayRE.js'></script>";
}
?>

<script language=Javascript>
    $(function() {
        $(document).ajaxStart(function() {
            $("#ajax-loading").show();
        })
        .ajaxStop(function() {
            $("#ajax-loading").delay(10).hide(1);
        });
    <?if($_SERVER['SERVER_NAME'] == "center.kiam.kr") {?>
        pay_total_v('add_phone_');
    <?}?>
    });
    function ftn_fix(dfm) {
        $.ajax({
            type: "POST",
            url: "/makeData_sprg_selling.php",
            data: $('#pay_form').serialize(),
            dataType: 'html',
            success: function (data) {
                $("#ajax-loading").delay(10).hide(1);
                var navCase = navigator.userAgent.toLocaleLowerCase();
                if(navCase.search("android") > -1){
                    Allat_Mobile_Fix(dfm, "0", "0");
                }else{
                    Allat_Plus_Fix(dfm, "0", "0");
                }
            },
            error: function (request, status, error) {
                console.log('code: ' + request.status + "\n" + 'message: ' + request.responseText + "\n" + 'error: ' + error);
            }
        });
    }

    function result_submit_no(result_cd,result_msg,enc_data) {
        var navCase = navigator.userAgent.toLocaleLowerCase();
        if(navCase.search("android") > -1)
            Allat_Mobile_Close();
        else
            Allat_Plus_Close();

        if(result_cd != '0000') {//pay_test
            alert(result_cd + " : " + result_msg);
            location.reload();
        } else {
            pay_form.allat_enc_data.value = enc_data;
            pay_form.action = "/allat/mp/allat_fix_sprg_selling.php";
            pay_form.method = "post";
            pay_form.target = "blank";
            pay_form.submit();
        }
    }
    function activeTab(index){
        if(index == 1){
            $(".exp-tab").removeClass('active').addClass('active');
            $(".cost-tab").removeClass('active');
            $(".cancel-tab").removeClass('active');
            $(".exp").show();
            $(".pay").hide();
            $(".cancel").hide();
        }else if(index == 2){
            $(".exp-tab").removeClass('active');
            $(".cost-tab").removeClass('active').addClass('active');
            $(".cancel-tab").removeClass('active');
            $(".exp").hide();
            $(".pay").show();
            $(".cancel").hide();
        }else{
            $(".exp-tab").removeClass('active');
            $(".cost-tab").removeClass('active');
            $(".cancel-tab").removeClass('active').addClass('active');
            $(".exp").hide();
            $(".pay").hide();
            $(".cancel").show();
        }
    }
    //결제고고
    function pay(frm){
        var price = $("#item_price").val() * 1;
        $('#allat_amt').val(price);
        $('#price').val(price);
        $('#member_type').val($("#item_name").val());
        $('#month_cnt').val(120);
        if($('#member_type').val() == ""){
            alert('상품명이 존재하지 않습니다.');
            return;
        }
        if(price <= 0){
            alert('결제하실 금액이 존재하지 않습니다.');
            return;
        }
        if(confirm('결제시작하시겠습니까?')){
            if($('input[name=payment_type]:checked').val()=="month") {// 통장정기결제
                frm.target='_self';
                frm.action='/pay_month_sprg_selling.php';
                frm.submit();
            }else if($('input[name=payment_type]:checked').val()=="month_card") {//카드정기결제
                if($('#allat_recp_addr').val() == "") {
                    $.ajax({
                        type:"POST",
                        url:"/ajax/get_mem_address.php",
                        dataType:"json",
                        data:{mem_id:'<?=$_SESSION['one_member_id']?>'},
                        success: function(data){
                            console.log(data.address);
                            $('#allat_recp_addr').val(data.address);
                            $('#allat_shop_id').val('bwelcome12');
                            $('#shop_receive_url').val('https://<?php echo $_SERVER['SERVER_NAME'];?>/allat/mp/allat_receive.php?mid=<?php echo $mid;?>');
                            ftn_fix(document.pay_form);
                        },
                        error: function () {
                            alert('내 정보 수정에서 자택주소를 추가해주세요.');
                            location.href = "mypage.php?link=pay_sprg";
                            return;
                        }
                    });
                }else{
                    $('#allat_shop_id').val('bwelcome12');
                    $('#shop_receive_url').val('https://<?php echo $_SERVER['SERVER_NAME'];?>/allat/mp/allat_receive.php?mid=<?php echo $mid;?>');
                    ftn_fix(document.pay_form);
                }
            }
        }
    }
</script>

<body style="padding-top: 0px">
    <div class="big_main pay-wrap" style="height: auto;min-height: 100%;">
        <div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
        <div class="m_div">
            <div>
                <img src="/images/sub_02_visual_03.jpg" style = "width : 100%"/>
            </div>
            <section id = "pay_menu" style="background: white">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item exp-tab pay_tab" style = "width :33%" onclick="activeTab(1)">
                        <a class="nav-link" id="exp-tab" data-toggle="tab" href="#" role="tab" aria-controls="exp" aria-selected="true" >서비스 설명</a>
                    </li>
                    <li class="nav-item cost-tab pay_tab active" style = "width :33%" onclick="activeTab(2)">
                        <a class="nav-link" id="pay-tab" data-toggle="tab" href="#" role="tab" aria-controls="cost" aria-selected="false" >가격정보</a>
                    </li>
                    <li class="nav-item cancel-tab pay_tab" style = "width :34%" onclick="activeTab(3)">
                        <a class="nav-link" id="cancel-tab" data-toggle="tab" href="#" role="tab" aria-controls="cancel" aria-selected="false" >취소,환불규정</a>
                    </li>
                </ul>
            </section>
            <div class = "exp" style="background : white;display: none;">
                <div style="font-weight: 700;font-size: 16px;padding: 20px;">
                    <img src="/iam/img/iam_pay_tool.png">
                </div>
            </div>
            <div class="pay">
                <form name="pay_form" id="pay_form" method="post"> <!--승인요청 및 결과수신페이지 지정 //-->
                    <input type="hidden" name="allat_encode_type" value="euc-kr">
                    <!--주문정보암호화필드-->
                    <input type="hidden" name="allat_enc_data" value=''>
                    <!--상점 ID-->
                    <input type="hidden" name="allat_shop_id" id="allat_shop_id" value="welcome101" size="19" maxlength=20>
                    <!--주문번호-->
                    <input type="hidden" name="allat_order_no" id="allat_order_no" value="<?php echo $mid;?>" size="19" maxlength=70>
                    <!--인증정보수신URL-->
                    <input type="hidden" name="shop_receive_url" id="shop_receive_url" value="https://<?php echo $_SERVER['SERVER_NAME'];?>/allat/pay/allat_receive.php?mid=<?php echo $mid;?>" size="19">
                    <!--승인금액-->
                    <input type="hidden" name="allat_amt" id="allat_amt" value="" size="19" maxlength=10>
                    <!--회원ID-->
                    <input type="hidden" name="allat_pmember_id" value="<?php echo $_SESSION['one_member_id'];?>" size="19" maxlength=20>
                    <!--상품코드-->
                    <input type="hidden" name="allat_product_cd" id="allat_product_cd" value="특별정기결제" size="19" maxlength=1000>
                    <!--상품명-->
                    <input type="hidden" name="allat_product_nm" id="allat_product_nm" value="특별정기결제" size="19" maxlength=1000>
                    <!--결제자성명-->
                    <input type="hidden" name="allat_buyer_nm" value="<?php echo $data['mem_name'];?>" size="19" maxlength=20>
                    <!--수취인성명-->
                    <input type="hidden" name="allat_recp_nm" value="<?php echo $data['mem_name'];?>" size="19" maxlength=20>
                    <!--수취인주소-->
                    <input type="hidden" name="allat_recp_addr" id="allat_recp_addr" value="<?php echo $data['mem_add1'];?>" size="19" maxlength=120>
                    <!--테스트 여부 테스트(Y),서비스(N) - Default : N 테스트 결제는 실결제가 나지 않으며 테스트 성공시 결과값은 "0001" 리턴-->
                    <input type="hidden" name="allat_test_yn" value="N" size="19" maxlength=1>
                    <!--결제 정보 수신 E-mail-->
                    <input type="hidden" name="allat_email_addr" id="allat_email_addr"  size="19" maxlength=50  value="<?php echo $data['mem_email']?$data['mem_email']:"turbolight@daum.net";?>">
                    <input type="hidden" name="allat_fix_type" value="" size="19" maxlength=3>
                    <input type="hidden" name="allat_registry_no">
                    <input type="hidden" name="gopaymethod" />
                    <input type="hidden" name="pay_type" id="pay_type" />
                    <input type="hidden" name="max_cnt" />
                    <input type="hidden" name="month_cnt" id="month_cnt" value="120"/>
                    <input type="hidden" name="member_type" id="member_type" />
                    <input type="hidden" name="fujia_status" id="fujia_status" />
                    <input type="hidden" name="mid" value="obmms20151" />
                    <div class="table-wrap">
                        <div id="spec_table" class="table-type">
                            <div class="row">
                                <div class="col-4" style="float: none">
                                    <div class="a3_1" style="text-align: center">
                                        <input type="radio" checked> 특별결제
                                    </div>
                                    <div  style="text-align: center;border: 2px solid;border-color: #cfcfcf">
                                        <div style="padding:10px;border-bottom: 2px solid;border-color: #cfcfcf;background-color: #dcf19d">
                                            <span style="font-size: 18px;font-weight: 800;font-family: 'notokr', serif">특별정기결제</span>
                                        </div>
                                        <div style="padding-top: 30px">
                                            <div style="display: flex;padding: 10px">
                                                <div><span style="font-size: 16px;font-weight: 600;font-family: 'notokr', serif">상품명</span></div>
                                                <div><input type="text" name="item_name" id="item_name" style="border-radius: 5px;line-height: 20px;text-align: center;margin-left: 14px"></div>
                                            </div>
                                        </div>
                                        <div style="padding-bottom: 15px">
                                            <div style="display: flex;padding: 10px">
                                                <div><span style="font-size: 16px;font-weight: 600;font-family: 'notokr', serif">결제금액</span></div>
                                                <div><input type="number" name="item_price" id="item_price" style="border-radius: 5px;line-height: 20px;text-align: right;" placeholder="원"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="a3">
                        <div class="a3_1 payment_2">
                            <input type="radio" name="payment_type" id="payment_type_month_card" value="month_card" checked> 카드정기결제
                        </div>
                    </div>

                    <div style="padding-bottom: 10px"> &xcirc;  [카드결제]는 결제후 담장자 승인없이 사용가능, 미결제시 자동관리 가능하지만, </div>
                    <input type="radio" name="payment_type" id="payment_type_month" value="month"> [통장결제]</input>는 ARS콜수신, 이용승인, 잔고부족시 이용 중지되므로 가능한 [카드결제]를 사용하세요<br />
                    <div class="a6" style="background-color:#fed700;display: none;">
                        <span>
                            이용정책 확인 후 결제가 가능합니다. &nbsp&nbsp&nbsp&nbsp
                            <button name="button"  style="background:#0a1e52; font-size:12px;"> <a href="/sub_14.php" target="_blank"  style=" color:#FFF;">이용정책 보기</a></button>
                            <script type="text/javascript">
                                function onPrivacy(){
                                window.open('/sub_14.php', "SingleSecondaryWindowName","resizable,scrollbars,status")
                                }
                            </script>
                        </span>
                    </div>
                    
                    <?if(!$_SESSION['one_member_id']){?>
                        <div class="a8"><a href="javascript:void(0)" onclick="alert('로그인후 이용이 가능합니다.');"><img src="/images/sub_02_btn_23.jpg" /></a></div>
                    <?}else {?>
                        <div class="a8"><a href="javascript:void(0)" onclick="pay(document.pay_form)"><img src="/images/sub_02_btn_23.jpg" /></a></div>
                    <?}?>
                </form>
            </div>
            <div class = "cancel" style="background : white;display: none">
                <div style="font-weight: 700;font-size: 16px;padding: 20px;">
                    <h4><b> [결제확인]</b></h4>
                    ∙ 마이페이지>> 결제정보에서 결제내역을 확인하세요.<br><br>

                    <h4><b> [정기결제 재개 또는 재가입하기]</b></h4>
                    ∙ 결제타입이나 수량변경을 하려면 기존 결제를 해지하고 다시 결제해주세요.<br>
                    ∙ 카드 기간만료나 분실 등으로 이용이 불가한 경우 해지하고 다시 결제해주세요.<br>
                    ∙ 결제 후 잔고부족으로 정지된 경우 재개를 원하시면, 무통장입금으로 한 달분을 입금 후 사이트 상단 카톡 상담창에 입금여부를 남겨주시면 다시 사용 승인해드립니다. <br>
                    ∙ 무통장입금은 아래 입금처로 보내주세요.<br>
                    SC제일은행 617-20-109431 온리원연구소<br>
                    ∙ 이때 입금된 금액은 해당월의 종료일까지 사용가능한 비용입니다. 이후 출금일에 잔고부족으로 미출금이 되면 다시 자동으로 이용정지되오니 이 점 유의하시길 바랍니다.<br><br>

                    <h4><b>[해지안내]</b></h4>
                    ∙ 마이페이지>> 결제정보에서 해지신청을 해주세요.<br>
                    ∙ 결제일 7일 전에 해지신청을 해야 해당 월 결제가 되지 않습니다.<br>
                    <span style = "color:red;">
                    ∙ 결제시 잔고부족으로 미출금되어도 해지신청을 하지 않으면, 이후 잔고가 채워질 시 자동출금될 수 있습니다. 해지신청을 하지 않은 상태에서의 출금에 대한 환불요청은 불가하오니 이 점 유의하시길 바랍니다.<br>
                    . 해지신청을 하지 않고 탈퇴를 해도 결제는 유지됩니다. 탈퇴를 하시기 전 반드시 해지신청을 하시길 바랍니다.
                    </span><br><br>
                    <h4><b> [이월안내]</b></h4>
                    ∙ 본 서비스는 통신사처럼 종량제로서 해당 월에 제공된 데이터를 사용하지 않으면 해당 월에 소멸되고 익월로 이월되지 않습니다.<br><br>

                    <h4><b>   [환불안내]</b></h4>
                    ∙ 전자상거래 및 소비자보호원의 표준약관 환불 규정에 따라 구매 후 본 소프트웨어를 이용했다면 솔루션 자체의 문제 등의 하자가 있는 경우를 제외하고 환불이 불가능합니다.<br>                 
                    ∙  구매 후 7일이 지나지 않았고 사용하지 않았다면, 환불을 요청할 수 있습니다.<br>
                    ∙  단 환불시 수수료, 승인처리 등의 비용을 차감 후 환불 가능합니다.<br>
                </div>
            </div>
        </div>
       <?
       mysqli_close($self_con);
       include_once "_foot.php";
       ?>
</body>

