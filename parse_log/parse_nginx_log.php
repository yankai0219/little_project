<?php
class ParseNginxLog
{
    public function statLogInfo(&$statInfos, $info)
    {
        if (date('d/M/Y') != $info['date'])
        {
            if (! isset($statInfos['nottoday']))
            {
                $statInfos['nottoday'] = array();
                $statInfos['nottoday']['nottoday'] = 1;
            }
            else
            {
                $statInfos['nottoday']['nottoday']++; // 方便统计
            }
            return ;
        }
        $action = $info['action'];
        $ip = $info['ip'];
        if (!isset($statInfos[$action]))
        {
            $statInfos[$action] = array();
        }
        if (!isset($statInfos[$action][$ip]))
        {
            $statInfos[$action][$ip] = 1;
        }
        else
        {
            $statInfos[$action][$ip]++;
        }
    }

    public function parseOneLine($line)
    {
        $pattern = '#((\d+\.){3}\d+)\ -\ -\ \[(\d{2}/\w+/\d+).*\ "GET\ /\w+/(\w+)?#';
        $matchArr = array();
        $matchNum = preg_match($pattern, $line, $matchArr);
        if ($matchNum === 0)
        {
            $res = array();
        }
        else
        {
            $res = array(
                'ip' => $matchArr[1],
                'date' => $matchArr[3],
                'action' => $matchArr[4],
                );
        }
        return $res;
    }
    public function afterParseOp($statInfos, $resLog)
    {
        $fd = @fopen($resLog, 'w');
        if ($fd === false)
        {
            echo $resLog . ' open failed';
            return false;
        }

        $curDate = date('Y-m-d');
        foreach ($statInfos as $action => $ips)
        {
            $actionCnt = 0;
            foreach ($ips as $ip => $cnt)
            {
                $actionCnt += $cnt;
            }
            $msg = "[$curDate]$action\t$actionCnt\n";
            $res = fwrite($fd, $msg);
        }
        return true;
    }
}
