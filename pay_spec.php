<?
$path="./";
include_once "_head.php";
if($member_1[mem_id] == "") {
    echo "<script>location.history(-1);</script>";
    exit;
}
$sql="select * from Gn_Member where mem_id='$member_1[mem_id]' ";
$res=mysql_query($sql)or die(mysql_error());
$data=mysql_fetch_array($res);
?>
<script>
    function cancel(){
        history.back(-1);
    }
    function pay(){
        $.ajax({
            type:"POST",
            url:"makeData.php",
            data:$('#pay_form').serialize(),
            dataType: 'html',
            success:function(data){
                $('#iam_info_modal').modal('show');
            },
            error: function(){
                alert('로딩 실패');
            }
        });
    }
    function form_save() {
        if($('#mem_id').val() == "") {
            alert('분양자아이디를 입력해주세요.');
            $('#mem_id').focus();
        }else if($('#sub_domain').val() == "") {
            alert('도메인을 입력해주세요.');
            $('#sub_domain').focus();
        }else {
            $('#main_domain').val($('#sub_domain').val());
            var data = $('#dForm').serialize();
            $.ajax({
                type:"POST",
                url:"/admin/ajax/service_Iam_save.php",
                data:data,
                dataType: 'html',
                success:function(data){
                    location.href = "/iam";
                },
                error: function(){
                    alert('로딩 실패');
                }
            });
        }
    }
