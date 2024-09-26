<?php
// 다운로드할 이미지 파일 경로 설정
$file_path = base64_decode($_GET['id']); // 실제 이미지 파일 경로로 변경하세요.
$pos = strpos($file_path,"wm.");
$ext = substr($file_path,$pos + 2);
$file_path = "..".str_replace("wm.",".",$file_path);
$file_name = date("YmdHis").$ext; // 다운로드될 파일 이름
// 파일이 존재하는지 확인
if (file_exists($file_path)) {
    // 파일 크기 가져오기
    $file_size = filesize($file_path);

    // HTTP 헤더 설정
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $file_size);
    // 파일을 출력하여 다운로드 시작
    readfile($file_path);
    
    exit;
} else {
    // 파일이 존재하지 않을 때 에러 메시지 출력
    echo 'no file exist.';
}
?>
