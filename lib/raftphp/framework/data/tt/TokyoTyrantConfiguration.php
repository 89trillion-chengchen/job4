<?php
namespace framework\data\tt;

/**
 * TokyoTyrant配置信息
 * 
 * @author zivn 
 * @package framework\data\tt
 */
class TokyoTyrantConfiguration {
    /**
     * TokyoTyrant服务器地址
     * 
     * @var string 
     */
    public $host;
    /**
     * TokyoTyrant服务器端口
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
    public function __construct($host, $port) {
        $this->host = $host;
        $this->port = $port;
    }
}
