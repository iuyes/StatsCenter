<?php
namespace App\Controller;
use Swoole;

class Setting extends \App\LoginController
{
    public $prefix = 'YYPUSH';
    public $alert_types = array(
        1 => "谈窗",
        2 => '短信'
    );

    function look_sess()
    {
        echo "<pre>";
        var_dump($_SESSION);
    }

    function add_interface()
    {
        $gets['select'] = 'uid,username,realname,mobile';
        $tmp = table('user')->gets($gets);
        $user = array();
        $mobile = array();
        foreach ($tmp as $t)
        {
            $name = !empty($t['realname'])?$t['realname']:'';
            $user[$t['uid']] = $name.$t['username'];
            if (!empty($t['mobile']))
                $mobile[$t['mobile']] = $name.$t['mobile'];
        }
        //\Swoole\Error::dbd();
        //新增操作
        if (empty($_GET['id']) and empty($_POST))
        {
            $params['title'] = '新增接口';
            $m_params['select'] = 'id,name';
            $module = table('module')->getMap($m_params,'name');
            $form['module_id'] = \Swoole\Form::select('module_id',$module,'',null,array('class'=>'select2 select2-offscreen','style'=>"width:100%"));
            $form['alert_uids'] = \Swoole\Form::muti_select('alert_uids[]',$user,array(),null,array('class'=>'select2 select2-offscreen','multiple'=>"1",'style'=>"width:100%" ),false);
            $form['owner_uid'] = \Swoole\Form::select('owner_uid',$user,'',null,array('class'=>'select2 select2-offscreen','style'=>"width:100%"));
            $form['backup_uids'] = \Swoole\Form::muti_select('backup_uids[]',$user,array(),null,array('class'=>'select2 select2-offscreen','multiple'=>"1",'style'=>"width:100%" ),false);
            $this->assign('user', $user);
            $this->assign('form', $form);
            $this->assign('data', $params);
        } elseif (!empty($_GET['id']) and empty($_POST)) { //修改页面
            $id = (int)$_GET['id'];
            $data = table('interface')->get($id)->get();
            $m_params['select'] = 'id,name';
            $module = table('module')->getMap($m_params,'name');
            $form['module_id'] = \Swoole\Form::select('module_id',$module,$data['module_id'],null,array('class'=>'select2 select2-offscreen','style'=>"width:100%"));
            $form['alert_uids'] = \Swoole\Form::muti_select('alert_uids[]',$user,!empty($data['alert_uids'])?explode(',',$data['alert_uids']):array(),null,array('class'=>'select2 select2-offscreen','multiple'=>"1",'style'=>"width:100%" ),false);
            $form['owner_uid'] = \Swoole\Form::select('owner_uid',$user,$data['owner_uid'],null,array('class'=>'select2 select2-offscreen','style'=>"width:100%" ),false);
            $form['backup_uids'] = \Swoole\Form::muti_select('backup_uids[]',$user,!empty($data['backup_uids'])?explode(',',$data['backup_uids']):array(),null,array('class'=>'select2 select2-offscreen','multiple'=>"1",'style'=>"width:100%" ),false);
            $this->assign('form', $form);
            $params['title'] = '修改接口';
            if (empty($data))
            {
                $msg['code'] = 400;
                $msg['message'] = "错误操作";
                $this->assign('msg', $msg);
            } else {
                $data['alert_types'] = explode('|',$data['alert_types']);
                $params['data'] = $data;
            }
            $this->assign('data', $params);
        } elseif (!empty($_POST)) { //入库操作
            if (empty($_POST['id'])) //新增
            {
                $params['title'] = '新增接口';

                $in['name'] = trim($_POST['name']);
                $in['module_id'] = trim($_POST['module_id']);
                $in['alias'] = trim($_POST['alias']);
                $in['succ_hold'] = trim($_POST['succ_hold']);
                $in['wave_hold'] = trim($_POST['wave_hold']);
                $in['alert_int'] = trim($_POST['alert_int']);
                $in['enable_alert'] = trim($_POST['enable_alert']);

                $alert_uids = '';
                if (!empty($_POST['alert_uids']))
                {
                    $alert_uids = implode(',',$_POST['alert_uids']);
                }
                $in['alert_uids'] = $alert_uids;

                $alert_types = '';
                if (!empty($_POST['$alert_types']))
                {
                    $alert_types = implode('|',$_POST['$alert_types']);
                }
                $in['alert_types'] = $alert_types;

                $in['owner_uid'] = trim($_POST['owner_uid']);

                $backup_uids = '';
                if (!empty($_POST['backup_uids']))
                {
                    $backup_uids = implode(',',$_POST['backup_uids']);
                }
                $in['backup_uids'] = $backup_uids;
                $in['intro'] = trim($_POST['intro']);
                $in['owner_uid'] = $_SESSION['userinfo']['yyuid'];
                $c = table('interface')->count(array('name' => $in['name'],'module_id' => $in['module_id']));
                if ($c > 0)
                {
                    \Swoole\JS::js_goto("操作失败,该模块下已经存在{$in['name']}接口",'/setting/add_interface/');
                }
                else
                {
                    $insert_id = table('interface')->put($in);
                    if ( $insert_id )
                    {
                        //更新redis 通知报警server
                            $this->_save_interface($insert_id,$in);
                        //
                        $params['data'] = $in;
                        \Swoole\JS::js_goto("操作成功","/setting/add_interface/?id={$insert_id}");
                        \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} add new interface {$insert_id} ".print_r($in,1));
                    }
                    else
                    {
                        \Swoole\JS::js_goto("操作失败","/setting/add_interface");
                    }
                }
                $this->assign('data', $params);
            } else { //修改
                $params['title'] = '修改接口';
                $id = (int)$_POST['id'];
                $in['name'] = trim($_POST['name']);
                $in['module_id'] = trim($_POST['module_id']);
                $in['alias'] = trim($_POST['alias']);
                $in['succ_hold'] = trim($_POST['succ_hold']);
                $in['wave_hold'] = trim($_POST['wave_hold']);
                $in['alert_int'] = trim($_POST['alert_int']);
                $in['enable_alert'] = trim($_POST['enable_alert']);

                $alert_uids = '';
                if (!empty($_POST['alert_uids']))
                {
                    $alert_uids = implode(',',$_POST['alert_uids']);
                }
                $in['alert_uids'] = $alert_uids;

                $alert_types = '';
                if (!empty($_POST['alert_types']))
                {
                    $alert_types = implode('|',$_POST['alert_types']);
                }
                $in['alert_types'] = $alert_types;

                $in['owner_uid'] = trim($_POST['owner_uid']);

                $backup_uids = '';
                if (!empty($_POST['backup_uids']))
                {
                    $backup_uids = implode(',',$_POST['backup_uids']);
                }
                $in['backup_uids'] = $backup_uids;

                $in['intro'] = trim($_POST['intro']);
                $condition['name'] = $in['name'];
                $condition['module_id'] = $in['module_id'];
                $condition['where'][] = "id != $id";
                $c = table('interface')->count($condition);
                if ($c > 0)
                {
                    \Swoole\JS::js_goto("操作失败,该模块下已经存在{$in['name']}接口","/setting/add_interface/?id={$id}");
                } else {
                    $res = table('interface')->set($id,$in);
                    if ($res)
                    {
                        \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} modified interface {$id} with ".print_r($in,1));
                        $this->_save_interface($id,$in);
                        \Swoole\JS::js_goto("操作成功","/setting/add_interface/?id={$id}");
                    } else {
                        \Swoole\JS::js_goto("操作失败","/setting/add_interface/?id={$id}");
                    }
                }
                $this->assign('data', $params);
            }
        }
        $this->display();
    }

    function delete_interface()
    {
        //\Swoole\Error::dbd();
        $id = (int)$_GET['id'];
        $uid = $_SESSION['userinfo']['yyuid'];
        $data = table('interface')->get($id)->get();
        if ($data['owner_uid'] == 0)
        {
            \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} try to del interface {$id} failed cause of owner_uid==0");
            $return['status'] = 300;
            $return['msg'] = '暂时不能删除';
        } elseif($uid != $data['owner_uid']) {
            \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} try to del interface {$id} failed cause of has no privilege");
            $return['status'] = 400;
            $return['msg'] = '没有权限删除';
        } else {
            $res = table('interface')->del($id);
            if ($res) {
                \Swoole::$php->log->put("{$_SESSION['userinfo']['username']}  del interface {$id} success ".print_r($data,1));
                if (\Swoole::$php->redis->delete($this->prefix."::".$id) == 1)
                {
                    \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} del from redis hash $this->prefix::$id success ");
                }
                else
                {
                    \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} del from redis hash $this->prefix::$id failed ");
                }

                if (\Swoole::$php->redis->sRemove($this->prefix, $id) == 1)
                {
                    \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} del from redis set {$id} success ");
                }
                else
                {
                    \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} del from redis set {$id} failed ");
                }

                $return['status'] = 0;
                $return['msg'] = '操作成功';
            } else {
                \Swoole::$php->log->put("{$_SESSION['userinfo']['username']}  del interface {$id} failed with db error");
                $return['status'] = 500;
                $return['msg'] = '操作失败';
            }
        }

        return json_encode($return);
    }

    function interface_list()
    {
        //Swoole\Error::dbd();
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
        $users['select'] = 'uid,username,realname';
        $tmp = table('user')->gets($users);
        $user = array();
        foreach ($tmp as $t)
        {
            $name = !empty($t['realname'])?$t['realname']:'';
            $user[$t['uid']] = $name.$t['username'];
        }
        $gets['page'] = !empty($_GET['page'])?$_GET['page']:1;
        $gets['pagesize'] = 20;
        $gets['order'] = 'id desc';
        $data = table('interface')->gets($gets,$pager);
        foreach ($data as $k => $v)
        {
            $data[$k]['owner_uid_name'] = $user[$v['owner_uid']];
        }
        $this->assign('pager', array('total'=>$pager->total,'render'=>$pager->render()));
        $this->assign('data', $data);
        $this->display();
    }

    function list_data()
    {
        $gets = array();
        $gets['select'] = 'id, name,alias,succ_hold,wave_hold, owner_name, addtime';
        $gets['order'] = 'id desc';
        $data = table('interface')->gets($gets);
        if (!empty($data))
        {
            $ret['list'] = $data;
            $ret['status'] = 0;

        } else {
            $ret['list'] = array();
            $ret['status'] = 1;
        }
        return json_encode($ret);
    }

    function add_module()
    {
        //\Swoole\Error::dbd();
        if (empty($_GET['id']) and empty($_POST))
        {
            $gets['select'] = 'uid,username,realname';
            $tmp = table('user')->gets($gets);
            $user = array();
            foreach ($tmp as $t)
            {
                $name = !empty($t['realname'])?$t['realname']:'';
                $user[$t['uid']] = $name.$t['username'];
            }
            $form['name'] = \Swoole\Form::input('name');
            $form['intro'] = \Swoole\Form::text('intro');
            $form['owner_uid'] = \Swoole\Form::select('owner_uid',$user,'',null,array('class'=>'select2 select2-offscreen','style'=>"width:100%" ));
            $form['backup_uids'] = \Swoole\Form::muti_select('backup_uids[]',$user,array(),null,array('class'=>'select2 select2-offscreen','multiple'=>"1",'style'=>"width:100%" ),false);

            $tmp = table('project')->gets(array("order"=>"id desc"));
            $project = array();
            foreach ($tmp as $t)
            {
                $project[$t['id']] = $t['name'];
            }
            $form['project_id'] = \Swoole\Form::select('project_id',$project,'',null,array('class'=>'select2 select2-offscreen','style'=>"width:100%" ));
            $this->assign('form', $form);
            $this->display();
        }
        elseif (!empty($_GET['id']) and empty($_POST))
        {
            $id = (int)$_GET['id'];
            $module = table("module")->get($id)->get();
            $gets['select'] = '*';
            $tmp = table('user')->gets($gets);
            $user = array();
            foreach ($tmp as $t)
            {
                $name = !empty($t['realname'])?$t['realname']:'';
                $user[$t['uid']] = $name.$t['username'];
            }
            $form['id'] = \Swoole\Form::hidden('id',$module['id']);
            $form['name'] = \Swoole\Form::input('name',$module['name']);
            $form['intro'] = \Swoole\Form::text('intro',$module['intro']);
            $form['owner_uid'] = \Swoole\Form::select('owner_uid',$user,$module['owner_uid'],null,array('class'=>'select2 select2-offscreen','style'=>"width:100%" ));
            $form['backup_uids'] = \Swoole\Form::muti_select('backup_uids[]',$user,explode(',',$module['backup_uids']),null,array('class'=>'select2 select2-offscreen','multiple'=>"1",'style'=>"width:100%" ),false);

            $tmp = table('project')->gets(array("order"=>"id desc"));
            $project = array();
            foreach ($tmp as $t)
            {
                $project[$t['id']] = $t['name'];
            }
            $form['project_id'] = \Swoole\Form::select('project_id',$project,$module['project_id'],null,array('class'=>'select2 select2-offscreen','style'=>"width:100%" ));
            $this->assign('form', $form);
            $this->display();
        }
        elseif (!empty($_POST) and empty($_POST['id']))
        {
            $in['name'] = trim($_POST['name']);
            $in['owner_uid'] = trim($_POST['owner_uid']);
            $in['project_id'] = trim($_POST['project_id']);
            $backup_uids = '';
            if (!empty($_POST['backup_uids']))
            {
                $backup_uids = implode(',',$_POST['backup_uids']);
            }
            $in['backup_uids'] = $backup_uids;
//            $in['backup_name'] = trim($_POST['backup_name']);
            $in['intro'] = trim($_POST['intro']);

            $c = table('module')->count(array('name' => $in['name']));
            if ($c > 0)
            {
                \Swoole\JS::js_goto("操作失败,已存在同名模块","/setting/add_module/");
            }
            else
            {
                $id = table('module')->put($in);
                if ($id)
                {
                    \Swoole\JS::js_goto("操作成功","/setting/add_module/");
                    \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} add module success $id : ". print_r($in,1));
                }
                else
                {
                    \Swoole\JS::js_goto("操作失败","/setting/add_module/");
                    \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} add module failed:  ". print_r($in,1));
                }
            }
        }
        else
        {
            $id = (int)$_POST['id'];
            $in['name'] = trim($_POST['name']);
            $in['owner_uid'] = trim($_POST['owner_uid']);
            $in['project_id'] = trim($_POST['project_id']);
            $backup_uids = '';
            if (!empty($_POST['backup_uids']))
            {
                $backup_uids = implode(',',$_POST['backup_uids']);
            }
            $in['backup_uids'] = $backup_uids;
            $in['intro'] = trim($_POST['intro']);
            $where['name'] = $in['name'];
            $where['where'][] = "id !=$id";
            $c = table('module')->count($where);
            if ($c > 0)
            {
                \Swoole\JS::js_goto("操作失败,已存在同名模块","/setting/module_list/");
            }
            else
            {

                $res = table('module')->set($id,$in);
                if ($res)
                {
                    \Swoole\JS::js_goto("操作成功","/setting/module_list/");
                    \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} modify module success $id : ". print_r($in,1));
                }
                else
                {
                    \Swoole\JS::js_goto("操作失败","/setting/module_list/");
                    \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} modify module failed:  ". print_r($in,1));
                }
            }
        }
    }

    //判断符合包就那报警条件的数据  转存入redis
    function _save_interface($id,$interface)
    {
        $params = array();
        if (  ($interface['succ_hold'] > 0 or $interface['wave_hold'] > 0)
            and  (!empty($interface['alert_uids']) and $interface['alert_int'] > 0 )
            )
        {
            $gets['select'] = 'uid,mobile';
            $gets['where'][] = 'uid in ('.$interface['alert_uids'].')';
            $tmp = table('user')->gets($gets);
            $user = array();
            foreach ($tmp as $t)
            {
                if (!empty($t['mobile']))
                    $user[$t['uid']] = $t['mobile'];
            }

            $params['module_id'] = $interface['module_id'];
            $params['interface_id'] = $id;
            $params['interface_name'] = $interface['name'];
            $res = table('module')->get($interface['module_id'])->get();
            $params['module_name'] = $res['name'];
            $params['enable_alert'] = $interface['enable_alert'];
            $params['alert_types'] = $interface['alert_types'];
            $params['alert_uids'] = $interface['alert_uids'];
            $params['alert_mobiles'] = implode(',',$user);
            $params['alert_int'] = $interface['alert_int'];
            $params['succ_hold'] = $interface['succ_hold'];
            $params['wave_hold'] = $interface['wave_hold'];
            $key = $this->prefix."::".$id;
            \Swoole::$php->redis->hMset($key, $params);
            \Swoole::$php->redis->sAdd($this->prefix, $id);
            \Swoole::$php->log->put("{$_SESSION['userinfo']['username']} add redis : interface_id-{$id} key-{$key} ". print_r($params,1));
        }
    }

    function module_list()
    {
        //Swoole\Error::dbd();
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
        $users['select'] = '*';
        $tmp = table('user')->gets($users);
        $user = array();
        foreach ($tmp as $t)
        {
            $name = !empty($t['realname'])?$t['realname']:'';
            $user[$t['uid']] = $name.$t['username'];
        }
        $gets['page'] = !empty($_GET['page'])?$_GET['page']:1;
        $gets['pagesize'] = 20;
        $gets['order'] = 'id desc';
        $data = table('module')->gets($gets,$pager);
        foreach ($data as $k => $v)
        {
            $back_names = array();
            if (!empty($v['backup_uids']))
            {
                $back_ids = explode(',',$v['backup_uids']);
                foreach($back_ids as $bid)
                {
                    $back_names[] = $user[$bid];
                }
            }
            $data[$k]['backup_uids_name'] = implode(',',$back_names);
            $data[$k]['owner_uid_name'] = $user[$v['owner_uid']];
        }
        $this->assign('pager', array('total'=>$pager->total,'render'=>$pager->render()));
        $this->assign('data', $data);
        $this->display();
    }
}