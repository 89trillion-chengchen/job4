<?php
/**
 *
 */

namespace service;

use entity;
use dao;
use framework\util;
use utils\Functions;

class CounterService extends BaseService
{
	public function __construct()
	{
		//$this->dataDao = parent::__construct("dao\\CacheDao");
	}

	public function checkUploadParams($params)
	{
		extract($params);

		if (!isset($type) || empty($type)) {
			return [ false, 'lack_of_params' ];
		}

		if (!isset($id) || empty($id)) {
			return [ false, 'lack_of_id' ];
		}

		return [ true, 'ok' ];
	}

	public function incrCounter($objId, $type)
	{
		$confs = Functions::getGlobalVars('counter_confs');
		if (!isset($confs[$type])) {
			return 0;
		}

		$conf = $confs[$type];
		$type = $conf['key'];
		$cacheSrv = util\Singleton::get("service\\CacheService");
		$cacheKey = str_replace('{type}', $type, GENERAL_COUNTER_CACHE_TPL);
		$viewCnt = $cacheSrv->hIncrBy($cacheKey, $objId, 1);

		return $viewCnt;
	}

	public function initCounter($objId, $type)
	{
		$confs = Functions::getGlobalVars('counter_confs');
		if (!isset($confs[$type])) {
			return 0;
		}

		$conf = $confs[$type];
		$type = $conf['key'];
		$cacheSrv = util\Singleton::get("service\\CacheService");
		$cacheKey = str_replace('{type}', $type, GENERAL_COUNTER_CACHE_TPL);
		$cnt = $cacheSrv->getHash($cacheKey, $objId);
		if (false !== $cnt) {
			return $cnt;
		}

		list($minCnt, $maxCnt) = explode('_', $conf['range']);
		$viewCnt = rand($minCnt, $maxCnt);
		$cacheSrv->setHash($cacheKey, $objId, $viewCnt);

		return $viewCnt;
	}

	public function getCounter($objId, $type)
	{
		$confs = Functions::getGlobalVars('counter_confs');
		if (!isset($confs[$type])) {
			return 0;
		}

		$key = $confs[$type]['key'];
		$cacheSrv = util\Singleton::get("service\\CacheService");
		$cacheKey = str_replace('{type}', $key, GENERAL_COUNTER_CACHE_TPL);
		$cnt = $cacheSrv->getHash($cacheKey, $objId);

		return $cnt;
	}
}
