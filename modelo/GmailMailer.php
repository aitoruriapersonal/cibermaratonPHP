<?php
// filepath: c:\TodoDesarrollo\proyectos\php\cibermaratonPHP\util\GmailMailer.php

/*use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Asegúrate de tener PHPMailer instalado vía Composer:
// composer require phpmailer/phpmailer

require_once __DIR__ . '/../../vendor/autoload.php';

class GmailMailer
{
    private PHPMailer $mail;

    public function __construct(string $gmailUser, string $gmailPassword, string $fromName = '')
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $gmailUser;
        $this->mail->Password = $gmailPassword;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = 587;
        $this->mail->CharSet = 'UTF-8';
        $this->mail->setFrom($gmailUser, $fromName ?: $gmailUser);
    }

    public function send(string $to, string $subject, string $body, bool $isHtml = true): bool
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->isHTML($isHtml);
            return $this->mail->send();
        } catch (Exception $e) {
            // Puedes registrar el error si lo deseas
            return false;
        }
    }
}*/
?>