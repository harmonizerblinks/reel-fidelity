<?php

/*
 * Email Utilities. 
 * Uses PHPMailer library.
 * 
 */

require_once __DIR__ . '/phpmailer/PHPMailerAutoload.php';

class MailUtils {

    public static function quickSend(
    $to, $subject, $message, $from = null, $sender_name = null, $cc = null, $bcc = null, $attachments = array(), $is_html = true, $alt_message = null) {

        if (!$to) {
            throw new Exception('"To" email address not specified.');
        }

        $mail = new PHPMailer;

        if (AppConfig::MAIL_IS_SMTP) {
            $mail->isSMTP();
            $mail->Host = AppConfig::MAIL_SMTP_HOST;

            if (AppConfig::MAIL_SMTP_PORT) {
                $mail->Port = AppConfig::MAIL_SMTP_PORT;
            }

            $mail->SMTPAuth = AppConfig::MAIL_ENABLE_SMTP_AUTH;
            $mail->Username = AppConfig::MAIL_SMTP_USERNAME;
            $mail->Password = AppConfig::MAIL_SMTP_PASSWORD;

            if (AppConfig::MAIL_SMTP_SECURITY) {
                $mail->SMTPSecure = AppConfig::MAIL_SMTP_SECURITY;
            }
        }

        $mail->From = AppConfig::MAIL_SMTP_DEFAULT_FROM_EMAIL;
        $mail->FromName = AppConfig::MAIL_SMTP_DEFAULT_FROM_NAME;

        $tos = explode(',', $to);
        foreach ($tos as $_to) {
            $mail->addAddress($_to);
        }

        $mail->addReplyTo($from, $sender_name);

        $ccs = explode(',', $cc);
        foreach ($ccs as $_cc) {
            $mail->addCC($_cc);
        }

        $bccs = explode(',', $bcc);
        foreach ($bccs as $_bcc) {
            $mail->addBCC($_bcc);
        }

        $mail->WordWrap = 50;

        if ($attachments) {
            foreach ($attachments as $attachment) {
                if (!file_exists($attachment)) {
                    throw new Exception(sprintf('Attachment \'%s\' not found'
                            , $attachment));
                }
                $mail->addAttachment($attachment);
            }
        }

        $mail->isHTML($is_html);

        $mail->Subject = $subject;
        $mail->Body = $message;

        if ($alt_message) {
            $mail->AltBody = $alt_message;
        } else {
            $mail->AltBody = 'Your email software does not support HTML email.'
                    . ' Please use an HTML compatible email software to read'
                    . ' this email.';
        }

        if (!$mail->send()) {
            throw new Exception('Message could not be sent.'
            . ' Mailer Error: ' . $mail->ErrorInfo);
        }

        return true;
    }

}
