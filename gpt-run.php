<?php

$api_key = "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";

function translate($content, $source='ko', $target='en') {
    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL,'https://www.googleapis.com/language/translate/v2');
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
    $data = array('key' => "API Key",
                    'q' => $content,
                    'source' => $source,
                    'target' => $target);
    curl_setopt($handle, CURLOPT_POSTFIELDS, preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', http_build_query($data)));
    curl_setopt($handle,CURLOPT_HTTPHEADER,array('X-HTTP-Method-Override: GET'));
    $response = curl_exec($handle);
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
$prompt = filter_var($_POST["prompt"], FILTER_SANITIZE_STRING);;

$data = array(
    "model" => "text-davinci-003",  
    "prompt" => $prompt,
    "max_tokens" => 3000,
    "temperature" => 0.5,
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

// echo $response;
?>
<form method="post" id="gpt-run" action="gpt-form.php" onsubmit="return true;" style="display:none;">
  <textarea id="response" name="response"><?php echo trim($response); ?></textarea>
  <textarea id="prompt" name="prompt"><?php echo $prompt; ?></textarea>
  <input type="submit" value="Submit">
</form>
<script>
    document.getElementById("gpt-run").submit();
</script>