<?php
define('DEBUG', 'on');
define('WEBPATH', __DIR__);

require __DIR__ . '/../../vendor/autoload.php';
Swoole\Loader::vendor_init();
Swoole::$php->config->setPath(__DIR__.'/configs/');

class Cron
{
    public $jobs;
    public $times;

    public function set_job($name)
    {
        $this->jobs[] = $name;
    }

    function log($log)
    {
        echo "[".date("Y-m-d H:i:s")."] ".$log.PHP_EOL;
    }

    function time_mark($key)
    {
        $this->times[$key][] = microtime(true);
    }
    function start($name)
    {
        $this->log("start running:$name");
        $this->time_mark($name);
        $name();
        $this->end($name);
    }
    function end($name)
    {
        $this->log("end running:$name");
        $this->time_mark($name);
        $this->log("elapsed:".($this->times[$name][1] - $this->times[$name][0]));
    }
    function run()
    {
        if (!empty($this->jobs))
        {
            foreach ($this->jobs as $name)
            {
                $this->start($name);
            }
        }
        else
        {
            $this->log("job list is empty");
        }
    }
}


function del_log()
{
    //\Swoole\Error::dbd();
    $now = date("Y-m-d",time()-3600*24*30);
    $sql = "show tables like 'logs_%'";
    $res = Swoole::$php->db->query($sql)->fetchall();
    if (!empty($res))
    {
        foreach ($res as $re)
        {
            foreach ($re as $r)
            {
                $tmp = explode('_',$r);
                if ($tmp[1] < $now)
                {
                    $sql = "DROP TABLE IF EXISTS `$r`";
                    $res = Swoole::$php->db->query($sql);
                    echo $sql;
                    if ($res)
                    {
                        echo ' success '.PHP_EOL;
                    }
                    else
                    {
                        echo ' failed '.PHP_EOL;
                    }

                }
            }
        }
    }
    else
    {
        echo "no table to delete ".PHP_EOL;
    }
}

function del_stats()
{
    $time_key = date("Y-m-d",time()-3600*24*30);
    $tables = array('stats','stats_client','stats_server');
    foreach ($tables as $table)
    {
        $sql1 = "DELETE FROM `".$table."` where date_key < '$time_key'";
        $res = Swoole::$php->db->query($sql1);
        echo $sql1;
        if ($res)
        {
            echo ' success '.PHP_EOL;
        }
        else
        {
            echo ' failed '.PHP_EOL;
        }
    }
}

$cron = new Cron();
$cron->set_job('del_log');
$cron->set_job('del_stats');
$cron->run();