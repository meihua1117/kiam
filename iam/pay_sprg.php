<?
extract($_GET);
?>
<script>
    if("<?=$platform?>" != "mobile" && navigator.userAgent.toLocaleLowerCase().search("android") > -1) {
        location.href = "?platform=mobile";
    }
</script>
<?
include "inc/header.inc.php";
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
    ul li a
    {
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
        //$('input[id=payment]').on("leave")
    });
    function ftn_fix(dfm) {
        $.ajax({
            type: "POST",
            url: "/makeData_sprg.php",
            data: $('#pay_form').serialize(),
            dataType: 'html',
            success: function (data) {
                var navCase = navigator.userAgent.toLocaleLowerCase();
                if(navCase.search("android") > -1)
                    Allat_Mobile_Fix(dfm, "0", "0");
                else
                    Allat_Plus_Fix(dfm, "0", "0");
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
            pay_form.action = "/allat/mp/allat_fix_spec.php";
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
        if(confirm('결제시작하시겠습니까?'))
        {
            if($('input[name=payment_type]:checked').val()=="month") {// 통장정기결제
                    frm.target='_self';
                    frm.action='/pay_month_sprg.php?link=iam';
                    frm.submit();
            }else if($('input[name=payment_type]:checked').val()=="month_card") {//카드정기결제
                if($('#allat_recp_addr').val() == "") {
                    $.ajax({
                        type:"POST",
                        url:"/ajax/get_mem_address.php",
                        dataType:"json",
                        data:{mem_id:'<?=$_SESSION['iam_member_id']?>'},
                        success: function(data){
                            console.log(data.address);
                            $('#allat_recp_addr').val(data.address);
                            $('#allat_shop_id').val('bwelcome12');
                            $('#shop_receive_url').val('https://<?php echo $_SERVER['SERVER_NAME'];?>/allat/mp/allat_receive.php?mid=<?php echo $mid;?>');
                            $('#pay_form').attr("action","/allat/mp/allat_fix_spec.php");
                            ftn_fix(document.pay_form);
                        },
                        error: function () {
                            alert('내 정보 수정에서 자택주소를 추가해주세요.');
                            location.href = "mypage.php";
                            return;
                        }
                    });
                }else{
                    $('#allat_shop_id').val('bwelcome12');
                    $('#shop_receive_url').val('http://<?php echo $_SERVER['SERVER_NAME'];?>/allat/mp/allat_receive.php?mid=<?php echo $mid;?>');
                    $('#pay_form').attr("action","/allat/mp/allat_fix_spec.php");
                    ftn_fix(document.pay_form);
                }
            }
        }
    }
    
    var share_kind = 0;
    function showSNSModal(kind){
        if(!kind)
            share_kind = 0;
        else
            share_kind = 1;
        $("#sns-modalwindow").modal("show");
    }
    //데일리 발송 팝업
    function daily_send_pop() {
        $("#sns-modalwindow").modal("hide");
        iam_count('iam_msms');
        console.log($(window).height() + "|" + $(this).outerHeight() + "|" + $(window).scrollTop());
        jQuery.fn.center = function() {
            this.css('position', 'absolute');
            this.css('width', '100%');
            this.css('top', Math.min(400, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + 'px');
            this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window)
                .scrollLeft()) + 'px');
            return this;
        }
        $(".daily_popup").center();
        $('.daily_popup').css('display', 'block');
        var iam_link = "";
        if(share_kind == 0) {
            iam_link = "daily_write_iam.php?msg=" + '<?=$G_card['card_name']?>' + "님의 명함입니다.  " +
            "<?=$G_card['card_company'].'<br>'.$G_card['card_position'].'<br>'.$G_card['card_phone'].'<br>'.$domainData['sub_domain'].'/?'.$G_card['card_short_url']?>" +
            " 모바일 명함을 새로 만들었습니다. 휴대폰에 저장부탁해요. 혹시 명함 만들면 저에게도 보내주시구요. 감사합니다. ";
        }
        else if(share_kind == 1) {
            iam_link = "daily_write_iam.php?msg=" + '<?=$G_card['card_name']?>' + "님의 명함입니다.  " +
            '<?=$G_card['card_company']?> ' + ' <?=$G_card['card_position']?> ' + ' <?=$G_card['card_phone']?> ' + '<?=$domainData['sub_domain']."/?mem_code=".$all_card_row['mem_code']?>' +
            " 모바일 명함을 새로 만들었습니다. 휴대폰에 저장부탁해요. 혹시 명함 만들면 저에게도 보내주시구요. 감사합니다. ";
        }
        //location = iam_link;
        $("#daily_popup_content").prop("href",iam_link);
    }
    function sns_sendSMS(){
        $("#sns-modalwindow").modal("hide");
        var iam_link = "";
        if(share_kind == 0)
            iam_link = '<?=$G_card['card_name']?>님의 명함 <?=$G_card['card_company']?> <?=$G_card['card_position']?> <?=$G_card['card_phone']?> <?php echo $domainData['sub_domain'];?>/?<?=$G_card['card_short_url']?>';
        else
            iam_link = '<?=$G_card['card_name']?>님의 명함 <?=$G_card['card_company']?> <?=$G_card['card_position']?> <?=$G_card['card_phone']?> <?=$domainData['sub_domain']."/?mem_code=".$all_card_row['mem_code']?>';
        iam_sms(iam_link);
    }
    function iam_sms(url) {
        // location.href = '';
        iam_count('iam_sms');
        location.href = "sms:" + "<?echo (preg_match('/iPhone/',$_SERVER['HTTP_USER_AGENT']))?'&':'?';?>body=" + url;
    }
    function iam_count(str) {
        var member_id = '<?=$card_owner?>';
        var card_idx = '<?=$G_card['idx']?>';
        var formData = new FormData();
        formData.append('str', str);
        formData.append('mem_id', member_id);
        formData.append('card_idx', card_idx);
        $.ajax({
            type: "POST",
            url: "ajax/iam_count.proc.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
            }
        })
    }
    function sns_shareKakaoTalk(){
        $("#sns-modalwindow").modal("hide");
        shareKakaotalk();
    }
    function shareKakaotalk() {
        var iam_link = "";
        if(share_kind == 0)
            iam_link = '<?=$domainData['sub_domain'];?>/?<?=$G_card['card_short_url']?>';
        else
            iam_link = '<?=$domainData['sub_domain']."/?mem_code=".$all_card_row['mem_code']?>';
        iam_count('iam_kakao');
        try{
            Kakao.Link.sendDefault({
                objectType: "feed",
                content: {
                    title: "아이엠으로 나를 브랜딩하기", // 콘텐츠의 타이틀
                    description: "<?=$G_card['card_name']?>/<?=$G_card['card_company']?>/<?=$G_card['card_position']?>/<?=$G_card['card_phone']?>", // 콘텐츠 상세설명
                    imageUrl: "<?=$main_img1?>", // 썸네일 이미지
                    link: {
                        mobileWebUrl: iam_link, // 모바일 카카오톡에서 사용하는 웹 링크 URL
                        webUrl: iam_link // PC버전 카카오톡에서 사용하는 웹 링크 URL
                    }
                },
                buttons: [{
                    title: "우리 프렌즈해요!", // 버튼 제목
                    link: {
                        mobileWebUrl: iam_link, // 모바일 카카오톡에서 사용하는 웹 링크 URL
                        webUrl: iam_link // PC버전 카카오톡에서 사용하는 웹 링크 URL
                    }
                }]
            });
        }catch(e){
            alert("Kakao talk 과 연동할수 없습니다.");
        }
    }
    function sns_shareEmail(){
        $("#sns-modalwindow").modal("hide");
    }
    function sns_shareFaceBook(){
        $("#sns-modalwindow").modal("hide");
        var iam_link = "";
        if(share_kind == 0)
            iam_link = '<?=$domainData['sub_domain'];?>/?<?=$G_card['card_short_url']?>';
        else
            iam_link = '<?=$domainData['sub_domain']."/?mem_code=".$all_card_row['mem_code']?>';
        shareFaceBook('<?=$G_card['card_name']?>님의 명함 <?=$G_card['card_company']?> <?=$G_card['card_position']?>', iam_link);
    }
    function shareFaceBook(desc, url) {
        var text = encodeURIComponent(desc);
        var linkUrl = encodeURIComponent(url);
        var title = '아이엠으로 나를 브랜딩하기';
        var description = desc;
        var imgUrl = '<?=$main_img1?>';

        if (!$('meta[property="og:title"').attr('content')) {
            $('head').append(format('<meta property="og:title" content="{0}" />', title));
        } else {
            $('meta[property="og:title"').attr('content', title);
        }
        if (!$('meta[property="og:description"').attr('content')) {
            $('head').append(format('<meta property="og:description" content="{0}" />', description));
        } else {
            $('meta[property="og:description"').attr('content', description);
        }
        if (!$('meta[property="og:image"').attr('content')) {
            $('head').append(format('<meta property="og:image" content="{0}" />', imgUrl));
        } else {
            $('meta[property="og:image"').attr('content', imgUrl);
        }
        iam_count('iam_facebook');
        window.open('http://www.facebook.com/sharer.php?u=' + linkUrl + '&t=' + text);
    }
    // 카카오톡
    try{
        Kakao.init("<?php echo $domainData['kakao_api_key']!=""?$domainData['kakao_api_key']:"c0550dad4e9fdb8a298f6a5ef39ebae6";?>"); // 사용할 앱의 JavaScript 키를 설정
        //Kakao.init("2e50869591823e28ed57afa55ff56b47");      // 사용할 앱의 JavaScript 키를 설정
    }catch(e){
        console.log("Kakao 로딩 failed : " + e);
    }
    function sns_copyContacts(){
        $("#sns-modalwindow").modal("hide");
        copy();
    }
    //텍스트 복사
    function copy() {
        iam_count('iam_share');
        var iam_link = "";
        if(share_kind == 0)
            iam_link = '<?=$domainData['sub_domain'];?>/?<?=$G_card['card_short_url']?>';
        else
            iam_link = '<?=$domainData['sub_domain']."/?mem_code=".$all_card_row['mem_code']?>';
        // 글을 쓸 수 있는 란을 만든다.
        var aux = document.createElement("input");
        // 지정된 요소의 값을 할당 한다.
        aux.setAttribute("value",
            "<?=$G_card['card_name']?>님의 명함 <?=$G_card['card_company']?> <?=$G_card['card_position']?> <?=$G_card['card_phone']?> "+iam_link
        );
        // bdy에 추가한다.
        document.body.appendChild(aux);
        // 지정된 내용을 강조한다.
        aux.select();
        // 텍스트를 카피 하는 변수를 생성
        document.execCommand("copy");
        // body 로 부터 다시 반환 한다.
        document.body.removeChild(aux);
        alert("복사에 성공 하였습니다.");
    }
