<?php

namespace entity;

class userData{

    var $coin;
    var $diamond;
    var $props;
    var $hero;
    var $soldier;
    var $updateTime;

    /**
     * userData constructor.
     * @param $coin
     * @param $diamond
     * @param $props
     * @param $hero
     * @param $soldier
     * @param $updateTime
     */
    public function __construct($coin, $diamond, $props, $hero, $soldier, $updateTime)
    {
        $this->coin = $coin;
        $this->diamond = $diamond;
        $this->props = $props;
        $this->hero = $hero;
        $this->soldier = $soldier;
        $this->updateTime = $updateTime;
    }


}