<?
extract($_GET);
?>
<script>
    if("<?=$platform?>" != "mobile" && navigator.userAgent.toLocaleLowerCase().search("android") > -1) {
        location.href = "?platform=mobile&item_price=" + '<?=$item_price?>'+'&url=' + '<?=$url?>';
    }
</script>
<?
include "inc/header.inc.php";
$item_name = "포인트충전";
$item_price = 0;
$seller_id = "";
if(isset($_GET['item_price'])){
    $item_price = $_GET['item_price'] * 1;
}
if(isset($_GET['url'])){
    $cur_url = $_GET['url'];
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
    <meta property="og:title" content="아이엠으로 나를 브랜딩하기">
    <!--제목-->
    <meta property="og:description" content="<?=$G_card['card_name']?>님의 명함 <?=$G_card['card_company']?> <?=$G_card['card_position']?>">
    <!--내용-->
    <meta property="og:image" content="<?=$main_img1?>">
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
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="utf-8"></script>
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
        $("#set_point").keyup(function(){
            point = $(this).val();
            // console.log(cnt);
            $("#item_price").val(point * 1);
        });
    });
    // 카드단회결제
    function ftn_approval(dfm) {
        $.ajax({
            type:"POST",
            url:"/makeData_spoc_selling.php",
            data:$('#pay_form').serialize(),
            dataType: 'html',
            success:function(data){
            },
            error: function(){
                alert('로딩 실패');
            }
        });
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
            iam_item('<?=$_SESSION['iam_member_id'];?>', 'buy', 'card');
            pay_form.acceptCharset = 'utf-8';
            document.charset = 'utf-8';
            pay_form.allat_enc_data.value = enc_data;
            pay_form.action = "/pay_end_allat_spoc_selling.php";
            pay_form.method = "post";
            pay_form.target = "_self";
            pay_form.submit();
        }
    }
    
    function active_pay(){
        $("#pay-modalwindow").modal("hide");
        $("#set_point").prop("readonly",false);
        $("#item_price").prop("readonly",false);
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
        if($('#member_type').val() == ""){
            alert('상품명이 존재하지 않습니다.');
            return;
        }
        if(price <= 0)
        {
            alert('결제하실 금액이 존재하지 않습니다.');
            return;
        }
        if(confirm('결제시작하시겠습니까?')){
            if($('#allat_recp_addr').val() == "") {
                $.ajax({
                    type:"POST",
                    url:"/ajax/get_mem_address.php",
                    dataType:"json",
                    data:{mem_id:'<?=$_SESSION['iam_member_id']?>'},
                    success: function(data){
                        $('#allat_recp_addr').val(data.address);
                        ftn_approval(document.pay_form);
                    },
                    error: function () {
                        alert('내 정보 수정에서 자택주소를 추가해주세요.');
                        location.href = "/iam/mypage.php?link=pay_point";
                        return;
                    }
                });
            }else {
                ftn_approval(document.pay_form);
            }
        }
    }

    function iam_item(memid, action, type){
        if(action == 'use'){
            console.log("use123123");
            $.ajax({
                type:"POST",
                url:"iam_item_mng.php",
                dataType:"json",
                data:{use:true, memid:memid},
                success:function (data) {
                    console.log(data);
                    // alert("결제 되었습니다!");
                }
            })
        }
        else{
            $.ajax({
                type:"POST",
                url:"/iam/iam_item_mng.php",
                dataType:"json",
                data:{buy:true, memid:memid},
                success:function (data) {
                    console.log(data);
                }
            })
        }
    }
</script>

