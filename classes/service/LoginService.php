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

    public function test(){
        /** @var SampleService $sampleService */
        $sampleService = Singleton::get(SampleService::class);
        //查询用户物品
        $uid=1;


        /** @var CacheService $cacheService */
        $cacheService = Singleton::get(CacheService::class);

        //获取礼品码redis数据
        $redisArray=$cacheService->getAllHash('code_O0afeWfR');

        $content=array();
        foreach ($redisArray as $key=>$value){
            if(substr($key,0,8)=='content_'){

                $content[substr($key,8,strlen($key))]=$value;
                //array_push($content,substr($key,8,strlen($key)),$value);
            }
        }


        $sql="INSERT INTO `user_thing` (uid,hero,soldier,props) VALUES ('$uid','$content[hero]','$content[soldier]','$content[props]')";
        $inserResult=$sampleService->query($sql);

        $relist=$sampleService->query("SELECT * FROM user_thing where uid = '$uid'");
        $hero=array();
        $soldier=array();
        $props=array();
        foreach ($relist as $key=>$value){
            array_push($hero,$value[hero]);
            array_push($soldier,$value[soldier]);
            array_push($props,$value[props]);
        }

        //查询更新后的数据
        $finllyresult=$sampleService->query("SELECT * FROM user WHERE id = '$uid'");

        //合并数据
        $finllyresult[0]['hero']=$hero;
        $finllyresult[0]['soldier']=$soldier;
        $finllyresult[0]['props']=$props;

        die(var_dump($finllyresult[0]));
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
