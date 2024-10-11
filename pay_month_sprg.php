<?
$path="./";
include_once "_head.php";
if($member_1['mem_id'] == "") {
    echo "<script>location.history(-1);</script>";
    exit;
}
$data = $member_iam;
$orderNumber = $_POST['allat_order_no'];
$pay_info['month_cnt'] = $_POST['month_cnt']>120?120:$_POST['month_cnt']; //12?12를 120?120수정하여 마감기간을 솔루션결제관리페이지에 120개월로 표시
$pay_info['fujia_status'] = "N"; 
$pay_info['max_cnt'] = $pay_info['phone_cnt'] = $_POST['phone_cnt']; // 추가갯수
$pay_info['end_status'] = "N";
$pay_info['buyertel'] = $member_1[mem_phone]; //구매자 전화번호
$pay_info['buyeremail'] = $member_1[mem_email]; //구매자 연락처
$pay_info['resultCode'] = "00";
$pay_info['resultMsg'] = "정기결제";
$pay_info['payMethod'] = "MONTH";
$pay_info['TotPrice'] = $_POST['allat_amt']; //금액
$pay_info['pc_mobile'] = "A"; //금액
$pay_info['VACT_InputName'] =$member_1['mem_name'];
$pay_info['buyer_id'] = $member_1['mem_id'];        
$member_type = /*$pay_info['iam_pay_type'] =*/ $_POST['member_type'];
$pay_info['add_opt'] = $_POST['add_opt'];
$pay_info['db_cnt'] = $_POST['db_cnt'];
$pay_info['email_cnt'] = $_POST['email_cnt'];
$pay_info['onestep1'] = $_POST['onestep1'];
$pay_info['onestep2'] = $_POST['onestep2'];
$pay_info['idx'] = $orderNumber;
$pay_info['orderNumber'] = $orderNumber;
//if($_POST[member_type] != "")
//    $_POST[iam_pay_type] = $_POST[member_type];
$sql = "insert into tjd_pay_result
    set idx='$orderNumber',
    orderNumber='$orderNumber',
    VACT_InputName='{$data['mem_name']}',
    TotPrice='$pay_info[TotPrice]',
    end_date=date_add(now(),INTERVAL {$_pay_info['month_cnt']} month),
    end_status='N',
    buyertel='$data[mem_phone]',
    buyeremail='$data[mem_email]',
    payMethod='MONTH',
    buyer_id='{$pay_info['buyer_id']}',
    date=NOW(),
    member_type='$_POST[member_type]',
    iam_card_cnt='$_POST[iam_card_cnt]',
    iam_share_cnt='$_POST[iam_share_cnt]',
    month_cnt='{$_pay_info['month_cnt']}',
    member_cnt='$_POST[member_cnt]',
    monthly_yn = 'Y'";
mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
?>
<link href='/css/main.css' rel='stylesheet' type='text/css'/>
<link href='/css/responsive.css' rel='stylesheet' type='text/css'/>
<script>
    //var win = window.open("http://xn--2q1bv58amcq4w.kr/ars?userid=onlyone19", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=500,width=600,height=780")
    var win = window.open("https://ap.hyosungcmsplus.co.kr/external/shorten/20230317ezgQgt9oPT", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=500,width=600,height=780")
</script>
<div class="big_main">
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
   <div class="m_div">
   		<div><img src="images/sub_02_visual_03.jpg" /></div>
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
                        <div class="bnt main-buttons">
                            <div class="wrap2">
                                <a href="https://ap.hyosungcmsplus.co.kr/external/shorten/20230317ezgQgt9oPT" class="button" target="_blank">정기결제 신청하기</a>
                                <input type="button" value="메인으로" onclick="location.replace('/')"  />
                            </div>
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
