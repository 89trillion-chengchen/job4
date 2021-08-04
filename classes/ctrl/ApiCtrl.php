<?php
/**
 *
 */

namespace ctrl;

use framework\util;
use view\JsonView;
use utils\HttpUtil;
use utils\Functions;
use service\AnswerService;

class ApiCtrl extends CtrlBase
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

		$ipList = explode(',', API_ALLOW_IPS);
		$realIP = SAFE_IP;
		$ipCheck = Functions::checkIP($realIP, $ipList);
		if (true === $ipCheck) {
			return true;
		}

		header("HTTP/1.1 403 Forbidden");
		error_log("[Access Deny] {$_SERVER['HTTP_HOST']} > " . $_SERVER['REQUEST_URI'] . ' [' . $realIP . ']');
		exit();
	}

}
