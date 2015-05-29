<?php
namespace App;

require_once __DIR__.'/Thrift/ClassLoader/ThriftClassLoader.php';
use Thrift\ClassLoader\ThriftClassLoader;
$GEN_DIR = realpath(dirname(__FILE__)).'/gen-php/';

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__ . '/');
$loader->registerDefinition('mobilemsg', $GEN_DIR);
$loader->register();

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\TSocketPool;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TFramedTransport;
use Thrift\Exception\TException;

/**
 * Class Msg
 * @package App
 * 弹窗
 */
class Msg
{
    public $alert;
    public $worker_id;

    private $config;

    function __construct($alert)
    {
        $this->alert = $alert;
        $this->config = \Swoole::$php->config['msg']['master'];
    }

    function alert($msg)
    {
        $this->worker_id = $this->alert->yy->worker_id;
        $mobiles = explode(',',$msg['alert_mobiles']);
        if (!empty($mobiles))
        {
            $this->_send($mobiles,$msg);
        }
        else
        {
            $this->log("task worker {$this->worker_id} error.".print_r($msg,1));
        }
    }

    private function is_ready($msg)
    {
        if (empty($msg['last_msg_time']))
        {
            $this->log("task worker {$this->worker_id}  first time to msg");
            return true;
        }
        else
        {
            $interval = $msg['msg_int'] * 60;//pop时间间隔 单位分钟
            if (time() - intval($msg['last_msg_time']) >= $interval) //间隔大于设置的间隔
            {
                $this->log("task worker {$this->worker_id}  time to msg;value:".
                    time()."-".intval($msg['last_msg_time'])."=".(time()-intval($msg['last_msg_time'])).", setting :".$interval);
                return true;
            }
            else
            {
                $this->log("task worker {$this->worker_id}  time is not ready to msg ;value:".
                    time()."-".intval($msg['last_msg_time'])."=".(time()-intval($msg['last_msg_time'])).", setting :".$interval);
                return false;
            }
        }
    }

    private function _send($mobiles,$message)
    {
        try {
            $socket = new TSocketPool($this->config['msg_server_host'], $this->config['msg_server_port']);
            $transport = new TFramedTransport($socket, 1024, 1024);
            $protocol = new TBinaryProtocol($transport);
            $transport->open();

            $client = new \mobilemsg\service\MsgOper_ServiceClient($protocol);
            $identity = new \mobilemsg\service\Identity(
                array(
                    'version'=>$GLOBALS['mobilemsg_CONSTANTS']['version'],
                    'appId'=>$this->config['app_id'],
                    'appKey'=>$this->config['app_key'])
            );

            $sent_control = new \mobilemsg\service\SentControl();
            $type = 0;
            $downMsg = new \mobilemsg\service\DownMsg();
            $downMsg->mobiles = $mobiles;
            $downMsg->smsContent = $this->alert->build_msg($message);
            $downMsg->muid = $mobiles[0].'_'.time();

            $msg = array(0=>$downMsg);
            $ret = $client->send($identity, $type, $msg, $sent_control);
            $this->log("task worker {$this->worker_id}  send msg result".var_export($ret,1));
            //业务代码end
            $transport->close();
        } catch (TException $tx) {
            $this->log("task worker {$this->worker_id}  thrift Exception ".var_export($tx,1));
        }
    }

//    private function build_msg($message)
//    {
//        $content = "【紧急告警】 ".date("Y-m-d")." ".$this->alert->get_time_string($message['time_key'])."-".$this->alert->get_time_string($message['time_key']+1)
//            ."  {$message['module_name']}->{$message['interface_name']} ";
//        if (isset($message['succ_percent'])) //注意成功率为0 不要用empty 判断
//        {
//            $content .= "成功率低于{$message['succ_hold']}%，";
//        }
//        if (isset($message['wave_percent']) and !empty($message['wave_percent']))
//        {
//            if ($message['flag'] == 1)
//            {
//                $content .= "波动率同比增长{$message['wave_percent']}% 高于 {$message['wave_hold']}%，";
//            }
//            if ($message['flag'] == 2)
//            {
//                $content .= "波动率同比下降{$message['wave_percent']}% 高于 {$message['wave_hold']}%，";
//            }
//        }
//        $content .= "需要尽快处理，请登录https://stats.duowan.com/stats/index/?module_id=".$message['module_id']." 查看更详细的信息。";
//        return $content;
//    }

    public function log($msg)
    {
        $this->alert->yy->log($msg);
    }
}
