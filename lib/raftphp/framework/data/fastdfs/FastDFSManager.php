<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-1-17 下午3:45:38
 * @description: 
 * $Id: FastDFSManager.php 3239 2014-07-22 10:58:09Z pengcheng2 $
 */
namespace framework\data\fastdfs;

/**
 * FastDFS管理工具
 */
class FastDFSManager {
    /**
     * 配置
     */
    public static $configs;
    /**
     * 实例
     */
    private static $instances;

    /**
     * 添加配置
     * 
     * @param RedisConfiguration $config 
     */
    public static function addConfigration($name, FastDFSConfiguration $config) {
        self::$configs[$name] = $config;
    }

    /**
     * 获取实例
     */
    public static function getInstance($name) {
        if (empty(self::$instances[$name])) {
            $fdfs = new \FastDFS();
            if (is_object($fdfs)) {
                self::$instances[$name] = $fdfs;
            }else {
                self::$instances[$name] = false;
            }
        }
        return self::$instances[$name];
    }
}
