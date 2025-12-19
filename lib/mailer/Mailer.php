<?php
// lib/mailer/Mailer.php

declare(strict_types=1);

require_once __DIR__ . '/PHPMailer.php';
require_once __DIR__ . '/SMTP.php';
require_once __DIR__ . '/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private array $smtpOptions;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->host     = 'smtp.office365.com';
        $this->port     = 587;
        $this->smtpOptions = [
            'ssl' => [
                'verify_peer'       => true,
                'verify_peer_name'  => true,
                'allow_self_signed' => false,
                'crypto_method'     => STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT,
            ]
        ];
    }

    /**
     * $params = [
     *   fromEmail, fromName (옵션),
     *   to => 'a@b.com' or ['a@b.com','c@d.com'],
     *   subject => '',
     *   body => '',
     *   isHtml => true/false,
     *   cc => [...], bcc => [...],
     *   attachments => [ ['path'=>'/tmp/x.pdf','name'=>'file.pdf'] , ... ]
     * ]
     *
     * returns: ['ok'=>bool, 'error'=>string|null]
     */
    public function send(array $params): array
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = $this->host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->username;
            $mail->Password   = $this->password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $this->port;
            $mail->SMTPOptions = $this->smtpOptions;
            $mail->CharSet    = 'UTF-8';

            // from
            $fromEmail = $params['fromEmail'] ?? $this->username;
            $fromName  = $params['fromName']  ?? $params['fromEmail']  ?? $this->username;
            $mail->setFrom($fromEmail, $fromName);

            // to (단일 또는 배열)
            $tos = is_array($params['to']) ? $params['to'] : [$params['to'] ?? ''];
            foreach ($tos as $t) {
                if ($t) $mail->addAddress($t);
            }

            // replyTo
            if (!empty($params['replyTo'])) {
                $mail->addReplyTo($params['replyTo']);
            }

            // cc / bcc
            if (!empty($params['cc']) && is_array($params['cc'])) {
                foreach ($params['cc'] as $c) { if ($c) $mail->addCC($c); }
            }
            if (!empty($params['bcc']) && is_array($params['bcc'])) {
                foreach ($params['bcc'] as $b) { if ($b) $mail->addBCC($b); }
            }

            // attachments
            if (!empty($params['attachments']) && is_array($params['attachments'])) {
                foreach ($params['attachments'] as $att) {
                    if (isset($att['path']) && file_exists($att['path'])) {
                        $name = $att['name'] ?? basename($att['path']);
                        $mail->addAttachment($att['path'], $name);
                    }
                }
            }

            $mail->Subject = $params['subject'] ?? '(no subject)';
            $mail->isHTML(true);
            
            if (is_array($params['body'])) {
                $mail->Body = implode('', $params['body']);
            } else {
                $mail->Body = $params['body'] ?? '';
            }
            
            $mail->AltBody = $params['altBody'] ?? strip_tags($mail->Body);

            // 디버그 모드 설정
            if (!empty($params['debug'])) {
                $mail->SMTPDebug = (int)$params['debug'];
                $mail->Debugoutput = function($str, $level) {
                    echo $str;
                };
            }

            $mail->send();
            return ['ok' => true, 'error' => null];

        } catch (Exception $e) {
            error_log('[MAILER ERROR] ' . $e->getMessage());
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }
}

