<?php
namespace App\Controller;
use Swoole;

require_once APPSPATH.'/include/dwUDB.php';

class Page extends Swoole\Controller
{
    function index()
    {
        //https
        if ($_SERVER['SERVER_PORT'] != 80)
        {
            $this->swoole->http->redirect($this->swoole->config['login']['login_url']);
        }
        $this->swoole->session->start();
        if (!empty($_SESSION['isLogin']))
        {
            $this->swoole->http->redirect($this->swoole->config['login']['home_url']);
        }
        else
        {
            if (!empty($_GET['refer']))
            {
                $refer = '?refer='.$_GET['refer'];
            }
            else
            {
                $refer = '';
            }
            $this->assign('refer', $refer);
            $this->display();
        }
    }

    function logout()
    {
        $this->swoole->session->start();
        Swoole\Cookie::delete('username');
        Swoole\Cookie::delete('password');
        Swoole\Cookie::delete('osinfo');
        Swoole\Cookie::delete('oauthCookie');
        unset($_SESSION['userinfo'], $_SESSION['isLogin']);
        $this->swoole->http->redirect($this->swoole->config['login']['login_url']);
    }

    function login()
    {
        $this->swoole->session->start();
        if (!empty($_SESSION['isLogin']))
        {
            home:
                if (!empty($_GET['refer']))
                {
                    $refer = base64_decode($_GET['refer']);
                    $url = WEBROOT.$refer;
                }
                else
                {
                    $url = $this->swoole->config['login']['home_url'];
                }
                $this->swoole->http->redirect($url);
        }
        else
        {
            if (!empty($_GET['refer']))
            {
                $refer = '?refer='.$_GET['refer'];
            }
            else
            {
                $refer = '';
            }
            $login = new \dwUDB;
            $result = $login->isLogin();
            if (empty($result))
            {
                $this->swoole->http->redirect($this->swoole->config['login']['login_url'].$refer);
            }
            else
            {
                //$this->collect_user();
                $uid = $_COOKIE['yyuid'];
                $gets = array();
                $gets['uid'] = $uid;
                $gets['where'][] = "project_id !=''";
                if (!table('user')->exists(array('uid' => $uid)))
                {
                    $this->collect_user();
                    $this->display("page/tip.php");
                    exit;
                }
                elseif (!table('user')->exists($gets))
                {
                    $this->display("page/tip.php");
                    exit;
                }
                else
                {
                    $_SESSION['userinfo'] = $result;
                    $_SESSION['isLogin'] = true;
                    $_SESSION['realname'] = urldecode($_COOKIE['sysop_privilege_nick_name']);
                    goto home;
                }
            }
        }
    }

    function collect_user()
    {
        $uid = $_COOKIE['yyuid'];
        if (!table('user')->exists(array('uid' => $uid)))
        {
            $puts['uid'] = $uid;
            $puts['username'] = $_COOKIE['username'];
            if (isset($_COOKIE['sysop_privilege_nick_name']) and !empty($_COOKIE['sysop_privilege_nick_name']))
                $puts['realname'] = urldecode($_COOKIE['sysop_privilege_nick_name']);
            table('user')->put($puts);
        }
    }
}