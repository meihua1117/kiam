<?php

header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$profile = $_REQUEST["box_text"];
// $profile = 'A. 서울특별시 동작구 노량진로 186
// E. hyeongil77@naver.com
// 좋은사람들모임(조사모) 회장
// 영화배우협회 부이사장
// M. 010 7765 1358
// Chief Director / Kim hyeong il
// 이사장/김 형 일
// HAPPINESS';

$prompt = GetPrompt("https://www.kiam.kr/api/dber_get_value_for_key.php", "gpt_extract_profile_info");
if($prompt == "" || $prompt == null)
{
    $prompt = "위의 [명함 텍스트]를 분석하여 아래의 항목을 추출하여 제이슨 형태로 정리하는데 키값은 괄호안의 문자열로 해줘.
    - 명함이름(name) : 명함텍스트에서 이름이 드러날경우 찾는다. 
    - 직책(job) : 이름 전 후에 보여지는 직함이 있으면 찾는다.
    - 기관명(org_name) : 명함텍스트에서 업체, 단체, 회사명을 찾는다.
    - 주소(address) : 명함 텍스트에 시군구 읍면동이 포함된 정보가 있을 경우 주소에 입력한다.  (예시 : 수원시, 서울시, 강남구)
    - 전화1(phone1) : 전화번호중에 지역 전화번호를  찾는다.
    - 전화2(phon2) : 전화번호중에 지역번호가 2개 있을 경우에 찾는다.
    - 휴대폰(mobile) : 폰번호중에 010 번호를 찾는다. 
    - 팩스(fax) : 팩스 전화번호를 찾는다. 
    - 이멜1(email1) : 명함 텍스트에 이메일이 있을 경우 찾는다.
    - 이멜2(email2) : 명함 텍스트에 두개의 이메일이 있을 경우 찾는다.
    - 메모(memo) : 명함 텍스트에 있는 정보 중에 위의 항목외에 필요한 정보가 있을 경우 메모에 입력한다.";
}

// 예시 프롬프트
$request = 
"[명함 텍스트 예시]
$profile
[프롬프트] 
$prompt
";

// GPT-3 응답 받기
$responseData = getGptResponse($request);
echo $responseData;

function GetPrompt($url, $key)
{

    // 전송할 데이터 배열
    $data = array(
    'key' => $key
    );

    // URL-encoded 쿼리 문자열로 변환
    $postFields = http_build_query($data);

    // cURL 세션 초기화
    $ch = curl_init($url);

    // cURL 옵션 설정
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

    // 요청 실행 및 응답 받기
    $response = curl_exec($ch);

    // 오류 체크
    if (curl_errno($ch)) {
        //echo 'cURL error: ' . curl_error($ch);
        return "";
    } 
    // cURL 세션 닫기
    curl_close($ch); 

    $jsonData = json_decode($response);
    return $jsonData->value;
}

function getGptResponse($prompt, $model = "gpt-4o", $maxTokens = 200) {
    $apiKey = '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';
    $url = 'https://api.openai.com/v1/chat/completions';
    
    // cURL 초기화
    $ch = curl_init($url);
    
    // ChatGPT 모델은 messages 배열을 사용합니다.
    $messages = array(
    array('role' => 'system', 'content' => 'You are a helpful assistant.'),
    array('role' => 'user', 'content' => $prompt)
    );
    
    // 요청 데이터 설정
    $data = array(
    'model' => $model,
    'messages' => $messages,
    'max_tokens' => $maxTokens,
    'temperature' => 0,
    'n' => 1
    );
    
    // cURL 옵션 설정
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    // 요청 실행 및 응답 받기
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
    return json_encode(array('error' => curl_error($ch)), JSON_PRETTY_PRINT);
    }
    
    curl_close($ch);
    
    // 응답을 JSON 형식으로 반환
    return json_encode(json_decode($response), JSON_PRETTY_PRINT);
    }

?>