<?
//아이엠통장정기결제
include "inc/header.inc.php";
if($member_iam['mem_id'] == "") {
    echo "<script>location.history(-1);</script>";
    exit;
}
$orderNumber = $_POST[allat_order_no];
$pay_info['fujia_status'] = "N";
$pay_info['month_cnt'] = $_POST['month_cnt']>120?120:$_POST['month_cnt']; //12?12를 120?120수정하여 마감기간을 솔루션결제관리페이지에 120개월로 표시
$pay_info['max_cnt'] = $pay_info['phone_cnt'] = $_POST['phone_cnt']; // 추가갯수
$pay_info['end_status'] = "N";
$pay_info['buyertel'] = $member_iam[mem_phone]; //구매자 전화번호
$pay_info['buyeremail'] = $member_iam[mem_email]; //구매자 연락처
$pay_info['resultCode'] = "00";
$pay_info['resultMsg'] = "통장정기결제";
$pay_info['payMethod'] = "MONTH";
$pay_info['TotPrice'] = $_POST['allat_amt']; //금액
$pay_info['pc_mobile'] = "A"; //금액
$pay_info['VACT_InputName'] =$member_iam['mem_name'];
$pay_info['buyer_id'] = $member_iam['mem_id'];
$pay_info['member_type'] = $_POST['member_type'];
$pay_info['add_opt'] = $_POST['add_opt'];
$pay_info['db_cnt'] = $_POST['db_cnt'];
$pay_info['email_cnt'] = $_POST['email_cnt'];
$pay_info['shop_cnt'] = $_POST['shop_cnt'];
$pay_info['onestep1'] = $_POST['onestep1'];
$pay_info['onestep2'] = $_POST['onestep2'];
$pay_info['add_phone']=$_POST['phone_cnt'] * 1 / 9000;
$pay_info['monthly_yn']='Y';
$pay_info['idx'] = $_POST[allat_order_no];
$pay_info['orderNumber'] = $_POST[allat_order_no];
$pay_info['iam_card_cnt']=$_POST[iam_card_cnt];
$pay_info['iam_share_cnt']=$_POST[iam_share_cnt];
$pay_info['member_cnt']=$_POST[member_cnt];
$sql = "insert into tjd_pay_result set ";
foreach ($pay_info as $key => $v) {
    $sql .= " $key = '$v' , ";
}
$sql .= " end_date=date_add(now(),INTERVAL {$_POST[month_cnt]} month) , date=now()";
mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$no = mysqli_insert_id($self_con);

$sql = "insert into tjd_pay_result_month set pay_idx='$orderNumber',
                                            order_number='$orderNumber',
                                            pay_yn='N',
                                            msg='아이엠통장정기결제',
                                            regdate = NOW(),
                                            amount='$_POST[allat_amt]',
                                            buyer_id='{$member_iam['mem_id']}'";
mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
// set_service_mem_cnt($member_iam['mem_id'], $_POST['member_cnt']);
if($_POST['phone_cnt'] > 0) {
    $sql = "select * from tjd_pay_result where orderNumber='{$orderNumber}' and buyer_id='{$member_iam['mem_id']}' ";
    $resul = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($resul);

    $sql = "select * from Gn_Member where mem_id='{$member_iam['mem_id']}' ";
    $sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $srow = mysqli_fetch_array($sresult);
    $sql = "select count(cmid) from crawler_member_real where user_id='{$member_iam['mem_id']}' ";
    $sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $crow = mysqli_fetch_array($sresult);
    if ($crow[0] == 0) {
        $user_id = $srow['mem_id'];
        $user_name = $srow['mem_name'];
        $password = $srow['mem_pass'];
        $cell = $srow['mem_phone'];
        $email = $srow['mem_email'];
        $address = $srow['mem_add1'];
        $status = "N";
        $use_cnt = $_POST[db_cnt];
        $search_email_date = substr($last_time, 0, 10);
        $search_email_cnt = $_POST[email_cnt];
        $term = substr($last_time, 0, 10);
        $query = "insert into crawler_member_real set user_id='$user_id',
                                            user_name='$user_name',
                                            password='$password',
                                            cell='$cell',
                                            email='$email',
                                            address='$address',
                                            term='$term',
                                            status='N',
                                            use_cnt='$use_cnt',
                                            regdate=NOW(),
                                            search_email_yn='$status',
                                            search_email_date='$search_email_date',
                                            search_email_cnt='$search_email_cnt',
                                            shopping_end_date='$search_email_date'";
        mysqli_query($self_con,$query);
    }

    if ($srow['recommend_id'] != "") {
        $sql = "select * from Gn_Member where mem_id='$srow[recommend_id]' ";
        $rresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        if (mysqli_num_rows($rresult) > 0) {
            $rrow = mysqli_fetch_array($rresult);
            $addQuery = "";
            $branch_share_per = 0;

            // 리셀러 / 분양 회원 확인
            // 리셀러 회원인경우 분양회원 아이디 확인
            if ($rrow[service_type] == 2) {
                // 추천인의 추천인 검색 및 등급 확인
                $sql = "select * from Gn_Member where mem_id='$rrow[recommend_id]'";
                $rresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                $trow = mysqli_fetch_array($rresult);
                $share_per = $recommend_per = $rrow['share_per'] ? $rrow['share_per'] : 30;
                if ($trow[0] != "") {
                    $recommend_per = $trow['share_per'] ? $trow['share_per'] : 50;
                    $branch_share_per = $recommend_per - $share_per;
                    if ($share_per == "" || $share_per == 0) $branch_share_per = 0;
                    $branch_share_id = $trow['mem_id'];
                }
            } else if ($rrow[service_type] == 3) {
                $share_per = $recommend_per = $rrow['share_per'] ? $rrow['share_per'] : 50;
                $branch_share_per = 0;
            }

            $sql = "update tjd_pay_result set share_per='$share_per', branch_share_per = '$branch_share_per', share_id='$srow[recommend_id]', branch_share_id='$branch_share_id' where no='$no'";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        }
    }
}
?>
<link href='/css/main.css' rel='stylesheet' type='text/css'/>
<link href='/css/responsive.css' rel='stylesheet' type='text/css'/>
<script>
    window.open("https://ap.hyosungcmsplus.co.kr/external/shorten/20230317ezgQgt9oPT", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=500,width=600,height=780")
</script>
<body style="padding-top: 0px">
<div class="big_main pay-wrap" style="height: auto;min-height: 100%;">
    <div id="wrap" class="common-wrap" background-image = "/images/main_bg_09.jpg" style="max-width: 1024px;padding:0px">
        <div style="margin-top: 86px">
            <img src="/images/sub_02_visual_03.jpg" style = "width : 100%"/>
        </div>
        <div class="big_1">
            <div class="m_div">
                <div class="left_sub_menu">
                    <a href="./">홈</a> >
                    <a href="pay_return.php">결제결과</a>
                </div>
                <div class="right_sub_menu">&nbsp;</div>
                <p style="clear:both;"></p>
            </div>
        </div>
        <div class="pay">
            <table class="write_table" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="2" style="font-size:16px;">
                        PG log
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center;">
                        <h3>아래 정기결제 신청이 완료되어야 구매가 완료됩니다.</h3>
                    </td>
                </tr>
                <tr>
                    <td>지불수단</td>
                    <td>정기결제</td>
                </tr>
                <tr>
                    <td>주문번호</td>
                    <td><?=$orderNumber?></td>
                </tr>                    
                <tr>
                    <td>구매자명</td>
                    <td><?=$pay_info['VACT_InputName']?></td>
                </tr>
                <tr>
                    <td>입금금액</td>
                    <td><?=number_format($pay_info['TotPrice'])?> 원</td>
                </tr>
                <tr>
                	<td colspan="2" style="text-align:center;">
                        <div style="">
                            <input type="button" style="height: auto" class="btn btn-primary" value="정기결제 신청하기" onclick="window.open('https://ap.hyosungcmsplus.co.kr/external/shorten/20230317ezgQgt9oPT')">
                            <input type="button" style="height: auto" class="btn btn-primary" value="메인으로" onclick="location.replace('/')">
                        </div>
                    </td>
                </tr>
            </table>
            <div style="margin-bottom:25px;border:1px solid #000;padding:10px;line-height:25px;">
                <div class="a3_1"> 정기결제 유의사항</div>
                    <p>1. 부가세 포함 안내  : 정기결제금액은 부가세가 포함된 가격입니다.<br/>
					   2. 정기결제출금일자 : 정기결제 출금일은 결제신청을 한 날로부터 7일 후로 신청됩니다.<br/>
					   3. 정기결제신청완료 : 정기결제 신청 후 휴대폰으로 ARS전화를 받아야 신청이 완료됩니다.<br/>
					   4. 정기결제해지위치 : 정기결제는 고객님의 마이페이지에서 가능합니다.<br/>
					   5. 정기결제해지처리 : 고객 계정 오픈 후 첫달 결제일 전에 해지해도 첫 달 결제후 해지 처리되며, 정기 결제일이 지난 후 해지하면 해당 월의 사용기간이 끝날 때 해지 됩니다.<br/>
					   6. 정기결제변경처리 : 결제타입이나 수량변경을 하려면 기존 결제를 해지하고 다시 신청해주세요.<br/>
                    </p>
                </div>
            </div>
        </div>
    </div>
<?
include_once "_foot.php";
?>
