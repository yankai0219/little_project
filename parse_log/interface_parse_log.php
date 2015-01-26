<?php
interface IParseLog
{
    public function parseOneLine($line);
    public function statLogInfo(&$statInfos, $info);
    public function afterParseOp($statInfos, $resLog);
}
