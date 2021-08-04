<?php
namespace framework\data\mongo;

/**
 * Mongo配置信息
 * 
 * @author zivn 
 * @package framework\data\Mongo
 */
class MongoConfiguration {
    /**
     * Mongo服务器地址
     * 
     * @var string 
     */
    public $host;
    /**
     * Mongo服务器端口
     * 
     * @var int 
     */
    public $port;

    /**
     * 构造函数
     * 
     * @param string $host 
     * @param int $port 
     */
    public function __construct($config = null) {
        if (!empty($config) && is_array($config)) {
            if (isset($config['host'])) $this->host = $config['host'];
            if (isset($config['port'])) $this->port = $config['port'];
            if (isset($config['pass'])) $this->pass = $config['pass'];
            if (isset($config['dbname'])) $this->dbname = $config['dbname'];
        }
    }
}
