<?php
namespace framework\data\memcached;

use \Memcached;
/**
 * Memcached管理工具，用于管理Memcached对象的工具，需要memcached扩展。
 * 
 * @author zivn 
 * @package framework\data\memcached
 */
class MemcachedManager {
    /**
     * Memcached配置
     * 
     * @var <MemcachedConfiguration>array
     */
    private static $configs;
    /**
     * Memcached实例
     * 
     * @var \Memcached
     */
    private static $instances;

    /**
     * 添加Memcached配置
     * 
     * @param MemcachedConfiguration $config 
     */
    public static function addConfigration($name, MemcachedConfiguration $config) {
        self::$configs[$name] = $config;
    }

    /**
     * 获取Memcached实例
     * 
     * @return \Memcached
     */
    public static function getInstance($name) {
        /**
         * if (empty(self::$instance))
         * {			
         * if (empty(self::$configs))
         * {
         * return null;
         * }
         * 
         * $memcached = new \Memcached();
         * 
         * foreach (self::$configs as $config)
         * {
         * $memcached->addServer($config->host, $config->port);
         * }
         * 
         * self::$instance = $memcached;
         * }
         * 
         * 
         * return self::$instance;
         */

        if (empty(self::$instances[$name])) {
            if (empty(self::$configs[$name])) {
                return null;
            }
            if (extension_loaded('memcached')) {
                $memcached = new \Memcached();
            }elseif (extension_loaded('memcache')) {
                $memcached = new \Memcache();
            }else {
                return false;
            }
            if (is_array(self::$configs[$name])) {
                foreach (self::$configs[$name] as $config) {
                    $memcached->addServer($config->host, $config->port);
                }
            }else {
                $memcached->addServer(self::$configs[$name]->host, self::$configs[$name]->port);
            }
            self::$instances[$name] = $memcached;
        }
        return self::$instances[$name];
    }
}
