#!/opt/php/bin/php
<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";

$headers = array('Accept: application/json, text/plain, */*', 'Cache_Control: no-cache', 'content-type:mutipart/form-data;');
date_default_timezone_set('Asia/Seoul');
$cur_time1 = date("Y-m-d H:i:s");
$cur_time = date("Y-m-d H:i");
echo $cur_time1."-------->>>>>>>>>>>".PHP_EOL;

$server = "https://www.goodhow.com/crawler/Crawler_bizinfo/";

$mem_phone = array();
$mem_stop_phone = array();
$s = 0;

$sql_reg = "select id, get_time, info_source, info_type, web_address, search_key, status from reg_biz_contents where work_status=1";
$res_reg = mysql_query($sql_reg);
while($row_reg = mysql_fetch_array($res_reg)){
    $time = $row_reg['get_time'];//시간
    $info_source = $row_reg['info_source'];//정보출처(기업마당, 나라장터...)
    $info_type = $row_reg['info_type'];//정보형태(지원사업, 행사교육...)
    $web_address = $row_reg['web_address'];//웹주소
    $search_key = $row_reg['search_key'];//검색키워드
    $status = $row_reg['status'];//진행중/종료된사업
    $reg_id = $row_reg['id'];

    switch($info_source){
        case "기업마당":
            if($info_type == "지원사업"){
                $url = $server."index_biz_jiwon.php";
            }
            else if ($info_type == "행사교육"){
                $url = $server."index_biz_edu";
            }
            break;
        case "나라장터": $url = $server."index_nara.php"; break;
        case "산림과학원": $url = $server."index_science.php"; break;
        case "산림청": $url = $server."inex_sanrim.php"; break;
        case "임업진흥원": $url = $server."index_imup.php"; break;
    }

    if(strpos($time, ",") !== false){
        $time_arr = explode(",", $time);
        for($i = 0; $i < count($time_arr); $i++){
            if($time_arr[$i] < 10){
                $time = "0".$time_arr[$i];
            }
            else{
                $time = $time_arr[$i];
            }
            $db_time = date("Y-m-d ").$time.":50";
            if($cur_time == $db_time){
                // $sql_insert = "insert into adsf set dasdf=100, namem='bizinfo', date='{$cur_time1}', update_id=100";
                // mysql_query($sql_insert);
                echo $db_time.PHP_EOL;
                echo $url;
                send_request($web_address, $info_type, $search_key, $status, $headers, $port, $url, $reg_id);
            }
        }
    }
    else{
        if($time < 10){
            $time = "0".$time;
        }
        $db_time = date("Y-m-d ").$time.":00";
        if($cur_time == $db_time){
            // $sql_insert = "insert into adsf set dasdf=100, namem='bizinfo', date='{$cur_time1}', update_id=100";
            // mysql_query($sql_insert);
            echo $db_time.PHP_EOL;
            echo $url;
            send_request($web_address, $info_type, $search_key, $status, $headers, $port, $url, $reg_id);
        }
    }
}

function send_request($address, $info_type, $search_key, $status, $header, $port, $link, $regid){
    $ch = curl_init();//크롤링 요청 보내기
    
    $fields['address'] = $address;
    $fields['info_type'] = $info_type;
    $fields['search_key'] = $search_key;
    $fields['status'] = $status;
    $fields['regid'] = $regid;
    
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_PORT, $port);
    curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $kresult = curl_exec($ch);
    
    echo $kresult."----------->>>>>>>>>>>>>>".PHP_EOL;
    
    if($kresult === FALSE){
	die('FCM Send Error: '.curl_error($ch));
    }
    curl_close($ch);
}
?>