<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-1-17 下午3:43:25
 * @description: 
 * $Id: FastDFSConfiguration.php 147 2014-01-20 03:54:30Z pengcheng2 $
 */
namespace framework\data\fastdfs;

/**
 * FastDFS配置信息
 */
class FastDFSConfiguration {
    /**
     * Tracker服务器地址
     * 
     * @var string 
     */
    public $ip_addr;
    /**
     * Tracker服务器端口
     * 
     * @var int 
     */
    public $port;

    /**
     * 构造函数
     */
    public function __construct($config = null) {
        if (!empty($config) && is_array($config)) {
            if (isset($config['ip_addr'])) $this->ip_addr = $config['ip_addr'];
            if (isset($config['port'])) $this->port = $config['port'];
        }
    }
}
