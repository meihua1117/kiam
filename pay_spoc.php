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
$sql="select * from Gn_Member where mem_id='$_SESSION[one_member_id]' ";
$resul=mysql_query($sql);
$data=mysql_fetch_array($resul);
// 이미 진행중인 결제가 있는지 확인
$mid = date("YmdHis").rand(10,99);
/*$query = "select * from tjd_pay_result where buyer_id='$_SESSION[one_member_id]' and end_status='Y' and `end_date` > '$date'";
$res = mysql_query($query);
$sdata = mysql_fetch_array($res);
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
        $(document).ajaxStart(function() {
            $("#ajax-loading").show();
        })
        .ajaxStop(function() {
            $("#ajax-loading").delay(10).hide(1);
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
                pay_form.acceptCharset = 'euc-kr';
                document.charset = 'euc-kr';
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
            },
            error: function(){
                alert('로딩 실패');
            }
        });
        
    }

    // 결과값 반환( receive 페이지에서 호출 )
    function result_submit(result_cd,result_msg,enc_data) {
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
            pay_form.action = "/pay_end_allat_spoc_selling.php";
            pay_form.method = "post";
            pay_form.target = "_self";
            pay_form.submit();
        }
    }
    
    function active_pay(){
        $("#pay-modalwindow").modal("hide");
        $("#item_name").prop("readonly",false);
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
    function pay(frm){
        var price = $("#item_price").val() * 1;
        $('#allat_amt').val(price);
        $('#price').val(price);
        $('#member_type').val($("#item_name").val());
        $('#allat_product_nm').val($("#item_name").val());
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
            if($('#allat_recp_addr').val() == "") {
                $.ajax({
                    type:"POST",
                    url:"/ajax/get_mem_address.php",
                    dataType:"json",
                    data:{mem_id:'<?=$_SESSION[one_member_id]?>'},
                    success: function(data){
                        console.log(data.address);
                        $('#allat_recp_addr').val(data.address);
                        ftn_approval(document.pay_form);
                    },
                    error: function () {
                        alert('내 정보 수정에서 자택주소를 추가해주세요.');
                        location.href = "/mypage.php?link=pay_spoc";
                        return;
                    }
                });
            }else {
                ftn_approval(document.pay_form);
            }
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
                <h4><b>서비스소개</b></h4><br>
                본 서비스는 자동셀링통합 솔루션입니다.<br>
                디비수집, 폰문자, 콜백문자, 스텝문자 발송까지 이용 가능한 통합상품입니다.<br>
                따라서 하나의 솔루션만 별도 결제는 되지 않습니다.<br><br>


                1. 디비수집 :잠재고객의 디비를 자동으로 수집해주는 솔루션<br>
                2. 폰문자발송 : 휴대폰의 잔여문자를 PC에 연동해 문자 발송하고 관리해주는 솔루션<br>
                3. 원스텝발송 : 시나리오 1회 세팅으로 자동으로 문자 발송하고 관리해주는 솔루션<br>
                4. 콜백문자발송: 전화와 문자 모두 콜백이 가능한 콜백서비스<br><br><br>

                <div style="margin-bottom:25px;border:1px solid #9e9fae;padding:10px;line-height:25px;">
                    <div class="a3_1">
                        <p>정기결제 유의사항</p>
                    </div>
                    <p>1. 부가세 포함 안내 : 정기결제금액은 부가세가 포함된 가격입니다.<br/>
                        2. 정기결제변경처리 : 결제타입이나 수량변경을 하려면 기존 결제를 해지하고 다시 신청해주세요.<br/>
                           <span style = "color:red;">
                           3. 정기결제해지위치 : 정기결제는 고객님의 마이페이지에서 가능합니다.
                           </span><br/>
                        4. 정기결제해지처리 : 고객 계정 오픈 후 첫 달 결제일 전에 해지해도 첫 달 결제 후 해지 처리되며, 정기 결제일이 지난 후 해지하면 해당 월의 사용기간이 끝날 때 해지 됩니다.<br/>
                        5. 통장정기결제출금일자 : 정기결제 출금일은 결제신청을 한 날로부터 7일 후로 신청됩니다.<br/>
                        6. 통장정기결제신청완료 : 정기결제 신청 후 휴대폰으로 ARS전화를 받아야 신청이 완료됩니다.<br/>
                           <span style = "color:red;">
                           7. 약정기간 안내 : 월 정기결제는 약정기간이 없으며 언제든 해지하려고 하면 마이페이지에서 해지 신청을 하면 됩니다.
                           </span><br>
                        8. 종량제 상품 안내 : 본 상품은 통신상품처럼 종량제로서 해당 월에 제공한 데이터는 사용하지 않으면 해당월에 소멸됩니다.</p>
                </div>

                <div style="margin-bottom:25px;border:1px solid #9e9fae;padding:10px;line-height:25px;">
                    <div class="a3_1">
                        <p>온리원문자 유의사항</p>
                    </div>
                    <p>1. 안드로이드폰 전용  : 2014년 5월 이후부터 출시된 안드로이드폰이며 아이폰(X)은 사용할 수 없습니다.<br/>
                        2. 통신요금 확인 필수 : 휴대폰 통신사 통신요금제의 문자 발송 수 확인 후 가입하시기 바랍니다.<br/>
                        3. 휴대폰 사용량 안내 : 결제한 휴대폰 개수보다 적게 사용해도 환불은 되지 않습니다.<br/>
                        4. 문자 발송 번호 : 문자 발송 번호는 휴대폰 번호입니다. 투넘버나 임의 번호 사용이 불가능합니다. <br/>
                        4. 온리원문자는 문자발송 솔루션입니다.  온리원셀링에서 발송된 문자내용은 발송자가 제공하는 정보로서 이를 신뢰하여 취한 조치에 대해 책임지지 않습니다.<br/>
                    </p>
                </div>

                <div style="margin-bottom:25px;border:1px solid #9e9fae;padding:10px;line-height:25px;">
                    <div class="a3_1">
                        <p>온리원디버 유의사항</p>
                    </div>
                    <p> 1. 디버 로그인 : 온리원셀링 ID, 온리원셀링 PW로 로그인하면 됩니다.<br/>
                        2. 디버설치 사양 : 온리원디버는 브라우저 크롬에서 다운로드가 잘 되며, 컴퓨터 사양은 윈도우10 이상, 64비트 지원될 때 정상 작동합니다.<br/>
                        3. M/S 엑셀 설치 : 수집된 디비는 M/S 엑셀에 저장됩니다.<br/>
                        4. 익스플로어 설치 : 디버는 검색시 익스창을 통해 검색합니다. <br/>
                        <br/>
                        [디버 미작동시 확인사항]<br/>
                        1. 디버가 최신 버전인지 확인합니다(공지사항)<br/>
                        2. 인터넷 속도 및 핫스팟 데이타를 확인합니다.<br/>
                        3. 익스 네이버 지도 검색이 되는지 확인합니다.<br/>
                        (가끔 익스 네이버 지도 자체가 안될 때가 있음)<br/>
                        4. 컴퓨터가 낮은 단계 CPU/저전력모델인지 확인합니다.
                    </p>
                </div>
            </div>
        </div>
        <div class="pay" style="">
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
                <input type="hidden" name="allat_pmember_id" value="<?php echo $_SESSION[one_member_id];?>" size="19" maxlength=20>
                <!--상품코드-->
                <input type="hidden" name="allat_product_cd" id="allat_product_cd" value="특별단회결제" size="19" maxlength=1000>
                <!--상품명-->
                <input type="hidden" name="allat_product_nm" id="allat_product_nm" value="특별단회결제" size="19" maxlength=1000>
                <!--결제자성명-->
                <input type="hidden" name="allat_buyer_nm" value="<?php echo $data['mem_name'];?>" size="19" maxlength=20>
                <!--수취인성명-->
                <input type="hidden" name="allat_recp_nm" value="<?php echo $data['mem_name'];?>" size="19" maxlength=20>
                <!--수취인주소-->
                <input type="hidden" name="allat_recp_addr" id="allat_recp_addr" value="<?php echo $data['mem_add1'];?>" size="19" maxlength=120>
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
                                        <input type="checkbox" id = "pay_cb"><span style="font-size: 18px;font-weight: 800;font-family: 'notokr', serif">특별단회결제</span>
                                    </div>
                                    <div style="padding-top: 30px">
                                        <div style="display: flex;padding: 10px">
                                            <div><span style="font-size: 16px;font-weight: 600;font-family: 'notokr', serif">상품명</span></div>
                                            <div><input type="text" name="item_name" id="item_name" style="border-radius: 5px;line-height: 20px;text-align: center;margin-left: 14px" readonly></div>
                                        </div>
                                    </div>
                                    <div style="padding-bottom: 15px">
                                        <div style="display: flex;padding: 10px">
                                            <div><span style="font-size: 16px;font-weight: 600;font-family: 'notokr', serif">결제금액</span></div>
                                            <div><input type="number" name="item_price" id="item_price" style="border-radius: 5px;line-height: 20px;text-align: right;" placeholder="원" readonly></div>
                                        </div>
                                    </div>
                                    <?if(!$_SESSION[one_member_id]){?>
                                        <div class="a8">
                                            <a href="javascript:void(0)" onclick="alert('로그인후 이용이 가능합니다.');">
                                                <img src="/images/sub_02_btn_23.jpg" style="width: 80%"/>
                                            </a>
                                        </div>
                                    <?}else {?>
                                        <div class="a8">
                                            <a href="javascript:void(0)" onclick="pay(document.pay_form)">
                                                <img src="/images/sub_02_btn_23.jpg" style="width: 80%"/>
                                            </a>
                                        </div>
                                    <?}?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                    . 해지신청을 하지 않고 탈퇴를 해도 결제는 유지됩니다. 탈퇴를 하시기 전 반드시 해지신청을 하시길 바랍니다.</span><br><br>
                <h4><b> [이월안내]</b></h4>
                ∙ 본 서비스는 통신사처럼 종량제로서 해당 월에 제공된 데이터를 사용하지 않으면 해당 월에 소멸되고 익월로 이월되지 않습니다.<br><br>
                <h4><b>   [환불안내]</b></h4>
                ∙ 전자상거래 및 소비자보호원의 표준약관 환불 규정에 따라 구매 후 본 소프트웨어를 이용했다면 솔루션 자체의 문제 등의 하자가 있는 경우를 제외하고 환불이 불가능합니다.<br>                 
                ∙ 구매 후 7일이 지나지 않았고 사용하지 않았다면, 환불을 요청할 수 있습니다.<br>
                ∙ 단 환불시 수수료, 승인처리 등의 비용을 차감 후 환불 가능합니다.<br>
            </div>
        </div>
    </div>
</div>
<?
mysql_close();
include_once "_foot.php";
?>
</body>

