<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-2-25 下午4:08:20
 * @description:
 * $Id: index.php 1909 2014-05-05 07:57:51Z pengcheng2 $
 */

use entity\user;
use framework\mvc\dispatcher\HTTPRequestDispatcher;
use framework\core\Context;
use framework\data\redis;
use framework\data\pdo;
use framework\util\Singleton;
use service\SampleService;
use utils\Functions;
use framework\mvc\view\smarty;

include_once("../lib/raftphp/setup.php");

Context::setRootPath(realpath('..'));
$infPath = Context::getRootPath() . DIRECTORY_SEPARATOR . 'inf' . DIRECTORY_SEPARATOR;
if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST']) && is_dir($infPath . $_SERVER['HTTP_HOST'])) {
	$infPath .= $_SERVER['HTTP_HOST'];
}
else if (isset($_SERVER['HTTP_X_FORWARDED_HOST']) && !empty($_SERVER['HTTP_X_FORWARDED_HOST']) && is_dir($infPath . $_SERVER['HTTP_X_FORWARDED_HOST'])) {
	$infPath .= $_SERVER['HTTP_X_FORWARDED_HOST'];
}
else if (isset($_SERVER['SERVER_NAME']) && !empty($_SERVER['SERVER_NAME']) && is_dir($infPath . $_SERVER['SERVER_NAME'])) {
	$infPath .= $_SERVER['SERVER_NAME'];
}
else {
	$infPath .= 'test-89tr.chengchen.com';
}
Context::setInfoPath($infPath);
Context::setExceptionHandler('exception\CommonException::exceptionHandler');
Context::initialize();

smarty\SmartyView::setSmartyConfiguration(new smarty\SmartyConfiguration(SmartyConfigs::$default));

if (DbConfigs::$configs && is_array(DbConfigs::$configs)) {
	foreach (DbConfigs::$configs as $configName => $configValue) {
		pdo\PDOManager::addConfigration($configName, new pdo\PDOConfiguration($configValue));
	}
}
if (CacheConfigs::$configs && is_array(CacheConfigs::$configs)) {
	foreach (CacheConfigs::$configs as $configName => $configValue) {
		redis\RedisManager::addConfigration($configName, new redis\RedisConfiguration($configValue));
	}
}

class MyHTTPRequestDispatcher extends HTTPRequestDispatcher
{
	public function getCtrlClassName()
	{
		return parent::getCtrlClassName() . "Ctrl";
	}
}

$dispatcher = new \MyHTTPRequestDispatcher();
$dispatcher->dispatch();

/** @var SampleService $sampleService */
$sampleService = Singleton::get(SampleService::class);

