<?php
namespace framework\data\redis;

use \Redis;

/**
 * Redis数据处理类
 *
 * @author zivn
 * @package framework\data\redis
 */
class RedisHelper
{
	/**
	 * Redis对象
	 *
	 * @var \Redis
	 */
	private $redis;

	/**
	 * 构造函数
	 *
	 * @param bool $enable
	 */
	public function __construct($config)
	{
		/**
		 * if ($enable)
		 * {
		 * $this->redis = RedisManager::getInstance();
		 * }
		 */
		if ($config) {
			$this->redis = RedisManager::getInstance($config);
		}
	}

	/**
	 * 启用缓存
	 */
	function enable()
	{
		if (empty($this->redis)) {
			$this->redis = RedisManager::getInstance();
		}
	}

	/**
	 * 禁用缓存
	 */
	function disable()
	{
		if (!empty($this->redis)) {
			$this->redis = null;
		}
	}

	/**
	 * 取得Redis对象
	 *
	 * @return \Redis
	 */
	function getRedis()
	{
		return $this->redis;
	}

	/**
	 * 存储指定键名的数据（如存在则覆盖）
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param int $expiration
	 *
	 * @return bool
	 */
	public function set($key, $value, $expiration = 0)
	{
		if ($expiration) {
			return $this->redis ? $this->redis->set($key, $value, $expiration) : null;
		}
		else {
			return $this->redis ? $this->redis->set($key, $value) : null;
		}
	}

	/**
	 * 获取指定键名的数据
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get($key)
	{
		return $this->redis ? $this->redis->get($key) : null;
	}

	/**
	 * 为给定 key 设置生存时间，当 key 过期时(生存时间为 0 )，它会被自动删除。
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function expire($key, $ttl)
	{
		return $this->redis ? $this->redis->expire($key, $ttl) : null;
	}

	/**
	 * 增加整数数据的值
	 *
	 * @param string $key
	 * @param int $offset
	 *
	 * @return bool
	 */
	public function incr($key, $offset = 1)
	{
		return $this->redis ? $this->redis->incr($key, $offset) : null;
	}

	/**
	 * 减少整数数据的值
	 *
	 * @param string $key
	 * @param int $offset
	 *
	 * @return bool
	 */
	public function decr($key, $offset = 1)
	{
		return $this->redis ? $this->redis->decr($key, $offset) : null;
	}

	/**
	 * 删除指定数据
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function delete($key)
	{
		return $this->redis ? $this->redis->del($key) : null;
	}

	/**
	 * 获取服务器统计信息
	 *
	 * @return array
	 */
	public function info()
	{
		return $this->redis ? $this->redis->info() : null;
	}

	/**
	 * 检查指定键名存在与否
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function exists($key)
	{
		return $this->redis ? $this->redis->exists($key) : null;
	}

	/**
	 * 将哈希表 key 中的域 field 的值设为 value
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hSet($key, $hashKey, $value)
	{
		return $this->redis ? $this->redis->hSet($key, $hashKey, $value) : null;
	}

	/**
	 * 将哈希表 key 中的域 field 的值设置为 value ，当且仅当域 field 不存在
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hSetNx($key, $hashKey, $value)
	{
		return $this->redis ? $this->redis->hSetNx($key, $hashKey, $value) : null;
	}

	/**
	 * 返回哈希表 key 中给定域 field 的值
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hGet($key, $hashKey)
	{
		return $this->redis ? $this->redis->hGet($key, $hashKey) : null;
	}

	/**
	 * 返回哈希表 key 中域的数量
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hLen($key)
	{
		return $this->redis ? $this->redis->hLen($key) : null;
	}

	/**
	 * 删除哈希表 key 中的一个或多个指定域，不存在的域将被忽略
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hDel($key, $hashKey)
	{
		return $this->redis ? $this->redis->hDel($key, $hashKey) : null;
	}

	/**
	 * 返回哈希表 key 中的所有域
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hKeys($key)
	{
		return $this->redis ? $this->redis->hKeys($key) : null;
	}

	/**
	 * 返回哈希表 key 中所有域的值
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hVals($key)
	{
		return $this->redis ? $this->redis->hVals($key) : null;
	}

	/**
	 * 返回哈希表 key 中，所有的域和值
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hGetAll($key)
	{
		return $this->redis ? $this->redis->hGetAll($key) : null;
	}

	/**
	 * 查看哈希表 key 中，给定域 field 是否存在
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hExists($key, $hashKey)
	{
		return $this->redis ? $this->redis->hExists($key, $hashKey) : null;
	}

	/**
	 * 为哈希表 key 中的域 field 的值加上增量 increment
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hIncrBy($key, $hashKey, $value)
	{
		return $this->redis ? $this->redis->hIncrBy($key, $hashKey, $value) : null;
	}

	/**
	 * 同时将多个 field-value (域-值)对设置到哈希表 key 中
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hMset($key, $members)
	{
		return $this->redis ? $this->redis->hMset($key, $members) : null;
	}

	/**
	 * 返回哈希表 key 中，一个或多个给定域的值
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function hmGet($key, $memberKeys)
	{
		return $this->redis ? $this->redis->hmGet($key, $memberKeys) : null;
	}

	/**
	 * 一次查询多个String类型的Key
	 */
	public function mGet($keyList)
	{
		return $this->redis ? $this->redis->mGet($keyList) : null;
	}

