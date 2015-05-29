<?php
ini_set('display_errors', '1');
error_reporting(-1);

require __DIR__.'/../api/php/StatsCenter.php';
$n = empty($argv[1]) ? 10000 : intval($argv[1]);
$tid = empty($argv[2]) ? 0 : intval($argv[2]);
$ifs = array(
    array(
        'id' => '5000577',
        'use_min' => 1000,
        'use_max' => 4900,
        'fail_ms' => 3000,
        'server_ip' => array(
            '10.99.56.32',
            '10.199.3.10',
            '10.99.56.11',
            '172.0.0.3',
        ),
    ),
    array(
        'id' => 5000576,
        'use_min' => 10000,
        'use_max' => 80000,
        'fail_ms' => 78000,
        'server_ip' => array(
            '10.99.56.32',
            '10.199.3.10',
            '10.99.56.11',
            '172.0.0.3',
        ),
    ),
    array(
        'id' => 5000572,
        'use_min' => 10000,
        'use_max' => 80000,
        'fail_ms' => 78000,
        'server_ip' => array(
            '10.99.56.32',
            '10.199.3.10',
            '10.99.56.11',
            '172.0.0.3',
        ),
    ),
    array(
        'id' => 5000569,
        'use_min' => 10000,
        'use_max' => 80000,
        'fail_ms' => 78000,
        'server_ip' => array(
            '10.99.56.32',
            '10.199.3.10',
            '10.99.56.11',
            '172.0.0.3',
        ),
    ),
);

//StatsCenter::setServerIp('127.0.0.1');
/*
for($i = 0; $i< $n; $i++)
{
    $stat = StatsCenter::tick($ifs[$tid]['id'], 1000285);
    $ms = rand($ifs[$tid]['use_min'], $ifs[$tid]['use_max']);
    usleep($ms); //120ms
    $ip_i = array_rand($ifs[$tid]['server_ip']);
    if ($ms > $ifs[$tid]['fail_ms'])
    {
        $succ = StatsCenter::FAIL;
        $ret_code = rand(1001, 1006);
        $stat->reportCode($ret_code,$ifs[$tid]['server_ip'][$ip_i]);
    }
    else
    {
        $succ = StatsCenter::SUCC;
        $ret_code = rand(2001, 2006);
        $stat->reportCode($ret_code,$ifs[$tid]['server_ip'][$ip_i]);
    }

    //$stat->report($succ, $ret_code, $ifs[$tid]['server_ip'][$ip_i]);
}
*/
for($i = 0; $i < $n; $i++)
{
    $stat = StatsCenter::tick($ifs[$tid]['id'], 1000255);
    $ms = rand($ifs[$tid]['use_min'], $ifs[$tid]['use_max']);
    usleep($ms); //120ms
    if ($ms > $ifs[$tid]['fail_ms'])
    {   
        $succ = StatsCenter::FAIL;
        $ret_code = rand(1001, 1006);
    }   
    else
    {   
        $succ = StatsCenter::SUCC;
        $ret_code = rand(2001,2006);
    }   
    $ip_i = array_rand($ifs[$tid]['server_ip']);
    $stat->report($succ, $ret_code, $ifs[$tid]['server_ip'][$ip_i]);
    unset($stat);
}

