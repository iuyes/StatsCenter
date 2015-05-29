<?php
require __DIR__.'/../api/php/StatsCenter.php';
AopNet::setServerIp('127.0.0.1');
StatsCenter::setServerIp('127.0.0.1');
StatsCenter::$module_id = 1000245;
StatsCenter::bind('basicHandler');
function basicHandler()
{
    $ms = rand(3000,9000);
    usleep($ms); //120ms
    if (rand(1,15000) > 7500)
    {
        return true;
    }
    else {
        return false;
    }

}

echo 'start aop';
for ($i=0;$i<15000;$i++)
{
    basicHandler();
}
echo 'end aop';