</script>
<body style="padding-top: 0px">
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
                    <input type="hidden" name="allat_buyer_nm" value="<?=$Gn_mem_row['mem_name'];?>" size="19" maxlength=20>
                    <!--수취인성명-->
                    <input type="hidden" name="allat_recp_nm" value="<?=$Gn_mem_row['mem_name'];?>" size="19" maxlength=20>
                    <!--수취인주소-->
                    <input type="hidden" name="allat_recp_addr" id="allat_recp_addr" value="<?=$Gn_mem_row['mem_add1'];?>" size="19" maxlength=120>
                    <div class="table-wrap">
                        <div id="spec_table" class="table-type">
                            <div class="row">
                                <div class="col-6" style="float: none">
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
                    <input type="hidden" name="gopaymethod" />
                    <input type="hidden" name="pay_type" id="pay_type" />
                    <input type="hidden" name="max_cnt" />
                    <input type="hidden" name="month_cnt" id="month_cnt" value="120"/>
                    <input type="hidden" name="member_type" id="member_type" />
                    <input type="hidden" name="fujia_status" id="fujia_status" />
                    <input type="hidden" name="mid" value="obmms20151" />
                    <?if(!$_SESSION['iam_member_id']){?>
                        <div class="a8"><a href="javascript:void(0)" onclick="alert('로그인후 이용이 가능합니다.');"><img src="/images/sub_02_btn_23.jpg" /></a></div>
                    <?}else {?>
                        <div class="a8"><a href="javascript:void(0)" onclick="pay_go(document.pay_form)"><img src="/images/sub_02_btn_23.jpg" /></a></div>
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

