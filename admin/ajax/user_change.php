<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$fp = fopen("user_change.log","w+");
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$date_today1 = date("Y-m-d H:i:s");
$mem_code = $_POST["mem_code"]; 
$sendnum = $_POST["sendnum"];
$result=0;

// 정보 확인
$sql="select * from Gn_Member where mem_code='{$mem_code}'";
fwrite($fp,"17=>".$sql."\r\n");
$resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($resul);    

if($_POST['mem_code']) {
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
    if($add_opt == "Y") {
        $addSql .= " ,fujia_date2 = '{$fujia_date2}' ";
    } else if($add_opt == "N"){
        $addSql .= " ,fujia_date2 = fujia_date1 ";
    }
    if($add_opt == "Y" && $total_pay_money > 0) {
	    $date_today=date("Y-m-d g:i:s");
    	$dateLimit = date("Y-m-d 23:59:59",strtotime($date_today."+6 months")); //가입일 +6개월        
        $asql="insert into tjd_pay_result (phone_cnt,fujia_status,month_cnt,end_date,end_status,TotPrice,pc_mobile, date, cancel_status,buyertel,buyer_id) values";
        $asql.="(300,'Y',6,'{$dateLimit}','Y','{$total_pay_money}',pc_mobile, '{$date_today}', cancel_status,'{$mem_phone}','{$row['mem_id']}')";
	    fwrite($fp,"48=>".$asql."\r\n");
        mysqli_query($self_con,$asql);
	    
	    $sql_num_up="update Gn_MMS_Number set end_status='Y' , end_date=date_add(now(),INTERVAL 6 MONTH) where mem_id='{$row['mem_id']}' ";
	    fwrite($fp,"52=>".$sql_num_up."\r\n");
        mysqli_query($self_con,$sql_num_up);	    
	}
    // 앱비밀번호가 있을경우
    if($passwd) {
        $passwd = md5($passwd);
        $addSql .= " ,mem_pass='$passwd'";
    }
    // PC 비밀번호가 있는경우
    if($web_passwd) {
        //password()
        $addSql .= " ,web_pwd=md5('$web_passwd')";
        $web_pwd = md5($web_passwd);
        $dber_sql="update crawler_member_real set `password` = '{$web_pwd}' where user_id='{$row['mem_id']}'";
        fwrite($fp,"66=>".$dber_sql."\r\n");
        mysqli_query($self_con,$dber_sql);
    }
    // 스마트폰 번호가 있을경우
    if($row['mem_phone'] != $mem_phone) {
        $addSql .= " ,mem_phone='{$mem_phone}'";
        $changePhone = true;
        $sql="update tjd_pay_result set fujia_status='Y' where buyer_id='{$row['mem_id']}'";
        fwrite($fp,"74=>".$sql."\r\n");
        mysqli_query($self_con,$sql);	        
    }
    if($row['mem_phone1'] != $mem_phone1) {
        $addSql .= " ,mem_phone1='{$mem_phone1}'";        
    }
    if($row['mem_add1'] != $mem_add1) {
        $addSql .= " ,mem_add1='{$mem_add1}'";        
    }
    if($row['mem_add2'] != $mem_add2) {
        $addSql .= " ,mem_add2='{$mem_add2}'";        
    }
    if($row['com_add1'] != $com_add1) {
        $addSql .= " ,com_add1='{$com_add1}'";        
    }
    if($row['mem_memo'] != $mem_memo) {
        $addSql .= " ,mem_memo='{$mem_memo}'";        
    }

    if($send_phone_data && strlen($send_phone_data) > 1){
        $data1 = explode("/", $send_phone_data);
        $limit = $data1[0];
        $p_cnt = $data1[1];

        $sql_update = "update tjd_pay_result set max_cnt={$limit}, add_phone={$p_cnt} where buyer_id='{$row['mem_id']}' order by no desc limit 1";
        fwrite($fp,"99=>".$sql_update."\r\n");
        mysqli_query($self_con,$sql_update);
    }

    if(isset($_POST['vote_member'])){
        $addSql .= " ,mem_vote=1";
    }else{
        $addSql .= " ,mem_vote=0";
    }
    if($mem_type) {
        $sql="update Gn_Member set mem_type='{$mem_type}',
                                       mem_name='{$mem_name}',
                                       mem_birth='{$mem_birth}',
                                       mem_email='{$mem_email}',
                                       total_pay_money='{$total_pay_money}',
                                       phone_cnt='{$phone_cnt}',
                                       is_message='{$is_message}',
                                       bank_name='{$bank_name}',
                                       bank_account='{$bank_account}',
                                       bank_owner='{$bank_owner}',
                                       site='{$site}',
                                       zy='{$zy}',
                                       recommend_id='{$recommend_id}',
                                       site_iam='{$site_iam}',
                                       iam_card_cnt = {$iam_card_cnt},
                                       special_type = '{$special_type}',
                                       last_modify = '{$date_today1}',
                                       video_upload = '{$video_upload}'
                                       $addSql
                                 where mem_code='{$mem_code}'";
                                 fwrite($fp,"129=>".$sql."\r\n");
        mysqli_query($self_con,$sql);
    }
    else{
        $sql="update Gn_Member set mem_code='$mem_code' $addSql where mem_code='{$mem_code}'";
        fwrite($fp,"134=>".$sql."\r\n");
        mysqli_query($self_con,$sql);    
    }
    // 스마트폰 변경시 변경되어야 하는 테이블
    if($changePhone === true) {
    }
}
if($_POST['mem_code'] && $_POST['sendnum']) {
    // 기부회원 정보 수정 [기부비율]
    if($donation_rate) {
        $sql_num="update Gn_MMS_Number set donation_rate='$donation_rate' where mem_id='{$row['mem_id']}' and sendnum='{$sendnum}' ";
        fwrite($fp,"144=>".$sql_num."\r\n");
        mysqli_query($self_con,$sql_num);
    }
}
if($_POST['update_memo']){
    $sql_update = "update Gn_Member set mem_memo='{$memo}' where mem_id='{$mem_id}'";
    fwrite($fp,"151=>".$sql_update."\r\n");
    $result = mysqli_query($self_con,$sql_update);
}
if($_POST['video_upload']){
    $sql_update = "update Gn_Member set video_upload='{$status}' where mem_code='{$index}'";
    fwrite($fp,"156=>".$sql_update."\r\n");
    $result = mysqli_query($self_con,$sql_update);
}
echo json_encode(array("result"=>$result));
function setPosition($user_id, $position)
{
    global $self_con;
    $sql3="select sub_domain FROM Gn_Service WHERE sub_domain like '%kiam.kr' And mem_id='{$user_id}'";
    $res=mysqli_query($self_con,$sql3);
    $row1 = mysqli_fetch_array($res);
    if($row1['sub_domain'])
        return;

    $strQuery = "select mem_id from Gn_Member WHERE recommend_id='{$user_id}'";
    $res=mysqli_query($self_con,$strQuery);
    $bOnce = false;
    while($arrResult = mysqli_fetch_array($res))
    {
        if(!$bOnce)
        {
            $strQuery1 = "update Gn_Member set site='$position' where recommend_id='{$user_id}'";
            mysqli_query($self_con,$strQuery1);
            $bOnce = true;
        }
        setPosition($arrResult['mem_id'], $position);
    }
}

function getPosition($recommender)
{
    global $self_con;
    $sql3="select sub_domain FROM Gn_Service WHERE sub_domain like '%kiam.kr' And mem_id='{$recommender}'";
    $res=mysqli_query($self_con,$sql3);
    $row1 = mysqli_fetch_array($res);
    if($row1['sub_domain'])
    {
        $parse = parse_url($row1['sub_domain']);
        $sites = explode(".", $parse['host']);
        $site = $sites[0];
    }
    else
    {
        $sql3 = "select site from Gn_Member WHERE mem_id='{$recommender}'";
        $res=mysqli_query($self_con,$sql3);
        $row1 = mysqli_fetch_array($res);        
        $site = $row1['site'];
    }
    return $site;
}
?>