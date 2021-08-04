<?php
/**
 *
 * @file Functions.php
 * @author ChengRennt <ChengRennt@gmail.com>
 * @date 2013-3-25
 * @description
 */

namespace utils;

class Functions
{

	public static function logging($type, $dirname, $msg)
	{
		$dir = LOG_PATH . '/' . $type . '/' . $dirname;
		$file = $dir . '/' . NOW_DATE . '.log';
		if (!is_writable($file)) {
			if (!is_dir($dir)) {
				umask(0);
				mkdir($dir, 0777, true);
			}
			touch($file);
			chmod($file, 0777);
		}
		error_log(NOW_DATETIME . "\t" . IP . "\t" . $msg . "\n", 3, $file);
	}

	public static function log($dirname, $msg)
	{
		if (defined("DEBUG_MODE") && DEBUG_MODE && $dirname == 'debug') {
			echo $msg;
		}
		self::logging('running', $dirname, $msg);
	}

	public function get_dclog_fname($type)
	{
		$rq = @date('Ymd');
		$ym = substr($rq, 0, 6);
		$d = '/data/syslog/1mobilelog/' . $ym;
		if (!file_exists($d)) {
			mkdir($d, 0777, true);
		}

		return $d . '/' . $type . '_' . $rq . '.log';
	}

	public static function errlog($dirname, $msg)
	{
		self::logging('error', $dirname, $msg);
	}

	public static function phperr($priority, \Exception $e)
	{
		switch ($priority) {
			case LOG_INFO: 
				$tag = 'LOG_INFO';
				break;
			case LOG_NOTICE:
				$tag = 'LOG_NOTICE';
				break;
			case LOG_WARNING:
				$tag = 'LOG_WARNING';
				break;
			case LOG_ERR:
				$tag = 'LOG_ERR';
				break;
		}

		$body = sprintf("[%s] %s in %s on line %d", $tag, $e->getMessage(), $e->getFile(), $e->getLine());
		error_log($body);
	}

	public static function setObjAttrs($obj, $keys, $args)
	{
		if (is_array($keys) && !empty($keys)) {
			foreach ($keys as $key) {
				if (isset($args[$key])) {
					$obj->$key = $args[$key];
				}
				else {
					$obj->$key = null;
				}
			}
		}

		return $obj;
	}

	public static function getTimestamp($datetime)
	{
		return strtotime($datetime);
	}

	public static function getDatetime($timestamp)
	{
		return date("Y-m-d H:i:s", $timestamp);
	}

	public static function urlParamsFilter($url, $filters)
	{
		$urls = parse_url($url);
		if (!isset($urls['query'])) return $url . '?';

		parse_str($urls['query'], $args);
		foreach ($args as $key => $arg) {
			if (in_array($key, $filters)) {
				unset($args[$key]);
			}
		}
		$query = http_build_query($args);
		$data = $urls['scheme'] . '://' . $urls['host'] . $urls['path'] . '?' . $query;

		return $data;
	}

