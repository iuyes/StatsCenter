<?php
namespace Sdk\Watcher;
require __DIR__."/File.php";
defined("DIR_MASK") || define("DIR_MASK", IN_MODIFY | IN_CREATE | IN_DELETE );
class DirByDay
{
    /*
     * 监听一个二级目录下 所有目录和当前时间为名称目录下的所有文件
     * 目录下新增文件 自动监听
     *      删除文件 删除监听
     */
    public $path;
    public $handler;
    public $filenames = array();
    public $dirs = array();

    public $ifds = array();//inofity 实例
    public $wds = array(); //监听描述符

    public $fds = array();//文件描述符
    public $pos = array();//每个文件的当前位置

    public $format = "Ymd";

    const DIR_MASK = DIR_MASK;
    const BUFFER_SIZE = 8192;

    public function __construct($inotify)
    {
        $this->path = $inotify->path;
        $this->handler = $inotify->handler;
    }

    function log($log)
    {
        echo "[".date("Y-m-d H:i:s")."] - ".$log."\n";
    }

    function getDayName()
    {
        return date($this->format);
    }

    /*
     * 获取当前所有需要监听的文件
     */
    function getFileNames($path)
    {
        if (is_dir($path))
        {
            $dirs = scandir($path);
            foreach ($dirs as $val)
            {
                if ($val == '.' || $val == '..')
                {
                    continue;
                }
                $this->filenames[$path.'/'.$val] = $path.'/'.$val;
            }
        }
        else
        {
            exit($path." dir is not exists");
        }
    }

    function watchAFile($file)
    {
        $this->filenames[$file] = $file;

        $this->ifds[$file] = inotify_init();
        $this->wds[$file] = inotify_add_watch($this->ifds[$file], $file, self::DIR_MASK);
        $this->fds[$file] = fopen($file,'r');
        stream_set_blocking($this->fds[$file], 0);
        $this->pos[$file] = filesize($file);
        $this->log("add file $file ".print_r($this->filenames,1).print_r($this->wds,1));
    }

    function watchADir($path)
    {
        $this->dirs[$path] = $path;
        $this->ifds[$path] = inotify_init();
        $this->wds[$path] = inotify_add_watch($this->ifds[$path], $path, self::DIR_MASK);
        $this->log("add dir $path ".print_r($this->dirs,1).print_r($this->wds,1));
    }

    /*
     * 停止监控目录下的文件
     */
    function stopWatchDir($dirname)
    {
        $this->log("stop dir $dirname --before ".print_r($this->dirs,1).print_r($this->wds,1));
        $path = $this->path.'/'.$dirname;
        if (in_array($path,$this->dirs))
        {
            unset($this->dirs[$path]);
            inotify_rm_watch($this->ifds[$path],$this->wds[$path]);
            unset($this->ifds[$path]);
            unset($this->wds[$path]);
        }
        $this->log("stop dir $dirname --after ".print_r($this->dirs,1).print_r($this->wds,1));

        $this->log("stop file under the dir $dirname --before ".print_r($this->filenames,1).print_r($this->wds,1));
        $dirs = scandir($path);
        foreach ($dirs as $filename)
        {
            if ($filename == '.' || $filename == '..')
            {
                continue;
            }
            $file = $path.'/'.$filename;
            if (in_array($file,$this->filenames))
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
        $this->log("stop file under the dir $dirname --after ".print_r($this->filenames,1).print_r($this->wds,1));
    }

    function initFiles($path)
    {
        $this->getFileNames($path);
        foreach($this->filenames as $file)
        {
            $this->watchAFile($file);
        }
        return true;
    }

    function addFile($filename)
    {
        //监听当前正在监听的文件夹
        $file = $this->path.'/'.$this->getDayName().'/'.$filename;
        if (!in_array($file,$this->filenames))
        {
            $this->watchAFile($file);
        }
    }

    function addDir($dirname)
    {
        $path = $this->path.'/'.$dirname;
        if (!in_array($path,$this->dirs))
        {
            $this->watchADir($path);
            $this->watchFiles($path);
        }
    }

    function deleteFile($filename)
    {
        $file = $this->path.'/'.$this->getDayName().'/'.$filename;
        if (in_array($file,$this->filenames))
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

    function deleteDir($dirname)
    {
        $path = $this->path.'/'.$dirname;
        if (in_array($path,$this->dirs))
        {
            unset($this->dirs[$path]);
            inotify_rm_watch($this->ifds[$path],$this->wds[$path]);
            unset($this->ifds[$path]);
            unset($this->wds[$path]);
        }
    }

    function watchFiles($path)
    {
        return $this->initFiles($path);
    }

    function Watch($date)
    {
//        if (in_array($this->path.'/'.$date,$this->dirs))
//        {
            $this->watchADir($this->path.'/'.$date);
            $this->watchFiles($this->path.'/'.$date);
//        }
//        else
//        {
//            exit($date.' dir is not exists');
//        }
    }

    /*
     * 监听顶级目录
     *
     */
    function initDirs()
    {
        //监听顶级目录
        $this->watchADir($this->path);
//        //列出所有二级目录
//        $dirs = scandir($this->path);
//        foreach ($dirs as $v)
//        {
//            if ($v == '.' || $v == '..')
//            {
//                continue;
//            }
//            $this->dirs[$this->path.'/'.$v] = $this->path.'/'.$v;
//        }
        $this->Watch($this->getDayName());
    }

    function start()
    {
        $this->initDirs();
        while(true)
        {
            $fds = $this->ifds;
            if (stream_select($fds, $w = null, $e = null, 1))
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
                                    if ($buf)
                                    {
                                        $this->handler->InModify($buf,basename($file));
                                        $this->pos[$file] = ftell($this->fds[$file]);
                                    }
                                }
                                break;
                            case (($event['mask'] & IN_CREATE) and ($event['mask'] & IN_ISDIR)):
                                $this->addDir($event['name']);
                                $this->log("add dir event ".$event['name']);
                                break;
                            case ($event['mask'] & IN_CREATE):
                                $this->addFile($event['name']);
                                $this->log("add file event ".$event['name']);
                                break;
                            case (($event['mask'] & IN_DELETE) and ($event['mask'] & IN_ISDIR)):
                                $this->deleteDir($event['name']);
                                break;
                            case ($event['mask'] & IN_DELETE):
                                $this->deleteFile($event['name']);
                                break;

                        }
                    }
                }
            }
            if (date("His") == "000100") //到达第二天零点1分 停止监控前一天目下文件
            {
                $yesterday = date($this->format,time()-24*3600);
                $this->log(date("Y-m-d H:i:s")."stop watch $yesterday");
                $this->stopWatchDir($yesterday);
            }
        }
    }
}