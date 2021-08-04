<?php
/**
 * @author: ChengRennt <ChengRennt@gmail.com>
 * @created: 2014-2-26 下午2:58:03
 * @description:
 * $Id: BaseService.php 891 2014-03-12 13:15:23Z pengcheng2 $
 */

namespace service;

use entity;
use dao;
use framework\util;

/**
 *
 * @package Service
 */
class BaseService
{
	protected $dataDao;

	protected function __construct($className)
	{
		$this->dataDao = util\Singleton::get($className);

		return $this->dataDao;
	}


    function show($status,$msg='',$data=[]) {

        $result = [
            'status'=>intval($status),
            'msg'=>$msg
        ];

        if(!empty($data)|| $data == 0){
            $result['data'] = $data;
        }

        return $result;
    }

    function showResule($status,$msg='',$codeInfo=[],$useList=[]) {

        $result = [
            'status'=>intval($status),
            'msg'=>$msg
        ];

        if(!empty($codeInfo)|| $codeInfo == 0){
            $result['codeInfo'] = $codeInfo;

        }
        if(!empty($useList)|| $useList == 0){
            $result['useList'] = $useList;

        }

        return $result;
    }

}
