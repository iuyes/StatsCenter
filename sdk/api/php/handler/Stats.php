<?php
namespace Sdk\Handler;

require __DIR__."/Basic.php";
require __DIR__.'/../StatsCenter.php';
class Stats implements \Sdk\Handler\Basic
{
    public $module_id;
    public $interface_name;
    public $special_id = 0;
    public $level = 1;
    public $user_id = 0;

    function __construct($params)
    {
        if (!empty($params))
        {
            $this->module_id = isset($params['module_id'])?$params['module_id']:0;
            $this->level = isset($params['level'])?$params['level']:0;
            $this->special_id = isset($params['special_id'])?$params['special_id']:0;
            $this->user_id = isset($params['user_id'])?$params['user_id']:0;
        }
    }

    function setModule($module_id)
    {
        $this->module_id = $module_id;
    }

    function setInterface($interface_id)
    {
        $this->interface_id = $interface_id;
    }

    function setSpecial($special_id)
    {
        $this->special_id = $special_id;
    }

    function setLevel($level)
    {
        $this->level = $level;
    }

    function setUser($user)
    {
        $this->user_id = $user;
    }

    function setServerIp($ip)
    {
        \StatsCenter::setServerIp($ip);
        \AopNet::setServerIp($ip);
    }

    function InModify($data,$interface_name)
    {
        self::report($data,$interface_name);
    }

    function report($data,$interface_name)
    {
        $this->interface_name = $interface_name;
        if (empty($this->module_id))
        {
            exit('module_id can not be empty~');
        }
        \StatsCenter::log1($data, $this->level, $this->user_id, $this->module_id, $this->interface_name, $this->special_id);
    }

}