	/**
	 * 将一个或多个 member 元素加入到集合 key 当中，已经存在于集合的 member 元素将被忽略
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function sAdd($key, $value)
	{
		return $this->redis ? $this->redis->sAdd($key, $value) : null;
	}

	/**
	 * 判断member元素是否是集合key的成员
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function sIsMember($key, $value)
	{
		return $this->redis ? $this->redis->sIsMember($key, $value) : null;
	}

	/**
	 * 返回集合 key 的基数(集合中元素的数量)
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function sSize($key)
	{
		return $this->redis ? $this->redis->sSize($key) : null;
	}

	/**
	 * 如果命令执行时，只提供了 key 参数，那么返回集合中的一个随机元素
	 * 如果 count 为正数，且小于集合基数，那么命令返回一个包含 count 个元素的数组，数组中的元素各不相同。如果 count 大于等于集合基数，那么返回整个集合
	 * 如果 count 为负数，那么命令返回一个数组，数组中的元素可能会重复出现多次，而数组的长度为 count 的绝对值
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function sRandMember($key, $count = null)
	{
		if (is_null($count)) {
			return $this->redis ? $this->redis->sRandMember($key) : null;
		}

		return $this->redis ? $this->redis->sRandMember($key, $count) : null;
	}

	/**
	 * 移除集合key中的一个或多个member元素，不存在的member元素会被忽略
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function sRem($key, $member)
	{
		return $this->redis ? $this->redis->sRem($key, $member) : null;
	}

	/**
	 * 返回集合 key 中的所有元素
	 *
	 * @param string $key
	 *
	 * @return array
	 */
	public function sMembers($key)
	{
		return $this->redis ? $this->redis->sMembers($key) : null;
	}

	/**
	 * 将一个或多个member元素及其score值加入到有序集key当中
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function zAdd($key, $score, $member)
	{
		return $this->redis ? $this->redis->zAdd($key, $score, $member) : null;
	}

	/**
	 * 返回有序集 key 的基数
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function zSize($key)
	{
		return $this->redis ? $this->redis->zSize($key) : null;
	}

	/**
	 * 为有序集key的成员member的score值加上增量increment
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function zIncrBy($key, $increment, $member)
	{
		return $this->redis ? $this->redis->zIncrBy($key, $increment, $member) : null;
	}

	/**
	 * 返回有序集key中，指定区间内的成员。
	 * 其中成员的位置按score值递增(从小到大)来排序。
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function zRange($key, $start, $end, $withscores = false)
	{
		return $this->redis ? $this->redis->zRange($key, $start, $end, $withscores) : null;
	}

	/**
	 * 返回有序集key中，指定区间内的成员。
	 * 其中成员的位置按score值递增(从大到小)来排序。
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function zRevRange($key, $start, $end, $withscores = false)
	{
		return $this->redis ? $this->redis->zRevRange($key, $start, $end, $withscores) : null;
	}

	/**
	 * 返回有序集key中成员member的排名。其中有序集成员按score值递增(从小到大)顺序排列。
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function zRank($key, $member)
	{
		return $this->redis ? $this->redis->zRank($key, $member) : null;
	}

	/**
	 * 返回有序集key中成员member的排名。其中有序集成员按score值递减(从大到小)排序。
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function zRevRank($key, $member)
	{
		return $this->redis ? $this->redis->zRevRank($key, $member) : null;
	}

	/**
	 * 返回有序集key中，成员member的score值。
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function zScore($key, $member)
	{
		return $this->redis ? $this->redis->zScore($key, $member) : null;
	}

	/**
	 * 移除集合key中的一个或多个member元素，不存在的member元素会被忽略
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function zDelete($key, $member)
	{
		return $this->redis ? $this->redis->zRem($key, $member) : null;
	}

	public function zCount($key, $min, $max)
	{
		return $this->redis ? $this->redis->zCount($key, $min, $max) : null;
	}

	public function zRemRangeByRank($key, $start, $stop)
	{
		return $this->redis ? $this->redis->ZREMRANGEBYRANK($key, $start, $stop) : null;
	}

	/**
	 * 返回最后一条错误信息
	 *
	 * @return mixed
	 */
	public function getLastError()
	{
		return $this->redis ? $this->redis->getLastError() : null;
	}

