<?php

namespace service;

require "../../lib/raftphp/framework/data/redis/RedisConfiguration.php";
require "../../lib/raftphp/framework/data/redis/RedisHelper.php";
require "../../lib/raftphp/framework/data/redis/RedisManager.php";
require "../../lib/raftphp/framework/util/Singleton.php";
require "../../lib/raftphp/framework/data/pdo/PDOConfiguration.php";
require "../../lib/raftphp/framework/data/pdo/PDOManager.php";
require "../../lib/raftphp/framework/data/pdo/PDOHelper.php";
require "../dao/DaoBase.php";
require "../dao/ConstDaoBase.php";
require "../dao/CacheDao.php";
require "../dao/SampleDao.php";
require "../service/BaseService.php";
require "../service/CacheService.php";
require "../service/SampleService.php";
require "../service/GiftCodeService.php";

use framework\util\Singleton;
use framework\data\pdo\PDOConfiguration;
use framework\data\pdo\PDOManager;
use framework\data\pdo\PDOHelper;
use dao\DaoBase;
use dao\ConstDaoBase;
use dao\SampleDao;
use service\BaseService;
use service\SampleService;
use service\GiftCodeService;
use PHPUnit\Framework\TestCase;

class GiftCodeServiceTest extends TestCase
{

    public function testUseCode()
    {
        $uid='1';
        $role=1;
        $code='code_O0afeWfR';

        /** @var GiftCodeService $giftCodeService */
        $giftCodeService = Singleton::get(GiftCodeService::class);
        print_r($giftCodeService->useCode($uid,$role,$code));
    }
}
