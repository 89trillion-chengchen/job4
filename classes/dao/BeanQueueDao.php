<?php
/**
 *
 */

namespace dao;

class BeanQueueDao extends ConstDaoBase
{

	protected $beanHelper;

	/**
	 * 构造函数
	 *
	 * @return
	 */
	public function __construct()
	{
		$this->useBeanstalk();
	}

	public function appendOne($tube, $body, $priority = 1024, $delay = 0, $ttr = 0)
	{
		$doResult = $this->beanHelper->useTube($tube)->put($body, $priority, $delay, $ttr);

		return $doResult;
	}

	public function fetchOne($tube, $timeout)
	{
		$job = $this->beanHelper->watchOnly($tube)->reserve($timeout);

		if (false === $job) {
			return false;
		}

		$jobData = $job->getData();

		$this->beanHelper->delete($job);

		return $jobData;
	}

	public function fetchBulk($tube, $count, $timeout)
	{
		if ($count < 1) {
			return [];
		}

		$jobLists = [];
		for ($i = 0; $i < $count; $i++) {
			// 第二次循环时强制设置最长超时为1秒，便于尽快将已经取到的数组返回
			if ($i >= 1) $timeout = min(1, $timeout);

			$jobData = $this->fetchOne($tube, $timeout);
			// 若本次没有取到任务，则直接中止循环
			if (empty($jobData)) break;

			$jobLists[] = $jobData;
		}

		return $jobLists;
	}
}