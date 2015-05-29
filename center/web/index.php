<?php
define('DEBUG', 'off');
define('WEBPATH', __DIR__);
require __DIR__.'/../vendor/autoload.php';
Swoole\Loader::vendor_init();

if (get_cfg_var('env.name') == 'local' or get_cfg_var('env.name') == 'dev')
{
    Swoole::$php->config->setPath(__DIR__.'/apps/configs/dev/');
    define('WEBROOT', 'https://local.mostats.duowan.com/');
}
else
{
    Swoole::$php->config->setPath(__DIR__.'/apps/configs/');
    define('WEBROOT', 'https://stats.duowan.com');
}
Swoole::getInstance()->runMVC();