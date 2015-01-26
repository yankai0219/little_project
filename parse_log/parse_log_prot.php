<?php
class ParseLogProt
{
    private $_prsObj;
    public function __construct($parseLogObj)
    {
        $this->_prsObj = $parseLogObj;
    }
    public function parseLogFile($filePaths)
    {
        if ($this->_prsObj === null)
        {
            echo 'Please set parse log object';
            exit;
        }

        $statInfos = array();
        foreach ($filePaths as $filePath)
        {
            $this->_parseOneFile($statInfos, $filePath);
        }
        return $statInfos;
    }
    private function _parseOneFile(&$statInfos, $filename)
    {
        $fd = $this->_open($filename);
        if ($fd === false)
        {
            echo $filename . ' open failed' . "\n";
            return false;
        }

        while($line = $this->_gets($fd))
        {
            $info = $this->_prsObj->parseOneLine($line);
            $this->_prsObj->statLogInfo($statInfos, $info);
        }
    }
    private function _open($filename, $mode = 'r')
    {
        return @fopen($filename, $mode);
    }
    private function _gets($fd)
    {
        return fgets($fd);
    }
    public function afterParseOp($statInfos, $resLog)
    {
        if (! method_exists($this->_prsObj, 'afterParseOp'))
        {
            echo 'afterParseOp method doesnot exist' . "\n";
            echo '解析后没有后续处理 解析结果:' . print_r($statInfos, true) . "\n";
            return false;
        }
        return $this->_prsObj->afterParseOp($statInfos, $resLog);
    }
}
