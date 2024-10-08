<?
$url = 'https://fcm.googleapis.com/v1/projects/onepagebookmms5/messages:send';
require_once('./vendor/autoload.php');
putenv('GOOGLE_APPLICATION_CREDENTIALS=./onepagebookmms5.json');
$scope = 'https://www.googleapis.com/auth/firebase.messaging';
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes($scope);
$auth_key = $client->fetchAccessTokenWithAssertion();
echo $auth_key['access_token'];
$ch = curl_init();
//header 설정 후 삽입
$headers = array(
    'Authorization: Bearer ' . $auth_key['access_token'],
    'Content-Type: application/json'
);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$title = $_POST['data']['title'];
$message = $_POST['data']['body'];
$priority = $_POST['priority'];
//$notification_opt =
$datas =  array(
    'title'         => $title,
    'body'          => $message
    // 'image' => 'http://sowonbyul.com/original/totalAdmin/images/Icon-512.png'
);
//$datas = $_POST['data'];
/*$android_opt = array (
                    'notification' => array(
                	'default_sound'         => true
            	    ),
            	    'priority' => $priority,
            	    'data'=>$datas
        	);*/
$android_opt = array(
    'priority' => $priority
);
$token = $_POST['token'];
$message = array(
    'token' => $token,
    'data' => $datas,
    'android' => $android_opt
    //'notification' => $notification_opt,
);
$last_msg = array(
    "message" => $message
);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($last_msg));
$result = curl_exec($ch);
if ($result === FALSE) {
    // die('FCM Send Error: ' . curl_error($ch));
    printf(
        "cUrl error (#%d): %s<br>\n",
        curl_errno($ch),
        htmlspecialchars(curl_error($ch))
    );
} else {
    /*$fp = fopen("test.log","a+");
	fwrite($fp,$result."\r\n");
	fwrite($fp,"token=".$token."\r\n");
	fwrite($fp,"title=".$title."\r\n");
	fwrite($fp,"body=".var_export($message,true)."\r\n");
	fclose($fp);*/
}
echo json_encode(array("result" => $result));
