<?php // -*-coding:utf-8; mode:php-mode;-*-
namespace framework\mvc\view;

use framework\mvc\IView;

/**
 * 字符串视图，向用户输出字符串
 * 
 * @author xodger@gmail.com 
 * @package framework\mvc\view
 */
class StringView implements IView {
    private $string;

    public function __construct($string) {
        $this->string = $string;
    }

    public function display() {
        echo $this->string;
    }
}
