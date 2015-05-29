<?php
namespace Sdk\Handler;

require __DIR__."/Basic.php";

class Tail implements \Sdk\Handler\Basic
{
    function InModify($data,$interface_name)
    {
        $this->_echo($data);
    }

    function _echo($msg)
    {
        echo $msg;
    }
}