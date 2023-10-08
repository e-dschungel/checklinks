<?php

$cl_config['linkcheckerPath'] = "linkchecker";
$cl_config['URLs'] = [
                        'https://www.google.com',
                        'https://www.example.com',
                     ];
$cl_config['subject'] = "Checklinks: Errors or warnings found";
$cl_config['body']   = "Errors or warning were found. See the attached report for details.";
$cl_config['emailTo']  = "receiver@example.com";
$cl_config['emailFrom']  = "sender@example.com";
$cl_config['emailBackend'] = "smtp";
$cl_config['SMTPHost'] = "example.com";
$cl_config['SMTPAuth'] = true;
$cl_config['SMTPUsername'] = "username";
$cl_config['SMTPPassword'] = "password";
$cl_config['SMTPSecurity'] = "smtps";
$cl_config['SMTPPort'] = 465;
