<?php

namespace img\tests\credit_calculator;

use img\credit_calculator\base\Base;
use img\credit_calculator\services\Insurance;

/**
 * Class InsuranceTest
 */
class InsuranceTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Сумма кредита
     */
    private $creditAmount = 3595347;

    /**
     * Стоимость СЖ (1-й год)
     */
    private $insurancePrice = 611209;

    /**
     * Процентная ставка СЖ
     */
    private $insurancePercentages = 17;

    /**
     * @dataProvider insurancePriceProvider
     * Проверка расчета стоимости СЖ
     */
    public function testSetInsurancePrice($needInsurance, $insurancePercentages, $creditAmount, $insurancePrice)
    {
        $insurance = new Insurance($needInsurance, $insurancePercentages);
        $this->assertEquals($insurancePrice, Base::setRoundedValue($insurance->setPrice($creditAmount)));
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
        $this->assertEquals($this->insurancePercentages, $insurance->getPercentages());
    }

    /**
     * @dataProvider insuranceInitProvider
     * Проверка необходимости учета СЖ
     */
    public function testIsNeedInsurance($needInsurance, $insurancePercentages)
    {
        $insurance = new Insurance($needInsurance, $insurancePercentages);
        $this->assertInternalType('bool', $insurance->isNeedable());
    }

    /**
     * Значения, используемые при тестировании расчета стоимости КАСКО
     * @return array
     */
    public function insurancePriceProvider()
    {
        return [
            [1, $this->insurancePercentages, $this->creditAmount, $this->insurancePrice],
            [0, $this->insurancePercentages, $this->creditAmount, $this->insurancePrice],
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
            [true, $this->insurancePercentages],
            [false, $this->insurancePercentages],
        ];
    }
}