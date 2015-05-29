<?php
define('DEBUG', 'on');
define('WEBPATH', __DIR__);

require __DIR__ . '/../../../vendor/autoload.php';
Swoole\Loader::vendor_init();
$ch = new \Swoole\Client\CURL();

$res = $ch->post(\Swoole::$php->config['pop']['master']['post'],'msg='.file_get_contents('./test.xml'));
var_dump($res);