<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-3-4 下午6:19:50
 * @description:
 * $Id: RawView.php 770 2014-03-06 03:48:22Z pengcheng2 $
 */

namespace view;

use framework\mvc\IView;

class RawView implements IView
{
	/**
	 * 数据
	 *
	 * @var mixed
	 */
	private $model;

	public function __construct($model = null)
	{
		$this->model = $model;
	}

	public function display()
	{
		echo $this->model;
	}
}
