<?php
namespace App\Controller;
use Swoole;

class Source extends \App\LoginController
{
    function look_sess()
    {
        var_dump($_SESSION);
    }

    function home()
    {
        $this->display();
    }

    function manage()
    {
        $gets['page'] = !empty($_GET['page'])?$_GET['page']:1;
        $gets['pagesize'] = 50;
        $gets['order'] = 'id asc';
        $source = table('url_source')->gets($gets,$pager);
        debug($source);
        $this->assign('pager', array('total'=>$pager->total,'render'=>$pager->render()));
        $this->assign('source', $source);
        $this->display();
    }

    function add()
    {

    }
}