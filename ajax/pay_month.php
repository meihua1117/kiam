<?
$fp = fopen("pay_month.log","w+");
//셀링통장정기결제
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$orderNumber = $_POST['allat_order_no'];
$pay_info['fujia_status'] = "N";
$pay_info['month_cnt'] = $_POST['month_cnt']>120?120:$_POST['month_cnt']; //12?12를 120?120수정하여 마감기간을 솔루션결제관리페이지에 120개월로 표시
$pay_info['max_cnt'] = $pay_info['phone_cnt'] = $_POST['phone_cnt'];
$pay_info['end_status'] = "N";
$pay_info['buyertel'] = $member_1['mem_phone']; //구매자 전화번호
$pay_info['buyeremail'] = $member_1['mem_email']; //구매자 연락처
$pay_info['resultCode'] = "00";
$pay_info['resultMsg'] = "통장정기결제";
$pay_info['payMethod'] = "MONTH";
$pay_info['TotPrice'] = $_POST['allat_amt']; //금액
$pay_info['pc_mobile'] = "A"; //금액
$pay_info['VACT_InputName'] =$member_1['mem_name'];
$pay_info['buyer_id'] = $member_1['mem_id'];
$pay_info['member_type'] = $_POST['member_type'];
$pay_info['add_opt'] = $_POST['add_opt'];
$pay_info['db_cnt'] = $_POST['db_cnt'];
$pay_info['email_cnt'] = $_POST['email_cnt'];
$pay_info['shop_cnt'] = $_POST['shop_cnt'];
$pay_info['onestep1'] = $_POST['onestep1'];
$pay_info['onestep2'] = $_POST['onestep2'];
$pay_info['add_phone']=$_POST['phone_cnt'] * 1 / 9000;
$pay_info['monthly_yn']='Y';
$pay_info['idx'] = $_POST['allat_order_no'];
$pay_info['orderNumber'] = $_POST['allat_order_no'];
$pay_info['iam_card_cnt']=$_POST['iam_card_cnt'];
$pay_info['iam_share_cnt']=$_POST['iam_share_cnt'];
$pay_info['member_cnt']=$_POST['member_cnt'];
$sql = "insert into tjd_pay_result set ";
foreach ($pay_info as $key => $v) {
    $sql .= " $key = '$v' , ";
}
$sql .= " end_date=date_add(now(),INTERVAL {$_POST['month_cnt']} month) , date=now()";
fwrite($fp,"38=".$sql."\r\n");
mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$no = mysqli_insert_id($self_con);

$sql = "insert into tjd_pay_result_month set pay_idx='$orderNumber',
                                            order_number='$orderNumber',
                                            pay_yn='N',
                                            msg='셀링통장정기결제',
                                            regdate = NOW(),
                                            amount='{$_POST['allat_amt']}',
                                            buyer_id='{$member_1['mem_id']}'";
fwrite($fp,"49=".$sql."\r\n");
mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

if($_POST['phone_cnt'] > 0) {
    $sql = "select * from tjd_pay_result where orderNumber='{$orderNumber}' and buyer_id='{$member_1['mem_id']}' ";
    fwrite($fp,"53=".$sql."\r\n");
    $resul = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($resul);

    $sql = "select * from Gn_Member where mem_id='{$member_1['mem_id']}' ";
    $sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $srow = mysqli_fetch_array($sresult);
    fwrite($fp,"61=".$sql."\r\n");

    $sql = "select count(cmid) from crawler_member_real where user_id='{$member_1['mem_id']}' ";
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
        $use_cnt = $_POST['db_cnt'];
        $search_email_date = substr($last_time, 0, 10);
        $search_email_cnt = $_POST['email_cnt'];
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
        fwrite($fp,"90=".$query."\r\n");
        mysqli_query($self_con,$query);
    } 
    if ($srow['recommend_id'] != "") {
        $share_id = $srow['recommend_id'];
        $sql = "select * from Gn_Member where mem_id='$share_id'";
        fwrite($fp,"96=".$sql."\r\n");
        $rresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        if(mysqli_num_rows($rresult) > 0){
            $rrow = mysqli_fetch_array($rresult);
            $addQuery = "";
            $branch_share_per = 0;
            // 리셀러 / 분양 회원 확인
            // 리셀러 회원인경우 분양회원 아이디 확인
            if ($rrow['service_type'] == 2) {
                // 추천인의 추천인 검색 및 등급 확인
                $sql = "select * from Gn_Member where mem_id='{$rrow['recommend_id']}'";
                fwrite($fp,"107=".$sql."\r\n");
                $rresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                $trow = mysqli_fetch_array($rresult);

                $share_per = $recommend_per = $rrow['share_per'] ? $rrow['share_per'] : 30;
                if ($trow[0] != "") {
                    $recommend_per = $trow['share_per'] ? $trow['share_per'] : 50;
                    $branch_share_per = $recommend_per - $share_per;
                    if ($share_per == "" || $share_per == 0) $branch_share_per = 0;
                    $branch_share_id = $trow['mem_id'];
                }
            } else if ($rrow['service_type'] == 3) {
                $share_per = $recommend_per = $rrow['share_per'] ? $rrow['share_per'] : 50;
                $branch_share_per = 0;
            }

            $sql = "update tjd_pay_result set share_id='$share_id',share_per='$share_per', branch_share_id='$branch_share_id',branch_share_per = '$branch_share_per'  where no='$no'";
            fwrite($fp,"121=".$sql."\r\n");
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        }
    }
}
fwrite($fp,"127=end\r\n");
?>