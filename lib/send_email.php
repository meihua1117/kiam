<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer의 autoload 파일 포함

$mail = new PHPMailer(true); // PHPMailer 인스턴스 생성

try {
    // SMTP 설정
    $mail->isSMTP();                                      // SMTP 사용 설정
    $mail->Host = 'smtp.cafe24.com';                     // Cafe24 SMTP 서버
    $mail->SMTPAuth = true;                               // SMTP 인증 사용
    $mail->Username = 'admin@ilearning11.cafe24.com';      // Cafe24 이메일 계정
    $mail->Password = '5614614a!@';             // 이메일 비밀번호
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // TLS 암호화
    $mail->Port = 587;                                   // 포트 번호
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL 암호화
    //$mail->Port = 465; // SSL 포트
    
    // 수신자 설정
    $mail->setFrom('admin@kiam.kr', 'onlyone');
    $mail->addAddress('jungjungjae0909@naver.com', 'jungjae'); // 수신자 추가
    //$mail->addAddress('meihua_1117@hotmail.com', 'meihua'); // 수신자 추가
    //$mail->addAddress('meihua20181221@gmail.com', 'meihua'); // 수신자 추가
    // 메일 내용 설정
    $mail->isHTML(true);                                  // HTML 메일 설정
    $mail->Subject = '메일 제목';
    $mail->Body    = '메일 내용 <b>굵게</b>';
    $mail->AltBody = '메일 내용 - 일반 텍스트 버전';

    // 메일 전송
    $mail->send();
    echo '메일이 발송되었습니다.';
} catch (Exception $e) {
    echo "메일 발송 실패: {$mail->ErrorInfo}";
}
?>