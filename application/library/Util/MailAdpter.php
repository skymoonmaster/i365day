<?php

Class Util_MailAdpter {

    /**
     * @var unknown_type
     */
    private static $instance;

    /**
     * 
     * @return Util_MailAdpter
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Util_MailAdpter();
        }
        return self::$instance;
    }

    public function sendMail($from, $fromname, $to, $subject, $content, $attachments, $ccList = array(), $bccList = array()) {
        $mail = new Util_PHPMailer();
        //set mailer to use SMTP
        $mail->IsSMTP();
        //specify main and backup server
        $mail->Host = SMTP_SERVER;
        $mail->Port = 25;
        //turn on SMTP authentication
        //$mail->SMTPAuth = true;
        //SMTP username
        $mail->Username = "gccn";
        //SMTP password
        $mail->Password = "@ctive987";
        $mail->From = $from;
        $mail->FromName = $fromname;

        $mail->Body = "$content";
        // set email format to HTML
        $mail->IsHTML(true);
        $mail->AddAddress($to);

        if (is_array($ccList) && count($ccList) > 0) {
            foreach ($ccList as $cc) {
                $mail->AddCC($cc);
            }
        }
        if (is_array($bccList) && count($bccList) > 0) {
            foreach ($bccList as $bcc) {
                $mail->AddBCC($bcc);
            }
        }
        $mail->Subject = $subject;

        $mail->Body = $content;
        if (is_array($attachments) && count($attachments) != 0) {
            foreach ($attachments as $attachment) {
                $mail->AddAttachment($attachment);
            }
        }
        $state = $mail->Send();
        $mail->ClearAddresses();
        $mail->ClearAttachments();
        if (!$state) {
            Util_CLog::warning($mail->ErrorInfo);
            return false;
        } else {
            return true;
        }
    }

}

/* vim: set ts=4 sw=4 sts=4 tw=90 noet: */
?>
