<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-2-27 下午3:30:04
 * @description:
 * $Id: CacheService.php 5879 2014-11-14 12:16:20Z huzhigang $
 */

namespace service;

use utils\Functions;

/**
 *
 */
class CacheService extends BaseService
{

	public function __construct()
	{
		$this->dataDao = parent::__construct("dao\\CacheDao");
	}

	public function setConf($name)
	{
		$this->dataDao->setConf($name);
	}

	/**
	 * 检查缓存键名是否存在
	 */
	public function exists($key)
	{
		return $this->dataDao->exists($key);
	}

	/**
	 * 设置缓存键名过期时间
	 */
	public function expire($key, $ttl)
	{
		return $this->dataDao->expire($key, $ttl);
	}

	/**
	 * 删除缓存键名
	 */
	public function delete($key)
	{
		return $this->dataDao->delete($key);
	}

	public function getList($key, $index)
	{
		return $this->dataDao->getList($key, $index);
	}

	public function setList($key, $index, $data)
	{
		return $this->dataDao->setList($key, $index, $data);
	}

	public function getLists($key)
	{
		return $this->dataDao->getLists($key);
	}

	public function lPush($key, $value)
	{
		return $this->dataDao->lPush($key, $value);
	}

	public function lPop($key)
	{
		return $this->dataDao->lPop($key);
	}

	public function rPush($key, $value)
	{
		return $this->dataDao->rPush($key, $value);
	}

	public function rPop($key)
	{
		return $this->dataDao->rPop($key);
	}

	public function lRange($key, $start = '0', $num = '-1')
	{
		return $this->dataDao->lRange($key, $start, $num);
	}

	public function setLists($key, $datas)
	{
		return $this->dataDao->setLists($key, $datas);
	}

	public function lTrim($key, $start, $end)
	{
		return $this->dataDao->lTrim($key, $start, $end);
	}

	/**
	 * 设置字符串集合
	 */
	public function zAdd($key, $score, $member)
	{
		return $this->dataDao->zAdd($key, $score, $member);
	}

	/**
	 * 获得有序集合
	 */
	public function zRevRange($key, $start, $end, $withscores)
	{
		return $this->dataDao->zRevRange($key, $start, $end, $withscores);
	}

	/**
	 * 有序增加
	 */
	public function zIncrBy($key, $increment, $member)
	{
		return $this->dataDao->zIncrBy($key, $increment, $member);
	}

	/**
	 * 获得指定元素的位置
	 */
	public function zRevRank($key, $member)
	{
		return $this->dataDao->zRevRank($key, $member);
	}

	/**
	 * 获取字符串集合的数据
	 */
	public function getscore($key, $member)
	{
		return $this->dataDao->getscore($key, $member);
	}

	/**
	 * 返回有序集key中，指定区间内的成员。
	 * 其中成员的位置按score值递增(从小到大)来排序。
	 */
	public function getRange($key, $start, $end, $withscores = false)
	{
		return $this->dataDao->getRange($key, $start, $end, $withscores = false);
	}

	/**
	 * 返回有序集key中，指定区间内的成员。
	 * 其中成员的位置按score值递增(从大到小)来排序。
	 */
	public function getRevRange($key, $start, $end, $withscores = false)
	{
		return $this->dataDao->zRevRange($key, $start, $end, $withscores = false);
	}

	/**
	 * 移除有序集 key 中的一个或多个成员，不存在的成员将被忽略。
	 */
	public function zDelete($key, $member)
	{
		return $this->dataDao->zDelete($key, $member);
	}

	public function zCount($key, $min, $max)
	{
		return $this->dataDao->zCount($key, $min, $max);
	}

	/**
	 * 移除有序集 key 中的一个或多个成员，不存在的成员将被忽略。
	 */
	public function LDelete($key, $count, $value)
	{
		return $this->dataDao->LDelete($key, $count, $value);
	}

	/**
	 * 按排名删除有序集合部分元素
	 */
	public function zRemRangeByRank($key, $start, $stop)
	{
		return $this->dataDao->zRemRangeByRank($key, $start, $stop);
	}

	/**
	 * 获取key长度。
	 */
	public function lLen($key)
	{
		return $this->dataDao->lLen($key);
	}

