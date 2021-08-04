<?php
namespace framework\data\mongo;

use \MongoClient;

/**
 * Mongo数据处理类
 * 
 * @author zivn 
 * @package framework\data\Mongo
 */
class MongoHelper {
    /**
     * Mongo对象
     * 
     * @var \Mongo
     */
    private $Mongo;

    /**
     * 构造函数
     * 
     * @param bool $enable 
     */
    public function __construct($config) {
        /**
         * if ($enable)
         * {
         * $this->Mongo = MongoManager::getInstance();
         * }
         */
        if ($config) {
            $this->Mongo = MongoManager::getInstance($config);
        }
    }

    /**
     * 取得Mongo对象
     * 
     * @return \Mongo
     */
    function getMongo() {
        return $this->Mongo;
    }

    /**
     * 添加新数据（如存在则更新）
     */
    public function save($collection,$data) {
        return $this->Mongo->$collection ? $this->Mongo->$collection->save($data) : false;
    }
    
    public function command($data)
    {
    	return $this->Mongo ? $this->Mongo->command($data) : false;
    }
    
    public function remove($collection,$cond)
    {
    	return $this->Mongo->$collection ? $this->Mongo->$collection->remove($cond) : false;
    }
    
    public function find($collection,$cond,$fields=array())
    {
    	if(empty($fields)){
    		return $this->Mongo->$collection ? $this->Mongo->$collection->findOne($cond) : array();
    	}
    	else{
    		return $this->Mongo->$collection ? $this->Mongo->$collection->findOne($cond,$fields) : array();
    	}
    }
    
    public function findOne($collection,$cond,$fields=array())
    {
    	if(empty($fields)){
    		return $this->Mongo->$collection ? $this->Mongo->$collection->findOne($cond) : false;
    	}
    	else{
    		return $this->Mongo->$collection ? $this->Mongo->$collection->findOne($cond,$fields) : false;
    	}
    }

}

?>
