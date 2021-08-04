<?php

namespace utils;

class MyLogger extends Logger
{
	public static function log($response, $successFlag = false, $filePrefix = '')
	{
		$dateTime = Functions::getDateString();
		$userIP = Functions::getClientIP();
		$requestParams = HttpUtil::getRequestData();
		$requestUri = $requestParams['act'];
		$logText = "$dateTime $userIP $requestUri ";
		$logText .= "\nrequest: \n" . var_export($requestParams, true) . "\n";

		if (is_array($response) || is_object($response)) {
			$logText = rtrim($logText) . "\nresponse:\n" . var_export($response, 1);
		}
		else {
			$logText .= "response: $response";
		}
		$logText .= "\n\n";

		if (empty($filePrefix)) {
			$filePrefix = str_replace('.', '_', $requestUri);
		}
		$dateString = date("Ymd");
		$fileSuffix = ".log";
		if (true == $successFlag) {
			$fileName = $filePrefix . '_ok_' . $dateString . $fileSuffix;
		}
		else {
			$fileName = $filePrefix . '_failed_' . $dateString . $fileSuffix;
		}
		$dir = LOG_PATH . '/raw/' . date("Ym") . '/';
		if (!is_dir($dir)) {
			umask(0);
			mkdir($dir, 0777, true);
		}
		$fullFilePath = $dir . $fileName;
		error_log($logText, 3, $fullFilePath);
	}
}
