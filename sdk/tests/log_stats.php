<?php
require __DIR__ . '/../api/php/handler/Stats.php';
$setting = array(
    'module_id' => 1000238,
);
$stats = new Sdk\Handler\Stats($setting);
$stats->setServerIp('127.0.0.1');

require __DIR__ . '/../api/php/Inotify.php';
$config['path'] = '/var/log/nginx/access.log';
$inotify = new \Sdk\Inotify($config);
$inotify->setHandler($stats);
$inotify->start();

