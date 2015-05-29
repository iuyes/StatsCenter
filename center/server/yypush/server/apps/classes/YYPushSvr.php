<?php
namespace App;

class YYPushSvr
{
    public $setting;
    public $log;
    public $alert;
    public $worker_id;

    const SVR_PORT = 9990;
    const CHECK_TIME = 5;//5min pre check
    const PREFIX="YYPUSH";

    protected $serv;
    protected $pid_file;

    function __construct()
    {
        $this->alert = new \App\YYAlert($this);
    }

    function onMasterStart($server)
    {
        $this->log("stats server start");
        file_put_contents($this->pid_file,$server->master_pid);
    }

    function onManagerStop($server)
    {
        $this->log("stats server shutdown");
        if (file_exists($this->pid_file))
        {
            unlink($this->pid_file);
        }
    }

    function onWorkerStart(\swoole_server $serv, $worker_id)
    {
        $this->log("worker start {$worker_id}");
        $this->worker_id = $worker_id;
        if ($worker_id == 0)
        {
            //$serv->addtimer(self::CHECK_TIME*60*1000);
            $serv->addtimer(5000);
            $this->log("{$this->worker_id} add timer 5000ms");
        }
    }

    /**
     * @param $serv
     * @param $fd
     * @param $from_id
     * @param $data
     */
    function onPackage(\swoole_server $serv, $fd, $from_id, $data)
    {

    }

    function onTimer(\swoole_server $serv, $interval)
    {
        $interfaces = \Swoole::$php->redis->sMembers(self::PREFIX);
        if (!empty($interfaces))
        {
            foreach ($interfaces as $in)
            {
                $key = self::PREFIX."::".$in;
                $tmp = \Swoole::$php->redis->hGetAll($key);
//                $this->log("{$this->worker_id} interfaces detials  ".print_r($tmp,1));
                if (!empty($tmp) and $tmp['enable_alert'] == 1 and !empty($tmp['alert_uids']))
                {
                    $serv->task($tmp);
                }
                else
                {
                    $this->log("{$this->worker_id} interface condition error ".var_export($tmp,1));
                }
            }
        }
    }

    function onTask($serv, $task_id, $from_worker_id, $interface)
    {
        $time_key = self::getMinute() - 3;//当前时间减去2 统计要占用两个时间片
        if ($time_key)
        {
            $gets['select'] = "total_count,fail_count,time_key";
            $gets['interface_id'] = $interface['interface_id'];
            $gets['module_id'] = $interface['module_id'];
            $gets['date_key'] = date('Y-m-d');
            $gets['time_key'] = $time_key;
            $tmp = table('stats')->gets($gets);
            if (!empty($tmp))
            {
                $this->alert->alert($interface,$tmp[0]); //传入最多数据 后期详细数据报警
            }
            else
            {
                $this->alert->alert($interface,array(
                    'total_count' => 0,
                    'fail_count' => 0,
                    'time_key' => $time_key,
                ));
            }
            $this->log("{$this->worker_id} on task data details mysql {$time_key} ".var_export($tmp,1));
        }
    }

    function onFinish($serv, $task_id, $data)
    {
        $this->log("on fin ".print_r(json_decode($data,1),1));
    }

    static function getMinute()
    {
        return intval((date('G')*60 + date('i')) / self::CHECK_TIME);
    }

    function log($msg)
    {
        $this->log->info($msg);
    }

    function setLogger($log)
    {
        $this->log = $log;
    }

    //获取报警接口信息
    function get_interface()
    {

    }

    function run($_setting = array())
    {
        $default_setting = array(
            'worker_num' => 4,
            'task_worker_num' => 4,
            'max_request' => 0,
        );
        $this->pid_file = $_setting['pid_file'];
        $setting = array_merge($default_setting, $_setting);
        $this->setting = $setting;
        $serv = new \swoole_server('0.0.0.0', self::SVR_PORT, SWOOLE_PROCESS, SWOOLE_UDP);
        $serv->set($setting);
        $serv->on('start', array($this, 'onMasterStart'));
        $serv->on('managerStop', array($this, 'onManagerStop'));
        $serv->on('workerStart', array($this, 'onWorkerStart'));
        $serv->on('receive', array($this, 'onPackage'));
        $serv->on('timer', array($this, 'onTimer'));
        $serv->on('task', array($this, 'onTask'));
        $serv->on('finish', array($this, 'onFinish'));
        $this->serv = $serv;
        $this->serv->start();
    }
}
