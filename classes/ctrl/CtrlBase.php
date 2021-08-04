<?php
/**
 *
 * @file CtrlBase.php
 * @author ChengRennt <ChengRennt@gmail.com>
 * @date 2013-3-22 下午1:34:14
 * @description Ctrl 的基础类
 */

namespace ctrl;

use framework\util;
use framework\view;
use framework\mvc\IController;
use framework\mvc\IRequestDispatcher;
use \view\RedirectView;
use \utils\Functions;

/**
 * CtrlBase Ctrl 的基础类
 *
 * @package CtrlBase
 * @subpackage framework.core.IController
 */
class CtrlBase implements IController
{
	/**
	 * dispatcher 对象
	 *
	 * @var object
	 * @access protected
	 */
	protected $dispatcher;

	/**
	 * 设置 dispatcher
	 *
	 * @param dispatcher $
	 *
	 * @return void
	 * @access public
	 */
	public function setDispatcher(IRequestDispatcher $dispatcher)
	{
		$this->dispatcher = $dispatcher;
	}

	/**
	 * 获取 dispatcher
	 *
	 * @return framework \core\IRequestDispatcher
	 * @access public
	 */
	public function getDispatcher()
	{
		return $this->dispatcher;
	}

	/**
	 * 在执行具体动作前，要执行的动作
	 *
	 * @return ture /false
	 * @access public
	 */
	function beforeFilter()
	{
		return true;
	}

	/**
	 * 在完成功能后，所执行的动作
	 *
	 * @return ture /false
	 * @access public
	 */
	function afterFilter()
	{
		return true;
	}

	/**
	 * 构造函数
	 *
	 * @return void
	 * @access public
	 */
	public function __construct()
	{
	}
}
