<?php

namespace utils;

class GoogleShortUrl
{
	public static $apiUrl = null;
	public static $request = '';
	public static $response = '';

	public static function init()
	{
		self::$apiUrl = GOOGLE_SHORTURL_API . GOOGLE_API_KEY;
	}

	public static function make($longUrl)
	{
		self::init();
		$postData = [ 'longUrl' => $longUrl ];
		$postJson = json_encode($postData, true);
		self::$request = $postJson;
		$configs = [ 'proxy' => PROXY_URL ];
		$headers = [
			'Content-Type: application/json; charset=utf-8',
			'Content-Length: ' . strlen($postJson),
		];
		$response = Functions::curl(self::$apiUrl, 'POST', $postJson, $headers, $configs);
		self::$response = $response;
		$data = json_decode($response, true);
		if (is_array($data) && isset($data['id']) && !empty($data['id'])) {
			return $data['id'];
		}
		$response = str_replace("\n", "", $response);
		$logStr = "\nrequest: $postJson\nresponse: $response\n";
		Functions::log('google', $logStr);

		return $longUrl;
	}
}