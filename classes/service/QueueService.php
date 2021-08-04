<?php
/**
 * 消息队列服务
 */

namespace service;

use framework\util;
use utils\StatLogger;

class QueueService extends BaseService
{

	public function __construct()
	{
	}

	/**
	 * 添加一个队列消息到 beanstalkd 中
	 *
	 * @param int $queueBody
	 * @param int $priority
	 *
	 * @return mixed
	 */
	public static function appendBeanQueue($queueBody, $priority=100)
	{
		try {
			$beanDao = util\Singleton::get("dao\\BeanQueueDao");
			$tubeName = self::genBeanTubeName();
			$result = $beanDao->appendOne($tubeName, $queueBody, $priority);
		}
		catch (\Exception $e) {
			// beanstalkd服务异常
			$logType = sprintf("queue_error");
			$logText = date("c") . "\t[Bean_Exception]\tappend\t$queueBody\t" . $e->getMessage();
			StatLogger::log($logType, $logText);

			return [ false, 'bean_exception' ];
		}

		// beanstalk队列插入失败
		if (!$result) {
			$logType = sprintf("queue_error");
			$logText = date("c") . "\t[Bean_Failed]\tappend\t$queueBody";
			StatLogger::log($logType, $logText);

			return [ false, 'queue_faild' ];
		}

		return [ true, 'ok' ];
	}

	/**
	 * 从队列中获取多条待处理消息
	 */
	public static function fetchBulkQueue($maxCnt = 10, $timeout=0)
	{
		try {
			$beanDao = util\Singleton::get("dao\\BeanQueueDao");
			$tubeName = self::genBeanTubeName();
			$bodyList = $beanDao->fetchBulk($tubeName, $maxCnt, $timeout);
		}
		catch (\Exception $e) {
			$logType = sprintf("queue_error");
			$logText = date("c") . "\t[Bean_Exception]\tfetchBulk\t" . $e->getMessage();
			StatLogger::log($logType, $logText);

			return [];
		}

		if (empty($bodyList) || !is_array($bodyList)) {
			return [];
		}

		$fmtLists = [];
		foreach ($bodyList as $body) {
			$data = json_decode($body, true);
			$fmtLists[] = $data;
		}

		return $fmtLists;
	}

	/**
	 * Beanstalk 队列 tube 名称构造
	 *
	 * @return string
	 */
	protected static function genBeanTubeName()
	{
		// TODO 替换为实际队列名称
		return 'test_queue';
	}

}
