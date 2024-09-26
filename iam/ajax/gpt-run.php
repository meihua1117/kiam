<?php
header('Content-Type: text/html; charset=utf-8');
$api_key = "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";
// $api_key = "AIzaSyBOti4mM-6x9WDnZIjIeyEU21OpBXqWBgw";

function translate($content, $source='ko', $target='en') {
    $handle = curl_init();

    if (FALSE === $handle)
    throw new Exception('failed to initialize');

    curl_setopt($handle, CURLOPT_URL,'http://www.goodhow.com:81/translate.php');
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($handle, CURLOPT_POSTFIELDS, array('q' => $content, 'source' => $source, 'target' => $target));
    curl_setopt($handle,CURLOPT_HTTPHEADER,array('X-HTTP-Method-Override: GET'));
    $response = curl_exec($handle);

    /*curl_setopt($handle, CURLOPT_URL,'https://www.googleapis.com/language/translate/v2');
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
    $data = array('key' => $api_key,
                    'q' => $content,
                    'source' => $source,
                    'target' => $target);
    curl_setopt($handle, CURLOPT_POSTFIELDS, preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', http_build_query($data)));
    curl_setopt($handle,CURLOPT_HTTPHEADER,array('X-HTTP-Method-Override: GET'));
    $response = curl_exec($handle);*/
    $responseDecoded = json_decode($response, true);
    $responseCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);
    return  $responseDecoded['data']['translations'][0]['translatedText'];
}

function ko2en($content) {
    return translate($content, 'ko', 'en');
}

function en2ko($content) {
    return translate($content, 'en', 'ko');
}

$url = "https://api.openai.com/v1/completions";

// "What is the capital of France?"
$prompt = filter_var($_POST["prompt"], FILTER_SANITIZE_STRING);
$prompt = ko2en($prompt);

$data = array(
    "model" => "text-davinci-003",  
    "prompt" => $prompt,
    "max_tokens" => 3000,
    "temperature" => 0.5,
    //"stream" => true,
);

$data_string = json_encode($data);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Authorization: Bearer $api_key",
    "Content-Length: " . strlen($data_string))
);

$output = curl_exec($ch);
curl_close($ch);
// print_r($output);

$output_json = json_decode($output, true);
$response = $output_json["choices"][0]["text"];
$response = en2ko(trim($response));

$response = str_replace('다.', '다.<br>', $response);
$response = str_replace('오.', '오.<br>', $response);
$response = str_replace('요.', '요.<br>', $response);

$str_res = "";
$res_arr = explode('<br>', $response);
for($i = 0; $i < count($res_arr); $i++){
    $str_res .= '<span style="margin-left: 25px;">'.$res_arr[$i].'</span>';
}

$str = '<span style="border-bottom: 1px solid;line-height: 30px;font-size: 17px;"><span style="background-color: red;color: white;padding: 3px 7px;margin-right: 7px;">Q</span> '.$_POST["prompt"].'</span><br><br>
<span style="display: inline-grid;"><span style="background-color: red;color: white;padding: 3px 7px;width: 25px;">A</span> '.$str_res.'</span><br><br>';

echo $str;
?>