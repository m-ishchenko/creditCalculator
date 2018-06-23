<?php

namespace img\tests\credit_calculator;

use img\credit_calculator\services\Deferred;


/**
 * Class CascoTest
 */
class DeferredTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Процентная ставка Отложенного платежа
     */
    private $deferredPercentages = 20;

    /**
     * @dataProvider deferredExceptionsProvider
     * Проверка бросаемых классом исключений
     */
    public function testDeferredExceptions($needDeferred, $deferredPercentages)
    {
        $this->expectException(\InvalidArgumentException::class);
        return new Deferred($needDeferred, $deferredPercentages);
    }

    /**
     * Значения, используемые при тестировании исключений
     * @return array
     */
    public function deferredExceptionsProvider()
    {
        return [
            [1, 0],
            [1, 'str'],
        ];
    }
}