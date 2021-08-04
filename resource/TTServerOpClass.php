<?php

class TTServerOpClass
{
  private static $instance = array();
	private $group;
	private $tcrmgr;
	
	public function __construct($group, $conf = array())
	{
		$this->group = $group;
		$this->tcrmgr = isset($conf['tcrmgr']) ? $conf['tcrmgr'] : '/usr/bin/tcrmgr';
	}

	public function get($id, $key, &$cas_token = null)
	{
		if (false == ($mc = $this->getMc($id))) return false;
		$data = $mc->get($key, null, $cas_token);
		if ($data) return json_decode($data, true);
		return $data;
	}

	public function set($id, $key, $data)
	{
		if (false == ($mc = $this->getMc($id))) return false;
		$ret = $mc->set($key, json_encode($data));
		return $ret;
	}

	public function cas($id, $key, $data, $cas)
	{
		if (false == ($mc = $this->getMc($id))) return false;
		$ret = $mc->cas($cas, $key, json_encode($data));
		return $ret;
	}

	public function sync()
	{
		$cfgname = 'ttserver_cfg_'.$this->group;
		if (!isset($GLOBALS[$cfgname])) return false;
		if (!isset($GLOBALS[$cfgname]['hash_db'])) return false;
		foreach($GLOBALS[$cfgname]['hash_db'] as $one) {
			system($this->tcrmgr.' sync -port '.$one['port'].' '.$one['host']);
		}
		return true;
	}
	
	private function getConf($id)
	{
		$cfgname = 'ttserver_cfg_'.$this->group;
		if (!isset($GLOBALS[$cfgname])) return false;
		$hashmax = $GLOBALS[$cfgname]['hash_index']['max'];
		$maxpower = isset($GLOBALS[$cfgname]['hash_index']['maxpower']) ? $GLOBALS[$cfgname]['hash_index']['maxpower'] : false;
		$hashiter = $GLOBALS[$cfgname]['hash_index']['iter'];
		$iterpower = isset($GLOBALS[$cfgname]['hash_index']['iterpower']) ? $GLOBALS[$cfgname]['hash_index']['iterpower'] : false;

		$t = sprintf('%u', crc32($id) >> 16 & 0xffff);
		$hashIndex = ($maxpower!==false) ? ($t & ($hashmax-1)) : ($t % $hashmax);

		$index = ($iterpower!==false) ? ($hashIndex >> $iterpower) : (int)($hashIndex / $hashiter);
		if (!isset($GLOBALS[$cfgname]['hash_index']['index'][$index])) return false;
		$index2 = $GLOBALS[$cfgname]['hash_index']['index'][$index];
		if (!isset($GLOBALS[$cfgname]['hash_db'][$index2])) return false;
		return $GLOBALS[$cfgname]['hash_db'][$index2];
	}

	private function getMc($id)
	{
		$conf = $this->getConf($id);
		if (!$conf) return false;
		$host = $conf['host'].'_'.$conf['port'];
		if (isset(self::$instance[$host])) {
			if (!self::$instance[$host]['enable']) return false;
			if (is_object(self::$instance[$host]['mc'])) return self::$instance[$host]['mc'];
		}

		self::$instance[$host]['mc'] = new Memcached();
		self::$instance[$host]['mc']->setOption(Memcached::OPT_COMPRESSION, false);
		self::$instance[$host]['enable'] = false;
		if ($conf['enable']) {
			if (@self::$instance[$host]['mc']->addServer($conf['host'], $conf['port'])) self::$instance[$host]['enable'] = true;
		}

		if (!self::$instance[$host]['enable']) return false;
		return self::$instance[$host]['mc'];
	}
}
