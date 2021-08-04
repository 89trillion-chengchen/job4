<?php
/**
 * 产品特别定制配置文件
 */

/* ===================== 框架初始化配置 ===================== */

\error_reporting(E_ALL ^ E_NOTICE);
\ini_set('display_errors', 0);
date_default_timezone_set('Asia/Shanghai');

/* 调试模式 */
define("DEBUG_MODE", false);

/* 当前环境 */
define("ENV", 'online');

/* 站点目录 */
define('ROOT_PATH', realpath('..'));

/* ===================== 业务逻辑相关配置 ===================== */

/* 通用统计应用ID */
define('STAT_APPID', '{APPID}');

/*
 * 数组类型的配置
 */
global $G_CONFIGS;
$G_CONFIGS = [
	// 'key' => array('item1','item2');
];

