<?php

namespace dao;

use framework\data\pdo;
use framework\data\redis;
use framework\data\fastdfs;
use framework\data\memcached;
use framework\data\mongo;

abstract class ConstDaoBase extends DaoBase
{
	/**
	 * 缓存处理器实例
	 */
	protected $rcacheHelper;
	protected $mcacheHelper;
	/**
	 * 数据处理器实例
	 */
	protected $dbHelper;

	/**
	 * 文件处理器实例
	 */
	protected $dfsHelper;

	/**
	 * 搜索处理器实例
	 */
	protected $searchHelper;

	/**
	 * 统计处理器实例
	 */
	protected $statHelper;

	/**
	 * 指定日期统计处理器实例
	 */
	protected $statNoHelper;

	protected $mongoHelper;

	/**
	 * 构造函数
	 *
	 * @param string $entity
	 * @param bool $useCache
	 */
	public function __construct($entity, $db = 'default')
	{
		$this->entity = $entity;
	}

	/**
	 * 使用Mysql数据库
	 */
	public function useDb($config = 'default')
	{
		$this->dbHelper = new pdo\PDOHelper($this->entity);
		$this->dbHelper->setPdo(pdo\PDOManager::getInstance($config));
	}

	/**
	 * 使用MongoDB数据库
	 */
	public function useMongo($config = 'default')
	{
		$this->mongoHelper = new mongo\MongoHelper($config);
	}

	/**
	 * 使用Memcached
	 */
	public function useMemcached()
	{
		$this->mcacheHelper = new memcached\MemcachedHelper(true);
	}

	/**
	 * 使用Redis
	 */
	public function useRedis($config = 'default')
	{
		$this->rcacheHelper = new redis\RedisHelper($config);
	}

	/**
	 * 使用FastDFS
	 */
	public function useFastDFS($config = 'default')
	{
		$this->dfsHelper = new fastdfs\FastDFSHelper($config);
	}

	/**
	 * 使用Sphinx
	 */
	public function useSphinx()
	{
		if (empty($this->searchHelper)) {
			include_once(RES_PATH . '/sphinxapi.php');
			$cl = new \SphinxClient();
			$cl->SetServer(SEARCH_HOST, SEARCH_PORT);
			$cl->SetMatchMode(SPH_MATCH_ANY);
			//$cl->SetSortMode(SPH_SORT_EXTENDED, '@weight desc,last_week_download DESC');
			//$cl->SetWeights(array('name'=>100,'description'=>1));
			$this->searchHelper = $cl;
		}
	}

	/**
	 * 使用TTserver总数统计
	 */
	public function useStat()
	{
		if (empty($this->statHelper)) {
			require_once('/data/app/server/conf/ttserver_cfg_pkgtotalstat.inc');
			require_once(RES_PATH . '/TTServerOpClass.php');
			$totaltt = new \TTServerOpClass('pkgtotalstat');
			$this->statHelper = $totaltt;
		}
	}

	/**
	 * 使用TTserver指定日期下载数统计
	 */
	public function useStatDate($date)
	{
		$month = substr($date, 4, 2);
		$statNo = $month % 3 + 1;
		if (empty($this->statNoHelper[$statNo])) {
			$stat = "stat$statNo";
			require_once("/data/app/server/conf/ttserver_cfg_$stat.inc");
			require_once(RES_PATH . '/TTServerOpClass.php');
			$tt = new \TTServerOpClass($stat);
			$this->statNoHelper[$statNo] = $tt;
		}
	}
}
