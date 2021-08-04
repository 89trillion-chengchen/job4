<?php

namespace utils;

class Logger
{
	public static function writeLog($message, $type, $fileName = null)
	{
		if (strpos($type, '.') !== false) {
			$type = array_filter(explode('.', $type));
			$type = implode(DS, $type);
		}
		$logPath = LOG_PATH . DS . $type;
		if (!is_dir($logPath)) {
			if (!mkdir($logPath, 0700, true)) return false;
		}
		$logPath = realpath($logPath);
		if (!$fileName) {
			$fileName = Functions::getDateString(TIME_ZONE, 'now', 'Ymd') . '.log';
		}
		$logFile = $logPath . DS . $fileName;
		error_log($message . "\n", 3, $logFile);
	}

	public static function writeToFile($message, $logFile)
	{
		$logPath = dirname($logFile);
		if (!is_dir($logPath)) {
			if (!mkdir($logPath, 0700, true)) return false;
		}
		$logPath = realpath($logPath);
		error_log($message . "\n", 3, $logFile);
	}
}