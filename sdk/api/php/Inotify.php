<?php
namespace Sdk;

class Inotify
{
    public  $path;
    public $handler;
    public $watcher;

    public $mode = 1; //监听目录的模式 默认监听一层目录下的所有文件


    public function __construct($config)
    {
        if (!empty($config['path']))
        {
            $this->path = $config['path'];
        }
        else
        {
            exit('must set path~');
        }
        $this->mode = !empty($config['mode'])?$config['mode']:1;
    }


    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    public function setWatcher()
    {
        if (is_dir($this->path))
        {
            if ($this->mode == 1)
            {
                require __DIR__."/watcher/Dir.php";
                $this->watcher = new \Sdk\Watcher\Dir($this);
            }
            else
            {
                require __DIR__."/watcher/DirByDay.php";
                $this->watcher = new \Sdk\Watcher\DirByDay($this);
            }
        }
        elseif (is_file($this->path))
        {
            require __DIR__."/watcher/File.php";
            $this->watcher = new \Sdk\Watcher\File($this);
        }
        else
        {
            exit('params wrong~');
        }
    }

    public function start()
    {
        if (empty($this->handler))
        {
            exit('please set handler~');
        }
        $this->setWatcher();
        $this->watcher->start();
    }
}