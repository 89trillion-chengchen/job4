<?php // -*-coding:utf-8; mode:php-mode;-*-
namespace framework\mvc\view;

use framework\mvc\IView;

/**
 * 用于生成JSON数据
 * 
 * @author xodger@gmail.com 
 * @package framework\mvc\view
 */
class JSONView implements IView {
    private $model;

    /**
     * 数据模型，即需要展示的数据
     * 
     * @param  $model 
     */
    public function __construct($model) {
        $this->model = $model;
    }

    function display() {
        // header("Content-Type:text/plain; charset=utf-8");
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($this->model);
    }
}
