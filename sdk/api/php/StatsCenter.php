<?php
class StatsCenter
{
    const SUCC = 1;
    const FAIL = 0;
    const TIME_OUT_STATUS = 4444;

    const PACK_LOG = 'NNNNCN';
    const PACK_STATS = 'NNCNNNN';

    const PORT_STATS = 9903;
    const PORT_LOGS = 9902;

    protected static $interface_tick = array();
    protected static $sc_svr_ip = '183.57.36.102';

    protected static $aop_bind;
    protected static $aop_interface;

    //默认值
    static $module_id = 1000238;
    static $retCode = 0;
    static $callServerIp = '127.0.0.1';
    static $registerShutdown = false;
    static $tick_array = array();
    static $round_index = 0;

    static function setServerIp($ip)
    {
        self::$sc_svr_ip = $ip;
    }

    static function log($loginfo, $level = 1, $userid = 0, $module = 0, $interfaceid = 0, $special_id = 0)
    {
        $pkg = pack(self::PACK_LOG, $module, $interfaceid, $special_id, $userid, $level, time());
        $data = $pkg.$loginfo;
        self::_send_udp($data, self::PORT_LOGS);
    }

    /*
     * 提供接口名称自动创建接口id,上报日志
     */
    static function log1($loginfo, $level = 1, $userid = 0, $module = 0, $interface_name = '', $special_id = 0)
    {
        $interface_id = 0;
        if (!empty($interface_name))
        {
            $interface_id = self::getInterfaceId($module.'_log_'.$interface_name);
        }
        self::log($loginfo, $level, $userid, $module, $interface_id, $special_id);
    }
    /**
     * 自动创建接口
     */
    static protected function autoCreateInterface()
    {
        static $interfaces = null;
        if ($interfaces === null)
        {
            $cache_file = __DIR__.'/cache.php';
            if (is_file($cache_file))
            {
                $interfaces = include $cache_file;
                if (!is_array($interfaces))
                {
                    $interfaces = array();
                }
            }
            else
            {
                $interfaces = array();
            }
        }
    }

    static function getInterfaceId($key)
    {
        $key = base64_encode($key);
        $file = '/tmp/mostats/'.$key;
        if (!is_dir('/tmp/mostats'))
        {
            mkdir('/tmp/mostats');
        }
        if (is_file('/tmp/mostats/'.$key))
        {
            $id = file_get_contents($file);
            return $id;
        }
        else
        {
            $aop = new AopNet();
            $id = $aop->get_id($key);
            if ($id)
            {
                file_put_contents($file, $id);
                return $id;
            }
            else
            {
                $id = $aop->create_id($key);
                file_put_contents($file, $id);
                return $id;
            }
        }
    }

    /**
     * 监听接口
     * @param $callName
     * @param int $interface_id
     */
    static function bind($callName, $interface_id = 0)
    {
        if (substr($callName, -2, 2) != '()')
        {
            $callName .= '()';
        }
        if ($interface_id == 0)
        {
            $interface_id = self::getInterfaceId(self::$module_id.'_stat_'.substr($callName, 0, -2));
        }
        aop_add_before($callName, 'StatsCenter::aop_tick');
        aop_add_after($callName, 'StatsCenter::aop_report');
        self::$aop_interface[$callName] = $interface_id;
    }

    static function aop_tick(\AopJoinPoint $joinpoint)
    {
        $func = $joinpoint->getPointcut();
        self::$aop_bind[$func] = \StatsCenter::tick(self::$aop_interface[$func], self::$module_id);
    }

    static function aop_report(\AopJoinPoint $joinpoint)
    {
        $result = $joinpoint->getReturnedValue();
        $func = $joinpoint->getPointcut();
        $tick = self::$aop_bind[$func];
        $success = ($result === false) ? 0 : 1;
        $tick->report($success, self::$retCode, self::$callServerIp);
        unset(self::$aop_bind[$func]);
    }

    static function _send_udp($data, $port)
    {
        $cli = stream_socket_client('udp://'.self::$sc_svr_ip.':'.$port, $errno, $errstr);
        stream_socket_sendto($cli, $data);
    }

