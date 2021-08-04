<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-3-11 下午4:23:04
 * @description:
 * $Id: InnerView.php 867 2014-03-11 08:31:10Z pengcheng2 $
 */

namespace view;

use framework\mvc\IView;
use utils\Functions;

class JsonView implements IView
{
	/**
	 * 数据
	 *
	 * @var mixed
	 */
	private $model;

	/**
	 * HTTP响应状态码
	 *
	 * @var int
	 */
	private $response_code;

	public function __construct($model = null, $response_code = 200, $encrypt = false)
	{
		$this->model = $model;
		$this->response_code = $response_code;
		$this->encrypt = $encrypt;

	}

	public function display()
	{
		if ($this->encrypt) {
			$encJson = Functions::encryptByKaiser(json_encode($this->model), API_ENCRYPT_INDEX);
			echo $encJson;
		}
		else {
			header("Content-Type: application/json; charset=utf-8");
			echo json_encode($this->model);
		}
	}
}
