<?php
/**
* @author: ChengRennt <ChengRennt@gmail.com>
* @created: 2014-3-6 上午11:40:47
* @description:
* $Id: RedisConfiguration.php 770 2014-03-06 03:48:22Z pengcheng2 $
*/
namespace framework\data\redis;

/**
 * Redis配置信息
 */
class RedisConfiguration {
    /**
     * Redis服务器地址
     */
    public $host;
    
    /**
     * Redis服务器端口
     */
    public $port;
    
    /**
    * Redis服务器密码
    */
    public $pass;
    
    /**
    * Redis服务器数据库编号
    */
    public $dbindex;
    
    /**
     * 构造函数
     */
    public function __construct($config = null) {
        if (!empty($config) && is_array($config)) {
            if (isset($config['host'])) $this->host = $config['host'];
            if (isset($config['port'])) $this->port = $config['port'];
            if (isset($config['pass'])) $this->pass = $config['pass'];
            if (isset($config['dbindex'])) $this->dbindex = $config['dbindex'];
        }
    }
}
