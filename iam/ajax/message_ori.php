<?php
include_once "../../lib/db_config.php";
header( "Access-Control-Allow-Origin: *" );
header( "Content-Type: application/json" );
// $context = json_decode( $_POST['context'] ?: "[]" ) ?: [];
$context = json_decode($_POST['context']);
// Initialisierung der Modellparameter
$postData = array(
    "model" => "gpt-3.5-turbo",
    "messages" => array(),
    "temperature" => 0.3,
    "presence_penalty" => 0,
    "frequency_penalty" => 1.2,
);
if( !empty( $context ) ) {
    $context = array_slice( $context, -5 );
    foreach( $context as $message ) {
        $postData['messages'][] = array('role' => 'user', 'content' => str_replace("\n", "\\n", $message[0]));
        $postData['messages'][] = array('role' => 'assistant', 'content' => str_replace("\n", "\\n", $message[1]));
    }
}
$postData['messages'][] = array('role' => 'user', 'content' => $_POST['message']);

$ch = curl_init();
$OPENAI_API_KEY = "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";
$headers  = array(
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer ' . $OPENAI_API_KEY . ''
);

$postData = json_encode($postData);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

$result = curl_exec($ch);
$complete = json_decode($result);

if( isset( $complete->choices[0]->message->content ) ) {
    $text = trim(str_replace( "\\n", "\n", $complete->choices[0]->message->content ),"\n");
    if($_POST['message'] == "안녕" && strpos($text, "하세요! 저는 AI 어시스턴트입니다.") !== false){
        $text = str_replace("하세요!", "안녕하세요!", $text);
    }
    save_req_list($_POST['mem_id'], htmlspecialchars($_POST['message']), htmlspecialchars($text));
} elseif( isset( $complete->error->message ) ) {
    $text = "서버에서 오류 메시지를 반환합니다.".$complete->error->message;
} else {
    $text = "서버가 중단되거나 예외 메시지를 반환합니다.";
}

echo json_encode( array(
     "message" => $text,
     "raw_message" => $text,
     "status" => "success",
 ) );

function save_req_list($mem_id, $msg, $res){
    $sql_insert = "insert into Gn_Gpt_Req_List set mem_id='{$mem_id}', gpt_question='{$msg}', gpt_answer='{$res}', reg_date=now()";
    mysqli_query($self_con, $sql_insert);
}
?>
