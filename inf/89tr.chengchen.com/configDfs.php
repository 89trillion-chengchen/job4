<?php
/**
 */

class DFSConfigs
{
	/* tracker 服务器配置 */
	public static $tracker = [
		'default' => [
			'ip_addr' => '127.0.0.1',
			'port'    => '22122',
		],
	];

	/* storage 服务器配置  */
	public static $storage = [
		'defalut' => [
			'group1' => [
				'ip_addr' => '127.0.0.1',
				'port'    => '80',
			],
		],
	];
}
