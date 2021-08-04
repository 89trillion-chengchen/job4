<?php // -*-coding:utf-8; mode:php-mode;-*-
use framework\core;
initFrameworkPath();

use framework\core\Context;

require_once FRAMEWORK_PATH . DIRECTORY_SEPARATOR . "framework/core/Context.php";

Context::setExceptionHandler("framework_exception_handler");

function initFrameworkPath() {
    if (!defined('FRAMEWORK_PATH')) {
        $setupPath = __FILE__;
        $strrpos = strrpos($setupPath, DIRECTORY_SEPARATOR);
        $frameworkPath = substr($setupPath, 0, $strrpos);

        define('FRAMEWORK_PATH', $frameworkPath);
    }
}

spl_autoload_register("framework_autoload");

function framework_autoload($class) {
    $baseClasspath = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $classpath = Context::getClassesRoot() . DIRECTORY_SEPARATOR . $baseClasspath;

    if (!file_exists($classpath)) {
        $classpath = FRAMEWORK_PATH . DIRECTORY_SEPARATOR . $baseClasspath;
    }

    if (file_exists($classpath)) {
        require_once($classpath);
    }
}

function framework_exception_handler($exception) {
    $exceptionXml = "<" . "?xml version=\"1.0\" encoding=\"utf-8\" ?" . ">\n";
    $exceptionXml .= "<exception>\n";
    $exceptionXml .= "<code>" . $exception->getCode() . "</code>\n";
    $exceptionXml .= "<message>" . $exception->getMessage() . "</message>\n";
    if (defined("DEBUG_MODE") && DEBUG_MODE) {
        $exceptionXml .= "<file>" . $exception->getFile() . "</file>\n";
        $exceptionXml .= "<line>" . $exception->getLine() . "</line>\n";
        $exceptionXml .= "<trace>\n";

        $trace = $exception->getTrace();
        foreach ($trace as $traceItem) {
            $exceptionXml .= "<traceItem>";
            $exceptionXml .= "<file>" . $traceItem['file'] . "</file>\n";
            $exceptionXml .= "<line>" . $traceItem['line'] . "</line>\n";
            $exceptionXml .= "<function>" . $traceItem['function'] . "</function>\n";
            $exceptionXml .= "<class>" . $traceItem['class'] . "</class>\n";
            $exceptionXml .= "<type>" . $traceItem['type'] . "</type>\n";
            $exceptionXml .= "<args>\n";

            if (!empty($traceItem['args'])) {
                foreach ($traceItem['args'] as $argsItem) {
                    $exceptionXml .= "<argsItem>" . var_export($argsItem, 1) . "</argsItem>\n";
                }
            }
            $exceptionXml .= "</args>\n";
            $exceptionXml .= "</traceItem>";
        }
        $exceptionXml .= "</trace>\n";
    }
    $exceptionXml .= "</exception>\n";

    header("Content-Type:text/xml; charset=utf-8");
    echo $exceptionXml;
}