<body style="padding-top: 0px">
<!--단회결제체크시 팝업-->
<div id="pay-modalwindow" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header" style="border:none;background-color: #92d050">
                <div class="login_bold" style="color: #ffffff;text-align: center;font-size: 24px;margin-bottom: 0px">특별단회 결제안내</div>
            </div>
            <div class="modal-body">
                <div class="login_bold" style="margin-bottom: 0px">
                    1.본 결제는 카드결제만 가능합니다.<br>
                    2.카드 할부결제는 개인의 카드사와의 약정에 따라 가능합니다.당사에서 지원하는 할부정책은 없습니다.
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
    <div class="pay" style="border: 1px solid #ddd;">
        <form name="pay_form" id="pay_form" method="post"> <!--승인요청 및 결과수신페이지 지정 //-->
            <!--주문정보암호화필드-->
            <input type="hidden" name="allat_enc_data" value=''>
            <input type="hidden" name="allat_app_scheme" value=''>
            <input type="hidden" name="allat_autoscreen_yn" value='Y'>
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
            <input type="hidden" name="allat_product_cd" id="allat_product_cd" value="포인트충전" size="19" maxlength=1000>
            <!--상품명-->
            <input type="hidden" name="allat_product_nm" id="allat_product_nm" value="포인트충전" size="19" maxlength=1000>
            <!--결제자성명-->
            <input type="hidden" name="allat_buyer_nm" value="<?php echo $Gn_mem_row['mem_name'];?>" size="19" maxlength=20>
            <!--수취인성명-->
            <input type="hidden" name="allat_recp_nm" value="<?php echo $Gn_mem_row['mem_name'];?>" size="19" maxlength=20>
            <!--수취인주소-->
            <input type="hidden" name="allat_recp_addr" id="allat_recp_addr" value="<?=$Gn_mem_row['mem_add1'];?>" size="19" maxlength=120>
            <input type="hidden" name="cur_url" id="cur_url" value="<?php echo $cur_url;?>">
            <div class="table-wrap">
                <div id="spec_table" class="table-type">
                    <div class="row">
                        <div class="col-6" style="float: none">
                            <div class="a3_1" style="text-align: center">
                                <input type="radio" checked> 포인트충전
                            </div>
                            <div  style="text-align: center;border: 2px solid;border-color: #cfcfcf">
                                <div style="padding:10px;border-bottom: 2px solid;border-color: #cfcfcf;background-color: #dcf19d">
                                    <input type="checkbox" id = "pay_cb"><span style="font-size: 18px;font-weight: 800;font-family: 'notokr', serif">포인트카드결제</span>
                                </div>
                                <div style="padding-top: 30px">
                                    <div style="display: flex;padding: 10px">
                                        <div><span style="font-size: 16px;font-weight: 600;font-family: 'notokr', serif">포인트</span></div>
                                        <div><input type="number" name="set_point" id="set_point" value="<?=$item_price?>" style="border-radius: 5px;line-height: 20px;text-align: center;margin-left: 14px" readonly><input type="text" name="item_name" id="item_name" style="border-radius: 5px;line-height: 20px;text-align: center;margin-left: 14px" readonly hidden value="<?=$item_name?>"></div>
                                    </div>
                                </div>
                                <div style="padding-bottom: 15px">
                                    <div style="display: flex;padding: 10px">
                                        <div><span style="font-size: 16px;font-weight: 600;font-family: 'notokr', serif">결제금액</span></div>
                                        <div><input type="number" name="item_price" id="item_price" style="border-radius: 5px;line-height: 20px;text-align: center;" placeholder="원" readonly value="<?=$item_price?>"></div>
                                    </div>
                                </div>
                                <?if(!$_SESSION['one_member_id']){?>
                                    <div class="a8">
                                        <a href="javascript:void(0)" onclick="alert('로그인후 이용이 가능합니다.');">
                                            <img src="/images/sub_02_btn_23.jpg" style="width: 80%"/>
                                        </a>
                                    </div>
                                <?}else {?>
                                    <div class="a8">
                                        <a href="javascript:void(0)" onclick="pay_go(document.pay_form)">
                                            <img src="/images/sub_02_btn_23.jpg" style="width: 80%"/>
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
<!--데일리 발송 팝업-->
<div id="popup" class="daily_popup">
    <!-- 팝업 시작 -->
    <div class="popup-wrap" id="dailysend">
        <div class="text-wrap">
            <h3>내 아이엠을 내 폰 지인 모두에게 보내기!</h3><br><br>
            새명함이 나오면 지인들께 보내고<br>
            싶은데 방법이 마땅치 않지요?<br><br>

            ①데일리발송기능과 ②내 폰안의 무료 문자를<br>
            이용하여 내 폰주소록의 모든 지인에게<br>
            매일매일 자동으로 발송해보세요!<br>
            <br><br>
            <h3>내 아이엠을 보내는 절차!</h3><br><br>

            <a href="join.php">첫째 회원가입 먼저 해야지요(클릭)</a><br>
            <a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690">둘째 내 폰의 문자를 보내려면 앱을
                설치해야지요!(클릭)</a><br>
            셋째 데일리발송을 시작해요!<br>
            ※ 아이엠을 보내는 기능은 무료이지만 일반 메시지를 보내는 것은 유료입니다.</h3>
        </div>
        <div class="button-wrap">
            <?if($_SESSION['iam_member_id']) {?>
                <a href="#" id="closePopup" class="buttons is-cancel">다음에보내기</a>
                <a  id="daily_popup_content" href="#" target="_blank" class="buttons is-save">시작하기</a>
            <?} else {?>

                <a href="#" id="closePopup" class="buttons is-cancel">다음에보내기</a>
                <a href="login.php"
                   target="_blank" class="buttons is-save">시작하기</a>
            <?}?>
        </div>
    </div>
    <div class="popup-overlay"></div>
</div><!-- // 팝업 끝 -->
<!--공유하기 팝업-->
<div id="sns-modalwindow" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 200px;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header" style="border:none;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ><img src = "img/icon_close_black.svg"></button>
            </div>
            <div class="modal-body">
                <div class="center_text">
                    <div class="sns_item" onclick="daily_send_pop()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon1.png"></div><div class="sns_item_text" >데일리<br>문자발송</div></div>
                    <div class="sns_item" onclick="sns_sendSMS()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon2.png"></div><div class="sns_item_text" >문자<br>보내기</div></div>
                    <div class="sns_item" onclick="sns_shareKakaoTalk()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon3.png"></div><div class="sns_item_text" >카톡<br>공유</div></div>
                </div>
                <div class="center_text">
                    <!--        <div class="sns_item" onclick="sns_shareInsta()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon7.png"></div><div class="sns_item_text" >인스타<br>공유</div></div>
                                 <div class="sns_item" onclick="sns_shareBand()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon6.png"></div><div class="sns_item_text" >밴드<br>공유</div></div> -->
                    <div class="sns_item" onclick="sns_shareEmail()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon8.png"></div><div class="sns_item_text" >이메일<br>공유</div></div>
                    <div class="sns_item" onclick="sns_shareFaceBook()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon5.png"></div><div class="sns_item_text" >페북<br>공유</div></div>
                    <div class="sns_item" onclick="sns_copyContacts()"><div class="sns_icon_div"><img class="sns_icon" src = "img/sns_icon4.png"></div><div class="sns_item_text" >주소<br>복사</div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?
mysqli_close($self_con);
include_once "_foot.php";
?>
</body>

