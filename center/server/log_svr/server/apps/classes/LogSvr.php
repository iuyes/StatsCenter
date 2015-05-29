<?php
namespace App;

class LogSvr
{
    /**
     * @var \swoole_server
     */
    protected $redis;
    protected $serv;
    const SVR_PORT_LOG = 9902;
    protected $client = array(
        'host' => '127.0.0.1',
        'port' => 9906,
    );
    protected $cli;

    protected $log_table = 'logs';
    protected $today;
    protected $tomorrow;

    protected $pid_file;
    public $log;
    /**
     * @param $serv
     * @param $fd
     * @param $from_id
     * @param $data
     */
    function onPackage(\swoole_server $serv, $fd, $from_id, $data)
    {
        $conn = $serv->connection_info($fd, $from_id);
        if ($conn['from_port'] == self::SVR_PORT_LOG )
        {
            $pkg = unpack('Nmodule_id/Ninterface_id/Nspecial_id/Nuser_id/Clevel/Ntime', $data);
            if ($pkg == false)
            {
                $this->log("error package. data".$data);
                return;
            }
            $pkg['client_ip'] = $conn['remote_ip'];
            $pkg['txt'] = substr($data, 21);
            $tmp = $pkg;
            $tmp['cmd'] = 'server';
            $this->cli->send(json_encode($tmp));
            $this->redis->sAdd($pkg['module_id']."_".$pkg['interface_id'] , $pkg['client_ip']);
            if (time() >= strtotime($this->tomorrow.' '.'00:00:00'))
            {
                $this->log_table = 'logs_'.$this->tomorrow;
            }
            table($this->log_table)->put($pkg);
        }
    }
    function onStart(\swoole_server $serv, $worker_id)
    {
        $this->redis = new \redis;
        $this->redis->connect('localhost');
        $this->today = date("Y-m-d");
        $this->log_table = 'logs_'.$this->today;
        $this->tomorrow = date("Y-m-d",time()+86400);
        \Swoole::$php->db->query("CREATE TABLE IF NOT EXISTS `logs_".$this->today."` LIKE `logs`");
        \Swoole::$php->db->query("CREATE TABLE IF NOT EXISTS `logs_".$this->tomorrow."` LIKE `logs`");
        //每个worker 24小时执行一次创建表 不要零点启动服务
        $serv->addtimer(24*60*60000);
    }

    function onTimer($serv)
    {
        $tomorrow = date("Y-m-d",time()+86400);
        $res = \Swoole::$php->db->query("CREATE TABLE IF NOT EXISTS `logs_".$tomorrow."` LIKE `logs`");
        if ($res)
        {
            $this->tomorrow = $tomorrow;
        }
    }

    function onMasterStart($server)
    {
        $this->log("Log server start");
        file_put_contents($this->pid_file,$server->master_pid);
    }

    function onManagerStop($server)
    {
        $this->log("Log server shutdown");
        if (file_exists($this->pid_file))
        {
            unlink($this->pid_file);
        }
    }

    function setLogger($log)
    {
        $this->log = $log;
    }

    function log($msg)
    {
        $this->log->info($msg);
    }

    function run($_setting = array())
    {
        $default_setting = array(
            'dispatch_mode' => 1,
            'max_request' => 0,
        );
        $this->pid_file = $_setting['pid_file'];

        $this->cli = new \swoole_client(SWOOLE_UDP);
        $this->cli->connect($this->client['host'], $this->client['port'], 1, 1);
//        $this->cli = stream_socket_client('udp://127.0.0.1:9906', $errno, $errstr,5);

        $setting = array_merge($default_setting, $_setting);
        $serv = new \swoole_server('0.0.0.0', self::SVR_PORT_LOG, SWOOLE_PROCESS, SWOOLE_UDP);
        $serv->set($setting);
        $serv->on('start', array($this, 'onMasterStart'));
        $serv->on('managerStop', array($this, 'onManagerStop'));
        $serv->on('receive', array($this, 'onPackage'));
        $serv->on('workerStart', array($this, 'onStart'));
        $serv->on('timer', array($this, 'onTimer'));
        $this->serv = $serv;
        $this->serv->start();
    }
}