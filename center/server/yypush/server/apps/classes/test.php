<?php
namespace imweb\php;
//error_reporting(0);

//ip白名单
$white_ip_array = array(
	'127.0.0.1',//本地测试
	'172.16.43.113',//本地测试
	'172.19.31.70',
	'119.97.153.227',
	'183.60.177.224/27',//公司内网
	'210.21.125.40/29',//公司内网
	'113.108.232.33',//公司内网
	'113.108.232.34',//公司内网
	
	'183.61.143.34',//发布器	
);

require_once __DIR__.'/Thrift/ClassLoader/ThriftClassLoader.php';
use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = realpath(dirname(__FILE__)).'/gen-php/';

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__ . '/');
$loader->registerDefinition('mobilemsg', $GEN_DIR);
$loader->register();

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\TSocketPool;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TFramedTransport;
use Thrift\Exception\TException;


function check_ip($ip_array = array(), $remote_ip = ''){
	$remote_ip = empty($remote_ip) ? $_SERVER['REMOTE_ADDR'] : $remote_ip;
	//判断ip是否在白名单
	foreach($ip_array as $ip){
		$ip_info = explode('/', $ip);
		$mask = isset($ip_info[1]) ? $ip_info[1] : 32;
		if(substr(sprintf("%032b", ip2long($ip_info[0])), 0, $mask) === substr(sprintf("%032b", ip2long($remote_ip)), 0, $mask)){
			return true;
		}
	}
	return false;
}

$result = array('code'=>0, 'data'=>array());
/*
//ip白名单判断
if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
if( !check_ip($white_ip_array, $_SERVER['REMOTE_ADDR']) ){
	$result['code'] = -1; //ip不再白名单内
	echo json_encode($result);
	exit;
}
*/

try {

	$socket = new TSocketPool(array('115.238.170.51','121.9.221.159','113.106.100.79'), 30303);
	$transport = new TFramedTransport($socket, 1024, 1024);
	$protocol = new TBinaryProtocol($transport);
	$transport->open();
	
	//业务代码begin
	//注册验证码接口
	define('APPID', 77);
	define('APPKEY', 'stats20151007');
	
	//邀请短信接口
	//define('APPID', 44);
	//define('APPKEY', 'secretInvite');
	
	//define('APPID', 2);
	//define('APPKEY', 'test');	
	$client = new \mobilemsg\service\MsgOper_ServiceClient($protocol);
	$identity = new \mobilemsg\service\Identity(
												array(
												'version'=>$GLOBALS['mobilemsg_CONSTANTS']['version'], 
												'appId'=>APPID,
												'appKey'=>APPKEY)
											);
										
	$sent_control = new \mobilemsg\service\SentControl();
	$type = 0;
	$downMsg = new \mobilemsg\service\DownMsg();
	$downMsg->mobiles = array('18588746832');
	$downMsg->smsContent = '验证码546688';
	$downMsg->muid = '18588746832_'.time();

	$msg = array(0=>$downMsg);
	$ret = $client->send($identity, $type, $msg, $sent_control);
	var_dump($ret);
	//业务代码end
	
	$transport->close();
} catch (TException $tx) {
  $result['code'] = -100; //thrift异常
  print_r($tx);
}
echo json_encode($result);
