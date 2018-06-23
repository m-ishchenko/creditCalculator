<?php

namespace img\credit_calculator;

use img\credit_calculator\base\Base;

/**
 * Реализует базовые свойства и методы кредитного калькулятора
 * 
 * @author Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @package  Cars Credit Calculator
 * @copyright Maxim Ishchenko <maxim.ishchenko@gmail.com>
 * @license BSD-3-Clause https://opensource.org/licenses/BSD-3-Clause
 * @version 1.1
 */
abstract class BaseCreditCalculator
{
	/**
	 * Процент от цены для расчета суммы КАСКО
	 * 
	 * @access  protected
	 * @var integer процент стоимости а/м
	 */
	protected $cascoPercentages;

	/**
	 * Учитывать КАСКО
	 * 
	 * @access protected
	 * @var integer необходимость учитывать КАСКО
	 */
	protected $needCasco;

	/**
	 * Стоимость КАСКО
	 * 
	 * @access protected
	 * @var float стоимость КАСКО, руб.
	 */
	protected $cascoPrice;

	/**
	 * Учитывать страхование жизни
	 * 
	 * @access  protected
	 * @var boolean
	 */
	protected $needInsurance;

	/**
	 * Процент от стоимости а/м для расчета суммы страхования жизни
	 * 
	 * @access  protected
	 * @var integer процент стоимости а/м, %
	 */
	protected $insurancePercentages;

	/**
	 * Сумма страхования жизни, руб
	 * 
	 * @access  protected
	 * @var float страхование жизни, руб
	 */
	protected $insurancePrice;

	/**
	 * Учитывать отложенный платеж
	 * 
	 * @access  protected
	 * @var boolean необходимость учитывать отложенный платеж
	 */
	protected $needDeferred;

	/**
	 * Процент от первоначальной стоимости а/м для расчета суммы отложенного платежа, %
	 * 
	 * @access  protected
	 * @var integer процент стоимости а/м, %
	 */
	protected $deferredPercentages;

	/**
	 * Сумма отложенного платежа, руб
	 * 
	 * @access protected
	 * @var float сумма отложенного платежа, руб
	 */
	protected $deferredPrice;

	/**
	 * Сумма процентов отложенного платежа
	 * 
	 * @access protected
	 * @var float сумма, подлежащая уплате по процентам отложенного платежа, руб
	 */
	protected $deferredPercentagesPrice;

	/**
	 * Итоговая стоимость а/м
	 * 
	 * @access protected
	 * @var float стоимость а/м, руб
	 */
	protected $carPrice;

	/**
	 * Размер первоначального взноса, %
	 * 
	 * @access protected
	 * @var integer первоначальный взнос, %
	 */
	protected $firstPaymentPercentage;

	/**
	 * Срок кредита, мес
	 * 
	 * @access protected
	 * @var integer срок кредита, мес
	 */
	protected $creditTime;

	/**
	 * Размер процентной ставки по кредиту
	 * 
	 * @access protected
	 * @var integer процентная ставка, %
	 */
	protected $interestRate;

	/**
	 * Расчет числового коэффициента значения процентной ставки
	 * 
	 * @access protected
	 * @return float числовое значение процентной ставки
	 */
	protected function getInterestRateNumeric() {
		return $this->getInterestRate() / Base::PERCENTAGES_100;
	}

	/**
	 * Расчет месячной процентной ставки по кредиту
	 * 
	 * @access protected
	 * @return float месячная процентная ставка по кредиту
	 */
	protected function getMonthlyPercentages() {
		return $this->getInterestRateNumeric() / Base::MONTHS_IN_YEAR;
	}

	/**
	 * Расчет коэффициента аннуитета
	 * 
	 * @access protected
	 * @return float коэффициент аннуитета
	 */
	protected function getAnnuityCoefficient() {
		$i = pow(1 + $this->getMonthlyPercentages(), $this->creditTime);
		return ($this->getMonthlyPercentages() * $i) / ($i - 1);
	}
}