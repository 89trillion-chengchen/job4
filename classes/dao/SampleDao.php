<?php
/**
 *
 */

namespace dao;

use framework\data\pdo\PDOHelper;

class SampleDao extends ConstDaoBase
{

    /** @var PDOHelper */
	protected $dbHelper;

	/**
	 * 构造函数
	 *
	 * @return
	 */
	public function __construct()
	{
		$this->useDb();
	}

	public function fetchAll(){
	    $this->dbHelper->setTableName('user');
	    return $this->dbHelper->fetchAll();
    }

    public function add($entity, $fields, $onDuplicate = null){
        $this->dbHelper->setTableName('user');
        return $this->dbHelper->add($entity, $fields);
    }

    public function query($query, $params = null, $args = null)
    {

        return $this->dbHelper->query($query,$params,$args);
    }
}