<?php
namespace framework\data\pdo;

use \PDO;

/**
 * PDO管理工具，用于管理PDO对象的工具，需要PDO扩展。
 * 
 * @author zivn 
 * @package framework\data\pdo
 */
class PDOManager {
    /**
     * PDO配置
     * 
     * @var array <PDOConfiguration>
     */
    private static $configs;
    /**
     * PDO实例
     * 
     * @var array <\PDO>
     */
    private static $instances;

    /**
     * 添加PDO配置
     * 
     * @param string $name 
     * @param PDOConfiguration $config 
     */
    public static function addConfigration($name, PDOConfiguration $config) {
        self::$configs[$name] = $config;
    }

    /**
     * 获取PDO实例
     * 
     * @param string $name 
     * @return \PDO
     */
    public static function getInstance($name) {
        if (empty(self::$instances[$name])) {
            if (empty(self::$configs[$name])) {
                return null;
            }

            $config = self::$configs[$name];
            self::$instances[$name] = new \PDO($config->getDSN(), $config->user, $config->pass, array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '{$config->charset}';",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ));
            /*
            $timeStart = microtime(true);
            try {
                self::$instances[$name] = new \PDO($config->getDSN(), $config->user, $config->pass, array(
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '{$config->charset}';",
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                        ));
            } catch (PDOException $e) {
                $time = number_format(microtime(true) - $timeStart, 8, '.', '');
                @error_log(date("Y-m-d H:i:s", time()+13*3600) . "\t" . $time . "\t" . $config->host . "\t" . $config->dbname . "\t" . $e->getMessage() . "\n", 3, '/tmp/pdoConnect' . date("Ymd", time()+13*3600) . '.log');
                exit;
            }
            $time = number_format(microtime(true) - $timeStart, 8, '.', '');
            @error_log(date("Y-m-d H:i:s", time()+13*3600) . "\t" . $time . "\t" . $config->host . "\t" . $config->dbname . "\n", 3, '/tmp/pdoConnect' . date("Ymd", time()+13*3600) . '.log');
        	*/
        }

        return self::$instances[$name];
    }
    
    /**
    * 销毁PDO实例
    *
    * @param string $name
    * @return \PDO
    */
    public static function delInstance($name) {
        if ( isset(self::$instances[$name]) && !empty(self::$instances[$name]) ) {
            self::$instances[$name] = null;
        }
    }
}
