<?php
namespace App\Controller;
use Swoole;
use App;

class Logs extends App\LoginController
{
    public $log_level = array(
        1 => "INFO",
        2 => "WARNING",
        3 => "ERROR",
        4 => "TRACE",
    );
    function index()
    {
        $gets['select'] = 'id, name';
        $modules = table('module')->gets($gets);
        $module_id = intval($_GET['module_id']);
        if (!empty($module_id))
        {
            $gets['module_id'] = $module_id;
            $interfaces = table('log_interface')->gets($gets);
        }

        $this->data();
        $this->assign('interfaces',$interfaces);
        $this->assign('levels',$this->log_level);
        $this->assign('modules',$modules);
        $this->display();
    }
    function data()
    {
        //\Swoole\Error::dbd();
        $interface_id = intval($_GET['interface_id']);
        $module_id = intval($_GET['module_id']);
        if (!empty($interface_id) and !empty($module_id))
        {
            $log_table = 'logs';
            $gets['interface_id'] = $interface_id;
            $gets['module_id'] = $module_id;
            if (empty($_GET['date_key']))
            {
                $_GET['date_key'] = date('Y-m-d');
                $log_table = 'logs_'.date('Y-m-d');
            }
            else
            {
                $log_table = 'logs_'.$_GET['date_key'];
            }
            $start_hour = !empty($_GET['hour_start']) ? ($_GET['hour_start']) : '00';
            $end_hour = !empty($_GET['hour_end']) ? ($_GET['hour_end']) : 23;
            $start_time = $_GET['date_key'].' '.$start_hour.':00:'.'00';
            $end_time = $_GET['date_key'].' '.$end_hour.':00:'.'00';
            //echo $start_time.'---'.$end_time."<br>";
            $gets['where'][] = 'time > '.strtotime($start_time);
            $gets['where'][] = 'time < '.strtotime($end_time);

            if (!empty($_GET['special_id']))
            {
                $gets['special_id'] = $_GET['special_id'];
            }
            if (!empty($_GET['client_ip']))
            {
                $gets['client_ip'] = $_GET['client_ip'];
            }
            if (!empty($_GET['level']))
            {
                $gets['level'] = $_GET['level'];
            }
            $gets['page'] = !empty($_GET['page'])?$_GET['page']:1;
            $gets['pagesize'] = 50;
            $gets['order'] = 'id asc';
            $logs = table($log_table)->gets($gets,$pager);
            $this->assign('pager', array('total'=>$pager->total,'render'=>$pager->render()));
            if (!empty($logs))
            {
                foreach ($logs as $k => $l)
                {
                    if (!empty($l['txt']))
                    {
                        $logs[$k]['txt'] = htmlentities($l['txt']);
                    }
                }

                $return['status'] = 200;
                $return['msg'] = '获取日志成功';
                $return['content'] = $logs;
            } else
            {
                $return['status'] = 400;
                $return['msg'] = '没有匹配的日志';
            }
            $client_ids = $this->redis->sMembers($module_id."_".$interface_id);
            foreach ($client_ids as $k => $v)
            {
                $_client_ids[$v] = $v;
            }
        }
        $form['client'] = \Swoole\Form::select('client_ip',$_client_ids,$_GET['client_ip'],'',array('class'=>'select2'));
        $this->assign('form', $form);
        $this->assign('logs', $return);
    }

    function runtime()
    {
        $interface_id = intval($_GET['interface_id']);
        $module_id = intval($_GET['module_id']);
        if (empty($interface_id) or empty($module_id))
        {
            $return['status'] = 400;
            $return['msg'] = '操作失败';
        }
        $config = $this->config['websocket'];
        $this->assign('config', $config);
        $this->assign('return', $return);
        $this->display();
    }

    function get_ip()
    {
        $interface_id = (int)$_GET['interface_id'];
        $module_id = (int)$_GET['module_id'];
        if (!empty($interface_id) and !empty($module_id))
        {
            $client_ids = $this->redis->sMembers($module_id."_".$interface_id);
            if (!empty($client_ids))
            {
                $return['status'] = 0;
                $return['client_ids'] = $client_ids;
            }
            else
            {
                $return['status'] = 400;
            }
        }
        else
        {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }

    function get_interface()
    {
        $module_id = (int)$_GET['module_id'];
        if (!empty($module_id))
        {
            $interface_ids = table("log_interface")->gets(array('module_id'=>$module_id));
            if (!empty($interface_ids))
            {
                $return['status'] = 0;
                $return['interface_ids'] = $interface_ids;
            }
            else
            {
                $return['status'] = 400;
            }
        }
        else
        {
            $return['status'] = 400;
        }
        echo json_encode($return);
    }
}