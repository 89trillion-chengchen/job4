
/*
 * 新增外部插件
 * 假设外部插件类文件为sample.php, 类名为Sample
 * 1. 创建插件目录 SamplePlugin （名称可任意起，尽量和引入类名称相关联）
 * 2. 创建插件加载类文件 {插件目录名}.class.php
 * 3. 创建插件加载类, 类名为 插件目录名，需要继承外部插件类
 *
 *
 * 使用示例
 * 1. use framework\plugins;
 * 2. $obj = new plugins\PluginsManager('SamplePlugin');
 * 3. $sampleClass = $obj->getInstance();
 * 
 * $sampleClass 即为Sample类的实例
 * 如果类有构造参数可以通过第二个参数传递
 * $obj = new plugins\PluginsManager('SamplePlugin',array('param_1','param2'));
 */