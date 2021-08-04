<?php
/**
 * 接口通用响应方法类
 * 将各个接口中数据转换为指定格式并响应
 *
 * @file    AnswerService.php
 * @date    2014-02-26
 * @author  MiaoYushun <miaoyushun@gmail.com>
 */

namespace service;

use utils\Functions;
use utils\HttpUtil;
use utils\StatLogger;

class AnswerService
{
	/**
	 * 纯文本原始格式响应
	 */
	public static function plainResponse($data)
	{
		die($data);
	}

	/**
	 * JSON格式数据响应
	 */
	public static function jsonResponse($data)
	{
		$json = json_encode($data);
		die($json);
	}

	/**
	 * XML格式数据响应
	 */
	public static function xmlResponse($data)
	{
		$xml = self::arrayToXml($data);
		die($xml);
	}

	/**
	 * 响应结构构造方法，默认版：强制转换Data字段为数组
	 *
	 * @param string $errKey
	 * @param array $data
	 * @param bool $noDataKey
	 *
	 * @return array
	 */
	public static function makeResponseArray($errKey, $data = [], $noDataKey = false)
	{
		!is_array($data) ? $data = [] : 1;
		list($code, $msg) = self::getResponseCodeAndMsg($errKey);

		// 记录统一的接口返回异常日志，监控使用
		if (0 !== $code) {
			$header = Functions::getHeaders();
			list($reqURI) = explode('?', $_SERVER['REQUEST_URI']);
			$logArr = [
				$header['gaid'], $header['cvc'], $reqURI,
				$code, $errKey, $_SERVER['QUERY_STRING'],
			];
			$text = implode("\t", $logArr);
			StatLogger::log('response_error', $text);
		}

		$response = [
			'code' => $code,
			'msg'  => $msg,
		];
		if ($noDataKey) {
			$response += $data;
		}
		else {
			$response += [ 'data' => $data ];
		}

		return $response;
	}

	/**
	 * 响应结构构造方法，优化版：不强制转换Data字段为数组
	 *
	 * @param string $errKey
	 * @param mixed $data
	 * @param bool $noDataKey
	 *
	 * @return array
	 */
	public static function makeResponseData($errKey, $data = [], $noDataKey = false)
	{
		list($code, $msg) = self::getResponseCodeAndMsg($errKey);
		$response = [
			'code' => $code,
			'msg'  => $msg,
		];
		if ($noDataKey) {
			$response += $data;
		}
		else {
			$response += [ 'data' => $data ];
		}

		return $response;
	}

	/**
	 * 获取错误KEY对应的错误码和错误描述
	 */
	public static function getResponseCodeAndMsg($errKey)
	{

		$responseConfig = Functions::getGlobalVars("response");
		$params = HttpUtil::getQueryData();
		if (isset($responseConfig[$errKey])) {
			return $responseConfig[$errKey];
		}

		return $responseConfig[DEFAULT_ERROR_KEY];

	}

	/**
	 * 重定向响应
	 */
	public static function redirectResponse($url)
	{
		header("Location: $url");
		exit;
	}

	/**
	 * The main function for converting to an XML document.
	 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
	 *
	 * @param array $data
	 * @param string $rootNodeName - what you want the root node to be - defaultsto data.
	 * @param SimpleXMLElement $xml - should only be used recursively
	 *
	 * @return string XML
	 */
	private function arrayToXml($data, $rootNodeName = 'data', $xml = null)
	{
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if (ini_get('zend.ze1_compatibility_mode') == 1) {
			ini_set('zend.ze1_compatibility_mode', 0);
		}

		if ($xml == null) {
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
		}

		// loop through the data passed in.
		foreach ($data as $key => $value) {
			// no numeric keys in our xml please!
			if (is_numeric($key)) {
				// make string key...
				$key = "item";//. (string) $key;
			}

			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z_0-9]/i', '', $key);

			// if there is another array found recrusively call this function
			if (is_array($value)) {
				$node = $xml->addChild($key);
				// recrusive call.
				self::arrayToXml($value, $rootNodeName, $node);
			}
			else {
				// add single node.
				$value = $value;
				$xml->addChild($key, $value);
			}

		}
		// pass back as string. or simple xml object if you want!
		//return $xml->asXML();

		$dom = dom_import_simplexml($xml)->ownerDocument;
		$dom->formatOutput = true;

		return $dom->saveXML();
	}
}
