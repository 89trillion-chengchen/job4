<?php

namespace view;

use framework\mvc\IView;

class RedirectView implements IView
{
	private $url;
	private $window;

	public function __construct($url = null, $window = "window")
	{
		$this->url = $url;
		$this->window = $window;
	}

	public function display()
	{
		if ($this->window == "top") {
			echo "<script type=\"text/javascript\">\ntop.location.href = \" {$this->url}\"</script>";
		}
		else {
			header('Location: ' . $this->url);
		}
	}
}
