<?php

namespace framework\data\pdo;

use \PDO;

/**
 * PDO数据处理类
 *
 * @author pzd
 * @package framework\data\pdo
 */
class PDOHelper
{
	/**
	 * pdo对象
	 *
	 * @var \PDO
	 */
	private $pdo;
	/**
	 * 数据库名
	 *
	 * @var string
	 */
	private $dbName;
	/**
	 * 数据表名
	 *
	 * @var string
	 */
	private $tableName;
	/**
	 * 类名
	 *
	 * @var string
	 */
	private $className;

	/**
	 * 主键名称
	 *
	 * @var string
	 */
	private $pk;

	/**
	 * 构造函数
	 *
	 * @param string $className
	 * @param string $dbName
	 */
	public function __construct($className, $dbName = null)
	{
		$this->className = $className;
		if (!empty($dbName)) {
			$this->dbName = $dbName;
		}
	}

	/**
	 * 取得库名
	 *
	 * @return String
	 */
	public function getDBName()
	{
		return $this->dbName;
	}

	/**
	 * 设置库名
	 *
	 * @param String $dbName
	 */
	public function setDBName($dbName)
	{
		$this->dbName = $dbName;
	}

	/**
	 * 取得表名
	 *
	 * @return String
	 */
	private function getTableName()
	{
		if (empty($this->tableName)) {
			$classRef = new \ReflectionClass($this->className);
			$this->tableName = $classRef->getConstant('TABLE_NAME');
		}

		return $this->tableName;
	}

	/**
	 * 设置表名
	 *
	 * @return String
	 */
	public function setTableName($tableName)
	{
		$this->tableName = $tableName;
	}

	/**
	 * 取得主键名
	 *
	 * @return array |String
	 */
	private function getPK()
	{
		if (empty($this->pk)) {
			$classRef = new \ReflectionClass($this->className);
			$this->pk = $classRef->getConstant('PK');

			if (empty($this->pk)) {
				$this->pk = "id";
			}
		}

		return $this->pk;
	}

	/**
	 * 取得类名
	 *
	 * @return String
	 */
	public function getClassName()
	{
		return $this->className;
	}

	/**
	 * 设置类名
	 *
	 * @return String
	 */
	public function setClassName($className)
	{
		$this->className = $className;
		$classRef = new \ReflectionClass($this->className);
		$this->tableName = $classRef->getConstant('TABLE_NAME');
	}

	/**
	 * 取得查询表名
	 *
	 * @return String
	 */
	function getLibName()
	{
		$tableName = $this->getTableName();
		if (empty($tableName)) {
			throw new \Exception("invalid table name, please check entity's const 'TABLE_NAME'");
		}

		$libName = "`{$this->getTableName()}`";

		$dbName = $this->getDBName();
		if (!empty($dbName)) {
			$libName = "`{$dbName}`.{$libName}";
		}

		return $libName;
	}

	/**
	 * 取得PDO对象
	 *
	 * @return \PDO
	 */
	function getPdo()
	{
		return $this->pdo;
	}

	/**
	 * 设置PDO对象
	 *
	 * @param  $ \PDO $pdo
	 */
	function setPdo($pdo)
	{
		$this->pdo = $pdo;
	}

	/**
	 * 添加一个对象到数据库
	 *
	 * @param Object $entity
	 * @param array $fields
	 * @param string $onDuplicate
	 *
	 * @return int
	 */
	public function add($entity, $fields, $onDuplicate = null)
	{
		$strFields = '`' . implode('`,`', $fields) . '`';
		$strValues = ':' . implode(', :', $fields);

		$query = "INSERT INTO {$this->getLibName()} ({$strFields}) VALUES ({$strValues})";

		if (!empty($onDuplicate)) {
			$query .= ' ON DUPLICATE KEY UPDATE ' . $onDuplicate;
		}

		$timeStart = microtime(true);
		$statement = $this->pdo->prepare($query);
		$params = [];

		foreach ($fields as $field) {
			$params[$field] = $entity->$field;
		}

		$result = $statement->execute($params);

		$lastInsertId = $this->pdo->lastInsertId();

		return $lastInsertId ? $lastInsertId : $result;
	}

