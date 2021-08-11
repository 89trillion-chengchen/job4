<?php
namespace entity;
class user{

    var $name;
    var $coin;
    var $diamond;
    var $creatTime;
    var $updateTime;

    /**
     * user constructor.
     * @param $name
     * @param $coin
     * @param $diamond
     * @param $creatTime
     * @param $updateTime
     */
    public function __construct($name, $coin, $diamond, $creatTime, $updateTime)
    {
        $this->name = $name;
        $this->coin = $coin;
        $this->diamond = $diamond;
        $this->creatTime = $creatTime;
        $this->updateTime = $updateTime;
    }


}