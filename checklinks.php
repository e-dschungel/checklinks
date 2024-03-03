<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/checklinks_functions.php';
require __DIR__ . '/config/config.inc.php';

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

header('Content-Type: text/plain; charset=utf-8');

if (!isset($cl_config)) {
    throw new Exception('$cl_config is not set');
}

foreach ($cl_config["URLs"] as $url) {
    echo "Checking $url \n";
    $configFile = "config/" . getConfigFilename($url);
    $command = array_merge(
        [$cl_config['linkcheckerPath']],
        ["--config=" . $configFile],
        ["--file-output=html/utf-8/" . getOutputFilePath($url), $url]
    );
    $process = new Process($command);
    $process->setTimeout(3600);
    $process->run(
        function ($type, $buffer) {
            echo $buffer;
            flush();
            ob_flush();
        }
    );

    switch ($process->getExitCode()) {
        case 0:
            echo "No errors or warnings found!\n";
            break;
        case 1:
            echo "Warnings or errors found while checking $url, mailing report to " . $cl_config['emailTo'] . "\n";
            sendMail(getOutputFilePath($url), $cl_config);
            break;
        case 2:
            echo "Run failed:\n";
            echo $process->getErrorOutput();
            break;
    }
    //remove output file
    if (is_file(getOutputFilePath($url))) {
        unlink(getOutputFilePath($url));
    }
}
