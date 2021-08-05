<?php

namespace service;

use entity\user;
use framework\util\Singleton;

class LoginService extends BaseService{


    /**
     * LoginService constructor.
     */
    public function __construct()
    {
    }

    public function checkParam($uid)
    {
        if (!isset($uid) || empty($uid)) {
            return [false, 'lack_of_uid'];
        }
        return [true, 'ok'];
    }


    public function login($uid){
        /** @var SampleService $sampleService */
        $sampleService = Singleton::get(SampleService::class);
        //查询用户是否已注册
        $relist=$sampleService->query("SELECT * FROM user");
        //die(var_dump($relist));
        foreach ($relist as $key=>$value){
            if($value[id]==$uid){
                return  parent::show(
                    200,
                    'ok',
                    $value
                );
            }else{
                //创建时间
                $date = date('Y-m-d H:i:s');
                $name=$this->getRandomString(4);
                $user=new user($name,2000,200,$date,$date);
                $fields=array('name','coin','diamond','creatTime','updateTime','hero');
                //插入用户，返回id
                $reid=$sampleService->add($user,$fields);
                $result=$sampleService->query("SELECT * FROM user WHERE id = '$reid'");//id,coin,diamond
                return parent::show(
                    200,
                    'ok',
                    $result
                );
            }
        }

    }

    /**
     * 生成随机用户名
     * @param $len
     * @param null $chars
     * @return string
     */
    function getRandomString($len, $chars = null)
    {
        if (is_null($chars)) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        }
        mt_srand(10000000 * (double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars) - 1; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }
        return 'user_'.$str;
    }
}
