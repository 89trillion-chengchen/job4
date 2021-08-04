<?php

namespace framework\data\beanstalk;

class BeanstalkHelper
{

	private $pstalk;

	public function __construct($config)
	{
		if ($config) {
			$this->pstalk = BeanstalkManager::getInstance($config);
		}
	}

	public function watch($tube)
	{
		return $this->pstalk ? $this->pstalk->watch($tube) : false;
	}

	public function reserve($timeout)
	{
		return $this->pstalk ? $this->pstalk->reserve($timeout) : false;
	}

	public function delete($job)
	{
		return $this->pstalk ? $this->pstalk->delete($job) : false;
	}

	public function bury($job)
	{
		return $this->pstalk ? $this->pstalk->bury($job) : false;
	}

	public function put($data, $priority, $delay, $ttr)
	{
		return $this->pstalk ? $this->pstalk->put($data, $priority, $delay, $ttr) : false;
	}

	public function useTube($tube)
	{
		return $this->pstalk ? $this->pstalk->useTube($tube) : false;
	}

	public function watchOnly($tube)
	{
		if (!$this->pstalk) return false;

		if (is_array($tube)) {
			foreach ($tube as $idx => $oneTube) {
				if (0 == $idx) {
					$this->pstalk->watchOnly($oneTube);
				}
				else {
					$this->pstalk->watch($oneTube);
				}
			}

			return $this->pstalk;
		}

		return $this->pstalk->watchOnly($tube);
	}

	public function ignore($tube)
	{
		return $this->pstalk ? $this->pstalk->ignore($tube) : false;
	}
}