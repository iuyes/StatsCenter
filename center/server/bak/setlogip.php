<?php
define('DEBUG', 'on');
define('WEBPATH', __DIR__);

require __DIR__.'/../vendor/autoload.php';
Swoole\Loader::vendor_init();
\Swoole\Error::dbd();
$sql = "select distinct module_id,interface_id,client_ip from logs";
$res = Swoole::$php->db->query($sql)->fetchall();
$tmp = array();
foreach ($res as $val)
{
    $tmp[$val['module_id']."_".$val['interface_id']][$val['client_ip']]  = $val['client_ip'];
}
//debug(Swoole::$php->redis);
foreach ($tmp as $m_f_id => $client_ips)
{
    foreach ($client_ips as $id)
    {
        $res = Swoole::$php->redis->sAdd($m_f_id,$id);
        echo $m_f_id.'--'.$id;
        var_dump($res);
        echo "\n";

    }
}

