<?php
namespace App\Controller;
use Swoole;

class User extends \App\LoginController
{
    function edit()
    {
        //\Swoole\Error::dbd();
        $id = $_COOKIE['yyuid'];
        if (empty($id))
        {
            \Swoole\JS::js_back("操作不合法");
        }

        $user_info = table("user")->get($id,'uid')->get();
        if (!empty($_POST))
        {
            //编辑
            $id = $_POST['id'];
            unset($_POST['id']);
            $res = table("user")->set($id,$_POST);
            if ($res)
            {
                \Swoole\JS::js_goto("更新成功",'/user/edit_self');
            }
            else
            {
                \Swoole\JS::js_goto("更新失败",'/user/edit_self');
            }
        }
        else
        {
            //展示
            $form['id'] = \Swoole\Form::hidden('id',$user_info['id']);
            $form['mobile'] = \Swoole\Form::input('mobile',$user_info['mobile']);
            $form['realname'] = \Swoole\Form::input('realname',$user_info['realname'],array('type'=>'tel'));
        }
        $this->assign('form', $form);
        $this->display();
    }

    function add()
    {
        //\Swoole::$php->db->debug = true;
        if (empty($_GET) and empty($_POST))
        {
            $tmp = table('project')->gets(array("order"=>"id desc"));
            $project = array();
            foreach ($tmp as $t)
            {
                $project[$t['id']] = $t['name'];
            }
            $form['project_id'] = \Swoole\Form::muti_select('project_id[]',$project,array(),null,array('class'=>'select2 select2-offscreen','multiple'=>"1",'style'=>"width:100%" ),false);
            $form['uid'] = \Swoole\Form::input('uid');
            $form['mobile'] = \Swoole\Form::input('mobile');
            $form['realname'] = \Swoole\Form::input('realname');
            $form['username'] = \Swoole\Form::input('username');
            $this->assign('form', $form);
            $this->display();
        }
        elseif (!empty($_GET['id']) and empty($_POST))
        {
            $id = (int)$_GET['id'];
            $user = table('user')->get($id)->get();

            $tmp = table('project')->gets(array("order"=>"id desc"));
            $project = array();
            foreach ($tmp as $t)
            {
                $project[$t['id']] = $t['name'];
            }
            $form['project_id'] = \Swoole\Form::muti_select('project_id[]',$project,explode(',',$user['project_id']),null,array('class'=>'select2 select2-offscreen','multiple'=>"1",'style'=>"width:100%" ),false);
            $form['uid'] = \Swoole\Form::input('uid',$user['uid']);
            $form['mobile'] = \Swoole\Form::input('mobile',$user['mobile']);
            $form['realname'] = \Swoole\Form::input('realname',$user['realname']);
            $form['username'] = \Swoole\Form::input('username',$user['username']);
            $form['id'] = \Swoole\Form::hidden('id',$user['id']);
            $this->assign('form', $form);
            $this->display();
        }
        elseif (!empty($_POST['id']))
        {
            $id = (int)$_POST['id'];
            $inserts['realname'] = $_POST['realname'];
            $inserts['username'] = $_POST['username'];
            $inserts['uid'] = (int)$_POST['uid'];
            $inserts['project_id'] = implode(',',$_POST['project_id']);
            $inserts['mobile'] = $_POST['mobile'];

            $res = table("user")->set($id,$inserts);
            if ($res)
            {
                \Swoole\JS::js_goto("修改成功",'/user/ulist/');
            }
            else
            {
                \Swoole\JS::js_goto("修改失败",'/user/ulist/');
            }
        }
        else
        {
            $inserts['realname'] = $_POST['realname'];
            $inserts['username'] = $_POST['username'];
            $inserts['uid'] = (int)$_POST['uid'];
            $inserts['project_id'] = implode(',',$_POST['project_id']);
            $inserts['mobile'] = $_POST['mobile'];

            $res = table("user")->put($inserts);
            if ($res)
            {
                \Swoole\JS::js_goto("添加成功",'/user/ulist//');
            }
            else
            {
                \Swoole\JS::js_goto("添加失败",'/user/ulist/');
            }
        }
    }

    function ulist()
    {
        $gets = array();
        if (!empty($_POST['uid']))
        {
            $uid = intval(trim($_POST['uid']));
            $gets['where'][] = "uid={$uid}";
            $_GET['uid'] = $uid;
        }
        if (!empty($_POST['username']))
        {
            $name = trim($_POST['username']);
            $gets['where'][] = "username like '%{$name}%'";
            $_GET['username'] = $name;
        }
        if (!empty($_POST['realname']))
        {
            $name = trim($_POST['realname']);
            $gets['where'][] = "realname like '%{$name}%'";
            $_GET['realname'] = $name;
        }

        $gets["order"] = 'addtime desc';
        $gets['page'] = !empty($_GET['page'])?$_GET['page']:1;
        $gets['pagesize'] = 20;
        $data = table("user")->gets($gets,$pager);
        $this->assign('pager', array('total'=>$pager->total,'render'=>$pager->render()));
        $this->assign('data', $data);
        $this->display();
    }

    function tip()
    {
        $this->display();
    }
}