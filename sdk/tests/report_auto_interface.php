<?php
require __DIR__.'/../api/php/StatsCenter.php';
$n = empty($argv[1]) ? 10000 : intval($argv[1]);
$tid = empty($argv[2]) ? 0 : intval($argv[2]);
$ifs = array(
    array(
        'id' => "interface_test",
        'use_min' => 1000,
        'use_max' => 4000,
        'fail_ms' => 3000,
        'server_ip' => array(
            '10.99.56.32',
            '10.199.3.10',
            '10.99.56.11',
            '172.0.0.3',
        ),
    ),
    array(
        'id' => "wahaha1",
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
        'id' => "wahaha2",
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
        'id' => "wahaha3",
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

//StatsCenter::setServerIp('119.147.176.30');
StatsCenter::setServerIp('127.0.0.1');
for($i = 0; $i< $n; $i++)
{
    $stat = StatsCenter::tick($ifs[$tid]['id'], 1000257);
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
}
