<?php 
namespace framework\plugins;
/**
 * Plugins管理工具，用于管理Plugins对象的工具。
 * 
 * @author erntoo
 * @package framework\plugins
 * @exemple
 *
 */
class PluginsManager
{
	private static $path;
	private static $pluginName;
	private static $parameters;
	private static $instances;
	public function __construct($name,$parameters=array(),$path = null)
	{
		self::$path = $path;
		self::$pluginName = $name;
		self::$parameters = $parameters;
	}	
	
	public function getInstance()
	{
		if (empty(self::$pluginName))
		{
			throw new \Exception("Please instance your object");
		}
		if(!self::$path)
		{
			self::$path = dirname(__FILE__);
		}
		$classFile = self::$path . DIRECTORY_SEPARATOR . self::$pluginName . DIRECTORY_SEPARATOR . ucfirst(self::$pluginName).".class.php";
		if(!file_exists($classFile))
		{
			throw new \Exception("Plugins file not be found: $classFile");
		}
		include_once $classFile;	
		$className = ucfirst(self::$pluginName); 
			
		if(!empty(self::$parameters))
		{

			$parameterString = '';
			$strObj = '';
			foreach(self::$parameters as $k => $v)
			{
				$strObj .= '\''. $v.'\',';
			}
			$parameterString = substr($strObj,0,-1);
			$str = 'new '.$className.'('.$parameterString.')';
			eval("\$obj=$str;");
			self::$instances = $obj;
		}else{
		    self::$instances =  new $className();
		}
		return self::$instances;
	}
}
