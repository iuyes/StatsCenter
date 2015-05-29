<?php
namespace App;

class WebsocketSvr extends \Swoole\Protocol\WebSocket
{

    function __construct($config = array())
    {
        parent::__construct($config);
    }

    public function onReceive($server, $fd, $from_id, $data)
    {
        $conn = $server->connection_info($fd, $from_id);
        if ($conn['from_port'] == 9906 )
        {
            if (!empty($this->connections))
            {
                foreach($this->connections as $conn_fd => $v)
                {
                    $tmp = json_decode($data,true);
                    if ($tmp['module_id'] == $v['module_id'] and $tmp['interface_id'] == $v['interface_id'])
                    {
                        $tmp['time'] = date("Y-m-d H:i:s",$tmp['time']);
                        $this->send($conn_fd, json_encode($tmp));
                    }
                }
            }
        } else {
            parent::onReceive($server, $fd, $from_id, $data);
        }
    }

    public function onExit($client_id)
    {

    }

    public function onShutdown($server)
    {
        $this->log('worker onShutdown');
    }
    function onMessage($client_id, $ws)
    {
        $msg = json_decode($ws['message'], true);
        if (empty($msg['cmd']))
        {
            $this->sendErrorMessage($client_id, 101, "invalid command");
            return;
        }
        $func = 'cmd_'.$msg['cmd'];
        $this->$func($client_id, $msg);
    }

    function cmd_login($client_id, $msg)
    {
        $this->connections[$client_id]['interface_id'] = $msg['interface_id'];
        $this->connections[$client_id]['module_id'] = $msg['module_id'];
    }
    function cmd_log($client_id, $msg)
    {
        $resMsg = array(
            'cmd' => 'log',
            'data' => 'helloworld',
        );
        $this->sendJson($client_id, $resMsg);
    }

    function cmd_ll($client_id, $msg)
    {
        $resMsg = array(
            'cmd' => 'll',
            'data' => `ls -al`,
        );
        $this->sendJson($client_id, $resMsg);
    }
    function cmd_pwd($client_id, $msg)
    {
        $resMsg = array(
            'cmd' => 'll',
            'data' => `pwd`,
        );
        $this->sendJson($client_id, $resMsg);
    }
    function cmd_ps($client_id, $msg)
    {
        $resMsg = array(
            'cmd' => 'll',
            'data' => `ps aux`,
        );
        $this->sendJson($client_id, $resMsg);
    }
    /**
     * 发送错误信息
     * @param $client_id
     * @param $code
     * @param $msg
     */
    function sendErrorMessage($client_id, $code, $msg)
    {
        $this->sendJson($client_id, array('cmd' => 'error', 'code' => $code, 'msg' => $msg));
    }

    /**
     * 发送JSON数据
     * @param $client_id
     * @param $array
     */
    function sendJson($client_id, $array)
    {
        $msg = json_encode($array);
        $this->send($client_id, $msg);
    }

    /**
     * 广播JSON数据
     * @param $client_id
     * @param $array
     */
    function broadcastJson($client_id, $array)
    {
        $msg = json_encode($array);
        $this->broadcast($client_id, $msg);
    }

    function broadcast($client_id, $msg)
    {
        if (extension_loaded('swoole'))
        {
            $sw_serv = $this->getSwooleServer();
            $start_fd = 0;
            while(true)
            {
                $conn_list = $sw_serv->connection_list($start_fd, 10);
                if($conn_list === false)
                {
                    break;
                }
                $start_fd = end($conn_list);
                foreach($conn_list as $fd)
                {
                    if($fd === $client_id) continue;
                    $this->send($fd, $msg);
                }
            }
        }
        else
        {
            foreach ($this->connections as $fd => $info)
            {
                if ($client_id != $fd)
                {
                    $this->send($fd, $msg);
                }
            }
        }
    }
}