<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
//셀링카드정기결제
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
// 올앳관련 함수 Include
//----------------------
include "./allatutil.php";
///Request Value Define
//----------------------
$at_cross_key = "가맹점 CrossKey";     //설정필요 [사이트 참조 - http://www.allatpay.com/servlet/AllatBiz/helpinfo/hi_install_guide.jsp#shop]
$at_shop_id   = "가맹점 ShopId";       //설정필요
$at_cross_key = "304f3a821cac298ff8a0ef504e1c2309";	//설정필요 [사이트 참조 - http://www.allatpay.com/servlet/AllatBiz/support/sp_install_guide_scriptapi.jsp#shop]
$at_shop_id   = "bwelcome12";		//설정필요
// 요청 데이터 설정
//----------------------
$at_data   = "allat_shop_id=".$at_shop_id.
    "&allat_enc_data=".$_POST["allat_enc_data"].
    "&allat_cross_key=".$at_cross_key;
// 올앳 서버와 통신
//--------------------------
$at_txt = CertRegReq($at_data,"SSL");
// 결제 결과 값 확인
//------------------
$REPLYCD   =getValue("reply_cd",$at_txt);        //결과코드
$REPLYMSG  =getValue("reply_msg",$at_txt);       //결과 메세지
$ORDER_NO = $_POST['allat_order_no'];

$FIX_KEY	= getValue("fix_key",$at_txt);
$APPLY_YMD	= getValue("apply_ymd",$at_txt);

$sql="select * from tjd_pay_result where orderNumber='$ORDER_NO'";
$resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($resul);
$no = $row['no'];
if(!strcmp($REPLYCD,"0000")){//pay_test
    $sql = "update tjd_pay_result set end_status='Y',billkey='$FIX_KEY',billdate='$APPLY_YMD' where  orderNumber='$ORDER_NO'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
//디버회원가입하기
    $user_id = $member_1['mem_id'];
    $user_name = $member_1['mem_name'];
    $password = $member_1['mem_pass'];
    $cell = $member_1['mem_phone'];
    $email = $member_1['mem_email'];
    $address = $member_1['mem_add1'];
    $status = "Y";
    $use_cnt = $row['db_cnt'];
    $last_time = date("Y-m-d H:i:s", strtotime("+{$row['month_cnt']} month"));
    $search_email_date = substr($last_time, 0, 10);
    $search_email_cnt = $row['email_cnt'];
    $term = substr($last_time, 0, 10);

    $sql = "select count(cmid) from crawler_member_real where user_id='{$member_1['mem_id']}' ";
    $sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $crow = mysqli_fetch_array($sresult);
    if ($crow[0] == 0) {
        $query = "insert into crawler_member_real set user_id='$user_id',
                                        user_name='$user_name',
                                        password='$password',
                                        cell='$cell',
                                        email='$email',
                                        address='$address',
                                        term='$term',
                                        status='$status',
                                        use_cnt='$use_cnt',
                                        regdate=NOW(),
                                        search_email_yn='Y',
                                        search_email_date='$search_email_date',
                                        search_email_cnt='$search_email_cnt',
                                        shopping_end_date='$search_email_date'";
        mysqli_query($self_con,$query);
    } else {
        $query = "update crawler_member_real set
                                        cell='$cell',
                                        email='$email',
                                        address='$address',
                                        term='$term',
                                        status='$status',
                                        use_cnt='$use_cnt',
                                        monthly_cnt = 0,
                                        total_cnt = 0,
                                        regdate=NOW(),
                                        search_email_yn='Y',
                                        search_email_date='$search_email_date',
                                        search_email_cnt='$search_email_cnt',
                                        shopping_yn='N',
                                        shopping_end_date='$search_email_date',
                                        status='Y'
                                        where user_id='$user_id'";
        mysqli_query($self_con,$query);
    }
    $add_phone = $row[phone_cnt] / 9000;
    $sql_m = "update Gn_Member set fujia_date1=now() , fujia_date2=date_add(now(),INTERVAL 120 month),phone_cnt=phone_cnt+'$add_phone'";
    $sql_m .= " where mem_id='{$member_1['mem_id']}'";
    mysqli_query($self_con,$sql_m) or die(mysqli_error($self_con));

    if ($member_1['recommend_id'] != "") {
        $sql = "select * from Gn_Member where mem_id='$member_1[recommend_id]' ";
        $rresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        if (mysqli_num_rows($rresult) > 0) {
            $rrow = mysqli_fetch_array($rresult);
            $branch_share_id = "";
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
                    $branch_share_id = $trow['mem_id'];
                }
            } else if ($rrow[service_type] == 3) {
                $share_per = $recommend_per = $rrow['share_per'] ? $rrow['share_per'] : 50;
                $branch_share_per = 0;
            }

            $sql = "update tjd_pay_result set share_per='$share_per', branch_share_per = '$branch_share_per', share_id='$member_1[recommend_id]', branch_share_id='$branch_share_id' where no='$no'";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        }
    }//디버회원가입 완료
    $iam_card_cnt = $_POST['iam_card_cnt'];
    $iam_share_cnt = $_POST['iam_share_cnt'];
    $sql_m = "update Gn_Member set fujia_date1=now() ,
                                fujia_date2=date_add(now(),INTERVAL 120 month),
                                iam_card_cnt = iam_card_cnt + '$iam_card_cnt',
                                iam_share_cnt = iam_share_cnt + '$iam_share_cnt',
                                exp_start_status = 0,
                                exp_mid_status = 0,
                                exp_limit_status = 0,
                                exp_limit_date = NULL
                            where mem_id='{$member_1['mem_id']}' ";
    mysqli_query($self_con,$sql_m) or die(mysqli_error($self_con));
    // 결과값 처리
    // reply_cd "0000" 일때만 성공
    $FIX_KEY	= getValue("fix_key",$at_txt);
    $APPLY_YMD	= getValue("apply_ymd",$at_txt);
?>
    <script language="javascript">
        window.parent.location.replace('/allat/mp/allat_approval_extra.php?ORDER_NO='+ '<?=$ORDER_NO?>');
    </script>
<?
}else{
    // reply_cd 가 "0000" 아닐때는 에러 (자세한 내용은 매뉴얼참조)
    // reply_msg 는 실패에 대한 메세지
    echo "결과코드  : ".$REPLYCD."<br>";
    echo "결과메세지: ".$REPLYMSG."<br>";
}
?>