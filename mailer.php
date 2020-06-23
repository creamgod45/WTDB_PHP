<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                                                   // DEBUG INFO
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                                                // 設置 SMTP 主機位置
    $mail->Host       = '';                                                            // 設置 SMTP 服务器进行发送
    $mail->SMTPAuth   = true;                                                          // 設置 SMTP 授權
    $mail->CharSet    = 'UTF-8';                                                       // 設置 SMTP 轉碼至 UTF8
    $mail->Username   = "";                                                            // 設置 SMTP 帳號
    $mail->Password   = "";                                                            // 設置 SMTP 密碼
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                                // 設置 SMTP 安全協定
    $mail->Port       = 587;                                                           // 設置 SMTP TCP 端口
    
    $mail->setFrom(, $FORM);
    $mail->addAddress($email, $nickname);
    $mail->isHTML(true);
    $mail->Subject = $SUBJECT;
    $mail->Body    = $HTML;
    $mail->AltBody = $ALTHTML;

    $query = $mail->send();
    
    if($query){
        echo 'Message has been sent';
    }
} catch (Exception $e) {
    echo "\nMessage could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>