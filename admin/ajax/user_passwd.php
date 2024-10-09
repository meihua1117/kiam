<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$mem_code = $_POST["mem_code"]; 
$sendnum = $_POST["sendnum"];
$result=0;

// 정보 확인
$sql="select * from Gn_Member where mem_code='$mem_code'";
$resul=mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_array($resul);    

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
        $addSql .= " ,fujia_date2 = '".date("Y-m-d H:i:s", mktime()+(86400*365))."' ";
    } else if($add_opt == "N"){
        $addSql .= " ,fujia_date2 = '0000-00-00 00:00:00' ";
    }

    $web_passwd = '1    
    // 앱비밀번호가 있을경우
        $passwd = md5('111111');
        $addSql .= " ,mem_pass='$passwd'";
    
    // PC 비밀번호가 있는경우
        $addSql .= " ,web_pwd=password('$web_passwd')";
    
    // 스마트폰 번호가 있을경우
    if($row['mem_phone'] != $mem_phone) {
        $addSql .= " ,mem_phone='$mem_phone'";
        $changePhone = true;
        
            $sql="update tjd_pay_result set fujia_status='Y' 
                                     where buyer_id='$row[mem_id]'";
            mysql_query($sql);	        
    }
    
    if($mem_type) {
        $sql="update Gn_Member set mem_type='$mem_type', 
                                       total_pay_money='$total_pay_money',
                                       phone_cnt='$phone_cnt',
                                       is_message='$is_message',
                                       bank_name='$bank_name',
                                       bank_account='$bank_account',
                                       bank_owner='$bank_owner'
                                       $addSql 
                                 where mem_code='$mem_code'";
        mysql_query($sql);	
    }
    
    
    // 스마트폰 변경시 변경되어야 하는 테이블
    if($changePhone === true) {
        
        
    }
    
}
if($_POST['mem_code'] && $_POST['sendnum']) {
    // 기부회원 정보 수정 [기부비율]
    if($donation_rate) {
        $sql_num="update Gn_MMS_Number set donation_rate='$donation_rate' where mem_id='$row[mem_id]' and sendnum='$sendnum' ";
        mysql_query($sql_num);
    }
}
echo "{\"result\":\"$result\"}";
?>