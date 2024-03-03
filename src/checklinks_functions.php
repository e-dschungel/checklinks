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
Return path + filename for outputfile generated from URL.

@param $url URL

@return path + filename
 **/
function getOutputFilePath($url)
{
    return("work/" . getOutputFilename($url));
}

/**
Return config filename generated from URL.

@param $url URL

@return filename
 **/
function getConfigFilename($url)
{
    $filename = parse_url($url, PHP_URL_HOST);
    $filename = str_replace(".", "_", $filename);
    $filename = $filename . ".conf";
    return $filename;
}

/**
Copy content of given file to file handle

@param $fp file handle to write to
@param $path path of file to copy

@return void
 **/
function copyFileIfExisting($fp, $path)
{
    if (file_exists($path)) {
        fwrite($fp, file_get_contents($path));
    }
}

/**
Merge $sharedConfig (for all URLs) and specific config for $url to a given file ($mergedConfig)

@param $sharedConfig config shared for all URLs
@param $url URL for which specific config is requested
@param $mergedConfig file name to store merged config

@return void
 **/
function mergeConfigFiles($sharedConfig, $url, $mergedConfig)
{
    $specificConfig = getConfigFilename($url);
    $fp = fopen($mergedConfig, "w");
    copyFileIfExisting($fp, $sharedConfig);
    copyFileIfExisting($fp, dirname($sharedConfig) . "/" . $specificConfig);
    fclose($fp);
}

/**
Send mail with report attached.

@param $attachmentPath path to file to attach
@param $config configuration array

@return void
 **/
function sendMail($attachmentPath, $config)
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
        $mail->addAttachment($attachmentPath);

        //Content
        $mail->Subject = $config['subject'];
        $mail->Body    = $config['body'];

        $mail->send();
    } catch (Exception $e) {
        echo "Report could not be sent. \n";
        echo "Mailer Error: {$mail->ErrorInfo}\n";
    }
}
