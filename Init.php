<?php

// error_reporting(E_ALL);
// ini_set('display_errors', true);


class Init
{

    public static function run()
    {
        $os = strtolower(PHP_OS);
        $baseDir = dirname(__FILE__). "/bin";


        if (strpos($os, 'win') !== false) {
            $baseDir = dirname(__FILE__). "\bin";
            
            if (file_exists("$baseDir\\textimg.exe")) exec("powershell rm $baseDir\\textimg.exe");
            // Windows
            echo "Detected Windows. Extracting textimg-windows-amd64.zip...\nThis may take a while...";
            exec("powershell Expand-Archive -Path $baseDir/textimg-windows-amd64.zip -DestinationPath $baseDir");
            echo "\nExtract has completed";
        } else {
            if (file_exists("$baseDir\\textimg"))exec("rm -rf $baseDir/textimg");
            // Linux or other OS
            echo "Detected Linux. Extracting textimg-linux-amd64.tar.gz...\n";
            exec("tar -xzf $baseDir/textimg-linux-amd64.tar.gz -C $baseDir");
            echo "\nExtract has completed";
        }
    }
}


// Run the initialization when the script is executed directly
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    Init::run();
}