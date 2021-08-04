<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-2-26 下午2:23:43
 * @description:
 * $Id: IndexCtrl.php 1261 2014-03-28 09:49:34Z pengcheng2 $
 */

namespace ctrl;

use framework\util;

/**
 *
 */
class IndexCtrl extends CtrlBase
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

	public function main()
	{
		return '';
	}

	public function test()
    {
        die('here');
    }
}
