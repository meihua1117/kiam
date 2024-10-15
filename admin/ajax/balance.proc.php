<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$total_price = $_POST['total_price'];
$mem_id = $_POST['mem_id'];
$search_year = $_POST['search_year'];
$search_month = $_POST['search_month'];
$search_year = $search_year ? $search_year : date("Y");
$search_month = $search_month ? sprintf("%02d", $search_month) : sprintf("%02d", date("m"));
$search_start_date = $search_year . "-" . sprintf("%02d", $search_month) . "-01";
$search_end_date = date('Y-m', strtotime('+1 month', strtotime("$search_start_date"))) . "-01";
$searchStr .= " and date between '$search_start_date 00:00:00' and '$search_end_date 00:00:00'";

$query = "  SELECT 
                        	    SQL_CALC_FOUND_ROWS 
                        	    a.no, 
                        	    b.mem_id,
                        	    b.mem_name,
                        	    a.end_status,
                        	    b.mem_phone,
                        	    a.TotPrice,
                        	    a.add_phone,
                        	    a.month_cnt,
                        	    a.date,
                        	    a.end_date,
                        	    c.price as total_price,
                        	    c.payment_date,
                        	    a.balance_date,
                        	    a.balance_yn,
                        	    a.share_per,
                        	    a.branch_share_per,
                        	    b.service_type
                        	FROM Gn_Member b
                        	INNER JOIN tjd_pay_result a
                        	       on b.mem_id =a.buyer_id
                            INNER JOIN (   select 
                                              bb.recommend_id, 
                                              DATE_FORMAT(date, '%Y%m') payment_date,
                                              sum(TotPrice) price 
                                         from tjd_pay_result aa 
                                    left join Gn_Member bb
                                           on bb.mem_id = aa.buyer_id 
                                        where 1=1 
                                        $searchStr
                                        and end_status='Y' 
                                        AND recommend_id <> ''
                                        group by bb.recommend_id, payment_date
                                    ) c
                                   on b.mem_id = c.recommend_id 

                        	WHERE 1=1 
                	              $searchStr
                	              and buyer_id='$mem_id'";
$resul = mysqli_query($self_con, $query) or die(mysqli_error($self_con));
$row = mysqli_fetch_array($resul);
// 금액 동일한지 확인
// insert
$query = "insert into Gn_Balance set buyer_id='',
                                     total_price='',
                                     balance_price='',
                                     rate='',
                                     brance_rate='',
                                     mem_id='',
                                     regdate=NOW(),
                                     status='Y',
                                     status_date=NOW()";
// tjd_pay_result update 
$query = "update tjd_pay_result set where $searchStr and end_status='Y' ";
print_r($row);
exit;

// 정보 확인
$sql = "select * from Gn_Member where mem_code='$mem_code'";
$resul = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$row = mysqli_fetch_array($resul);

