<?php

namespace service;

use framework\util\Singleton;
use PHPUnit\Framework\TestCase;

class OperationServiceTest extends TestCase
{

    public function testGetOperationResult()
    {
        /** @var OperationService $operationService */
        $operationService=Singleton::get(OperationService::class);
        echo $operationService->getOperationResult("1+3*4+8");
    }
}
