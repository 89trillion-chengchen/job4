<?php

namespace framework\data\beanstalk;

/**
 * Beanstalk配置信息
 */
class BeanstalkConfiguration
{
	public $host;
	public $port;

	/**
	 * 构造函数
	 */
	public function __construct($config = null)
	{
		if (!empty($config) && is_array($config)) {
			if (isset($config['host'])) $this->host = $config['host'];
			if (isset($config['port'])) $this->port = $config['port'];
		}
	}
}
