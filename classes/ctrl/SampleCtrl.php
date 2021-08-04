<?php
/**
 *
 */

namespace ctrl;

use framework\util;
use framework\mvc\view\smarty;
use service\AnswerService;
use view\JsonView;

class SampleCtrl extends CtrlBase
{
	/**
	 * 构造函数，继承父方法
	 *
	 * @return void
	 * @access public
	 */
	public function __construct()
	{
		parent::__construct();
	}
}
