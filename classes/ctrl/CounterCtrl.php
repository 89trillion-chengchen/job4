<?php
/**
 *
 */

namespace ctrl;

use framework\util;
use framework\mvc\view\smarty;
use utils\HttpUtil;
use service\AnswerService;
use view\JsonView;
use utils\Functions;

class CounterCtrl extends CtrlBase
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

	public function upload()
	{
	    //获取get或post请求数据
		$params = HttpUtil::getQueryData();

		$cntSrv = util\Singleton::get("service\\CounterService");
		//校验数据
		list($checkResult, $checkNotice) = $cntSrv->checkUploadParams($params);
		if (true !== $checkResult) {
			$rspArr = AnswerService::makeResponseArray($checkNotice);

			return new JsonView($rspArr);
		}

		extract($params);


		//调用方法进行逻辑操作
		$cnt = $cntSrv->incrCounter($id, $type);

		$rspArr = AnswerService::makeResponseArray('ok');

		return new JsonView($rspArr);

	}
}
