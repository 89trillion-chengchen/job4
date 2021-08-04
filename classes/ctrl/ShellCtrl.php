<?php
/**
 * 计划任务脚本控制器
 */

namespace ctrl;

use framework\util;
use utils\HttpUtil;
use utils\MyLogger;
use service\AnswerService;
use utils\Functions;

class ShellCtrl extends CtrlBase
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
		if (!defined("SHELL_MODE") || SHELL_MODE != true) {
			die('access deny!');
		}
	}

}
