<?php
namespace App;

/**
 * Class Msg
 * @package App
 * 弹窗
 */
class Pop
{
    public $ch;
    public $alert;
    public $worker_id;

    function __construct($alert)
    {
        $this->ch = new \Swoole\Client\CURL();
        $this->url = \Swoole::$php->config['pop']['master']['post'];
        $this->alert = $alert;
    }

    function alert($msg)
    {
        $this->worker_id = $this->alert->yy->worker_id;
        $alert_uids = explode(',',$msg['alert_uids']);
        if (!empty($alert_uids))
        {
            foreach ($alert_uids as $uid)
            {
                $this->_send($uid,$msg);
            }
        }
        else
        {
            $this->log("task worker {$this->worker_id} alert_uids error.".print_r($msg,1));
        }
    }

    private function _send($uid,$msg)
    {
        $xml = $this->build_xml($uid,$msg);
        $this->log("xml detials user_id $uid ".$xml);
        $res = $this->ch->post($this->url,'msg='.$xml);
        if (trim($res) == '0')
        {
            $this->log("task worker {$this->worker_id}  post result success".var_export($res,1));
        }
        else
        {
            $this->log("task worker {$this->worker_id}  post result failed".var_export($res,1));
        }
    }

    private function build_xml($uid,$message)
    {
        $string = '<?xml version="1.0" encoding="utf8"?>
                    <sysmessage>
                    </sysmessage>';
        $xml = new \App\SimpleXMLExtended($string);
        $xml->addChild('type', 1);
        $xml->addChild('app_id', 373);
        $xml->addChild('uid', $uid);
        $xml->addChild('saveOffline', 1);
        $xml->addChild('expiredate', date('Y-m-d H:i:s',time()+24*3600));
        $content = $xml->addChild('message')->addChild('sysmessage');
        $content->addChild('display_type',100);
        $msg = $content->addChild('message');
        $msg->addChild('poptype','common_pop');
        $msg->addChild('effecttype','FadeEffect');
        $msg->addChild('closetime','0');//一直显示
        $msg->addChild('style','RightBottomDirection');
        $msg->addChild('width','280');
        $msg->addChild('height','190');
        $title = $msg->addChild('title','多玩统计报警');
        $title->addAttribute('textColor','0,0,0');
//        $html = '<html>
//                    <head><meta http-equiv="Content-Language" content="zh-cn">
//                    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
//                    <title>多玩数据统计中心</title>
//                    </head>
//                    <body>
//                    <div>紧急告警:'.date("Y-m-d")." ".$this->alert->get_time_string($message['time_key'])."-".$this->alert->get_time_string($message['time_key']+1).' '.$message['module_name'].'</div>
//                    <div>模块:'.$message['module_name'].'</div>
//                    <div>接口:'.$message['interface_name'].'</div>
//                    <div>请求次数:'.$message['total_count'].'</div>
//                    <div>失败次数:'.$message['fail_count'].'</div>
//                ';
//        if (isset($message['succ_percent']))
//        {
//            $html .= '<div><span style="color: red">成功率:'.$message['succ_percent'].' 低于 '.$message['succ_hold'].'</span></div>';
//        }
//        if (isset($message['wave_percent']) and !empty($message['wave_percent']))
//        {
//            if ($message['flag'] == 1)
//            {
//                $html .= '<div><span style="green">波动率同比增长'.$message['wave_percent'].'高于'.$message['wave_hold'].'</span></div>';
//            }
//            if ($message['flag'] == 2)
//            {
//                $html .= '<div><span style="red">波动率同比下降'.$message['wave_percent'].'高于'.$message['wave_hold'].'</span></div>';
//            }
//        }
//        $html .= '</body>
//                </html>';
        $html = $this->alert->build_msg($message);
        $msg->addChildWithCDATA('context',str_replace('%','％',$html));
        $msg->addChild('logo',':/theme/mainframe/YYboy16.png');
        $msg->addChild('bgcolor','6699BB');
        $msg->addChild('buttonItemSpace','1');
        $yes_button = $msg->addChild('yesButton','查看');
        $yes_button->addAttribute('action','https://stats.duowan.com/stats/index/?module_id='.$message['module_id']);
        $yes_button->addAttribute('textColor','0,0,0');
        $yes_button->addAttribute('iconPath',':/theme/mainframe/icon_find_indicator.png');
        $yes_button->addAttribute('transparentBackground','true');
        $yes_button->addAttribute('actionClose','true');
        $yes_button->addAttribute('actionLogin','false');
        return $xml->asXML();
    }

    public function log($msg)
    {
        $this->alert->yy->log($msg);
    }
}

class SimpleXMLExtended extends \SimpleXMLElement {
    public function addChildWithCDATA($name, $value = NULL) {
        $new_child = $this->addChild($name);

        if ($new_child !== NULL) {
            $node = dom_import_simplexml($new_child);
            $no   = $node->ownerDocument;
            $node->appendChild($no->createCDATASection($value));
        }
        return $new_child;
    }
}