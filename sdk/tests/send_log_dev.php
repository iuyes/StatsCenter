<?php
require __DIR__.'/../api/php/StatsCenter.php';
StatsCenter::setServerIp('127.0.0.1');
//$module = array(1000238,1000239,1000240,1000241,1000242);
//$interface = array(5000369,5000371,5000372,5000373,5000374,5000375,5000376);
$module = array(1000238,1000239);
$interface = array(5000371,5000372);
$w = 0;
foreach ($module as $m)
{
    foreach ($interface as $in)
    {
        $k = $m."--".$in;
        $number[$k] = 0;
    }
}
foreach ($module as $m)
{
    foreach ($interface as $in)
    {
        for ($i = 0; $i < 10; $i++) {
            //static function log($loginfo, $level = 1, $userid = 0, $module = 0, $interfaceid = 0, $special_id = 0)
            $sp_id = $m+$in;
            $now = time();
            $k = $m."--".$in;
            $number["$k"]++;
            StatsCenter::log("$number[$k] 时间- $now -;我来自 $m 模块, $in 接口--ErrorLog Test. Line=". __LINE__ ." File=" . __FILE__ . '|num=' . $i,  $i+1, 350749960,$m,$in,$w);
            usleep(1000);
            $w++;
        }
    }
}
echo $w;


//for ($i = 0; $i < 5; $i++) {
//    //static function log($loginfo, $level = 1, $userid = 0, $module = 0, $interfaceid = 0, $special_id = 0)
//
//    StatsCenter::log("ErrorLog Test. Line=". __LINE__ ." File=" . __FILE__ . '|num=' . $i,  $i+1, 350749960,1000238,5000369,$i);
//    usleep(1000);
//}
//sleep(1);
