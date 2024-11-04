<?php 
$cur_data = 'A. 서울특별시 동작구 노량진로 186
        // E. hyeongil77@naver.com
        // 좋은사람들모임(조사모) 회장
        // 영화배우협회 부이사장
        // M. 010 7765 1358
        // Chief Director / Kim hyeong il
        // 이사장/김 형 일
        // HAPPINESS';

$cur_url = "http://localhost:8080/get_profile_info.php";
$cur_url = "http://aiserver.kiam.kr:8080/get_profile_info.php";
//$cur_url = "https://www.kiam.kr/api/get_profile_info.php";
$postvars = 'box_text='. $cur_data;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $cur_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);

$response = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close ($ch);

if($status_code == 200) {

$response = str_replace("```", "", $response);
$response = str_replace("json", "", $response);    
  
$responseArray = json_decode($response, true);

if (isset($responseArray['choices'][0]['message']['content'])) {
    $jsonData = json_decode($responseArray['choices'][0]['message']['content'], true);
    //var_dump($jsonData);
    if (is_array($jsonData)) {
        foreach ($jsonData as $key => $value) {
            if(is_array($value))
            {
                $result = implode(", ", $value) ;
                echo "$key: $result" . "<br/>";
            }else{
                echo "$key: $value" . "<br/>";
            }

        }
    } else {
        echo "The provided data is not an array.";
    }
}
else{
    echo $response;
}
} else {
  echo "Error:".$response;
}

?>