<?php
namespace framework\data\mongo;

use \MongoClient;
/**
 * Mongo管理工具，用于管理Mongo对象的工具，需要Mongo扩展。
 * 
 * @author zivn 
 * @package framework\data\Mongo
 */
class MongoManager {
    /**
     * Mongo配置
     * 
     * @var <MongoConfiguration>array
     */
    private static $configs;
    /**
     * Mongo实例
     * 
     * @var \Mongo
     */
    private static $instances;

    /**
     * 添加Mongo配置
     * 
     * @param MongoConfiguration $config 
     */
    public static function addConfigration($name, MongoConfiguration $config) {
        self::$configs[$name] = $config;
    }

    /**
     * 获取Mongo实例
     * 
     * @return \Mongo
     */
    public static function getInstance($name) {
 
        if (empty(self::$instances[$name])) {
            if (empty(self::$configs[$name])) {
                return null;
            }
            $client = new \MongoClient(self::$configs[$name]->host . ":" .self::$configs[$name]->port);
            $dbname = self::$configs[$name]->dbname;
            $db = $client->$dbname;
            self::$instances[$name] = $db;
        }
        return self::$instances[$name];
    }
}
