<?php
// header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";
//로그인 id pw
//$check1 = sql_password("온리원");
if($_REQUEST['key'] == "7dff7d66f0ca175b15a23fb4d7e09429") {
    $sql = "select * from Gn_Service where status='Y' order by idx asc";
    $result=mysqli_query($self_con, $sql);
    $data["result"] = "0";
    while($row=mysqli_fetch_array($result)) {
        $data["data"][] = $row;
    }
    echo '{"result":"0","data":[{"0":"1","idx":"1","1":"http:\/\/kiam.kr","domain":"http:\/\/kiam.kr","2":"http:\/\/center.kiam.kr","sub_domain":"http:\/\/center.kiam.kr","3":"om","service_name":"om","4":"2019-\uacbd\uae30\uad11\uba85-0162","communications_vendors":"2019-\uacbd\uae30\uad11\uba85-0162","5":"\uc1a1\uc870\uc740","privacy":"\uc1a1\uc870\uc740","6":"\uc628\ub9ac\uc6d0\ub9c8\ucf00\ud305\uc13c\ud130","company_name":"\uc628\ub9ac\uc6d0\ub9c8\ucf00\ud305\uc13c\ud130","7":"\uc628\ub9ac\uc6d0\ub9c8\ucf00\ud305\uc13c\ud130","site_name":"\uc628\ub9ac\uc6d0\ub9c8\ucf00\ud305\uc13c\ud130","8":"2019-06-01","contract_start_date":"2019-06-01","9":"2019-06-01","contract_end_date":"2019-06-01","10":"only1m","mem_id":"only1m","11":"\uc1a1\uc870\uc740","mem_name":"\uc1a1\uc870\uc740","12":"\uae40\uc218\uc9c4 \uc774\uc0ac","manage_name":"\uae40\uc218\uc9c4 \uc774\uc0ac","13":"070-4477-6630","manage_cell":"070-4477-6630","14":"","fax":"","15":"Y","status":"Y","16":"\uc1a1\uc870\uc740 \ub300\ud45c","ceo_name":"\uc1a1\uc870\uc740 \ub300\ud45c","17":"\uacbd\uae30\ub3c4 \uad11\uba85\uc2dc \uac00\ub9c8\uc0b0\ub85c 7 \uac00\ub3d9\uc0c1\uac00 120\ud638","address":"\uacbd\uae30\ub3c4 \uad11\uba85\uc2dc \uac00\ub9c8\uc0b0\ub85c 7 \uac00\ub3d9\uc0c1\uac00 120\ud638","18":"\/data\/site\/1561695687_0967.png","logo":"\/data\/site\/1561695687_0967.png","19":null,"main_image":null,"20":null,"footer_image":null,"21":"","phone_cnt":"","22":"","branch_rate":"","23":"","branch_type":"","24":"","price":"","25":"10000000","member_cnt":"10000000","26":"2020-02-24 13:20:21","regdate":"2020-02-24 13:20:21","27":"Y","main_default_yn":"Y","28":"","naver-site-verification":"","29":"","keywords":"","30":"http:\/\/pf.kakao.com\/_jVafC\/chat","kakao":"http:\/\/pf.kakao.com\/_jVafC\/chat"}]}';
    //echo json_encode($data);
} else {
    echo "{\"result\":\"-1\",\"data\":\"\"}";
}

?>