	public static function curl($url, $type = 'GET', $data = [], $header = [], $configs = [], $stream = false)
	{
		$connTimeout = isset($configs['conntimeout']) ? intval($configs['conntimeout']) : 10;
		$timeout = isset($configs['timeout']) ? intval($configs['timeout']) : 30;
		$redirect = isset($configs['redirect']) ? (bool)$configs['redirect'] : false;
		$proxy = isset($configs['proxy']) ? strval($configs['proxy']) : '';

		$opts = [
			CURLOPT_CONNECTTIMEOUT => $connTimeout,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_TIMEOUT        => $timeout,
			CURLOPT_USERAGENT      => 'Mozilla/5.0 Gecko/20100101 Firefox/19.0',
			CURLOPT_FOLLOWLOCATION => $redirect,
		];

		if (!empty($proxy)) {
			$opts[CURLOPT_PROXY] = $proxy;
		}

		$opts[CURLOPT_URL] = $url;
		$opts[CURLOPT_CUSTOMREQUEST] = $type;
		$opts[CURLOPT_HTTPHEADER] = $header;
		$ch = curl_init();
		if ($type == 'POST' && is_string($data)) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			if ($stream) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
						'Content-Type: application/octet-stream',
						'Content-Length: ' . strlen($data) ]
				);
			}
			else {
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
						'Content-Type: application/json; charset=utf-8',
						'Content-Length: ' . strlen($data) ]
				);
			}
		}
		else if ($type == 'POST' || $type == 'PUT') {
			$opts[CURLOPT_POSTFIELDS] = $data;
		}

		curl_setopt_array($ch, $opts);
		$result = curl_exec($ch);
		if (curl_errno($ch)) {
			if (defined("DEBUG_MODE") && DEBUG_MODE) {
				echo curl_error($ch);
			}

			return false;
		}
		curl_close($ch);

		return $result;
	}

	/**
	 * 获取客户端信息
	 */
	public static function getHeaders($token = null)
	{
		if (empty($token)) {
			$token = isset($_SERVER['HTTP_BASIC_INFO']) ? $_SERVER['HTTP_BASIC_INFO'] : '';
		}
		$tokenJson = base64_decode($token);
		$data = json_decode($tokenJson, true);
		$headers = [
			'gaid'    => isset($data['gaid']) ? $data['gaid'] : 0,
			'uid'     => isset($data['uid']) ? $data['uid'] : 0,
			'adid'    => isset($data['adid']) ? $data['adid'] : 0,
			'svc'     => isset($data['svc']) ? $data['svc'] : 0,
			'cvc'     => isset($data['cvc']) ? $data['cvc'] : 0,
			'vc'      => isset($data['vc']) ? $data['vc'] : 0,
			'device'  => isset($data['device']) ? $data['device'] : 0,
			'chan'    => isset($data['chan']) ? $data['chan'] : 0,
			'network' => isset($data['network']) ? $data['network'] : 0,
			'simcode' => isset($data['simcode']) ? $data['simcode'] : 0,
			'lang'    => isset($data['lang']) ? $data['lang'] : 0,
		];

		return $headers;
	}

	/**
	 * 判断IP所属国家代码
	 */
	public static function getCountryCode($ip = null)
	{
		if (empty($ip)) {
			$ip = IP;
			if (empty($ip)) {
				return 'aa';
			}
		}
		if (!function_exists('geoip_country_code_by_name')) {
			include(RES_PATH . '/geoip/geoip.inc.php');
			$gi = geoip_open("/usr/share/GeoIP/GeoIP.dat", GEOIP_STANDARD);
			$country = geoip_country_code_by_addr($gi, $ip);
		}
		else {
			$country = geoip_country_code_by_name($ip);
		}

		return strtolower($country);
	}

	/**
	 * 格式化文件大小
	 */
	public static function formatBytes($size)
	{
		$units = [ 'B', 'KB', 'MB', 'GB', 'TB' ];
		for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;

		return round($size, 1) . $units[$i];
	}

	/**
	 * 获取配置文件中定义的全局变量
	 */
	public static function getGlobalVars($name)
	{
		if (empty($name)) {
			return $GLOBALS['G_CONFIGS'];
		}
		if (!isset($GLOBALS['G_CONFIGS'][$name])) {
			return false;
		}

		return $GLOBALS['G_CONFIGS'][$name];
	}

	/**
	 * 获取请求URI地址
	 */
	public static function getRequestUri()
	{
		$protocol = 'http://';
		$domainName = $_SERVER['HTTP_HOST'];
		parse_str($_SERVER['QUERY_STRING'], $queryArr);
		if (isset($queryArr['act'])) {
			$baseUri = '/' . str_replace('.', '/', $queryArr['act']);
		}
		else {
			$baseUri = $_SERVER['REQUEST_URI'];
		}
		$uri = $protocol . $domainName . $baseUri;

		return $uri;
	}

	/**
	 * 检查指定IP是否在给定的列表内
	 * 列表支持子网掩码的写法
	 *
	 * @param String $ip 要检查的IP
	 * @param Array $ipLists IP列表
	 *
	 * @return Boolean true:检查通过 false:检查未通过
	 */
	public static function checkIP($ip, $ipLists)
	{
		$clientip_is_allow = false;
		foreach ($ipLists as $v) {
			$auth_ip = explode("/", trim($v));
			if (sizeof($auth_ip) > 1) {
				$mark = (int)$auth_ip[1];
				$net_addr = ip2long($auth_ip[0]) >> (32 - $mark);
				$ip_addr = ip2long($ip) >> (32 - $mark);
				if ($ip_addr == $net_addr)
					$clientip_is_allow = true;

			}
			else if ($ip && $ip == $v) {
				$clientip_is_allow = true;
			}

			if ($clientip_is_allow == true)
				break;
		}

		return $clientip_is_allow;
	}

	/**
	 * 获取客户端ip
	 *
	 * @return String 用户IP
	 */
	public static function getClientIP()
	{
		if (isset($_SERVER)) {
			$realip = $_SERVER["REMOTE_ADDR"];
		}
		else {
			$realip = getenv("REMOTE_ADDR");
		}

		return addslashes($realip);
	}

	public static function getDateString($timezone = '', $timestamp = 'now', $format = "Y-m-d H:i:s")
	{
		if (empty($timezone)) {
			$timezone = @date_default_timezone_get();
		}
		if (preg_match('/^\d+$/', $timestamp)) {
			$timestamp = '@' . $timestamp;
		}
		$tz = new \DateTimeZone($timezone);
		$dt = new \DateTime($timestamp);
		$dt->setTimezone($tz);

		return $dt->format($format);
	}

	public static function isSerialized($data)
	{
		$data = trim($data);
		if ('N;' == $data)
			return true;
		if (!preg_match('/^([adObis]):/', $data, $badions))
			return false;
		switch ($badions[1]) {
			case 'a' :
			case 'O' :
			case 's' :
				if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data))
					return true;
				break;
			case 'b' :
			case 'i' :
			case 'd' :
				if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data))
					return true;
				break;
		}

		return false;
	}

	/**
	 * @desc 根据两点间的经纬度计算距离
	 *
	 * @param float $lat 纬度值
	 * @param float $lng 经度值
	 */
	public static function getDistance($lat1, $lng1, $lat2, $lng2)
	{
		$earthRadius = 6371393; //approximate radius of earth in meters

		/*
		 Convert these degrees to radians
		to work with the formula
		*/

		$lat1 = ($lat1 * pi()) / 180;
		$lng1 = ($lng1 * pi()) / 180;

		$lat2 = ($lat2 * pi()) / 180;
		$lng2 = ($lng2 * pi()) / 180;

		/*
		 Using the
		Haversine formula
	
		http://en.wikipedia.org/wiki/Haversine_formula
	
		calculate the distance
		*/

		$calcLongitude = $lng2 - $lng1;
		$calcLatitude = $lat2 - $lat1;
		$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
		$stepTwo = 2 * asin(min(1, sqrt($stepOne)));
		$calculatedDistance = $earthRadius * $stepTwo;

		return round($calculatedDistance);
	}

	public static function getImageType($header)
	{
		if ($header{0} . $header{1} == "\x89\x50") {
			return 'png';
		}
		else if ($header{0} . $header{1} == "\xff\xd8") {
			return 'jpeg';
		}
		else if ($header{0} . $header{1} . $header{2} == "\x47\x49\x46") {
			if ($header{4} == "\x37")
				return 'gif'; //gif87
			else if ($header{4} == "\x39")
				return 'gif'; //gif89
		}

		return '';
	}

	public static function calcRawBytes($fileSize)
	{
		$unit = substr($fileSize, -2);
		$unit = strtoupper($unit);
		$unitArr = [ 'B', 'KB', 'MB', 'GB', 'TB', 'PB' ];
		$index = array_search($unit, $unitArr);
		$index = intval($index);
		$bytes = floatval($fileSize);
		$bytes = intval($bytes * pow(1024, $index));

		return $bytes;
	}

	/**
	 * name:  配置名称，需要提前申请；并在接口端配置对应的收件人、标题等信息
	 * content: 邮件正文
	 */
	public static function sendEmailApi($name, $content)
	{
		$params = [ 'name' => $name, 'content' => $content ];
		$posttext = http_build_query($params, null, '&');
		$apiUrl = 'http://service-center.xfilemaster.com/api/sendemail.php';
		$option = [];
		$option['method'] = "POST";
		$option['header'] = "Content-type: application/x-www-form-urlencoded\r\n";
		$option['content'] = $posttext;
		$option['timeout'] = 30;
		$context = stream_context_create([ 'http' => $option ]);
		$json = file_get_contents($apiUrl, false, $context);
		$data = json_decode($json, true);
		if (!empty($data) && $data['code'] === 0) {
			return true;
		}

		return false;
	}

	/**
	 * name: 配置名称，需要提前申请；并在接口端配置对应的接收人手机号
	 * content: 消息正文
	 */
	public static function sendSmsApi($name, $content)
	{
		$params = [ 'name' => $name, 'content' => $content ];
		$posttext = http_build_query($params, null, '&');
		$apiUrl = 'http://service-center.xfilemaster.com/api/sendsms.php';
		$option = [];
		$option['method'] = "POST";
		$option['header'] = "Content-type: application/x-www-form-urlencoded\r\n";
		$option['content'] = $posttext;
		$option['timeout'] = 30;
		$context = stream_context_create([ 'http' => $option ]);
		$json = file_get_contents($apiUrl, false, $context);
		$data = json_decode($json, true);
		if (!empty($data) && $data['code'] === 0) {
			return true;
		}

		return false;
	}

	/**
	 * 微信企业号发送消息接口
	 * name: 配置名称，需要提前申请
	 * content: 消息正文
	 */
	public static function sendWxApi($name, $content)
	{
		$params = [ 'name' => $name, 'content' => $content ];
		$posttext = http_build_query($params, null, '&');
		$apiUrl = 'http://service-center.xfilemaster.com/api/sendwx.php';
		$option = [];
		$option['method'] = "POST";
		$option['header'] = "Content-type: application/x-www-form-urlencoded\r\n";
		$option['content'] = $posttext;
		$option['timeout'] = 30;
		$context = stream_context_create([ 'http' => $option ]);
		$json = file_get_contents($apiUrl, false, $context);
		$data = json_decode($json, true);
		if (!empty($data) && $data['code'] === 0) {
			return true;
		}

		return false;
	}

	/**
	 * 解决php5.5之前版本pathinfo 无法正常获取utf8文件名的问题
	 */
	public static function mbPathInfo($filepath)
	{
		preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', $filepath, $m);
		$ret['dirname'] = !empty($m[1]) ? $m[1] : '';
		$ret['basename'] = !empty($m[2]) ? $m[2] : '';
		$ret['extension'] = !empty($m[5]) ? $m[5] : '';
		$ret['filename'] = !empty($m[3]) ? $m[3] : '';

		return $ret;
	}

	public static function secureLink($url, $expire, $cache = false)
	{
		$urlInfo = parse_url($url);
		$queryUri = $urlInfo['path'];
		$scheme = $urlInfo['scheme'];
		$host = $urlInfo['host'];
		$port = isset($urlInfo['port']) ? ':' . $urlInfo['port'] : "";
		$queryStr = isset($urlInfo['query']) ? $urlInfo['query'] : "";

		$expire = intval($expire);
		$ts = time() + $expire;
		if ($cache) {
			$ts = strtotime(date("Y-m-d H:00:00", $ts + 3600));
		}
		$key = NGX_SECURE_LINK_KEY;
		$signRaw = "{$queryUri}{$ts} {$key}";

		$sign = base64_encode(md5($signRaw, true));
		$sign = strtr($sign, '+/', '-_');
		$sign = str_replace('=', '', $sign);

		$signStr = "t={$sign}&e={$ts}";
		$filterQueryStr = "";
		if (!empty($queryStr)) {
			parse_str($queryStr, $queryArr);
			unset($queryArr['e']);
			unset($queryArr['t']);
			$filterQueryStr = http_build_query($queryArr, null, '&');
		}
		$signUrl = $scheme . '://' . $host . $port . $queryUri . '?' . $signStr;
		if (!empty($filterQueryStr)) {
			$signUrl .= '&' . $filterQueryStr;
		}

		return $signUrl;
	}

	public static function encryptByKaiser($content, $offset)
	{
		return kaiser_encrypt($content, $offset);
	}

	public static function decryptByKaiser($content, $offset)
	{
		return kaiser_decrypt($content, $offset);
	}

	public static function getShortHash($str, $toLower = true)
	{
		if ($toLower) {
			$hash = substr(md5(strtolower($str)), 8, 16);
		}
		else {
			$hash = substr(md5($str), 8, 16);
		}

		return $hash;
	}
}
