<?php
/**
 * 跨应用通用的配置文件
 */

/* ===================== 框架初始化配置 ===================== */

/* 日志目录 */
define('LOG_PATH', ROOT_PATH . '/logs');

/* 资源文件目录 */
define('RES_PATH', ROOT_PATH . '/resource');

/* 系统时间戳 */
define('NOW_TIME', time());

/* 系统时间 */
define('NOW_DATETIME_SYS', date("Y-m-d H:i:s", NOW_TIME));

/* 系统日期 */
define('NOW_DATE_SYS', date("Ymd", NOW_TIME));

$sysTimeZone = date_default_timezone_get();
/* 切换到北京时间，取到时间和日期，再重新原系统时间 */
date_default_timezone_set("PRC");
define('NOW_DATETIME', date("Y-m-d H:i:s", NOW_TIME));
define('NOW_DATE', date("Ymd", NOW_TIME));
date_default_timezone_set($sysTimeZone);

/* 键名分隔符 */
define('KEY_SEPARATOR', '_');

/* 获得访问者外网IP */
$wanIPFunc = function () {

	/* 命令行模式：没有IP */
	if (PHP_SAPI === 'cli') {
		return '';
	}

	/* 外部IP直接访问：使用外部IP */
	if (filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
		return $_SERVER['REMOTE_ADDR'];
	}

	/* 局域网访问，且没有X-Forwarded-For信息：使用局域网IP */
	if (!isset($_SERVER['HTTP_X_FORWARDED_FOR']) || empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		return $_SERVER['REMOTE_ADDR'];
	}

	/* 通过反向代理 or ELB外部间接访问：从X-Forwarded-For中获取非局域网IP */
	$ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	$ips = array_reverse($ips);
	foreach($ips as $nowIP) {
		$nowIP = trim($nowIP);
		// 返回倒序后首个非局域网IP（即原始顺序最后一个）
		if (filter_var($nowIP, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
			return $nowIP;
		}
	}

	/* 没有获取到外网IP：默认值 */
	return $_SERVER['REMOTE_ADDR'];
};

/* 根据IP获取ISO国家二字码 */
$countryCodeFunc = function ($ip) {
	if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
		// IP非法或者是局域网
		return 'LO';
	}
	else if (!function_exists('geoip_country_code_by_name')) {
		// GeoIP扩展不存在
		return 'XX';
	}
	else {
		return strtoupper(geoip_country_code_by_name(IP));
	}
};

/* 当前访问者IP */
if (!defined('IP')) {
	define('IP', $wanIPFunc());
}
/* 当前访问者IP所属区域 */
if (!defined('COUNTRY_CODE')) {
	define('COUNTRY_CODE', $countryCodeFunc(IP));
}
/* 当前访问者安全IP，避免伪造，根据IP判断权限时必须使用此常量 */
if (!defined('SAFE_IP')) {
	define('SAFE_IP', IP); // 新IP逻辑上线后IP/SAFE_IP相同，代码兼容
}
/* 当前访问者SAFE_IP所属区域 */
if (!defined('SAFE_COUNTRY_CODE')) {
	define('SAFE_COUNTRY_CODE', $countryCodeFunc(SAFE_IP));
}


if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') {
	define('HTTP_PROTOCOL', 'https:');
}
else {
	define('HTTP_PROTOCOL', 'http:');
}

/* 默认错误key值 */
define('DEFAULT_ERROR_KEY', 'unknown_error');

/* 接口加密移位数值 */
define('API_ENCRYPT_INDEX', 15);

/* API接口允许的IP段 */
define('API_ALLOW_IPS', '127.0.0.1,10.0.0.0/8,172.31.0.0/16,114.248.76.2,13.228.59.198,13.251.11.70,52.74.26.246');

/* ================== 业务逻辑相关配置 =====================*/

/* 通用计数器配置 */
define('COUNTER_TYPE_TEST', 1);

/* 通用计数器 */
define('GENERAL_COUNTER_CACHE_TPL', STAT_APPID . ':counter:{type}');

/*
 * 响应码的定义
 * 0: ok
 * 1xx: 缺少参数
 * 2xx: 参数值错误
 * 3xx: 状态/逻辑错误
 * 403: 权限限制
 * 404: 不存在
 * xxx: 自定义
 * 9xx: 内部错误
 */
//@format:off
$G_CONFIGS['response'] = [
	'ok' => [ 0, 'success' ],

	'lack_of_params' => [ 101, 'lack_of_params' ],

	'internal_error' => [ 998, 'internal_error' ],
	'unknown_error'  => [ 999, 'unknown_error' ],
];
//@format:on

/* 计数器初始化配置 */
$G_CONFIGS['counter_confs'] = [
	COUNTER_TYPE_TEST => [ 'key' => 'xx:counter:test', 'range' => '0_0' ],
];

