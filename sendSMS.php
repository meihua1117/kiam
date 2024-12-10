<?php
    @header("Content-type: text/html; charset=utf-8");
    include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/admin/include_once/admin_header.inc.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36';
$header =array('Accept: application/json, text/plain, */*','Cache-Control: no-cache');
//$query = "select * from Gn_Iam_automem where image1=''";
$query = "select * from Gn_Iam_automem where no=1";
$res = mysqli_query($self_con,$query);
while($row=mysqli_fetch_array($res))
{
    $name = $row['mem_name'];
    $pos = strpos($name,'(') ;
    if($pos > 0)
    {
        $birthday = substr($name, $pos + 1, strlen($name) - $pos -2);
        $name = substr($name, 0, $pos);
    }
    $memid = $row['memid'];
    $passwd = $row['password'];
    $phone_number = $row['profile_telno'];
    $email = $row['profile_email'];
    $company = $row['profile_company'];
    $address = $row['profile_address'];
    $friend = 'onlyone';
    $site = 'kiam';
    $profile_logo = "http://www.kiam.kr/iam/img/common/logo-2.png";
    $profile_self_info = $row['profile_self_info'];
    sendIamInvite($phone_number,$memid);
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    $name_count_sql="select count(idx) from Gn_Iam_Name_Card where card_short_url = '{$randomString}'";
    $name_count_result=mysqli_query($self_con,$name_count_sql);
    $name_count_row=mysqli_fetch_array($name_count_result);

    if((int)$name_count_row[0]) {
        generateRandomString();
    } else {
        return $randomString;
    }
}
function sendIamInvite($phoneNo, $memid)
{
    $rDay = date("Ymd");
    $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36';
    $header =array('Accept: application/json, text/plain, */*','Cache-Control: no-cache');
    $data = array('send_title'=>'아이엠 지원사업 안내',
                    'send_num'=>$phoneNo,
                    'send_rday'=>$rDay,
					'send_htime'=>0,
					'send_mtime'=>0,
                    'send_txt'=>'안녕하세요? 스마트샵 운영자님!이번에 저희 협회에서 네이버샵 운영자 대상으로 아이엠을 활용하여 샵을 홍보하는 시스템을 지원하고 있습니다. ' .
                                '아래 아이엠의 샘플링크를 클릭하면 아이엠이 어떤 모습인지 볼수 있습니다. 운영자님도 자신의 샵을 소개하는 아이엠을 만들고 싶다면 아래 절차를 따라 신청하시면 됩니다.'.
                                '** 샘플 아이엠 보기 http://kiam.kr/iam/sample.php'.
                                '[운영자님의 아이엠 만들기 절차]'.
                                '1. 내 아이엠자동생성하기'.
                                '운영자님의 샵에 노출된 공개정보를 크롤링하여 자동으로 운영자님의 샵에 대한 기본적인 아이엠이 생성됩니다.'.
                                '2. 내 아이엠 꾸미기 : 아래 샘플 아이엠처럼 멋지게 운영자님의 아이엠을 수정보완하시면 됩니다.'.
                                '3. 내 아이엠 홍보하기 : 내 아이엠 주소를 지인, 이메일, 블로그등으로 홍보하시면 됩니다.'.
                                '자!그러면 운영자님의 아이엠을 자동생성해보실래요?'.
                                '운영자님의 네이버샵에 노출된 사업자정보, 샵주소, 상품제목, 상품이미지 등을 크롤링하여 자동으로 생성하는 시스템입니다. '.
                                '생성후에 생성된 아이엠 링크 정보를 통해 운영자님의 아이엠을 확인하고 수정할수 있습니다. '.
                                '확인하고 나서 필요가 없다고 생각되면 취소를 통해 삭제할수 있습니다.'.
                                '[네이버 샵의 아이엠 자동생성하기]'.
                                'http://kiam.kr/admin/iam_auto_make_check.php?memid=' + $memid);
    print_r($data);
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_URL, 'http://kiam.kr/ajax/sendmmsPrc.2020.php');
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_REFERER, 'http://kiam.kr');
    // curl_setopt($ch, CURLOPT_COOKIEFILE, $conf['cookie']['named']);
    // curl_setopt($ch, CURLOPT_COOKIEJAR, $conf['cookie']['named']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $cr=@curl_exec($ch);
    $cerr=curl_error($ch);
    curl_close($ch);
    if($cerr)
        echo('Fail get named:'.$cerr);
    else
        echo 'send sms success';
}
//echo "<script>alert('아이엠이 자동생성되었습니다.');location='/admin/iam_auto_making_db.php';</script>";
exit;
?>
