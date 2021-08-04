<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-3-4 下午5:59:19
 * @description:
 * $Id: DataView.php 2125 2014-05-16 06:51:05Z pengcheng2 $
 */

namespace view;

use framework\mvc\IView;

class DataView implements IView
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

	public function __construct($model = null, $response_code = 200)
	{
		$this->model = $model;
		$this->response_code = $response_code;
		if (($model === false || $model === null) && $response_code == 200) {
			$this->response_code = 201;
		}
	}

	public function display()
	{
		header("Content-Type: application/json; charset=utf-8");
		if (200 == $this->response_code) {
			$etag = md5($this->model);
			if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $etag == $_SERVER['HTTP_IF_NONE_MATCH']) {
				header(' ', true, 304);
			}
			else {
				header('Etag: ' . $etag);
				echo str_replace('http:\/\/img.1mobile.com', 'http:\/\/simg.1mobile.com', $this->model);
			}
		}
		else {
			header(' ', true, $this->response_code);
		}
	}
}