	/**
	 * 扩展插入
	 *
	 * @param array $entitys
	 * @param array $fields
	 *
	 * @return bool
	 */
	public function addMulti($entitys, $fields)
	{
		$items = [];
		$params = [];

		foreach ($entitys as $index => $entity) {
			$items[] = '(:' . implode($index . ', :', $fields) . $index . ')';

			foreach ($fields as $field) {
				$params[$field . $index] = $entity->$field;
			}
		}

		$query = "INSERT INTO {$this->getLibName()} (`" . implode('`,`', $fields) . "`) VALUES " . implode(',', $items);
		$statement = $this->pdo->prepare($query);

		return $statement->execute($params);
	}

	/**
	 * REPLACE模式添加一个对象到数据库
	 *
	 * @param Object $entity
	 * @param array $fields
	 *
	 * @return int
	 */
	public function replace($entity, $fields)
	{
		$strFields = '`' . implode('`,`', $fields) . '`';
		$strValues = ':' . implode(', :', $fields);

		$query = "REPLACE INTO {$this->getLibName()} ({$strFields}) VALUES ({$strValues})";
		$timeStart = microtime(true);
		$statement = $this->pdo->prepare($query);
		$params = [];

		foreach ($fields as $field) {
			$params[$field] = $entity->$field;
		}

		$result = $statement->execute($params);
		$lastInsertId = $this->pdo->lastInsertId();

		return $lastInsertId ? $lastInsertId : $result;
	}

	/**
	 * 更新所有符合条件的对象
	 *
	 * @param array $fields
	 * @param array $params
	 * @param string $where
	 * @param bool $change
	 *
	 * @return bool
	 */
	public function update($fields, $params, $where, $change = false)
	{
		if ($change) {
			$updateFields = array_map(__CLASS__ . '::changeFieldMap', $fields);
		}
		else {
			$updateFields = array_map(__CLASS__ . '::updateFieldMap', $fields);
		}

		$strUpdateFields = implode(',', $updateFields);
		$query = "UPDATE {$this->getLibName()} SET {$strUpdateFields} WHERE {$where}";
		$timeStart = microtime(true);
		$statement = $this->pdo->prepare($query);
		$result = $statement->execute($params);

		return $result;
	}

	/**
	 * 取得所有符合条件的对象
	 *
	 * @param string $where
	 * @param array $params
	 * @param string $fields
	 * @param string $orderBy
	 * @param string $limit
	 *
	 * @return array
	 */
	public function fetchAll($where = '1', $params = null, $fields = '*', $orderBy = null, $limit = null)
	{
		$query = "SELECT {$fields} FROM {$this->getLibName()} WHERE {$where}";

		if ($orderBy) {
			$query .= " order by {$orderBy}";
		}

		if ($limit) {
			$query .= " limit {$limit}";
		}

		$timeStart = microtime(true);
		$statement = $this->pdo->prepare($query);

		if (!$statement->execute($params)) {
			throw new \Exception('data base error');
		}

		//$statement->setFetchMode(PDO::FETCH_CLASS, $this->className);
		return $statement->fetchAll();
	}

	/**
	 * 根据条件返回一个对象
	 *
	 * @param string $where
	 * @param array $params
	 * @param string $fields
	 *
	 * @return object
	 */
	public function fetchEntity($where = '1', $params = null, $fields = '*', $orderBy = null)
	{
		$query = "SELECT {$fields} FROM {$this->getLibName()} WHERE {$where}";

		if ($orderBy) {
			$query .= " order by {$orderBy}";
		}

		$query .= " limit 1";

		$timeStart = microtime(true);
		$statement = $this->pdo->prepare($query);
		$statement->execute($params);
		$statement->setFetchMode(PDO::FETCH_CLASS, $this->className);

		return $statement->fetch();
	}

	/**
	 * 根据唯一标识从数据库中载入实体对象
	 *
	 * @param array $ |mixed $id 主键值，如果是复合主键请用数组。
	 *
	 * @return Object
	 */
	public function load($id, $fields = "*")
	{
		$pk = $this->getPK();
		if (is_array($id)) {
			$conditions = array_map(__CLASS__ . '::conditionMap', $pk);
			$where = implode(' and ', $conditions);
		}
		else {
			$where = "`" . $pk . "` = ?";
			$id = [ $id ];
		}

		return $this->fetchEntity($where, $id, $fields);
	}

