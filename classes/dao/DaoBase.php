<?php

namespace dao;

use framework\helper;

abstract class DaoBase
{
	/**
	 * 实例名称
	 *
	 * @var string
	 */
	protected $entity;

	/**
	 * 获取实体的所有属性名称
	 *
	 * @return array
	 */
	protected function getEntityAttribs()
	{
		return array_keys(get_class_vars($this->entity));
	}
}
