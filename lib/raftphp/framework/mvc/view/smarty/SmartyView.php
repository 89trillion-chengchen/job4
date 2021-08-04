<?php // -*-coding:utf-8; mode:php-mode;-*-
namespace framework\mvc\view\smarty;

use framework\mvc\IView;

/**
 * 用于用Smarty展示数据
 * 
 * @author xodger@gmail.com 
 * @package framework\mvc\view\smarty
 */
class SmartyView implements IView {
    /**
     * 
     * @var SmartyConfiguration 
     */
    private static $smartyConfiguration;
    private static $smarty = null;

    public $fileName;
    public $model;

    /**
     * 构造函数
     * 
     * @param String $fileName Smarty模版文件名
     * @param  $model 用于展示的数据
     */
    public function __construct($fileName, $model = null) {
        $this->fileName = $fileName;
        $this->model = $model;
    }

    public function display() {
        header("Content-Type: text/html; charset=utf-8");

        $smarty = self::getSmarty();
        $smarty->assign($this->model);
        $smarty->display($this->fileName);
    }
    /**
     * 设置Smarty的配置信息，用于SmartyView的smarty对象获取
     * 
     * @param SmartyConfiguration $config 
     */
    public static function setSmartyConfiguration(SmartyConfiguration $config) {
        self::$smartyConfiguration = $config;
    }

    /**
     * 取得Smarty的配置信息
     * 
     * @return SmartyConfiguration 
     */
    public static function getSmartyConfiguration() {
        return self::$smartyConfiguration;
    }
    /**
     * get Smarty instance
     * 
     * @return \Smarty
     */
    private static function getSmarty() {
        if (!self::$smarty) {
            if (empty(self::$smartyConfiguration)) {
                throw new \Exception("please set smarty configuration with  Smarty::setSmartyConfiguration()");
            }else {
                $config = self::$smartyConfiguration;
            }

            require_once $config->smartyPath . DIRECTORY_SEPARATOR . 'Smarty.class.php';

            $smarty = new \Smarty();
            $smarty->cache_dir = $config->cacheDir;
            $smarty->compile_dir = $config->compileDir;
            $smarty->template_dir = $config->templateDir;
            $smarty->config_dir = $config->configDir;
            $smarty->left_delimiter = '<{';
            $smarty->right_delimiter = '}>';
            if (isset($config->configFile)) {
                $smarty->configLoad($config->configFile);
            }
            if (isset($config->errorReporting)) {
                $smarty->error_reporting = $config->errorReporting;
            }
            $smarty->escape_html = true;

            self::$smarty = $smarty;
        }

        return self::$smarty;
    }
}
