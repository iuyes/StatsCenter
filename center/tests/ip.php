<?php
$ip = ip2long('127.0.0.1');

echo $ip.PHP_EOL;

$pkg = unpack('N', pack('N', $ip));

var_dump($pkg);
echo long2ip($pkg[1]);