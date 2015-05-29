<?php
namespace App\Controller;
use Swoole;

class Stats extends \App\LoginController
{
    //$_SESSION['userinfo']['yyuid']
    static $width = array(
        '10%','10%','10%','10%','10%','10%','10%','30%'
    );

    function home()
    {
        $this->display();
    }

    function index()
    {
        $this->assign('width', self::$width);
        $this->getInterfaceInfo();
        $this->display();
    }

    protected function getInterfaceInfo()
    {
        //\Swoole\Error::dbd();
        //获取用户项目
        $uid = $_SESSION['userinfo']['yyuid'];
        $user = table('user')->get($uid,'uid')->get();
        $project_ids = $user['project_id'];

        $gets['select'] = 'id,name';
        if (!empty($project_ids))
        {
            $gets['where'][] = 'project_id in('.$project_ids.')';
        }
        $modules = table('module')->gets($gets);
        if (empty($_GET['date_key']))
        {
            $_GET['date_key'] = date('Y-m-d');
        }

        if (empty($_GET['module_id']))
        {
            $gets = array();
            $gets['select'] = 'id,name';
            $interfaces = table('interface')->gets($gets);
            $_GET['module_id'] = 0;
        }
        else
        {
            $interface_ids = $this->redis->sMembers($_GET['module_id']);
            if (!empty($interface_ids))
            {
                $_ip = array();
                $_ip['in'] = array('id',implode(',',$interface_ids));
                $interfaces = table('interface')->gets($_ip);
            }
            else
            {
                $interfaces = table('interface')->gets(array('module_id'=>$_GET['module_id']));
            }
        }

        if (empty($_GET['interface_id']))
        {
            $_GET['interface_id'] = 0;
        }
        $this->assign('interfaces', $interfaces);
        $this->assign('modules', $modules);
    }

    function getInterface()
    {
        $module_id = (int)$_GET['module_id'];
        $gets['select'] = 'id, name';
        $gets['module_id'] = $module_id;
        $modules = table('interface')->getMap($gets,'name');
        $return = array();
        if (!empty($modules))
        {
            $return['status'] = 200;
            $return['data'] = $modules;
        }
        else
        {
            $return['status'] = 400;
        }
        return json_encode($return,JSON_NUMERIC_CHECK);
    }

    function detail_data()
    {
        //\Swoole\Error::dbd();
        if (empty($_GET['interface_id']) or empty($_GET['module_id']) or empty($_GET['type']))
        {
            return "需要interface_id/module_id参数";
        }
        $param = $_GET;
        unset($param['type']);
        $param['select'] = 'ip, interface_id, time_key, total_count, fail_count, total_time, total_fail_time, max_time, min_time';
        $data = table('stats_'.$_GET['type'])->gets($param);
        return json_encode($data);
    }

    function client()
    {
        $_GET['type'] = 'client';
        $this->getInterfaceInfo();
        $this->display('stats/detail.php');
    }

    function server()
    {
        $_GET['type'] = 'server';
        $this->getInterfaceInfo();
        $this->display('stats/detail.php');
    }

    function fail()
    {
        $gets['interface_id'] = $_GET['interface_id'];
        $gets['module_id'] = $_GET['module_id'];
        $gets['date_key'] = $_GET['date_key'];
        if (!empty($_GET['time_key']) or $_GET['time_key'] == '0')
        {
            $gets['time_key'] = $_GET['time_key'];
        }
        $gets['select'] = 'time_key, ret_code, fail_server';
        $data = table('stats')->gets($gets);
        $ret_code = $fail_server = array();
        foreach($data as $d)
        {
            //$d['time_key']
            $ret_code[] = json_decode($d['ret_code'], true);
            $fail_server[]  = json_decode($d['fail_server'], true);
        }
        $this->assign('ret_code', $ret_code);
        $this->assign('fail_server', $fail_server);
        $this->display();
    }

