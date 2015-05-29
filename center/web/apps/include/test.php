<?php
require __DIR__.'/mail.php';

$m = new \Apps\Mail();
$content = "<div><span style='color:red'>asdasdasd</span></div>";
$res = $m->mail('shiguangqi@yy.com','test',$content);
var_dump($res);