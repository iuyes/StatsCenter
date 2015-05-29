<?php
define('DEBUG', 'on');
define('WEBPATH', __DIR__);

require __DIR__.'/../vendor/autoload.php';
Swoole\Loader::vendor_init();
//\Swoole\Error::dbd();
$params['select'] = 'interface_id,module_id';
$res = table("stats")->gets($params);
$tmp = array();
foreach ($res as $val)
{
    $tmp[$val['module_id']][$val['interface_id']]  = $val['interface_id'];
}
//debug(Swoole::$php->redis);
foreach ($tmp as $m_id => $interface_ids)
{
    foreach ($interface_ids as $id)
    {
        $res = Swoole::$php->redis->sAdd($m_id,$id);
        echo $m_id.'--'.$id;
        var_dump($res);
        echo "\n";

    }
}

