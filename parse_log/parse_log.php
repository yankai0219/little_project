<?php
include dirname(__FILE__) . '/parse_log_prot.php';
main($argc, $argv);
function main($argc, $argv)
{
    if ($argc < 5)
    {
        echo "用法: $argv[0] parse_log_class.php parseLogClass result.log nginx_log";
        exit();
    }

    $parseLogClsPath = $argv[1];
    $parseLogClsName = $argv[2];
    include $parseLogClsPath;
    $resLog = $argv[3];
    $filePaths = array_slice($argv, 4);

    echo 'begin parse log' . "\n";
    // 主要是这三步骤 完成读日志文件的解析
    $parseLogObj = new ParseLogProt(new $parseLogClsName());
    $statInfos = $parseLogObj->parseLogFile($filePaths);
    $res = $parseLogObj->afterParseOp($statInfos, $resLog);

    echo 'end parse log' . "\n";
}