	/**
	 * 获取keys。
	 */
	public function hkeys($key)
	{
		return $this->dataDao->hkeys($key);
	}

	/**
	 * 设置哈希表
	 */
	public function setHash($key, $field, $value)
	{
		return $this->dataDao->setHash($key, $field, $value);
	}

	/**
	 * 设置哈希表
	 */
	public function setHashNx($key, $field, $value)
	{
		return $this->dataDao->setHashNx($key, $field, $value);
	}

	/**
	 * 获取哈希表
	 */
	public function getHash($key, $field)
	{
		return $this->dataDao->getHash($key, $field);
	}

	/**
	 * 检查哈希表的字段是否存在
	 */
	public function existsHash($key, $field)
	{
		return $this->dataDao->existsHash($key, $field);
	}

	/**
	 * 删除哈希表的字段
	 */
	public function delHash($key, $field)
	{
		return $this->dataDao->delHash($key, $field);
	}

	/**
	 * 获取老系统哈希表的全部数据
	 */
	public function getAllHashOld($key)
	{
		return $this->dataDao->getAllHashOld($key);
	}

	/**
	 * 获取哈希表的全部数据
	 */
	public function getAllHash($key)
	{
		return $this->dataDao->getAllHash($key);
	}

	/**
	 * 设置哈希表的全部数据
	 */
	public function setAllHash($key, $members)
	{
		return $this->dataDao->setAllHash($key, $members);
	}

	/**
	 * 获取字符串键名的数据
	 */
	public function get($key)
	{
		$val = $this->dataDao->get($key);
		/*
		 * 判断是否是序列化数组数据
		 */
		if (Functions::isSerialized($val)) {
			$val = unserialize($val);
		}

		return $val;
	}

	/**
	 * 设置字符串键名的数据
	 */
	public function set($key, $value, $expiration = 0)
	{
		if (is_array($value) || is_object($value)) {
			$value = serialize($value);
		}

		return $this->dataDao->set($key, $value, $expiration);
	}

	/**
	 * hash增加值
	 */

	public function hIncrBy($key, $value, $count)
	{
		return $this->dataDao->hIncrBy($key, $value, $count);
	}

	/**
	 * 获得hash的长度
	 */
	public function hLen($key)
	{
		return $this->dataDao->hLen($key);
	}

	public function setAdd($key, $value)
	{
		return $this->dataDao->sAdd($key, $value);
	}

	public function setMembers($key)
	{
		return $this->dataDao->sMembers($key);
	}

	public function setRem($key, $value)
	{
		return $this->dataDao->sRem($key, $value);
	}

	public function getMultiHash($key, $members)
	{
		return $this->dataDao->hMGet($key, $members);
	}

	/**
	 * 将一个已有的KEY重新命名；若新名字的KEY已经存在，则会被覆盖
	 */
	public function rename($srcKey, $dstKey)
	{
		return $this->dataDao->rename($srcKey, $dstKey);
	}

	/**
	 * 将一个已有的KEY重新命名；若新名字的KEY已经存在，则返回错误
	 */
	public function renameNx($srcKey, $dstKey)
	{
		return $this->dataDao->renameNx($srcKey, $dstKey);
	}

	/**
	 * 返回一个有序集合内指定区域内的元素，一般应用于关键词自动完成
	 */
	public function zrangebylex($key, $start, $stop, $offset = 0, $limit = -1)
	{
		return $this->dataDao->zrangebylex($key, $start, $stop, $offset, $limit);
	}

	public function zRangeByScore($key, $min, $max, $withscore = false, $offset = 0, $count = -1)
	{
		return $this->dataDao->zRangeByScore($key, $min, $max, $withscore, $offset, $count);
	}

	public function zRevRangeByScore($key, $max, $min, $withscore = false, $offset = 0, $count = -1)
	{
		return $this->dataDao->zRevRangeByScore($key, $max, $min, $withscore, $offset, $count);
	}

	public function zCard($key)
	{
		return $this->dataDao->zCard($key);
	}

	public function dump($key)
	{
		return $this->dataDao->dump($key);
	}

	public function restore($key, $ttl, $data)
	{
		return $this->dataDao->restore($key, $ttl, $data);
	}

	public function getMulti($keyList)
	{
		return $this->dataDao->getMulti($keyList);
	}


}
