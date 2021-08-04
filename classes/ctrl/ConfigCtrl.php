<?php
/**
 * 文件控制器
 */

namespace ctrl;

use framework\util;
use utils\HttpUtil;
use utils;
use utils\Functions;
use view\JsonView;
use utils\StatLogger;

class ConfigCtrl extends CtrlBase
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

	public function tssca()
	{
		$time = [ "ts" => time() ];
		$encryptFlag = true;

		return new JsonView($time, 200, $encryptFlag);
	}

	public function init()
	{
		$token_params = Functions::getHeaders();
		$params = HttpUtil::getEncryptPostData();
		extract($params);
		$encryptFlag = true;
		$time = time();
		$chan = isset($token_params['chan']) ? $token_params['chan'] : 0; //1 gp 2 其他
		$installTime = isset($token_params['installTime']) ? $token_params['installTime'] : time();
		$installVc = isset($token_params['installVc']) ? $token_params['installVc'] : 0;
		$t = $time - $installTime;

		$local_configJson = '{
			"success": 1
		}';

		return new JsonView(json_decode($local_configJson, true), 200, $encryptFlag);
	}
}