if ($_POST['mem_code']) {
    // 기본회원 정보 수정 []
    /*
    1. 가입회원상세정보에서 수정가능한 항목
    - 스마트폰 번호
    - 앱비밀번호 V
    - pc비밀번호 V
    - 기부폰승인
    - 부가서비스
    - 총결제금액 : 결제금액 합계 V
    - 결제계좌정보 : 은행명, 계좌번호, 예금주 V
    - 마지막결제정보 : 관리자가 결제금액입력 V
    - 회원구분 : 선거용 / 개인용 (현재 문자사이트 전체회원)  V
    */

    // 추가 변경될 내용
    $addSql = "";
    // 부가서비스가 있을경우
    if ($add_opt == "Y") {
        $addSql .= " ,fujia_date2 = '" . $fujia_date2 . "' ";
    } else if ($add_opt == "N") {
        $addSql .= " ,fujia_date2 = '0000-00-00 00:00:00' ";
    }


    if ($add_opt == "Y" && $total_pay_money > 0) {
        $date_today = date("Y-m-d g:i:s");
        $dateLimit = date("Y-m-d 23:59:59", strtotime($date_today . "+6 months")); //가입일 +6개월        
        $asql = "insert into tjd_pay_result (phone_cnt,fujia_status,month_cnt,end_date,end_status,TotPrice,pc_mobile, date, cancel_status,buyertel,buyer_id) values";
        $asql .= "(300,'Y',6,'" . $dateLimit . "','Y','" . $total_pay_money . "',pc_mobile, '" . $date_today . "', cancel_status,'" . $mem_phone . "','" . $row['mem_id'] . "')";
        mysqli_query($self_con, $asql);

        $sql_num_up = "update Gn_MMS_Number set end_status='Y' , end_date=date_add(now(),INTERVAL 6 MONTH) where mem_id='{$row['mem_id']}' ";
        mysqli_query($self_con, $sql_num_up);
    }

    // 앱비밀번호가 있을경우
    if ($passwd) {
        $passwd = md5($passwd);
        $addSql .= " ,mem_pass='$passwd'";
    }


    // PC 비밀번호가 있는경우
    if ($web_passwd) {
        $addSql .= " ,web_pwd=password('$web_passwd')";
    }

    // 스마트폰 번호가 있을경우
    if ($row['mem_phone'] != $mem_phone) {
        $addSql .= " ,mem_phone='$mem_phone'";
        $changePhone = true;

        $sql = "update tjd_pay_result set fujia_status='Y' where buyer_id='{$row['mem_id']}'";
        mysqli_query($self_con, $sql);
    }

    if ($mem_type) {
        $site = getPosition($recommend_id);
        setPosition($row['mem_id'], $site);
        $sql = "update Gn_Member set mem_type='$mem_type', 
                                       total_pay_money='$total_pay_money',
                                       phone_cnt='$phone_cnt',
                                       is_message='$is_message',
                                       bank_name='$bank_name',
                                       bank_account='$bank_account',
                                       bank_owner='$bank_owner',
                                       site='$site',
                                       recommend_id='$recommend_id'
                                       $addSql 
                                 where mem_code='$mem_code'";
        mysqli_query($self_con, $sql);
    }


    // 스마트폰 변경시 변경되어야 하는 테이블
    if ($changePhone === true) {
    }
}
if ($_POST['mem_code'] && $_POST['sendnum']) {
    // 기부회원 정보 수정 [기부비율]
    if ($donation_rate) {
        $sql_num = "update Gn_MMS_Number set donation_rate='$donation_rate' where mem_id='{$row['mem_id']}' and sendnum='$sendnum' ";
        mysqli_query($self_con, $sql_num);
    }
}
echo json_encode(array("result" => $result));

function setPosition($user_id, $position)
{
    global $self_con;
    $sql3 = "select sub_domain FROM Gn_Service WHERE sub_domain like '%kiam.kr' And mem_id='$user_id'";
    $res = mysqli_query($self_con, $sql3);
    $row1 = mysqli_fetch_array($res);
    if ($row1['sub_domain'])
        return;

    $strQuery = "select mem_id from Gn_Member WHERE recommend_id='$user_id'";
    $res = mysqli_query($self_con, $strQuery);
    $bOnce = false;
    while ($arrResult = mysqli_fetch_array($res)) {
        if (!$bOnce) {
            $strQuery1 = "update Gn_Member set site='$position' where recommend_id='$user_id'";
            mysqli_query($self_con, $strQuery1);
            $bOnce = true;
        }

        setPosition($arrResult['mem_id'], $position);
    }
}

function getPosition($recommender)
{
    global $self_con;
    $sql3 = "select sub_domain FROM Gn_Service WHERE sub_domain like '%kiam.kr' And mem_id='$recommender'";
    $res = mysqli_query($self_con, $sql3);
    $row1 = mysqli_fetch_array($res);
    if ($row1['sub_domain']) {
        $parse = parse_url($row1['sub_domain']);
        $sites = explode(".", $parse['host']);
        $site = $sites[0];
    } else {
        $sql3 = "select site from Gn_Member WHERE mem_id='$recommender'";
        $res = mysqli_query($self_con, $sql3);
        $row1 = mysqli_fetch_array($res);
        $site = $row1['site'];
    }
    return $site;
}
