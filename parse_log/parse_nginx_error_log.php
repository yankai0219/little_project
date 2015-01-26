<?php
class ParseNginxErrorLog
{
    static  public function parseOneLine($line)
    {
        $pattern = '#(\d+/\d+/\d+).*request:\ "\w+\ ((/\w*)*)(\?)?#';
        $matchArr = array();
        $matchNum = preg_match($pattern, $line, $matchArr);
        if ($matchNum === 0)
        {
            $res = array();
        }
        else
        {
            $res = array(
                'date' => $matchArr[1],
                'action' => $matchArr[2],
                );
        }
        return $res;
    }
    static public function statLogInfo(&$statInfos, $info)
    {
        // if (date('Y/m/d') != $info['date'])
        if ('2015/01/26' != $info['date'])
        {
            if (! isset($statInfos['nottoday']))
            {
                $statInfos['nottoday'] = array();
                $statInfos['nottoday'] = 1;
            }
            else
            {
                $statInfos['nottoday']++; // 方便统计
            }
            return;
        }
        $action = $info['action'];

        if (! isset($statInfos[$action]))
        {
            $statInfos[$action] = 1;
        }
        else
        {
            $statInfos[$action]++;
        }
    }
    static public function afterParseOp($statInfos, $resLog)
    {
        $fd = @fopen($resLog, 'w');
        if ($fd === false)
        {
            echo $resLog . ' open failed';
            return false;
        }

        $curDate = date('Y-m-d');
        foreach ($statInfos as $action => $actionCnt)
        {
            $msg = "[$curDate]$action\t$actionCnt\n";
            $res = fwrite($fd, $msg);
        }
        return true;
    }
}
