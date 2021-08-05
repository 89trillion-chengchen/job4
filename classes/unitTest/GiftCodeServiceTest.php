<?php

namespace service;

use framework\util\Singleton;
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
        echo new \framework\mvc\view\JSONView($giftCodeService->useCode($uid,$role,$code));
    }
}