</script>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<script src="/admin/bootstrap/js/bootstrap.js"></script>
<script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
<form name="pay_form" id="pay_form" method="post" action="/pay_end_allat.php"> <!--승인요청 및 결과수신페이지 지정 //-->
    <!--주문정보암호화필드-->
    <input type=hidden name="allat_enc_data" value=''>
    <!--상점 ID-->
    <input type="hidden" name="allat_shop_id" id="allat_shop_id" value="welcome101" size="19" maxlength=20>
    <!--주문번호-->
    <input type="hidden" name="allat_order_no" id="allat_order_no" value="<?=$_POST[allat_order_no]?>" size="19" maxlength=70>
    <!--인증정보수신URL-->
    <input type="hidden" name="shop_receive_url" id="shop_receive_url" value="http://<?php echo $_SERVER['SERVER_NAME'];?>/allat/pay/allat_receive.php?mid=<?php echo $mid;?>" size="19">
    <!--승인금액-->
    <input type="hidden" name="allat_amt" id="allat_amt" value="<?=$_POST[price]?>" size="19" maxlength=10>
    <!--회원ID-->
    <input type="hidden" name="allat_pmember_id" value="<?php echo $_SESSION[one_member_id];?>" size="19" maxlength=20>
    <!--상품코드-->
    <input type="hidden" name="allat_product_cd" id="allat_product_cd" value="전문가-구축" size="19" maxlength=1000>
    <!--상품명-->
    <input type="hidden" name="allat_product_nm" id="allat_product_nm" value="전문가-구축" size="19" maxlength=1000>
    <!--결제자성명-->
    <input type="hidden" name="allat_buyer_nm" value="<?php echo $data['mem_name'];?>" size="19" maxlength=20>
    <!--수취인성명-->
    <input type="hidden" name="allat_recp_nm" value="<?php echo $data['mem_name'];?>" size="19" maxlength=20>
    <!--수취인주소-->
    <input type="hidden" name="allat_recp_addr" id="allat_recp_addr" value="<?php echo $data['mem_add1'];?>" size="19" maxlength=120>
    <!--아이엠결제타입-->
    <!--input type=hidden name="iam_pay_type" id="iam_pay_type" value='<?=$_POST[iam_pay_type]?>'-->
    <!--아이엠카드갯수-->
    <input type=hidden name="iam_card_cnt" id="iam_card_cnt" value='<?=$_POST[iam_card_cnt]?>'>
    <!--아이엠공유갯수-->
    <input type=hidden name="iam_share_cnt" id="iam_share_cnt" value='<?=$_POST[iam_share_cnt]?>'>
    <!--결제타입-->
    <input type=hidden name="payMethod" id="payMethod" value='BANK'>
    <!--아이엠회원승인수-->
    <input type=hidden name="member_cnt" id="member_cnt" value='<?=$_POST[member_cnt]?>'>
    <input type=hidden name="month_cnt" id="month_cnt" value='<?=$_POST[month_cnt]?>'>
    <input type="hidden" name="member_type" id="member_type" value = "<?=$_POST[member_type]?>"/>

    <div class="big_main">
        <div class="big_1">
            <div class="m_div" style="width:100%">
                <div class="left_sub_menu">
                    <a href="./">홈</a> >
                    <a href="pay_return.php">결제결과</a>
                </div>
                <div class="right_sub_menu">&nbsp;</div>
                <p style="clear:both;"></p>
            </div>
        </div>
        <div class="m_div" style="width:100%">
            <div><img src="images/sub_02_visual_03.jpg" style="width:100%"/></div>
            <div class="pay">
                <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="2" style="font-size:16px;text-align: center">
                            [무통장입금 안내]
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center;">
                            <h3>스텐다드차타드은행 617-20-109431(구,SC제일은행)</h3>
                            <h3>온리원연구소</h3>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            결제안내 하단에도 통장번호가 있습니다.<br>입금후 카톡상담에 남겨주세요.^^
                        </td>
                    </tr>
                    <tr>
                        <td>지불수단</td>
                        <td>무통장 입금</td>
                    </tr>
                    <tr>
                        <td>주문번호</td>
                        <td><?=$_POST[allat_order_no]?></td>
                    </tr>
                    <tr>
                        <td>구매자명</td>
                        <td><?=$member_1[mem_name]?></td>
                    </tr>
                    <tr class="price">
                        <td >입금금액</td>
                        <td class="price">
                            <?=number_format($_POST[price])?> 원
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center;">
                            <input type="button" value="확인" onclick="pay();"  />
                            <input type="button" value="취소" onclick="cancel();"  />
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</form>
<!-- 아이엠 구축 팝업 -->
<div id="iam_info_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="width: 100%;max-width:768px;">
        <!-- Modal content-->
        <div class="modal-content" style="">
            <div class="modal-header" style="border:none;background-color: rgb(130,199,54);border-top-right-radius: 5px;border-top-left-radius: 5px;">
                <div>
                    <button type="button" class="close" data-dismiss="modal" style="opacity: 2">
                        <img src = "/iam/img/main/close_white.png" style="width:20px">
                    </button>
                </div>
                <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">독립아이엠 구축 정보</div>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;text-align: center;width:100%">
                    <form method="post" id="dForm" name="dForm" action="/admin/ajax/service_Iam_save.php"  enctype="multipart/form-data">
                        <input type="hidden" name="mode" value="creat"/>
                        <input type="hidden" name="main_domain" id="main_domain" value="" >
                        <table class="table table-bordered">
                            <colgroup>
                                <col width="20%">
                                <col width="80%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="bold"  colspan="2" style = "text-align: left;">
                                        ★스페셜 상품 결제하신 분만 입력해주세요.<br>
                                        1. 아이디는 반드시 가입한 아이디여야 합니다.<br>
                                        2. 서브도메인은 아래 OOOOO에 들어갈 영문(소문자)를 입력하세요  http://OOOOOO.kiam.kr<br>
                                        3. 준비된 정보만 입력하셔도 됩니다.
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>분양자아이디</th>
                                    <td > <input type="text" style="width:100%;" name="mem_id" id="mem_id" value="<?=$data['mem_id']?>" >  </td>
                                </tr>
                                <tr>
                                    <th>분양자이름</th>
                                    <td >  <input type="text" style="width:100%;" name="mem_name" id="mem_name" value="<?=$data['mem_name']?>" >  </td>
                                </tr>
                                <tr>
                                    <th>업체대표이름</th>
                                    <td> <input type="text" style="width:100%;" name="owner_name" id="owner_name" value="<?=$data['mem_name']?>" > </td>
                                </tr>
                                <tr>
                                    <th>관리자이름</th>
                                    <td> <input type="text" style="width:100%;" name="manager_name" id="manager_name" value="<?=$data['mem_name']?>" > </td>
                                </tr>
                                <tr>
                                    <th>업체이름</th>
                                    <td> <input type="text" style="width:100%;" name="company_name" id="company_name" value="" ></td>
                                </tr>
                                <tr>
                                    <th>사업자번호</th>
                                    <td> <input type="text" style="width:100%;" name="business_number" id="business_number" value="" ></td>
                                </tr>
                                <tr>
                                    <th>통신판매번호</th>
                                    <td><input type="text" style="width:100%;" name="communications_vendors" id="communications_vendors" value="" >  </td>
                                </tr>
                                <tr>
                                    <th>업체주소</th>
                                    <td><input type="text" style="width:100%;" name="address" id="address" value="">  </td>
                                </tr>
                                <tr>
                                    <th>정보책임자</th>
                                    <td> <input type="text" style="width:100%;" name="privacy" id="privacy" value="" > </td>
                                </tr>
                                <tr>
                                    <th>팩스번호</th>
                                    <td> <input type="text" style="width:100%;" name="fax" id="fax" value="" > </td>
                                </tr>
                                <tr>
                                    <th>대표전화번호</th>
                                    <td> <input type="text" style="width:100%;" name="owner_cell" id="owner_cell" value="" >  </td>
                                </tr>
                                <tr>
                                    <th>관리자폰번호</th>
                                    <td> <input type="text" style="width:100%;" name="manager_tel" id="manager_tel" value="" > </td>
                                </tr>
                                <tr>
                                    <th>이메일</th>
                                    <td> <input type="text" style="width:100%;" name="email" id="email" value="" >  </td>
                                </tr>
                                <tr>
                                    <th>메인도메인</th>
                                    <td> <input type="text" style="width:100%;" name="sub_domain" id="sub_domain" value="" >  </td>
                                </tr>
                                <!--tr>
                                    <th>Kakao API KEY</th>
                                    <td> <input type="text" style="width:100%;" name="kakao_api_key" id="kakao_api_key" value="" placeholder="">  </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td style="text-align: left">
                                        <input type="button" value="만드는 방법 참조(클릭)" onclick="window.open('https://tinyurl.com/yckm38fn');">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kakao Link</th>
                                    <td> <input type="text" style="width:100%;" name="kakao" id="kakao" value="" placeholder="카톡 1:1 오픈 채팅방주소">  </td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td style="text-align: left">
                                        <input type="button" value="만드는 방법 참조(클릭)" onclick="window.open('https://tinyurl.com/2p97jmr6');">
                                    </td>
                                </tr-->
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            <div class="modal-footer" style="padding:0px;display:flex">
                <button type="button" class="btn btn-active btn-center" onclick="form_save()">제출하기</button>
            </div>
        </div>
    </div>
</div>
<?
include_once "_foot.php";
?>
