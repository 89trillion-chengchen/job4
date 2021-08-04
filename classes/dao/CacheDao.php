<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-2-27 下午3:30:58
 * @description:
 * $Id: CacheDao.php 5738 2014-11-10 07:45:41Z huzhigang $
 */

namespace dao;

class CacheDao extends ConstDaoBase
{

	public function __construct()
	{
		$this->conf = 'default';
	}

	public function setConf($name)
	{
		$this->conf = $name;
	}

	/**
	 * 检查缓存键名是否存在
	 */
	public function exists($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->exists($key);
	}

	/**
	 * 设置缓存键名过期时间
	 */
	public function expire($key, $ttl)
	{
		$this->useRedis($this->conf);
		$result = $this->rcacheHelper->expire($key, $ttl);

		return $result;
	}

	/**
	 * 删除缓存键名
	 */
	public function delete($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->delete($key);
	}

	/**
	 * 获取列表缓存
	 */
	public function getList($key, $index)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->lIndex($key, $index);
	}

	/**
	 * 设置列表缓存
	 */
	public function setList($key, $index, $value)
	{
		$this->useRedis($this->conf);
		$len = $this->rcacheHelper->lLen($key);
		if ($len === 0) {
			return $this->rcacheHelper->rPush($key, $value);
		}
		else if ($len === false) {
			return false;
		}
		else if ($len > $index) {
			return $this->rcacheHelper->lSet($key, $index, $value);
		}
	}

	/**
	 * 获取整个列表缓存
	 */
	public function getLists($key, $start = '0', $num = '-1')
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->lRange($key, $start, $num);
	}

	/**
	 * 添加列表缓存
	 */
	public function lPush($key, $value)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->lPush($key, $value);
	}

	public function lPop($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->lPop($key);
	}

	public function rPush($key, $value)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->rPush($key, $value);
	}

	public function rPop($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->rPop($key);
	}

	/**
	 * 获取整个列表缓存
	 */
	public function lRange($key, $start = '0', $num = '-1')
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->lRange($key, $start, $num);
	}

	/**
	 * 设置整个列表缓存
	 */
	public function setLists($key, $datas)
	{
		$this->useRedis($this->conf);
		$len = $this->rcacheHelper->lLen($key);
		if ($len === false) {
			return false;
		}
		else {
			$this->rcacheHelper->lTrim($key, 1, 0);
		}
		$result = true;
		foreach ($datas as $data) {
			if (!$this->rcacheHelper->rPush($key, $data)) {
				$result = false;
			}
		}

		return $result;
	}

	public function lTrim($key, $start, $end)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->lTrim($key, $start, $end);
	}

	/**
	 * 设置哈希表的字段
	 */
	public function setHash($key, $field, $value)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->hSet($key, $field, $value) !== false;
	}

	/**
	 * 设置哈希表的字段, 只有当字段不存在时才设置
	 */
	public function setHashNx($key, $field, $value)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->hSetNx($key, $field, $value) !== false;
	}

	/**
	 * 获取哈希表的字段
	 */
	public function getHash($key, $field)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->hGet($key, $field);
	}

	/**
	 * 检查哈希表的字段是否存在
	 */
	public function existsHash($key, $field)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->hExists($key, $field);
	}

	/**
	 * 删除哈希表的字段
	 */
	public function delHash($key, $field)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->hDel($key, $field);
	}

	/**
	 * 获取哈希表的全部数据
	 */
	public function getAllHash($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->hGetAll($key);
	}

	/**
	 * 设置哈希表的全部数据
	 */
	public function setAllHash($key, $members)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->hMset($key, $members);
	}

	/**
	 * 获取字符串键名的数据
	 */
	public function get($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->get($key);
	}

	/**
	 * 设置字符串键名的数据
	 */
	public function set($key, $value, $expiration)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->set($key, $value, $expiration);
	}

	/**
	 * 设置字符串集合的数据
	 */
	public function zAdd($key, $score, $member)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zAdd($key, $score, $member);
	}

	/**
	 * 获取字符串集合的数据
	 */
	public function getscore($key, $member)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zScore($key, $member);
	}

	/**
	 * 返回有序集key中，指定区间内的成员。
	 * 其中成员的位置按score值递增(从小到大)来排序。
	 */
	public function getRange($key, $start, $end, $withscores = false)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zRange($key, $start, $end, $withscores = false);
	}

	/**
	 * 返回有序集key中，指定区间内的成员。
	 * 其中成员的位置按score值递增(从大到小)来排序。
	 */
	public function getRevRange($key, $start, $end, $withscores = false)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zRevRange($key, $start, $end, $withscores = false);
	}

	/**
	 * 移除有序集 key 中的一个或多个成员，不存在的成员将被忽略。
	 */
	public function zDelete($key, $member)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zDelete($key, $member);
	}

	public function zCount($key, $min, $max)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zCount($key, $min, $max);
	}

	/**
	 * 移除有序集 key 中的一个或多个成员，不存在的成员将被忽略。
	 */
	public function LDelete($key, $count, $value)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->LRem($key, $count, $value);
	}

	/**
	 * 获取key 长度。
	 */
	public function lLen($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->lLen($key);
	}

	/**
	 * 获取keys。
	 */
	public function hkeys($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->hkeys($key);
	}

	/**
	 * hash增加值
	 */

	public function hIncrBy($key, $value, $count)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->hIncrBy($key, $value, $count);
	}

	/**
	 * 获得hash的长度
	 */
	public function hLen($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->hLen($key);
	}

	/**
	 * 有序化输出
	 */
	public function zRevRange($key, $start, $end, $withscores)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zRevRange($key, $start, $end, $withscores);
	}

	/**
	 * 有序化增加
	 */
	public function zIncrBy($key, $increment, $member)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zIncrBy($key, $increment, $member);
	}

	/**
	 * 获得某个元素的位置
	 */
	public function zRevRank($key, $member)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zRevRank($key, $member);
	}

	/**
	 * 按排名删除有序集合部分元素
	 */
	public function zRemRangeByRank($key, $start, $stop)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zRemRangeByRank($key, $start, $stop);
	}

	/**
	 * 集合中增加成员
	 */
	public function sAdd($key, $member)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->sAdd($key, $member);
	}

	/**
	 * 列出集合中成员
	 */
	public function sMembers($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->sMembers($key);
	}

	/**
	 * 删除集合中成员
	 */
	public function sRem($key, $member)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->sRem($key, $member);
	}

	/**
	 * 批量获取hash对象
	 */
	public function hMGet($key, $members)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->hMGet($key, $members);
	}

	public function rename($srcKey, $dstKey)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->rename($srcKey, $dstKey);
	}

	public function renameNx($srcKey, $dstKey)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->renameNx($srcKey, $dstKey);
	}

	public function zrangebylex($key, $start, $stop, $offset, $limit)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zrangebylex($key, $start, $stop, $offset, $limit);
	}

	public function zRangeByScore($key, $min, $max, $withscores, $offset, $count)
	{
		$this->useRedis($this->conf);
		$args = [
			'withscores' => $withscores,
			'limit'      => [ $offset, $count ],
		];

		return $this->rcacheHelper->zRangeByScore($key, $min, $max, $args);
	}

	public function zRevRangeByScore($key, $max, $min, $withscores, $offset, $count)
	{
		$this->useRedis($this->conf);
		$args = [
			'withscores' => $withscores,
			'limit'      => [ $offset, $count ],
		];

		return $this->rcacheHelper->zRevRangeByScore($key, $max, $min, $args);
	}

	public function zCard($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->zCard($key);
	}

	public function dump($key)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->dump($key);
	}

	public function restore($key, $ttl, $data)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->restore($key, $ttl, $data);
	}

	public function getMulti($keyList)
	{
		$this->useRedis($this->conf);

		return $this->rcacheHelper->mGet($keyList);
	}
}
