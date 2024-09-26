<?php
  // 네이버 Papago NMT 기계번역 Open API 예제
  $client_id = "mOvs7EMZ1a3mJpSreosj"; // 네이버 개발자센터에서 발급받은 CLIENT ID
  $client_secret = "MGRowWovyN";// 네이버 개발자센터에서 발급받은 CLIENT SECRET
  $myfile = fopen("iam/index.php", "r") or die("Unable to open file!");
  $encText = fread($myfile,filesize("iam/index.php"));
  fclose($myfile);
  //echo $encText;
  //exit;
  //$encText = urlencode("만나서 반갑습니다.");
  $postvars = "source=ko&target=en&text=".$encText;
  $url = "https://openapi.naver.com/v1/papago/n2mt";
  $is_post = true;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, $is_post);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
  $headers = array();
  $headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
  $headers[] = "X-Naver-Client-Id: ".$client_id;
  $headers[] = "X-Naver-Client-Secret: ".$client_secret;
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec ($ch);
  $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  echo "status_code:".$status_code."<br>";
  curl_close ($ch);
  if($status_code == 200) {
    echo $response;
  } else {
    echo "Error:".$response;
  }
?>