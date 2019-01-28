<?php

namespace App\Mail;

use Phalcon\Mvc\User\Component;
use PHPMailer\PHPMailer\PHPMailer;

class Mail extends Component
{


    public function send($to, $body)
    {
        $mail = new PHPMailer(true);
        try {

            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = '****';
            $mail->Password = '****';
            $mail->SMTPSecure = 'SSL';
            $mail->Port = 587;

            $mail->setFrom('****', 'Phalcon Register');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $mail->Subject = 'Регистрация на сайте';
            $mail->Body    = $body;


            $mail->send();
           // echo 'Сообщение отправлено';
            return true;
        } catch (Exception $e) {
            return false;
            echo 'Сообщение не отправлено. Ошибка: ', $mail->ErrorInfo;
        }
    }
}