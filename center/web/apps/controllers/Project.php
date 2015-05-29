<?php
namespace App\Controller;
use Swoole;

class Project extends \App\LoginController
{
    function edit()
    {
        if (empty($_GET) and empty($_POST))
        {
            $form['name'] = \Swoole\Form::input('name');
            $form['intro'] = \Swoole\Form::input('intro');
            $this->assign('form', $form);
            $this->display();
        }
        elseif (!empty($_GET['id']) and empty($_POST))
        {
            $id = (int)$_GET['id'];
            $res = table("project")->get($id)->get();
            $form['name'] = \Swoole\Form::input('name',$res['name']);
            $form['intro'] = \Swoole\Form::input('intro',$res['intro']);
            $form['id'] = \Swoole\Form::hidden('id',$res['id']);
            $this->assign('form', $form);
            $this->display();
        }
        elseif(empty($_POST['id']))
        {
            $inserts['name'] = $_POST['name'];
            $inserts['intro'] = $_POST['intro'];
            $res = table("project")->put($inserts);
            if ($res)
            {
                \Swoole\JS::js_goto("添加成功",'/project/plist/');
            }
            else
            {
                \Swoole\JS::js_goto("添加失败",'/project/plist/');
            }
        }
        elseif (!empty($_POST['id']))
        {
            $id = (int)$_POST['id'];
            $inserts['name'] = $_POST['name'];
            $inserts['intro'] = $_POST['intro'];
            $res = table("project")->set($id,$inserts);
            if ($res)
            {
                \Swoole\JS::js_goto("修改成功",'/project/plist/');
            }
            else
            {
                \Swoole\JS::js_goto("修改失败",'/project/plist/');
            }
        }

    }

    function plist()
    {
        $gets = array();
        if (!empty($_POST['id']))
        {
            $id = intval(trim($_POST['id']));
            $gets['where'][] = "id={$id}";
            $_GET['id'] = $id;
        }
        if (!empty($_POST['name']))
        {
            $name = trim($_POST['name']);
            $gets['where'][] = "name like '%{$name}%'";
            $_GET['name'] = $name;
        }
        $gets["order"] = 'add_time desc';
        $gets['page'] = !empty($_GET['page'])?$_GET['page']:1;
        $gets['pagesize'] = 20;
        $data = table("project")->gets($gets,$pager);
        $this->assign('pager', array('total'=>$pager->total,'render'=>$pager->render()));
        $this->assign('data', $data);
        $this->display();
    }
}