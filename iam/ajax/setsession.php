<?php
// $context = json_decode($_POST['context'] ?: "[]") ?: [];
$postData = array(
    "model" => "gpt-3.5-turbo",
    "temperature" => 0.3,
    "stream" => true,
    "messages" => array(),
);
if (!empty($context)) {
    $context = array_slice($context, -5);
    foreach ($context as $message) {
        $postData['messages'][] = array('role' => 'user', 'content' => str_replace("\n", "\\n", $message[0]));
        $postData['messages'][] = array('role' => 'assistant', 'content' => str_replace("\n", "\\n", $message[1]));
    }
}
$postData['messages'][] = array('role' => 'user', 'content' => $_POST['message']);
$postData = json_encode($postData);
if(isset($_SESSION) == false){ session_start(); }
$_SESSION['postdata'] = $postData;
if ((isset($_POST['key'])) && (!empty($_POST['key']))) {
    $_SESSION['apikey'] = $_POST['key'];
}
else{
    $_SESSION['apikey'] = '';
}
echo '{"success":true}';
?>