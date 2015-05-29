<?php
define('DEBUG', 'on');
require __DIR__.'/../vendor/autoload.php';
Swoole\Loader::vendor_init();
$pkg = array(
    "client_ip"=>"127.0.0.1",
    "user_id"=>350749960,
  "level"=> 1,
  "time"=>1398345164,
  "txt"=> "ErrorLog Test. File=/home/htf/workspace/duowan/mostats/tests/send_log.php|num=0",
);
Swoole::$app_path = __DIR__.'/../server/';
table('module_log')->put($pkg);