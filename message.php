<?php
include_once "../../lib/db_config.php";
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/event-stream");
header("X-Accel-Buffering: no");
// session_start();
$postData = $_SESSION['data'];
//echo $postData; exit;
$_SESSION['response'] = "";
$ch = curl_init();
$OPENAI_API_KEY = "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";
if (isset($_SESSION['key'])) {
    $OPENAI_API_KEY = $_SESSION['key'];
}
$headers  = array(
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer ' . $OPENAI_API_KEY
);
setcookie("errcode", ""); //EventSource无法获取错误信息，通过cookie传递
setcookie("errmsg", "");

function callback($ch, $data) {
    $complete = json_decode($data);
    if (isset($complete->error)) {
        setcookie("errcode", $complete->error->code);
        setcookie("errmsg", $data);
        if (strpos($complete->error->message, "Rate limit reached") === 0) { //访问频率超限错误返回的code为空，特殊处理一下
            setcookie("errcode", "rate_limit_reached");
        }
        if (strpos($complete->error->message, "Your access was terminated") === 0) { //违规使用，被封禁，特殊处理一下
            setcookie("errcode", "access_terminated");
        }
        if (strpos($complete->error->message, "You didn't provide an API key") === 0) { //未提供API-KEY
            setcookie("errcode", "no_api_key");
        }
        if (strpos($complete->error->message, "You exceeded your current quota") === 0) { //API-KEY余额不足
            setcookie("errcode", "insufficient_quota");
        }
        if (strpos($complete->error->message, "That model is currently overloaded") === 0) { //OpenAI服务器超负荷
            setcookie("errcode", "model_overloaded");
        }
    } else {
        echo $data;
        $_SESSION['response'] .= $data;
    }
    return strlen($data);
};
// $callback = callback($ch, $data);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_WRITEFUNCTION, callback);
//curl_setopt($ch, CURLOPT_PROXY, "http://127.0.0.1:1081");

curl_exec($ch);

$answer = "";
if (substr(trim($_SESSION['response']), -6) == "[DONE]") {
    $_SESSION['response'] = substr(trim($_SESSION['response']), 0, -6) . "{";
}
$responsearr = explode("}\n\ndata: {", $_SESSION['response']);

foreach ($responsearr as $msg) {
    $contentarr = json_decode("{" . trim($msg) . "}", true);
    if (isset($contentarr['choices'][0]['delta']['content'])) {
        $answer .= $contentarr['choices'][0]['delta']['content'];
    }
}

$questionarr = json_decode($_SESSION['data'], true);
// save_req_list($_POST['mem_id'], htmlspecialchars($_POST['message']), htmlspecialchars($text));
// $filecontent = $_SERVER["REMOTE_ADDR"] . " | " . date("Y-m-d H:i:s") . "\n";
// $filecontent .= "Q:" . end($questionarr['messages'])['content'] .  "\nA:" . trim($answer) . "\n----------------\n";
// $myfile = fopen(__DIR__ . "/chat.txt", "a") or die("Writing file failed.");
// fwrite($myfile, $filecontent);
// fclose($myfile);
curl_close($ch);

function save_req_list($mem_id, $msg, $res){
    $sql_insert = "insert into Gn_Gpt_Req_List set mem_id='{$mem_id}', gpt_question='{$msg}', gpt_answer='{$res}', reg_date=now()";
    mysqli_query($self_con,$sql_insert);
}
?>