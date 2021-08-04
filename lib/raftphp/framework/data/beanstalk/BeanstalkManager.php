<?php

namespace framework\data\beanstalk;

use Pheanstalk\Pheanstalk;

/**
 * Beanstalk管理工具
 */
class BeanstalkManager
{
	/**
	 * 配置
	 */
	public static $configs;

	/**
	 * 实例
	 */
	private static $instances;

	/**
	 * 添加配置
	 */
	public static function addConfigration($name, BeanstalkConfiguration $config)
	{
		self::$configs[$name] = $config;
	}

	/**
	 * 获取实例
	 */
	public static function getInstance($name)
	{
		if (empty(self::$instances[$name])) {
			$serverIP = self::$configs[$name]->host;
			$serverPort = self::$configs[$name]->port;
			$pstalk = new Pheanstalk($serverIP, $serverPort);
			if (is_object($pstalk)) {
				self::$instances[$name] = $pstalk;
			}
			else {
				self::$instances[$name] = false;
			}
		}

		return self::$instances[$name];
	}
}