    static function tick($interface, $module, $force=false)
    {
        if (!is_numeric($interface) || $force)
        {
            $interface = self::getInterfaceId($module.'_stat_'.$interface);
        }
        if (!self::$registerShutdown)
        {
            register_shutdown_function('StatsCenter::onShutdown');
            self::$registerShutdown = true;
        }
        $obj = new StatsCenter_Tick($interface, $module, self::$round_index);
        self::$tick_array[self::$round_index] = $obj;
        self::$round_index ++;
        return $obj;
    }

    static function net_get_id()
    {

    }

    /**
     * PHP结束时发送所有统计请求
     */
    static function onShutdown()
    {
        /**
         * @var $tick StatsCenter_Tick
         */
        foreach(self::$tick_array as $tick)
        {
            $tick->report(false, 4444);
        }
    }

    static function net_create_id()
    {

    }
}

class StatsCenter_Tick
{
    protected $interface;
    protected $module_id;
    protected $start_ms;
    protected $params;
    protected $id;

    const STATS_PKG_LEN = 25;
    const STATS_PKG_NUM = 58;

    protected static $_send_udp_pkg = '';
    protected $_time_out_pkg = array();

    function __construct($interface, $module, $id)
    {
        $this->interface = $interface;
        $this->module = $module;
        $this->start_ms = microtime(true);
        $this->_time_out_pkg = array(
            'interface' => $this->interface,
            'module' => $this->module,
            'success' => StatsCenter::FAIL,
            'ret_code' => StatsCenter::TIME_OUT_STATUS,
            'server_ip' => 0,
            'use_time' => 0,
            'time' => 0
        );
        $this->id = $id;
    }

    function addParam($key, $value)
    {
        $this->params[$key] = $value;
    }

    function report($success, $ret_code, $server_ip = 0)
    {
        $this->_time_out_pkg = array();
        $use_ms = intval((microtime(true) - $this->start_ms) * 1000);
        $pkg = pack(StatsCenter::PACK_STATS,
            $this->interface,
            $this->module,
            $success,
            $ret_code,
            ip2long($server_ip),
            $use_ms, time());

        self::$_send_udp_pkg .= $pkg;

        //60个统计时发送数据包，避免超过最大传输单元，1500 MTU
        if (strlen(self::$_send_udp_pkg) >= self::STATS_PKG_LEN * self::STATS_PKG_NUM)
        {
            self::sendPackage();
        }
        unset(StatsCenter::$tick_array[$this->id]);
    }

    function reportSucc($success,$server_ip)
    {
        $this->report($success, 0, $server_ip);
    }

    function reportCode($ret_cod,$server_ip)
    {
        if ($ret_cod === 0)
        {
            $this->report(StatsCenter::SUCC, $ret_cod, $server_ip);
        }
        else
        {
            $this->report(StatsCenter::FAIL, $ret_cod, $server_ip);
        }
    }

    static function sendPackage()
    {
        StatsCenter::_send_udp(self::$_send_udp_pkg, StatsCenter::PORT_STATS);
        self::$_send_udp_pkg = '';
    }

    static function getIP()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "0.0.0.0";
        return $ip;
    }
}

class AopNet
{
    protected static $sc_svr_ip = '119.147.176.30';
    const PORT_AOP = 9904;

    public function __construct()
    {

    }


    static function setServerIp($ip)
    {
        self::$sc_svr_ip = $ip;
    }

    public function get_id($key)
    {
        $cli = stream_socket_client('tcp://'.self::$sc_svr_ip.':'.self::PORT_AOP, $errno, $errstr,5);
        stream_socket_sendto($cli, 'GET'.$key);
        $key = fread($cli, 1024);
        fclose($cli);
        return $key;
    }

    public function create_id($key)
    {
        $cli = stream_socket_client('tcp://'.self::$sc_svr_ip.':'.self::PORT_AOP, $errno, $errstr,5);
        stream_socket_sendto($cli, 'SET'.$key);
        $key = fread($cli, 1024);
        fclose($cli);
        return $key;
    }
}
