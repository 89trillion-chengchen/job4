<?php // -*-coding:utf-8; mode:php-mode;-*-
namespace framework\mvc\dispatcher;

use framework\mvc\IView;

use framework\mvc\view\StringViewFactory;

use framework\core\Context;
use framework\mvc\IRequestDispatcher;
use framework\mvc\IController;
/**
 * IRequestDispatcher的抽象实现，它实现了dispatch方法，并且定义了getCtrlClassName和getCtrlMethodName两个抽象方法，其子类只需实现这两个方法即可。
 * 
 * @author xodger@gmail.com 
 * @package framework\mvc\dispatcher
 */
abstract class RequestDispatcherBase implements IRequestDispatcher {
    /**
     * Enter description here ...
     * 
     * @var framework \mvc\IViewFactory
     */
    public $viewFactory;

    public function __construct() {
        $this->viewFactory = new StringViewFactory();
    }

    public function dispatch() {
        $exception = null;
        try {
            //分割请求
            $ctrlClass = Context::getCtrlNamespace() . "\\" . $this->getCtrlClassName();
            $ctrlMethod = $this->getCtrlMethodName();
            if (!class_exists($ctrlClass)) {
                // throw new \Exception("Class not found: $ctrlClass");
                header("HTTP/1.1 404 Not Found");
                header("Status: 404 Not Found");
                exit();
            }
            $ctrl = new $ctrlClass();

            $filtered = false;

            if ($ctrl instanceof IController) {
                $ctrl->setDispatcher($this);
                $filtered = !$ctrl->beforeFilter();
            }

            if (!$filtered) {
                if (!method_exists($ctrl, $ctrlMethod)) {
                    // throw new \Exception("Method not found: $ctrlClass::$ctrlMethod");
                    header("HTTP/1.1 404 Not Found");
                    header("Status: 404 Not Found");
                    exit();
                }
                $result = $ctrl->$ctrlMethod();
                if ($result instanceof IView) {
                    $result->display();
                }else if ($this->viewFactory) {
                    $view = $this->viewFactory->createView($result);
                    $view->display();
                }
            }

            if ($ctrl instanceof IController) {
                $ctrl->afterFilter();
            }
        }
        catch(\Exception $e) {
            $exception = $e;
        }
        if ($exception != null) {
            throw $exception;
        }
    }

    /**
     * 获取控制器类名
     * 
     * @return String 
     */
    abstract public function getCtrlClassName();

    /**
     * 获取控制器方法名
     * 
     * @return String 
     */
    abstract public function getCtrlMethodName();
}
