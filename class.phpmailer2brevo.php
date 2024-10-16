<?php


##require_once '/var/www/inc/spark/sparkpost.com';

class PHPMailer
{
    private $from = '';
    private $fromName = '';
    private $to = array();
    private $subject = '';
    private $body = '';
    private $altBody = '';
    private $attachment = array();

    // Maintain some of the original properties to prevent errors in existing code
    public $Priority = 3;
    public $CharSet = "iso-8859-1";
    public $ContentType = "text/plain";
    public $Encoding = "8bit";
    public $ErrorInfo = "";
    public $From = "root@localhost";
    public $FromName = "Root User";
    public $Sender = "";
    public $Subject = "";
    public $Body = "";
    public $AltBody = "";
    public $WordWrap = 0;
    public $Mailer = "mail";
    public $Sendmail = "/usr/sbin/sendmail";
    public $PluginDir = "";
    public $Version = "1.73";
    public $ConfirmReadingTo = "";
    public $Hostname = "";
    public $Host = "localhost";
    public $Port = 25;
    public $Helo = "";
    public $SMTPAuth = false;
    public $Username = "";
    public $Password = "";
    public $Timeout = 10;
    public $SMTPDebug = false;
    public $SMTPKeepAlive = false;

    public function SetFrom($address, $name = '') {
        $this->from = $address;
        $this->fromName = $name;
    }

    public function AddAddress($address, $name = '') {
        $this->to[] = array($address, $name);
    }

    public function Subject($subject) {
        $this->subject = $subject;
    }

    public function Body($body) {
        $this->body = $body;
    }

    public function AltBody($altBody) {
        $this->altBody = $altBody;
    }

    public function AddAttachment($path, $name = '') {
        $this->attachment[] = array($path, $name);
    }

    public function Send() {
        if (empty($this->to)) {
            $this->ErrorInfo = "No recipients set";
            return false;
        }

        $toEmail = $this->to[0][0];
        $toName = $this->to[0][1];

        $result = sendmail_spark(
            $this->from,
            $this->fromName,
            $toEmail,
            $toName,
            $this->subject,
            $this->altBody ?: $this->body,
            $this->body,
            $this->attachment
        );

        if (!$result) {
            $this->ErrorInfo = "Email sending failed";
            return false;
        }

        return true;
    }

    // Add stub methods for other PHPMailer methods to maintain compatibility
    public function IsHTML($ishtml = true) {}
    public function IsSMTP() {}
    public function IsQmail() {}
    public function IsSendmail() {}
    public function IsMail() {}
    public function AddCC($address, $name = '') {}
    public function AddBCC($address, $name = '') {}
    public function AddReplyTo($address, $name = '') {}
    public function SetLanguage($lang_type, $lang_path = '') {}
    public function AddCustomHeader($custom_header) {}
    // ... add other methods as needed
}




function sendmail_spark($from, $fromName, $toEmail, $toName, $subject, $altBody, $body, $attachment) {


    $json=json_encode(
        array(
        "sender"=>array(
            "name"=>$fromName,
            "email"=>$from
        ),
        "to"=>array(
            array(
                "email"=>$toEmail,
                "name"=>$toName
            )
        ),
        "subject"=>$subject,
        "textContent"=>$altBody,
        "htmlContent"=>$body,
        "attachment"=>$attachment
    ));


    shell_exec("curl --request POST \
     --url https://api.brevo.com/v3/smtp/email \
     --header 'accept: application/json' \
     --header 'api-key: xkeysib-........' \
     --header 'content-type: application/json' \
     --data '".$json."'");


}
