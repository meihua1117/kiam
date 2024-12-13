#!/usr/bin/php
<?php
set_time_limit(0);
ini_set('memory_limit', '-1');

include_once "/home/kiam/lib/db_config.php";

$query = "start transaction";
$result = mysqli_query($self_con, $query);

//주소변환이 실패한 주소를 찾아 다시 변환을 진행한다.
$query = "select address,seq from crawler_data_map where region IS NULL or region=''";
$result = mysqli_query($self_con, $query);
while ($row = mysqli_fetch_array($result)) {
    $address = $row['address'];
    if ($address != "") {
        while (true) {
            $lastSpacePosition = strrpos($address, ' ');
            if ($lastSpacePosition == false)
                break;

            $address = substr($address, 0, $lastSpacePosition);
            $region = trim(GetAPIResponse($address));
            //echo $row['seq'] . " ".$address. " => ". $region . "\n";
            //flush(); // 출력 버퍼를 비워서 즉시 출력
            if (substr_count($region, ' ') > 1) {
                $query = "update crawler_data_map set region='{$region}' where seq={$row['seq']}";
                mysqli_query($self_con, $query);
                break;
            }
        }
    }
}

$query = "commit";
$result = mysqli_query($self_con, $query);

echo 'Done';

function GetAPIResponse($addr)
{

    // 요청할 URL
    $url = 'https://www.juso.go.kr/addrlink/addrLinkApi.do';
    $apiKey = 'devU01TX0FVVEgyMDI0MTEwODA5MDQ1NzExNTIyMTM=';

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