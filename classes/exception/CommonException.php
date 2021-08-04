<?php
/**
 * 
 * @file CommonException.php
 * @author ChengRennt <ChengRennt@gmail.com> 
 * @date 2013-4-9
 * @description 
 */
namespace exception;

use \Exception;
use framework\util\Formater;
use utils\Functions;

/**
 * 通用异常基类
 * 
 * @package exception
 */
class CommonException extends \Exception {
    /**
     * 执行过程中产生的所有异常
     * 
     * @var Array 
     */
    private static $exceptions = array();

    /**
     * 构造函数
     * 
     * @param string $key 
     */
    public function __construct($message) {
        Functions::errlog('exception', $message);
        exit;
    }

    /**
     * 获取执行过程中的异常发生次数
     * 
     * @return int 
     */
    public static function getExceptionNum() {
        return \count(self::$exceptions);
    }

    /**
     * 获取执行过程中的发生的最后一次异常
     * 
     * @return GameException 
     */
    public static function getLastException() {
        return empty(self::$exceptions) ? null : \end(self::$exceptions);
    }

    /**
     * 异常处理回调函数
     * 
     * @param  $ \Exception $exception
     */
    public static function exceptionHandler(\Exception $exception) {
        $queryString = '';
        if(isset($_GET) && !empty($_GET)) {
            $queryString .= "\tGET: " . http_build_query($_GET);
        }
        if(isset($_POST) && !empty($_POST)) {
            $queryString .= "\tPOST: " . http_build_query($_POST);
        }
        if(isset($_SERVER['argv']) && !empty($_SERVER['argv'])) {
            $queryString .= "\tARGV: " . http_build_query($_SERVER['argv']);
        }
        if(defined("DEBUG_MODE") && DEBUG_MODE) {
            echo $exception . $queryString;
        }
        error_log("{$exception}{$queryString}");
        exit;
    }
}
