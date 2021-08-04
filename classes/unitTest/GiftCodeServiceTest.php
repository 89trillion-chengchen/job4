<?php

namespace service;

use framework\mvc\view\JSONView;
use framework\util\Singleton;
use service\GiftCodeService;
use PHPUnit\Framework\TestCase;


class GiftCodeServiceTest extends TestCase
{

    public function testCreatGiftCode()
    {

        $uid='1';
        $role=1;
        $code='code_O0afeWfR';

        /** @var GiftCodeService $giftCodeService */
        $giftCodeService = Singleton::get(GiftCodeService::class);
        echo new JSONView($giftCodeService->useCode($uid,$role,$code));

    }
}
