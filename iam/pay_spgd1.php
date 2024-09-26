<?
extract($_GET);
//https://kiam.kr/iam/pay_spgd.php?item_name=%EC%BB%A4%ED%94%BC&item_price=4000&manager=1450614978&conidx=17135289&sale_cnt=0
?>
<script>
    if("<?=$platform?>" != "mobile" && navigator.userAgent.toLocaleLowerCase().search("android") > -1) {
        location.href = "?platform=mobile&item_price=4000&item_name=커피&manager=1450614978&conidx=17135289";
    }
</script>
<?
$mid = date("YmdHis").rand(10,99);
$item_name = '커피';
$item_price = 4000;
$seller_id = '1450614978';
$conidx = '17135289';

include "inc/header.inc.php";
$_SESSION[iam_member_id] = "andgallery";
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
    <meta property="og:title" content="아이엠으로 나를 브랜딩하기">
    <!--제목-->
    <meta property="og:description" content="1450614978님의 명함">
    <!--내용-->
    <meta property="og:image" content="https://dbimg.co.kr/editor/2211/1124/ewood_1th1.jpg">
    <!--이미지-->
    <!--오픈그래프 끝-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>아이엠으로 홍보와 소통이 가능해요</title>

    <link rel="shortcut icon" href="img/common/iconiam.ico">
    <link rel="stylesheet" href="css/notokr.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/new_style.css">
    <link rel="stylesheet" href="css/grid.min.css">
    <link rel="stylesheet" href="css/slick.min.css">
    <link rel='stylesheet' href='/plugin/toastr/css/toastr.css' />
    <link rel='stylesheet' href='/css/nanumgothic.css' />
    <link rel='stylesheet' href='/css/main.css' />
    <link rel='stylesheet' href='/css/sub_4_re.css' />
    <link rel='stylesheet' href='/css/responsive.css' /><!-- 2019.11 반응형 CSS -->
    <link rel='stylesheet' href='/css/font-awesome.min.css' /><!-- 2019.11 반응형 CSS -->
    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style_j.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/main.js"></script>
    <script src="/js/rlatjd_fun.js"></script>
    <script src="/js/jquery-2.1.0.js"></script>
    <script src="/js/rlatjd.js"></script>
    <script src="/js/jquery.carouFredSel-6.2.1-packed.js"></script>
    <script src="/jquery.lightbox_me.js"></script>
    <script src="/jquery.cookie.js"></script>
    <script src="/admin/bootstrap/js/bootstrap.js"></script>
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE"></script>
    <script src='/plugin/toastr/js/toastr.min.js'></script>
