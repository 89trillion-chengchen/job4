<?php

namespace framework\mvc\view\smarty;
/**
 * Smarty配置信息
 * 
 * @author xodger@gmail.com 
 * @package framework\mvc\view\smarty
 */
class SmartyConfiguration {
    /**
     * Smarty框架的路径
     * 
     * @var String 
     */
    public $smartyPath;
    /**
     * Smarty缓存目录
     * 
     * @var String 
     */
    public $cacheDir;
    /**
     * Smarty编译目录
     * 
     * @var String 
     */
    public $compileDir;
    /**
     * Smarty模板目录
     * 
     * @var String 
     */
    public $templateDir;
    /**
     * Smarty配置目录
     * 
     * @var String 
     */
    public $configDir;
    /**
     * Smarty错误报告级别
     * 
     * @var String 
     */
    public $errorReporting;

    /**
     * 构造函数
     * 
     * @param string $smartyPath 
     * @param string $cacheDir 
     * @param string $compileDir 
     * @param string $templateDir 
     * @param string $configDir 
     */
    public function __construct($config = null) {
        if (!empty($config) && is_array($config)) {
            if (isset($config['smartyPath'])) $this->smartyPath = $config['smartyPath'];
            if (isset($config['cacheDir'])) $this->cacheDir = $config['cacheDir'];
            if (isset($config['compileDir'])) $this->compileDir = $config['compileDir'];
            if (isset($config['templateDir'])) $this->templateDir = $config['templateDir'];
            if (isset($config['configDir'])) $this->configDir = $config['configDir'];
            if (isset($config['configFile'])) $this->configFile = $config['configFile'];
            if (isset($config['errorReporting'])) $this->errorReporting = $config['errorReporting'];
        }
    }
}

?>