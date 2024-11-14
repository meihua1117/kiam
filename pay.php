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
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
echo "test12<br>";
$date = date("Y-m-d H:i:s");
$sql="select * from Gn_Member where mem_id='{$_SESSION['one_member_id']}' ";
$resul=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($resul);
// 이미 진행중인 결제가 있는지 확인
$mid = date("YmdHis").rand(10,99);

$show_iam_info_status = "N";
$query = "select count(no) from tjd_pay_result where buyer_id='{$member_1['mem_id']}' and
          ((member_type like '%standard%' or member_type like '%professional%' or member_type like '%enterprise%') or 
          (((iam_pay_type = '' or iam_pay_type = '0' or iam_pay_type = '전문가') and member_type != '포인트충전')) and end_status = 'Y'";
echo $query;
$res = mysqli_query($self_con,$query);
$pay_row = mysqli_fetch_array($res);
echo "test25<br>";
$query = "select count(idx) from Gn_Iam_Service where mem_id='{$member_1['mem_id']}'";
$res = mysqli_query($self_con,$query);
$iam_service_row = mysqli_fetch_array($res);
if($iam_service_row[0] == 0 && $pay_row[0] > 0)
    $show_iam_info_status = "Y";
    echo "test32<br>";
?>

<style type="text/css">
    input {
         line-height: normal!important; 
    }
    .head_right a{
        font-size: 13px!important;
        font-family: "Nanum Gothic"!important;
    }
    .nav-tabs>.pay_tab.active>a, .nav-tabs>.pay_tab.active>a:focus, .nav-tabs>.pay_tab.active>a:hover {
        color: #000!important;
        background-color: rgb(146, 208, 80)!important;
        border-radius: 0px!important;
    }
    #ajax-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:9000;text-align:center;display:none;background-color: #fff;opacity: 0.8;}
    #ajax-loading img{position:absolute;top:50%;left:50%;width:120px;height:120px;margin:-60px 0 0 -60px;}
    .exp .table-wrap .view_table tbody tr td {
        font-size:12px;
        text-align:center;
    }
    .exp .table-wrap .view_table tbody tr td:first-child{text-align:left}
    .exp .table-wrap .view_table thead tr th {
        font-size:12px;
        text-align:center;
    }
    .exp .table-wrap .view_table thead tr th:first-child{text-align:left}
</style>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<script src="admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<?
if($platform == "mobile"){
    echo "<script type='text/javascript' charset='euc-kr' src='https://tx.allatpay.com/common/AllatPayM.js'></script>";
}else{
    echo "<script language=JavaScript charset='euc-kr' src='https://tx.allatpay.com/common/NonAllatPayREPlus.js'></script>";
    echo "<script language=JavaScript charset='euc-kr' src='https://tx.allatpay.com/common/NonAllatPayRE.js'></script>";
}
?>
<div class="big_main pay-wrap" style = "height : 100%">
    <div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
    <div class="m_div" style="background:white">
        <div style="text-align:center;">
            <!--img src="images/sub_02_visual_03.jpg"  style="width:100%"/-->
            <label style="color:#99cc00;font-size:18px;margin-top:20px;margin-bottom:10px">결제안내</label>
        </div>
        <div style="border:1px solid #ddd;padding:10px;text-align: right;">
            <a href="pay_sprg.php" style="font-weight: 900;">특별정기결제</a>
            <a href="pay_spoc.php" style="margin-left:10px;font-weight: 900;">특별단회결제</a>
        </div>
        <!--div style= "display:flex;border:1px solid #ddd;padding:10px;justify-content: space-between;" onclick="activeTab(1)">
            <div style="margin-top:10px;margin-left:10px">
                <label style="font-size:16px;font-weight:bold;">IAM 무료이용하기</label>
                <br>
                <label style="font-size:10px;">회원가입하면 무료이용이 가능합니다.나의 다양한 이야기를 담아보세요.</label>
                <br><br>
                <label style="font-size:10px;">서비스 등급을 자세히 알고싶으면 클릭하세요.</label>
            </div>
            <div style="margin:auto 10px">
                <img src = "/iam/img/menu/icon_pay_arrow.png" style="width:30px">
            </div>
        </div-->
        <section id = "pay_menu" style="background: white">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item exp-tab pay_tab " style = "width :33%" onclick="activeTab(1)">
                    <a class="nav-link" id="exp-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="exp" aria-selected="true" >서비스 설명</a>
                </li>
                <li class="nav-item cost-tab pay_tab active" style = "width :33%" onclick="activeTab(2)">
                    <a class="nav-link" id="pay-tab" data-toggle="tab" href="#story" role="tab" aria-controls="cost" aria-selected="false" >가격정보</a>
                </li>
                <li class="nav-item cancel-tab pay_tab" style = "width :34%" onclick="activeTab(3)">
                    <a class="nav-link" id="cancel-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="cancel" aria-selected="false" >취소,환불규정</a>
                </li>
            </ul>
        </section>
        <div class = "exp" style="background : white;display: none">
        <?
            $pay_row = array();
            $sql = "select * from payment_info order by idx";
            $res = mysqli_query($self_con,$sql);
            while($row = mysqli_fetch_array($res)){
                array_push($pay_row,$row);
            }
        ?>
            <div class="table-wrap"> 
                <div class="table-type">
                    <table class="view_table">
                        <colgroup>
                            <col width="28%">
                            <col width="18%">
                            <col width="18%">
                            <col width="18%">
                            <col width="18%">
                        </colgroup>
                        <thead>
                            <tr style="height: 40px;background-color: #f4f4f4;">
                                <th></th>
                                <?foreach($pay_row as $row){?>
                                    <th><?=$row['kind']?></th>
                                <?}?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>가격</td>
                                <?foreach($pay_row as $row){?>
                                    <td><?=number_format($row['price'])?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td  style="height: 40px;background-color: #f4f4f4;" colspan="6">IAM카드</td>
                            </tr>
                            <tr>
                                <td colspan="6">[마이플랫폼]</td>
                            </tr>
                            <tr>
                                <td>독립서브도메인 구축</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['domain'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>메인로고, 푸터로고</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['logo'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>플랫폼 회원승인수</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=number_format($row['mem_cnt'])?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>마케팅통합관리자기능</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['marketing'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>오토회원생성기능</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['auto_mem'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>단체콜백전송기능</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['callback'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>전체회원스텝문자기능</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['step'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>관리자공지기능</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['alert'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td colspan="6">[카드생성수]</td>
                            </tr>
                            <tr>
                                <td>포토+프로필+콘텐츠</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=number_format($row['card_cnt'])?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td colspan="6">[콘텐츠생성]</td>
                            </tr>
                            <tr>
                                <td>한개/다수/자동/상품 등록</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['cont_make'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td colspan="6">[공유기능]</td>
                            </tr>
                            <tr>
                                <td>IAM카드별 공유</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['card_share'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>콘텐츠별 공유</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['cont_share'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td colspan="6">[연락처기능]</td>
                            </tr>
                            <tr>
                                <td>폰주소록 관리</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['phone_addr_list'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td colspan="6">[AI카드생성기능]</td>
                            </tr>
                            <tr>
                                <td>AI자동생성</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['ai_auto']?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>오토데이트</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['autodate']?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td colspan="6">[회원전송기능]</td>
                            </tr>
                            <tr>
                                <td>카드전송하기</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['card_send']?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>콘텐츠전송하기</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['cont_send']?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td colspan="6">[광고카드]</td>
                            </tr>
                            <tr>
                                <td>광고카드보기</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['banner_view'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>광고카드만들기</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['banner_make'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td colspan="6">[커넥팅기능]</td>
                            </tr>
                            <tr>
                                <td>프렌즈</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['friends_connecting'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td colspan="6">[그룹카드기능]</td>
                            </tr>
                            <tr>
                                <td>그룹페이지참여</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['group_join'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>그룹페이지개설</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['group_create'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td style="height: 40px;background-color: #f4f4f4;" colspan="6">IAM콜백</td>
                            </tr>
                            <tr>
                                <td>개인자동/선택콜백</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['iam_callback'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>단체콜백전송기능</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['callback'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td style="height: 40px;background-color: #f4f4f4;" colspan="6">IAM폰문자</td>
                            </tr>
                            <tr>
                                <td>폰연결수</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['phone_cnt'] < 0 ?"무제한" : number_format($row['phone_cnt'])?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>발송건수(+가능)</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=number_format($row['send_daily_cnt'])?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>대량엑셀발송</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['excel_send'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>수신거부 자동관리</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['recv_n'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>수신동의 자동관리</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['recv_y'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>수신불가번호 자동관리</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['recv_block'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>없는번호 자동관리</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['no_number'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>변경번호 자동관리</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['change_number'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>그룹별 발송</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['group_send'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>포토3장 발송</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['photo_send'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>수신처제한 자동관리</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['recv_limit'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>고객이름 치환관리</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['name_change'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>발송비율 자동조절</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['send_percent'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>예약발송</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['reserve_send'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td style="height: 40px;background-color: #f4f4f4;" colspan="6">IAM디버</td>
                            </tr>
                            <tr>
                                <td>휴대폰디비</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=number_format($row['phone_db'])?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>이메일디비</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=number_format($row['email_db'])?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>일반번호</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['normal_number'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>수집출처</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['recv_from'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>쇼핑 디비</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['shop_db'] < 0 ?"별도":$row['shop_db']?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td style="height: 40px;background-color: #f4f4f4;" colspan="6">IAM스탭</td>
                            </tr>
                            <tr>
                                <td>랜딩페이지</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['landing'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>고객신청창</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['request'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>스텝예약발송</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['reserve_step'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>데일리발송</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['send_daily'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>신청고객관리</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['new_mem'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>기존고객관리</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['old_mem'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td>발송예정내역관리</td>
                                <?foreach($pay_row as $row){?>
                                <td><?=$row['send_pre'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                            <tr>
                                <td style="font-size:12px">발송결과내역확인</td>
                                <?foreach($pay_row as $row){?>
                                <td  style="font-size:12px"><?=$row['send_result'] == "Y"?"&#x2705;":""?></td>
                                <?}?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="pay">
            <form name="pay_form" id="pay_form" method="post"> <!--승인요청 및 결과수신페이지 지정 //-->
                <input type="hidden" name="allat_encode_type" value="euc-kr">
                <input type="hidden" name="allat_app_scheme" value=''>
                <input type="hidden" name="allat_autoscreen_yn" value='Y'>
                <!--상점 ID-->
                <input type="hidden" name="allat_shop_id" id="allat_shop_id" value="welcome101" size="19" maxlength=20>
                <!--주문번호-->
                <input type="hidden" name="allat_order_no" id="allat_order_no" value="<?=$mid;?>" size="19" maxlength=70>
                <!--승인금액-->
                <input type="hidden" name="allat_amt" id="allat_amt" value="33000" size="19" maxlength=10>
                <!--회원ID-->
                <input type="hidden" name="allat_pmember_id" value="<?=$_SESSION['one_member_id'];?>" size="19" maxlength=20>
                <!--상품코드-->
                <input type="hidden" name="allat_product_cd" id="allat_product_cd" value="Selling-Best" size="19" maxlength=1000>
                <!--상품명-->
                <input type="hidden" name="allat_product_nm" id="allat_product_nm" value="베스트 상품" size="19" maxlength=1000>
                <!--결제자성명-->
                <input type="hidden" name="allat_buyer_nm" value="<?=$data['mem_name'];?>" size="19" maxlength=20>
                <!--수취인성명-->
                <input type="hidden" name="allat_recp_nm" value="<?=$data['mem_name'];?>" size="19" maxlength=20>
                <!--수취인주소-->
                <input type="hidden" name="allat_recp_addr" id="allat_recp_addr" value="<?=$data['mem_add1'];?>" size="19" maxlength=120>
                <!--인증정보수신URL-->
                <input type="hidden" name="shop_receive_url" id="shop_receive_url" value="https://<?php echo $_SERVER['SERVER_NAME'];?>/allat/pay/allat_receive.php?mid=<?=$mid;?>" size="19">
                <!--주문정보암호화필드-->
                <input type="hidden" name="allat_enc_data" value=''>
                <!--신용카드 결제 사용 여부-->
                <input type="hidden" name="allat_card_yn" value="" size="19" maxlength=1>
                <!--계좌이체 결제 사용 여부-->
                <input type="hidden" name="allat_bank_yn" value="N" size="19" maxlength=1>
                <!--무통장(가상계좌) 결제 사용 여부-->
                <input type="hidden" name="allat_vbank_yn" value="N" size="19" maxlength=1>
                <!--휴대폰 결제 사용 여부-->
                <input type="hidden" name="allat_hp_yn" value="" size="19" maxlength=1>
                <!--상품권 결제 사용 여부-->
                <input type="hidden" name="allat_ticket_yn" value="" size="19" maxlength=1>
                <!--무통장(가상계좌) 인증 Key-->
                <input type="hidden" name="allat_account_key" value="" size="19" maxlength=20>
                <!--과세여부-->
                <input type="hidden" name="allat_tax_yn" value="Y" size="19" maxlength=1>
                <!--할부 사용여부: 할부사용(Y), 할부 사용않함(N) - Default : Y-->
                <input type="hidden" name="allat_sell_yn" value="Y" size="19" maxlength=1>
                <!--일반/무이자 할부 사용여부: 일반(N), 무이자 할부(Y) - Default :N-->
                <input type="hidden" name="allat_zerofee_yn" value="Y" size="19" maxlength=1>
                <!--포인트 사용 여부-->
                <input type="hidden" name="allat_bonus_yn" value="N" size="19" maxlength=1>
                <!--현금 영수증 발급 여부-->
                <input type="hidden" name="allat_cash_yn" value="" size="19" maxlength=1>
                <!--결제 정보 수신 E-mail-->
                <input type="hidden" name="allat_email_addr" id="allat_email_addr"  size="19" maxlength=50  value="<?php echo $data['mem_email']?$data['mem_email']:"turbolight@daum.net";?>">
                <!--테스트 여부 테스트(Y),서비스(N) - Default : N 테스트 결제는 실결제가 나지 않으며 테스트 성공시 결과값은 "0001" 리턴-->
                <input type="hidden" name="allat_test_yn" value="N" size="19" maxlength=1>
                <!--상품 실물 여부-->
                <input type="hidden" name="allat_real_yn" value="N" size="19" maxlength=1>
                <!--카드 에스크로 적용여부-->
                <input type="hidden" name="allat_cardes_yn" value="" size="19" maxlength=1>
                <!--계좌이체 에스크로 적용여부-->
                <input type="hidden" name="allat_bankes_yn" value="" size="19" maxlength=1>
                <!--무통장(가상계좌) 에스크로 적용여부-->
                <input type="hidden" name="allat_vbankes_yn" value="" size="19" maxlength=1>
                <!--휴대폰 에스크로 적용여부-->
                <input type="hidden" name="allat_hpes_yn" value="" size="19" maxlength=1>
                <!--상품권 에스크로 적용여부-->
                <input type="hidden" name="allat_ticketes_yn" value="" size="19" maxlength=1>
                <!--주민번호-->
                <input type="hidden" name="allat_registry_no" value="" size="19" maxlength=13>
                <!--KB복합결제 적용여부-->
                <input type="hidden" name="allat_kbcon_point_yn" value="" size="19" maxlength=1>
                <!--제공기간-->
                <input type="hidden" name="allat_provide_date" value="" size="19" maxlength=25>
                <!--컨텐츠 상품의 제공기간 : YYYY.MM.DD ~ YYYY.MM.DD -->
                <input type="hidden" name="allat_gender" value="" size="19" maxlength=1>
                <!--구매자 성별, 남자(M)/여자(F)생년월일-->
                <input type="hidden" name="allat_fix_type" value="" size="19" maxlength=3>
                <input type="hidden" name="allat_registry_no">
                <input type="hidden" name="allat_birth_ymd" value="" size="19" maxlength=8>
                <input type="hidden" name="member_type" id="member_type" value="베스트상품">
                <input type="hidden" name="db_cnt" id="db_cnt" value="0">
                <input type="hidden" name="email_cnt" id="email_cnt" value="0">
                <input type="hidden" name="shop_cnt" id="shop_cnt" value="0">
                <input type="hidden" name="onestep1" id="onestep1" value="OFF">
                <input type="hidden" name="onestep2" id="onestep2" value="OFF">
                <input type="hidden" name="add_phone" id="add_phone" value=0>
                <input type="hidden" name="payMethod" id = "payMethod"/>
                <input type="hidden" name="month_cnt" id="month_cnt" value="120"/>
                <!--input type="hidden" name="fujia_status" id="fujia_status" /-->
                <input type="hidden" name="mid" value="obmms20151" />
                <!--아이엠카드갯수(구축비결제때 이용)-->
                <input type="hidden" name="iam_card_cnt" id="iam_card_cnt">
                <!--아이엠공유갯수(구축비결제때 이용)-->
                <input type="hidden" name="iam_share_cnt" id="iam_share_cnt" value="0">
                 <!--문자발송건수-->
                 <input type="hidden" name="phone_cnt" id="phone_cnt" value="0">
                <!--아이엠회원 카드갯수(구축비결제때 이용)-->
                <input type="hidden" name="member_cnt" id="member_cnt">
                <input type="hidden" name="show_iam_info_status" id="show_iam_info_status" value='<?=$show_iam_info_status?>'>
                <div style="display:flex;">
                    <!--div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="pay_type" value="best">베스트(정기)
                        </label>
                    </div-->
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="pay_type" value="year">약정(리셀러)
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="pay_type" value="all" checked>전체(정기)
                        </label>
                    </div>
                    <div class="form-check-inline">
                        <label class="form-check-label">
                            <input type="radio" class="form-check-input" name="pay_type" value="dber">디버별도
                        </label>
                    </div>
                </div>
                <div class="table-wrap" style="margin-top:20px">
                    <div id="all_table" class="table-type">
                        <div class="row">
                            <!--div class="col-4" style="padding:0px 5px">
                                <table class="view_table" cellspacing="0" cellpadding="0" data-num="3">
                                    <thead>
                                        <tr>
                                            <th class="colored" colspan="3" style="text-align: center;">
                                                <input type="checkbox" name="service_type" class="service_type"  data-product = <?=$pay_row[1]['product_type']?>><?=$pay_row[1]['kind']?>
                                                <img src="/iam/img/menu/icon_pay_check.png" style="height:20px" data-toggle="collapse" data-target=".all-sta" class="">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:12px">광고없는 IAM카드와 자동화솔루션 이용</span>
                                            </td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM카드</span>
                                            </td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold" >아이엠카드수</td>
                                            <td colspan="2">
                                                <span name="iam_card_num" id="iam_card_num" data-num=<?=$pay_row[1]['card_cnt']?>><?=$pay_row[1]['card_cnt']?></span>개
                                            </td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold" >콘텐츠생성수</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold" >콘텐츠웹주소로등록</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold" >연락처기능</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold" >카드/콘텐츠별<br>공유하기</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold" >그룹카드 참여</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">AI카드생성</td>
                                            <td colspan="2">포인트구매</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">회원전송기능</td>
                                            <td colspan="2">포인트구매</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">직거래/쇼핑몰기능</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">독립도메인/독립로고</td>
                                            <td colspan="2">X</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM콜백</span>
                                            </td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">개인 자동/선택콜백</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">단체 콜백전송기능</td>
                                            <td colspan="2">X</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM문자</span>
                                            </td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">폰연결수</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">발송건수</td>
                                            <td colspan="2">
                                                <span name="send_count" id="send_count" data-num = <?=$pay_row[1]['send_daily_cnt']?> data-min = <?=$pay_row[1]['send_daily_cnt']?>><?=number_format($pay_row[1]['send_daily_cnt'])?></span>건
                                            </td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM디버</span>
                                            </td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">휴대폰디비</td>
                                            <td colspan="2" id="phone_db" data-num = <?=$pay_row[1]['phone_db']?>><?=number_format($pay_row[1]['phone_db'])?>건</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">이메일디비</td>
                                            <td colspan="2" id="email_db" data-num = <?=$pay_row[1]['email_db']?> data-min = <?=$pay_row[1]['email_db']?>><?=number_format($pay_row[1]['email_db'])?>건</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">일반번호</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM스탭</span>
                                            </td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">랜딩페이지</td>
                                            <td colspan="2">
                                                <span id="landing" data-value = "OFF">X</span>
                                            </td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">고객신청창</td>
                                            <td colspan="2">
                                                <span id="req" data-value = "OFF">X</span>
                                            </td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">스텝예약발송</td>
                                            <td colspan="2">
                                                <span id="step" data-value = "OFF">X</span>
                                            </td>
                                        </tr>
                                        <tr class="all-sta collapse">
                                            <td class="fw-bold">데일리발송</td>
                                            <td colspan="2">
                                                <span id="daily" data-value = "OFF">X</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3" style="position:relative">
                                                <span id="payment" class="payment" data-num = <?=$pay_row[1]['price']?>><?=number_format($pay_row[1]['price'])?>원</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div-->
                            <div class="col-6" style="padding:0px 5px">
                                <table class="view_table" cellspacing="0" cellpadding="0" data-num="3">
                                    <thead>
                                        <tr>
                                            <th class="colored active" colspan="3" style="text-align: center;">
                                                <input type="checkbox" name="service_type" class="service_type"  data-product = <?=$pay_row[1]['product_type']?> checked><?=$pay_row[1]['kind']?>
                                                <img src="/iam/img/menu/icon_pay_check.png" style="height:20px" data-toggle="collapse" data-target=".all-pro" class="">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:12px">IAM멀티플랫폼과 자동화솔루션 이용</span>
                                            </td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM카드</span>
                                            </td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold" >아이엠카드수</td>
                                            <td colspan="2">
                                                <span name="iam_card_num" id="iam_card_num" data-num=<?=$pay_row[1]['card_cnt']?>><?=$pay_row[1]['card_cnt']?></span>개
                                            </td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold" >콘텐츠생성수</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold" >콘텐츠웹주소로등록</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold" >연락처기능</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold" >카드/콘텐츠별<br>공유하기</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold" >그룹카드 참여</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">AI카드생성</td>
                                            <td colspan="2"><?=$pay_row[1]['ai_auto']?></td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">회원전송기능</td>
                                            <td colspan="2"><?=$pay_row[1]['card_send']?></td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">직거래/쇼핑몰기능</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">독립도메인/독립로고</td>
                                            <td colspan="2"><?=$pay_row[1]['domain'] == "Y"?"O":"X"?></td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM콜백</span>
                                            </td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">개인 자동/선택콜백</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">단체 콜백전송기능</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM문자</span>
                                            </td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">폰연결수</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">발송건수</td>
                                            <td>
                                                <span name="send_count" id="send_count" data-num = <?=$pay_row[1]['send_daily_cnt']?> data-min = <?=$pay_row[1]['send_daily_cnt']?>><?=number_format($pay_row[1]['send_daily_cnt'])?></span>건
                                            </td>
                                            <td>
                                                <input type="button" value="+" class="send_count_plus"/>
                                                <input type="button" value="-" class="send_count_minus"/>
                                            </td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM디버</span>
                                                <input type="button" value="+" id="plus_btn_db" class="db_plus"/>
                                                <input type="button" value="-" id="minus_btn_db" class="db_minus"/>
                                            </td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">휴대폰디비</td>
                                            <td colspan="2" id="phone_db" data-num = <?=$pay_row[1]['phone_db']?> data-min = <?=$pay_row[1]['phone_db']?>><?=number_format($pay_row[1]['phone_db'])?>건</td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">이메일디비</td>
                                            <td colspan="2" id="email_db" data-num = <?=$pay_row[1]['email_db']?> data-min = <?=$pay_row[1]['email_db']?>><?=number_format($pay_row[1]['email_db'])?>건</td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">일반번호</td>
                                            <td colspan="2"><?=$pay_row[1]['normal_number'] == "Y"?"무제한":"불가"?></td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM스탭</span>
                                            </td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">랜딩페이지</td>
                                            <td colspan="2">
                                                <span id="landing" data-value = "<?=$pay_row[1]['landing'] == "Y"?"ON":"OFF"?>"><?=$pay_row[1]['landing'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">고객신청창</td>
                                            <td colspan="2">
                                                <span id="req" data-value = "<?=$pay_row[1]['request'] == "Y"?"ON":"OFF"?>"><?=$pay_row[1]['request'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">스텝예약발송</td>
                                            <td colspan="2">
                                                <span id="step" data-value = "<?=$pay_row[1]['reserve_step'] == "Y"?"ON":"OFF"?>"><?=$pay_row[1]['reserve_step'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr class="all-pro collapse in">
                                            <td class="fw-bold">데일리발송</td>
                                            <td colspan="2">
                                                <span id="daily" data-value = "<?=$pay_row[1]['send_daily'] == "Y"?"ON":"OFF"?>"><?=$pay_row[1]['send_daily'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3" style="position:relative">
                                                <span id="payment" class="payment" data-num = <?=$pay_row[1]['price']?>><?=number_format($pay_row[1]['price'])?>원</span>
                                                <button type="button" id="iam_info_btn" class="btn btn-primary iam_info_btn" style="position:absolute;top:15px;right:10px;padding:2px;font-size:10px;" onclick="$('#iam_info_modal').modal('show');">구축정보입력</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-6" style="padding:0px 5px">
                                <table class="view_table" cellspacing="0" cellpadding="0" data-num="3">
                                    <thead>
                                        <tr>
                                            <th class="colored" colspan="3" style="text-align: center;">
                                                <input type="checkbox" name="service_type" class="service_type"  data-product = <?=$pay_row[2]['product_type']?>><?=$pay_row[2]['kind']?>
                                                <img src="/iam/img/menu/icon_pay_check.png" style="height:20px" data-toggle="collapse" data-target=".all-ent" class="">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:12px">IAM멀티플랫폼으로 비즈니스하기</span>
                                            </td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM카드</span>
                                            </td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold" >아이엠카드수</td>
                                            <td colspan="2">
                                                <span name="iam_card_num" id="iam_card_num" data-num=<?=$pay_row[2]['card_cnt']?>><?=$pay_row[2]['card_cnt']?></span>개
                                            </td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold" >콘텐츠생성수</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold" >콘텐츠웹주소로등록</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold" >연락처기능</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold" >카드/콘텐츠별<br>공유하기</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold" >그룹카드 참여</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">AI카드생성</td>
                                            <td colspan="2"><?=$pay_row[2]['ai_auto']?></td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">회원전송기능</td>
                                            <td colspan="2"><?=$pay_row[2]['card_send']?></td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">직거래/쇼핑몰기능</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">독립도메인/독립로고</td>
                                            <td colspan="2"><?=$pay_row[2]['domain'] == "Y"?"O":"X"?></td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM콜백</span>
                                            </td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">개인 자동/선택콜백</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">단체 콜백전송기능</td>
                                            <td colspan="2">X</td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM문자</span>
                                            </td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">폰연결수</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">발송건수</td>
                                            <td>
                                                <span name="send_count" id="send_count" data-num = <?=$pay_row[2]['send_daily_cnt']?> data-min = <?=$pay_row[2]['send_daily_cnt']?>><?=number_format($pay_row[2]['send_daily_cnt'])?></span>건
                                            </td>
                                            <!--td>
                                                <input type="button" value="+" class="send_count_plus"/>
                                                <input type="button" value="-" class="send_count_minus"/>
                                            </td-->
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM디버</span>
                                                <!--input type="button" value="+" id="plus_btn_db" class="db_plus"/>
                                                <input type="button" value="-" id="minus_btn_db" class="db_minus"/-->
                                            </td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">휴대폰디비</td>
                                            <td colspan="2" id="phone_db" data-num = <?=$pay_row[2]['phone_db']?> data-min = <?=$pay_row[2]['phone_db']?>><?=number_format($pay_row[2]['phone_db'])?>건</td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">이메일디비</td>
                                            <td colspan="2" id="email_db" data-num = <?=$pay_row[2]['email_db']?> data-min = <?=$pay_row[2]['email_db']?>><?=number_format($pay_row[2]['email_db'])?>건</td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">일반번호</td>
                                            <td colspan="2"><?=$pay_row[2]['normal_number'] == "Y"?"무제한":"불가"?></td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM스탭</span>
                                            </td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">랜딩페이지</td>
                                            <td colspan="2">
                                                <span id="landing" data-value = "<?=$pay_row[2]['landing'] == "Y"?"ON":"OFF"?>"><?=$pay_row[2]['landing'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">고객신청창</td>
                                            <td colspan="2">
                                                <span id="req" data-value = "<?=$pay_row[2]['request'] == "Y"?"ON":"OFF"?>"><?=$pay_row[2]['request'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">스텝예약발송</td>
                                            <td colspan="2">
                                                <span id="step" data-value = "<?=$pay_row[2]['reserve_step'] == "Y"?"ON":"OFF"?>"><?=$pay_row[2]['reserve_step'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr class="all-ent collapse">
                                            <td class="fw-bold">데일리발송</td>
                                            <td colspan="2">
                                                <span id="daily" data-value = "<?=$pay_row[2]['send_daily'] == "Y"?"ON":"OFF"?>"><?=$pay_row[2]['send_daily'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3" style="position:relative">
                                                <span id="payment" class="payment" data-num = <?=$pay_row[2]['price']?>><?=number_format($pay_row[2]['price'])?>원</span>
                                                <button type="button" id="iam_info_btn" class="btn btn-primary iam_info_btn" style="position:absolute;top:15px;right:10px;padding:2px;font-size:10px;" onclick="$('#iam_info_modal').modal('show');">구축정보입력</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="year_table" class="table-type" hidden>
                        <div class="row">
                            <div class="col-12" style="padding-right:0px">
                                <table class="view_table" cellspacing="0" cellpadding="0" data-num="3">
                                    <thead>
                                        <tr>
                                            <th class="colored active" colspan="3" style="text-align: center;">
                                                <?=$pay_row[3]['kind']?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="colored active fw-bold" colspan="3">
                                                <span style="font-size:12px">IAM멀티플랫폼으로 비즈니스하기</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored active fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM카드</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" >아이엠카드수</td>
                                            <td colspan="2">
                                                <span name="iam_card_num" id="iam_card_num" data-num=<?=$pay_row[3]['card_cnt']?>><?=$pay_row[3]['card_cnt']?></span>개
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" >콘텐츠생성수</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" >콘텐츠웹주소로등록</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" >연락처기능</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" >카드/콘텐츠별<br>공유하기</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" >그룹카드 참여</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">AI카드생성</td>
                                            <td colspan="2"><?=$pay_row[3]['ai_auto']?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">회원전송기능</td>
                                            <td colspan="2"><?=$pay_row[3]['card_send']?></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">직거래/쇼핑몰기능</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">독립도메인/독립로고</td>
                                            <td colspan="2"><?=$pay_row[3]['domain'] == "Y"?"O":"X"?></td>
                                        </tr>
                                        <tr>
                                            <td class="colored active fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM콜백</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">개인 자동/선택콜백</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">단체 콜백전송기능</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr>
                                            <td class="colored active fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM문자</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">폰연결수</td>
                                            <td colspan="2">무제한</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">발송건수</td>
                                            <td>
                                                <span name="send_count" id="send_count" data-num = <?=$pay_row[3]['send_daily_cnt']?> data-min = <?=$pay_row[3]['send_daily_cnt']?>><?=number_format($pay_row[3]['send_daily_cnt'])?></span>건
                                            </td>
                                            <td>
                                                <input type="button" value="+" class="send_count_plus"/>
                                                <input type="button" value="-" class="send_count_minus"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored active fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM디버</span>
                                                <input type="button" value="+" id="plus_btn_db" class="db_plus"/>
                                                <input type="button" value="-" id="minus_btn_db" class="db_minus"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">휴대폰디비</td>
                                            <td colspan="2" id="phone_db" data-num = <?=$pay_row[3]['phone_db']?> data-min = <?=$pay_row[3]['phone_db']?>><?=number_format($pay_row[3]['phone_db'])?>건</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">이메일디비</td>
                                            <td colspan="2" id="email_db" data-num = <?=$pay_row[3]['email_db']?> data-min = <?=$pay_row[3]['email_db']?>><?=number_format($pay_row[3]['email_db'])?>건</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">일반번호</td>
                                            <td colspan="2"><?=$pay_row[3]['normal_number'] == "Y"?"무제한":"불가"?></td>
                                        </tr>
                                        <tr>
                                            <td class="colored active fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM스탭</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">랜딩페이지</td>
                                            <td colspan="2">
                                                <span id="landing" data-value = "<?=$pay_row[3]['landing'] == "Y"?"ON":"OFF"?>"><?=$pay_row[3]['landing'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">고객신청창</td>
                                            <td colspan="2">
                                                <span id="req" data-value = "<?=$pay_row[3]['request'] == "Y"?"ON":"OFF"?>"><?=$pay_row[3]['request'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">스텝예약발송</td>
                                            <td colspan="2">
                                                <span id="step" data-value = "<?=$pay_row[3]['reserve_step'] == "Y"?"ON":"OFF"?>"><?=$pay_row[3]['reserve_step'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">데일리발송</td>
                                            <td colspan="2">
                                                <span id="daily" data-value = "<?=$pay_row[3]['send_daily'] == "Y"?"ON":"OFF"?>"><?=$pay_row[3]['send_daily'] == "Y"?"무제한":"불가"?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored active fw-bold" colspan="3" style="position:relative">
                                                <span id="payment" class="payment" data-num = <?=$pay_row[3]['price']?>><?=number_format($pay_row[3]['price'])?>원</span>
                                                <button type="button" id="iam_info_btn" class="btn btn-primary iam_info_btn" style="position:absolute;top:15px;right:10px;padding:2px;font-size:10px;" onclick="$('#iam_info_modal').modal('show');">구축정보입력</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="dber_table" class="table-type" hidden>
                        <div class="row">
                            <div class="col-12" >
                                <table class="view_table" cellspacing="0" cellpadding="0" data-num="3" style="font-size:14px">
                                    <thead>
                                        <tr>
                                            <th class="colored" colspan="3" style="text-align: center;">
                                                디비 별도 상품
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3">
                                                <span style="font-size:12px">공개된 디비수집으로 타겟 마케팅하기</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored active fw-bold" colspan="3">
                                                <span style="font-size:14px">IAM디버</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" >
                                                <input type="checkbox" name="phone_db_chk">휴대폰디비
                                            </td>
                                            <td>
                                                <span name="phone_db" id="phone_db" data-num="1000" data-min="1000">1,000</span>건
                                            </td>
                                            <td>
                                                <input type="button" value="+" class="phone_db_plus"/>
                                                <input type="button" value="-" class="phone_db_minus"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold" >
                                                <input type="checkbox" name="email_db_chk">이메일디비
                                            </td>
                                            <td><span name="email_db" id="email_db" data-num="10000"  data-min="10000">10,000</span>건</td>
                                            <td>
                                                <input type="button" value="+" class="email_db_plus"/>
                                                <input type="button" value="-" class="email_db_minus"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">
                                                <input type="checkbox" name="shop_db_chk">쇼핑디비
                                            </td>
                                            <td><span name="shop_db" id="shop_db" data-num="2000" data-min="2000">2,000</span>건</td>
                                            <td>
                                                <input type="button" value="+" class="shop_db_plus"/>
                                                <input type="button" value="-" class="shop_db_minus"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="colored fw-bold" colspan="3" style="position:relative">
                                                <span id="payment" class="payment" data-num = "0">0원</span>
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
                        <input type="radio" name="payment_type" id="payment_type_card" value="card" data-monthly="N"> 카드</span>
                        <input type="radio" name="payment_type" id="payment_type_cash" value="bank" > 무통장입금
                    </div>
                    <div class="a3_1 payment_2" style="display:none">
                        <input type="radio" name="payment_type" id="payment_type_month_card" value="month_card" checked> 카드정기결제
                    </div>
                </div>
                <div class="payment_2">
                    <div style="padding-bottom: 10px" id = "detail1"> &xcirc;  [카드결제]는 결제후 담장자 승인없이 사용가능, 미결제시 자동관리 가능하지만,<br>
                        <input type="radio" name="payment_type" id="payment_type_month" value="month"> [통장결제]</input>는 ARS콜수신, 이용승인, 잔고부족시 이용 중지되므로 가능한 [카드결제]를 사용하세요<br>
                    </div>
                </div>
                <div style="padding-bottom: 10px; display : none" id = "detail2"> 입금안내 : 스탠다드차타드은행 617-20-109431 온리원연구소(구,SC제일은행)<br>연결제는 월결제에 2개월치를 추가로 드립니다.<br></div>
            <?if(!$_SESSION['one_member_id']){?>
                <div class="a8"><a href="javascript:alert('로그인후 이용이 가능합니다.');"><img src="images/sub_02_btn_23.jpg" style="max-width: 100%"/></a></div>
            <?}else {?>
                <div class="a8"><a href="javascript:show_modal();"><img src="images/sub_02_btn_23.jpg" style="max-width: 100%"/></a></div>
            <?}?>
            </form>
        </div>
        
        <div class = "cancel" style="background : white;display: none">
            <div style="width:80%;font-weight: 700;font-size: 16px;padding: 20px;">
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
        <!-- 아이엠 구축 팝업 -->
        <div id="iam_info_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
            <div class="modal-dialog" style="width: 100%;max-width:768px;">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header" style="border:none;background-color: rgb(130,199,54);border-top-right-radius: 5px;border-top-left-radius: 5px;">
                        <div>
                            <button type="button" class="close" data-dismiss="modal" style="opacity: 2">
                                <img src = "/iam/img/main/close_white.png" style="width:20px">
                            </button>
                        </div>
                        <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">마이풀랫폼 구축 정보</div>
                    </div>
                    <div class="modal-body">
                        <div class="container" style="margin-top: 20px;text-align: center;width:100%">
                            <form method="post" id="dForm" name="dForm" action="/admin/ajax/service_Iam_save.php"  enctype="multipart/form-data">
                                <input type="hidden" name="mode" value="creat"/>
                                <input type="hidden" name="main_domain" id="main_domain" value="" >
                                <input type="hidden" name="iamcard_cnt" id="iamcard_cnt" value="1" >
                                <input type="hidden" name="my_card_cnt" id="my_card_cnt" value="20" >
                                <input type="hidden" name="mem_cnt" id="mem_cnt" value="0" >
                                <input type="hidden" name="status" id="iam_info_status" value="Y" >
                                <table class="table table-bordered">
                                    <colgroup>
                                        <col width="20%">
                                        <col width="80%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th class="bold"  colspan="2" style = "text-align: left;">
                                                ★마이플랫폼 구축안내<br>
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
                                            <th>서브도메인</th>
                                            <td> <input type="text" style="width:100%;" name="sub_domain" id="sub_domain" value="" placeholder="영문 소문자 10자 내외로 입력해주세요.">  </td>
                                        </tr>
                                        <!--tr>
                                            <-th>Kakao API KEY</th>
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
        <!-- 입금안내 팝업 -->
        <div id="detail_intro_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
            <div class="modal-dialog" style="width: 100%;max-width:768px;">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header" style="border:none;background-color: rgb(130,199,54);border-top-right-radius: 5px;border-top-left-radius: 5px;">
                        <div>
                            <button type="button" class="close" data-dismiss="modal" style="opacity: 2">
                                <img src = "/iam/img/main/close_white.png" style="width:20px">
                            </button>
                        </div>
                        <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">위약금 세부 안내 및 입금 안내</div>
                    </div>
                    <div class="modal-body">
                        <div class="container" style="margin-top: 20px;text-align: center;width:100%">
                            <table class="table table-bordered">
                                <colgroup>
                                    <col width="20%">
                                    <col width="80%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="bold"  colspan="2" style = "text-align: left;font-size: 14px;">
                                            ※ 위약금 종류와 근거자료<br><br>
                                            1. 등록비(80만원) : 리셀러로서 수당을 받을수 있는 등록비를 말한다. 약정하지 않는 리셀러는 등록비를 사전 납부하고 리셀러로 등록할수 있다. 그러므로 약정을 하고 나서 해지 하는 것은 등록비를 이미 납부한 경우 반환이 되지 않으므로 약정자는 해지할경우 등록비를 위약금으로 납부해야 한다. <br><br>
                                            2. 관리비(20만원) : 리셀러로 등록하게 되면 기존 추천자가 1-2개월간 플랫폼 설정, 이용, 교육, 대행등을 진행한다. 약정없는 리셀러의 경우 사전에 20만원을 납부하고 진행하지만 약정자는 납부하지 않고 시작하므로 약정후 해지시 이 비용을 위약금으로 납부한다. <br><br>
                                            3. 이용료(9.9만원) : 이용료는 9.9만원을 3.3만원에 지원하므로 매월 지원받은 만큼 위약금으로 납부해야 한다. <br><br>
                                            4. 포인트(100만원) : 리셀러에게 제공되는 초기 포인트 100만 포인트 중 사용한 액수를 위약금으로 환불한다. <br><br>
                                            단, 기간이 지남에 따라 약정의무를 수행하게 되므로 약정기간이 끝나갈때에 맞춰 위약금의 비율을 낮추게 된다.
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding:0px;display:flex">
                        <button type="button" class="btn btn-default btn-left" style="width:50%" onclick="pay_go(document.pay_form)">잘 확인했습니다</button>
                        <button type="button" class="btn btn-active btn-right" style="width:50%" onclick="show_table()">위약금 도표 보기</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- 위약금 도표 팝업 -->
        <div id="penalty_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
            <div class="modal-dialog" style="width: 100%;max-width:768px;">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header" style="border:none;background-color: rgb(130,199,54);border-top-right-radius: 5px;border-top-left-radius: 5px;">
                        <div>
                            <button type="button" class="close" data-dismiss="modal" style="opacity: 2">
                                <img src = "/iam/img/main/close_white.png" style="width:20px">
                            </button>
                        </div>
                        <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">위약금 항목과 기간별 위약금 정산 안내</div>
                    </div>
                    <div class="modal-body">
                        <div class="container" style="margin-top: 20px;text-align: center;width:100%">
                            <p>
                                ※ 3년 약정 결제 해지에 대한 개월별 위약금 안내입니다.
                            </p>
                            <table class="table table-bordered">
                                <colgroup>
                                    <col width="24%">
                                    <col width="24%">
                                    <col width="24%">
                                    <col width="24%">
                                    <col width="24%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="bold" style = "text-align: center;font-size: 14px;vertical-align: middle;background-color: #cbc5c5;">
                                            기간
                                        </th>
                                        <th class="bold" colspan="3" style = "text-align: center;vertical-align: middle;font-size: 14px;padding: 0px;">
                                            <table class="table table-bordered" style="margin: 0px;border: none;text-align: center;background-color: #cbc5c5;">
                                                <colgroup>
                                                    <col width="32.5%">
                                                    <col width="33%">
                                                    <col width="32.5%">
                                                </colgroup>
                                                <thead>
                                                    <tr>
                                                        <th colspan="3" style="text-align: center;">
                                                            위약금항목
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="border-bottom: none;">등록비</td>
                                                        <td style="border-bottom: none;">관리비</td>
                                                        <td style="border-bottom: none;">이용료</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </th>
                                        <th class="bold" style = "text-align: center;font-size: 14px;vertical-align: middle;background-color: #cbc5c5;">
                                            위약금
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?
                                    $sql_penalty = "select * from gn_penalty_list";
                                    $res_penalty = mysqli_query($self_con,$sql_penalty);
                                    while($row_penalty = mysqli_fetch_array($res_penalty)){
                                    ?>
                                    <tr>
                                        <td><?=$row_penalty['month']?>개월</td>
                                        <td><?=number_format($row_penalty['reg_money'])?></td>
                                        <td><?=number_format($row_penalty['manage_money'])?></td>
                                        <td><?=number_format($row_penalty['use_money'])?></td>
                                        <td><?=number_format($row_penalty['penalty_money'])?></td>
                                    </tr>
                                    <?}?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding:0px;display:flex">
                        <button type="button" class="btn btn-active btn-center" onclick="close_table()">잘 확인했습니다</button>
                    </div>
                </div>
            </div>
        </div>
       <?
       include_once "_foot.php";
       ?>
<script>
    $(function(){
        $(document).ajaxStart(function() {
            $("#ajax-loading").show();
        })
        .ajaxStop(function() {
            //$("#ajax-loading").delay(10).hide(1);
        });
    });

    function show_modal(){
        // var pay_modal_state = getCookie('pay_modal_state');
        var pay_type = $('input[name=pay_type]:checked').val();
        if(pay_type == "year"){
            $("#detail_intro_modal").modal('show');
        }
        else{
            pay_go(document.pay_form);
        }
    }

    function show_table(){
        $("#penalty_modal").modal('show');
    }

    function close_table(){
        $("#penalty_modal").modal('hide');
    }
    //결제고고
    function pay_go(frm){
        // var pay_modal_state = getCookie('pay_modal_state');
        // if(pay_modal_state == ''){
        //     setCookie("pay_modal_state","done",1,"");
            $("#detail_intro_modal").modal('hide');
        // }
        var product_type = "";
        var table = "";
        var month_cnt = 120;
        var member_cnt = 0;
        var yak = "OFF";
        if($('input[name=pay_type]:checked').val() == "all") {
            var service_type = $('input[name=service_type]:checked').val();
            if(service_type == "" || service_type == undefined){
                alert('상품종류를 선택해주세요.');
                return false;
            }
            product_type = $('input[name=service_type]:checked').data("product");
            table = $('input[name=service_type]:checked').parents(".view_table");
        }else if($('input[name=pay_type]:checked').val() == "year") {
            product_type = "year-professional";
            table = $("#year_table").children().find(".view_table");
            member_cnt = 2000;
            yak = "ON";
        }else{
            product_type = "dber";
            table = $("#dber_table").children().find(".view_table");
            month_cnt = 1;
            if($('input[name=phone_db_chk]').prop("checked") == false && 
                $('input[name=email_db_chk]').prop("checked") == false &&
                $('input[name=shop_db_chk]').prop("checked") == false){
                    alert('상품종류를 선택해주세요.');
                    return false;
                }
        }
        var payment = table.find("#payment");
        var price = payment.data("num") * 1;
        if(price < 0){
            location.href = '<?=$domainData['kakao']?>';
            return;
        }
        $('#allat_amt').val(price);
        $('#member_type').val(product_type);
        $('#allat_product_cd').val(product_type);
        $('#allat_product_nm').val(product_type);
        $("#db_cnt").val(table.find("#phone_db").data('num') * 1);
        $("#email_cnt").val(table.find("#email_db").data('num') * 1);
        $("#shop_cnt").val(0);
        if(product_type == "dber"){
            if($('input[name=phone_db_chk]').prop("checked") == true)
                $("#db_cnt").val(table.find("#phone_db").data('num') * 1);
            else
                $("#db_cnt").val(0);

            if($('input[name=email_db_chk]').prop("checked") == true)
                $("#email_cnt").val(table.find("#email_db").data('num') * 1);
            else
                $("#email_cnt").val(0);

            if($('input[name=shop_db_chk]').prop("checked") == true)
                $("#shop_cnt").val(table.find("#shop_db").data('num') * 1);
            else
                $("#shop_cnt").val(0);
        }
        $("#onestep1").val(table.find("#step").data('value'));
        $("#onestep2").val(yak);
        var card_cnt = table.find("span[id=iam_card_num]").data("num");
        $('#iam_card_cnt').val(card_cnt);
        var phone_cnt = table.find("span[id=send_count]").data("num");
        $('#phone_cnt').val(phone_cnt);
        $('#member_cnt').val(member_cnt);
        $('#mem_cnt').val(member_cnt);
        $('#month_cnt').val(month_cnt);
        if(confirm('결제시작하시겠습니까?')){
            if($('input[name=payment_type]:checked').val()=="bank") {//무통장결제 디버결제시 이용
                $("#payMethod").val("BANK");
                frm.target='_self';
                frm.action='pay_cash.php';
                frm.submit();
            }else if($('input[name=payment_type]:checked').val()=="month") {//통장정기결제
                $("#ajax-loading").show();
                $("#payMethod").val("MONTH");
                if($('#allat_recp_addr').val() == "") {
                    $.ajax({
                        type:"POST",
                        url:"/ajax/get_mem_address.php",
                        dataType:"json",
                        data:{mem_id:'<?=$_SESSION['iam_member_id']?>'},
                        success: function(data){
                            $('#allat_recp_addr').val(data.address);
                            if(product_type != "standard"){
                                var win = window.open("https://ap.hyosungcmsplus.co.kr/external/shorten/20230317ezgQgt9oPT", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=500,width=600,height=780")
                                $.ajax({
                                    type: "POST",
                                    url: "ajax/pay_month.php",
                                    data: $('#pay_form').serialize(),
                                    dataType: 'html',
                                    success: function (data) {
                                        $("#ajax-loading").delay(10).hide(1);
                                        if(product_type != "enterprise")
                                            $("#iam_info_modal").modal("show");
                                        else
                                            alert("결제가 완료되었습니다.");
                                        $("#iam_info_status").val("N");
                                    },
                                    error: function () {
                                        $("#ajax-loading").delay(10).hide(1);
                                        alert('로딩 실패');
                                    }
                                });
                            }else{
                                frm.target='_self';
                                frm.action='pay_month.php';
                                frm.submit();
                            }
                        },
                        error: function () {
                            alert('내 정보 수정에서 자택주소를 추가해주세요.');
                            location.href = "mypage.php?link=/pay";
                            return;
                        }
                    });
                }else {
                    if(product_type != "standard"){
                        var win = window.open("https://ap.hyosungcmsplus.co.kr/external/shorten/20230317ezgQgt9oPT", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=500,width=600,height=780")
                        $.ajax({
                            type: "POST",
                            url: "ajax/pay_month.php",
                            data: $('#pay_form').serialize(),
                            dataType: 'html',
                            success: function (data) {
                                $("#ajax-loading").delay(10).hide(1);
                                if(product_type != "enterprise")
                                    $("#iam_info_modal").modal("show");
                                else
                                    alert("결제가 완료되었습니다.");
                                $("#iam_info_status").val("N");
                            },
                            error: function () {
                                $("#ajax-loading").delay(10).hide(1);
                                alert('로딩 실패');
                            }
                        });
                    }else{
                        frm.target='_self';
                        frm.action='pay_month.php';
                        frm.submit();
                    }
                }
            }else if($('input[name=payment_type]:checked').val()=="month_card") {//카드정기결제
                $("#payMethod").val("MONTH_Card");
                if($('#allat_recp_addr').val() == "") {
                    $.ajax({
                        type:"POST",
                        url:"/ajax/get_mem_address.php",
                        dataType:"json",
                        data:{mem_id:'<?=$_SESSION['iam_member_id']?>'},
                        success: function(data){
                            $('#allat_recp_addr').val(data.address);
                            $('#allat_shop_id').val('bwelcome12');
                            $('#shop_receive_url').val('https://<?=$_SERVER['SERVER_NAME'];?>/allat/mp/allat_receive.php?mid=<?=$mid;?>');
                            $('#pay_form').attr("action","/allat/mp/allat_fix.php");
                            ftn_fix(document.pay_form);
                        },
                        error: function () {
                            alert('내 정보 수정에서 자택주소를 추가해주세요.');
                            location.href = "mypage.php?link=/pay";
                            return;
                        }
                    });
                }else {
                    $('#allat_shop_id').val('bwelcome12');
                    $('#shop_receive_url').val('https://<?php echo $_SERVER['SERVER_NAME'];?>/allat/mp/allat_receive.php?mid=<?php echo $mid;?>');
                    $('#pay_form').attr("action","/allat/mp/allat_fix.php");
                    ftn_fix(document.pay_form);
                }
            }else {//카드단회결제 디버결제시에 이용
                $("#payMethod").val("Card");
                if($('#allat_recp_addr').val() == "") {
                    $.ajax({
                        type:"POST",
                        url:"/ajax/get_mem_address.php",
                        dataType:"json",
                        data:{mem_id:'<?=$_SESSION['iam_member_id']?>'},
                        success: function(data){
                            $('#allat_recp_addr').val(data.address);
                            $('#allat_shop_id').val('welcome101');
                            $('#shop_receive_url').val('https://<?php echo $_SERVER['SERVER_NAME'];?>/allat/pay/allat_receive.php?mid=<?php echo $mid;?>');
                            cardType = 1;
                            ftn_approval(document.pay_form);
                        },
                        error: function () {
                            alert('내 정보 수정에서 자택주소를 추가해주세요.');
                            location.href = "mypage.php?link=/pay";
                            return;
                        }
                    });
                }else {
                    $('#allat_shop_id').val('welcome101');
                    $('#shop_receive_url').val('https://<?php echo $_SERVER['SERVER_NAME'];?>/allat/pay/allat_receive.php?mid=<?php echo $mid;?>');
                    cardType = 1;
                    ftn_approval(document.pay_form);
                }
            }
        }
    }
    // 결제페이지 호출
    function ftn_approval(dfm) {
        $('body,html').animate({
                scrollTop: 0
            }, 100
        );
        $.ajax({
            type:"POST",
            url:"makeData.php",
            data:$('#pay_form').serialize(),
            dataType: 'html',
            success:function(data){
                $("#ajax-loading").delay(10).hide(1);
                pay_form.acceptCharset = 'euc-kr';
                document.charset = 'euc-kr';
                var navCase = navigator.userAgent.toLocaleLowerCase();
                if(navCase.search("android") > -1) {
                    dfm.allat_app_scheme.value = navCase;
                    Allat_Mobile_Approval(dfm, 0, 0);
                }else {
                    dfm.allat_app_scheme.value = '';
                    AllatPay_Approval(dfm);
                    // 결제창 자동종료 체크 시작
                    AllatPay_Closechk_Start();
                }
            },
            error: function(){
              //alert('로딩 실패');
            }
        });
        
	}

	// 결과값 반환( receive 페이지에서 호출 )
	function result_submit(result_cd,result_msg,enc_data) {
        pay_form.acceptCharset = 'utf-8';
        document.charset = 'utf-8';
        var navCase = navigator.userAgent.toLocaleLowerCase();
        if(navCase.search("android") > -1) {
            Allat_Mobile_Close();
        }else {
            // 결제창 자동종료 체크 종료
            AllatPay_Closechk_End();
        }
        if( result_cd != '0000' ){//pay_test
            $("#ajax-loading").delay(10).hide(1);
            window.setTimeout(function(){alert(result_cd + " : " + result_msg);},1000);
            location.reload();
        } else {
            $("#ajax-loading").delay(10).hide(1);
            pay_form.allat_enc_data.value = enc_data;
            pay_form.action = "pay_end_allat.php";
            pay_form.method = "post";
            pay_form.target = "_self";
            pay_form.submit();
        }
	}
    function ftn_fix(dfm) {
        $('body,html').animate({
                scrollTop: 0
            }, 100
        );
        $.ajax({
            type: "POST",
            url: "makeData.php",
            data: $('#pay_form').serialize(),
            dataType: 'html',
            success: function (data) {
                console.log(data);
                $("#ajax-loading").delay(10).hide(1);
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
            if(pay_form.member_type.value == "standard") {
                pay_form.allat_enc_data.value = enc_data;
                pay_form.action = "/allat/mp/allat_fix.php";
                pay_form.method = "post";
                pay_form.target = "_self";
                pay_form.submit();
            }else{
                $.ajax({
                    type:"POST",
                    url:"/allat/mp/allat_fix_ajax.php",
                    data:$('#pay_form').serialize(),
                    dataType: 'html',
                    success:function(data){
                        $("#ajax-loading").delay(10).hide(1);
                        if(pay_form.member_type.value != "enterprise")
                            $('#iam_info_modal').modal('show');
                        else
                            alert("결제가 완료되었습니다.");
                    },
                    error: function (request, status, error) {
                        console.log('code: ' + request.status + "\n" + 'message: ' + request.responseText + "\n" + 'error: ' + error);
                    }
                });
            }
        }
        $("#ajax-loading").delay(10).hide(1);
    }
    function isMobile() {
        var userAgent = navigator.userAgent || navigator.vendor || window.opera;
        // Windows Phone must come first because its UA also contains "Android"
        if (/windows phone/i.test(userAgent)) {
            return true;
        }
        if (/android/i.test(userAgent)) {
            return true;
        }
        // iOS detection from: http://stackoverflow.com/a/9039885/177710
        if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
            return true;
        }
        return false;
    }
    function activeTable(){
        $('[name="service_type"]').each(function(){
            if( $(this).prop('checked') == true ){
                $(this).parents('.view_table').find('.colored').addClass('active');
            } else {
                $(this).parents('.view_table').find('.colored').removeClass("active");
            }
        });
    }
    function set_monthly_payment() {
        $('.payment_1').hide();
        $('.payment_2').show();
        $("#payment_type_month_card").prop("checked",true);
        $("#payment_type_month").prop("checked",false);    
    }
    function set_once_payment() {
        $('.payment_1').show();
        $('.payment_2').hide();
        $("#payment_type_card").prop("checked",true);
        $("#payment_type_cash").prop("checked",false);
    }
    $(function(){
        set_monthly_payment();
        if($("#show_iam_info_status").val() == "N"){
            $(".iam_info_btn").css("display","none");
        }
        if(!isMobile()){
            $(".all-sta").addClass("in");
            $(".all-pro").addClass("in");
            $(".all-ent").addClass("in");
            $(".year-sta").addClass("in");
        }
        $('input[name=pay_type]').on("change",function(){
            if($('input[name=pay_type]:checked').val() == "all") {
                $("#all_table").show();
                $("#year_table").hide();
                $("#dber_table").hide();
                $(".colored").removeClass("active");
                $('.service_type').each(function(){
                    $(this).prop('checked',false);
                });
                set_monthly_payment();
            }else if($('input[name=pay_type]:checked').val() == "year") {
                $("#all_table").hide();
                $("#year_table").show();
                $("#dber_table").hide();
                $(".colored").removeClass("active");
                $("#year_table").find('.view_table').find('.colored').addClass('active');
                set_monthly_payment();
            }else{
                $("#all_table").hide();
                $("#year_table").hide();
                $("#dber_table").show();
                $(".colored").removeClass("active");
                $("#dber_table").find('.view_table').find('.colored').addClass('active');
                set_once_payment();
            }
        });
        
        $('.service_type').on("click", function(){
            $('.service_type').each(function(){
                $(this).prop('checked',false);
            });
            $(this).prop('checked',true);
            activeTable();
        });
        $('.db_plus').on("click",function() {
            var db_phone = $(this).parents(".view_table").find("#phone_db");
            var phone_value = db_phone.data('num') * 1;
            phone_value += 1000;
            db_phone.data('num', phone_value);
            db_phone.html(comma(phone_value) + "건");

            var email_db = $(this).parents(".view_table").find("#email_db");
            var email_value = email_db.data('num') * 1;
            email_value += 2000;
            email_db.data('num', email_value);
            email_db.html(comma(email_value) + "건");

            var payment = $(this).parents(".view_table").find("span[id=payment]");
            var price = payment.data('num') * 1;
            price += 12000;
            payment.data('num',price);
            payment.html(comma(price) + "원");
        });

        $('.db_minus').on("click",function() {
            var db_phone = $(this).parents(".view_table").find("#phone_db");
            var phone_value = db_phone.data('num') * 1;
            var phone_min = db_phone.data('min') * 1;
            if(phone_value > phone_min) {
                phone_value -= 1000;
                db_phone.data('num', phone_value);
                db_phone.html(comma(phone_value) + "건");

                var email_db = $(this).parents(".view_table").find("#email_db");
                var email_value = email_db.data('num') * 1;
                email_value -= 2000;
                email_db.data('num', email_value);
                email_db.html(comma(email_value) + "건");

                var payment = $(this).parents(".view_table").find("span[id=payment]");
                var price = payment.data('num') * 1;
                price -= 12000;
                payment.data('num', price);
                payment.html(comma(price) + "원");
            }
        });
        $('.phone_db_plus').on("click",function() {
            if($('input[name=phone_db_chk]').prop("checked") == true){
                var db_phone = $(this).parents(".view_table").find("#phone_db");
                var phone_value = db_phone.data('num') * 1;
                phone_value += 1000;
                db_phone.data('num', phone_value);
                db_phone.html(comma(phone_value));

                var payment = $(this).parents(".view_table").find("span[id=payment]");
                var price = payment.data('num') * 1;
                price += 10000;
                payment.data('num',price);
                payment.html(comma(price) + "원");
            }
        });

        $('.phone_db_minus').on("click",function() {
            if($('input[name=phone_db_chk]').prop("checked") == true){
                var db_phone = $(this).parents(".view_table").find("#phone_db");
                var phone_value = db_phone.data('num') * 1;
                var phone_min = db_phone.data('min') * 1;
                if(phone_value > phone_min) {
                    phone_value -= 1000;
                    db_phone.data('num', phone_value);
                    db_phone.html(comma(phone_value));

                    var payment = $(this).parents(".view_table").find("span[id=payment]");
                    var price = payment.data('num') * 1;
                    price -= 10000;
                    payment.data('num', price);
                    payment.html(comma(price) + "원");
                }
            }
        });

        $('.email_db_plus').on("click",function() {
            if($('input[name=email_db_chk]').prop("checked") == true){
                var email_db = $(this).parents(".view_table").find("#email_db");
                var email_value = email_db.data('num') * 1;
                email_value += 2000;
                email_db.data('num', email_value);
                email_db.html(comma(email_value));

                var payment = $(this).parents(".view_table").find("span[id=payment]");
                var price = payment.data('num') * 1;
                price += 2000;
                payment.data('num',price);
                payment.html(comma(price) + "원");
            }
        });

        $('.email_db_minus').on("click",function() {
            if($('input[name=email_db_chk]').prop("checked") == true){
                var email_db = $(this).parents(".view_table").find("#email_db");
                var email_value = email_db.data('num') * 1;
                var email_min = email_db.data('min') * 1;
                if(email_value > email_min) {
                    email_value -= 2000;
                    email_db.data('num', email_value);
                    email_db.html(comma(email_value));

                    var payment = $(this).parents(".view_table").find("span[id=payment]");
                    var price = payment.data('num') * 1;
                    price -= 2000;
                    payment.data('num', price);
                    payment.html(comma(price) + "원");
                }
            }
        });
        $('.shop_db_plus').on("click",function() {
            if($('input[name=shop_db_chk]').prop("checked") == true){
                var db_shop = $(this).parents(".view_table").find("#shop_db");
                var shop_value = db_shop.data('num') * 1;
                shop_value += 1000;
                db_shop.data('num', shop_value);
                db_shop.html(comma(shop_value));

                var payment = $(this).parents(".view_table").find("span[id=payment]");
                var price = payment.data('num') * 1;
                price += 5000;
                payment.data('num',price);
                payment.html(comma(price) + "원");
            }
        });

        $('.shop_db_minus').on("click",function() {
            if($('input[name=shop_db_chk]').prop("checked") == true){
                var db_shop = $(this).parents(".view_table").find("#shop_db");
                var shop_value = db_shop.data('num') * 1;
                var shop_min = db_shop.data('min') * 1;
                if(shop_value > shop_min) {
                    shop_value -= 1000;
                    db_shop.data('num', shop_value);
                    db_shop.html(comma(shop_value));

                    var payment = $(this).parents(".view_table").find("span[id=payment]");
                    var price = payment.data('num') * 1;
                    price -= 5000;
                    payment.data('num', price);
                    payment.html(comma(price) + "원");
                }
            }
        });
        $('input[id=payment]').on('blur',function(){
            var val = $(this).val();
            var min = $(this).data('min');
            if(val < min)
                $(this).val(min);
            else
                $(this).data('num',$(this).val());
        });
        $('.send_count_plus').on("click", function(){
            var send_count = $(this).parents('.view_table').find('#send_count');
            var send_count_num = send_count.data("num") * 1;
            send_count_num += 1000;
            send_count.html(comma(send_count_num));
            send_count.data("num", send_count_num);
            var payment = $(this).parents('.view_table').find('#payment');
            if(payment.data("type") != 2) {
                var price = payment.data("num") * 1;
                price += 2000;
                payment.html(comma(price) + "원");
                payment.data("num", price);
            }
        });
        $('.send_count_minus').on("click", function(){
            var send_count = $(this).parents('.view_table').find('#send_count');
            var send_count_num = send_count.data("num") * 1;
            var send_count_min = send_count.data("min") * 1;
            if(send_count_num > send_count_min) {
                send_count_num -= 1000;
                send_count.html(comma(send_count_num));
                send_count.data("num", send_count_num);
                var payment = $(this).parents('.view_table').find('#payment');
                if(payment.data("type") != 2) {
                    var price = payment.data("num") * 1;
                    price -= 2000;
                    payment.html(comma(price) + "원");
                    payment.data("num", price);
                }
            }
        });
        $('input[name=phone_db_chk]').on("click", function(){
            refresh_dber_payment();
        });
        $('input[name=email_db_chk]').on("click", function(){
            refresh_dber_payment();
        });
        $('input[name=shop_db_chk]').on("click", function(){
            refresh_dber_payment();
        });
    });
    function refresh_dber_payment(){
        var table = $("#dber_table").children().find(".view_table");
        var db_price = table.find("#phone_db").data('num') * 10;
        var email_price = table.find("#email_db").data('num') * 1;
        var shop_price = table.find("#shop_db").data('num') * 5;
        var price = 0;
        if($('input[name=phone_db_chk]').prop("checked") == true)
            price += db_price;
        if($('input[name=email_db_chk]').prop("checked") == true)
            price += email_price;
        if($('input[name=shop_db_chk]').prop("checked") == true)
            price += shop_price;
        var payment = table.find('#payment');
        payment.html(comma(price) + "원");
        payment.data("num", price);
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
    function form_save() {
        if($('#mem_id').val() == "") {
            alert('분양자아이디를 입력해주세요.');
            $('#mem_id').focus();
        }else if($('#sub_domain').val() == "") {
            alert('도메인을 입력해주세요.');
            $('#sub_domain').focus();
        }else if($('#sub_domain').val() == "kiam"){
            alert('이미 등록된 도메인입니다. 다른 도메인명을 입력해주세요.');
            $('#sub_domain').focus();
        }else {
            $.ajax({
                type:"POST",
                url:"/admin/ajax/service_Iam_save.php",
                data:{
                    "mode":"check_service",
                    "sub_domain":$("#sub_domain").val()
                },
                dataType: 'json',
                success:function(data){
                    if(data.result == 0){
                        $('#main_domain').val($('#sub_domain').val());
                        var data = $('#dForm').serialize();
                        var domain_len = $("#sub_domain").val().length;
                        if(domain_len > 10){
                            alert("도메인 입력이 잘못됐습니다. 영문 소문자 10자 내외로 입력해주세요.");
                            return;
                        }
                        $.ajax({
                            type:"POST",
                            url:"/admin/ajax/service_Iam_save.php",
                            data:data,
                            dataType: 'html',
                            success:function(data){
                                if($("#onestep2").val() == "OFF"){
                                    alert("제출 완료되었습니다.");
                                    location.reload();
                                }else{
                                    alert("결제되었고 플랫폼구축정보 제출하기도 완료되었습니다.다시 로그인해주세요");
                                    $.ajax({
                                        type: "POST",
                                        url: "/ajax/ajax_session.php",
                                        data: {
                                            logout_go: "로그아웃가자"
                                        },
                                        success: function (data) {
                                            try {
                                                window.AppScript.setLogout();
                                            } catch (e) {}
                                            ajax_state = 1;
                                            $("#ajax_div").html(data);
                                        }
                                    });
                                }
                                
                            },
                            error: function(){
                                alert('로딩 실패');
                            }
                        });
                    }else{
                        $("#ajax-loading").delay(10).hide(1);
                        alert("이미 등록된 도메인입니다. 다른 도메인명을 입력해주세요.");
                        $('#sub_domain').focus();
                    }
                },
                error: function(){
                    //alert('로딩 실패');
                }
            });
        }
    }
</script>

