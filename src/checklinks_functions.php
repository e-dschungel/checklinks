<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
Return output filename generated from URL.

@param $url URL

@return filename
 **/
function getOutputFilename($url)
{
    $filename = parse_url($url, PHP_URL_HOST);
    $filename = str_replace(".", "_", $filename);
    $filename = $filename . ".html";
    return $filename;
}

/**
Send mail with report attached.

@param $filename filename of file to attach
@param $config configuration array

@return void
 **/
function sendMail($filename, $config)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        switch (strtolower($config['emailBackend'])) {
            case "mail":
                $mail->isMail();
                break;
            case "smtp":
                $mail->isSMTP();
                $mail->Host       = $config['SMTPHost'];
                $mail->SMTPAuth   = $config['SMTPAuth'];
                $mail->Username   = $config['SMTPUsername'];
                $mail->Password   = $config['SMTPPassword'];
                switch (strtolower($config['SMTPSecurity'])) {
                    case "starttls":
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        break;
                    case "smtps":
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                        break;
                    default:
                        echo("Invalid config entry for SMTPSecurity {$config['SMTPSecurity']}\n");
                }
                $mail->Port       = $config['SMTPPort'];
                break;
            default:
                echo("Invalid config entry for emailBackend {$config['emailBackend']}\n");
        }

        //Recipients
        $mail->setFrom($config['emailFrom']);
        $mail->addAddress($config['emailTo']);

        //Attachments
        $mail->addAttachment("work/$filename");

        //Content
        $mail->Subject = $config['subject'];
        $mail->Body    = $config['body'];

        $mail->send();
        echo "Email has been sent";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
