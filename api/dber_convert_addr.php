<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
set_time_limit(0);
ini_set('memory_limit','-1');

// $addr = $_REQUEST["addr"];
// $addr = '충남 천안시 서북구 입장면 입장로 144-1';
// echo GetAPIResponse($addr);
$end_idx = 10;

$query = "select address,seq from crawler_data_supply where data_type='지도' and seq < $end_idx order by seq";
$result = mysql_query($query);
while($row=mysql_fetch_array($result)) {
    if($row['address'] != "")
    {
        $region = GetAPIResponse($row['address']);
        if($region != ""){
            $query = "update crawler_data_supply set region='$region' where seq=$row[seq]";
            mysql_query($query);
        }
    }
}
echo 'Done';

function GetAPIResponse($addr)
{

    // 요청할 URL
    $url = 'https://www.juso.go.kr/addrlink/addrLinkApi.do';
    $apiKey = 'devU01TX0FVVEgyMDI0MTAzMTExMDIzMzExNTIwNTA=';

    // 전달할 파라미터들
    $params = array(
    'confmKey' => $apiKey,
    'keyword' => $addr,
    'resultType' => 'json'
    );

    // URL에 파라미터 추가
    $url .= '?' . http_build_query($params);

    // curl 초기화
    $ch = curl_init();

    // curl 옵션 설정
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL 인증서의 유효성 검사 비활성화
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // 호스트의 SSL 인증서가 유효한지 확인하지 않음

    // 요청 보내기
    $response = curl_exec($ch);

    // curl 세션 종료
    curl_close($ch);

   // JSON 응답 처리
    if ($response) {
        $data = json_decode($response, true);        
        if ($data) {
            // JSON 데이터가 올바르게 해석되었을 때 처리할 내용
            if (isset($data['results']['juso'])) {
                $emdNm = $data['results']['juso'][0]['siNm'] . " " . $data['results']['juso'][0]['sggNm'] . " " . $data['results']['juso'][0]['emdNm'];
                return $emdNm;
            } 

        }
    }
    return "";
}


?>