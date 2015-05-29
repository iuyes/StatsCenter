<?php
namespace Sdk\Watcher;

class File
{
    public $path;
    public $filename;
    public $handler;

    private $ifd;
    private $wd;
    private $fd;

    const MASK = IN_MODIFY;

    public function __construct($inotify)
    {
        $this->path = $inotify->path;
        $this->handler = $inotify->handler;
        $this->filename = basename($this->path);
    }

    public function start()
    {
        $this->init();
        $this->fd = fopen($this->path,'r');
        stream_set_blocking($this->fd, 0);
        $pos = filesize($this->path);
        while (true)
        {
            $events = inotify_read($this->ifd);
            foreach ($events as $event)
            {
                switch (true)
                {
                    case ($event['mask'] & self::MASK):
                        fseek($this->fd,$pos);
                        $buf = '';
                        while (!feof($this->fd)) {
                            $buf .= fread($this->fd,8192);
                        }
                        $this->handler->InModify($buf,$this->filename);
                        $pos = ftell($this->fd);
                        break;
                }
            }
        }
    }

    public function init()
    {
        $this->ifd = inotify_init();
        $this->wd = inotify_add_watch($this->ifd, $this->path, self::MASK);
    }
}