<?php
namespace utils;

class HttpUtil
{
	private static function filterVal($val)
	{
		if (!is_array($val)) {
			return (!get_magic_quotes_gpc() ? addslashes(trim($val)) : trim($val));
		} else {
			$data = array();
			foreach ($val as $k => $v) {
				$data[$k] = self::filterVal($v);
			}
			return $data;
		}
	}
	
	public static function getQueryData($key = null)
	{
		if (!is_null($key) && !is_array($key)) {
			if (!isset($_GET[$key])) {
				return '';
			}
			return $_GET[$key];
			//return self::filterVal($_GET[$key]);
		}
		if (is_array($key)) {
			$keys = $key;
		} else {
			$keys = array_keys($_GET);
		}
		$data = array();
		foreach ($keys as $k) {
			$data[$k] = self::getQueryData($k);
		}
		return $data;
	}
	
	public static function getPostData($key = null)
	{

		if(isset($_SERVER['HTTP_CONTENT_TYPE']) && 
			in_array(strtolower($_SERVER['HTTP_CONTENT_TYPE']),array('text/json','application/json'))
		){
			$postRaw = file_get_contents("php://input");
			$_POST = json_decode($postRaw,true);
		}

		if (!is_null($key) && !is_array($key)) {
			if (!isset($_POST[$key])) {
				return '';
			}
			return $_POST[$key];
		}

		$keys = [];
		if (is_array($key)) {
			$keys = $key;
		}
		elseif(is_array($_POST)) {
			$keys = array_keys($_POST);
		}

		$data = array();
		foreach ($keys as $k) {
			$data[$k] = self::getPostData($k);
		}
		return $data;
	}

	public static function getRequestData()
	{
		$getData = self::getQueryData();
		$postData = self::getPostData();
		!is_array($postData) ? $postData = array() : 1;
		$requestData = array_merge($getData,$postData);
		return $requestData;
		
	}
	
	public static function getCurrentUrl($filter = array())
	{
		$port = '';
		if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443 ) {
			$port = $_SERVER['SERVER_PORT'];
		}
		$url = HTTP_PROTOCOL . '//' . $_SERVER['HTTP_HOST'] . $port . $_SERVER['REQUEST_URI'];
		if (empty($filter)) {
			return $url;
		}
		if (!is_array($filter)) {
			$filter = array($filter);
		}
		$queryData = self::getQueryData();
		foreach ($queryData as $k => $v) {
			if (in_array($k, $filter)) {
				unset($queryData[$k]);
			}
		}
		return str_ireplace($_SERVER['QUERY_STRING'], http_build_query($queryData, null, '&'), $url);
	}
	
	public static function makeRequest($url, $params = array(), $method = 'GET')
	{
		$ch = curl_init();
		$opts = array(
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 60
		);
		if (strtoupper($method) == 'GET' && count($params) > 0) {
			$url .= (strpos($url, '?') === false) ? '?' : '&';
			$url .= http_build_query($params, null, '&');
		} elseif (strtoupper($method) == 'POST') {
			$opts[CURLOPT_POST] = 1;
			$opts[CURLOPT_POSTFIELDS] = $params;
		}
		$opts[CURLOPT_URL] = $url;
		curl_setopt_array($ch, $opts);
		
		$result = curl_exec($ch);
		curl_close($ch);
	
		return $result;
	}

	public static function getEncryptPostData($format = '')
	{
		$postRaw = file_get_contents("php://input");
		$rawData = Functions::decryptByKaiser($postRaw, API_ENCRYPT_INDEX);
		$postData = [];

		if (isset($_SERVER['HTTP_CONTENT_TYPE']) &&
			in_array(strtolower($_SERVER['HTTP_CONTENT_TYPE']), [ 'text/json', 'application/json' ])
		) {
			$postData = json_decode($rawData, true);
		}
		else if ('json' == $format) {
			$postData = json_decode($rawData, true);
		}
		else {
			parse_str($rawData, $postData);
		}

		return $postData;
	}

	public static function getEncryptPostRaw()
	{
		$postEnc = file_get_contents("php://input");
		$postRaw = Functions::decryptByKaiser($postEnc, API_ENCRYPT_INDEX);

		return $postRaw;
	}

	public static function getEncryptGetData()
	{
		$getData = self::getQueryData();
		$queryData = array();
		if(isset($getData['q'])){
			$encryStr = base64_decode($getData['q']);
			$queryStr = Functions::decryptByKaiser($encryStr, API_ENCRYPT_INDEX);
			parse_str($queryStr,$queryData);
		}

		return $queryData;
	}
	public static function redirect($url)
	{
		header('location:'.$url);
		exit();
	}
	
	public static function topLocation($url)
	{
		echo '<script type="text/javascript">top.location.href = "'.$url.'";</script>';
		exit();
	}
	
}