</head>
<style type="text/css">
    button, input, select, textarea {
    }
    input {
        /*line-height: normal!important;*/
    }
    button, input, optgroup, select, textarea {
    }
    ul li a{
        font-family: 'notokr', sans-serif;
        font-size: 14px;
    }
    .payon {padding:5px;width:60px;background:#67D1FF;color:#fff;}
    .payoff {padding:5px;width:60px;background:#efefef;}
    .nav-tabs>.pay_tab.active>a, .nav-tabs>.pay_tab.active>a:focus, .nav-tabs>.pay_tab.active>a:hover {
        color: #000!important;
        background-color: rgb(146, 208, 80)!important;
        border-radius: 0px!important;
    }
</style>
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
        $("#pay_cb").on('change',function(){
            if($(this).prop('checked') == true){
                $("#pay-modalwindow").modal("show");
            }
        });
        $(document).ajaxStop(function() {
            $("#ajax-loading").delay(10).hide(1); 
        });
        <?if($_SERVER['SERVER_NAME'] == "center.kiam.kr") {?>
        pay_total_v('add_phone_');
        <?}?>
        // 팝업 닫기 스크립트
        $('.popup-overlay, #closePopup').on('click', function() {
            $('.daily_popup').css('display', 'none');
            return false;
        });
        $("#item_count").keyup(function () {
            var item_count = $("#item_count").val();
            if(item_count == "" || item_count == 0){
                $("#item_price").val(<?=$item_price?>);
            }
            else{
                $("#item_price").val($("#item_price").val() * $("#item_count").val());
            }
        });
    });
    // 카드단회결제
    function ftn_approval(dfm) {
        pay_form.acceptCharset = 'euc-kr';
        document.charset = 'euc-kr';
        dfm.allat_product_nm.value = dfm.item_name.value;
        var navCase = navigator.userAgent.toLocaleLowerCase();
        if(navCase.search("android") > -1){
            dfm.allat_app_scheme.value = navCase;
            Allat_Mobile_Approval(dfm, 0, 0);
        }
        else{
            dfm.allat_app_scheme.value = '';
            AllatPay_Approval(dfm);
            // 결제창 자동종료 체크 시작
            AllatPay_Closechk_Start();
        }
    }

    // 결과값 반환( receive 페이지에서 호출 )
    function result_submit(result_cd,result_msg,enc_data) {
        //pay_form.acceptCharset = 'utf-8';
        //document.charset = 'utf-8';
        var navCase = navigator.userAgent.toLocaleLowerCase();
        if(navCase.search("android") > -1) {
            Allat_Mobile_Close();
        }else {
            // 결제창 자동종료 체크 종료
            AllatPay_Closechk_End();
        }
        if( result_cd != '0000' ){//pay_test
            //window.setTimeout(function(){alert(result_cd + " : " + result_msg);},1000);
            alert(result_cd + " : " + result_msg);
            location.reload();
        } else {
            pay_form.acceptCharset = 'utf-8';
            document.charset = 'utf-8';
            pay_form.allat_enc_data.value = enc_data;
            pay_form.action = "/pay_end_allat_item.php";
            pay_form.method = "post";
            pay_form.target = "_self";
            pay_form.submit();
        }
    }
    
    function active_pay(){
        $("#pay-modalwindow").modal("hide");
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
    function pay_go(frm){
        /*if($("#privacy_check").prop("checked") == false){
            $("#modal-1").modal("show");
            return;
        }*/
        var price = $("#item_price").val() * 1;
        $('#allat_amt').val(price);
        $('#price').val(price);
        $('#member_type').val($("#item_name").val());
        //$('#iam_pay_type').val($("#item_name").val());
        $('#month_cnt').val(120);
        if(!frm.mid.value){
            alert('결제종류를 선택해주세요.');
            document.getElementsByName('money_type')[0].focus();
            return false;
        }
        if($('#member_type').val() == ""){
            alert('상품명이 존재하지 않습니다.');
            return;
        }
        if(price <= 0){
            alert('결제하실 금액이 존재하지 않습니다.');
            return;
        }
        if(confirm('결제시작하시겠습니까?')){
            if($('#allat_recp_addr').val() == "") {
                $.ajax({
                    type:"POST",
                    url:"/ajax/get_mem_address.php",
                    dataType:"json",
                    data:{mem_id:'<?=$_SESSION[iam_member_id]?>'},
                    success: function(data){
                        console.log(data.address);
                        $('#allat_recp_addr').val(data.address);
                        $('#pay_form').attr("action","/allat/mp/allat_fix_spec.php");
                        ftn_approval(document.pay_form);
                    },
                    error: function () {
                        alert('내 정보 수정에서 자택주소를 추가해주세요.');
                        location.href = "mypage.php";
                        return;
                    }
                });
            }else{
                // $('#pay_form').attr("action","/allat/mp/allat_fix_spec.php");
                ftn_approval(document.pay_form);
            }
            /*if($('#allat_email_addr').val() == "") {
                alert('내 정보 수정에서 이메일 주소를 추가해주세요.');
                return;
            }*/
        }
    }
</script>

<body style="padding-top: 0px">
<!--단회결제체크시 팝업-->
<div id="pay-modalwindow" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header" style="border:none;background-color: #92d050">
                <div class="login_bold" style="color: #ffffff;text-align: center;font-size: 24px;margin-bottom: 0px">상품 결제 안내</div>
            </div>
            <div class="modal-body">
                <div class="login_bold" style="margin-bottom: 0px">
                    카드 할부결제는 개인과 카드사와의 약정에 따라 가능합니다.본 쇼핑몰에서 지원하는 할부정책은 없습니다.
                </div>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button class="btn btn-secondry" onclick="active_pay();" style="color: #ffffff;background-color: #bbbbbb">확인</button>
            </div>
        </div>
    </div>
</div>

<div class="big_main pay-wrap" style="height: auto;min-height: 100%;">
    <div id="wrap" class="common-wrap" background-image = "/images/main_bg_09.jpg" style="max-width: 1024px;padding:0px">
    <div>
        <!-- <img src="/images/sub_02_visual_03.jpg" style = "width : 100%"/> -->
    </div>
    <section id = "pay_menu" style="background: white;margin-top: 88px;">
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
    <div class="pay" style="border: 1px solid #ddd;">
        <form name="pay_form" id="pay_form" method="post"> <!--승인요청 및 결과수신페이지 지정 //-->
            <!--주문정보암호화필드-->
            <input type="hidden" name="allat_enc_data" value=''>
            <input type="hidden" name="allat_app_scheme" value=''>
            <!--상점 ID-->
            <input type="hidden" name="allat_shop_id" id="allat_shop_id" value="welcome101" size="19" maxlength=20>
            <!--주문번호-->
            <input type="hidden" name="allat_order_no" id="allat_order_no" value="<?=$mid;?>" size="19" maxlength=70>
            <!--인증정보수신URL-->
            <input type="hidden" name="shop_receive_url" id="shop_receive_url" value="https://<?php echo $_SERVER['SERVER_NAME'];?>/allat/pay/allat_receive.php?mid=<?php echo $mid;?>" size="19">
            <!--승인금액-->
            <input type="hidden" name="allat_amt" id="allat_amt" value="" size="19" maxlength=10>
            <!--회원ID-->
            <input type="hidden" name="allat_pmember_id" value="<?=$_SESSION[iam_member_id];?>" size="19" maxlength=20>
            <!--상품코드-->
            <input type="hidden" name="allat_product_cd" id="allat_product_cd" value="상품결제" size="19" maxlength=1000>
            <!--상품명-->
            <input type="hidden" name="allat_product_nm" id="allat_product_nm" value="상품결제" size="19" maxlength=1000>
            <!--결제자성명-->
            <input type="hidden" name="allat_buyer_nm" value="grime" size="19" maxlength=20>
            <!--수취인성명-->
            <input type="hidden" name="allat_recp_nm" value="grime" size="19" maxlength=20>
            <!--수취인주소-->
            <input type="hidden" name="allat_recp_addr" id="allat_recp_addr" value="충청남도 홍성군 갈산면" size="19" maxlength=120>
            <!--배당율-->
            <input type="hidden" name="pay_percent" id="pay_percent" value="90">
            <input type="hidden" name="seller_id" id="seller_id" value="<?=$seller_id?>">
            <input type="hidden" name="cont_idx" id="cont_idx" value="<?=$conidx?>">
            <div class="table-wrap">
                <div id="spec_table" class="table-type">
                    <div class="row">
                        <div class="col-6" style="float: none">
                            <!-- <div class="a3_1" style="text-align: center">
                                <input type="radio" checked> 특별결제
                            </div> -->
                            <div  style="text-align: center;border: 2px solid;border-color: #cfcfcf">
                                <div style="padding:10px;border-bottom: 2px solid;border-color: #cfcfcf;background-color: #dcf19d">
                                    <input type="checkbox" id = "pay_cb"><span style="font-size: 18px;font-weight: 800;font-family: 'notokr', serif">상품결제하기</span>
                                </div>
                                <div style="padding-top: 30px">
                                    <div style="display: flex;padding: 10px">
                                        <div><span style="font-size: 16px;font-weight: 600;font-family: 'notokr', serif">상품명</span></div>
                                        <div><input type="text" name="item_name" id="item_name" style="border-radius: 5px;line-height: 20px;text-align: center;margin-left: 14px" readonly value="<?=$item_name?>"></div>
                                    </div>
                                </div>
                                <!-- <div style="padding-top: 30px">
                                    <div style="display: flex;padding: 10px">
                                        <div><span style="font-size: 16px;font-weight: 600;font-family: 'notokr', serif">상품건수</span></div>
                                        <div><input type="number" name="item_count" id="item_count" style="border-radius: 5px;line-height: 20px;text-align: center;margin-left: 14px" value="1"></div>
                                    </div>
                                </div> -->
                                <div style="padding-bottom: 15px">
                                    <div style="display: flex;padding: 10px">
                                        <div><span style="font-size: 16px;font-weight: 600;font-family: 'notokr', serif">결제금액</span></div>
                                        <div><input type="number" name="item_price" id="item_price" style="border-radius: 5px;line-height: 20px;text-align: right;" placeholder="원" readonly value="<?=$item_price?>"></div>
                                    </div>
                                </div>
                                <?if(!$_SESSION[iam_member_id]){?>
                                    <div class="a8">
                                        <a href="javascript:void(0)" onclick="alert('로그인후 이용이 가능합니다.');">
                                            <img src="/images/sub_02_btn_23.jpg" style="width: 80%"/>
                                        </a>
                                    </div>
                                <?}else {?>
                                    <div class="a8">
                                        <a href="javascript:void(0)" onclick="pay_go(document.pay_form)">
                                            <img src="/images/sub_02_btn_23.jpg"  style="width: 80%"/>
                                        </a>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="gopaymethod" />
            <input type="hidden" name="pay_type" id="pay_type" />
            <input type="hidden" name="max_cnt" />
            <input type="hidden" name="month_cnt" id="month_cnt" value="120"/>
            <input type="hidden" name="member_type" id="member_type" />
            <input type="hidden" name="fujia_status" id="fujia_status" />
            <input type="hidden" name="mid" value="obmms20151" />

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
mysql_close();
include_once "_foot.php";
?>
</body>

