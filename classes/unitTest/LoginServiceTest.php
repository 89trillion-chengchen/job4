<?php

namespace service;

use framework\util\Singleton;
use PHPUnit\Framework\TestCase;
use view\JsonView;

class LoginServiceTest extends TestCase
{

    public function testLogin()
    {

        /** @var LoginService $loginService */
        $loginService = Singleton::get(LoginService::class);
        echo new JsonView($loginService->login('1'));
    }
}