	/**
	 * 添加 fetchRowById 这样的方法，Id 是字段名
	 *
	 * @param string $methodName 调用的方法名字
	 * @param array $arguments 传递给方法的参数
	 *
	 * @return
	 */
	public function __call($methodName, $arguments)
	{
		if (preg_match("/^(fetch(Value|Entity|All))By(.+)$/", $methodName, $matches)) {
			$where = "`" . strtolower($matches[3]) . "`=?";
			$srcMethod = $matches[1];
			$params = [ $arguments[0] ];
			$fields = count($arguments) > 1 ? $arguments[1] : '*';

			return $this->$srcMethod($where, $params, $fields);
		}
		else {
			throw new \Exception('Method not found');
		}
	}

	/**
	 * 取得符合条件的第一条记录的第一个值
	 *
	 * @param string $where
	 * @param array $params
	 * @param string $fields
	 *
	 * @return unknown
	 */
	public function fetchValue($where = '1', $params = null, $fields = '*')
	{
		$query = "SELECT " . $fields . " FROM " . $this->getLibName() . " WHERE " . $where . " limit 1";
		$timeStart = microtime(true);
		$statement = $this->pdo->prepare($query);
		$statement->execute($params);

		return $statement->fetchColumn();
	}

	/**
	 * 删除符合条件的记录
	 *
	 * @param string $where
	 * @param array $params
	 */
	public function remove($where, $params)
	{
		if (empty($where)) {
			return false;
		}

		$query = "DELETE FROM {$this->getLibName()} WHERE {$where}";
		$timeStart = microtime(true);
		$statement = $this->pdo->prepare($query);
		$result = $statement->execute($params);

		return $result;
	}

	public static function conditionMap($field)
	{
		return '`' . $field . '`=?';
	}

	public static function updateFieldMap($field)
	{
		return '`' . $field . '`=:' . $field;
	}

	public static function changeFieldMap($field)
	{
		return '`' . $field . '`=`' . $field . '`+:' . $field;
	}

	/**
	 * 获取最后插入ID
	 */
	public function lastInsertId()
	{
		return $this->pdo->lastInsertId();
	}

	/**
	 * 执行SQL语句
	 *
	 * @param string $query
	 * @param array $params
	 *
	 * @return unknown
	 */
	public function query($query, $params = null, $args = null)
	{
		$query = trim($query);
		$mode = strtoupper(substr($query, 0, strpos($query, ' ')));
		$allowModes = [ 'SELECT', 'UPDATE', 'DELETE', 'REPLACE', 'INSERT' ];
		$affectModes = [ 'UPDATE', 'DELETE', 'REPLACE', 'INSERT' ];
		if (!in_array($mode, $allowModes)) {
			throw new \Exception('Query not allow');
		}
		$timeStart = microtime(true);
		$statement = $this->pdo->prepare($query);
		$result = $statement->execute($params);
		if ($args == 'statement') {
			return $statement;
		}
		if (in_array($mode, $affectModes)) {
			if ($args == 'lastInsertId') {
				return $this->pdo->lastInsertId();
			}
			else if ($args == 'rowCount') {
				return $statement->rowCount();
			}
			else {
				return $result;
			}
		}
		else {
			$statement->setFetchMode(PDO::FETCH_ASSOC);
			if ($args == 'column') {
				return $statement->fetchColumn();
			}
			else if ($args == 'fetch') {
				return $statement->fetch();
			}
			else {
				return $statement->fetchAll();
			}
		}
	}

	public function exec($sql, $mode = '')
	{
		$statement = $this->pdo->prepare($sql);
		$result = $statement->execute();
		if ('fetch' === $mode) {
			return $statement->fetch();
		}

		return $result;
	}

	public function beginTransaction()
	{
		$this->pdo->beginTransaction();
	}

	public function rollBack()
	{
		$this->pdo->rollBack();
	}

	public function commit()
	{
		$this->pdo->commit();
	}

}