    function succ()
    {
        $gets['interface_id'] = $_GET['interface_id'];
        $gets['module_id'] = $_GET['module_id'];
        $gets['date_key'] = $_GET['date_key'];
        if (!empty($_GET['time_key']) or $_GET['time_key'] == '0')
        {
            $gets['time_key'] = $_GET['time_key'];
        }
        $gets['select'] = 'time_key, succ_ret_code, succ_server';
        $data = table('stats')->gets($gets);
        $ret_code = $fail_server = array();
        foreach($data as $d)
        {
            //$d['time_key']
            $ret_code[] = json_decode($d['succ_ret_code'], true);
            $succ_server[]  = json_decode($d['succ_server'], true);
        }
        $this->assign('succ_ret_code', $ret_code);
        $this->assign('succ_server', $succ_server);
        $this->display();
    }

    function history_data ()
    {
        if (empty($_GET['module_id']) or empty($_GET['interface_id']))
        {
            return $this->message(5001, "require module_id and interface_id");
        }
        $param = $_GET;

        $param['date_start'] = !empty($_GET['date_start']) ? $_GET['date_start'] : date('Y-m-d');
        $param['date_end'] = !empty($_GET['date_end']) ? $_GET['date_end'] : date('Y-m-d', time() - 86400);
        $param['time_start'] = !empty($_GET['hour_start']) ? intval($_GET['hour_start']) * 12 : 0;
        $param['time_end'] = !empty($_GET['hour_end']) ? intval($_GET['hour_end'] + 1) * 12 : 0;

        $param['date_key'] = $_GET['date_start'];
        $d1 = $this->data($param, false, false);

        $param['date_key'] = $_GET['date_end'];
        $d2 = $this->data($param, false, false);

        return json_encode(array('data1' => $d1, 'data2' => $d2));
    }

    function data($param = array(), $ret_json = true, $get_interface = true)
    {
        //Swoole\Error::dbd();
        if (empty($param))
        {
            $param = $_GET;
        }
        $ifs = array();
        if ($get_interface)
        {
            if (!empty($_GET['module_id']))
            {
                $gets['module_id'] = intval($_GET['module_id']);
                $sql = "select * from interface";
                $ifs = $this->db->query($sql)->fetchall();
            }
            else
            {
                $ifs = table('interface')->all()->fetchall();
            }
        }
        if (!empty($_GET['interface_id']))
        {
            $gets['interface_id'] = intval($_GET['interface_id']);
            $ret['interface'] = $ifs;
        }
        else
        {
            $ids = array();
            foreach($ifs as $if)
            {
                $ids[] = $if['id'];
            }
            //$gets['in'] = array('interface_id', join(',', $ids));
            $ret['interface'] = $ifs;
        }

        $gets['date_key'] = empty($param['date_key']) ? date('Y-m-d') : $param['date_key'];
        $gets['select'] = 'interface_id, module_id, time_key, total_count, fail_count, total_time, total_fail_time, max_time, min_time';
        if (!empty($param['time_start']))
        {
            $gets['where'][] = 'time_key >= '.$param['time_start'];
        }
        if (!empty($param['time_end']))
        {
            $gets['where'][] = 'time_key < '.$param['time_end'];
        }
        $data = model('Stats')->gets($gets);
        if (!empty($data)) {
            $ret['status'] = 200;
        } else {
            $ret['status'] = 400;
        }
        $ret['stats'] = $data;
        $ret['date'] = $gets['date_key'];
        $ret['time'] = '00:00~23:59';

        return $ret_json ? json_encode($ret, JSON_NUMERIC_CHECK) : $ret;
    }

    function modules()
    {
        //$this->db->debug = true;
        if (is_numeric($_GET['q']))
        {
            $gets['id'] = $_GET['q'];
        }
        else
        {
            $gets['like'] = array('name', $_GET['q'].'%');
        }
        $gets['select'] = 'id,name';
        $modules = table('module')->gets($gets);
        return json_encode($modules);
    }

    function history()
    {
        $this->assign('width', self::$width);
        $this->getInterfaceInfo();
        $this->display('stats/history.php');
    }
}