<?php

namespace service;

require "../../lib/raftphp/framework/util/Singleton.php";
require "../../lib/raftphp/framework/data/pdo/PDOConfiguration.php";
require "../../lib/raftphp/framework/data/pdo/PDOManager.php";
require "../../lib/raftphp/framework/data/pdo/PDOHelper.php";
require "../dao/DaoBase.php";
require "../dao/ConstDaoBase.php";
require "../dao/SampleDao.php";
require "../service/BaseService.php";
require "../service/SampleService.php";
require "../service/LoginService.php";

use framework\util\Singleton;
use framework\data\pdo\PDOConfiguration;
use framework\data\pdo\PDOManager;
use framework\data\pdo\PDOHelper;
use dao\DaoBase;
use dao\ConstDaoBase;
use dao\SampleDao;
use service\BaseService;
use service\SampleService;
use service\LoginService;
use PHPUnit\Framework\TestCase;



class LoginServiceTest extends TestCase
{

    public function testLogin()
    {
        /** @var LoginService $loginService */
        $loginService = Singleton::get(LoginService::class);
        print_r($loginService->login('1'));
    }
}
