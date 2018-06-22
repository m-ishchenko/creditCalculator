<?php
require __DIR__.'/../src/Casco.php';
require __DIR__.'/../src/Insurance.php';
require __DIR__.'/../src/Base.php';
require __DIR__.'/../src/Booleans.php';

use PHPUnit\Framework\TestCase;
use img\credit_calculator\Casco;


/**
 * Class CascoTest
 */
class CascoTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Стоимость а/м
     */
    const CAR_PRICE = 5100000;

    /**
     * Стоимость КАСКО (1-й год)
     */
    const CASCO_PRICE = 36210;

    /**
     * Процентная ставка КАСКО
     */
    const CASCO_PERCENTAGES = 0.71;

    /**
     * @dataProvider cascoPriceProvider
     * Проверка расчета стоимости КАСКО
     */
    public function testSetCascoPrice($needCasco, $cascoPercentages, $carPrice, $cascoPrice)
    {
        $casco = new Casco($needCasco, $cascoPercentages);
        $this->assertEquals($cascoPrice, $casco->setCascoPrice($carPrice));
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
        $this->assertEquals(static::CASCO_PERCENTAGES, $cascoPercentages);
    }

    /**
     * @dataProvider cascoInitProvider
     * Проверка необходимости учета КАСКО
     */
    public function testIsNeedCasco($needCasco, $cascoPercentages)
    {
        $casco = new Casco($needCasco, $cascoPercentages);
        $this->assertInternalType('bool', $casco->isNeedCasco());
    }

    /**
     * Значения, используемые при тестировании расчета стоимости КАСКО
     * @return array
     */
    public function cascoPriceProvider()
    {
        return [
            [1, static::CASCO_PERCENTAGES, static::CAR_PRICE, static::CASCO_PRICE],
            [0, static::CASCO_PERCENTAGES, static::CAR_PRICE, static::CASCO_PRICE],
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
            [true, static::CASCO_PERCENTAGES],
            [false, static::CASCO_PERCENTAGES],
        ];
    }
}