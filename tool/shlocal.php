<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-3-5 上午11:47:15
 * @description:
 * $Id: shlocal.php 944 2014-03-14 13:18:50Z pengcheng2 $
 */
set_time_limit(0);

use framework\data\pdo;
use framework\data\redis;
use framework\core\Context;
use framework\mvc\dispatcher\ShellRequestDispatcher;
use utils\Functions;

define('SHELL_MODE', true);
define('ROOT_DIR', realpath(dirname(__FILE__) . '/../'));

include_once(ROOT_DIR . "/lib/raftphp/setup.php");

Context::setRootPath(ROOT_DIR);
$infPath = Context::getRootPath() . DIRECTORY_SEPARATOR . 'inf' . DIRECTORY_SEPARATOR . "local-{DOMAIN}";
Context::setInfoPath($infPath);
Context::initialize();  //加载inf相关目录下所有文件

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

$dispatcher = new ShellRequestDispatcher();
$dispatcher->dispatch();
