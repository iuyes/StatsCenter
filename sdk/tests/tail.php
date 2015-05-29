<?php
require __DIR__ . '/../api/php/handler/Tail.php';
$tail = new \Sdk\Handler\Tail();

require __DIR__ . '/../api/php/Inotify.php';
$config['path'] = '/var/log/nginx/access.log';
$inotify = new \Sdk\Inotify($config);
$inotify->setHandler($tail);
$inotify->start();

