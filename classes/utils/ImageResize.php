<?php

namespace utils;

class ImageResize
{
	//图片类型
	protected $type;
	//实际宽度
	protected $width;
	//实际高度
	protected $height;
	//改变后的宽度
	public $resizeWidth;
	//改变后的高度
	public $resizeHeight;
	//是否裁图
	protected $cut;
	//源图象
	protected $srcimg;
	//目标图象地址
	protected $dstimg;
	//临时创建的图象
	protected $im;
	//错误信息
	public $errors;
	//图片信息
	protected $imageInfo;

	public function resize($srcpath, $width, $height, $dstpath = null)
	{
		if (!$this->_checkenv()) {
			return false;
		}

		//初始化图象
		if (!$this->initImage($srcpath, $width, $height, $dstpath)) {
			return false;
		}

		$this->newImage();

		ImageDestroy($this->im);

		return true;
	}

	/**
	 * 检测当前环境是否支持GD
	 */
	protected function _checkenv()
	{
		if (!function_exists('gd_info')) {
			$this->errors[] = "gd extension not found!";

			return false;
		}

		return true;
	}

	//初始化图象
	protected function initImage($srcpath, $width, $height, $dstpath)
	{
		$this->srcimg = $srcpath;
		$this->dstimg = $dstpath;

		if (!$this->getImageInfo($this->srcimg)) {
			return false;
		}

		$this->width = $this->imageInfo[0];
		$this->height = $this->imageInfo[1];
		$this->ratio = $this->width / $this->height;

		if ($this->ratio >= 1) {
			//图片宽度比较大，按宽边缩放到指定大小
			$this->resizeWidth = $width;
			$this->resizeHeight = ceil($width / $this->ratio);
		}
		else {
			//图片高度比较大，按高边绽放到指定大小
			$this->resizeWidth = ceil($height * $this->ratio);
			$this->resizeHeight = $height;
		}

		$code = $this->imageInfo[2];
		$result = true;

		switch ($code) {
			case 1:
				$this->im = imagecreatefromgif($this->srcimg);
				$this->type = "gif";
				break;
			case 2:
				$this->im = imagecreatefromjpeg($this->srcimg);
				$this->type = "jpeg";
				break;
			case 3:
				$this->im = imagecreatefrompng($this->srcimg);
				$this->type = "png";
				break;
			default :
				$result = false;
				$this->errors[] = "file not image!";
				break;
		}

		return $result;
	}

	protected function imgout($newimg)
	{
		if ($this->type == "jpg" || $this->type == 'jpeg') {
			@imagejpeg($newimg, $this->dstimg, 60);
		}
		if ($this->type == "gif") {
			@imagegif($newimg, $this->dstimg);
		}
		if ($this->type == "png") {
			@imagepng($newimg, $this->dstimg);
		}

	}

	protected function newImage()
	{
		if ($this->type == "png") {
			//add 20140610 white background pic will black
			imagesavealpha($this->im, true);//这里很重要
			//add 20140610 white background pic will black
		}
		$newimg = imagecreatetruecolor($this->resizeWidth, $this->resizeHeight);
		if ($this->type == "png") {
			//add 20140610 white background pic will black
			imagealphablending($newimg, false);//这里很重要,意思是不合并颜色,直接用$this->im图像颜色替换,包括透明;
			imagesavealpha($newimg, true);//这里很重要,意思是不要丢了$newimg图像的透明色;
			//add 20140610 white background pic will black
		}
		imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, $this->resizeWidth, $this->resizeHeight, $this->width, $this->height);
		$this->imgout($newimg);
	}

	protected function getImageInfo($filepath)
	{
		$this->imageInfo = getimagesize($filepath);
		if (!$this->imageInfo) {
			$this->errors[] = "local file error!";

			return false;
		}

		return true;
	}
}