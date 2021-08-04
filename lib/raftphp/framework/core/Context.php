<?php // // -*-coding:utf-8; mode:php-mode;-*-
namespace framework\core;

use framework\util;
use framework\config\SmartyConfiguration;

/**
 * 框架的上下文，司整个框架的配置信息及初始化信息
 * 
 * @author xodger@gmail.com 
 * @package framework\core
 */
class Context {
    private static $rootPath;
    private static $classesRoot;
    private static $ctrlNamespace = "ctrl";
    private static $infoPath;
    private static $exceptionHandler;
    public static $currentTime;
    public static $currentDate;
    /**
     * 取得当前时间的时间戳，为保证一次PHP请求的执行周期内时间的统一，可调用该方法来代替time()函数。
     * 
     * @return int 
     */
    function getCurrentTime() {
        if (empty(self::$currentTime)) {
            self::$currentTime = time();
        }

        return self::$currentTime;
    }

    /**
     * 取得当前时间的字符串，和getCurrentTime()方法类似，但取得的值为'Y-m-d H:i:s'格式的字符串形式
     * 
     * @return string 
     * @see getCurrentTime
     */
    function getCurrentDate() {
        if (empty(self::$currentDate)) {
            self::$currentDate = date('Y-m-d H:i:s', self::getCurrentTime());
        }

        return self::$currentDate;
    }

    /**
     * 设置当前的时间戳
     * 
     * @param int $currentTime 当前时间的时间戳
     * @see getCurrentTime
     */
    function setCurrentTime($currentTime) {
        self::$currentTime = $currentTime;
    }

    /**
     * 设置当前时间的字符串形式
     * 
     * @param String $currentDate 当前时间的字符串形式
     * @see getCurrentDate
     */
    function setCurrentDate($currentDate) {
        self::$currentDate = $currentDate;
    }

    /**
     * 取得项目的配置信息路径
     * 
     * @return String 
     * @see setInfoPath
     */
    public static function getInfoPath() {
        if (empty(self::$infoPath)) {
            self::$infoPath = self::getRootPath() . DIRECTORY_SEPARATOR . "inf";
        }

        return Context::$infoPath;
    }

    /**
     * 设置项目的配置信息路径，所有的配置信息都可以存在在该目录下，框架会自动将该目录及其子目录包含在运行环境中。
     * 
     * @param String $infoPath 项目的配置信息路径
     */
    public static function setInfoPath($infoPath) {
        self::$infoPath = $infoPath;
    }

    /**
     * 取得项目的根路径
     * 
     * @return String 
     * @see setRootPath
     */
    public static function getRootPath() {
        if (empty(self::$rootPath)) {
            throw new \Exception("please set root path with Context::setRootPath");
        }

        return self::$rootPath;
    }

    /**
     * 取得项目的类定义路径
     * 
     * @return String 
     * @see setClassesRoot
     */
    public static function getClassesRoot() {
        if (empty(self::$classesRoot)) {
            self::$classesRoot = self::getRootPath() . DIRECTORY_SEPARATOR . "classes";
        }

        return self::$classesRoot;
    }

    /**
     * 取得Controller的名称空间
     * 
     * @return String 
     * @see setCtrlNamespace
     */
    public static function getCtrlNamespace() {
        return self::$ctrlNamespace;
    }

    /**
     * 设置项目的根路径
     * 
     * @param String $rootPath 
     * @return String 
     */
    public static function setRootPath($rootPath) {
        self::$rootPath = $rootPath;
    }

    /**
     * 设置异常处理函数
     * 
     * @param callback $exceptionHandler 
     */
    public static function setExceptionHandler($exceptionHandler) {
        self::$exceptionHandler = $exceptionHandler;
    }
    /**
     * 设置项目的类定义路径，该路径用于存放所有的PHP类的定义。
     * 
     * @param  $classesRoot 
     */
    public static function setClassesRoot($classesRoot) {
        self::$classesRoot = $classesRoot;
    }

    /**
     * 设置Controller的名称空间，该设定主要用于RequestDispatcher对控制器类的定位
     * 
     * @param String $ctrlNamespace 
     */
    public static function setCtrlNamespace($ctrlNamespace) {
        self::$ctrlNamespace = $ctrlNamespace;
    }
    /**
     * 初始化上下文
     */
    public static function initialize() {
        if (!empty(self::$exceptionHandler) && is_callable(self::$exceptionHandler)) {
            set_exception_handler(self::$exceptionHandler);
        }

        $infoPath = self::getInfoPath();

        $infoFiles = util\FileUtil::treeDirectory($infoPath, "/\\.php$/");
        for($i = 0, $count = count($infoFiles); $i < $count; $i ++) {
            require_once $infoFiles[$i];
        }
    }
}
