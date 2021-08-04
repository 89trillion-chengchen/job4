<?php
namespace framework\data\redis;

use \Redis;
/**
 * Redis管理工具，用于管理Redis对象的工具，需要redis扩展。
 * 
 * @author zivn 
 * @package framework\data\redis
 */
class RedisManager {
    /**
     * Redis配置
     * 
     * @var <RedisConfiguration>array
     */
    private static $configs;
    /**
     * Redis实例
     * 
     * @var \Redis
     */
    private static $instances;

    /**
     * 添加Redis配置
     * 
     * @param RedisConfiguration $config 
     */
    public static function addConfigration($name, RedisConfiguration $config) {
        self::$configs[$name] = $config;
    }

    /**
     * 获取Redis实例
     * 
     * @return \Redis
     */
    public static function getInstance($name) {
        if (empty(self::$instances[$name])) {
            if (empty(self::$configs[$name])) {
                return null;
            }
            $redis = new \Redis();
            $result = $redis->connect(self::$configs[$name]->host, self::$configs[$name]->port, 3); // 3 sec timeout.
            if ($result) {
                if (!empty(self::$configs[$name]->pass)) {
                    $redis->auth(self::$configs[$name]->pass);
                }
                if (!empty(self::$configs[$name]->dbindex)) {
                    $redis->select(self::$configs[$name]->dbindex);
                }
                self::$instances[$name] = $redis;
            }else {
                self::$instances[$name] = false;
            }
        }
        return self::$instances[$name];
    }
}
