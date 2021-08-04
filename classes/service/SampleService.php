<?php
/**
 *
 */

namespace service;

use entity;
use dao;
use framework\util;
use utils\Functions;

class SampleService extends BaseService
{
	public function __construct()
	{
		$this->dataDao = parent::__construct("dao\\SampleDao");
	}

	public function fetchAll(){

	    return $this->dataDao->fetchAll();
    }
    public function add($entity, $fields){

        return $this->dataDao->add($entity, $fields);
    }

    public function query($query, $params = null, $args = null){
	    return $this->dataDao->query($query, $params, $args);
    }
}
