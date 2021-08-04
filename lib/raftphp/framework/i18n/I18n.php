<?php
namespace framework\i18n;

use framework\core\Context;

/**
 * 国际化支持，需要gettext扩展
 * 
 * @author zivn 
 * @package framework\i18n
 * @global String DEFAULT_CHARSET
 * @global String DEFAULT_LOCALE
 */
class I18n {
    /**
     * 语言文件目录
     * 
     * @var string 
     */
    const LOCALE_DIR = 'locale';

    /**
     * 单实例对象序列
     * 
     * @var array 
     */
    private static $instances = array();
    /**
     * 当前语言
     * 
     * @var string 
     */
    private static $locale;
    /**
     * 当前域
     * 
     * @var string 
     */
    private $domain;

    /**
     * 格式化索引
     * 
     * @param string $key 
     * @return string 
     */
    public static function quote($key) {
        return '/\{' . $key . '\}/';
    }

    /**
     * 构造函数
     * 
     * @param string $domain 
     */
    private function __construct($domain) {
        $this->domain = $domain;

        if (!defined("DEFAULT_LOCALE")) {
            throw new \Exception("the global constant  DEFAULT_LOCALE is required");
        }

        if (!defined("DEFAULT_CHARSET")) {
            throw new \Exception("the global constant DEFAULT_CHARSET is required");
        }

        bind_textdomain_codeset($this->domain, DEFAULT_CHARSET);
        bindtextdomain($this->domain, Context::getRootPath() . DIRECTORY_SEPARATOR . self::LOCALE_DIR);
    }

    /**
     * 取得一个单实例I18n对象
     * 
     * @param string $domain 
     * @return I18n 
     */
    public static function getInstance($domain) {
        if (empty(self::$locale)) {
            self::$locale = DEFAULT_LOCALE;
            setlocale(LC_ALL, DEFAULT_LOCALE);
        }

        if (!array_key_exists($domain, self::$instances)) {
            self::$instances[$domain] = new I18n($domain);
        }

        return self::$instances[$domain];
    }

    /**
     * 获取格式化后的文本
     * 
     * @param string $key 
     * @param array $params 
     * @return string 
     */
    public function _($key, $params = null) {
        $text = dgettext($this->domain, $key);

        if (empty($params)) {
            return $text;
        }else {
            return preg_replace(array_map('framework\i18n\I18n::quote', array_keys($params)), array_values($params), $text);
        }
    }

    /**
     * 获取本地化后的文本
     * 
     * @param string $domain 
     * @param string $key 
     * @param array $params 
     * @return string 
     */
    public static function __($domain, $key, $params = null) {
        $i18n = self::getInstance($domain);
        return $i18n->_($key, $params);
    }
}

?>
