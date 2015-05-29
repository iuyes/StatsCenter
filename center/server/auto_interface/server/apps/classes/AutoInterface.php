<?php
namespace App;

class AutoInterface
{
    /**
     * @var \swoole_server
     */
    protected $pid_file;
    public $log;

    protected $serv;
    const SVR_PORT_AOP = 9904;
    const EOF = "\r\n";

    function onReceive(\swoole_server $serv, $fd, $from_id, $data)
    {
        $conn = $serv->connection_info($fd, $from_id);
        if ($conn['from_port'] == self::SVR_PORT_AOP )
        {
            $_key = explode('_',substr($data,3),3);
            if (substr($data,0,3) == 'GET')
            {
                $key = $this->getKey($_key[0],$_key[1],$_key[2]);
                $this->serv->send($fd,$key);
            } elseif (substr($data,0,3) == 'SET') {
                $key = $this->createKey($_key[0],$_key[1],$_key[2]);
                $this->serv->send($fd,$key);
            }
        }
    }

    private function getKey($module_id,$type,$name)
    {
        if ($type == 'log')
        {
            $table = 'log_interface';
        }
        else
        {
            $table = 'interface';
        }
        $params['select'] = "id";
        $params['name'] = $name;
        $params['module_id'] = $module_id;
        $data = table($table)->gets($params);
        if (!empty($data))
        {
            return $data[0]['id'];
        }
        else {
            return 0;
        }
    }

    private function createKey($module_id,$type,$name)
    {
        if ($type == 'log')
        {
            $table = 'log_interface';
        }
        else
        {
            $table = 'interface';
        }
        $params['name'] = $name;
        $params['alias'] = $name;
        $params['module_id'] = $module_id;
        $params['intro'] = 'auto create by api';
        return table($table)->put($params);
    }

    function onMasterStart($server)
    {
        $this->log("autointerface server start");
        file_put_contents($this->pid_file,$server->master_pid);
    }

    function onManagerStop($server)
    {
        $this->log("autointerface server shutdown");
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
            'worker_num' => 4,
            'max_request' => 0,
        );
        $this->pid_file = $_setting['pid_file'];
        $setting = array_merge($default_setting, $_setting);
        $serv = new \swoole_server('0.0.0.0', self::SVR_PORT_AOP, SWOOLE_PROCESS, SWOOLE_TCP);
        $serv->set($setting);
        $serv->on('start', array($this, 'onMasterStart'));
        $serv->on('managerStop', array($this, 'onManagerStop'));
        $serv->on('receive', array($this, 'onReceive'));
        $this->serv = $serv;
        $this->serv->start();
    }
}