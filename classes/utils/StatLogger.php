<?php

namespace utils;

class StatLogger extends Logger
{
	public static function log($type, $text, $date = '')
	{
		$ts = time();
		$dir = LOG_PATH . '/stat/' . date("Ym") . '/';
		if (!is_dir($dir)) {
			umask(0);
			mkdir($dir, 0777, true);
		}
		$fileName = $type . '-' . date("Ymd") . ".log";
		$fullFilePath = $dir . $fileName;

		$timeStr = date("Y-m-d H:i:s");
		$text = implode("\t", [ $ts, $timeStr, IP, COUNTRY_CODE, STAT_APPID, $text ]) . "\n";
		error_log($text, 3, $fullFilePath);
	}

	public static function logHour($type, $text, $date = '')
	{
		$ts = time();
		$dir = LOG_PATH . '/stat/' . date("Ym") . '/';
		if (!is_dir($dir)) {
			umask(0);
			mkdir($dir, 0777, true);
		}
		$fileName = $type . '-' . date("Ymd") . '-' . date("H") . ".log";
		$fullFilePath = $dir . $fileName;

		$text = $ts . "\t" . $text . "\n";
		error_log($text, 3, $fullFilePath);
	}

	public static function rawLog($type, $text, $date = '')
	{
		if (empty($date)) {
			$ts = time();
		}
		else {
			$ts = strtotime($date);
		}
		$dir = LOG_PATH . '/stat/' . date("Ym", $ts) . '/';
		if (!is_dir($dir)) {
			umask(0);
			mkdir($dir, 0777, true);
		}
		$fileName = $type . '-' . date("Ymd", $ts) . ".log";
		$fullFilePath = $dir . $fileName;

		file_put_contents($fullFilePath, $text);
	}

	public static function getLogFile($type, $date)
	{
		$yearmonth = date("Ym", strtotime($date));
		$dir = LOG_PATH . '/stat/' . $yearmonth . '/';
		$fileName = $type . '-' . $date . ".log";

		$fullPath = $dir . $fileName;

		return $fullPath;
	}

}
