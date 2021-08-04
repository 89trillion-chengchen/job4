<?php
/**
 */

class DFSConfigs
{
	/* tracker 服务器配置 */
	public static $tracker = [
		'default' => [
			'ip_addr' => '10.0.1.6',
			'port'    => '22122',
		],
	];

	/* storage 服务器配置  */
	public static $storage = [
		'defalut' => [
			'group1' => [
				'ip_addr' => '10.0.1.6',
				'port'    => '80',
			],
		],
	];
}
