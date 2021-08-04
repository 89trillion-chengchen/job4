<?php
namespace framework\data\memcached;

/**
 * Memcached配置信息
 * 
 * @author zivn 
 * @package framework\data\memcached
 */
class MemcachedConfiguration {
    /**
     * Memcached服务器地址
     * 
     * @var string 
     */
    public $host;
    /**
     * Memcached服务器端口
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
        }
    }
}
