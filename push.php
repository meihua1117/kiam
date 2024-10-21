#!/opt/php/bin/php
<?php
$url = 'https://fcm.googleapis.com/v1/projects/onepagebookmms5/messages:send';
require_once($_SERVER['DOCUMENT_ROOT'] . '/fcm/vendor/autoload.php');
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $_SERVER['DOCUMENT_ROOT'] . '/fcm/onepagebookmms5.json');
$scope = 'https://www.googleapis.com/auth/firebase.messaging';
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes($scope);
$auth_key = $client->fetchAccessTokenWithAssertion();
$headers = array(
    'Authorization: Bearer ' . $auth_key['access_token'],
    'Content-Type: application/json'
);

$id = 'd8FJwAWJQz2vWFkPrLS6X8:APA91bHOJNXQTTuZkjNVTWq5ilJfs8HXAc6PKlc_KpYymDefgQkOvHLBaI5zVDVap2fAyy4n4TUkefZp9JjruakEJ5mug1HN8CHgqKmVH3GF7MCujnFPmbRZ9Bg_uw_O_NIWqOUvs4ZB';

$title = '{"MMS Push"}';
$message = '{"Send":"Start","idx":"2759963","send_type":"7"}';
$fields = array(
    'data' => array(
        "body" => $message,
        "title" => $title
    )
);

if (is_array($id)) {
    $fields['registration_ids'] = $id;
} else {
    $fields['to'] = $id;
}
$fields['token'] = $id;
$fields['android'] = array("priority"=>"high");
$fields = json_encode(array('message' => $fields));
//$fields = http_build_query($fields);
$ch = curl_init();
//curl_setopt ( $ch, CURLOPT_URL, "https://nm.kiam.kr/fcm/send_fcm.php" );
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec($ch);
if ($result === FALSE) {
    echo 'FCM Send Error: ' . curl_error($ch);
}
curl_close($ch);
echo 'result:' . $result;
?>