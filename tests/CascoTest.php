<?php

namespace img\tests\credit_calculator;

use img\credit_calculator\services\Casco;

/**
 * Class CascoTest
 */
class CascoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Стоимость а/м
     */
    private $carPrice = 5100000;

    /**
     * Стоимость КАСКО (1-й год)
     */
    private $cascoPrice = 36210;

    /**
     * Процентная ставка КАСКО
     */
    private $cascoPercentages = 0.71;

    /**
     * @dataProvider cascoPriceProvider
     * Проверка расчета стоимости КАСКО
     */
    public function testSetCascoPrice($needCasco, $cascoPercentages, $carPrice, $cascoPrice)
    {
        $casco = new Casco($needCasco, $cascoPercentages);
        $this->assertEquals($cascoPrice, $casco->setPrice($carPrice));
    }

    /**
     * @dataProvider cascoExceptionsProvider
     * Проверка бросаемых классом исключений
     */
    public function testCascoExceptions($needCasco, $cascoPercentages)
    {
        $this->expectException(\InvalidArgumentException::class);
        return new Casco($needCasco, $cascoPercentages);
    }

    /**
     * @dataProvider cascoInitProvider
     * Проверка процентной ставки КАСКО
     */
    public function testCascoPercentages($needCasco, $cascoPercentages)
    {
        $casco = new Casco($needCasco, $cascoPercentages);
        $this->assertEquals($this->cascoPercentages, $cascoPercentages);
    }

    /**
     * @dataProvider cascoInitProvider
     * Проверка необходимости учета КАСКО
     */
    public function testIsNeedCasco($needCasco, $cascoPercentages)
    {
        $casco = new Casco($needCasco, $cascoPercentages);
        $this->assertInternalType('bool', $casco->isNeedable());
    }

    //


    /**
     * Значения, используемые при тестировании расчета стоимости КАСКО
     * @return array
     */
    public function cascoPriceProvider()
    {
        return [
            [1, $this->cascoPercentages, $this->carPrice, $this->cascoPrice],
            [0, $this->cascoPercentages, $this->carPrice, $this->cascoPrice],
        ];
    }

    /**
     * Значения, используемые при тестировании исключений
     * @return array
     */
    public function cascoExceptionsProvider()
    {
        return [
            [1, 0],
            [1, 'str'],
        ];
    }

    /**
     * Значения, используемые при тестировании необходимости расчета КАСКО
     * @return array
     */
    public function cascoInitProvider()
    {
        return [
            [true, $this->cascoPercentages],
            [false, $this->cascoPercentages],
        ];
    }
}