<?php
namespace App;

/**
 * Class alert
 * @package App
 * 弹窗
 */
class YYAlert
{
    public $alerts;
    public $yy;

    function __construct($yy)
    {
        $this->alerts['pop'] = new \App\Pop($this);
        $this->alerts['msg'] = new \App\Msg($this);

        $this->yy = $yy;
        $this->worker_id = $this->yy->worker_id;
    }

    /**
     * @param $interface  接口信息
     * @param $data 当前时间统计数据
     */
    public function alert($interface,$data)
    {
        //时间段没有数据上报  成功率不给报警
        if ($data['total_count'] > 0)
        {
            $succ_percent = round(($data['total_count']-$data['fail_count'])/$data['total_count'],2)*100;
            if ($succ_percent <= $interface['succ_hold'])
            {
                //成功率低于配置
                $data['succ_percent'] = $succ_percent;
            }
        }

        //波动报警
        //前一天数据为空  今天有数据 波动不报警
        //前一天有数据  今天为0 波动报警
        $time_key = $data['time_key'];
        $key = "last_succ_count_".$time_key;
        //前一天数据为空 不报警
        if (isset($data[$key]) and !empty($data[$key]))
        {
            if (!empty($data['total_count']))
            {
                //前一天和今天的数据都不为空 正常波动报警
                $wave = ($data['total_count']-$data['fail_count']) - $data[$key];
                $wave_percent = round(abs($wave)/$data['$key'],2)*100;
                if ( $wave_percent >= $data['wave_hold'])
                {
                    if ($wave > 0)
                    {
                        $data['flag'] = 1;//大于上次数据
                    }
                    else
                    {
                        $data['flag'] = 2;//小于上次数据
                    }
                    //成功率低于配置
                    $data['wave_percent'] = $wave_percent;
                }
            }
            else
            {
                //前一天有数据  今天为0 波动报警 波动100%
                $data['flag'] = 2;//小于上次数据
                $data['wave_percent'] = 100;
            }
        }
        \Swoole::$php->redis->hSet(YYPushSvr::PREFIX."::".$interface['interface_id'],'last_succ_count_'.$time_key,$data['total_count']-$data['fail_count']);

        //成功率 或者 波动率 满足其中一个条件
        if (isset($data['succ_percent']) or isset($data['flag']))
        {

            $msg = array_merge($interface,$data);
            if ($this->is_ready($msg))
            {
                $this->_alert(array_merge($interface,$data));
                \Swoole::$php->redis->hSet(YYPushSvr::PREFIX."::".$msg['interface_id'],'last_alert_time',time());
                $this->log("task worker {$this->worker_id} msg detials .".print_r($msg,1));
                $this->log("meet alert condition,move to alert stage");
            }
        }
        else
        {
            $this->log("alert condition do not meet,return to next loop");
        }
    }

    private function is_ready($msg)
    {
        if (empty($msg['last_alert_time']))
        {
            $this->log("task worker {$this->worker_id}  first time to msg");
            return true;
        }
        else
        {
            $interval = $msg['alert_int'] * 60;//pop时间间隔 单位分钟
            if (time() - intval($msg['last_alert_time']) >= $interval) //间隔大于设置的间隔
            {
                $this->log("task worker {$this->worker_id}  time to msg; value:".
                    time()."-".intval($msg['last_alert_time'])."=".(time()-intval($msg['last_alert_time'])).", setting :".$interval);
                return true;
            }
            else
            {
                $this->log("task worker {$this->worker_id}  time is not ready to msg ;value:".
                    time()."-".intval($msg['last_alert_time'])."=".(time()-intval($msg['last_alert_time'])).", setting :".$interval);
                return false;
            }
        }
    }

    public function build_msg($message)
    {
        $content = "【紧急告警】 ".date("Y-m-d")." ".$this->get_time_string($message['time_key'])."-".$this->get_time_string($message['time_key']+1)
            ."  {$message['module_name']}->{$message['interface_name']} ";
        if (isset($message['succ_percent'])) //注意成功率为0 不要用empty 判断
        {
            $content .= "成功率低于{$message['succ_hold']}%，";
        }
        if (isset($message['wave_percent']) and !empty($message['wave_percent']))
        {
            if ($message['flag'] == 1)
            {
                $content .= "波动率同比增长{$message['wave_percent']}% 高于 {$message['wave_hold']}%，";
            }
            if ($message['flag'] == 2)
            {
                $content .= "波动率同比下降{$message['wave_percent']}% 高于 {$message['wave_hold']}%，";
            }
        }
        $content .= "需要尽快处理，请登录https://stats.duowan.com/stats/index/?module_id=".$message['module_id']." 查看更详细的信息。";
        return $content;
    }

    /**
     * @param $data 报警信息
     */
    private function _alert($msg)
    {
        if (!empty($msg['alert_types']))
        {
            $alert_types = explode('|',$msg['alert_types']);
            if (is_array($alert_types))
            {
                if (in_array(1,$alert_types))
                {
                    $this->alerts['pop']->alert($msg);
                }
                if (in_array(2,$alert_types))
                {
                    $this->alerts['msg']->alert($msg);
                }
            }
        }
        else
        {
            $this->log("alert types error".print_r($msg,1));
        }
    }

    public function log($msg)
    {
        $this->yy->log($msg);
    }

    public function get_time_string($time_key)
    {
        $h = floor($time_key / 12);
        $m = ($time_key % 12)*5;
        return $this->fill_zero_time($h).':'.$this->fill_zero_time($m);
    }

    public function fill_zero_time($s)
    {
        if (intval($s) < 10)
        {
            return '0'.$s;
        }
        else
        {
            return $s;
        }
    }
}