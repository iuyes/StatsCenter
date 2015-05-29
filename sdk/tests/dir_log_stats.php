<?php
require __DIR__ . '/../api/php/handler/Stats.php';
$setting = array(
    'module_id' => 1000238,
);
$stats = new Sdk\Handler\Stats($setting);
$stats->setServerIp('127.0.0.1');
//119.147.176.30
//$stats->setServerIp('119.147.176.30');
require __DIR__ . '/../api/php/Inotify.php';
//$config['path'] = '/tmp/molog/';
$config['path'] = '/tmp/molog/daylog';
//$config['path'] = '/data/weblog/gamenews';
$config['mode'] = 2; // 1 只监一层目录  2 监听两层目录
$inotify = new \Sdk\Inotify($config);
$inotify->setHandler($stats);
$inotify->start();