	/**
	 * 返回列表 key 的长度。如果 key 不存在，则 key 被解释为一个空列表，返回 0，如果 key 不是列表类型，返回false
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function lLen($key)
	{
		return $this->redis ? $this->redis->lLen($key) : null;
	}

	/**
	 * 将一个或多个值 value 插入到列表 key 的表尾(最右边)。返回值：执行 LPUSH 操作后，表的长度。
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function lPush($key, $value)
	{
		return $this->redis ? $this->redis->lPush($key, $value) : null;
	}

	public function lPop($key)
	{
		return $this->redis ? $this->redis->lPop($key) : null;
	}

	/**
	 * 将一个或多个值 value 插入到列表 key 的表尾(最右边)。返回值：执行 RPUSH 操作后，表的长度。
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function rPush($key, $value)
	{
		return $this->redis ? $this->redis->rPush($key, $value) : null;
	}

	public function rPop($key)
	{
		return $this->redis ? $this->redis->rPop($key) : null;
	}


	/**
	 * 将列表 key 下标为 index 的元素的值设置为 value。
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function lSet($key, $index, $value)
	{
		return $this->redis ? $this->redis->lSet($key, $index, $value) : null;
	}

	/**
	 * 返回列表 key 中，下标为 index 的元素。
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function lIndex($key, $index)
	{
		return $this->redis ? $this->redis->lIndex($key, $index) : null;
	}

	/**
	 * 对一个列表进行修剪(trim)，就是说，让列表只保留指定区间内的元素，不在指定区间之内的元素都将被删除。
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function lTrim($key, $start, $stop)
	{
		return $this->redis ? $this->redis->lTrim($key, $start, $stop) : null;
	}

	/**
	 * 返回列表 key 中指定区间内的元素，区间以偏移量 start 和 stop 指定。
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function lRange($key, $start = '0', $stop = '-1')
	{
		return $this->redis ? $this->redis->lRange($key, $start, $stop) : null;
	}

	/**
	 * 移除集合key中的一个或多个member元素，不存在的member元素会被忽略
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function LRem($key, $count, $value)
	{
		return $this->redis ? $this->redis->LRem($key, $value, $count) : null;
	}

	/**
	 * 将一个已有的KEY重新命名；若新名字的KEY已经存在，则会被覆盖
	 *
	 * @param string $oldKey
	 * @param string $newKey
	 */
	public function rename($srcKey, $dstKey)
	{
		return $this->redis ? $this->redis->rename($srcKey, $dstKey) : null;
	}

	/**
	 * 将一个已有的KEY重新命名；若新名字的KEY已经存在，则返回错误
	 *
	 * @param string $oldKey
	 * @param string $newKey
	 */
	public function renameNx($srcKey, $dstKey)
	{
		return $this->redis ? $this->redis->renameNx($srcKey, $dstKey) : null;
	}

	/**
	 * 返回一个有序集合内指定区域内的元素，一般应用于关键词自动完成
	 *
	 * @param  string $oldKey
	 * @param string $newKey
	 * @param  $start
	 */
	public function zrangebylex($key, $start, $stop, $offset, $limit)
	{
		return $this->redis ? $this->redis->zrangebylex($key, $start, $stop, $offset, $limit) : null;
	}

	public function zRangeByScore($key, $min, $max, $args)
	{
		return $this->redis ? $this->redis->zRangeByScore($key, $min, $max, $args) : null;
	}

	public function zRevRangeByScore($key, $max, $min, $args)
	{
		return $this->redis ? $this->redis->zRevRangeByScore($key, $max, $min, $args) : null;
	}

	public function zCard($key)
	{
		return $this->redis ? $this->redis->zCard($key) : null;
	}

	public function dump($key)
	{
		return $this->redis ? $this->redis->dump($key) : null;
	}

	public function restore($key, $ttl, $data)
	{
		return $this->redis ? $this->redis->restore($key, $ttl, $data) : null;
	}

}

