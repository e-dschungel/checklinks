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
    $process = new Process(
        [
        "/www/htdocs/w00a194a/.local/bin/linkchecker",
        "--check-extern",
        "--file-output=html/utf-8/work/" . getOutputFilename($url),
        $url
        ],
    );

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
            echo "Warnings or errors found, report available at https:/admin.e-dschungel.de/checklinks/work/";
            echo getOutputFilename($url) . "\n";
            sendMail(getOutputFilename($url), $cl_config);
            break;
        case 2:
            echo "Run failed:\n";
            echo $process->getErrorOutput();
            break;
    }
}
