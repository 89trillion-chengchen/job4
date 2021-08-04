<?php // -*-coding:utf-8; mode:php-mode;-*-
namespace framework\mvc\dispatcher;

/**
 * HTTP请求转发器，IRequestDispacher的一个实现，用于分发HTTP的请求。
当GET或者POST信息包含类似于act=CtrlName.methodName时，将执行CtrlName类的methodName方法。
 * 
 * @author xodger@gmail.com 
 * @package framework\mvc\dispatcher
 */
class HTTPRequestDispatcher extends RequestDispatcherBase {
    private $ctrlClassName;

    private $ctrlMothodName;

    public function __construct() {
        parent::__construct();

        if (array_key_exists("act", $_GET)) {
            if (preg_match ('/^([a-z0-9_]+)\.([a-z0-9_]+)$/i', $_GET['act'], $matches)) {
                $this->ctrlClassName = ucfirst($matches[1]); //.'Ctrl';
                $this->ctrlMethodName = $matches[2];
            }else if (preg_match ('/^([a-z0-9_]+)$/i', $_GET['act'], $matches)) {
                $this->ctrlClassName = ucfirst($matches[1]); //.'Ctrl';
                $this->ctrlMethodName = 'main';
            }else {
                header("HTTP/1.1 404 Not Found");
                header("Status: 404 Not Found");
                exit();
            }
        }else {
            $this->ctrlClassName = "Index"; //.'Ctrl';
            $this->ctrlMethodName = "main";
        }
    }

    public function getCtrlClassName() {
        return $this->ctrlClassName;
    }

    public function getCtrlMethodName() {
        return $this->ctrlMethodName;
    }
}
