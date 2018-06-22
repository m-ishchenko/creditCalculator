<?php

use img\credit_calculator\Base;
use img\credit_calculator\Insurance;

/**
 * Class InsuranceTest
 */
class InsuranceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Сумма кредита
     */
    const CREDIT_AMOUNT = 3595347;

    /**
     * Стоимость СЖ (1-й год)
     */
    const INSURANCE_PRICE = 611209;

    /**
     * Процентная ставка СЖ
     */
    const INSURANCE_PERCENTAGES = 17;

    /**
     * @dataProvider insurancePriceProvider
     * Проверка расчета стоимости СЖ
     */
    public function testSetInsurancePrice($needInsurance, $insurancePercentages, $creditAmount, $insurancePrice)
    {
        $insurance = new Insurance($needInsurance, $insurancePercentages);
        $this->assertEquals($insurancePrice, Base::setRoundedValue($insurance->setInsurancePrice($creditAmount)));
    }

    /**
     * @dataProvider insuranceExceptionsProvider
     * Проверка бросаемых классом исключений
     */
    public function testInsuranceExceptions($needInsurance, $insurancePercentages)
    {
        $this->expectException(\InvalidArgumentException::class);
        return new Insurance($needInsurance, $insurancePercentages);
    }

    /**
     * @dataProvider insuranceInitProvider
     * Проверка процентной ставки СЖ
     */
    public function testInsurancePercentages($needInsurance, $insurancePercentages)
    {
        $insurance = new Insurance($needInsurance, $insurancePercentages);
        $this->assertEquals(static::INSURANCE_PERCENTAGES, $insurance->getInsurancePercentages());
    }

    /**
     * @dataProvider insuranceInitProvider
     * Проверка необходимости учета СЖ
     */
    public function testIsNeedInsurance($needInsurance, $insurancePercentages)
    {
        $insurance = new Insurance($needInsurance, $insurancePercentages);
        $this->assertInternalType('bool', $insurance->isNeedInsurance());
    }

    /**
     * Значения, используемые при тестировании расчета стоимости КАСКО
     * @return array
     */
    public function insurancePriceProvider()
    {
        return [
            [1, static::INSURANCE_PERCENTAGES, static::CREDIT_AMOUNT, static::INSURANCE_PRICE],
            [0, static::INSURANCE_PERCENTAGES, static::CREDIT_AMOUNT, static::INSURANCE_PRICE],
        ];
    }

    /**
     * Значения, используемые при тестировании исключений
     * @return array
     */
    public function insuranceExceptionsProvider()
    {
        return [
            [1, 0],
            [1, 'str'],
        ];
    }

    /**
     * Значения, используемые при тестировании необходимости расчета СЖ
     * @return array
     */
    public function insuranceInitProvider()
    {
        return [
            [true, static::INSURANCE_PERCENTAGES],
            [false, static::INSURANCE_PERCENTAGES],
        ];
    }
}