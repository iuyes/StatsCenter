<?php
namespace Sdk\Watcher;
require __DIR__."/File.php";
defined("DIR_MASK") || define("DIR_MASK", IN_MODIFY | IN_CREATE | IN_DELETE );
class Dir
{
    /*
     * 监听一个目录下所有文件
     * 目录下新增文件 自动监听
     *      删除文件 删除监听
     */
    public $path;
    public $handler;
    public $filenames = array();

    public $ifds = array();//inofity 实例
    public $wds = array(); //监听描述符

    public $fds = array();//文件描述符
    public $pos = array();//每个文件的当前位置

    const DIR_MASK = DIR_MASK;
    const BUFFER_SIZE = 8192;

    public function __construct($inotify)
    {
        $this->path = $inotify->path;
        $this->handler = $inotify->handler;
    }

    function getFileNames()
    {
        $tmps = scandir($this->path);
        foreach($tmps as $val)
        {
            if ($val == '.' or $val == '..')
            {
                continue;
            }
            $this->filenames[$val] = $val;
        }
    }

    function initFiles()
    {
        $this->getFileNames();
        foreach($this->filenames as $file)
        {
            $this->ifds[$file] = inotify_init();
            $this->wds[$file] = inotify_add_watch($this->ifds[$file], $this->path.'/'.$file, self::DIR_MASK);
            $this->fds[$file] = fopen($this->path.'/'.$file,'r');
            stream_set_blocking($this->fds[$file], 0);
            $this->pos[$file] = filesize($this->path.'/'.$file);
        }
    }

    function addFiles()
    {
        $tmps = scandir($this->path);
        foreach ($tmps as $val)
        {
            if ($val == '.' or $val == '..')
            {
                continue;
            }
            if (!in_array($val,$this->filenames))
            {
                $this->filenames[$val] = $val;
                $this->ifds[$val] = inotify_init();
                $this->wds[$val] = inotify_add_watch($this->ifds[$val], $this->path.'/'.$val, self::DIR_MASK);
                $this->fds[$val] = fopen($this->path.'/'.$val,'r');
                stream_set_blocking($this->fds[$val], 0);
                $this->pos[$val] = filesize($this->path.'/'.$val);
            }
        }
    }

    function deleteFiles()
    {
        $tmps = scandir($this->path);
        foreach ($this->filenames as $file)
        {
            if (!in_array($file,$tmps))
            {
                unset($this->filenames[$file]);
                inotify_rm_watch($this->ifds[$file],$this->wds[$file]);
                unset($this->ifds[$file]);
                unset($this->wds[$file]);
                fclose($this->fds[$file]);
                unset($this->fds[$file]);
                unset($this->pos[$file]);
            }
        }

    }

    function watchDir()
    {
        $this->ifds['dir'] = inotify_init();
        $this->wds['dir'] = inotify_add_watch($this->ifds['dir'], $this->path, self::DIR_MASK);
    }

    function watchFiles()
    {
        $this->initFiles();
        while(true)
        {
            $fds = $this->ifds;
            if (stream_select($fds, $w = null, $e = null, 0))
            {
                foreach ($fds as $file => $ifd)
                {
                    $events = inotify_read($ifd);
                    foreach ($events as $event)
                    {
                        switch (true)
                        {
                            case ($event['mask'] & IN_MODIFY):
                                if (isset($this->fds[$file]))
                                {
                                    fseek($this->fds[$file],$this->pos[$file]);
                                    $buf = '';
                                    while (!feof($this->fds[$file])) {
                                        $buf .= fread($this->fds[$file],self::BUFFER_SIZE);
                                    }
                                    //var_dump($file,$this->fds[$file],$buf);
                                    $this->handler->InModify($buf,$file);
                                    $this->pos[$file] = ftell($this->fds[$file]);
                                }
                                break;
                            case ($event['mask'] & IN_CREATE):
                                //echo 'add files';
                                $this->addFiles();
                                break;
                            case ($event['mask'] & IN_DELETE):
                                //echo 'dell files';
                                $this->deleteFiles();
                                break;
                        }
                    }
                }
            }
        }
    }

    function start()
    {
        $this->watchDir();
        $this->watchFiles();
    }
}