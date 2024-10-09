#!/opt/php/bin/php
<?php
define("GOOGLE_SERVER_KEY", "AAAAmvl-uQA:APA91bHP4S4L8-nMvfOJ9vcjYlTmiRjEfOcLbAm6ITDFo9Ky-ziKAowlZi0rWhO3c7jsZ50unqWabQCBAmtr9bOxUIbwyAMgRsxO1jeLKlJ9l_Gir_wc1sZ66VBtHVBSjeAZcRfffVwo7M2fBvrrt1d5vz5clf7PVQ");

    
$url = 'https://fcm.googleapis.com/fcm/send';

$headers = array (
    'Authorization: key=' . GOOGLE_SERVER_KEY,
    'Content-Type: application/json'
);    
    
$id = 'd8FJwAWJQz2vWFkPrLS6X8:APA91bHOJNXQTTuZkjNVTWq5ilJfs8HXAc6PKlc_KpYymDefgQkOvHLBaI5zVDVap2fAyy4n4TUkefZp9JjruakEJ5mug1HN8CHgqKmVH3GF7MCujnFPmbRZ9Bg_uw_O_NIWqOUvs4ZB';
	
$title='{"MMS Push"}';
$message='{"Send":"Start","idx":"2759963","send_type":"7"}';
$fields = array ( 
    'data' => array (
                "body" => $message,
                "title" => $title)
);

if(is_array($id)) {
    $fields['registration_ids'] = $id;
} else {
    $fields['to'] = $id;
}
$fields['token'] = $id;

$fields['priority'] = "high";

//$fields = json_encode ($fields);
$fields = http_build_query($fields);
$ch = curl_init ();
curl_setopt ( $ch, CURLOPT_URL, "https://nm.kiam.kr/fcm/send_fcm.php" );
curl_setopt ( $ch, CURLOPT_POST, true );
//curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec ( $ch );
if ($result === FALSE) {
    echo 'FCM Send Error: ' . curl_error($ch);
} 
curl_close ( $ch );
echo 'result:' . $result; 


?>
