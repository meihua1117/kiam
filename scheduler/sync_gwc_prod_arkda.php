#!/usr/bin/php
<?php
echo "arkda_get_start>>".date("Y-m-d H:i:s")."\n";
$currentDateTime = date('Ymd');

$fields['sync_data_arkda'] = "Y";
$fields['sync_date'] = $currentDateTime;
$url = "https://www.goodhow.com/crawler/crawler/index_arkda.php";
// $fields = json_encode ($fields);

$ch = curl_init ();
curl_setopt ( $ch, CURLOPT_URL, $url );
curl_setopt ( $ch, CURLOPT_POST, true );
// curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($fields) );
curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0); 
curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0);

$result = curl_exec ( $ch );
if ($result === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
}
curl_close ( $ch );
?>